<?php
/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

class WOC_Post_meta {
	
	public function __construct(){
		
		add_action( 'add_meta_boxes', array($this, 'add_meta_boxes') );
		add_action( 'save_post', array($this, 'save_meta_data') );
		add_action( 'post_submitbox_misc_actions', array($this, 'publish_box_content') );
	}
    
    public function publish_box_content(){
	
		global $post;
		if( $post->post_type != 'woc_hour' ) return;

        include WOC_PLUGIN_DIR . 'templates/admin/meta-box-publish.php';
    }
    
	public function add_meta_boxes( $post_type ) {

		$post_types = array('woc_hour');
        
        if( in_array( $post_type, $post_types ) ) {
        
            add_meta_box('woc_metabox', 'Woocommerce Open Close ' . __('Data Box','woc-open-close'), array($this, 'woc_meta_box_function'), $post_type,'normal','high');
		}
	}
	
	public function woc_meta_box_function($post) {
        
		wp_nonce_field('woc_nonce_check', 'woc_nonce_check_value_hour');
        
        include WOC_PLUGIN_DIR . 'templates/admin/meta-box-hour.php';	
   	}
	
	public function save_meta_data($post_id){

		$nonce = isset( $_POST['woc_nonce_check_value_hour'] ) ? $_POST['woc_nonce_check_value_hour'] : '';
		
        if( ! wp_verify_nonce( $nonce, 'woc_nonce_check' ) ) return $post_id;

		$woc_hours_meta = stripslashes_deep($_POST['woc_hours_meta']);
		update_post_meta($post_id, 'woc_hours_meta', $woc_hours_meta);		
    }
    
} new WOC_Post_meta();