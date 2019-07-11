<?php
/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

global $wooopenclose;

$pages = array();


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
					'disabled' => true,
				),
				array(
					'id'       => 'woc_allow_add_cart_on_close',
					'title'    => __( 'Allow add to cart', 'woc-open-close' ),
					'details'  => __( 'Pro: Allow add to cart even the shop is closed but show popup message.', 'woc-open-close' ),
					'type'     => 'select',
					'args'     => array(
						'yes' => __( 'Yes', 'woc-open-close' ),
						'no'  => __( 'No', 'woc-open-close' ),
					),
					'disabled' => true,
				),

				array(
					'id'      => 'show_admin_status',
					'title'   => __( 'Show Shop Status', 'woc-open-close' ),
					'details' => __( 'Do you want to display shop status as notice inside WP-Admin?', 'woc-open-close' ),
					'type'    => 'select',
					'args'    => array(
						'yes' => __( 'Yes', 'woc-open-close' ),
						'no'  => __( 'No', 'woc-open-close' ),
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


$pages['woc_design'] = array(

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


if ( WOC_PLUGIN_TYPE == 'pro' ) {
	$pages['woc_license'] = array(
		'page_nav'      => '<i class="icofont-license"></i> ' . __( 'License', 'woc-open-close' ),
		'show_submit'   => false,
		'page_settings' => array(

			'sec_options' => array(
				'title'   => __( 'License key management', 'woc-open-close' ),
				'options' => array(
					array(
						'id'          => 'woc_license_key',
						'title'       => __( 'License Key', 'woc-open-close' ),
						'details'     => sprintf( __( 'Enter your license key. <a href="%s" target="_blank">Get your key</a>', 'woc-open-close' ), WOC_LICENSE_KEY ),
						'type'        => 'text',
						'placeholder' => '5c21375eb017d',
					),
					array(
						'id'      => 'woc_license_status',
						'title'   => __( 'Activation/Deactivation', 'woc-open-close' ),
						'details' => __( 'Activate or Deactivate your license key on this Domain', 'woc-open-close' ),
						'type'    => 'radio',
						'args'    => array(
							'slm_activate'   => __( 'Activate', 'woc-open-close' ),
							'slm_deactivate' => __( 'Deactivate', 'woc-open-close' ),
						),
					),
				)
			),
		)
	);
}


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


$wooopenclose->PB( array(
	'add_in_menu'     => true,
	'menu_type'       => 'submenu',
	'menu_title'      => __( 'Settings', 'woc-open-close' ),
	'page_title'      => __( 'Settings', 'woc-open-close' ),
	'menu_page_title' => 'WooCommerce Open Close - ' . __( 'Control Panel', 'woc-open-close' ),
	'capability'      => "manage_woocommerce",
	'menu_slug'       => "woc-open-close",
	'parent_slug'     => "edit.php?post_type=woc_hour",
	'disabled_notice' => sprintf( esc_html__( 'This feature is locked.', 'woc-open-close' ) . ' <a href="%s">%s</a>', WOC_PLUGIN_LINK, esc_html__( 'Get pro', 'woc-open-close' ) ),
	'pages'           => $pages,
) );


if ( ! function_exists( 'woc_pb_settings_page_license' ) ) {
	function woc_pb_settings_page_license() {

		global $wooopenclose;

		$wooopenclose->print_notice( sprintf( __(
			'If you think you are giving the correct License key but not working, or you think there is something wrong with License server. Then please let <i>Pluginbazar.com</i> as early as possible.
            <br><a href="%s" target="_blank">Click here to have a solution.</a>
            <a href="%s" target="_blank">Problem with login?</a>', 'woc-open-close' ), WOC_FORUM_URL, WOC_CONTACT_URL ), 'warning' );

		$woc_license_nonce = isset( $_REQUEST['woc_license_nonce'] ) ? $_REQUEST['woc_license_nonce'] : '';

		if ( empty( $woc_license_nonce ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $woc_license_nonce, 'woc_license_action' ) ) {
			$wooopenclose->print_notice( __( 'Invalid request !' ), false );

			return;
		}

		$woc_license_key    = isset( $_REQUEST['woc_license_key'] ) ? sanitize_text_field( $_REQUEST['woc_license_key'] ) : '';
		$woc_license_status = isset( $_REQUEST['woc_license_status'] ) ? stripslashes_deep( $_REQUEST['woc_license_status'] ) : array();
		$woc_license_status = reset( $woc_license_status );

		if ( empty( $woc_license_key ) || empty( $woc_license_status ) ) {
			$wooopenclose->print_notice( __( 'Invalid License key or Status selection !' ), false );

			return;
		}

		$response = $wooopenclose->update_license( $woc_license_key, $woc_license_status );

		if ( is_wp_error( $response ) ) {
			$wooopenclose->print_notice( $response->get_error_message(), false );

			return;
		}

		if ( $response->result == 'success' ) {
			$wooopenclose->print_notice( $response->message );
			update_option( 'woc_license_key', $woc_license_key );

			return;
		}

		$wooopenclose->print_notice( $response->message, false );
	}
}
add_action( 'pb_settings_page_woc_license', 'woc_pb_settings_page_license' );


if ( ! function_exists( 'woc_license_display_form_end' ) ) {
	function woc_license_display_form_end() {

		wp_nonce_field( 'woc_license_action', 'woc_license_nonce' );
		submit_button( __( 'Activate / Deactivate', 'woc-open-close' ) );
		echo '</form>';
	}
}
add_action( 'pb_settings_after_page_woc_license', 'woc_license_display_form_end' );


if ( ! function_exists( 'woc_license_display_form_start' ) ) {
	function woc_license_display_form_start() {
		echo '<form action ="" method="post">';
	}
}
add_action( 'pb_settings_before_page_woc_license', 'woc_license_display_form_start' );


if ( ! function_exists( 'woc_settings_after_timezone_string' ) ) {
	function woc_settings_after_timezone_string() {

		$timezone_format = _x( 'Y-m-d H:i:s', 'timezone date format' );

		echo '<p class="timezone-info">';
		echo '<span id="utc-time">';
		printf( __( 'Universal time (%1$s) is %2$s.' ), '<abbr>' . __( 'UTC' ) . '</abbr>', '<code>' . date_i18n( $timezone_format, false, true ) . '</code>' );
		echo '</span>';

		if ( get_option( 'timezone_string' ) || ! empty( $current_offset ) ) {

			echo '<span id="local-time">';
			printf( __( 'Local time is %s.' ), '<code>' . date_i18n( $timezone_format ) . '</code>' );
			echo '</span>';
		}

		echo '</p>';
	}
}
add_action( 'pb_settings_after_timezone_string', 'woc_settings_after_timezone_string' );

