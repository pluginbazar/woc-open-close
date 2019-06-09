<?php
/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 


if( ! function_exists( 'woc_status_bar_classes' ) ){
    function woc_status_bar_classes( $echo = false ){

        $classes        = array();
        $woc_bar_where  = get_option( 'woc_bar_where', 'woc-bar-footer' );
        $woc_bar_where  = empty( $woc_bar_where ) ? 'woc-bar-footer' : $woc_bar_where;

        $classes[] = $woc_bar_where;

        if( $echo ) echo implode(' ', apply_filters( 'woc_filters_status_bar_classes', $classes ) );
        else return apply_filters( 'woc_filters_status_bar_classes', $classes );
    }
}


if( ! function_exists( 'woc_display_close_popup' ) ){
    function woc_display_close_popup(){
        if( ! woc_is_open() ) include WOC_PLUGIN_DIR . '/templates/close-popup.php';
    }
}
add_action( 'wp_footer', 'woc_display_close_popup' );


if( ! function_exists( 'woc_display_shop_status_bar' ) ){
    function woc_display_shop_status_bar(){
        if( ! woc_is_open() ) include WOC_PLUGIN_DIR . '/templates/shop-status-bar.php';
    }
}
add_action( 'wp_footer', 'woc_display_shop_status_bar' );


if( ! function_exists( 'woc_is_open' ) ) {
    function woc_is_open(){
        global $wooopenclose;
        return $wooopenclose->is_open();
    }
}



return;




	add_action( 'admin_notices', 'woc_err_notice' );
	function woc_err_notice() {
		
		if( WOC_USER_TYPE == 'free' && isset( $_GET['page'] ) && $_GET['page'] == 'woc_menu_conditional' ) {
			
			echo "<div class='error'><p>Error: Please Buy Premium version to use this feature <a href='https://www.pluginbazar.net/product/woocommerce-open-close/?r={$_SERVER['SERVER_NAME']}' target='_blank'>BUY NOW</a></p></div>";
		}
		
		$woc_license_status = get_option( 'woc_license_status', 'not_validate' );
		if( WOC_USER_TYPE == 'pro' && ! empty( $woc_license_status ) && $woc_license_status != 'validate' ) {
			
			echo "<div class='error'><p>Error: Please activate Your License Key <a href='edit.php?post_type=woc_hour&page=woc_menu_license'>Activate Now</a></p></div>";
			
			return;
		}
		
		if( ! class_exists( 'WooCommerce' ) ) {
			
			echo "<div class='error'><p>Error: Woocommerce is required</p></div>";
			return;
		}
			
		$woc_active_set = get_option('woc_active_set');
		if ( empty( $woc_active_set ) ){
			
			echo "<div class='error'><p>
			Error: No active hour set found! 
			<a href='post-new.php?post_type=woc_hour'>Create Schedule</a> then 
			<a href='edit.php?post_type=woc_hour&page=woc_menu_settings'>Save Settings</a>
			</p></div>";
			return;
		}
	}
	

	
	// Add node topbar
	add_action( 'admin_bar_menu', 'woc_top_bar_shop_status_display', 9999 );
	function woc_top_bar_shop_status_display( $wp_admin_bar ) {
		
		if( woc_is_open() ) {
			$id = 'woc-open';
			$status = __('Shop Open', 'woc-open-close');
		}
		else {
			$id = 'woc-closed';
			$status = __('Shop Closed', 'woc-open-close');
		}
		
		$wp_admin_bar->add_node( 
			array (
				'id'     => $id,
				'title'  => $status,
				'parent' => false,
			)
		);
	}


