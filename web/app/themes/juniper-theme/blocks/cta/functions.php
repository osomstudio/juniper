<?php

add_action(
	'wp_enqueue_scripts',
	function () {
		if ( has_block( 'acf/cta' ) ) {
			$version    = wp_get_theme()->get( 'Version' );
			$theme_path = get_template_directory_uri();

			wp_enqueue_style( 'cta-css', $theme_path . '/dist/blocks/cta/style.css', array(), $version, 'all' );
			wp_enqueue_script( 'cta-js', $theme_path . '/dist/blocks/cta/script.js', array(), $version, true );
		}
	}
);

add_action(
	'admin_init',
	function () {
		add_editor_style( '/dist/blocks/cta/style.css' );
	}
);

add_action(
	'acf/init',
	function () {
		if ( ! function_exists( 'acf_register_block_type' ) ) {
			return;
		}

		acf_register_block_type(
			array(
				'name'            => 'cta',
				'title'           => __( 'CTA', 'juniper-theme' ),
				'render_template' => __DIR__ . '/render.php',
				'category'        => 'formatting',
				'icon'            => 'admin-comments',
				'keywords'        => array(),
				'mode'            => 'edit',
				'align'           => 'full',
				'supports'        => array(
					'align'    => array( 'left', 'right', 'full' ),
					'mode'     => true,
					'multiple' => true,
				),
			)
		);
	}
);
