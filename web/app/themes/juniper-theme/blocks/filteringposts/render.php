<?php
/**
 * Filtering Posts block render template.
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    The block inner HTML (empty).
 * @param WP_Block $block      Block instance.
 */
?>

<div <?php echo get_block_wrapper_attributes( array( 'class' => 'filteringposts' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already returns an escaped attribute string. ?>>
	<form class="testform">
		<input type="text" name="inputajax1" placeholder="<?php esc_attr_e( 'Field 1', 'juniper-theme' ); ?>">
		<input type="text" name="inputajax2" placeholder="<?php esc_attr_e( 'Field 2', 'juniper-theme' ); ?>">
		<input type="text" name="inputajax3" placeholder="<?php esc_attr_e( 'Field 3', 'juniper-theme' ); ?>">

		<button type="submit"><?php esc_html_e( 'Filter', 'juniper-theme' ); ?></button>
	</form>

	<div class="filteringposts__results"></div>
</div>
