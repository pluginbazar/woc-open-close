<?php

/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

class WOC_Conditional_logic{
	
	public function __construct(){
		
		// add_filter( 'woocommerce_loop_add_to_cart_link', array( $this, 'woc_filter_woocommerce_loop_add_to_cart_link_function' ), 10, 2 );
		
		add_filter( 'woc_filter_conditional_permission', array( $this, 'woc_filter_conditional_permission_function' ), 10, 2 );
		
	}

	public function woc_filter_conditional_permission_function( $bool, $product ){
		
		$woc_safelisted_products = get_option( 'woc_safelisted_products' );
		$woc_blocked_products = get_option( 'woc_blocked_products' );
		
		if( empty( $woc_safelisted_products ) || empty( $woc_blocked_products ) ) return $bool;
		
		$safelisted_products 	= explode( ",", $woc_safelisted_products );
		$blocked_products 		= explode( ",", $woc_blocked_products );
		
		if( empty( $safelisted_products ) || empty( $blocked_products ) ) return $bool;
		
		if( is_array( $safelisted_products ) && in_array( $product->get_id(), $safelisted_products ) ) return true;
		if( is_array( $blocked_products ) && in_array( $product->get_id(), $blocked_products ) ) return false;
		
		return $bool;
	}
	
	public function woc_filter_woocommerce_loop_add_to_cart_link_function( $add_to_cart_text, $product ){
		
		if( woc_is_open() ) return "Open";
		else return "Close";
	}
	
} new WOC_Conditional_logic();