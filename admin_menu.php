<?php

function tpm_menu() {	
	add_submenu_page('elementor', 'Templementor', 'Templementor', 'install_plugins', 'edit.php?post_type=tpm_templates');	
}
add_action('admin_menu', 'tpm_menu', 98);




// fix to set the taxonomy and user pages as menu page sublevel
function tpm_menu_correction($parent_file) {
	global $current_screen;

	// hack for taxonomy
	if(isset($current_screen->post_type) && $current_screen->post_type == 'tpm_templates') {
		$parent_file = 'elementor';
	}
		
	return $parent_file;
}
add_action('parent_file', 'tpm_menu_correction', 100);


function tpm_submenu_correction($submenu_file, $parent_file) {
	global $current_screen;

	// hack for taxonomy
	if(isset($current_screen->post_type) && $current_screen->post_type == 'tpm_templates') {
		$submenu_file = 'edit.php?post_type=tpm_templates';	
	}
		
	return $submenu_file;
}
add_action('submenu_file', 'tpm_submenu_correction', 110, 2);
