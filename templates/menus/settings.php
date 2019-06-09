<?php	


/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 



class class_woc_settings_page {
	
    public function __construct(){
		
    }
	
	public function woc_settings_options($options = array()){
		
		$options[__('Settings',WOCTEXTDOMAIN)] = array(
			
			'woc_active_set'=>array(
				'css_class'=>'woc_active_set',					
				'title'=>__('Select the Active Timeset',WOCTEXTDOMAIN),
				'input_type'=>'select',
				'option_details'=>__('Select a timeshet what system will follow to maintain. <b>Otherwise Your shop will not Close</b>',WOCTEXTDOMAIN),
				'input_args'=> woc_get_hour_sets(),
			),
			'woc_enable_order_restriction'=>array(
				'css_class'=>'woc_enable_order_restriction',					
				'title'=>__('Enable Order Number Restriction',WOCTEXTDOMAIN),
				'input_type'=>'select',
				'option_details'=>__('Do you want to restrict maximum Order on a Day?',WOCTEXTDOMAIN),
				'input_args'=> array(
					'no'=>__('NO',WOCTEXTDOMAIN),
					'yes'=>__('Yes',WOCTEXTDOMAIN),
				),
			),
			'woc_empty_cart_after_shop_close'=>array(
				'css_class'=>'woc_empty_cart_after_shop_close',
				'title'=>__('Empty cart on Shop Close',WOCTEXTDOMAIN),
				'input_type'=>'select',
				'option_details'=>__('Cart will be empty as soon as shop is Closed.',WOCTEXTDOMAIN),
				'input_args'=> array(
					'yes'=>__('Yes',WOCTEXTDOMAIN),
					'no'=>__('NO',WOCTEXTDOMAIN),
				),
			),
		);
		
		$options[__('Alertbar Options',WOCTEXTDOMAIN)] = array(
			
			'woc_alertbar_position'=>array(
				'css_class'=>'woc_alertbar_position',					
				'title'=>__('Alertbar Position',WOCTEXTDOMAIN),
				'input_type'=>'select',
				'input_values'=>'bottom',
				'option_details'=>__('Select a position where alertbar will be shown.',WOCTEXTDOMAIN),
				'input_args'=> array(
					'bottom'=>__('Bottom',WOCTEXTDOMAIN),
					'none'=>__('DO Not Show',WOCTEXTDOMAIN),
					'top'=>__('Top',WOCTEXTDOMAIN),
					'middle'=>__('Middle',WOCTEXTDOMAIN),
				),
			),
			
			'woc_alertbar_text'=>array(
				'css_class'=>'woc_alertbar_text',					
				'title'=>__('Alertbar Text',WOCTEXTDOMAIN),
				'input_type'=>'text',
				'option_details'=>__('Set text for the button in Alertbar',WOCTEXTDOMAIN),
				'placeholder'=> __('Hide This',WOCTEXTDOMAIN),
			),
			
			'woc_alertbar_font_size'=>array(
				'css_class'=>'woc_alertbar_font_size',					
				'title'=>__('Alertbar Font Size',WOCTEXTDOMAIN),
				'input_type'=>'text',
				'option_details'=>__('Set your prefered font size for Alertbar Text',WOCTEXTDOMAIN),
				'placeholder'=> '16px',
			),
			'woc_alertbar_color'=>array(
				'css_class'=>'woc_alertbar_color',					
				'title'=>__('Alertbar Color',WOCTEXTDOMAIN),
				'option_details'=>__('Set custom color for Alertbar',WOCTEXTDOMAIN),
				'input_type'=>'text',
			),
			'woc_alertbar_background_color'=>array(
				'css_class'=>'woc_alertbar_background_color',					
				'title'=>__('Alertbar Background Color',WOCTEXTDOMAIN),
				'option_details'=>__('Set custom background color for Alertbar',WOCTEXTDOMAIN),
				'input_type'=>'text',
			),
		);
		
		$options[__('Alertbox Options',WOCTEXTDOMAIN)] = array(
			
			'woc_alertbox_message_font_size'=>array(
				'css_class'=>'woc_box_message_font_size',					
				'title'=>__('AlertBox Message Font Size',WOCTEXTDOMAIN),
				'input_type'=>'text',
				'option_details'=>__('Set your prefered font size for alertbox.',WOCTEXTDOMAIN),
				'placeholder'=> '20px',
			),
			
			'woc_alertbox_icon_color'=>array(
				'css_class'=>'woc_alertbox_icon_color',					
				'title'=>__('Alertbox Icon Color',WOCTEXTDOMAIN),
				'input_type'=>'text',
				'option_details'=>__('Set your custom color for alertbox Icon.',WOCTEXTDOMAIN),
			),
			
			'woc_alertbox_message_color'=>array(
				'css_class'=>'woc_alertbox_message_color',					
				'title'=>__('Alertbox Message Color',WOCTEXTDOMAIN),
				'input_type'=>'text',
				'option_details'=>__('Set your custom color for alertbox Message.',WOCTEXTDOMAIN),
			),
			
		);
		
		$options = apply_filters( 'woc_settings_options', $options );
		return $options;
	}
		
