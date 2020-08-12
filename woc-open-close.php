<?php
/*
	Plugin Name: WooCommerce Open Close
	Plugin URI: https://pluginbazar.com/plugin/woocommerce-open-close/
	Description: Maintain Business hour for your WooCommerce Shop. Let your customers know about business schedules and restrict them from placing new orders while Store is Closed.
	Version: 4.1.5
	Text Domain: woc-open-close
	Author: Pluginbazar
	Author URI: https://pluginbazar.com/
	License: GPLv2 or later
	License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

defined( 'ABSPATH' ) || exit;

define( 'WOC_PLUGIN_URL', WP_PLUGIN_URL . '/' . plugin_basename( dirname( __FILE__ ) ) . '/' );
define( 'WOC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WOC_PLUGIN_FILE', plugin_basename( __FILE__ ) );
define( 'WOC_LICENSE_KEY', 'https://pluginbazar.com/license-key/' );
define( 'WOC_TICKET_URL', 'https://pluginbazar.com/my-account/tickets/?action=new' );
define( 'WOC_PLUGIN_LINK', 'https://pluginbazar.com/plugin/woocommerce-open-close/?add-to-cart=3395' );
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

			require_once( WOC_PLUGIN_DIR . 'includes/classes/class-pb-settings-3.2.php' );
			require_once( WOC_PLUGIN_DIR . 'includes/classes/class-functions.php' );
			require_once( WOC_PLUGIN_DIR . 'includes/classes/class-hooks.php' );
			require_once( WOC_PLUGIN_DIR . 'includes/classes/class-post-meta.php' );
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
				'ajaxurl'    => admin_url( 'admin-ajax.php' ),
				'copyText'   => esc_html__( 'Copied !', 'woc-open-close' ),
				'removeConf' => esc_html__( 'Are you really want to remove this schedule?', 'woc-open-close' ),
			) );
		}


		/**
		 * Load Front Scripts
		 */
		function front_scripts() {

			wp_enqueue_script( 'magnific-popup', plugins_url( '/assets/front/js/jquery.magnific-popup.min.js', __FILE__ ), array( 'jquery' ), '', true );
			wp_enqueue_script( 'woc-front', plugins_url( '/assets/front/js/scripts.js', __FILE__ ), array( 'jquery' ), '', true );
			wp_localize_script( 'woc-front', 'wooopenclose', $this->localize_scripts() );

			wp_enqueue_style( 'magnific-popup', WOC_PLUGIN_URL . 'assets/front/css/magnific-popup.css' );
			wp_enqueue_style( 'woc-front', WOC_PLUGIN_URL . 'assets/front/css/style.css' );
			wp_enqueue_style( 'woc-tool-tip', WOC_PLUGIN_URL . 'assets/hint.min.css' );

			if ( woc_pro_available() ) {
				wp_enqueue_script( 'jquery-ui-sortable' );
				wp_enqueue_script( 'jquery-time-picker', WOC_PLUGIN_URL . '/assets/jquery-timepicker.js', array( 'jquery' ) );
				wp_enqueue_script( 'woc-global', plugins_url( '/assets/scripts.js', __FILE__ ), array( 'jquery' ), '', true );
				wp_localize_script( 'woc-global', 'wooopenclose', $this->localize_scripts() );

				wp_enqueue_style( 'woc-schedules', WOC_PLUGIN_URL . 'assets/admin/css/schedule-style.css' );
				wp_enqueue_style( 'jquery-timepicker', WOC_PLUGIN_URL . 'assets/jquery-timepicker.css' );
			}
		}


		/**
		 * Load Admin Scripts
		 */
		function admin_scripts() {

			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'jquery-time-picker', WOC_PLUGIN_URL . '/assets/jquery-timepicker.js', array( 'jquery' ) );
			wp_enqueue_script( 'woc-admin', plugins_url( '/assets/admin/js/scripts.js', __FILE__ ), array( 'jquery' ) );
			wp_localize_script( 'woc-admin', 'wooopenclose', $this->localize_scripts() );

			wp_enqueue_script( 'woc-global', plugins_url( '/assets/scripts.js', __FILE__ ), array( 'jquery' ), '', true );
			wp_localize_script( 'woc-global', 'wooopenclose', $this->localize_scripts() );

			wp_enqueue_style( 'woc-admin', WOC_PLUGIN_URL . 'assets/admin/css/style.css' );
			wp_enqueue_style( 'woc-schedules', WOC_PLUGIN_URL . 'assets/admin/css/schedule-style.css' );
			wp_enqueue_style( 'woc-tool-tip', WOC_PLUGIN_URL . 'assets/hint.min.css' );
			wp_enqueue_style( 'jquery-timepicker', WOC_PLUGIN_URL . 'assets/jquery-timepicker.css' );
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
