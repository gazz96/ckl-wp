/**
 * Checkout Payment Page JavaScript
 *
 * Handles payment method selection and UI interactions
 * for the WooCommerce order payment page
 */

(function($) {
    'use strict';

    $(document).ready(function() {

        // ============================================================
        // PAYMENT METHOD SELECTION
        // ============================================================

        const paymentMethods = $('.wc_payment_methods input[type="radio"]');

        if (paymentMethods.length) {
            // Handle payment method selection
            paymentMethods.on('change', function() {
                const selectedMethod = $(this).val();
                const $label = $(this).siblings('label');
                const $parent = $(this).closest('.wc_payment_method');
                const $paymentBox = $parent.find('.payment_box');

                // Reset all payment methods
                $('.wc_payment_methods input[type="radio"]').each(function() {
                    const $thisLabel = $(this).siblings('label');
                    const $thisParent = $(this).closest('.wc_payment_method');
                    const $thisPaymentBox = $thisParent.find('.payment_box');

                    // Reset label styles
                    $thisLabel
                        .removeClass('border-blue-500 bg-blue-50')
                        .addClass('border-gray-200 bg-white hover:border-gray-300');

                    // Reset radio indicator
                    $thisLabel.find('.w-5.h-5.rounded-full')
                        .removeClass('border-blue-500')
                        .addClass('border-gray-300');
                    $thisLabel.find('.w-3.h-3.rounded-full').remove();

                    // Reset arrow icon
                    $thisLabel.find('svg').removeClass('transform rotate-180');

                    // Hide all payment boxes
                    $thisPaymentBox
                        .removeClass('block border-blue-200')
                        .addClass('hidden border-gray-200');
                });

                // Set selected payment method styles
                $label
                    .removeClass('border-gray-200 bg-white hover:border-gray-300')
                    .addClass('border-blue-500 bg-blue-50');

                // Update radio indicator
                $label.find('.w-5.h-5.rounded-full')
                    .removeClass('border-gray-300')
                    .addClass('border-blue-500');

                // Add blue dot
                const $indicator = $label.find('.w-5.h-5.rounded-full');
                if ($indicator.find('.w-3.h-3.rounded-full').length === 0) {
                    $indicator.append('<div class="w-3 h-3 rounded-full bg-blue-500"></div>');
                }

                // Rotate arrow icon
                $label.find('svg').addClass('transform rotate-180');

                // Show payment box for selected method
                $paymentBox
                    .removeClass('hidden border-gray-200')
                    .addClass('block border-blue-200');
            });

            // Initialize first payment method as selected
            paymentMethods.filter(':checked').trigger('change');
        }

        // ============================================================
        // FORM VALIDATION
        // ============================================================

        const form = $('#order_review');

        if (form.length) {
            form.on('submit', function(e) {
                let isValid = true;
                const $selectedMethod = paymentMethods.filter(':checked');

                // Validate payment method selection
                if ($selectedMethod.length === 0) {
                    isValid = false;
                    showErrorMessage('<?php esc_html_e('Please select a payment method.', 'woocommerce'); ?>');
                }

                // Validate payment fields if gateway has fields
                if (isValid && $selectedMethod.length) {
                    const $paymentBox = $selectedMethod.closest('.wc_payment_method').find('.payment_box');
                    const $requiredFields = $paymentBox.find('[required]');

                    $requiredFields.each(function() {
                        if ($(this).is(':visible') && !$(this).val()) {
                            isValid = false;
                            $(this).addClass('border-red-500');
                        } else {
                            $(this).removeClass('border-red-500');
                        }
                    });

                    if (!isValid) {
                        showErrorMessage('<?php esc_html_e('Please fill in all required fields.', 'woocommerce'); ?>');
                    }
                }

                if (!isValid) {
                    e.preventDefault();
                    // Scroll to first error
                    $('html, body').animate({
                        scrollTop: $('.wc_payment_methods').offset().top - 100
                    }, 300);
                }
            });
        }

        // ============================================================
        // LOADING STATE
        // ============================================================

        const placeOrderBtn = $('#place_order');

        if (placeOrderBtn.length) {
            form.on('submit', function() {
                if (!placeOrderBtn.hasClass('processing')) {
                    placeOrderBtn
                        .addClass('processing opacity-75 cursor-not-allowed')
                        .prop('disabled', true);

                    const originalText = placeOrderBtn.text();
                    placeOrderBtn.data('original-text', originalText);
                    placeOrderBtn.html('<span class="inline-block animate-spin mr-2">⟳</span> Processing...');
                }
            });
        }

        // ============================================================
        // HELPER FUNCTIONS
        // ============================================================

        function showErrorMessage(message) {
            // Remove existing error messages
            $('.ckl-checkout-error').remove();

            // Create error message
            const errorHtml = `
                <div class="ckl-checkout-error mb-4 p-4 bg-red-50 border border-red-200 rounded-lg flex items-center space-x-3">
                    <svg class="w-6 h-6 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-red-800">${message}</span>
                </div>
            `;

            // Insert before form
            form.before(errorHtml);

            // Auto-remove after 5 seconds
            setTimeout(function() {
                $('.ckl-checkout-error').fadeOut(300, function() {
                    $(this).remove();
                });
            }, 5000);
        }

        // ============================================================
        // TERMS AND CONDITIONS TOGGLE
        // ============================================================

        const termsCheckbox = $('#terms');

        if (termsCheckbox.length) {
            termsCheckbox.on('change', function() {
                const $label = $(this).siblings('label');
                if ($(this).is(':checked')) {
                    $label.find('input[type="checkbox"]').prop('checked', true);
                }
            });
        }

        // ============================================================
        // RESPONSIVE TABLE FIX
        // ============================================================

        function makeTableResponsive() {
            const table = $('.shop_table');
            const tableWrapper = table.parent();

            if (window.innerWidth < 640) {
                if (!tableWrapper.hasClass('overflow-x-auto')) {
                    tableWrapper.addClass('overflow-x-auto -mx-4 px-4');
                }
            } else {
                tableWrapper.removeClass('overflow-x-auto -mx-4 px-4');
            }
        }

        // Run on load and resize
        makeTableResponsive();
        $(window).on('resize', makeTableResponsive);

        // ============================================================
        // ANIMATE ELEMENTS ON SCROLL
        // ============================================================

        function animateOnScroll() {
            const elements = $('.bg-white.rounded-xl');

            elements.each(function() {
                const $element = $(this);
                const elementTop = $element.offset().top;
                const viewportBottom = $(window).scrollTop() + $(window).height();

                if (elementTop < viewportBottom) {
                    $element.addClass('animate-fade-in');
                }
            });
        }

        // Run on load and scroll
        $(window).on('scroll', animateOnScroll);
        animateOnScroll();

    });

})(jQuery);
