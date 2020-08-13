jQuery(document).ready(function ($) {

    $(document).on('click', '.wooopenclose-layout-1 .wooopenclose-schedules .wooopenclose-schedule .wooopenclose-day-name', function () {

        is_self = false;
        if ($(this).parent().hasClass('opened')) is_self = true;

        $(this).parent().parent().find('.wooopenclose-schedule').removeClass('opened').find('.wooopenclose-day-schedules').slideUp('slow');

        if (is_self) return;

        $(this).parent().addClass('opened').find('.wooopenclose-day-schedules').slideDown('slow');
    });

    $(document).on('click', '.shop-status-bar .shop-status-bar-inline.close-bar', function () {
        $(this).parent().slideUp('slow');
    });

    $(document).on('click', '.wooopenclose-add-to-cart', function () {
        let disAllowMessage = $(this).data('disallowmessage'),
            wocPopupBox = $('#wooopenclose-box-container').find('.wooopenclose-box');

        if ( typeof disAllowMessage !== "undefined" && disAllowMessage.length && disAllowMessage.length > 0) {
            wocPopupBox.html(disAllowMessage);
        }
    });

    // Inline popups
    $('.wooopenclose-add-to-cart').magnificPopup({
        // delegate: 'a',
        removalDelay: 500,
        callbacks: {
            beforeOpen: function () {
                this.st.mainClass = this.st.el.attr('data-effect');
            }
        },
        midClick: true,
    });

});