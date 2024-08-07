<?php

function juniper_disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}

add_action( 'init', 'juniper_disable_emojis' );

add_filter( 'xmlrpc_enabled', '__return_false' );


function juniper_remove_wp_version() {
	return '';
}
add_filter( 'the_generator', 'juniper_remove_wp_version' );
remove_action( 'wp_head', 'wp_generator' );
