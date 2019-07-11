<?php

/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

class class_woc_shortcodes {

	public function __construct() {
		add_shortcode( 'woc_open_close', array( $this, 'woc_display' ) );
		add_shortcode( 'woc_countdown_timer', array( $this, 'woc_countdown_timer_display' ) );

		add_filter( 'widget_text', 'do_shortcode', 11 );
	}


	public function woc_countdown_timer_display( $atts, $content = null ) {

		extract( is_array( $atts ) ? $atts : array() );

		ob_start();

		woc_get_template( 'countdown-timer.php', $atts );

		return ob_get_clean();

	}

	public function woc_display( $atts ) {

		extract( is_array( $atts ) ? $atts : array() );

		ob_start();

		woc_get_template( 'business-schedules.php', $atts );

		return ob_get_clean();
	}


}

new class_woc_shortcodes();