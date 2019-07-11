<?php
/**
 * Class Hooks
 *
 * @author Pluginbazar
 */


if ( ! class_exists( 'WOC_Hooks' ) ) {
	class WOC_Hooks {


		/**
		 * WOC_Hooks constructor.
		 */
		function __construct() {

			add_action( 'wp_footer', array( $this, 'display_popup_statusbar' ) );
			add_action( 'admin_notices', array( $this, 'manage_admin_notices' ) );
			add_action( 'admin_bar_menu', array( $this, 'handle_admin_bar_menu' ), 9999, 1 );

			add_filter( 'plugin_row_meta', array( $this, 'add_plugin_meta' ), 10, 2 );
			add_filter( 'plugin_action_links_' . WOC_PLUGIN_FILE, array( $this, 'add_plugin_actions' ), 10, 2 );

			add_action( 'init', array( $this, 'display_countdown_timer_dynamically' ) );
		}


		/**
		 * Display Countdown timer content
		 */
		function display_countdown_timer() {

			global $wooopenclose;

			$timer_style  = woc_get_option( 'woc_timer_style', array( '4' ) );
			$timer_style  = is_array( $timer_style ) ? reset( $timer_style ) : $timer_style;
			$text_to_show = woc_is_open() ? woc_get_option( 'woc_timer_text_open' ) : woc_get_option( 'woc_timer_text_close' );
			$text_to_show = empty( $text_to_show ) ? '' : sprintf( '<p>%s</p>', $text_to_show );

			printf( '<div class="woc-countdown-wrapper">%s %s</div>', $text_to_show, $wooopenclose->get_countdown_timer( $timer_style ) );
		}


		/**
		 * Add hooks dynamically to display countdown timer on multiple pages
		 */
		function display_countdown_timer_dynamically() {

			$show_timer_on = woc_get_option( 'woc_timer_display_on', array(
				'before_cart_table',
				'before_order_review',
				'before_cart_single',
				'top_on_myaccount'
			) );

			foreach ( $show_timer_on as $timer_place ) {

				$action_hook     = '';
				$action_priority = 10;

				switch ( $timer_place ) {
					case 'before_cart_table' :
						$action_hook = 'woocommerce_before_cart_table';
						break;
					case 'after_cart_table' :
						$action_hook = 'woocommerce_after_cart_table';
						break;
					case 'before_cart_total' :
						$action_hook = 'woocommerce_before_cart_totals';
						break;
					case 'after_cart_total' :
						$action_hook = 'woocommerce_after_cart_totals';
						break;
					case 'before_checkout_form' :
						$action_hook = 'woocommerce_before_checkout_form';
						break;
					case 'after_checkout_form' :
						$action_hook = 'woocommerce_after_checkout_form';
						break;
					case 'before_order_review' :
						$action_hook = 'woocommerce_checkout_before_order_review';
						break;
					case 'after_order_review' :
						$action_hook     = 'woocommerce_checkout_order_review';
						$action_priority = 11;
						break;
					case 'top_on_myaccount' :
						$action_hook = 'woocommerce_account_navigation';
						break;
					case 'before_cart_single' :
						$action_hook = 'woocommerce_single_product_summary';
						$action_priority = 29;
						break;
				}

				add_action( $action_hook, array( $this, 'display_countdown_timer' ), $action_priority );
			}
		}


		/**
		 * Add custom links to Plugin actions
		 *
		 * @param $links
		 *
		 * @return array
		 */
		function add_plugin_actions( $links ) {

			$action_links = array(
				'settings' => sprintf( __( '<a href="%s">Settings</a>', 'woc-open-close' ), admin_url( 'edit.php?post_type=woc_hour&page=woc-open-close' ) ),
				'license'  => sprintf( __( '<a href="%s">License</a>', 'woc-open-close' ), admin_url( 'edit.php?post_type=woc_hour&page=woc-open-close&tab=woc_license' ) ),
			);

			if ( WOC_PLUGIN_TYPE == 'free' ) {
				unset( $action_links['license'] );
			}

			return array_merge( $action_links, $links );
		}


		/**
		 * Add custom links to plugin meta
		 *
		 * @param $links
		 * @param $file
		 *
		 * @return array
		 */
		function add_plugin_meta( $links, $file ) {

			if ( WOC_PLUGIN_FILE === $file ) {

				$row_meta = array(
					'docs'    => sprintf( __( '<a href="%s"><i class="icofont-search-document"></i> Docs</a>', 'woc-open-close' ), esc_url( WOC_DOCS_URL ) ),
					'support' => sprintf( __( '<a href="%s"><i class="icofont-live-support"></i> Forum Supports</a>', 'woc-open-close' ), esc_url( WOC_FORUM_URL ) ),
					'buypro'  => sprintf( __( '<a class="woc-plugin-meta-buy" href="%s"><i class="icofont-cart-alt"></i> Get Pro</a>', 'woc-open-close' ), esc_url( WOC_PLUGIN_LINK ) ),
				);

				if ( WOC_PLUGIN_TYPE == 'pro' ) {
					unset( $row_meta['buypro'] );
				}

				return array_merge( $links, $row_meta );
			}

			return (array) $links;
		}


		/**
		 * Add nodes to WP Admin Bar
		 *
		 * @param WP_Admin_Bar $wp_admin_bar
		 */
		function handle_admin_bar_menu( \WP_Admin_Bar $wp_admin_bar ) {

			if ( get_post_type() == 'woc_hour' ) {
				$wp_admin_bar->remove_menu( 'view' );
			}

			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				return;
			}

			$main_node_id = woc_is_open() ? 'woc-shop-open' : 'woc-shop-close';

			$wp_admin_bar->add_node(
				array(
					'id'     => $main_node_id,
					'title'  => woc_is_open() ? __( 'Shop Open', 'woc-open-close' ) : __( 'Shop Close', 'woc-open-close' ),
					'parent' => false,
				)
			);

			$wp_admin_bar->add_node(
				array(
					'id'     => 'all-schedules',
					'title'  => __( 'All Schedules', 'woc-open-close' ),
					'parent' => $main_node_id,
					'href'   => esc_url( admin_url( 'edit.php?post_type=woc_hour' ) ),
				)
			);

			$wp_admin_bar->add_node(
				array(
					'id'     => 'add-schedule',
					'title'  => __( 'Add Schedules', 'woc-open-close' ),
					'parent' => $main_node_id,
					'href'   => esc_url( admin_url( 'post-new.php?post_type=woc_hour' ) ),
				)
			);

			$wp_admin_bar->add_node(
				array(
					'id'     => 'woc-settings',
					'title'  => __( 'Settings - Options', 'woc-open-close' ),
					'parent' => $main_node_id,
					'href'   => esc_url( admin_url( 'edit.php?post_type=woc_hour&page=woc-open-close' ) ),
				)
			);

			$wp_admin_bar->add_node(
				array(
					'id'     => 'woc-settings-design',
					'title'  => __( 'Settings - Design', 'woc-open-close' ),
					'parent' => $main_node_id,
					'href'   => esc_url( admin_url( 'edit.php?post_type=woc_hour&page=woc-open-close&tab=woc_design' ) ),
				)
			);

			$wp_admin_bar->add_node(
				array(
					'id'     => 'woc-settings-support',
					'title'  => __( 'Settings - Support', 'woc-open-close' ),
					'parent' => $main_node_id,
					'href'   => esc_url( admin_url( 'edit.php?post_type=woc_hour&page=woc-open-close&tab=woc_support' ) ),
				)
			);
		}


		/**
		 * Show admin step by step guide as Notice
		 */
		function manage_admin_notices() {

			global $wooopenclose;

			// Check WooCommerce

			if ( ! class_exists( 'WooCommerce' ) ) {
				$wooopenclose->print_notice( sprintf(
					__( 'WooCommerce plugin is mendatory for WooCommerce Open Close <a href="%s" target="_blank">Get WooCommerce</a>', 'woc-open-close' ),
					esc_url( 'https://wordpress.org/plugins/woocommerce/' ) ), false );

				return;
			}


			// Check License if Premium User

			if ( defined( 'WOC_PLUGIN_TYPE' ) && WOC_PLUGIN_TYPE == 'pro' && empty( get_option( 'woc_license_key', '' ) ) ) {
				$wooopenclose->print_notice( sprintf(
					__( 'Premium version is not verified with License Key <a href="%s">Verify here</a> or <a href="%s" target="_blank">Grab your Key</a>', 'woc-open-close' ),
					admin_url( 'edit.php?post_type=woc_hour&page=woc-open-close&tab=woc_license' ),
					esc_url( 'https://pluginbazar.com/license-key/' ) ), false );

				return;
			}


			// Check any Schedule available or not

			if ( count( get_posts( 'post_type=woc_hour' ) ) == 0 ) {
				$wooopenclose->print_notice( sprintf(
					__( 'No Schedules Found for this WooCommerce Shop. <a href="%s">Create Schedule</a> or <a href="%s">Import</a>', 'woc-open-close' ),
					admin_url( 'post-new.php?post_type=woc_hour' ),
					admin_url( 'import.php?import=wordpress' ) ), 'warning' );

				return;
			}


			// Check Active Schedule

			if ( empty( get_option( 'woc_active_set', '' ) ) ) {
				$wooopenclose->print_notice( sprintf(
					__( 'No Active Schedule found <a href="%s">Make a Schedule Active</a>', 'woc-open-close' ),
					admin_url( 'edit.php?post_type=woc_hour&page=woc-open-close' ) ), 'warning' );

				return;
			}


			// Check is_open()

			if ( get_option( 'show_admin_status', 'yes' ) == 'yes' ) {

				$buy_notice = WOC_PLUGIN_TYPE == 'free' ? sprintf( ' <a target="_blank" href="https://pluginbazar.com/plugin/woocommerce-open-close/">%s</a>', __( 'Get Pro to Restrict Order while shop Closed', 'woc-open-close' ) ) : '';

				if ( woc_is_open() ) {
					$wooopenclose->print_notice( __( 'Shop is now accepting order from Customers', 'woc-open-close' ) . $buy_notice );
				} else {
					$wooopenclose->print_notice( __( 'Shop is currently Closed from Taking Order', 'woc-open-close' ) . $buy_notice, 'warning' );
				}
			}
		}


		/**
		 * Display Footer Content of Popup and Statusbar
		 */
		function display_popup_statusbar() {
			if ( ! woc_is_open() ) {

				global $wp_query;

				woc_get_template( 'close-popup.php' );

				$wp_query->set( 'in_status_bar', true );
				woc_get_template( 'shop-status-bar.php' );
				$wp_query->set( 'in_status_bar', true );
			}
		}
	}

	new WOC_Hooks();
}