	public function woc_settings_options_form(){
		global $post;
			
		$woc_settings_options = $this->woc_settings_options();
		$html = '';
		$html.= '<div class="back-settings">';			
		$html_nav = '';
		$html_box = '';
		
		$i=1;
		foreach($woc_settings_options as $key=>$options)
		{
			if ( $i == 1 ):
				$html_nav.= '<li nav="'.$i.'" class="nav'.$i.' active">'.$key.'</li>';	
				$html_box.= '<li style="display: block;" class="box'.$i.' tab-box active">';
			else:
				$html_nav.= '<li nav="'.$i.'" class="nav'.$i.'">'.$key.'</li>';
				$html_box.= '<li style="display: none;" class="box'.$i.' tab-box">';
			endif;
			
			foreach($options as $option_key=>$option_info)
			{
				$option_value =  get_option( $option_key );				
				
				if(!isset($option_info['placeholder'])) $placeholder = '';
				else $placeholder = $option_info['placeholder'];
				
				if(!isset($option_info['input_values'])) $option_info['input_values'] = '';
				if(!isset($option_info['status'])) $option_info['status'] = '';
				if(!isset($option_info['option_details'])) $option_info['option_details'] = '';

				if(empty($option_value)) $option_value = $option_info['input_values'];
				
				$html_box.= '<div class="pb_section '.$option_info['css_class'].'">';
				$html_box.= '<div class="pb_section_title">'.$option_info['title'].'</div>';
				$html_box.= '<div class="pb_section_info">'.$option_info['option_details'].'</div>';
				$html_box.= '<div class="pb_section_input">';
							
				if($option_info['input_type'] == 'text') 
					$html_box.= '<input type="text" '.$option_info['status'].' placeholder="'.$placeholder.'" name="'.$option_key.'" id="'.$option_key.'" value="'.$option_value.'" /> ';					
				elseif($option_info['input_type'] == 'text-multi'){
					$input_args = $option_info['input_args'];
					foreach($input_args as $input_args_key=>$input_args_values)
					{
						if(empty($option_value[$input_args_key])) $option_value[$input_args_key] = $input_args[$input_args_key];
						$html_box.= '<label>'.$input_args_key.'<br/><input class="job-bm-color" type="text" placeholder="" name="'.$option_key.'['.$input_args_key.']" value="'.$option_value[$input_args_key].'" /></label><br/>';	
					}
				}					
				elseif($option_info['input_type'] == 'textarea') $html_box.= '<textarea placeholder="" name="'.$option_key.'" >'.$option_value.'</textarea> ';
				elseif($option_info['input_type'] == 'radio'){
					$input_args = $option_info['input_args'];
					foreach($input_args as $input_args_key=>$input_args_values)
					{
						if($input_args_key == $option_value) $checked = 'checked';
						else $checked = '';
						$html_box.= '<label><input class="'.$option_key.'" type="radio" '.$checked.' value="'.$input_args_key.'" name="'.$option_key.'"   >'.$input_args_values.'</label><br/>';
					}
				}
				elseif($option_info['input_type'] == 'select'){
					$input_args = $option_info['input_args'];
					$html_box.= '<select name="'.$option_key.'" >';
					foreach($input_args as $input_args_key=>$input_args_values)
					{
						if($input_args_key == $option_value) $selected = 'selected';
						else $selected = '';
						$html_box.= '<option '.$selected.' value="'.$input_args_key.'">'.$input_args_values.'</option>';
					}
					$html_box.= '</select>';
				}					
				elseif($option_info['input_type'] == 'checkbox'){
					$input_args = $option_info['input_args'];
					foreach($input_args as $input_args_key=>$input_args_values)
					{
						if(empty($option_value[$input_args_key])) $checked = '';
						else $checked = 'checked';
						$html_box.= '<label><input '.$checked.' value="'.$input_args_key.'" name="'.$option_key.'['.$input_args_key.']"  type="checkbox" >'.$input_args_values.'</label><br/>';
					}
				}
				elseif($option_info['input_type'] == 'file'){
					$html_box.= '<input type="text" id="file_'.$option_key.'" name="'.$option_key.'" value="'.$option_value.'" /><br />';
					$html_box.= '<input id="upload_button_'.$option_key.'" class="upload_button_'.$option_key.' button" type="button" value="Upload File" />';					
					$html_box.= '<br /><br /><div style="overflow:hidden;max-height:150px;max-width:150px;" class="logo-preview"><img width="100%" src="'.$option_value.'" /></div>';
					$html_box.= '
					<script>
						jQuery(document).ready(function($){
							var custom_uploader; 
							jQuery("#upload_button_'.$option_key.'").click(function(e) {
							e.preventDefault();
							if (custom_uploader) {
								custom_uploader.open();
								return;
							}
							custom_uploader = wp.media.frames.file_frame = wp.media({
								title: "Choose File",
								button: {
									text: "Choose File"
								},
								multiple: false
							});
							custom_uploader.on("select", function() {
								attachment = custom_uploader.state().get("selection").first().toJSON();
								jQuery("#file_'.$option_key.'").val(attachment.url);
								jQuery(".logo-preview img").attr("src",attachment.url);											
							});
							custom_uploader.open();
						});
					})
					</script>';					
				}		
				$html_box.= '</div>'; // pb_section_input
				$html_box.= '</div>'; 
			}
			$html_box.= '</li>';
			$i++;
		}
		$html.= '<ul class="tab-nav">';
		$html.= $html_nav;			
		$html.= '</ul>';
		$html.= '<ul class="box">';
		$html.= $html_box;
		$html.= '</ul>';		
		$html.= '</div>';			
		return $html;
	}
} new class_woc_settings_page();

	
// Saving Form Data
	
