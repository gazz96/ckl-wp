/**
 * CKL Customer Dashboard JavaScript
 *
 * Handles AJAX interactions for customer dashboard
 *
 * @package CKL_Car_Rental
 */

(function($) {
    'use strict';

    /**
     * Cancel Booking
     */
    $(document).on('click', '.ckl-cancel-booking-btn', function(e) {
        e.preventDefault();

        var $button = $(this);
        var bookingId = $button.data('booking-id');
        var nonce = $button.data('nonce');

        if (!confirm(ckl_dashboard.i18n.confirm_cancel)) {
            return;
        }

        // Disable button and show loading
        $button.prop('disabled', true).html('<span class="spinner is-active"></span> ' + ckl_dashboard.i18n.canceling);

        $.ajax({
            url: ckl_dashboard.ajax_url,
            type: 'POST',
            data: {
                action: 'ckl_cancel_booking',
                booking_id: bookingId,
                nonce: nonce
            },
            success: function(response) {
                if (response.success) {
                    // Show success message
                    showNotice('success', response.data.message);

                    // Reload page after short delay
                    setTimeout(function() {
                        window.location.reload();
                    }, 1500);
                } else {
                    showNotice('error', response.data.message || ckl_dashboard.i18n.error);
                    $button.prop('disabled', false).text(ckl_dashboard.i18n.cancel);
                }
            },
            error: function() {
                showNotice('error', ckl_dashboard.i18n.error);
                $button.prop('disabled', false).text(ckl_dashboard.i18n.cancel);
            }
        });
    });

    /**
     * Update Profile
     */
    $('#ckl-profile-form').on('submit', function(e) {
        e.preventDefault();

        var $form = $(this);
        var $button = $form.find('button[type="submit"]');

        // Disable button and show loading
        $button.prop('disabled', true).html('<span class="spinner is-active"></span> ' + ckl_dashboard.i18n.saving);

        $.ajax({
            url: ckl_dashboard.ajax_url,
            type: 'POST',
            data: $form.serialize() + '&action=ckl_update_profile',
            success: function(response) {
                if (response.success) {
                    showNotice('success', response.data.message);
                    // Reload to show updated profile
                    setTimeout(function() {
                        window.location.href = window.location.href.split('?')[0] + '?profile_updated=true';
                    }, 1500);
                } else {
                    showNotice('error', response.data.message || ckl_dashboard.i18n.error);
                    $button.prop('disabled', false).text(ckl_dashboard.i18n.save_changes);
                }
            },
            error: function() {
                showNotice('error', ckl_dashboard.i18n.error);
                $button.prop('disabled', false).text(ckl_dashboard.i18n.save_changes);
            }
        });
    });

    /**
     * Upload Document
     */
    $('#ckl-document-upload-form').on('submit', function(e) {
        e.preventDefault();

        var $form = $(this);
        var $button = $form.find('button[type="submit"]');
        var formData = new FormData(this);

        // Check if any file is selected
        var licenseFile = $('#license_upload')[0].files.length;
        var idFile = $('#id_upload')[0].files.length;

        if (!licenseFile && !idFile) {
            showNotice('error', ckl_dashboard.i18n.select_file);
            return;
        }

        formData.append('action', 'ckl_upload_document');

        // Disable button and show loading
        $button.prop('disabled', true).html('<span class="spinner is-active"></span> ' + ckl_dashboard.i18n.uploading);

        $.ajax({
            url: ckl_dashboard.ajax_url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    showNotice('success', response.data.message);
                    // Reload to show uploaded documents
                    setTimeout(function() {
                        window.location.reload();
                    }, 1500);
                } else {
                    showNotice('error', response.data.message || ckl_dashboard.i18n.error);
                    $button.prop('disabled', false).text(ckl_dashboard.i18n.upload);
                }
            },
            error: function() {
                showNotice('error', ckl_dashboard.i18n.error);
                $button.prop('disabled', false).text(ckl_dashboard.i18n.upload);
            }
        });
    });

    /**
     * Send Support Message
     */
    $('#ckl-support-form').on('submit', function(e) {
        e.preventDefault();

        var $form = $(this);
        var $button = $form.find('button[type="submit"]');

        // Disable button and show loading
        $button.prop('disabled', true).html('<span class="spinner is-active"></span> ' + ckl_dashboard.i18n.sending);

        $.ajax({
            url: ckl_dashboard.ajax_url,
            type: 'POST',
            data: $form.serialize() + '&action=ckl_send_support',
            success: function(response) {
                if (response.success) {
                    showNotice('success', response.data.message);
                    $form[0].reset();
                } else {
                    showNotice('error', response.data.message || ckl_dashboard.i18n.error);
                }
                $button.prop('disabled', false).text(ckl_dashboard.i18n.send_message);
            },
            error: function() {
                showNotice('error', ckl_dashboard.i18n.error);
                $button.prop('disabled', false).text(ckl_dashboard.i18n.send_message);
            }
        });
    });

    /**
     * Show notice message
     */
    function showNotice(type, message) {
        var className = type === 'success' ? 'woocommerce-message' : 'woocommerce-error';
        var notice = $('<div class="' + className + '" style="display:none;">' + message + '</div>');

        // Insert at the top of the content
        $('.ckl-my-account-content-inner').prepend(notice);

        // Slide down
        notice.slideDown();

        // Auto hide after 5 seconds
        setTimeout(function() {
            notice.slideUp(function() {
                $(this).remove();
            });
        }, 5000);
    }

})(jQuery);
