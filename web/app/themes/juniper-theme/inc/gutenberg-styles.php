<?php
add_action( 'init', 'juniper_register_blocks_styles' );
function juniper_register_blocks_styles() : void {
	register_block_style(
		'core/button',
		array(
			'name'  => 'arrowed',
			'label' => __( 'Arrowed', 'juniper' ),
		)
	);
}
