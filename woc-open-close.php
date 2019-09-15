<?php
/*
	Plugin Name: WooCommerce Open Close - Free
	Plugin URI: https://pluginbazar.com/plugin/woocommerce-open-close/
	Description: Maintain Business hour for your WooCommerce Shop. Let your customers know about business schedules and restrict them from placing new orders while Store is Closed.
	Version: 3.2.1
	Author: Pluginbazar
	Author URI: https://pluginbazar.com/
	License: GPLv2 or later
	License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

class WooCommerceOpenClose {

	public function __construct() {

		$this->define_constants();
		$this->define_scripts();
		$this->define_classes();
		$this->define_functions();
		$this->define_widgets();

		add_action( 'plugins_loaded', array( $this, 'woc_load_textdomain' ) );
	}

	public function woc_load_textdomain() {
		load_plugin_textdomain( 'woc-open-close', false, plugin_basename( dirname( __FILE__ ) ) . '/languages/' );
	}

	function define_functions() {

		require_once( plugin_dir_path( __FILE__ ) . 'includes/functions.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'includes/functions-settings.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'includes/functions-ajax.php' );
	}

	function load_widget() {

		register_widget( 'WocWidgetSchedule' );
	}

	function define_widgets() {

		add_action( 'widgets_init', array( $this, 'load_widget' ) );
	}

	function define_classes() {

		require_once( plugin_dir_path( __FILE__ ) . 'includes/classes/class-pb-settings.php' );

//		$settings_path = str_replace( array( 'Pluginbazar/free/', 'Pluginbazar\free/' ), '', ABSPATH );
//		include $settings_path . "PB-Settings/class-pb-settings.php";

		require_once( plugin_dir_path( __FILE__ ) . 'includes/classes/class-functions.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'includes/classes/class-hooks.php' );

		require_once( plugin_dir_path( __FILE__ ) . 'includes/classes/class-post-types.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'includes/classes/class-post-meta.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'includes/classes/class-shortcodes.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'includes/classes/class-column.php' );

		require_once( plugin_dir_path( __FILE__ ) . 'includes/classes/class-widget-schedule.php' );
	}

	public function admin_scripts() {

		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'jquery-time-picker', WOC_PLUGIN_URL . '/assets/jquery-timepicker.js', array( 'jquery' ) );
		wp_enqueue_script( 'woc_admin_js', plugins_url( '/assets/admin/js/scripts.js', __FILE__ ), array( 'jquery' ) );
		wp_localize_script( 'woc_admin_js', 'woc_ajax', array( 'woc_ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

		wp_enqueue_style( 'woc_admin_style', WOC_PLUGIN_URL . 'assets/admin/css/style.css' );
		wp_enqueue_style( 'icofont', WOC_PLUGIN_URL . 'assets/fonts/icofont.min.css' );
		wp_enqueue_style( 'hint.min', WOC_PLUGIN_URL . 'assets/hint.min.css' );
		wp_enqueue_style( 'jquery.timepicker', WOC_PLUGIN_URL . 'assets/jquery-timepicker.css' );
	}

	public function front_scripts() {

		wp_enqueue_script( 'magnific-popup', plugins_url( '/assets/front/js/jquery.magnific-popup.min.js', __FILE__ ), array( 'jquery' ), '', true );
		wp_enqueue_script( 'woc_js', plugins_url( '/assets/front/js/scripts.js', __FILE__ ), array( 'jquery' ), '', true );
		wp_localize_script( 'woc_js', 'woc_ajax', array( 'woc_ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

		wp_enqueue_style( 'icofont', WOC_PLUGIN_URL . 'assets/fonts/icofont.min.css' );
		wp_enqueue_style( 'magnific-popup', WOC_PLUGIN_URL . 'assets/front/css/magnific-popup.css' );
		wp_enqueue_style( 'woc_style', WOC_PLUGIN_URL . 'assets/front/css/style.css' );
		wp_enqueue_style( 'hint.min', WOC_PLUGIN_URL . 'assets/hint.min.css' );
	}

	function define_scripts() {

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'front_scripts' ) );
	}

	function define_constants() {

		define( 'WOC_PLUGIN_URL', WP_PLUGIN_URL . '/' . plugin_basename( dirname( __FILE__ ) ) . '/' );
		define( 'WOC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		define( 'WOC_PLUGIN_FILE', plugin_basename( __FILE__ ) );
		define( 'WOC_PLUGIN_TYPE', 'free' );
		define( 'WOC_LICENSE_KEY', 'https://pluginbazar.com/license-key/' );
		define( 'WOC_FORUM_URL', 'https://pluginbazar.com/forums/forum/woocommerce-open-close' );
		define( 'WOC_PLUGIN_LINK', 'https://pluginbazar.com/plugin/woocommerce-open-close/?add-to-cart=15' );
		define( 'WOC_DOCS_URL', 'https://pluginbazar.com/docs/woocommerce-open-close/' );
		define( 'WOC_CONTACT_URL', 'https://pluginbazar.com/contact/' );
		define( 'WOC_WP_REVIEW_URL', 'https://wordpress.org/support/plugin/woc-open-close/reviews/' );
	}

}

new WooCommerceOpenClose();

