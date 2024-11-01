<?php
// REGISTERS METABOX TO APPLY TEMPLATE AND ADD APPLIED TEMPLATE COLUMN INTO POSTS LIST



// register metabox and column
function tpm_register_metaboxes() {
	
	$post_types = get_post_types_by_support('elementor');
	foreach($post_types as $post_type) {
		add_meta_box('tpm_metabox', 'Templementor', 'tpm_metabox', $post_type, 'side', 'default');	
		
		add_filter('manage_edit-'.$post_type.'_columns', 'tpm_post_list_helper_head'); 
		add_action('manage_'.$post_type.'_posts_custom_column', 'tpm_post_list_helper_content', 10, 2);
	}
	
	add_action('admin_head', 'tpm_post_list_helper_css', 100);
}
add_action('admin_init', 'tpm_register_metaboxes');





// show metabox
function tpm_metabox() {
	global $post;
	$sel = get_post_meta($post->ID, 'tpm_template', true);
	
	
	// get templates 
	$args = array(
		'post_type' 	=> 'tpm_templates',
		'numberposts' 	=> -1,
		'post_status' 	=> 'publish',
		'orderby'		=> 'title',
		'order'			=> 'ASC',
		'fields' 		=> 'ids'
	);
	$templates = get_posts($args);
	?>
    <div class="misc-pub-section" style="padding-top: 5px; padding-left: 0;">
      <label style="display: block; margin-bottom: 8px;"><?php _e('Apply template', 'tpm_ml') ?></label>
      
      <select name="tpm_template" autocomplete="off" style="width: 100%; margin: 0;">
        <option value="">(none)</option>
        <?php 
        foreach($templates as $tpl_id) {
            echo '<option value="'. $tpl_id .'" '. selected($tpl_id, $sel, false) .'>'. get_the_title($tpl_id) .'</option>'; 
        }
        ?>
      </select> 
    </div>  
    <?php	
    echo '<input type="hidden" name="tpm_nonce" value="' . wp_create_nonce(__FILE__) . '" />';
}


// save metabox
function tpm_save_meta($post_id) {
	if(isset($_POST['tpm_nonce']) && wp_verify_nonce($_POST['tpm_nonce'], __FILE__)) {
		
		if(empty($_POST['tpm_template'])) {
			delete_post_meta($post_id, 'tpm_template');
		} else {
			update_post_meta($post_id, 'tpm_template', $_POST['tpm_template']);
		}
	}
 
    return $post_id;
}
add_action('save_post','tpm_save_meta');



//////////////////////////////////////////////////////////////////////////////


// micro CSS code in head, styling column
function tpm_post_list_helper_css() {
	?>
    <style type="text/css">
	th.column-tpm,
	td.column-tpm {
		width: 120px;	
	}
	td.column-tpm span {
		 display: inline-block;
		 text-overflow: ellipsis;
		 overflow: hidden; 
		 width: 100%; 
		 white-space: nowrap;
	}
	td.column-tpm em {
		opacity: 0.7;	
	}
	</style>
    <?php
}


// posts list column head
function tpm_post_list_helper_head($columns) {
	$columns_local = array();
	
	if(!isset($columns_local['tpm'])) { 
		$columns_local['tpm'] = '<span title="'. addslashes(__('Which template is applied to this page?', 'tpm_ml')).'">Templementor</span>';
	}
	return array_merge($columns, $columns_local);
}
	

// posts list column content
function tpm_post_list_helper_content($column, $post_id) {
	if($column != 'tpm') {return false;}
	
	$template = get_post_meta($post_id, 'tpm_template', true);
	$txt = ($template) ? get_the_title($template) : '<em>'. __('none', 'tpm_ml') .'</em>';
	
	echo '<span clas="column-tpm-" title="'. $txt .'">'. $txt .'</span>';
}
