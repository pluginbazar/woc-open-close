<?php	
/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

// Saving Form Data
$woc_blocked_products = isset( $_POST['woc_blocked_products'] ) ? $_POST['woc_blocked_products'] : "";
$woc_safelisted_products = isset( $_POST['woc_blocked_products'] ) ? $_POST['woc_safelisted_products'] : "";

$nonce = isset( $_POST['woc_cl_nonce_check_value'] ) ? $_POST['woc_cl_nonce_check_value'] : '';
if( !empty( $nonce ) && wp_verify_nonce($nonce, 'woc_cl_nonce_check') ) {

	if( ! empty($_POST['woc_cl_hidden']) && $_POST['woc_cl_hidden'] == 'Y' ):
			
		update_option( 'woc_safelisted_products', $woc_safelisted_products );
		update_option( 'woc_blocked_products', $woc_blocked_products );

		printf( '<div class="%1$s"><p>%2$s</p></div>', 'notice notice-success is-dismissible', __( "Changes saved", WOCTEXTDOMAIN ) ); 
		
	endif;
}


$woc_safelisted_products = get_option( 'woc_safelisted_products' );
$woc_blocked_products = get_option( 'woc_blocked_products' );
		
?>

<div class="wrap">
	<h2>WooCommerce Open Close - <?php echo __('Conditional Logic', WOCTEXTDOMAIN); ?> </h2><br>
	
	<form  method="post" class="pb_form" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<input type="hidden" name="woc_cl_hidden" value="Y" />
		<?php wp_nonce_field('woc_cl_nonce_check', 'woc_cl_nonce_check_value');?>
		
		<div class="pb_section">
			<div class="pb_section_title">Allow Product(s)</div>
			<div class="pb_section_info">
			Please enter all the product's ID(s) which you want to add safelist<br> 
			These products can order anytime, Even the store is Closed<br>
			You can add multiple products by separating with Comma(s) Like: 23,13,78,347
			</div>
			<div class="pb_section_input">
				<textarea name="woc_safelisted_products"><?php echo $woc_safelisted_products; ?></textarea>
			</div>
		</div>
		
		<div class="pb_section">
			<div class="pb_section_title">Block Product(s)</div>
			<div class="pb_section_info">
			Please enter all the product's ID(s) which you want to add blocklist<br> 
			These products will be shown on website but no one can order them, Even the store is Opened<br>
			You can add multiple products by separating with Comma(s) Like: 23,13,78,347
			</div>
			<div class="pb_section_input">
				<textarea name="woc_blocked_products"><?php echo $woc_blocked_products; ?></textarea>
			</div>
		</div>
		
		<input class="button button-orange" type="submit" name="Submit" value="<?php _e('Save Changes',WOCTEXTDOMAIN ); ?>" />			
	</form>
</div>
