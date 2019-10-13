<?php
/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

class WOC_Functions {

	/**
	 * Default Active Set ID
	 *
	 * @var null
	 */
	public $active_set_id = null;


	/**
	 * Set is open or Close
	 *
	 * @var null
	 */
	public $is_open = null;


	/**
	 * Today Schedules
	 *
	 * @var array
	 */
	public $todays_schedules = array();


	/**
	 * wooopenclose constructor.
	 */
	public function __construct() {

		if ( ! function_exists( 'woc_is_open' ) ) {
			require_once( WOC_PLUGIN_DIR . 'includes/functions.php' );
		}

		$this->is_open       = $this->is_open();
		$this->active_set_id = woc_get_option( 'woc_active_set' );

		$this->set_todays_schedule();
	}


	/**
	 * Return Whether shop is open or not
	 *
	 * @return mixed|void
	 */
	function is_open() {

		date_default_timezone_set( $this->get_timezone_string() );

		$current_time = date( 'U' );

		foreach ( $this->get_todays_schedule() as $schedule_id => $schedule ) {

			$open_time  = isset( $schedule['open'] ) ? date( 'U', strtotime( $schedule['open'] ) ) : '';
			$close_time = isset( $schedule['close'] ) ? date( 'U', strtotime( $schedule['close'] ) ) : '';


			if ( empty( $open_time ) || empty( $close_time ) ) {
				continue;
			}
			if ( $current_time >= $open_time && $current_time <= $close_time ) {
				return apply_filters( 'woc_is_open', true );
			}
		}

		return apply_filters( 'woc_is_open', false );
	}


	/**
	 * Return TimeZone String
	 *
	 * @return false|mixed|string|void
	 */
	function get_timezone_string() {

		// if site timezone string exists, return it
		if ( $timezone = woc_get_option( 'timezone_string' ) ) {
			return $timezone;
		}

		// get UTC offset, if it isn't set then return UTC
		if ( 0 === ( $utc_offset = woc_get_option( 'gmt_offset', 0 ) ) ) {
			return 'UTC';
		}

		// adjust UTC offset from hours to seconds
		$utc_offset *= 3600;

		// attempt to guess the timezone string from the UTC offset
		if ( $timezone = timezone_name_from_abbr( '', $utc_offset, 0 ) ) {
			return $timezone;
		}

		// last try, guess timezone string manually
		$is_dst = date( 'I' );

		foreach ( timezone_abbreviations_list() as $abbr ) {
			foreach ( $abbr as $city ) {
				if ( $city['dst'] == $is_dst && $city['offset'] == $utc_offset ) {
					return $city['timezone_id'];
				}
			}
		}

		// fallback to UTC
		return 'UTC';
	}


	/**
	 * Return Todays Schedules
	 *
	 * @return array|mixed
	 */
	function get_todays_schedule() {

		return apply_filters( 'woc_filters_get_todays_schedule', $this->todays_schedules );
	}


	/**
	 * Set Todays Schedules
	 *
	 * @return array|mixed
	 */
	function set_todays_schedule() {

		$woc_hours_meta = get_post_meta( $this->active_set_id, 'woc_hours_meta', true );
		$woc_hours_meta = empty( $woc_hours_meta ) ? array() : $woc_hours_meta;
		$all_schedules  = isset( $woc_hours_meta[ $this->get_current_day_id() ] ) ? $woc_hours_meta[ $this->get_current_day_id() ] : array();
		$all_schedules  = empty( $all_schedules ) ? array() : $all_schedules;

		$this->todays_schedules = $all_schedules;
	}


	/**
	 * Return Current Day ID
	 *
	 * @return int
	 */
	function get_current_day_id() {

		switch ( strtolower( date( 'D' ) ) ) {

			case 'mon' :
				return 10003;
			case 'tue' :
				return 10004;
			case 'wed' :
				return 10005;
			case 'thu' :
				return 10006;
			case 'fri' :
				return 10007;
			case 'sat' :
				return 10001;
			case 'sun' :
				return 10002;
		}
	}


