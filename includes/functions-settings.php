<?php
/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

if (!defined('ABSPATH')) exit;  // if direct access

$pages = array();

$pages['woc_options'] = array(

    'page_nav' => '<i class="icofont-ui-settings"></i> ' . __('Options', 'woc-open-close'),
    'page_settings' => array(

        'sec_options' => array(
            'title' => __('General Settings', 'woc-open-close'),
            'options' => array(
                array(
                    'id' => 'woc_active_set',
                    'title' => __('Make a Schedule Active', 'woc-open-close'),
                    'details' => __('The system will follow this schedule for your WooCommerce Shop', 'woc-open-close'),
                    'type' => 'select',
                    'args' => 'PICK_POSTS_%woc_hour%',
                ),
                array(
                    'id' => 'timezone_string',
                    'title' => __('Timezone', 'woc-open-close'),
                    'details' => __('Choose either a city in the same timezone as you or a UTC timezone offset.', 'woc-open-close'),
                    'type' => 'select2',
                    'args' => 'PICK_TIME_ZONES',
                ),
                array(
                    'id' => 'woc_empty_cart_on_close',
                    'title' => __('Empty cart when close', 'woc-open-close'),
                    'details' => __('Pro: Empty cart as soon as shop is closed, so that no more order if customer added before shop was closed', 'woc-open-close'),
                    'type' => 'select',
                    'args' => array(
                        'yes' => __('Yes - Clear cart', 'woc-open-close'),
                        'no' => __('No - Leave those on cart', 'woc-open-close'),
                    ),
                ),
                array(
                    'id' => 'woc_allow_add_cart_on_close',
                    'title' => __('Allow add to cart', 'woc-open-close'),
                    'details' => __('Pro: Allow add to cart even the shop is closed but show popup message.', 'woc-open-close'),
                    'type' => 'select',
                    'args' => array(
                        '' => __('Select your choice', 'woc-open-close'),
                        'yes' => __('Yes', 'woc-open-close'),
                        'no' => __('No', 'woc-open-close'),
                    ),
                ),

//                array(
//                    'id' => 'woc_instant_status',
//                    'title' => __('Instant Action', 'woc-open-close'),
//                    'details' => __('Instantly open or close your shop, it will not care about what schedule is active and current time. default: Do not follow', 'woc-open-close'),
//                    'type' => 'radio',
//                    'args' => array(
//                        'do_not_follow' => __('Do not follow', 'woc-open-close'),
//                        'force_open'    => __('Force open', 'woc-open-close'),
//                        'force_close'   => __('Force close', 'woc-open-close'),
//                    ),
//                    'value' => 'do_not_follow',
//                ),
                array(
                    'id' => 'show_admin_status',
                    'title' => __('Show Shop Status', 'woc-open-close'),
                    'details' => __('Do you want to display shop status as notice inside WP-Admin?', 'woc-open-close'),
                    'type' => 'select',
                    'args' => array(
                        'yes' => __('Yes', 'woc-open-close'),
                        'no' => __('No', 'woc-open-close'),
                    ),
                ),
            )
        ),

        'sec_products' => array(
            'title' => __('Product Options', 'woc-open-close'),
            'options' => array(
                array(
                    'id' => 'woc_product_allowed',
                    'title' => __('Allow Products', 'woc-open-close'),
                    'details' => __('These products can order anytime, Even the store is Closed. You can add multiple products.', 'woc-open-close'),
                    'type' => 'select2',
                    'multiple' => true,
                    'args' => 'PICK_POSTS_%product%',
                ),
                array(
                    'id' => 'woc_product_disabled',
                    'title' => __('Disable Products', 'woc-open-close'),
                    'details' => __('These products will be shown on website but no one can order them, Even the store is Open. You can add multiple products.', 'woc-open-close'),
                    'type' => 'select2',
                    'multiple' => true,
                    'args' => 'PICK_POSTS_%product%',
                ),
            )
        ),
    ),
);

