<?php

namespace Juniper\Cpt;

class Projects {
	public string $cpt_slug;
	public string $cpt_name;

	public function __construct() {
		$this->cpt_slug = substr( 'projects', 0, 20 );
		$this->cpt_name = substr( 'Projects', 0, 20 );

		add_action( 'init', array( $this, 'register_custom_cpt' ) );
		add_action( 'init', array( $this, 'register_taxonomy' ) );

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
				'has_archive' => false,
				'rewrite'     => array( 'slug' => $this->cpt_slug ),
			)
		);
	}

	public function register_taxonomy() {
			$labels = array(
				'name'              => _x( 'Project Categories', 'taxonomy general name' ),
				'singular_name'     => _x( 'Project Category', 'taxonomy singular name' ),
				'search_items'      => __( 'Search Project Categories' ),
				'all_items'         => __( 'All Project Categories' ),
				'parent_item'       => __( 'Parent Project Categories' ),
				'parent_item_colon' => __( 'Parent Project Categories:' ),
				'edit_item'         => __( 'Edit Project Category' ),
				'update_item'       => __( 'Update Project Category' ),
				'add_new_item'      => __( 'Add New Project Category' ),
				'new_item_name'     => __( 'New Project Category Name' ),
				'menu_name'         => __( 'Project Category' ),
			);
			$args   = array(
				'hierarchical'      => true, // make it hierarchical (like categories)
				'labels'            => $labels,
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => [ 'slug' => 'project-category' ],
			);
		 register_taxonomy( 'project-category', [ 'projects' ], $args );
	}

}
