<?php

namespace Juniper\Settings;

/**
 * Native replacement for the ACF Options Page: a single "Juniper Settings"
 * admin page (Settings API), storing every field in one wp_options row.
 *
 * Add/remove fields via $fields below, then read a value anywhere with
 * JuniperSettings::get( 'key' ) - no extra caching layer is needed since
 * get_option() is already served from WP's object cache.
 */
class JuniperSettings {
	public const OPTION_NAME = 'juniper_settings';
	public const PAGE_SLUG   = 'juniper-settings';

	private array $fields;

	public function __construct() {
		$this->fields = array(
			'contact_email' => array(
				'label' => __( 'Contact Email', 'juniper-theme' ),
				'type'  => 'email',
			),
			'footer_text'   => array(
				'label' => __( 'Footer Text', 'juniper-theme' ),
				'type'  => 'textarea',
			),
			'logo'          => array(
				'label' => __( 'Logo', 'juniper-theme' ),
				'type'  => 'image',
			),
		);

		add_action( 'admin_menu', array( $this, 'register_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'maybe_enqueue_media' ) );
	}

	/**
	 * Read a single value from the Juniper settings option, mirroring the
	 * ergonomics of ACF's get_field( 'key', 'option' ).
	 */
	public static function get( string $key, mixed $fallback = '' ): mixed {
		$options = get_option( self::OPTION_NAME, array() );

		return $options[ $key ] ?? $fallback;
	}

	public function register_page(): void {
		add_options_page(
			__( 'Juniper Settings', 'juniper-theme' ),
			__( 'Juniper Settings', 'juniper-theme' ),
			'manage_options',
			self::PAGE_SLUG,
			array( $this, 'render_page' )
		);
	}

	public function register_settings(): void {
		register_setting(
			self::OPTION_NAME,
			self::OPTION_NAME,
			array( 'sanitize_callback' => array( $this, 'sanitize' ) )
		);

		add_settings_section( 'juniper_settings_main', '', '__return_false', self::OPTION_NAME );

		foreach ( $this->fields as $key => $field ) {
			add_settings_field(
				$key,
				$field['label'],
				array( $this, 'render_field' ),
				self::OPTION_NAME,
				'juniper_settings_main',
				array(
					'key'   => $key,
					'field' => $field,
				)
			);
		}
	}

	public function render_field( array $args ): void {
		$key   = $args['key'];
		$field = $args['field'];
		$value = self::get( $key );
		$name  = self::OPTION_NAME . "[$key]";

		if ( 'textarea' === $field['type'] ) {
			printf(
				'<textarea id="%1$s" name="%2$s" rows="4" class="large-text">%3$s</textarea>',
				esc_attr( $key ),
				esc_attr( $name ),
				esc_textarea( $value )
			);

			return;
		}

		if ( 'image' === $field['type'] ) {
			printf(
				'<input type="hidden" id="%1$s" name="%2$s" value="%3$s" class="juniper-settings-image-id">
				<div class="juniper-settings-image-preview">%4$s</div>
				<p>
					<button type="button" class="button juniper-settings-image-select">%5$s</button>
					<button type="button" class="button-link-delete juniper-settings-image-remove"%6$s>%7$s</button>
				</p>',
				esc_attr( $key ),
				esc_attr( $name ),
				esc_attr( $value ),
				$value ? wp_get_attachment_image( (int) $value, 'medium' ) : '',
				esc_html__( 'Select image', 'juniper-theme' ),
				$value ? '' : ' style="display:none"',
				esc_html__( 'Remove', 'juniper-theme' )
			);

			return;
		}

		printf(
			'<input type="%1$s" id="%2$s" name="%3$s" value="%4$s" class="regular-text">',
			esc_attr( $field['type'] ),
			esc_attr( $key ),
			esc_attr( $name ),
			esc_attr( $value )
		);
	}

	public function sanitize( array $input ): array {
		$sanitized = array();

		foreach ( $this->fields as $key => $field ) {
			if ( ! isset( $input[ $key ] ) ) {
				continue;
			}

			$sanitized[ $key ] = match ( $field['type'] ) {
				'textarea' => sanitize_textarea_field( $input[ $key ] ),
				'email'    => sanitize_email( $input[ $key ] ),
				'image'    => absint( $input[ $key ] ),
				default    => sanitize_text_field( $input[ $key ] ),
			};
		}

		return $sanitized;
	}

	public function render_page(): void {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Juniper Settings', 'juniper-theme' ); ?></h1>
			<form method="post" action="options.php">
				<?php
				settings_fields( self::OPTION_NAME );
				do_settings_sections( self::OPTION_NAME );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	public function maybe_enqueue_media( string $hook ): void {
		if ( 'settings_page_' . self::PAGE_SLUG !== $hook ) {
			return;
		}

		wp_enqueue_media();
		add_action( 'admin_footer', array( $this, 'print_media_script' ) );
	}

	/**
	 * Wires the "Select image"/"Remove" buttons to the native media
	 * library. Inlined here (rather than a compiled asset) since it is
	 * a few lines used on a single admin screen.
	 */
	public function print_media_script(): void {
		?>
		<script>
		jQuery( function ( $ ) {
			$( '.juniper-settings-image-select' ).on( 'click', function ( event ) {
				event.preventDefault();

				var $wrapper = $( this ).closest( 'td' );
				var $input   = $wrapper.find( '.juniper-settings-image-id' );
				var $preview = $wrapper.find( '.juniper-settings-image-preview' );
				var $remove  = $wrapper.find( '.juniper-settings-image-remove' );

				var frame = wp.media( {
					title: <?php echo wp_json_encode( __( 'Select image', 'juniper-theme' ) ); ?>,
					multiple: false,
				} );

				frame.on( 'select', function () {
					var attachment = frame.state().get( 'selection' ).first().toJSON();
					var previewUrl = ( attachment.sizes && attachment.sizes.medium )
						? attachment.sizes.medium.url
						: attachment.url;

					$input.val( attachment.id );
					$preview.html( '<img src="' + previewUrl + '" alt="">' );
					$remove.show();
				} );

				frame.open();
			} );

			$( '.juniper-settings-image-remove' ).on( 'click', function ( event ) {
				event.preventDefault();

				var $wrapper = $( this ).closest( 'td' );

				$wrapper.find( '.juniper-settings-image-id' ).val( '' );
				$wrapper.find( '.juniper-settings-image-preview' ).empty();
				$( this ).hide();
			} );
		} );
		</script>
		<?php
	}
}
