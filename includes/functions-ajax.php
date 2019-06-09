<?php
/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

function woc_switch_active(){
	
    $post_id    = isset( $_POST['post_id'] ) ? sanitize_text_field( $_POST['post_id'] ) : '';
    $woc_active = isset( $_POST['woc_active'] ) ? sanitize_text_field( $_POST['woc_active'] ) : 'false';
    
    if( empty( $post_id ) || $post_id == 0 ) die();

    if( $woc_active == 'true' ) update_option( 'woc_active_set', $post_id );
    if( $woc_active == 'false' ) update_option( 'woc_active_set', '' );

    echo 'success';
    die();
}
add_action('wp_ajax_woc_switch_active', 'woc_switch_active');
add_action('wp_ajax_nopriv_woc_switch_active', 'woc_switch_active');

function woc_add_schedule(){
	
    $day_id = isset( $_POST['day_id'] ) ? sanitize_text_field( $_POST['day_id'] ) : '';
    $open   = isset( $_POST['open'] ) ? sanitize_text_field( $_POST['open'] ) : '';
    $close  = isset( $_POST['close'] ) ? sanitize_text_field( $_POST['close'] ) : '';
    
    global $wooopenclose;
    
    echo $wooopenclose->generate_woc_schedule( 
        array( 
            'day_id' => $day_id,
            'open'   => $open,
            'close'  => $close,
        ) 
    );

    die();
}
add_action('wp_ajax_woc_add_schedule', 'woc_add_schedule');
add_action('wp_ajax_nopriv_woc_add_schedule', 'woc_add_schedule');