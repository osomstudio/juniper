<?php

namespace Juniper\Cpt;

class Services {
	public string $cpt_slug;
	public string $cpt_name;

	public function __construct() {
		$this->cpt_slug = substr( 'services', 0, 20 );
		$this->cpt_name = substr( 'Services', 0, 20 );

		add_action( 'init', array( $this, 'register_custom_cpt' ) );
	}

	public function register_custom_cpt() {
		register_post_type(
			$this->cpt_slug,
			array(
				'labels'      => array(
					'name'          => __( $this->cpt_name ),
					'singular_name' => __( $this->cpt_name, ),
				),
				'public'      => true,
				'has_archive' => true,
				'rewrite'     => array( 'slug' => $this->cpt_slug ),
			)
		);
	}

}