	/**
	 * Return settings page as Array
	 *
	 * @return mixed|void
	 */
	function get_settings_pages() {

		$pages['woc_options'] = array(

			'page_nav'      => '<i class="icofont-ui-settings"></i> ' . __( 'Options', 'woc-open-close' ),
			'page_settings' => array(

				array(
					'title'   => __( 'General Settings', 'woc-open-close' ),
					'options' => array(
						array(
							'id'      => 'woc_active_set',
							'title'   => __( 'Make a Schedule Active', 'woc-open-close' ),
							'details' => __( 'The system will follow this schedule for your WooCommerce Shop', 'woc-open-close' ),
							'type'    => 'select',
							'args'    => 'POSTS_%woc_hour%',
						),
						array(
							'id'            => 'timezone_string',
							'title'         => __( 'Timezone', 'woc-open-close' ),
							'details'       => __( 'Choose either a city in the same timezone as you or a UTC timezone offset.', 'woc-open-close' ),
							'type'          => 'select2',
							'args'          => 'TIME_ZONES',
							'field_options' => array(
								'placeholder' => __( 'Select timezone', 'woc-open-close' ),
							),
						),
						array(
							'id'       => 'woc_empty_cart_on_close',
							'title'    => __( 'Empty cart when close', 'woc-open-close' ),
							'details'  => __( 'Pro: Empty cart as soon as shop is closed, so that no more order if customer added before shop was closed', 'woc-open-close' ),
							'type'     => 'select',
							'args'     => array(
								'yes' => __( 'Yes - Clear cart', 'woc-open-close' ),
								'no'  => __( 'No - Leave those on cart', 'woc-open-close' ),
							),
							'disabled' => woc_pro_available() ? false : true,
						),
						array(
							'id'       => 'woc_allow_add_cart_on_close',
							'title'    => __( 'Allow add to cart', 'woc-open-close' ),
							'details'  => __( 'Pro: Allow add to cart even the shop is closed. This settings will override <strong>Empty cart when close</strong> setting.', 'woc-open-close' ),
							'type'     => 'select',
							'args'     => array(
								'yes' => __( 'Yes', 'woc-open-close' ),
								'no'  => __( 'No', 'woc-open-close' ),
							),
							'disabled' => woc_pro_available() ? false : true,
						),

						array(
							'id'    => 'show_admin_status',
							'title' => __( 'Shop Status Notice', 'woc-open-close' ),
							'type'  => 'checkbox',
							'args'  => array(
								'no' => __( 'Disable shop status notice inside WP-Admin', 'woc-open-close' ),
							),
						),
					)
				),

				array(
					'title'   => __( 'Countdown Timer Settings', 'woc-open-close' ),
					'options' => array(

						array(
							'id'      => 'woc_timer_display_on',
							'title'   => __( 'Display countdown timer', 'woc-open-close' ),
							'details' => __( 'Select the places where you want to display the countdown timer on your shop. When your shop is closed then it will show how much time left for your shop to open, and vice verse', 'woc-open-close' ),
							'type'    => 'checkbox',
							'args'    => array(
								'before_cart_table'    => __( 'Before cart table on Cart page', 'woc-open-close' ),
								'after_cart_table'     => __( 'After cart table on Cart page', 'woc-open-close' ),
								'before_cart_total'    => __( 'Before cart total on Cart page', 'woc-open-close' ),
								'after_cart_total'     => __( 'After cart total on Cart page', 'woc-open-close' ),
								'before_checkout_form' => __( 'Before checkout form on Checkout Page', 'woc-open-close' ),
								'after_checkout_form'  => __( 'After checkout form on Checkout Page', 'woc-open-close' ),
								'before_order_review'  => __( 'Before order review on Checkout Page', 'woc-open-close' ),
								'after_order_review'   => __( 'After order review on Checkout Page', 'woc-open-close' ),
								'before_cart_single'   => __( 'Before cart button on Single Product Page', 'woc-open-close' ),
								'top_on_myaccount'     => __( 'Top on My-Account Page', 'woc-open-close' ),
							),
							'default' => array(
								'before_cart_table',
								'before_order_review',
								'before_cart_single',
								'top_on_myaccount'
							),
						),

						array(
							'id'      => 'woc_timer_style',
							'title'   => __( 'Countdown timer style', 'woc-open-close' ),
							'details' => __( 'Select the style for the countdown timer', 'woc-open-close' ),
							'type'    => 'select',
							'args'    => array(
								'1' => __( 'Style - 1', 'woc-open-close' ),
								'2' => __( 'Style - 2', 'woc-open-close' ),
								'3' => __( 'Style - 3', 'woc-open-close' ),
								'4' => __( 'Style - 4', 'woc-open-close' ),
								'5' => __( 'Style - 5', 'woc-open-close' ),
							),
						),

						array(
							'id'          => 'woc_timer_text_open',
							'title'       => __( 'Countdown timer text', 'woc-open-close' ),
							'details'     => __( 'For: Status Open, This text will visible before the countdown timer when shop is open.', 'woc-open-close' ),
							'type'        => 'textarea',
							'placeholder' => __( 'This shop will be closed within', 'woc-open-close' ),
						),

						array(
							'id'          => 'woc_timer_text_close',
							'details'     => __( 'For: Status Closed, This text will visible before the countdown timer when shop is closed.', 'woc-open-close' ),
							'type'        => 'textarea',
							'placeholder' => __( 'This shop will be open within', 'woc-open-close' ),
						),
					)
				),

				array(
					'title'   => __( 'Product Options', 'woc-open-close' ),
					'options' => array(
						array(
							'id'            => 'woc_product_allowed',
							'title'         => __( 'Allow Products', 'woc-open-close' ),
							'details'       => __( 'Pro: These products can order anytime, Even the store is Closed. You can add multiple products.', 'woc-open-close' ),
							'type'          => 'select2',
							'multiple'      => true,
							'field_options' => array(
								'placeholder' => __( 'Select products', 'woc-open-close' ),
							),
							'args'          => 'POSTS_%product%',
							'disabled'      => true,
						),
						array(
							'id'            => 'woc_product_disabled',
							'title'         => __( 'Disable Products', 'woc-open-close' ),
							'details'       => __( 'Pro: These products will be shown on website but no one can order them, Even the store is Open. You can add multiple products.', 'woc-open-close' ),
							'type'          => 'select2',
							'multiple'      => true,
							'field_options' => array(
								'placeholder' => __( 'Select products', 'woc-open-close' ),
							),
							'args'          => 'POSTS_%product%',
							'disabled'      => true,
						),
					)
				),
			),
		);
		$pages['woc_design']  = array(

			'page_nav'      => sprintf( '<i class="icofont-paint-brush"></i> %s', esc_html__( 'Design', 'woc-open-close' ) ),
			'page_settings' => array(

				array(
					'title'       => esc_html__( 'Business Hour Design', 'woc-open-close' ),
					'description' => esc_html__( 'Design business hour schedules display', 'woc-open-close' ),
					'options'     => array(
						array(
							'id'      => 'woc_bh_image_open',
							'title'   => esc_html__( 'Status Images', 'woc-open-close' ),
							'details' => esc_html__( 'For - Status Open, This image will display at the top of the business schedules', 'woc-open-close' ),

							'type' => 'media',
						),
						array(
							'id'      => 'woc_bh_image_open',
							'title'   => esc_html__( 'Status Images', 'woc-open-close' ),
							'details' => esc_html__( 'For - Status Open, This image will display at the top of the business schedules', 'woc-open-close' ),

							'type' => 'media',
						),
						array(
							'id'      => 'woc_bh_image_close',
							'details' => esc_html__( 'For - Status Closed, This image will display at the top of the business schedules', 'woc-open-close' ),
							'type'    => 'media',
						),
						array(
							'id'      => 'woc_bh_check_icon',
							'title'   => esc_html__( 'Display Check Icon', 'woc-open-close' ),
							'details' => esc_html__( 'Do you want to show a check/tick icon before the Day names.', 'woc-open-close' ),
							'type'    => 'radio',
							'args'    => array(
								'yes' => esc_html__( 'Yes', 'woc-open-close' ),
								'no'  => esc_html__( 'No', 'woc-open-close' ),
							),
							'default' => array( 'yes' ),
						),
					)
				),

				array(
					'title'       => esc_html__( 'Popup Design', 'woc-open-close' ),
					'description' => esc_html__( 'Update design for Popup on Shop/Archive page on your shop', 'woc-open-close' ),
					'options'     => array(
						array(
							'id'      => 'woc_pp_effect',
							'title'   => esc_html__( 'Popup Effect', 'woc-open-close' ),
							'details' => esc_html__( 'Change popup box effect while opening or closing', 'woc-open-close' ),
							'type'    => 'select',
							'args'    => array(
								'mfp-zoom-in'         => esc_html__( 'Zoom', 'woc-open-close' ),
								'mfp-zoom-out'        => esc_html__( 'Zoom Out', 'woc-open-close' ),
								'mfp-newspaper'       => esc_html__( 'Newspaper', 'woc-open-close' ),
								'mfp-move-horizontal' => esc_html__( 'Horizontal move', 'woc-open-close' ),
								'mfp-move-from-top'   => esc_html__( 'Move from top', 'woc-open-close' ),
								'mfp-3d-unfold'       => esc_html__( '3D unfold', 'woc-open-close' ),
							),
						),
					)
				),

				array(
					'title'       => __( 'Shop Status Bar', 'woc-open-close' ),
					'description' => __( 'Setup the status bar as like you want. This will only visible either Footer or Header when your WooCommerce Shop is closed', 'woc-open-close' ),
					'options'     => array(
						array(
							'id'      => 'woc_bar_where',
							'title'   => __( 'Bar Position', 'woc-open-close' ),
							'details' => __( 'Where you want to display the shop status bar? <strong>Default: Footer</strong>', 'woc-open-close' ),
							'type'    => 'select',
							'args'    => array(
								'woc-bar-footer' => __( 'Footer', 'woc-open-close' ),
								'woc-bar-header' => __( 'Header', 'woc-open-close' ),
							),
						),
						array(
							'id'      => 'woc_bar_btn_display',
							'title'   => __( 'Show Hide Button', 'woc-open-close' ),
							'details' => __( 'Do you want to display the Hide notice button? <strong>Default: Yes</strong>', 'woc-open-close' ),
							'type'    => 'select',
							'args'    => array(
								'yes' => __( 'Yes', 'woc-open-close' ),
								'no'  => __( 'No', 'woc-open-close' ),
							),
						),
						array(
							'id'          => 'woc_bar_hide_text',
							'title'       => __( 'Hide Button Text', 'woc-open-close' ),
							'details'     => __( 'Set custom text for \'Hide Message\' Button. <strong>Default: Hide Message</strong>', 'woc-open-close' ),
							'type'        => 'text',
							'placeholder' => __( 'Hide Message', 'woc-open-close' ),
						),
					)
				),

			),
		);
		$pages['woc_support'] = array(
			'page_nav'      => '<i class="icofont-live-support"></i> ' . __( 'Support', 'woc-open-close' ),
			'show_submit'   => false,
			'page_settings' => array(

				'sec_options' => array(
					'title'   => __( 'Emergency support from Pluginbazar.com', 'woc-open-close' ),
					'options' => array(
						array(
							'id'      => '__1',
							'title'   => __( 'Support Forum', 'woc-open-close' ),
							'details' => sprintf( '%1$s<br>' . __( '<a href="%1$s" target="_blank">Ask in Forum</a>', 'woc-open-close' ), WOC_FORUM_URL ),
						),

						array(
							'id'      => '__2',
							'title'   => __( 'Can\'t Login..?', 'woc-open-close' ),
							'details' => sprintf( __( '<span>Unable to login <strong>Pluginbazar.com</strong></span><br><a href="%1$s" target="_blank">Get Immediate Solution</a>', 'woc-open-close' ), WOC_CONTACT_URL ),
						),

						array(
							'id'      => '__3',
							'title'   => __( 'Like this Plugin?', 'woc-open-close' ),
							'details' => sprintf( __( '<span>To share feedback about this plugin Please </span><br><a href="%1$s" target="_blank">Rate now</a>', 'woc-open-close' ), WOC_WP_REVIEW_URL ),
						),

					)
				),
			)
		);

		return apply_filters( 'woc_filters_settings_pages', $pages );
	}


