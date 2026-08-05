<?php
/**
 * CTA block render template.
 *
 * @param array  $block      The block settings and attributes.
 * @param string $content    The block inner HTML (empty).
 * @param bool   $is_preview True during backend preview render.
 * @param int    $post_id    The post ID the block is rendered on.
 */

$heading = get_field( 'heading' );
$text    = get_field( 'text' );
?>

<div class="cta">
	<?php if ( $heading ) : ?>
		<h2 class="cta__heading"><?php echo esc_html( $heading ); ?></h2>
	<?php endif; ?>

	<?php if ( $text ) : ?>
		<div class="cta__text"><?php echo wp_kses_post( $text ); ?></div>
	<?php endif; ?>
</div>
