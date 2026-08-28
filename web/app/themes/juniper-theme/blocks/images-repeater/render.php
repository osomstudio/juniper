<?php
/**
 * Images Repeater block render template.
 *
 * @param array    $attributes Block attributes (images).
 * @param string   $content    The block inner HTML (empty).
 * @param WP_Block $block      Block instance.
 */

use Juniper\Fields\BlockFields;

$images = BlockFields::rows( $attributes['images'] ?? null );
?>

<div <?php echo get_block_wrapper_attributes( array( 'class' => 'images-repeater' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already returns an escaped attribute string. ?>>
	<?php if ( $images ) : ?>
		<ul class="images-repeater__list">
			<?php foreach ( $images as $row ) : ?>
				<?php
				$image   = BlockFields::image( $row['image'] ?? null );
				$caption = BlockFields::row_value( $row, 'caption' );
				?>
				<?php if ( $image ) : ?>
					<li class="images-repeater__item">
						<figure class="images-repeater__figure">
							<img
								src="<?php echo esc_url( $image['url'] ); ?>"
								alt="<?php echo esc_attr( $image['alt'] ); ?>"
								class="images-repeater__image"
							>
							<?php if ( $caption ) : ?>
								<figcaption class="images-repeater__caption"><?php echo esc_html( $caption ); ?></figcaption>
							<?php endif; ?>
						</figure>
					</li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</div>
