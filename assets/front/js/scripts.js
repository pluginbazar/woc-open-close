jQuery(document).ready(function ($) {

    $(document).on('click', '.woc-schedules .woc-schedule .woc-day-name', function () {

        is_self = false;
        if( $(this).parent().hasClass('opened') ) is_self = true;

        $('.woc-schedules .woc-schedule').removeClass('opened').find('.woc-day-schedules').slideUp('slow');

        if (is_self) return;

        $(this).parent().addClass('opened').find('.woc-day-schedules').slideDown('slow');
    });

    $(document).on('click', '.shop-status-bar .shop-status-bar-inline.close-bar', function () {
        $(this).parent().slideUp('slow');
    });

    $(document).on('click', '.woc-box-container .woc-box .box-close', function () {
        $(this).parent().slideUp('fast').parent().fadeOut('slow');
    });

    $(document).on('click', '.woc-add-to-cart', function (e) {

        $('.woc-box-container').fadeIn().find('.woc-box').slideDown();
        return false;
    });

});