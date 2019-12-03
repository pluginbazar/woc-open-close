<?php
/*
	Plugin Name: WooCommerce Open Close
	Plugin URI: https://pluginbazar.com/plugin/woocommerce-open-close/
	Description: Maintain Business hour for your WooCommerce Shop. Let your customers know about business schedules and restrict them from placing new orders while Store is Closed.
	Version: 4.0.5
	Text Domain: woc-open-close
	Author: Pluginbazar
	Author URI: https://pluginbazar.com/
	License: GPLv2 or later
	License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access


define( 'WOC_PLUGIN_URL', WP_PLUGIN_URL . '/' . plugin_basename( dirname( __FILE__ ) ) . '/' );
define( 'WOC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WOC_PLUGIN_FILE', plugin_basename( __FILE__ ) );
define( 'WOC_LICENSE_KEY', 'https://pluginbazar.com/license-key/' );
define( 'WOC_FORUM_URL', 'https://pluginbazar.com/forums/forum/woocommerce-open-close' );
define( 'WOC_PLUGIN_LINK', 'https://pluginbazar.com/plugin/woocommerce-open-close/?add-to-cart=15' );
define( 'WOC_DOCS_URL', 'https://pluginbazar.com/docs/woocommerce-open-close/' );
define( 'WOC_CONTACT_URL', 'https://pluginbazar.com/contact/' );
define( 'WOC_WP_REVIEW_URL', 'https://wordpress.org/support/plugin/woc-open-close/reviews/' );

if ( ! class_exists( 'wooCommerceOpenClose' ) ) {
	/**
	 * Class wooOpenClose
	 */
	class wooCommerceOpenClose {


		/**
		 * wooOpenClose constructor.
		 */
		function __construct() {

			$this->define_scripts();
			$this->define_classes_functions();

			add_action( 'widgets_init', array( $this, 'register_widgets' ) );
			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		}


		/**
		 * Load Textdomain
		 */
		function load_textdomain() {
			load_plugin_textdomain( 'woc-open-close', false, plugin_basename( dirname( __FILE__ ) ) . '/languages/' );
		}


		/**
		 * Register Widgets
		 */
		function register_widgets() {
			register_widget( 'WocWidgetSchedule' );
		}


		/**
		 * Include Classes and Functions
		 */
		function define_classes_functions() {

			require_once( WOC_PLUGIN_DIR . 'includes/classes/class-pb-settings.php' );
			require_once( WOC_PLUGIN_DIR . 'includes/classes/class-functions.php' );
			require_once( WOC_PLUGIN_DIR . 'includes/classes/class-hooks.php' );
			require_once( WOC_PLUGIN_DIR . 'includes/classes/class-post-meta.php' );
			require_once( WOC_PLUGIN_DIR . 'includes/classes/class-shortcodes.php' );
			require_once( WOC_PLUGIN_DIR . 'includes/classes/class-column.php' );
			require_once( WOC_PLUGIN_DIR . 'includes/classes/class-widget-schedule.php' );

			require_once( WOC_PLUGIN_DIR . 'includes/functions.php' );
		}


		/**
		 * Localize Scripts
		 *
		 * @return mixed|void
		 */
		function localize_scripts() {
			return apply_filters( 'woc_filters_localize_scripts', array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
			) );
		}


		/**
		 * Load Front Scripts
		 */
		function front_scripts() {

			wp_enqueue_script( 'magnific-popup', plugins_url( '/assets/front/js/jquery.magnific-popup.min.js', __FILE__ ), array( 'jquery' ), '', true );
			wp_enqueue_script( 'woc_js', plugins_url( '/assets/front/js/scripts.js', __FILE__ ), array( 'jquery' ), '', true );
			wp_localize_script( 'woc_js', 'wooopenclose', $this->localize_scripts() );

			wp_enqueue_style( 'icofont', WOC_PLUGIN_URL . 'assets/fonts/icofont.min.css' );
			wp_enqueue_style( 'magnific-popup', WOC_PLUGIN_URL . 'assets/front/css/magnific-popup.css' );
			wp_enqueue_style( 'woc_style', WOC_PLUGIN_URL . 'assets/front/css/style.css' );
			wp_enqueue_style( 'hint.min', WOC_PLUGIN_URL . 'assets/hint.min.css' );

			if ( woc_pro_available() ) {
				wp_enqueue_script( 'jquery-ui-sortable' );
				wp_enqueue_script( 'jquery-time-picker', WOC_PLUGIN_URL . '/assets/jquery-timepicker.js', array( 'jquery' ) );
				wp_enqueue_script( 'woc_global_js', plugins_url( '/assets/scripts.js', __FILE__ ), array( 'jquery' ), '', true );
				wp_localize_script( 'woc_global_js', 'wooopenclose', $this->localize_scripts() );

				wp_enqueue_style( 'woc_schedule_styles', WOC_PLUGIN_URL . 'assets/admin/css/schedule-style.css' );
				wp_enqueue_style( 'jquery.timepicker', WOC_PLUGIN_URL . 'assets/jquery-timepicker.css' );
			}
		}


		/**
		 * Load Admin Scripts
		 */
		function admin_scripts() {

			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'jquery-time-picker', WOC_PLUGIN_URL . '/assets/jquery-timepicker.js', array( 'jquery' ) );
			wp_enqueue_script( 'woc_admin_js', plugins_url( '/assets/admin/js/scripts.js', __FILE__ ), array( 'jquery' ) );
			wp_localize_script( 'woc_admin_js', 'wooopenclose', $this->localize_scripts() );

			wp_enqueue_script( 'woc_global_js', plugins_url( '/assets/scripts.js', __FILE__ ), array( 'jquery' ), '', true );
			wp_localize_script( 'woc_global_js', 'wooopenclose', $this->localize_scripts() );

			wp_enqueue_style( 'woc_admin_style', WOC_PLUGIN_URL . 'assets/admin/css/style.css' );
			wp_enqueue_style( 'woc_schedule_styles', WOC_PLUGIN_URL . 'assets/admin/css/schedule-style.css' );
			wp_enqueue_style( 'icofont', WOC_PLUGIN_URL . 'assets/fonts/icofont.min.css' );
			wp_enqueue_style( 'hint.min', WOC_PLUGIN_URL . 'assets/hint.min.css' );
			wp_enqueue_style( 'jquery.timepicker', WOC_PLUGIN_URL . 'assets/jquery-timepicker.css' );
		}


		/**
		 * Load Scripts
		 */
		function define_scripts() {

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'front_scripts' ) );
		}
	}

	new wooCommerceOpenClose();
}