$pages['woc_design'] = array(

    'page_nav' => '<i class="icofont-paint-brush"></i> ' . __('Design', 'woc-open-close'),
    'page_settings' => array(

        'sec_options' => array(
            'title' => __('Shop Status Bar', 'woc-open-close'),
            'description' => __('Setup the status bar as like you want. This will only visible either Footer or Header when your WooCommerce Shop is closed', 'woc-open-close'),
            'options' => array(
                array(
                    'id' => 'woc_bar_where',
                    'title' => __('Bar Position', 'woc-open-close'),
                    'details' => __('Where you want to display the shop status bar? <strong>Default: Footer</strong>', 'woc-open-close'),
                    'type' => 'select',
                    'args' => array(
                        'woc-bar-footer' => __('Footer', 'woc-open-close'),
                        'woc-bar-header' => __('Header', 'woc-open-close'),
                    ),
                ),
                array(
                    'id' => 'woc_bar_btn_display',
                    'title' => __('Show Hide Button', 'woc-open-close'),
                    'details' => __('Do you want to display the Hide notice button? <strong>Default: Yes</strong>', 'woc-open-close'),
                    'type' => 'select',
                    'args' => array(
                        'yes' => __('Yes', 'woc-open-close'),
                        'no' => __('No', 'woc-open-close'),
                    ),
                ),
                array(
                    'id' => 'woc_bar_hide_text',
                    'title' => __('Hide Button Text', 'woc-open-close'),
                    'details' => __('Set custom text for \'Hide Message\' Button. <strong>Default: Hide Message</strong>', 'woc-open-close'),
                    'type' => 'text',
                    'placeholder' => __('Hide Message', 'woc-open-close'),
                ),
            )
        ),
    ),
);

if( WOC_PLUGIN_TYPE == 'pro' ) {
    $pages['woc_license'] = array(
        'page_nav' => '<i class="icofont-license"></i> ' . __('License', 'woc-open-close'),
        'show_submit' => false,
        'page_settings' => array(

            'sec_options' => array(
                'title' => __('License key management', 'woc-open-close'),
                'options' => array(
                    array(
                        'id' => 'woc_license_key',
                        'title' => __('License Key', 'woc-open-close'),
                        'details' => sprintf(__('Enter your license key. <a href="%s" target="_blank">Get your key</a>', 'woc-open-close'), WOC_LICENSE_KEY),
                        'type' => 'text',
                        'placeholder' => '5c21375eb017d',
                    ),
                    array(
                        'id' => 'woc_license_status',
                        'title' => __('Activation/Deactivation', 'woc-open-close'),
                        'details' => __('Activate or Deactivate your license key on this Domain', 'woc-open-close'),
                        'type' => 'radio',
                        'args' => array(
                            'slm_activate' => __('Activate', 'woc-open-close'),
                            'slm_deactivate' => __('Deactivate', 'woc-open-close'),
                        ),
                    ),
                )
            ),
        )
    );
}

$pages['woc_support'] = array(
    'page_nav' => '<i class="icofont-live-support"></i> ' . __('Support', 'woc-open-close'),
    'show_submit' => false,
    'page_settings' => array(

        'sec_options' => array(
            'title' => __('Emergency support from Pluginbazar.com', 'woc-open-close'),
            'options' => array(
                array(
                    'id' => '__1',
                    'title' => __('Support Forum', 'woc-open-close'),
                    'details' => sprintf( '%1$s<br>' . __('<a href="%1$s" target="_blank">Ask in Forum</a>', 'woc-open-close'), WOC_FORUM_URL),
                ),

                array(
                    'id' => '__2',
                    'title' => __('Can\'t Login..?', 'woc-open-close'),
                    'details' => sprintf( __('<span>Unable to login <strong>Pluginbazar.com</strong></span><br><a href="%1$s" target="_blank">Get Immediate Solution</a>', 'woc-open-close'), WOC_CONTACT_URL),
                ),

                array(
                    'id' => '__3',
                    'title' => __('Like this Plugin?', 'woc-open-close'),
                    'details' => sprintf( __('<span>To share feedback about this plugin Please </span><br><a href="%1$s" target="_blank">Rate now</a>', 'woc-open-close'), WOC_WP_REVIEW_URL),
                ),

            )
        ),
    )
);


