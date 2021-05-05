<?php
/*
	Plugin Name: WooCommerce Open Close
	Plugin URI: https://pluginbazar.com/plugin/woocommerce-open-close/
	Description: Maintain Business hour for your WooCommerce Shop. Let your customers know about business schedules and restrict them from placing new orders while Store is Closed.
	Version: 4.2.7
	Text Domain: woc-open-close
	Author: Pluginbazar
	Author URI: https://pluginbazar.com/
	License: GPLv2 or later
	License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

defined( 'ABSPATH' ) || exit;
defined( 'WOOOPENCLOSE_PLUGIN_URL' ) || define( 'WOOOPENCLOSE_PLUGIN_URL', WP_PLUGIN_URL . '/' . plugin_basename( dirname( __FILE__ ) ) . '/' );
defined( 'WOOOPENCLOSE_PLUGIN_DIR' ) || define( 'WOOOPENCLOSE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
defined( 'WOOOPENCLOSE_PLUGIN_FILE' ) || define( 'WOOOPENCLOSE_PLUGIN_FILE', plugin_basename( __FILE__ ) );
defined( 'WOOOPENCLOSE_TICKET_URL' ) || define( 'WOOOPENCLOSE_TICKET_URL', 'https://pluginbazar.com/my-account/tickets/?action=new' );
defined( 'WOOOPENCLOSE_PLUGIN_LINK' ) || define( 'WOOOPENCLOSE_PLUGIN_LINK', 'https://pluginbazar.com/plugin/woocommerce-open-close/?add-to-cart=3395' );
defined( 'WOOOPENCLOSE_DOCS_URL' ) || define( 'WOOOPENCLOSE_DOCS_URL', 'https://pluginbazar.com/docs/woocommerce-open-close/' );
defined( 'WOOOPENCLOSE_CONTACT_URL' ) || define( 'WOOOPENCLOSE_CONTACT_URL', 'https://pluginbazar.com/contact/' );
defined( 'WOOOPENCLOSE_WP_REVIEW_URL' ) || define( 'WOOOPENCLOSE_WP_REVIEW_URL', 'https://wordpress.org/support/plugin/woc-open-close/reviews/' );

if ( ! class_exists( 'WOOOPENCLOSE_Plugin' ) ) {
	/**
	 * Class WOOOPENCLOSE_Plugin
	 */
	class WOOOPENCLOSE_Plugin {

		protected static $_instance = null;

		/**
		 * WOOOPENCLOSE_Plugin constructor.
		 */
		function __construct() {

			$this->define_scripts();
			$this->define_classes_functions();

			add_action( 'widgets_init', array( $this, 'register_widgets' ) );
			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		}

		/**
		 * @return WOOOPENCLOSE_Plugin
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
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

			require_once WOOOPENCLOSE_PLUGIN_DIR . 'includes/classes/class-pb-settings.php';
			require_once WOOOPENCLOSE_PLUGIN_DIR . 'includes/classes/class-functions.php';
			require_once WOOOPENCLOSE_PLUGIN_DIR . 'includes/classes/class-hooks.php';
			require_once WOOOPENCLOSE_PLUGIN_DIR . 'includes/classes/class-post-meta.php';
			require_once WOOOPENCLOSE_PLUGIN_DIR . 'includes/classes/class-column.php';
			require_once WOOOPENCLOSE_PLUGIN_DIR . 'includes/classes/class-widget-schedule.php';

			require_once WOOOPENCLOSE_PLUGIN_DIR . 'includes/functions.php';
		}


		/**
		 * Localize Scripts
		 *
		 * @return mixed|void
		 */
		function localize_scripts() {
			return apply_filters( 'woc_filters_localize_scripts', array(
				'ajaxurl'            => admin_url( 'admin-ajax.php' ),
				'copyText'           => esc_html__( 'Copied !', 'woc-open-close' ),
				'removeConf'         => esc_html__( 'Are you really want to remove this schedule?', 'woc-open-close' ),
				'tempProDownload'    => esc_url( 'https://pluginbazar.com/my-account/downloads/' ),
				'tempProDownloadTxt' => esc_html__( 'Download Version 1.1.2', 'woc-open-close' ),
			) );
		}


		/**
		 * Load Front Scripts
		 */
		function front_scripts() {

			wp_enqueue_script( 'magnific-popup', plugins_url( '/assets/front/js/jquery.magnific-popup.min.js', __FILE__ ), array( 'jquery' ), '', true );
			wp_enqueue_script( 'wooopenclose-front', plugins_url( '/assets/front/js/scripts.js', __FILE__ ), array( 'jquery' ), '', true );
			wp_localize_script( 'wooopenclose-front', 'wooopenclose', $this->localize_scripts() );

			wp_enqueue_style( 'dashicons' );
			wp_enqueue_style( 'wooopenclose-core', WOOOPENCLOSE_PLUGIN_URL . 'assets/front/css/pb-core-styles.css' );
			wp_enqueue_style( 'magnific-popup', WOOOPENCLOSE_PLUGIN_URL . 'assets/front/css/magnific-popup.css' );
			wp_enqueue_style( 'wooopenclose-front', WOOOPENCLOSE_PLUGIN_URL . 'assets/front/css/style.css' );
			wp_enqueue_style( 'wooopenclose-tool-tip', WOOOPENCLOSE_PLUGIN_URL . 'assets/hint.min.css' );

			if ( woc_pro_available() ) {
				wp_enqueue_script( 'jquery-ui-sortable' );
				wp_enqueue_script( 'jquery-time-picker', WOOOPENCLOSE_PLUGIN_URL . '/assets/jquery-timepicker.js', array( 'jquery' ) );
				wp_enqueue_script( 'wooopenclose-global', plugins_url( '/assets/scripts.js', __FILE__ ), array( 'jquery' ), '', true );
				wp_localize_script( 'wooopenclose-global', 'wooopenclose', $this->localize_scripts() );

				wp_enqueue_style( 'wooopenclose-schedules', WOOOPENCLOSE_PLUGIN_URL . 'assets/admin/css/schedule-style.css' );
				wp_enqueue_style( 'jquery-timepicker', WOOOPENCLOSE_PLUGIN_URL . 'assets/jquery-timepicker.css' );
			}
		}


		/**
		 * Load Admin Scripts
		 */
		function admin_scripts() {

			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'jquery-time-picker', WOOOPENCLOSE_PLUGIN_URL . '/assets/jquery-timepicker.js', array( 'jquery' ) );
			wp_enqueue_script( 'wooopenclose-admin', plugins_url( '/assets/admin/js/scripts.js', __FILE__ ), array( 'jquery' ) );
			wp_localize_script( 'wooopenclose-admin', 'wooopenclose', $this->localize_scripts() );

			wp_enqueue_script( 'wooopenclose-global', plugins_url( '/assets/scripts.js', __FILE__ ), array( 'jquery' ), '', true );
			wp_localize_script( 'wooopenclose-global', 'wooopenclose', $this->localize_scripts() );

			wp_enqueue_style( 'wooopenclose-admin', WOOOPENCLOSE_PLUGIN_URL . 'assets/admin/css/style.css' );
			wp_enqueue_style( 'wooopenclose-schedules', WOOOPENCLOSE_PLUGIN_URL . 'assets/admin/css/schedule-style.css' );
			wp_enqueue_style( 'wooopenclose-tool-tip', WOOOPENCLOSE_PLUGIN_URL . 'assets/hint.min.css' );
			wp_enqueue_style( 'jquery-timepicker', WOOOPENCLOSE_PLUGIN_URL . 'assets/jquery-timepicker.css' );
		}


		/**
		 * Load Scripts
		 */
		function define_scripts() {
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'front_scripts' ) );
		}
	}
}

WOOOPENCLOSE_Plugin::instance();


function pb_sdk_init_woc_open_close() {

	if ( ! class_exists( 'Pluginbazar\Client' ) ) {
		require_once( plugin_dir_path( __FILE__ ) . 'includes/sdk/class-client.php' );
	}

	global $wooopenclose_sdk;

	$wooopenclose_sdk = new Pluginbazar\Client( esc_html( 'WooCommerce Open Close' ), 'woc-open-close', 15, '4.2.7' );
}

/**
 * @global \Pluginbazar\Client $wooopenclose_sdk
 */
global $wooopenclose_sdk;

pb_sdk_init_woc_open_close();