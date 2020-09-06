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
	 * wooopenclose constructor.
	 */
	public function __construct() {

		if ( ! function_exists( 'woc_is_open' ) ) {
			require_once( WOC_PLUGIN_DIR . 'includes/functions.php' );
		}

		$this->active_set_id = $this->get_option( 'woc_active_set' );
	}


	/**
	 * Return Whether shop is open or not
	 *
	 * @return mixed|void
	 */
	function is_open() {

		date_default_timezone_set( $this->get_timezone_string() );

		$woc_is_open     = false;
		$current_time    = date( 'U' );
		$today_schedules = $this->get_todays_schedule();

		foreach ( $today_schedules as $schedule_id => $schedule ) {

			$open_time  = isset( $schedule['open'] ) ? date( 'U', strtotime( $schedule['open'] ) ) : '';
			$close_time = isset( $schedule['close'] ) ? date( 'U', strtotime( $schedule['close'] ) ) : '';

			if ( empty( $open_time ) || empty( $close_time ) ) {
				continue;
			}
			if ( $current_time >= $open_time && $current_time <= $close_time ) {
				$woc_is_open = true;
			}
		}

		return apply_filters( 'woc_is_open', $woc_is_open );
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

		$woc_hours_meta = get_post_meta( $this->get_active_schedule_id(), 'woc_hours_meta', true );
		$woc_hours_meta = empty( $woc_hours_meta ) ? array() : $woc_hours_meta;
		$all_schedules  = isset( $woc_hours_meta[ $this->get_current_day_id() ] ) ? $woc_hours_meta[ $this->get_current_day_id() ] : array();
		$all_schedules  = empty( $all_schedules ) ? array() : $all_schedules;

		return apply_filters( 'woc_filters_get_todays_schedule', $all_schedules );
	}


	/**
	 * Return option value
	 *
	 * @param string $option_key
	 * @param string $default_val
	 *
	 * @return mixed|string|void
	 */
	function get_option( $option_key = '', $default_val = '' ) {

		if ( empty( $option_key ) ) {
			return '';
		}

		$option_val = get_option( $option_key, $default_val );
		$option_val = empty( $option_val ) ? $default_val : $option_val;

		return apply_filters( 'woc_filters_option_' . $option_key, $option_val );
	}


	/**
	 * Return Current Day ID
	 *
	 * @return int
	 */
	function get_current_day_id() {

		switch ( strtolower( date( 'D' ) ) ) {
			case 'mon' :
				return 10001;
			case 'tue' :
				return 10002;
			case 'wed' :
				return 10003;
			case 'thu' :
				return 10004;
			case 'fri' :
				return 10005;
			case 'sat' :
				return 10006;
			case 'sun' :
				return 10007;
		}

		return 10001;
	}


	/**
	 * Return active schedule id
	 *
	 * @return mixed|void
	 */
	public function get_active_schedule_id() {
		return apply_filters( 'woc_filters_get_active_schedule_id', $this->active_set_id );
	}

	/**
	 * Return User Meta Value
	 *
	 * @param bool $meta_key
	 * @param bool $user_id
	 * @param string $default
	 *
	 * @return mixed|string|void
	 */
	function get_user_meta( $meta_key = false, $user_id = false, $default = '' ) {

		if ( ! $meta_key ) {
			return '';
		}

		$user_id    = ! $user_id ? get_current_user_id() : $user_id;
		$meta_value = get_user_meta( $user_id, $meta_key, true );
		$meta_value = empty( $meta_value ) ? $default : $meta_value;

		return apply_filters( 'woc_filters_get_user_meta', $meta_value, $meta_key, $user_id, $default );
	}

	/**
	 * Return settings page as Array
	 *
	 * @return mixed|void
	 */
	function get_settings_pages() {

		$pages['woc_options'] = array(

			'page_nav'      => esc_html__( 'Options', 'woc-open-close' ),
			'page_settings' => array(

				array(
					'title'   => esc_html__( 'General Settings', 'woc-open-close' ),
					'options' => array(
						array(
							'id'      => 'woc_active_set',
							'title'   => esc_html__( 'Make a Schedule Active', 'woc-open-close' ),
							'details' => esc_html__( 'The system will follow this schedule for your WooCommerce Shop', 'woc-open-close' ),
							'type'    => 'select',
							'args'    => 'POSTS_%woc_hour%',
						),
						array(
							'id'            => 'timezone_string',
							'title'         => esc_html__( 'Timezone', 'woc-open-close' ),
							'details'       => esc_html__( 'Choose either a city in the same timezone as you or a UTC timezone offset.', 'woc-open-close' ),
							'type'          => 'select2',
							'args'          => 'TIME_ZONES',
							'field_options' => array(
								'placeholder' => esc_html__( 'Select timezone', 'woc-open-close' ),
							),
						),
						array(
							'id'      => 'start_of_week',
							'title'   => esc_html__( 'Week Starts On	', 'woc-open-close' ),
							'details' => esc_html__( 'Select from which day your week starts on.', 'woc-open-close' ),
							'type'    => 'select',
							'args'    => array(
								1 => esc_html( 'Monday' ),
								2 => esc_html( 'Tuesday' ),
								3 => esc_html( 'Wednesday' ),
								4 => esc_html( 'Thursday' ),
								5 => esc_html( 'Friday' ),
								6 => esc_html( 'Saturday' ),
								0 => esc_html( 'Sunday' ),
							),
						),
						array(
							'id'    => 'show_admin_status',
							'title' => esc_html__( 'Shop Status Notice', 'woc-open-close' ),
							'type'  => 'checkbox',
							'args'  => array(
								'no' => esc_html__( 'Disable shop status notice inside WP-Admin', 'woc-open-close' ),
							),
						),
					)
				),

				array(
					'title'       => esc_html__( 'Pro Settings', 'woc-open-close' ),
					'description' => esc_html__( 'These setting options are available only in pro version, please purchase the pro version to unlock some awesome features!', 'woc-open-close' ),
					'options'     => array(
						array(
							'id'       => 'woc_empty_cart_on_close',
							'title'    => esc_html__( 'Empty cart when close', 'woc-open-close' ),
							'details'  => esc_html__( 'Pro: Empty cart as soon as shop is closed, so that no more order if customer added before shop was closed', 'woc-open-close' ),
							'type'     => 'select',
							'args'     => array(
								'yes' => esc_html__( 'Yes - Clear cart', 'woc-open-close' ),
								'no'  => esc_html__( 'No - Leave those on cart', 'woc-open-close' ),
							),
							'disabled' => woc_pro_available() ? false : true,
						),
						array(
							'id'       => 'woc_allow_add_cart_on_close',
							'title'    => esc_html__( 'Allow add to cart', 'woc-open-close' ),
							'details'  => esc_html__( 'Pro: Allow add to cart even the shop is closed. This settings will override <strong>Empty cart when close</strong> setting.', 'woc-open-close' ),
							'type'     => 'select',
							'args'     => array(
								'yes' => esc_html__( 'Yes', 'woc-open-close' ),
								'no'  => esc_html__( 'No', 'woc-open-close' ),
							),
							'disabled' => woc_pro_available() ? false : true,
						),
					)
				),

				array(
					'title'   => esc_html__( 'Countdown Timer Settings', 'woc-open-close' ),
					'options' => array(

						array(
							'id'      => 'woc_timer_display_on',
							'title'   => esc_html__( 'Display countdown timer', 'woc-open-close' ),
							'details' => esc_html__( 'Select the places where you want to display the countdown timer on your shop. When your shop is closed then it will show how much time left for your shop to open, and vice verse', 'woc-open-close' ),
							'type'    => 'checkbox',
							'args'    => array(
								'before_cart_table'    => esc_html__( 'Before cart table on Cart page', 'woc-open-close' ),
								'after_cart_table'     => esc_html__( 'After cart table on Cart page', 'woc-open-close' ),
								'before_cart_total'    => esc_html__( 'Before cart total on Cart page', 'woc-open-close' ),
								'after_cart_total'     => esc_html__( 'After cart total on Cart page', 'woc-open-close' ),
								'before_checkout_form' => esc_html__( 'Before checkout form on Checkout Page', 'woc-open-close' ),
								'after_checkout_form'  => esc_html__( 'After checkout form on Checkout Page', 'woc-open-close' ),
								'before_order_review'  => esc_html__( 'Before order review on Checkout Page', 'woc-open-close' ),
								'after_order_review'   => esc_html__( 'After order review on Checkout Page', 'woc-open-close' ),
								'before_cart_single'   => esc_html__( 'Before cart button on Single Product Page', 'woc-open-close' ),
								'top_on_myaccount'     => esc_html__( 'Top on My-Account Page', 'woc-open-close' ),
							),
						),

						array(
							'id'      => 'woc_timer_style',
							'title'   => esc_html__( 'Countdown timer style', 'woc-open-close' ),
							'details' => esc_html__( 'Select the style for the countdown timer', 'woc-open-close' ),
							'type'    => 'select',
							'args'    => array(
								'1' => esc_html__( 'Style - 1', 'woc-open-close' ),
								'2' => esc_html__( 'Style - 2', 'woc-open-close' ),
								'3' => esc_html__( 'Style - 3', 'woc-open-close' ),
								'4' => esc_html__( 'Style - 4', 'woc-open-close' ),
								'5' => esc_html__( 'Style - 5', 'woc-open-close' ),
							),
						),

						array(
							'id'          => 'woc_timer_text_open',
							'title'       => esc_html__( 'Countdown timer text', 'woc-open-close' ),
							'details'     => esc_html__( 'For: Status Open, This text will visible before the countdown timer when shop is open.', 'woc-open-close' ),
							'type'        => 'textarea',
							'placeholder' => esc_html__( 'This shop will be closed within', 'woc-open-close' ),
						),

						array(
							'id'          => 'woc_timer_text_close',
							'details'     => esc_html__( 'For: Status Closed, This text will visible before the countdown timer when shop is closed.', 'woc-open-close' ),
							'type'        => 'textarea',
							'placeholder' => esc_html__( 'This shop will be open within', 'woc-open-close' ),
						),
					)
				),
			),
		);
		$pages['woc_force']   = array(
			'page_nav'      => esc_html__( 'Force Rules', 'woc-open-close' ),
			'page_settings' => array(
				array(
					'title'       => esc_html__( 'Instant Controlling', 'woc-open-close' ),
					'description' => esc_html__( 'Manage opening or closing of your store instantly ignoring all other settings.', 'woc-open-close' ),
					'options'     => array(
						array(
							'id'       => 'woc_instant_controls',
							'title'    => esc_html__( 'Enable instant controlling', 'woc-open-close' ),
							'details'  => esc_html__( 'Leave this if you want to control opening/closing automatically from business schedules.', 'woc-open-close' ),
							'type'     => 'checkbox',
							'args'     => array(
								'yes' => esc_html__( 'Enable or Disable instant controlling settings', 'woc-open-close' ),
							),
							'disabled' => woc_pro_available() ? false : true,
						),
						array(
							'id'       => 'woc_instant_force',
							'title'    => esc_html__( 'Open or Close Store', 'woc-open-close' ),
							'type'     => 'custom',
							'disabled' => woc_pro_available() ? false : true,
						),
						array(
							'id'          => 'woc_instant_force_msg',
							'title'       => esc_html__( 'Custom Message', 'woc-open-close' ),
							'details'     => esc_html__( 'When store is forcefully closed, set a spacial custom message for your customers and users', 'woc-open-close' ),
							'type'        => 'textarea',
							'rows'        => 2,
							'placeholder' => esc_html__( 'We are completely off till next update', 'woc-open-close' ),
							'disabled'    => woc_pro_available() ? false : true,
						),
					)
				),
				array(
					'title'       => esc_html__( 'When Opened', 'woc-open-close' ),
					'description' => esc_html__( 'These rules will apply when the shop is opened from taking orders.', 'woc-open-close' ),
					'options'     => array(
						array(
							'id'            => 'woc_disallowed_products',
							'title'         => esc_html__( 'Disallow Products', 'woc-open-close' ),
							'details'       => esc_html__( 'Customers will not able to purchase these products even your shop is opened.', 'woc-open-close' ),
							'type'          => 'select2',
							'multiple'      => true,
							'args'          => 'POSTS_%product%',
							'field_options' => array(
								'placeholder' => esc_html__( 'Select products', 'woc-open-close' ),
							),
							'disabled'      => woc_pro_available() ? false : true,
						),
					)
				),
				array(
					'title'       => esc_html__( 'When Closed', 'woc-open-close' ),
					'description' => esc_html__( 'These rules will apply when the shop is closed from taking orders.', 'woc-open-close' ),
					'options'     => array(
						array(
							'id'            => 'woc_allowed_products',
							'title'         => esc_html__( 'Allow Products', 'woc-open-close' ),
							'details'       => esc_html__( 'Customers will able to purchase these products even your shop is closed.', 'woc-open-close' ),
							'type'          => 'select2',
							'multiple'      => true,
							'args'          => 'POSTS_%product%',
							'field_options' => array(
								'placeholder' => esc_html__( 'Select products', 'woc-open-close' ),
							),
							'disabled'      => woc_pro_available() ? false : true,
						),
					)
				),
			)
		);
		$pages['woc_design']  = array(

			'page_nav'      => esc_html__( 'Design', 'woc-open-close' ),
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
								'wooopenclose-bar-footer' => __( 'Footer', 'woc-open-close' ),
								'wooopenclose-bar-header' => __( 'Header', 'woc-open-close' ),
								'wooopenclose-bar-none'   => __( 'Disable notice bar', 'woc-open-close' ),
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
			'page_nav'      => esc_html__( 'Support', 'woc-open-close' ),
			'show_submit'   => false,
			'page_settings' => array(
				'sec_options' => array(
					'title'   => esc_html__( 'Emergency support from Pluginbazar.com', 'woc-open-close' ),
					'options' => array(
						array(
							'id'      => '__1',
							'title'   => esc_html__( 'Support Ticket', 'woc-open-close' ),
							'details' => sprintf( '%1$s<br>' . __( '<a href="%1$s" target="_blank">Create Support Ticket</a>', 'woc-open-close' ), WOC_TICKET_URL ),
						),

						array(
							'id'      => '__2',
							'title'   => esc_html__( 'Can\'t Login..?', 'woc-open-close' ),
							'details' => sprintf( __( '<span>Unable to login <strong>Pluginbazar.com</strong></span><br><a href="%1$s" target="_blank">Get Immediate Solution</a>', 'woc-open-close' ), WOC_CONTACT_URL ),
						),

						array(
							'id'      => '__3',
							'title'   => esc_html__( 'Like this Plugin?', 'woc-open-close' ),
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
	 * @param bool $return_label
	 *
	 * @return mixed|void
	 */
	function get_day_name( $day_id = '', $return_label = false ) {

		$day_id = empty( $day_id ) ? $this->get_current_day_id() : $day_id;
		$day    = isset( $this->get_days()[ $day_id ] ) ? $this->get_days()[ $day_id ] : array();

		if ( $return_label ) {
			$day_name = isset( $day['label'] ) ? $day['label'] : __( 'Not Found!', 'woc-open-close' );
		} else {
			$day_name = isset( $day['name'] ) ? $day['name'] : __( 'Not Found!', 'woc-open-close' );
		}

		return apply_filters( 'woc_filters_day_name', $day_name, $day_id );
	}


	/**
	 * Return all days
	 *
	 * @return mixed|void
	 */
	public function get_days() {

		$start_of_week = get_option( 'start_of_week' );
		$days_array    = array(
			'10000' => array(
				'name'  => esc_html( 'Sunday' ),
				'label' => __( 'Sunday', 'woc-open-close' ),
			),
			'10001' => array(
				'name'  => esc_html( 'Monday' ),
				'label' => __( 'Monday', 'woc-open-close' ),
			),
			'10002' => array(
				'name'  => esc_html( 'Tuesday' ),
				'label' => __( 'Tuesday', 'woc-open-close' ),
			),
			'10003' => array(
				'name'  => esc_html( 'Wednesday' ),
				'label' => __( 'Wednesday', 'woc-open-close' ),
			),
			'10004' => array(
				'name'  => esc_html( 'Thursday' ),
				'label' => __( 'Thursday', 'woc-open-close' ),
			),
			'10005' => array(
				'name'  => esc_html( 'Friday' ),
				'label' => __( 'Friday', 'woc-open-close' ),
			),
			'10006' => array(
				'name'  => esc_html( 'Saturday' ),
				'label' => __( 'Saturday', 'woc-open-close' ),
			),
		);
		$sorted_days   = array();

		for ( $index = $start_of_week; $index < ( $start_of_week + 7 ); $index ++ ) {
			$day_id                 = 10000 + $index % 7;
			$sorted_days[ $day_id ] = $days_array[ $day_id ];
		}

		return apply_filters( 'woc_filters_days_array', $sorted_days );
	}

	/**
	 * Return all Schedules
	 *
	 * @param string $set_id
	 *
	 * @return mixed|void
	 */
	function get_all_schedules( $set_id = '' ) {

		$set_to_display = empty( $set_id ) ? $this->get_active_schedule_id() : $set_id;
		$woc_hours_meta = get_post_meta( $set_to_display, 'woc_hours_meta', true );
		$woc_hours_meta = empty( $woc_hours_meta ) ? array() : $woc_hours_meta;
		$all_schedules  = array();

		foreach ( $this->get_days() as $day_key => $label ) {
			if ( isset( $woc_hours_meta[ $day_key ] ) ) {
				$all_schedules[ $day_key ] = $woc_hours_meta[ $day_key ];
			}
		}

		return apply_filters( 'woc_all_schedules', $all_schedules );
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
	 * PB_Settings Class
	 *
	 * @param array $args
	 *
	 * @return PB_Settings
	 */
	function PB_Settings( $args = array() ) {

		return new PB_Settings( $args );
	}

	/**
	 * Return Message if Shop is Closed
	 *
	 * @return mixed|string|void
	 */
	function get_message() {

		if ( ! $this->get_active_schedule_id() ) {
			return '';
		}

		$woc_hours_meta = get_post_meta( $this->get_active_schedule_id(), 'woc_hours_meta', true );
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

		woc_update_global_arguments( $schedule );

		ob_start();
		woc_get_template( 'admin/single-schedule.php', $schedule );

		return ob_get_clean();
	}

	/**
	 * Return current URL with HTTP parameters
	 *
	 * @param $http_args
	 * @param $wp_request
	 *
	 * @return mixed|void
	 */
	function get_current_url( $http_args = array(), $wp_request = '' ) {

		global $wp;

		$current_url = empty( $wp_request ) ? site_url( $wp->request ) : $wp_request;
		$http_args   = array_merge( $_GET, $http_args );

		if ( ! empty( $http_args ) ) {
			$current_url .= '?' . http_build_query( $http_args );
		}

		return apply_filters( 'woc_filters_current_url', $current_url );
	}

	/**
	 * Return Post Meta Value
	 *
	 * @param bool $meta_key
	 * @param bool $post_id
	 * @param string $default
	 *
	 * @return mixed|string|void
	 */
	function get_meta( $meta_key = false, $post_id = false, $default = '' ) {

		if ( ! $meta_key ) {
			return '';
		}

		$post_id    = ! $post_id ? get_the_ID() : $post_id;
		$meta_value = get_post_meta( $post_id, $meta_key, true );
		$meta_value = empty( $meta_value ) ? $default : $meta_value;

		return apply_filters( 'woc_filters_get_meta', $meta_value, $meta_key, $post_id, $default );
	}

	/**
	 * Return Arguments Value
	 *
	 * @param string $key
	 * @param string $default
	 * @param array $args
	 *
	 * @return mixed|string
	 */
	function get_args_option( $key = '', $default = '', $args = array() ) {

		global $wooopenclose_args;

		$args    = empty( $args ) ? $wooopenclose_args : $args;
		$default = empty( $default ) && ! is_array( $default ) ? '' : $default;
		$default = empty( $default ) && is_array( $default ) ? array() : $default;
		$key     = empty( $key ) ? '' : $key;

		if ( isset( $args[ $key ] ) && ! empty( $args[ $key ] ) ) {
			return $args[ $key ];
		}

		return $default;
	}
}

global $wooopenclose;
$wooopenclose = new WOC_Functions();