<?php

/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

class WOC_Post_types {
	
	public function __construct(){
		add_action( 'init', array( $this, 'post_type_woc' ), 0 );
	}
	
	public function post_type_woc(){

		if ( post_type_exists( "woc_hour" ) ) return;

		$singular  = __( 'Schedule', 'woc-open-close' );
		$plural    = __( 'Schedules', 'woc-open-close' );
	 
	 
		register_post_type( "woc_hour",
			apply_filters( "register_post_type_woc", array(
				'labels' => array(
					'name' 					=> $plural,
					'singular_name' 		=> $singular,
					'menu_name'             => sprintf( __( 'Shop %s', 'woc-open-close' ), $singular ),
					'all_items'             => sprintf( __( 'All %s', 'woc-open-close' ), $plural ),
					'add_new' 				=> sprintf( __( 'Add %s', 'woc-open-close' ), $singular ),
					'add_new_item' 			=> sprintf( __( 'Add %s', 'woc-open-close' ), $singular ),
					'edit' 					=> __( 'Edit', 'woc-open-close' ),
					'edit_item' 			=> sprintf( __( 'Edit %s', 'woc-open-close' ), $singular ),
					'new_item' 				=> sprintf( __( 'New %s', 'woc-open-close' ), $singular ),
					'view' 					=> sprintf( __( 'View %s', 'woc-open-close' ), $singular ),
					'view_item' 			=> sprintf( __( 'View %s', 'woc-open-close' ), $singular ),
					'search_items' 			=> sprintf( __( 'Search %s', 'woc-open-close' ), $plural ),
					'not_found' 			=> sprintf( __( 'No %s found', 'woc-open-close' ), $plural ),
					'not_found_in_trash' 	=> sprintf( __( 'No %s found in trash', 'woc-open-close' ), $plural ),
					'parent' 				=> sprintf( __( 'Parent %s', 'woc-open-close' ), $singular )
				),
				'description'           => sprintf( __( 'This is where you can create and manage %s.', 'woc-open-close' ), $plural ),
				'public' 				=> true,
				'show_ui' 				=> true,
				'capability_type' 		=> 'post',
				'map_meta_cap'          => true,
				'publicly_queryable' 	=> true,
				'exclude_from_search' 	=> false,
				'hierarchical' 			=> false,
				'rewrite' 				=> array(
				    'slug'              => 'schedule',
                ),
				'query_var' 			=> true,
				'supports' 				=> array(''),
				'show_in_nav_menus' 	=> false,
				'menu_icon'             => 'dashicons-clock',
			) )
		);
	}
	
} new WOC_Post_types();