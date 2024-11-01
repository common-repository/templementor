<?php
/* 
Plugin Name: Templementor - by LCweb
Plugin URI: https://wordpress.org/plugins/templementor/
Description: Create persistent templates with Elementor to shape up and edit your pages in minutes
Author: Luca Montanari
Version: 1.0.1
Author URI: https://lcweb.it
*/  



/////////////////////////////////////////////
/////// MAIN DEFINES ////////////////////////
/////////////////////////////////////////////

// plugin path
$wp_plugin_dir = substr(plugin_dir_path(__FILE__), 0, -1);
define('TPM_DIR', $wp_plugin_dir);

// plugin url
$wp_plugin_url = substr(plugin_dir_url(__FILE__), 0, -1);
define('TPM_URL', $wp_plugin_url);





/////////////////////////////////////////////
/////// FORCING DEBUG ///////////////////////
/////////////////////////////////////////////

if(isset($_REQUEST['tpm_php_debug'])) {
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);	
}





/////////////////////////////////////////////
/////// DONATION LINK ///////////////////////
/////////////////////////////////////////////

function tpm_plugin_action_links( $links ) {
	$links['tpm_donation'] = sprintf(
		'<a href="%s" target="_blank" style="color: #3eac4d; font-weight: 700; display: inline; letter-spacing: 0.04em;">%s</a>',
		'http://www.lcweb.it/donations',
		__('Enjoyed it?', 'tpm_ml')
	);
	return $links;
}
add_filter('plugin_action_links_templementor/templementor.php', 'tpm_plugin_action_links');





/////////////////////////////////////////////
/////// MAIN INCLUDES ///////////////////////
/////////////////////////////////////////////


include_once(TPM_DIR .'/admin_menu.php');

include_once(TPM_DIR .'/templates_pt.php');

include_once(TPM_DIR .'/metabox.php');

include_once(TPM_DIR .'/apply_template.php');