	/**
	 * Return Image URL dynamically
	 *
	 * @param string $image_for
	 *
	 * @return mixed|void
	 */
	function get_status_image( $image_for = '' ) {

		if ( empty( $image_for ) ) {
			$image_for = woc_is_open() ? 'open' : 'close';
		}
		$image_for = in_array( $image_for, array( 'open', 'close' ) ) ? $image_for : 'open';
		$image_id  = woc_get_option( "woc_bh_image_{$image_for}" );
		$image_url = wp_get_attachment_image_url( $image_id, 'full' );

		return apply_filters( 'woc_filters_status_image', $image_url, $image_for, $image_id );
	}


	/**
	 * Return Next Opening Time
	 *
	 * @param string $time_for open | close | toggle
	 * @param string $format
	 *
	 * @return mixed|void
	 */
	function get_next_time( $time_for = '', $format = 'M j Y G:i:s' ) {

		$current_day = $this->get_current_day_id();
		$times       = $this->calculate_times( $this->get_todays_schedule(), $time_for );

		if ( empty( $times ) ) {

			foreach ( $this->get_all_schedules() as $day_id => $schedules ) {
				if ( $current_day >= $day_id || ! empty( $times ) ) {
					continue;
				}
				$times = $this->calculate_times( $schedules, $time_for, $this->get_day_name( $day_id ) );
			}
		}

		$next_time = reset( $times );

		return apply_filters( 'woc_filters_next_time', date( $format, $next_time ), $next_time, $format );
	}


