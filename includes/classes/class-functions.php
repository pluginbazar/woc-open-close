<?php
/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

if (!defined('ABSPATH')) exit;  // if direct access

class wooopenclose {

    // Secret Key for License Verification Requests
    private $secret_key = '5beed4ad27fd52.16817105';

    // License Server URL
    private $license_server = 'https://pluginbazar.com';

    // This Item Reference - WooCommerce Open Close product ID
    private $item_reference = '15';

    public $active_set_id = null;

    public $is_open = null;

    public function __construct() {

        $this->init();

        add_action('admin_notices', array($this, 'manage_admin_notices'));
        add_action('admin_bar_menu', array($this, 'handle_admin_bar_menu'), 9999, 1);
        add_filter('plugin_row_meta', array($this, 'add_plugin_meta'), 10, 2);
        add_filter('plugin_action_links_' . WOC_PLUGIN_FILE, array($this, 'add_plugin_actions'), 10, 2);

        add_filter('woocommerce_loop_add_to_cart_link', array($this, 'replace_product_link'), 10, 3);
        add_filter('wp_head', array( $this, 'replace_on_single_product') );
    }

    function replace_on_single_product(){}

    function replace_product_link( $link_html, $product, $args ){
        return $link_html;
    }

    function is_open() {

        date_default_timezone_set( $this->get_timezone_string() );

        $woc_active_set = get_option('woc_active_set');
        if( empty( $woc_active_set ) ) return apply_filters( 'woc_is_open', true );

        $woc_hours_meta = get_post_meta($woc_active_set, 'woc_hours_meta', true);
        if( empty( $woc_hours_meta ) ) return apply_filters( 'woc_is_open', true );

        $all_schedules = isset( $woc_hours_meta[ $this->get_current_day_id() ] ) ? $woc_hours_meta[ $this->get_current_day_id() ] : array();
        if( empty( $all_schedules ) ) return apply_filters( 'woc_is_open', true );

        $current_time = date('U');

        foreach( $all_schedules as $schedule_id => $schedule) {

            $open_time  = isset( $schedule['open'] ) ? date( 'U', strtotime( $schedule['open'] ) ) : '';
            $close_time = isset( $schedule['close'] ) ? date( 'U', strtotime( $schedule['close'] ) ) : '';

            if( empty( $open_time ) || empty( $close_time ) ) continue;
            if( $current_time >= $open_time and $current_time <= $close_time ) return apply_filters( 'woc_is_open', true );
        }
        return apply_filters( 'woc_is_open', false );
    }


    function handle_admin_bar_menu($wp_admin_bar){

        if( get_post_type() == 'woc_hour' ) $wp_admin_bar->remove_menu('view');

        if( ! is_admin() ) return;

        $wp_admin_bar->add_node(
            array (
                'id'     => $this->is_open ? 'woc-shop-open' : 'woc-shop-close',
                'title'  => $this->is_open ? __('Shop Open', 'woc-open-close') : __('Shop Close', 'woc-open-close'),
                'parent' => false,
            )
        );
    }


    function get_message() {

        if( ! $this->active_set_id ) return '';

        $woc_hours_meta = get_post_meta($this->active_set_id, 'woc_hours_meta', true);
        if( empty( $woc_hours_meta ) ) return apply_filters( 'woc_is_open', true );

        $woc_message = isset( $woc_hours_meta['woc_message'] ) && ! empty( $woc_hours_meta['woc_message'] ) ? $woc_hours_meta['woc_message'] : __('We are currently off, Please try on next opening schedule. Thank you', 'woc-open-close');

        return apply_filters( 'woc_filters_shop_close_message', $woc_message );
    }


    function get_bar_btn_text() {

        return apply_filters( 'woc_filters_bar_btn_text', $this->get_option( 'woc_bar_hide_text', __('Hide Message', 'woc-open-close') ) );
    }


    function is_display_bar_btn() {

        if( $this->get_option( 'woc_bar_btn_display', 'yes' ) == 'yes' ) return true;
        else return false;
    }


    function add_plugin_actions( $links ){

        $action_links = array(
            'settings'     => sprintf( __('<a href="%s">Settings</a>', 'woc-open-close'), admin_url('edit.php?post_type=woc_hour&page=woc-open-close')),
            'license'     => sprintf( __('<a href="%s">License</a>', 'woc-open-close'), admin_url('edit.php?post_type=woc_hour&page=woc-open-close&tab=woc_license')),
        );

        if( WOC_PLUGIN_TYPE == 'free' ) unset( $action_links['license'] );

        return array_merge( $action_links, $links );
    }


