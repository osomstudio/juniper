<?php
/**
 * CTA block render template.
 *
 * @param array    $attributes Block attributes (heading, text).
 * @param string   $content    The block inner HTML (empty).
 * @param WP_Block $block      Block instance.
 */

$heading = $attributes['heading'] ?? '';
$text    = $attributes['text'] ?? '';
?>

<div <?php echo get_block_wrapper_attributes( array( 'class' => 'cta' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already returns an escaped attribute string. ?>>
	<?php if ( $heading ) : ?>
		<h2 class="cta__heading"><?php echo wp_kses_post( $heading ); ?></h2>
	<?php endif; ?>

	<?php if ( $text ) : ?>
		<div class="cta__text"><?php echo wp_kses_post( $text ); ?></div>
	<?php endif; ?>
</div>
