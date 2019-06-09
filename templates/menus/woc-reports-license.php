<?php	


/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

// Saving Form Data
$pb_license_key		= isset( $_POST['pb_license_key'] ) ? $_POST['pb_license_key'] : '';
$pb_license_action 	= isset( $_POST['pb_license_action'] ) ? $_POST['pb_license_action'] : '';
	
$nonce = isset( $_POST['woc_license_nonce_check_value'] ) ? $_POST['woc_license_nonce_check_value'] : '';
if( !empty( $nonce ) && wp_verify_nonce($nonce, 'woc_license_nonce_check') ) {

	if( ! empty($_POST['woc_r_license_hidden']) && $_POST['woc_r_license_hidden'] == 'Y' ):

		if( $pb_license_action == 'slm_activate' ):
			$api_params = array(
				'slm_action' => 'slm_activate',
				'secret_key' => '587a84a6de8425.16921718',
				'license_key' => $pb_license_key,
				'registered_domain' => $_SERVER['SERVER_NAME'],
				'item_reference' => urlencode(1020),
			);
			$response = wp_remote_get(add_query_arg($api_params, 'https://pluginbazar.com/'), array('timeout' => 20, 'sslverify' => false));
			if (is_wp_error($response)){
				echo "Unexpected Error! The query returned with an error.";
			}
			$license_data = json_decode(wp_remote_retrieve_body($response));
			if($license_data->result == 'success'){
				printf( '<div class="%1$s"><p>%2$s</p></div>', 'notice notice-success is-dismissible', $license_data->message ); 
				update_option('woc_license_key', $pb_license_key);
				update_option('woc_license_status', 'validate');
			}
			else{
				$message = $license_data->message;
				$message .= " <b><i><a href='' target='_blank'>Contact Support</a></i></b>";
				printf( '<div class="%1$s"><p>%2$s</p></div>', 'notice notice-error is-dismissible', $message ); 
			}
		endif;

		if( $pb_license_action == 'slm_deactivate' ):
			$api_params = array(
				'slm_action' => 'slm_deactivate',
				'secret_key' => '587a84a6de8425.16921718',
				'license_key' => $pb_license_key,
				'registered_domain' => $_SERVER['SERVER_NAME'],
				'item_reference' => urlencode(1020),
			);
			$response = wp_remote_get(add_query_arg($api_params, 'https://pluginbazar.com/'), array('timeout' => 20, 'sslverify' => false));
			if (is_wp_error($response)){
				echo "Unexpected Error! The query returned with an error.";
			}
			$license_data = json_decode(wp_remote_retrieve_body($response));
			if($license_data->result == 'success'){
				printf( '<div class="%1$s"><p>%2$s</p></div>', 'notice notice-success is-dismissible', $license_data->message ); 
				update_option('woc_license_key', $pb_license_key);
				update_option('woc_license_status', 'not_validate');
			}
			else{
				$message = $license_data->message;
				$message .= " <b><i><a href='' target='_blank'>Contact Support</a></i></b>";
				printf( '<div class="%1$s"><p>%2$s</p></div>', 'notice notice-error is-dismissible', $message ); 
			}
		endif;
	endif;
}
?>

<div class="wrap woc_license">
	<h2>WooCommerce Open Close Reports - <?php echo __('License', WOCTEXTDOMAIN); ?> </h2><br>
	<div class="pb_header_menu">
		<a class="" href="edit.php?post_type=woc_hour&page=woc_menu_license">WooCommerce Open Close</a>
		<a class="active" href="edit.php?post_type=woc_hour&page=woc_menu_wocr_license">WooCommerce Open Close - Reports</a>
	</div>
	<?php 
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if ( is_plugin_active( 'woc-open-close-reports/woc-open-close-reports.php' ) || class_exists( 'WooCommerceOpenCloseReports' ) ) {
	?>
	<form  method="post" class="pb_form" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<input type="hidden" name="woc_r_license_hidden" value="Y" />
		<?php wp_nonce_field('woc_license_nonce_check', 'woc_license_nonce_check_value');?>
		
		<div class="pb_section">
			<div class="pb_section_title">License Key</div>
			<div class="pb_section_info">License Key for <a href="" target="_blank"><b>WooCommerce Open Close - Reports</b></a></div>
			<div class="pb_section_input">
				<input type="text" autocomplete="off" size="30" required name="pb_license_key" value="<?php echo $pb_license_key; ?>" placeholder="586f85cc403oe" />
			</div>
		</div>
		<div class="pb_section">
			<div class="pb_section_title">Action</div>
			<div class="pb_section_info">Do you want to Activate or Deactivate it?</div>
			<div class="pb_section_input">
				<label><input type="radio" required="required" name="pb_license_action" value="slm_activate" /> Activate</label>
				<br>
				<label><input type="radio" required="required" name="pb_license_action" value="slm_deactivate"/> Deactivate</label>
			</div>
		</div>
		
		<input class="button button-orange" type="submit" name="Submit" value="<?php _e('Save Changes',WOCTEXTDOMAIN ); ?>" />			
	</form>
	
	<?php } else { ?>
	<div class="pb_form">
		<p class="pb_form_notice">You don't have WooCommerce Open Close Reports Addon</p>
		<p class="pb_form_purchase"><a href="<?php echo WOC_REPORTS_PURCHASE; ?>" target="_blank">Please Purchase this from here</a></p>
	</div>
	<?php }?>
</div>
