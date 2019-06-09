<?php

/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

class class_woc_settings{
	
	public function __construct(){
		
		add_action('admin_menu', array( $this, 'woc_menu_init' ));
	}

	public function woc_menu_conditional(){
		$woc_license_status = get_option( 'woc_license_status' );
		if( empty( $woc_license_status ) ) $woc_license_status = 'not_validate';
		if( WOC_USER_TYPE == 'pro' && $woc_license_status != 'validate' ) return;
		
		require_once( woc_plugin_dir .'templates/menus/conditional-logic.php');	
	}
	
	public function woc_menu_settings(){
		$woc_license_status = get_option( 'woc_license_status' );
		if( empty( $woc_license_status ) ) $woc_license_status = 'not_validate';
		if( WOC_USER_TYPE == 'pro' && $woc_license_status != 'validate' ) return;
		
		require_once( woc_plugin_dir .'templates/menus/settings.php');	
	}
	public function woc_menu_addons(){
		require_once( woc_plugin_dir .'templates/menus/addons.php');	
	}
	public function woc_menu_license(){
		require_once( woc_plugin_dir .'templates/menus/license.php');	
	}
	public function woc_menu_faq(){
		require_once( woc_plugin_dir .'templates/menus/faq.php');	
	}
	public function woc_menu_wocr_license(){
		require_once( woc_plugin_dir .'templates/menus/woc-reports-license.php');	
	}
	public function woc_menu_init() {
		add_submenu_page('edit.php?post_type=woc_hour', __('Conditional Logic',WOCTEXTDOMAIN), __('Conditional Logic',WOCTEXTDOMAIN), 'manage_options', 'woc_menu_conditional', array( $this, 'woc_menu_conditional' ));	
		add_submenu_page('edit.php?post_type=woc_hour', __('Settings',WOCTEXTDOMAIN), __('Settings',WOCTEXTDOMAIN), 'manage_options', 'woc_menu_settings', array( $this, 'woc_menu_settings' ));	
		add_submenu_page('edit.php?post_type=woc_hour', __('Addons',WOCTEXTDOMAIN), __('Addons',WOCTEXTDOMAIN), 'manage_options', 'woc_menu_addons', array( $this, 'woc_menu_addons' ));	
		add_submenu_page('edit.php?post_type=woc_hour', __('License',WOCTEXTDOMAIN), __('License',WOCTEXTDOMAIN), 'manage_options', 'woc_menu_license', array( $this, 'woc_menu_license' ));	
		add_submenu_page('edit.php?post_type=woc_hour', __('FAQ',WOCTEXTDOMAIN), __('FAQ',WOCTEXTDOMAIN), 'manage_options', 'woc_menu_faq', array( $this, 'woc_menu_faq' ));	
		add_submenu_page(null, __('License',WOCTEXTDOMAIN), __('License',WOCTEXTDOMAIN), 'manage_options', 'woc_menu_wocr_license', array( $this, 'woc_menu_wocr_license' ));	
	}
	
} new class_woc_settings();