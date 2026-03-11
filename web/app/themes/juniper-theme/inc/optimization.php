<?php

/**
 * Get cached ACF options page fields
 *
 * This function caches the ACF options to avoid multiple database queries
 * on each page load. Cache is invalidated when ACF options are saved.
 *
 * @param bool $use_cache Optional. Whether to use cache. Default false.
 * @return array The cached options or empty array if ACF is not available
 */
function juniper_get_cached_options( bool $use_cache = false ): array {
	if ( ! class_exists( 'ACF' ) ) {
		return array();
	}

	$use_cache = apply_filters( 'juniper_use_options_cache', $use_cache );

	if ( ! $use_cache ) {
		$fields = get_fields( 'options' );
		return $fields ? $fields : array();
	}

	$cache_key = 'juniper_acf_options';
	$options   = get_transient( $cache_key );

	if ( false === $options ) {
		$options = get_fields( 'options' );
		$options = $options ? $options : array();
		set_transient( $cache_key, $options, WEEK_IN_SECONDS );
	}

	return $options;
}

/**
 * Clear ACF options cache when options page is saved
 */
function juniper_clear_options_cache(): void {
	delete_transient( 'juniper_acf_options' );
}

add_action( 'acf/options_page/save', 'juniper_clear_options_cache', 20 );
