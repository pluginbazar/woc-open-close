/**
 * Admin Scripts
 */

(function ($, window, document, pluginObject) {

    "use strict";

    $(document).on('change', '.woc_section .woc_switch_checkbox', function () {

        let checkBox = $(this),
            post_id = checkBox.data('id'),
            woc_active = !!checkBox.is(":checked");

        jQuery.ajax({
            type: 'POST',
            url: pluginObject.ajaxurl,
            context: this,
            data: {
                "action": "woc_switch_active",
                "post_id": post_id,
                "woc_active": woc_active,
            },
            success: function (response) {
                if (response.success) {
                    location.reload();
                }
            }
        });
    });

})(jQuery, window, document, wooopenclose);