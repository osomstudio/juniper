<?php

namespace Juniper\Blocks;

class JuniperBlocks {
	public string $blocks_dir;
	private array $blocks_list;

	public function __construct() {
		$this->blocks_dir  = get_template_directory() . '/views/blocks';
		$this->blocks_list = $this->get_all_blocks();

	}

	private function get_all_blocks() {
		return array_values( array_diff( scandir( $this->blocks_dir ), array( '..', '.', '.gitkeep' ) ) );
	}

	public function include_blocks_functions() {
		foreach ( $this->blocks_list as $single_block ) {
			require_once get_template_directory() . '/views/blocks/' . $single_block . '/functions.php';
		}
	}
}
