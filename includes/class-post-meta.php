<?php

/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

class class_woc_post_meta{
	
	public function __construct(){
		
		$woc_license_status = get_option( 'woc_license_status' );
		if( empty( $woc_license_status ) ) $woc_license_status = 'not_validate';
		if( WOC_USER_TYPE == 'pro' && $woc_license_status != 'validate' ) return;
		
		add_action('add_meta_boxes', array($this, 'meta_boxes_woc_hour'));
		add_action('save_post', array($this, 'meta_boxes_woc_hour_save'));
		add_action( 'post_submitbox_misc_actions', array($this, 'woc_meta_box_status_function') );
	}
	
	public function meta_boxes_woc_hour($post_type) {
		$post_types = array('woc_hour');
		if (in_array($post_type, $post_types)) {
			add_meta_box('woc_metabox', 'Woocommerce Open Close ' . __('Data Box',WOCTEXTDOMAIN),
				array($this, 'woc_meta_box_function'),
				$post_type,'normal','high');
			
			add_meta_box('woc_metabox_message',__('Store Close Mssage',WOCTEXTDOMAIN),
				array($this, 'woc_meta_box_message_function'),
				$post_type,'side','low');
					
		}
	}
	
	public function woc_meta_box_status_function(){
	
		global $post;
		if( $post->post_type == 'woc_hour' ) {

			echo '<style> 
			#minor-publishing-actions, 
			.misc-pub-post-status,
			.misc-pub-curtime,
			.misc-pub-visibility { display:none; !important; } 
			</style>';
			echo '<div class="misc-pub-section woc_section_status">';

			if( woc_is_open() )
				echo '<div class="woc_back_shop_status woc_shop_open">'.__('Shop is now Open',WOCTEXTDOMAIN).'</div>';
			else echo '<div class="woc_back_shop_status woc_shop_close">'.__('Shop is now Close',WOCTEXTDOMAIN).'</div>';
			
			echo '</div>';
		}
	}
	
	public function woc_meta_box_message_function($post) {
		$woc_hours_meta = get_post_meta( $post->ID, 'woc_hours_meta', true );
		$woc_message 	= isset( $woc_hours_meta['woc_message'] ) ? $woc_hours_meta['woc_message'] : '';

		echo '<div class="back-settings woc-message">';
		echo '<p class="option-info">'.__('Write message for your offline visitors',WOCTEXTDOMAIN).'</p>';
		echo '<textarea name="woc_hours_meta[woc_message]" class="woc_message" id="woc_message" rows="4" cols="25">'.$woc_message.'</textarea>';
		echo '</div>';
	}
	
