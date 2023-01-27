<?php

namespace Osom;

class OsomAjaxFilteringposts {

	const TEXT_FIELD     = 'text_field'; // sanitize_text_field.
	const TEXTAREA_FIELD = 'textarea_field'; // sanitize_textarea_field / wp_kses.
	const EMAIL_FIELD    = 'email_field';

	/**
	 * Ajax action name that will be used for JS action property and generating action name.
	 *
	 * @var string Ajax action name.
	 */
	public string $action_name;

	/**
	 * ACF Block name.
	 *
	 * @var string Block name.
	 */
	public string $block_name;

	/**
	 * Javascript file path that will be used for this ajax handler.
	 *
	 * @var string Javascript file path.
	 */
	private string $js_directory;

	private array $ajax_fields_types = array(
		'inputajax1' => self::TEXT_FIELD,
		'inputajax2' => self::TEXT_FIELD,
		'inputajax3' => self::TEXT_FIELD,
	);

	/**
	 * Array with input values after sanitization
	 *
	 * @var array
	 */
	private array $ajax_fields_sanitized = array();

	/**
	 * Ajax class constructor. Basic properties setting, ajax necessary actions registered, enqueue ajax script with localize.
	 *
	 * @param string $action_name Ajax action name.
	 * @param string $js_directory string Directory with js file to be used with this ajax module.
	 * @param string $block_name  Block name
	 */
	public function __construct( $action_name, $js_directory, $block_name ) {
		$this->action_name  = $action_name;
		$this->js_directory = $js_directory;
		$this->block_name   = $block_name;

		add_action( 'wp_ajax_' . $this->action_name, array( $this, 'ajax_action_handler' ) );
		add_action( 'wp_ajax_nopriv_' . $this->action_name, array( $this, 'ajax_action_handler' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_script' ) );
	}

	/**
	 * Main ajax handler method. Do not make spaghetti code - if you want to parse data create separate method.
	 *
	 * @return void wp_send_json always ends with wp_die
	 */
	public function ajax_action_handler() {
		// We are setting up input names and their type in $ajax_fields_types first to sanitize them automatically.
		$this->parse_ajax_fields();

		// Custom methods to parse data.

		/**
		 * Method needs to end with data sent to JS. If error occurs during the processing of
		 * method, we need to send $this->send_ajax_error().
		 */
		$this->send_ajax_success( $this->ajax_fields_sanitized );
	}

	/**
	 * Methods used to register necessary scripts and localize them.
	 *
	 * @return void
	 */
	public function register_script() {
		if ( has_block( 'acf/' . $this->block_name ) ) {
			wp_enqueue_script( 'ajax-' . $this->action_name, $this->js_directory, array( 'jquery' ), time(), true );
			wp_localize_script(
				'ajax-' . $this->action_name,
				'ajax',
				array(
					'ajax_url'    => admin_url( 'admin-ajax.php' ),
					'action_name' => $this->action_name,
					'nonce'       => wp_create_nonce( $this->action_name ),
				)
			);
		}
	}

	/**
	 * Validation method for fields used in ajax request - $this->ajax_fields_sanitized
	 *
	 *
	 * @return void
	 */
	private function parse_ajax_fields() {
		if ( ! check_ajax_referer( $this->action_name, 'nonce' ) ) {
			$this->send_ajax_error( __( 'Unauthorized request. Try again later.' ) );
		}

		foreach ( $this->ajax_fields_types as $field_name => $sanitization_type ) {
			if ( ! isset( $_POST[ $field_name ] ) ) {
				continue;
			}

			switch ( $sanitization_type ) {
				case self::TEXT_FIELD:
					$this->ajax_fields_sanitized[ $field_name ] = sanitize_text_field( wp_unslash( $_POST[ $field_name ] ) );
					break;
				case self::TEXTAREA_FIELD:
					$this->ajax_fields_sanitized[ $field_name ] = sanitize_textarea_field( wp_unslash( $_POST[ $field_name ] ) );
					break;
				case self::EMAIL_FIELD:
					$this->ajax_fields_sanitized[ $field_name ] = sanitize_email( wp_unslash( $_POST[ $field_name ] ) );
					break;
				default:
					$this->ajax_fields_sanitized[ $field_name ] = sanitize_text_field( wp_unslash( $_POST[ $field_name ] ) );
			}
		}
	}

	/**
	 * Universal method for returning request error data.
	 *
	 * @param string $error_msg Error message, remember to use __() function.
	 * @return void wp_send_json ends with wp_die.
	 */
	private function send_ajax_error( string $error_msg ) {
		wp_send_json(
			array(
				'result' => false,
				'msg'    => $error_msg,
			)
		);
	}

	/**
	 * @param array $data Array with response data.
	 * @return void wp_send_json ends with wp_die.
	 */
	private function send_ajax_success( array $data ) {
		wp_send_json(
			array(
				'result' => true,
				'data'   => $data,
			)
		);
	}

}