    function add_plugin_meta( $links, $file ){

        if( WOC_PLUGIN_FILE === $file ) {

            $row_meta = array(
                'docs'      => sprintf( __('<a href="%s"><i class="icofont-search-document"></i> Docs</a>', 'woc-open-close'), esc_url('https://codex.pluginbazar.com/plugin/woocommerce-open-close/') ),
                'support'   => sprintf( __('<a href="%s"><i class="icofont-live-support"></i> Forum Supports</a>', 'woc-open-close'), esc_url('https://pluginbazar.com/forums/forum/woocommerce-open-close/') ),
                'buypro'    => sprintf( __('<a class="woc-plugin-meta-buy" href="%s"><i class="icofont-cart-alt"></i> Get Pro</a>', 'woc-open-close'), esc_url('https://pluginbazar.com/plugin/woocommerce-open-close/') ),
            );

            if( WOC_PLUGIN_TYPE == 'pro' ) unset( $row_meta['buypro'] );

            return array_merge( $links, $row_meta );
        }

        return (array) $links;
    }


    function update_license($license_key = '', $slm_action = '') {

        if (empty($license_key) || ! in_array($slm_action, array('slm_activate', 'slm_deactivate')))
            return new WP_Error('empty_data', __('Invalid data provided !'), 'woc-open-close');

        $api_params = array(
            'slm_action' => $slm_action,
            'secret_key' => $this->secret_key,
            'license_key' => $license_key,
            'registered_domain' => $_SERVER['SERVER_NAME'],
            'item_reference' => urlencode($this->item_reference),
        );

        $response = wp_remote_get(add_query_arg($api_params, $this->license_server), array('timeout' => 20, 'sslverify' => false));

        if (is_wp_error($response)) return $response;

        $license_data = json_decode(wp_remote_retrieve_body($response));

        return $license_data;
    }


    function manage_admin_notices() {

        // Check WooCommerce

        if (!class_exists('WooCommerce')) {
            $this->print_notice( sprintf(
                __('WooCommerce plugin is mendatory for WooCommerce Open Close <a href="%s" target="_blank">Get WooCommerce</a>', 'woc-open-close'),
                esc_url('https://wordpress.org/plugins/woocommerce/') ), false);
            return;
        }


        // Check License if Premium User

        if( defined('WOC_PLUGIN_TYPE') && WOC_PLUGIN_TYPE == 'pro' && empty( get_option( 'woc_license_key', '' ) ) ) {
            $this->print_notice( sprintf(
                __('Premium version is not verified with License Key <a href="%s">Verify here</a> or <a href="%s" target="_blank">Grab your Key</a>', 'woc-open-close'),
                admin_url('edit.php?post_type=woc_hour&page=woc-open-close&tab=woc_license'),
                esc_url('https://pluginbazar.com/license-key/') ), false);
            return;
        }


        // Check any Schedule available or not

        if( count( get_posts( 'post_type=woc_hour' ) ) == 0 ) {
            $this->print_notice( sprintf(
                __('No Schedules Found for this WooCommerce Shop. <a href="%s">Create Schedule</a> or <a href="%s">Import</a>', 'woc-open-close'),
                admin_url('post-new.php?post_type=woc_hour'),
                admin_url('import.php?import=wordpress') ), 'warning');
            return;
        }


        // Check Active Schedule

        if( empty( get_option( 'woc_active_set', '' ) ) ) {
            $this->print_notice( sprintf(
                __('No Active Schedule found <a href="%s">Make a Schedule Active</a>', 'woc-open-close'),
                admin_url('edit.php?post_type=woc_hour&page=woc-open-close') ), 'warning');
            return;
        }


        // Check is_open()

        if( get_option( 'show_admin_status', 'yes' ) == 'yes' ) {

            $buy_notice = WOC_PLUGIN_TYPE == 'free' ? sprintf(' <a target="_blank" href="https://pluginbazar.com/plugin/woocommerce-open-close/">%s</a>', __('Get Pro to Restrict Order while shop Closed', 'woc-open-close') ) : '';

            if( $this->is_open ) $this->print_notice( __('Shop is now accepting order from Customers', 'woc-open-close') . $buy_notice );
            else $this->print_notice( __('Shop is currently Closed from Taking Order', 'woc-open-close') . $buy_notice, 'warning');
        }
    }


    function print_notice($message = '', $is_success = true, $is_dismissible = true) {

        if (empty ($message)) return false;

        if( is_bool( $is_success ) ) $is_success = $is_success ? 'success' : 'error';

        printf('<div class="notice notice-%s %s"><p>%s</p></div>', $is_success,$is_dismissible ? 'is-dismissible' : '', $message );
    }


