<?php
add_action('template_redirect', array('tpm_apply_template', 'get_instance'));


class tpm_apply_template {
	
	private static $instance; 	// class instance
	private $post_id; 			// actually shown post ID 
	private $content_filtered = false; // (bool) flag to execute contents filter only once

	public $applied_templates = array(); 	// array containing applied templates ID (to recall CSS files)
	public $template_code = ''; 			// final template code to be applied on shown page
	
	
	/* create/manage class instance */
	public static function get_instance() {
		if(self::$instance == null) {
			self::$instance = new tpm_apply_template();
		} 
	
		return self::$instance;
	}
	
	
	/* init */
	private function __construct() {
		if(!is_admin()) {
			$tpl_id = $this->post_is_affected();
			if($tpl_id) {			
				
				$this->get_inherited_template($tpl_id); // is called on "template_redirect" since class is initialized on that hook
				//$this->template_css(); // commented on v1.0.1 - Elementor already enqueues CSS in footer
				
				add_filter('body_class', array($this, 'template_body_classes'));
				
				$priority = (isset($_GET['elementor-preview'])) ? NULL :  9999999999;
				add_filter('the_content', array($this, 'apply_template'), $priority);
			}
		}
	}



	/**
	  * know if is a post that needs to be wrapped by a template
	  * @return (int|bool) template ID or false
	  */
	protected function post_is_affected() {
		global $post;
		$template_id = false;
		
		if(is_singular() && is_object($post)) {		
			$post_id = $post->ID;
			if(is_null($this->post_id)) {$this->post_id = $post_id;}
			
			$tpl_id = get_post_meta($post_id, 'tpm_template', true);
			
			if(!empty($tpl_id) && get_post_status($tpl_id) == 'publish') {
				$template_id = $tpl_id;
				$this->applied_templates[] = $tpl_id;	
			}
		}

		$this->applied_template = $template_id;
		return $template_id;
	}
	
	
	
	/* does applied template have inherited templates? - stores applied templates ID in $this->applied_templates */
	protected function get_inherited_template($template_id) {
		
		$inher_id = get_post_meta($template_id, 'tpm_template', true);
		
		if(!empty($inher_id) && get_post_status($inher_id) == 'publish') {
			$this->applied_templates[] = $inher_id;	
			
			$this->get_inherited_template($inher_id);
			return true;
		}
	
		return false;
	}
	
	
	
	/* recursive function getting template code and  */
	protected function get_template_code() {
		$code = '';
		$elem_front = new Elementor\Frontend();
		
		global $post;
		$post_bak = $post;

		foreach($this->applied_templates as $tpl_id) {
			
			$new_code = do_shortcode($elem_front->get_builder_content($tpl_id, true)); 
			
			if($code) {
				$code = str_replace('{{contents}}', $code, $new_code);
			} else {
				$code = $new_code;	
			}
		}
		
		$post = $post_bak;
		$this->template_code = $code;
	}
	
	
	
	//////////////////////////////////////////////////////////
	
	
	
	/* enqueues template CSS */
	public function template_css() {
		$dirs = wp_upload_dir();
		
		$rev = array_reverse($this->applied_templates);
		foreach($rev as $tpl_id) {
			wp_enqueue_style('tpm-elementor-post-'.$tpl_id, $dirs['baseurl'] .'/elementor/css/post-'. $tpl_id .'.css', 999);	
		}
	}
	
	
	/* enqueues templates body classes */
	public function template_body_classes($classes) {
		foreach($this->applied_templates as $tpl_id) {
			$classes[] = 'elementor-page-'. $tpl_id;
		}
		
		return $classes;
	}
	
	
	//////////////////////////////////////////////////////////
	
	
	
	/**
	  * Process template placeholders (not {contents} one) 
	  * @return template with replaced placeholders
	  */
	protected function exec_placeholders() {
		$code = $this->template_code;
		preg_match_all('/{{(.*?)}}/', $this->template_code, $matches);
		
		if(is_array($matches) && count($matches) && is_array($matches[0])) {
			
			foreach($matches[0] as $pch) {
				if($pch == '{{contents}}') {continue;}
				
				switch($pch) {
					
					case '{{title}}' :
						$code = str_replace($pch, get_post_field('post_title', $this->post_id), $code);	
						break;
						
					case '{{author}}' :
						$user = get_userdata( get_post_field('post_author', $this->post_id) );
						$code = str_replace($pch, $user->user_nicename, $code);	
						break;
						
					case '{{pub-date}}' :
						$code = str_replace($pch, get_the_date(), $code);	
						break;
						
					case '{{edit-date}}' :
						$edit_date = get_post_field('post_modified', $this->post_id);
						$code = str_replace($pch, mysql2date( get_option('date_format'), $edit_date), $code);	
						break;

					case '{{excerpt}}' :
						$code = str_replace($pch, do_shortcode(get_post_field('post_excerpt', $this->post_id)), $code);	// do NOT use get_the_excerpt() to avoid loops
						break;
						
					case '{{comm-count}}' :
						$code = str_replace($pch, get_post_field('comment_count', $this->post_id), $code);	
						break;
						
						
					// meta fetching	
					default :
						$key = str_replace(array('{{', '}}'), '', $pch);
						$code = str_replace($pch, (string)get_post_meta($this->post_id, $key, true), $code);
						break;		
				}
			}
		}
		
		$this->template_code = $code;
	}
	
	
	
	/* apply template over page contents */
	public function apply_template($content) {
		global $post;

		if($post->ID != $this->post_id || $this->content_filtered) {
			return $content;
		}
		
		$this->content_filtered = true;
		
		$this->get_template_code(); 
		$this->exec_placeholders();
		

		// preview or not?
		if(isset($_GET['elementor-preview'])) {
						
			add_action('the_content', array($this, 'on_elementor_builder'), 99999999); 
			return $content;	
		}
	
		else {	
			$new_contents = str_replace('{{contents}}', $content, $this->template_code);
			return $new_contents;
		}
	}
	


	/* apply on elementor builder */
	public function on_elementor_builder($content) {
		$code_parts = explode('{{contents}}', $this->template_code);
		
		$content = $code_parts[0] . $content;
		if(count($code_parts) > 1) {
			$content = $content . $code_parts[1];	
		}
		
		return $content;
	}
	

	
}

