<?php

add_action('init', 'tpm_register_cpt');
function tpm_register_cpt() {

    $labels = array( 
        'name' => __('Templates', 'tpm_ml'),
        'singular_name' => __( 'Template', 'tpm_ml'),
        'add_new' => __( 'Add New Template', 'tpm_ml'),
        'add_new_item' => __( 'Add New Template', 'tpm_ml'),
        'edit_item' => __( 'Edit Template', 'tpm_ml'),
        'new_item' => __( 'New Template', 'tpm_ml'),
        'view_item' => __( 'View Template', 'tpm_ml'),
        'search_items' => __( 'Search Templates', 'tpm_ml'),
        'not_found' => __( 'No items found', 'tpm_ml'),
        'not_found_in_trash' => __( 'No items found in Trash', 'tpm_ml'),
        'parent_item_colon' => __( 'Parent Template:', 'tpm_ml'),
        'menu_name' => __( 'Media Grid', 'tpm_ml'),
    );

    $args = array( 
        'labels' => $labels,
        'hierarchical' => false,      
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => false,
        'show_in_nav_menus' => false,
        'publicly_queryable' => true,
        'exclude_from_search' => true,
        'has_archive' => false,
        'query_var' => false,
        'can_export' => true,
        'rewrite' => false,
		'supports' => array('title', 'elementor'),
        'capability_type' => 'page'
    );
	
    register_post_type('tpm_templates', $args);	
}



// avoid templates direct access on frontend if not logged as user that can manage templates
add_action('init', 'tpm_no_templates_reach', 1);
function tpm_no_templates_reach() {
	
	if(isset($_REQUEST['post_type']) && $_REQUEST['post_type'] == 'tpm_templates') {
		
		if(!current_user_can('install_plugins')) {
			wp_redirect( home_url() );
			die();	
		}
	}
}
