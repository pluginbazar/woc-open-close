<?php

/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

class class_woc_shortcodes{
	
    public function __construct(){
		add_shortcode( 'woc_open_close', array( $this, 'woc_display' ) );
		
		add_filter( 'widget_text', 'do_shortcode', 11);
	}
	
	public function woc_display($atts, $content = null ) {
		$atts = shortcode_atts( array(
			'set' => '',
		), $atts);
	
		$woc_active_set = $atts['set'];
		ob_start();
		include( woc_plugin_dir . 'templates/display-schedules.php');
		return ob_get_clean();
	}
}new class_woc_shortcodes();