$nonce = isset( $_POST['woc_settings_nonce_check_value'] ) ? $_POST['woc_settings_nonce_check_value'] : '';
if( !empty( $nonce ) && wp_verify_nonce($nonce, 'woc_settings_nonce_check') ) {

	if( ! empty( $_POST['woc_hidden'] ) ):	
		if( $_POST['woc_hidden'] == 'Y' ) :
			$class_woc_settings_page = new class_woc_settings_page();
			$woc_settings_options = $class_woc_settings_page->woc_settings_options();
			foreach($woc_settings_options as $options_tab=>$options){
				foreach($options as $option_key=>$option_data){
					if(!isset($_POST[$option_key])) $_POST[$option_key] = '';
					
					${$option_key} = stripslashes_deep($_POST[$option_key]);
					update_option($option_key, ${$option_key});
				}
			}
			?>
			<div class="updated"><p><strong><?php _e('Changes Saved.', WOCTEXTDOMAIN ); ?></strong></p></div>
			<?php	
		endif; 
	else:
		$class_woc_settings_page = new class_woc_settings_page();
		$woc_settings_options = $class_woc_settings_page->woc_settings_options();
		foreach($woc_settings_options as $options_tab=>$options){
			foreach($options as $option_key=>$option_data) 
				${$option_key} = get_option( $option_key );
		}
	endif;
}
?>

<div class="wrap">
	<?php echo "<h2>WooCommerce Open Close - ".__('Settings', WOCTEXTDOMAIN)."</h2>";?>
	<form class="pb_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<input type="hidden" name="woc_hidden" value="Y" />
		<?php 
		wp_nonce_field('woc_settings_nonce_check', 'woc_settings_nonce_check_value');
				
		$class_woc_settings_page = new class_woc_settings_page();
		echo $class_woc_settings_page->woc_settings_options_form(); 
		?>
		<input class="button button-orange" type="submit" name="Submit" value="<?php _e('Save Changes',WOCTEXTDOMAIN ); ?>" />			
	</form>
</div>
