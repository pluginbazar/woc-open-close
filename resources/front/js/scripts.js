jQuery(document).ready(function($)
	{
		
		$(document).on('click', '.woc_day', function() {
			
			var day_key = $(this).parent().attr('day_key');

			$('.woc_schedules .single_schedule').each(function(){
				_current_day = $(this).attr('day_key');
				if( $( '.day_key_' + _current_day ).is(':visible') && _current_day != day_key ) {
					$( '.day_key_' + _current_day ).hide( 'slow' );
				}
			});
		
			$( '.day_key_' + day_key ).slideToggle('slow');
		})
		
		
		$(document).on('click', '#woc_hide_alert_bar', function() {
			$("#woc_alert_bar_message").fadeOut();
		})
		
		$(document).on('click', '.woc_cart_button', function() {
			
			var woc_message 	= $(this).attr('woc_message');
			
			/* product_id = $(this).attr( 'data-product_id' );
			if( typeof product_id === 'undefined' || product_id.length == 0 ) product_id = 0;
			
			jQuery.ajax(
				{
			type: 'POST',
			url: woc_ajax.woc_ajaxurl,
			context:this,
			data: {
				"action"	: "woc_ajax_add_to_cart",
				"product_id": product_id,
			},
			success: function(data) {}
				}); */
			
			$('.alert_box_message').text(woc_message);
			$('.woc_alert_box_container').fadeIn();
			
		})
		
		$(document).on('click', '.alert_box_close', function(){
			$('.woc_alert_box_container').fadeOut();
		})
		
	});