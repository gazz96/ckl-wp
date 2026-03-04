/**
 * QuickSwap Admin Branding - Media Uploader JavaScript
 *
 * Handles media uploader for logo and background image uploads
 *
 * @package QuickSwap
 * @since 1.1.0
 */

(function($) {
    'use strict';

    /**
     * Initialize media uploader
     */
    $(document).ready(function() {
        initMediaUploaders();
        initColorPickers();
    });

    /**
     * Initialize media uploaders
     */
    function initMediaUploaders() {
        $('.quickswap-upload-image').on('click', function(e) {
            e.preventDefault();

            const field = $(this).data('field');
            const uploader = $('.quickswap-image-uploader[data-field="' + field + '"]');
            const input = uploader.find('input[type="hidden"]');
            const preview = uploader.find('.quickswap-image-preview');
            const removeBtn = uploader.find('.quickswap-remove-image');

            // Create media uploader frame
            const frame = wp.media({
                title: quickswapBranding.uploadTitle,
                button: {
                    text: quickswapBranding.uploadButton
                },
                multiple: false
            });

            // When an image is selected
            frame.on('select', function() {
                const attachment = frame.state().get('selection').first().toJSON();

                // Update input value
                input.val(attachment.id);

                // Update preview
                preview.html('<img src="' + attachment.sizes.thumbnail.url + '" alt="">');

                // Show remove button
                removeBtn.show();
            });

            // Open the media uploader
            frame.open();
        });

        // Remove image button
        $('.quickswap-remove-image').on('click', function(e) {
            e.preventDefault();

            const field = $(this).data('field');
            const uploader = $('.quickswap-image-uploader[data-field="' + field + '"]');
            const input = uploader.find('input[type="hidden"]');
            const preview = uploader.find('.quickswap-image-preview');

            // Clear input value
            input.val(0);

            // Clear preview
            preview.html('');

            // Hide remove button
            $(this).hide();
        });
    }

    /**
     * Initialize color pickers - sync color and text inputs
     */
    function initColorPickers() {
        $('.quickswap-color-picker').on('input', function() {
            const textInput = $(this).siblings('.quickswap-color-text');
            textInput.val($(this).val());
        });

        $('.quickswap-color-text').on('input', function() {
            const colorInput = $(this).siblings('.quickswap-color-picker');
            const value = $(this).val();

            // Validate hex color
            if (/^#[0-9A-Fa-f]{6}$/.test(value)) {
                colorInput.val(value);
            }
        });

        // On form submit, sync text values to color inputs
        $('form').on('submit', function() {
            $('.quickswap-color-text').each(function() {
                const colorInput = $(this).siblings('.quickswap-color-picker');
                const fieldName = colorInput.attr('name');
                const value = $(this).val();

                // Update the actual form field
                $('input[name="' + fieldName + '"]').not('.quickswap-color-text').val(value);
            });
        });
    }

})(jQuery);