    function generate_woc_schedule($schedule = array()) {

        $unique_id = isset($schedule['unique_id']) ? $schedule['unique_id'] : time() . rand(1, 1000);
        $day_id = isset($schedule['day_id']) ? $schedule['day_id'] : 10001;
        $open = isset($schedule['open']) ? $schedule['open'] : '';
        $close = isset($schedule['close']) ? $schedule['close'] : '';

        ob_start();

        echo "<div class='woc_repeat' day-id='$day_id' data-open='$open' data-close='$close' unique-id='$unique_id'>
            <label for='woc_tp_start_$unique_id'>" . __('Start time', 'woc-open-close') . "</label>
            <input name='woc_hours_meta[$day_id][$unique_id][open]' value='$open' type='text' autocomplete='off' id='woc_tp_start_$unique_id' placeholder='08:00 AM' />
            <label for='woc_tp_end_$unique_id'>" . __('End time', 'woc-open-close') . "</label>
            <input name='woc_hours_meta[$day_id][$unique_id][close]' value='$close' type='text' autocomplete='off' id='woc_tp_end_$unique_id' placeholder='12:00 PM' />
            <span class='woc_repeat_actions woc_repeat_copy hint--top' aria-label='" . __('Copy to all other days', 'woc-open-close') . "'><i class='icofont-copy'></i></span>
            <span class='woc_repeat_actions woc_repeat_sort hint--top' aria-label='" . __('Sort schedules', 'woc-open-close') . "'><i class='icofont-sort'></i></span>
            <span class='woc_repeat_actions woc_repeat_remove hint--top' aria-label='" . __('Remove schedule', 'woc-open-close') . "'><i class='icofont-close'></i></span>
        </div>
        <script> 
            jQuery('#woc_tp_start_$unique_id').timepicker({ 'timeFormat': 'h:i A', step: 1 }); 
            jQuery('#woc_tp_end_$unique_id').timepicker({ 'timeFormat': 'h:i A', step: 1 }); 
        </script>";

        return ob_get_clean();
    }


    function get_current_day_id(){

        switch( strtolower(date('D')) ) {

            case 'mon' : return 10003;
            case 'tue' : return 10004;
            case 'wed' : return 10005;
            case 'thu' : return 10006;
            case 'fri' : return 10007;
            case 'sat' : return 10001;
            case 'sun' : return 10002;
        }
    }


    function get_day_name( $day_id = '' ){

        $day_id     = empty( $day_id ) ? $this->get_current_day_id() : $day_id;
        $day        = isset( $this->get_days()[ $day_id ] ) ? $this->get_days()[ $day_id ] : array();
        $day_name   = isset( $day['label'] ) ? $day['label'] : __('Not Found!', 'woc-open-close');

        return apply_filters( 'woc_filters_day_name', $day_name, $day_id );
    }


    public function get_days() {

        $days_array = array(

            '10001' => array(
                'label' => __('Saturday', 'woc-open-close'),
            ),
            '10002' => array(
                'label' => __('Sunday', 'woc-open-close'),
            ),
            '10003' => array(
                'label' => __('Monday', 'woc-open-close'),
            ),
            '10004' => array(
                'label' => __('Tuesday', 'woc-open-close'),
            ),
            '10005' => array(
                'label' => __('Wednesday', 'woc-open-close'),
            ),
            '10006' => array(
                'label' => __('Thursday', 'woc-open-close'),
            ),
            '10007' => array(
                'label' => __('Friday', 'woc-open-close'),
            ),
        );

        return apply_filters('woc_filters_days_array', $days_array);
    }


    function get_timezone_string(){

        // if site timezone string exists, return it
        if ($timezone = get_option('timezone_string'))
            return $timezone;

        // get UTC offset, if it isn't set then return UTC
        if (0 === ($utc_offset = get_option('gmt_offset', 0)))
            return 'UTC';

        // adjust UTC offset from hours to seconds
        $utc_offset *= 3600;

        // attempt to guess the timezone string from the UTC offset
        if ($timezone = timezone_name_from_abbr('', $utc_offset, 0)) {
            return $timezone;
        }

        // last try, guess timezone string manually
        $is_dst = date('I');

        foreach (timezone_abbreviations_list() as $abbr) {
            foreach ($abbr as $city) {
                if ($city['dst'] == $is_dst && $city['offset'] == $utc_offset)
                    return $city['timezone_id'];
            }
        }

        // fallback to UTC
        return 'UTC';
    }


    function get_all_schedules( $set_id = '' ){

        $set_to_display = empty( $set_id ) ? $this->active_set_id : $set_id;
        $woc_hours_meta = get_post_meta( $set_to_display, 'woc_hours_meta', true );
        $woc_hours_meta = empty( $woc_hours_meta ) ? array() : $woc_hours_meta;

        return apply_filters( 'woc_all_schedules', $woc_hours_meta );
    }


    function get_option( $option_key = '', $default_val = '' ){

        if( empty( $option_key ) ) return '';

        $option_val = get_option( $option_key, $default_val );
        $option_val = empty( $option_val ) ? $default_val : $option_val;

        return apply_filters( 'woc_filters_option_' . $option_key, $option_val );
    }


    function init(){

        $this->is_open = $this->is_open();
        $this->active_set_id = $this->get_option( 'woc_active_set' );
    }
}

global $wooopenclose;
$wooopenclose = new wooopenclose();