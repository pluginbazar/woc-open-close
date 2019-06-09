jQuery(document).ready(function($)
	{
		
		$(function() {
			$( ".woc_hours_licontainer" ).sortable({ handle: ".woc_single_sorter" });
		});
				
		var val = $( "#woc_message_show_hour option:selected").val();
		if ( val != 'yes' ) $('.woc_message_format').css('display','none');
		
		$('.woc_btn_insert_input').on('click', function() {
			
			var day_key = $(this).attr('day_key');
			$('.woc_hours_meta_'+day_key+'').addClass( 'adding_slack' );
			
			jQuery.ajax(
				{
			type: 'POST',
			url: woc_ajax.woc_ajaxurl,
			context:this,
			data: {
				"action"	: "woc_add_more_time_slot",
				"day_key"	:day_key
			},
			success: function(data) {	
				$('.woc_hours_meta_'+day_key+'').removeClass( 'adding_slack' );
				$('.woc_hours_meta_'+day_key+'').append( data );
			}
				});
		});
		
		$('#woc_message_show_hour').on('change', function() {
			if ( this.value == 'yes' ) $('.woc_message_format').fadeIn();
			else $('.woc_message_format').fadeOut();
		});
		
		
	});
	
	jQuery(document).on('click','.woc_delete_single_schedule', function(){
		
		var ul_classes = jQuery(this).closest('li').parent().attr('class').split(' ');
		
		if( jQuery( '.' + ul_classes[1] + ' li' ).length <= 1 ) return;
		jQuery(this).closest('li').remove();
			
	});