	/**
	 * Calculate times
	 *
	 * @param array $__schedules
	 * @param string $__time_for
	 * @param string $__day_name
	 *
	 * @return mixed|void
	 */
	function calculate_times( $__schedules = array(), $__time_for = '', $__day_name = '' ) {

		$__times        = array();
		$__time_for     = empty( $__time_for ) && ! in_array( $__time_for, array(
			'open',
			'close',
			'toggle'
		) ) ? 'toggle' : $__time_for;
		$__time_for     = $__time_for == 'toggle' && woc_is_open() ? 'close' : 'open';
		$__current_time = date( 'U' );
		$__day_name     = empty( $__day_name ) ? $this->get_day_name() : $__day_name;
		$__day_name     = substr( $__day_name, 0, 3 );

		foreach ( $__schedules as $__schedule_id => $__schedule ) {

			$__this_time = date( 'U', strtotime( $__day_name . ' ' . $__schedule[ $__time_for ] ) );

			if ( isset( $__schedule[ $__time_for ] ) && ! empty( $__this_time ) && $__current_time < $__this_time ) {
				$__times[] = $__this_time;
			}
		}

		return apply_filters( 'woc_filters_calculate_times', $__times, $__time_for, $__day_name );
	}


	/**
	 * Return Current Day Name
	 *
	 * @param string $day_id
	 *
	 * @return mixed|void
	 */
	function get_day_name( $day_id = '' ) {

		$day_id   = empty( $day_id ) ? $this->get_current_day_id() : $day_id;
		$day      = isset( $this->get_days()[ $day_id ] ) ? $this->get_days()[ $day_id ] : array();
		$day_name = isset( $day['label'] ) ? $day['label'] : __( 'Not Found!', 'woc-open-close' );

		return apply_filters( 'woc_filters_day_name', $day_name, $day_id );
	}


