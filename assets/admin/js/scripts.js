/**
 * Admin Scripts
 */

(function ($, window, document, pluginObject) {

    "use strict";

    $(window).on('load', function () {
        let updateContainer = $('#woc-open-close-pro-update'),
            detailsButton = updateContainer.find('.thickbox.open-plugin-details-modal');

        detailsButton.removeClass('thickbox').attr('target', '_blank').attr('href', pluginObject.tempProDownload).html(pluginObject.tempProDownloadTxt);
    });


    $(document).on('click', '.wooopenclose-shortcode', function () {

        let inputField = document.createElement('input'),
            htmlElement = $(this),
            ariaLabel = htmlElement.attr('aria-label');

        console.log('ok');

        document.body.appendChild(inputField);
        inputField.value = htmlElement.html();
        inputField.select();
        document.execCommand('copy', false);
        inputField.remove();

        htmlElement.attr('aria-label', pluginObject.copyText);

        setTimeout(function () {
            htmlElement.attr('aria-label', ariaLabel);
        }, 5000);
    });


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