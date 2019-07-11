jQuery(document).ready(function ($) {

    $(document).on('click', '.woc-schedules .woc-schedule .woc-day-name', function () {

        is_self = false;
        if( $(this).parent().hasClass('opened') ) is_self = true;

        $(this).parent().parent().find('.woc-schedule').removeClass('opened').find('.woc-day-schedules').slideUp('slow');

        if (is_self) return;

        $(this).parent().addClass('opened').find('.woc-day-schedules').slideDown('slow');
    });

    $(document).on('click', '.shop-status-bar .shop-status-bar-inline.close-bar', function () {
        $(this).parent().slideUp('slow');
    });

    // Inline popups
    $('.woc-add-to-cart').magnificPopup({
        // delegate: 'a',
        removalDelay: 500,
        callbacks: {
            beforeOpen: function() {
                this.st.mainClass = this.st.el.attr('data-effect');
            }
        },
        midClick: true,
    });

});