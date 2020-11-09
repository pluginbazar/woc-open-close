<?php
/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WOC_Post_meta' ) ) {
	/**
	 * Class WOC_Post_meta
	 */
	class WOC_Post_meta {

		/**
		 * Post types that this work in
		 *
		 * @var string[]
		 */
		public $post_types = array( 'woc_hour' );


		/**
		 * WOC_Post_meta constructor.
		 */
		function __construct() {

			add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
			add_action( 'save_post', array( $this, 'save_meta_data' ) );
			add_action( 'post_submitbox_misc_actions', array( $this, 'publish_box_content' ) );
		}


		/**
		 * Publish box data
		 */
		function publish_box_content() {

			global $post_type;

			if ( in_array( $post_type, $this->post_types ) ) {
				woc_get_template( 'admin/meta-box-publish.php' );
			}
		}


		/**
		 * Display meta data
		 *
		 * @param $post
		 */
		function render_meta_box( $post ) {

			wp_nonce_field( 'woc_nonce', 'woc_nonce_val' );

			woc_get_template( 'admin/meta-box-hour.php' );
		}


		/**
		 * Add Meta boxes
		 *
		 * @param $post_type
		 */
		function add_meta_boxes( $post_type ) {

			if ( in_array( $post_type, $this->post_types ) ) {
				add_meta_box( 'woc_metabox', esc_html__( 'Woocommerce Open Close Data Box', 'woc-open-close' ), array( $this, 'render_meta_box' ), $post_type, 'normal', 'high' );
			}
		}


		/**
		 * Save post meta data
		 *
		 * @param $post_id
		 */
		function save_meta_data( $post_id ) {

			$posted_data = wp_unslash( $_POST );
			$nonce       = wooopenclose()->get_args_option( 'woc_nonce_val', '', $posted_data );

			if ( wp_verify_nonce( $nonce, 'woc_nonce' ) ) {
				$woc_hours_meta = wooopenclose()->get_args_option( 'woc_hours_meta', array(), $posted_data );
				update_post_meta( $post_id, 'woc_hours_meta', $woc_hours_meta );
			}
		}
	}

	new WOC_Post_meta();
}