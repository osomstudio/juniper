<?php

add_action( 'init', 'remove_core_patterns' );
function remove_core_patterns() : void {
	remove_theme_support( 'core-block-patterns' );
}

register_block_pattern_category(
	'hero',
	array( 'label' => __( 'Hero', 'juniper-theme' ) )
);