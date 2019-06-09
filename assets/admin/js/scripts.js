jQuery(document).ready(function ($) {

    $(function () {
        $(".woc_repeats").sortable({
            handle: ".woc_repeat_sort",
            revert: true
        });
    });

    $(document).on('change', '.woc_section .woc_switch_checkbox', function () {

        post_id     = $(this).data('id');
        woc_active  = $(this).is(":checked") ? true : false;

        jQuery.ajax({
            type: 'POST',
            url: woc_ajax.woc_ajaxurl,
            context: this,
            data: {
                "action"     : "woc_switch_active",
                "post_id"    : post_id,
                "woc_active" : woc_active,
            },
            success: function (data) {

                if( data.length != 0 ) location.reload();
            }
        });
    })

    $(document).on('click', '.woc_section .woc_repeat_copy', function () {

        unique_id   = $(this).parent().attr('unique-id');
        day_id      = $(this).parent().attr('day-id');
        open        = $( '#woc_tp_start_' + unique_id ).val();
        close       = $( '#woc_tp_end_' + unique_id ).val();

        jQuery.ajax({
            type: 'POST',
            url: woc_ajax.woc_ajaxurl,
            context: this,
            data: {
                "action" : "woc_add_schedule",
                "day_id" : day_id,
                "open"   : open,
                "close"  : close,
            },
            success: function (data) {

                old_day_id      = day_id;
                old_unique_id   = unique_id;

                $( ".woc_section .woc_days .woc_day" ).each(function( i ) {
                    
                    if( $(this).hasClass( day_id ) ) return true;

                    
                    this_day_id     = $(this).attr('id');
                    this_unique_id  = $.now() + Math.floor( Math.random() * 100 );
                    
                    data = data.replace( new RegExp(old_day_id, 'g'), this_day_id );
                    data = data.replace( new RegExp(old_unique_id, 'g'), this_unique_id );

                    old_day_id      = this_day_id;
                    old_unique_id   = this_unique_id;

                    $(this).find('.woc_repeats').append( data );
                });
            }
        });
    })

    $(document).on('click', '.woc_section .woc_repeat_remove', function () {

        $(this).parent().fadeOut().remove();
    })

    $(document).on('click', '.woc_section .woc_add_schedule', function () {

        day_id = $(this).attr('day-id');

        jQuery.ajax({
            type: 'POST',
            url: woc_ajax.woc_ajaxurl,
            context: this,
            data: {
                "action": "woc_add_schedule",
                "day_id": day_id
            },
            success: function (data) {

                $(this).parent().find('.woc_repeats').append(data);
            }
        });

    })

    $(document).on('click', '.woc_section .woc_days .woc_day .woc_day_header', function () {

        is_self = false;
        if ($(this).parent().hasClass('woc_day_active')) is_self = true;

        $('.woc_day').removeClass('woc_day_active');
        $('.woc_day').find('.woc_day_content').slideUp();

        if (is_self) return;

        $(this).parent().addClass('woc_day_active');
        $(this).parent().find('.woc_day_content').slideDown();
    })

});