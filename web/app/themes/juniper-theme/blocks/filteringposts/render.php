<?php
/**
 * Filtering Posts block render template.
 *
 * @param array  $block      The block settings and attributes.
 * @param string $content    The block inner HTML (empty).
 * @param bool   $is_preview True during backend preview render.
 * @param int    $post_id    The post ID the block is rendered on.
 */
?>

<div class="filteringposts">
	<form class="testform">
		<input type="text" name="inputajax1" placeholder="<?php esc_attr_e( 'Field 1', 'juniper-theme' ); ?>">
		<input type="text" name="inputajax2" placeholder="<?php esc_attr_e( 'Field 2', 'juniper-theme' ); ?>">
		<input type="text" name="inputajax3" placeholder="<?php esc_attr_e( 'Field 3', 'juniper-theme' ); ?>">

		<button type="submit"><?php esc_html_e( 'Filter', 'juniper-theme' ); ?></button>
	</form>

	<div class="filteringposts__results"></div>
</div>