	public function woc_meta_box_function($post) {
        
		wp_nonce_field('woc_nonce_check', 'woc_nonce_check_value_hour');
		
		$WOC_Functions = new WOC_Functions();
		$woc_hours_meta = get_post_meta( $post->ID, 'woc_hours_meta', true );
		
		$days_array = $WOC_Functions->woc_days_array();
		
		echo '<div class="pb_form woc-schedule-container">';
		
		$tab_nav = '';
		$tab_box = '';
		
		$count = 1; 		
		foreach( $days_array as $day_key => $day_details ) {
			
			if( $count == 1 ) {
				$tab_nav .= '<li nav="'.$count.'" class="nav'.$count.' active">'.$day_details['icon'].' '.$day_details['name'].'</li>';
				$tab_box .= '<li style="display: block;" class="box'.$count.' tab-box active">';
			} 
			else {
				$tab_nav .= '<li nav="'.$count.'" class="nav'.$count.'">'.$day_details['icon'].' '.$day_details['name'].'</li>';
				$tab_box .= '<li style="display: none;" class="box'.$count.' tab-box">';
			}
			
			$max_order = isset( $woc_hours_meta[$day_key]['max_order'] ) ? $woc_hours_meta[$day_key]['max_order'] : '';
			
			$tab_box .= '
			<div class="woc_btn_insert_input" day_key="'.$day_key.'"><i class="fa fa-plus-circle"></i> '.__('Add New Row',WOCTEXTDOMAIN).'</div>
			<div class="woc_max_order_item">
				<span>'.__('Maximum Order',WOCTEXTDOMAIN).'</span>
				<input type="number" name="woc_hours_meta['.$day_key.'][max_order]" placeholder="Max Order" size="5" value="'.$max_order.'" />
			</div>
			<div class="pb_section">
				<p class="pb_section_title">'.$day_details['name'].' '.__('Schedule',WOCTEXTDOMAIN).'</p>
				<p class="pb_section_info">'.__('You can add more slacks', WOCTEXTDOMAIN).'</p>
					
					<ul class="woc_hours_licontainer woc_hours_meta_'.$day_key.'">';
					
					if( empty($woc_hours_meta) ) {
					
						$tab_box .= '
						<li class="woc_single_schedule" id="'.time().'">
							
							<label for="woc_mon_open_'.time().'"><i class="fa fa-angle-up"></i> '.__('Opening Time',WOCTEXTDOMAIN).' </label>
							<input type="text" name="woc_hours_meta['.$day_key.']['.time().'][open]" id="woc_'.$day_key.'_open_'.time().'" placeholder="'.__('Click to set time', WOCTEXTDOMAIN ).'"/>
							
							<label for="woc_'.$day_key.'_close_'.time().'"><i class="fa fa-angle-down"></i> Closing Time </label>
							<input type="text" name="woc_hours_meta['.$day_key.']['.time().'][close]" id="woc_'.$day_key.'_close_'.time().'" placeholder="'.__('Click to set time', WOCTEXTDOMAIN ).'"/>
							
							<div class="woc_delete_single_schedule" row_id="'.time().'"><i class="fa fa-times-circle-o"></i></div>
							<div class="woc_single_sorter"><i class="fa fa-sort" aria-hidden="true"></i></div>
						</li>
						<script> 
							jQuery("#woc_'.$day_key.'_open_'.time().'").timepicker({ "timeFormat": "H:i" }); 
							jQuery("#woc_'.$day_key.'_close_'.time().'").timepicker({ "timeFormat": "H:i" }); 
						</script> ';
					
					 } else {
					
						foreach( $woc_hours_meta[$day_key] as $schedule_id => $schedule_details ) {
						
						if( $schedule_id == 'max_order' || $schedule_id == 'res_products' ) continue;
						
						$tab_box .= '
						<li class="woc_single_schedule" id="'.$schedule_id.'">
							<label for="woc_'.$day_key.'_open_'.$schedule_id.'"><i class="fa fa-angle-up"></i> Opening Time </label>
							<input type="text" name="woc_hours_meta['.$day_key.']['.$schedule_id.'][open]" value="'.$schedule_details['open'].'" id="woc_'.$day_key.'_open_'.$schedule_id.'" placeholder="Click to set time"/>
							
							<label for="woc_'.$day_key.'_close_'.$schedule_id.'"><i class="fa fa-angle-down"></i> Closing Time </label>
							<input type="text" name="woc_hours_meta['.$day_key.']['.$schedule_id.'][close]" value="'.$schedule_details['close'].'" id="woc_'.$day_key.'_close_'.$schedule_id.'" placeholder="Click to set time"/>
							
							<div class="woc_delete_single_schedule" row_id="'.$schedule_id.'"><i class="fa fa-times-circle-o"></i></div>
							
							<div class="woc_single_sorter"><i class="fa fa-sort" aria-hidden="true"></i></div>
						</li>
						<script> 
							jQuery("#woc_'.$day_key.'_open_'.$schedule_id.'").timepicker({ "timeFormat": "H:i", step: 1 }); 
							jQuery("#woc_'.$day_key.'_close_'.$schedule_id.'").timepicker({ "timeFormat": "H:i", step: 1 }); 
						</script>';
						}
					}
					$tab_box .= '
					</ul>
					
				</div>';
			$tab_box .= '</li>';
			$count++;
		}
		
		echo "<div class='woc_current_time'><div class='current'>";
		echo __( 'Current Time of Your Shop is: ', WOCTEXTDOMAIN ) .current_time( 'mysql' );
		echo "</div><div class='suggest_change'>";
		echo __( 'You can update any time from here', WOCTEXTDOMAIN );
		echo "<a class='button' target='_blank' href='options-general.php#default_role'>";
		echo __('Update Time Now', WOCTEXTDOMAIN );
		echo "</a></div></div>";
		
		echo '<ul class="tab-nav">'.$tab_nav.'</ul>';
		echo '<ul class="box">'.$tab_box.'</ul>';
		
		echo '</div>'; //back-settings
   	}
	
	public function meta_boxes_woc_hour_save($post_id){
		if (!isset($_POST['woc_nonce_check_value_hour'])) return $post_id;
		$nonce = $_POST['woc_nonce_check_value_hour'];
		
	 	if (!wp_verify_nonce($nonce, 'woc_nonce_check')) return $post_id;
	 	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
	 	if ('page' == $_POST['post_type']) if (!current_user_can('edit_page', $post_id)) return $post_id;
		else if (!current_user_can('edit_post', $post_id)) return $post_id;
				
		$woc_hours_meta = stripslashes_deep($_POST['woc_hours_meta']);
		update_post_meta($post_id, 'woc_hours_meta', $woc_hours_meta);		
	}
} new class_woc_post_meta();