	/**
	 * Return all days
	 *
	 * @return mixed|void
	 */
	public function get_days() {

		$days_array = array(

			'10001' => array(
				'label' => __( 'Saturday', 'woc-open-close' ),
			),
			'10002' => array(
				'label' => __( 'Sunday', 'woc-open-close' ),
			),
			'10003' => array(
				'label' => __( 'Monday', 'woc-open-close' ),
			),
			'10004' => array(
				'label' => __( 'Tuesday', 'woc-open-close' ),
			),
			'10005' => array(
				'label' => __( 'Wednesday', 'woc-open-close' ),
			),
			'10006' => array(
				'label' => __( 'Thursday', 'woc-open-close' ),
			),
			'10007' => array(
				'label' => __( 'Friday', 'woc-open-close' ),
			),
		);

		return apply_filters( 'woc_filters_days_array', $days_array );
	}


	/**
	 * Return all Schedules
	 *
	 * @param string $set_id
	 *
	 * @return mixed|void
	 */
	function get_all_schedules( $set_id = '' ) {

		$set_to_display = empty( $set_id ) ? $this->active_set_id : $set_id;
		$woc_hours_meta = get_post_meta( $set_to_display, 'woc_hours_meta', true );
		$woc_hours_meta = empty( $woc_hours_meta ) ? array() : $woc_hours_meta;

		return apply_filters( 'woc_all_schedules', $woc_hours_meta );
	}


