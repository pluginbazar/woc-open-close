/**
 * This scripts will run on both Admin and Front of WP
 *
 * @author Pluginbazar
 * @package /assets/scripts.js
 **/

(function ($, window, document, pluginObject) {
    "use strict";

    $(function () {
        $(".woc_repeats").sortable({
            handle: ".woc_repeat_sort",
            revert: true
        });
    });

    $(window).load(function () {
        $('.wooopenclose-loader-wrap').fadeOut('slow');
    });

    $(document).on('trigger-wooopenclose-loader', function () {
        $('.wooopenclose-loader-wrap').fadeIn();
        setTimeout(function () {
            $('.wooopenclose-loader-wrap').fadeOut('slow');
        }, 3000);
    });

    $(document).on('click', '.woc_section .woc_add_schedule', function () {

        let day_id = $(this).data('day-id');

        $.ajax({
            type: 'POST',
            url: pluginObject.ajaxurl,
            context: this,
            data: {
                'action': 'woc_add_schedule',
                'day_id': day_id
            },
            success: function (response) {
                if (response.success) {
                    $(this).parent().find('.woc_repeats').append(response.data);
                }
            }
        });
    });


    $(document).on('click', '.woc_section .woc_repeat_copy', function () {

        let unique_id = $(this).parent().data('unique-id'),
            day_id = $(this).parent().data('day-id'),
            eachDay = $('.woc_section .woc_days .woc_day'),
            open = $('#woc_tp_start_' + unique_id).val(),
            close = $('#woc_tp_end_' + unique_id).val();

        $.ajax({
            type: 'POST',
            url: pluginObject.ajaxurl,
            context: this,
            data: {
                'action': 'woc_add_schedule',
                'day_id': day_id,
                'open': open,
                'close': close,
            },
            success: function (response) {

                let old_day_id = day_id,
                    old_unique_id = unique_id,
                    data = response.data;

                eachDay.each(function (i) {

                    if ($(this).hasClass(day_id)) {
                        return true;
                    }

                    let wocRepeats = $(this).find('.woc_repeats'),
                        this_day_id = $(this).attr('id'),
                        this_unique_id = $.now() + Math.floor(Math.random() * 100);

                    data = data.replace(new RegExp(old_day_id, 'g'), this_day_id);
                    data = data.replace(new RegExp(old_unique_id, 'g'), this_unique_id);

                    old_day_id = this_day_id;
                    old_unique_id = this_unique_id;

                    wocRepeats.append(data);
                });
            }
        });
    });


    $(document).on('click', '.woc_section .woc_repeat_remove', function () {
        if (confirm(pluginObject.removeConf)) {
            $(this).parent().fadeOut().remove();
        }
    });

    $(document).on('click', '.woc_section .woc_days .woc_day .woc_day_header', function () {

        let is_self = false,
            wocDay = $('.woc_day');

        if ($(this).parent().hasClass('woc_day_active')) {
            is_self = true;
        }

        wocDay.removeClass('woc_day_active');
        wocDay.find('.woc_day_content').slideUp();

        if (is_self) return;

        $(this).parent().addClass('woc_day_active');
        $(this).parent().find('.woc_day_content').slideDown();
    });

})(jQuery, window, document, wooopenclose);
