<?php


add_filter(
	'timber/acf-gutenberg-blocks-data/test-block',
	function( $context ) {
		$context['xyz'] = 'QWE';

		return $context;
	}
);