	/**
	 * Return Plugin Path
	 *
	 * @return mixed|void
	 */
	function plugin_path() {
		return apply_filters( 'woc_filters_plugin_path', untrailingslashit( WOC_PLUGIN_DIR ) );
	}


	/**
	 * Return Template path
	 *
	 * @return mixed|void
	 */
	function template_path() {

		return apply_filters( 'woc_filters_template_path', 'woc/' );
	}


	/**
	 * PB_Settings Class
	 *
	 * @param array $args
	 *
	 * @return PB_Settings
	 */
	function PB( $args = array() ) {

		return new PB_Settings( $args );
	}


	/**
	 * Return Message if Shop is Closed
	 *
	 * @return mixed|string|void
	 */
	function get_message() {

		if ( ! $this->active_set_id ) {
			return '';
		}

		$woc_hours_meta = get_post_meta( $this->active_set_id, 'woc_hours_meta', true );
		if ( empty( $woc_hours_meta ) ) {
			return apply_filters( 'woc_is_open', true );
		}

		$woc_message = isset( $woc_hours_meta['woc_message'] ) && ! empty( $woc_hours_meta['woc_message'] ) ? $woc_hours_meta['woc_message'] : __( 'Offline ! We will start taking orders in %countdown%', 'woc-open-close' );

		if ( strpos( $woc_message, '%countdown' ) !== false ) {

			$matches     = array();
			$woc_message = str_replace( '%countdown%', '%countdown-1%', $woc_message );

			preg_match( '/countdown-([0-9]+)?/', $woc_message, $matches );

			$cd_style    = isset( $matches[1] ) ? $matches[1] : 1;
			$cd_style    = get_query_var( 'in_status_bar' ) ? 1 : $cd_style;
			$woc_message = preg_replace( '/%countdown-([0-9]+)%?/', $this->get_countdown_timer( $cd_style ), $woc_message );
		}

		return apply_filters( 'woc_filters_shop_close_message', $woc_message );
	}


