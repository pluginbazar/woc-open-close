<?php
/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

class wooopenclose {

	/**
	 * Secret Key for License Verification Requests
	 *
	 * @var string
	 */
	private $secret_key = '5beed4ad27fd52.16817105';


	/**
	 * License Server URL
	 *
	 * @var string
	 */
	private $license_server = 'https://pluginbazar.com';


	/**
	 * This Item Reference - WooCommerce Open Close product ID
	 *
	 * @var string
	 */
	private $item_reference = '15';


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


	public $todays_schedules = array();


	/**
	 * wooopenclose constructor.
	 */
	public function __construct() {

		if ( ! function_exists( 'woc_is_open' ) ) {
			require_once( WOC_PLUGIN_DIR . 'includes/functions.php' );
		}
		$this->init();
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

		$times = array();

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
	 * Return Message if Shop is Closed
	 *
	 * @return mixed|string|void
	 */
	function get_message() {

		global $wp_query;

		if ( ! $this->active_set_id ) {
			return '';
		}

		$woc_hours_meta = get_post_meta( $this->active_set_id, 'woc_hours_meta', true );
		if ( empty( $woc_hours_meta ) ) {
			return apply_filters( 'woc_is_open', true );
		}

		$woc_message = isset( $woc_hours_meta['woc_message'] ) && ! empty( $woc_hours_meta['woc_message'] ) ? $woc_hours_meta['woc_message'] : __( 'We are currently off, Please try on next opening schedule. Thank you', 'woc-open-close' );

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
	 * Update license
	 *
	 * @param string $license_key
	 * @param string $slm_action
	 *
	 * @return array|mixed|object|WP_Error
	 */
	function update_license( $license_key = '', $slm_action = '' ) {

		if ( empty( $license_key ) || ! in_array( $slm_action, array( 'slm_activate', 'slm_deactivate' ) ) ) {
			return new WP_Error( 'empty_data', __( 'Invalid data provided !' ), 'woc-open-close' );
		}

		$api_params = array(
			'slm_action'        => $slm_action,
			'secret_key'        => $this->secret_key,
			'license_key'       => $license_key,
			'registered_domain' => $_SERVER['SERVER_NAME'],
			'item_reference'    => urlencode( $this->item_reference ),
		);

		$response = wp_remote_get( add_query_arg( $api_params, $this->license_server ), array(
			'timeout'   => 20,
			'sslverify' => false
		) );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		return $license_data;
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
	 * Print notice to the admin bar
	 *
	 * @param string $message
	 * @param bool $is_success
	 * @param bool $is_dismissible
	 *
	 * @return bool
	 */
	function print_notice( $message = '', $is_success = true, $is_dismissible = true ) {

		if ( empty ( $message ) ) {
			return false;
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
	 * Initialize this Class
	 */
	function init() {

		$this->is_open       = $this->is_open();
		$this->active_set_id = woc_get_option( 'woc_active_set' );

		$this->set_todays_schedule();
	}
}

global $wooopenclose;
$wooopenclose = new wooopenclose();