new Pick_settings( array(
    'add_in_menu' => true,
    'menu_type' => 'submenu',
    'menu_title' => __('Settings', 'woc-open-close'),
    'page_title' => __('Settings', 'woc-open-close'),
    'menu_page_title' => 'WooCommerce Open Close - ' . __('Control Panel', 'woc-open-close'),
    'capability' => "manage_woocommerce",
    'menu_slug' => "woc-open-close",
    'parent_slug' => "edit.php?post_type=woc_hour",
    'pages' => $pages,
) );


if( ! function_exists( 'woc_pick_settings_page_license' ) ) {
    function woc_pick_settings_page_license() {

        global $wooopenclose;

        $wooopenclose->print_notice( sprintf( __(
            'If you think you are giving the correct License key but not working, or you think there is something wrong with License server. Then please let <i>Pluginbazar.com</i> as early as possible.
            <br><a href="%s" target="_blank">Click here to have a solution.</a>
            <a href="%s" target="_blank">Problem with login?</a>', 'woc-open-close'), WOC_FORUM_URL, WOC_CONTACT_URL ), 'warning' );

        $woc_license_nonce = isset( $_REQUEST['woc_license_nonce'] ) ? $_REQUEST['woc_license_nonce'] : '';

        if( empty( $woc_license_nonce ) ) return;

        if( ! wp_verify_nonce($woc_license_nonce, 'woc_license_action') ) {
            $wooopenclose->print_notice( __('Invalid request !'), false );
            return;
        }

        $woc_license_key    = isset( $_REQUEST['woc_license_key'] ) ? sanitize_text_field( $_REQUEST['woc_license_key'] ) : '';
        $woc_license_status = isset( $_REQUEST['woc_license_status'] ) ? stripslashes_deep( $_REQUEST['woc_license_status'] ) : array();
        $woc_license_status = reset( $woc_license_status );

        if( empty( $woc_license_key ) || empty( $woc_license_status ) ) {
            $wooopenclose->print_notice( __('Invalid License key or Status selection !'), false );
            return;
        }

        $response = $wooopenclose->update_license( $woc_license_key, $woc_license_status );

        if( is_wp_error( $response ) ) {
            $wooopenclose->print_notice( $response->get_error_message(), false );
            return;
        }

        if( $response->result == 'success' ) {
            $wooopenclose->print_notice( $response->message );
            update_option('woc_license_key', $woc_license_key );
            return;
        }

        $wooopenclose->print_notice( $response->message, false );
    }
}
add_action('pick_settings_page_woc_license', 'woc_pick_settings_page_license');



if( ! function_exists( 'woc_license_display_form_end' ) ) {
    function woc_license_display_form_end(){

        wp_nonce_field('woc_license_action', 'woc_license_nonce' );
        submit_button(__('Activate / Deactivate', 'woc-open-close'));
        echo '</form>';
    }
}
add_action( 'pick_settings_after_page_woc_license', 'woc_license_display_form_end');



if( ! function_exists( 'woc_license_display_form_start' ) ) {
    function woc_license_display_form_start(){
        echo '<form action ="" method="post">';
    }
}
add_action( 'pick_settings_before_page_woc_license', 'woc_license_display_form_start');



if (!function_exists('woc_settings_after_timezone_string')) {
    function woc_settings_after_timezone_string()
    {

        $timezone_format = _x('Y-m-d H:i:s', 'timezone date format');

        echo '<p class="timezone-info">';
        echo '<span id="utc-time">';
        printf(__('Universal time (%1$s) is %2$s.'), '<abbr>' . __('UTC') . '</abbr>', '<code>' . date_i18n($timezone_format, false, true) . '</code>');
        echo '</span>';

        if (get_option('timezone_string') || !empty($current_offset)) {

            echo '<span id="local-time">';
            printf(__('Local time is %s.'), '<code>' . date_i18n($timezone_format) . '</code>');
            echo '</span>';
        }

        echo '</p>';
    }
}
add_action('pick_settings_after_timezone_string', 'woc_settings_after_timezone_string');