	/**
	 * Return countdown timer code
	 *
	 * @param int $style
	 *
	 * @return false|string
	 */
	function get_countdown_timer( $style = 1 ) {

		ob_start();

		echo do_shortcode( sprintf( '[woc_countdown_timer style="%s"]', $style ) );

		return ob_get_clean();
	}


	/**
	 * Return Button Text on Bar
	 *
	 * @return mixed|void
	 */
	function get_bar_btn_text() {

		return apply_filters( 'woc_filters_bar_btn_text', woc_get_option( 'woc_bar_hide_text', __( 'Hide Message', 'woc-open-close' ) ) );
	}


	/**
	 * Return Whether Bar button to display or not
	 *
	 * @return bool
	 */
	function is_display_bar_btn() {

		if ( woc_get_option( 'woc_bar_btn_display', 'yes' ) == 'yes' ) {
			return true;
		} else {
			return false;
		}
	}


	/**
	 * Print notice to the admin bar
	 *
	 * @param string $message
	 * @param bool $is_success
	 * @param bool $is_dismissible
	 */
	function print_notice( $message = '', $is_success = true, $is_dismissible = true ) {

		if ( empty ( $message ) ) {
			return;
		}

		if ( is_bool( $is_success ) ) {
			$is_success = $is_success ? 'success' : 'error';
		}

		printf( '<div class="notice notice-%s %s"><p>%s</p></div>', $is_success, $is_dismissible ? 'is-dismissible' : '', $message );
	}


	/**
	 * Generate and return HTML for Schedule
	 *
	 * @param array $schedule
	 *
	 * @return false|string
	 */
	function generate_woc_schedule( $schedule = array() ) {

		$unique_id = isset( $schedule['unique_id'] ) ? $schedule['unique_id'] : time() . rand( 1, 1000 );
		$day_id    = isset( $schedule['day_id'] ) ? $schedule['day_id'] : 10001;
		$open      = isset( $schedule['open'] ) ? $schedule['open'] : '';
		$close     = isset( $schedule['close'] ) ? $schedule['close'] : '';

		ob_start();

		echo "<div class='woc_repeat' day-id='$day_id' data-open='$open' data-close='$close' unique-id='$unique_id'>
            <label for='woc_tp_start_$unique_id'>" . __( 'Start time', 'woc-open-close' ) . "</label>
            <input name='woc_hours_meta[$day_id][$unique_id][open]' value='$open' type='text' autocomplete='off' id='woc_tp_start_$unique_id' placeholder='08:00 AM' />
            <label for='woc_tp_end_$unique_id'>" . __( 'End time', 'woc-open-close' ) . "</label>
            <input name='woc_hours_meta[$day_id][$unique_id][close]' value='$close' type='text' autocomplete='off' id='woc_tp_end_$unique_id' placeholder='12:00 PM' />
            <span class='woc_repeat_actions woc_repeat_copy hint--top' aria-label='" . __( 'Copy to all other days', 'woc-open-close' ) . "'><i class='icofont-copy'></i></span>
            <span class='woc_repeat_actions woc_repeat_sort hint--top' aria-label='" . __( 'Sort schedules', 'woc-open-close' ) . "'><i class='icofont-sort'></i></span>
            <span class='woc_repeat_actions woc_repeat_remove hint--top' aria-label='" . __( 'Remove schedule', 'woc-open-close' ) . "'><i class='icofont-close'></i></span>
        </div>
        <script> 
            jQuery('#woc_tp_start_$unique_id').timepicker({ 'timeFormat': 'h:i A', step: 1 }); 
            jQuery('#woc_tp_end_$unique_id').timepicker({ 'timeFormat': 'h:i A', step: 1 }); 
        </script>";

		return ob_get_clean();
	}
}

global $wooopenclose;
$wooopenclose = new WOC_Functions();