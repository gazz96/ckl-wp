/**
 * Vehicle Single Page JavaScript
 *
 * Handles booking form interactions, real-time availability checking,
 * and dynamic calendar functionality
 */

(function($) {
    'use strict';

    // Current calendar state
    let calendarState = {
        currentMonth: new Date().toISOString().slice(0, 7), // YYYY-MM format
        vehicleId: null
    };

    $(document).ready(function() {

        // ============================================================
        // BOOKING FORM HANDLER
        // ============================================================

        const bookingForm = $('#ckl-booking-form');
        if (bookingForm.length) {

            initializeDateTimeInputs();
            handleLocationChanges();
            initializeRealTimeAvailabilityCheck();
            initializeServiceListeners();
            handleFormSubmission();
        }

        /**
         * Initialize date and time inputs with validation
         */
        function initializeDateTimeInputs() {
            const today = new Date();
            const tomorrow = new Date(today);
            tomorrow.setDate(tomorrow.getDate() + 1);
            const minReturn = new Date(today);
            minReturn.setDate(minReturn.getDate() + 2);

            const pickupDateInput = bookingForm.find('input[name="pickup_date"]');
            const pickupTimeInput = bookingForm.find('input[name="pickup_time"]');
            const returnDateInput = bookingForm.find('input[name="return_date"]');
            const returnTimeInput = bookingForm.find('input[name="return_time"]');

            // Set minimum dates
            pickupDateInput.attr('min', today.toISOString().split('T')[0]);
            returnDateInput.attr('min', minReturn.toISOString().split('T')[0]);

            // Update return date minimum when pickup date changes
            pickupDateInput.on('change', function() {
                const pickupDate = new Date($(this).val());
                const minReturnDate = new Date(pickupDate);
                minReturnDate.setDate(minReturnDate.getDate() + 2);

                returnDateInput.attr('min', minReturnDate.toISOString().split('T')[0]);

                // If return date is before new minimum, update it
                if (returnDateInput.val()) {
                    const currentReturn = new Date(returnDateInput.val());
                    if (currentReturn < minReturnDate) {
                        returnDateInput.val(minReturnDate.toISOString().split('T')[0]);
                    }
                }

                // Trigger availability check if all fields are filled
                checkAllFieldsAndCheckAvailability();
            });

            pickupTimeInput.on('change', function() {
                checkAllFieldsAndCheckAvailability();
            });

            returnDateInput.on('change', function() {
                checkAllFieldsAndCheckAvailability();
            });

            returnTimeInput.on('change', function() {
                checkAllFieldsAndCheckAvailability();
            });
        }

        /**
         * Handle location field changes
         */
        function handleLocationChanges() {
            const returnLocationType = bookingForm.find('select[name="return_location_type"]');
            const returnLocationPickerWrapper = $('#return-location-picker-wrapper');

            // Return location type change
            returnLocationType.on('change', function() {
                const value = $(this).val();

                if (value === 'different') {
                    returnLocationPickerWrapper.removeClass('hidden');
                } else {
                    returnLocationPickerWrapper.addClass('hidden');
                    // Clear return location values when switching back to "same as pickup"
                    bookingForm.find('input[name="return_location"]').val('');
                    bookingForm.find('input[name="return_location_name"]').val('');
                }
            });
        }

        /**
         * Initialize real-time availability checking
         */
        function initializeRealTimeAvailabilityCheck() {
            // Check availability when all date/time fields are filled
            bookingForm.find('input[name="pickup_date"], input[name="pickup_time"], input[name="return_date"], input[name="return_time"]').on('blur', function() {
                checkAllFieldsAndCheckAvailability();
            });
        }

        /**
         * Check if all date/time fields are filled and trigger availability check
         */
        function checkAllFieldsAndCheckAvailability() {
            const pickupDate = bookingForm.find('input[name="pickup_date"]').val();
            const pickupTime = bookingForm.find('input[name="pickup_time"]').val();
            const returnDate = bookingForm.find('input[name="return_date"]').val();
            const returnTime = bookingForm.find('input[name="return_time"]').val();

            if (pickupDate && pickupTime && returnDate && returnTime) {
                checkAvailability();
            }
        }

        /**
         * Initialize service change listeners
         */
        function initializeServiceListeners() {
            // Trigger availability check when services change
            bookingForm.find('input[name^="services"]').on('change', function() {
                const pickupDate = bookingForm.find('input[name="pickup_date"]').val();
                const pickupTime = bookingForm.find('input[name="pickup_time"]').val();
                const returnDate = bookingForm.find('input[name="return_date"]').val();
                const returnTime = bookingForm.find('input[name="return_time"]').val();

                if (pickupDate && pickupTime && returnDate && returnTime) {
                    checkAvailability();
                }
            });
        }

        /**
         * Perform AJAX availability check
         */
        function checkAvailability() {
            // Collect selected services
            const selectedServices = {};
            bookingForm.find('input[name^="services"]').each(function() {
                const $input = $(this);
                const serviceId = $input.data('service-id');
                if (!serviceId) return;

                if ($input.is('[type="checkbox"]')) {
                    if ($input.is(':checked')) {
                        selectedServices[serviceId] = 1;
                    }
                } else if ($input.is('[type="number"]')) {
                    const quantity = parseInt($input.val()) || 0;
                    if (quantity > 0) {
                        selectedServices[serviceId] = quantity;
                    }
                }
            });

            const formData = {
                action: 'ckl_check_availability',
                availability_nonce: cklVehicleData.availability_nonce,
                vehicle_id: cklVehicleData.vehicle_id,
                pickup_date: bookingForm.find('input[name="pickup_date"]').val(),
                pickup_time: bookingForm.find('input[name="pickup_time"]').val(),
                return_date: bookingForm.find('input[name="return_date"]').val(),
                return_time: bookingForm.find('input[name="return_time"]').val(),
                services: selectedServices
            };

            // Show loading state
            const resultContainer = $('#availability-result');
            resultContainer.html('<div class="text-center py-4"><div class="animate-spin inline-block w-6 h-6 border-2 border-primary border-t-transparent rounded-full"></div></div>');

            $.ajax({
                url: cklVehicleData.ajax_url,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        showAvailabilityResult(response.data);
                    } else {
                        showAvailabilityError(response.data);
                    }
                },
                error: function() {
                    showAvailabilityError({ message: 'Network error. Please try again.' });
                }
            });
        }

        /**
         * Display availability success with pricing breakdown
         */
        function showAvailabilityResult(data) {
            const resultContainer = $('#availability-result');

            let html = '<div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded mb-4">';
            html += '<div class="flex items-center mb-2">';
            html += '<svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
            html += '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>';
            html += '</svg>';
            html += '<span class="font-semibold">Available!</span>';
            html += '</div>';
            html += '</div>';

            // Price breakdown
            html += '<div class="bg-white border rounded-lg p-4 space-y-2">';
            html += '<h4 class="font-semibold text-gray-900 mb-3">Price Breakdown</h4>';

            if (data.duration_days > 0) {
                html += '<div class="flex justify-between text-sm">';
                html += '<span class="text-gray-600">' + data.duration_days + ' day';
                if (data.duration_days > 1) html += 's';
                html += ' × RM ' + data.price_per_day.toFixed(2) + '</span>';
                html += '<span class="font-medium">RM ' + data.daily_total.toFixed(2) + '</span>';
                html += '</div>';
            }

            if (data.duration_hours > 0) {
                html += '<div class="flex justify-between text-sm">';
                html += '<span class="text-gray-600">' + data.duration_hours + ' hour';
                if (data.duration_hours > 1) html += 's';
                html += ' × RM ' + data.price_per_hour.toFixed(2) + '</span>';
                html += '<span class="font-medium">RM ' + data.hourly_total.toFixed(2) + '</span>';
                html += '</div>';
            }

            // Display peak pricing surcharge if applicable
            if (data.peak_surcharge && data.peak_surcharge > 0) {
                html += '<div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-3 py-2 rounded mt-2">';
                html += '<div class="flex justify-between text-sm">';
                html += '<span class="font-medium">⚠️ Peak Season Surcharge</span>';
                html += '<span class="font-semibold">RM ' + data.peak_surcharge.toFixed(2) + '</span>';
                html += '</div>';
                html += '</div>';
            }

            // Display additional services
            if (data.services && data.services.length > 0) {
                html += '<div class="border-t pt-2 mt-2">';
                html += '<div class="text-sm font-semibold text-gray-700 mb-2">Additional Services:</div>';
                data.services.forEach(function(service) {
                    html += '<div class="flex justify-between text-sm text-gray-600">';
                    html += '<span>' + escapeHtml(service.title);
                    if (service.quantity > 1) html += ' (×' + service.quantity + ')';
                    if (service.pricing_type === 'daily' && service.duration_days > 0) {
                        html += ' - ' + service.duration_days + ' day(s)';
                    } else if (service.pricing_type === 'hourly' && service.duration_hours > 0) {
                        html += ' - ' + service.duration_hours + ' hour(s)';
                    }
                    html += '</span>';
                    html += '<span>RM ' + service.total.toFixed(2) + '</span>';
                    html += '</div>';
                });
                html += '</div>';
            }

            html += '<div class="border-t pt-2 mt-2">';
            html += '<div class="flex justify-between">';
            html += '<span class="font-semibold text-gray-900">Total</span>';
            html += '<span class="font-bold text-lg text-primary">' + data.formatted_total + '</span>';
            html += '</div>';
            html += '</div>';
            html += '</div>';

            resultContainer.html(html);
        }

        /**
         * Display availability error
         */
        function showAvailabilityError(data) {
            const resultContainer = $('#availability-result');

            let html = '<div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded">';
            html += '<div class="flex items-center">';
            html += '<svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
            html += '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>';
            html += '</svg>';
            html += '<span class="font-semibold">' + data.message + '</span>';
            html += '</div>';
            html += '</div>';

            resultContainer.html(html);
        }

        /**
         * Handle form submission
         */
        function handleFormSubmission() {
            bookingForm.on('submit', function(e) {
                e.preventDefault();

                const submitButton = bookingForm.find('button[type="submit"]');

                // Validate all required fields
                const pickupDate = bookingForm.find('input[name="pickup_date"]').val();
                const pickupTime = bookingForm.find('input[name="pickup_time"]').val();
                const returnDate = bookingForm.find('input[name="return_date"]').val();
                const returnTime = bookingForm.find('input[name="return_time"]').val();
                const pickupLocation = bookingForm.find('input[name="pickup_location"]').val();
                const pickupLocationName = bookingForm.find('input[name="pickup_location_name"]').val();
                const returnLocationType = bookingForm.find('select[name="return_location_type"]').val();
                const returnLocation = bookingForm.find('input[name="return_location"]').val();

                if (!pickupDate || !pickupTime || !returnDate || !returnTime) {
                    showFormError('Please complete all date and time fields.');
                    return;
                }

                if (!pickupLocation || !pickupLocationName) {
                    showFormError('Please select a pickup location.');
                    return;
                }

                // Validate return location if "different" is selected
                if (returnLocationType === 'different' && !returnLocation) {
                    showFormError('Please select a return location.');
                    return;
                }

                // Validate guest fields for non-logged users
                const guestEmail = bookingForm.find('input[name="guest_email"]').val();
                const guestPhone = bookingForm.find('input[name="guest_phone"]').val();

                if (bookingForm.find('input[name="guest_email"]').length > 0) {
                    if (!guestEmail || !guestPhone) {
                        showFormError('Please provide your email and phone number.');
                        return;
                    }
                    if (!isValidEmail(guestEmail)) {
                        showFormError('Please enter a valid email address.');
                        return;
                    }
                }

                // Collect form data
                // Collect selected services
                const selectedServices = {};
                bookingForm.find('input[name^="services"]').each(function() {
                    const $input = $(this);
                    const serviceId = $input.data('service-id');
                    if (!serviceId) return;

                    if ($input.is('[type="checkbox"]')) {
                        if ($input.is(':checked')) {
                            selectedServices[serviceId] = 1;
                        }
                    } else if ($input.is('[type="number"]')) {
                        const quantity = parseInt($input.val()) || 0;
                        if (quantity > 0) {
                            selectedServices[serviceId] = quantity;
                        }
                    }
                });

                const formData = {
                    action: 'ckl_create_booking_order',
                    vehicle_id: bookingForm.find('input[name="vehicle_id"]').val(),
                    product_id: bookingForm.find('input[name="product_id"]').val(),
                    pickup_date: pickupDate,
                    pickup_time: pickupTime,
                    return_date: returnDate,
                    return_time: returnTime,
                    pickup_location: pickupLocation,
                    pickup_location_name: pickupLocationName,
                    return_location_type: returnLocationType,
                    return_location: returnLocation,
                    return_location_name: bookingForm.find('input[name="return_location_name"]').val(),
                    promo_code: bookingForm.find('input[name="promo_code"]').val(),
                    guest_email: guestEmail,
                    guest_phone: guestPhone,
                    services: selectedServices,
                    booking_nonce: bookingForm.find('input[name="booking_nonce"]').val()
                };

                // Show loading state
                submitButton.prop('disabled', true);
                const originalText = submitButton.text();
                submitButton.html('<span class="inline-block animate-spin mr-2">⟳</span> Processing...');

                // Make AJAX request
                $.ajax({
                    url: cklVehicleData.ajax_url,
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            showFormSuccess('Order created! Redirecting to payment...');
                            // Redirect to payment page
                            setTimeout(function() {
                                window.location.href = response.data.redirect_url;
                            }, 1500);
                        } else {
                            showFormError(response.data.message || 'Failed to create booking. Please try again.');
                            submitButton.prop('disabled', false);
                            submitButton.text(originalText);
                        }
                    },
                    error: function(xhr, status, error) {
                        showFormError('Network error. Please check your connection and try again.');
                        submitButton.prop('disabled', false);
                        submitButton.text(originalText);
                    }
                });
            });
        }

        /**
         * Show form error message
         */
        function showFormError(message) {
            const resultContainer = $('#availability-result');
            let html = '<div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded">';
            html += '<div class="flex items-center">';
            html += '<svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
            html += '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>';
            html += '</svg>';
            html += '<span class="font-semibold">' + escapeHtml(message) + '</span>';
            html += '</div>';
            html += '</div>';
            resultContainer.html(html);

            // Scroll to error message
            $('html, body').animate({
                scrollTop: resultContainer.offset().top - 100
            }, 300);
        }

        /**
         * Show form success message
         */
        function showFormSuccess(message) {
            const resultContainer = $('#availability-result');
            let html = '<div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">';
            html += '<div class="flex items-center">';
            html += '<svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
            html += '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>';
            html += '</svg>';
            html += '<span class="font-semibold">' + escapeHtml(message) + '</span>';
            html += '</div>';
            html += '</div>';
            resultContainer.html(html);
        }

        /**
         * Validate email format
         */
        function isValidEmail(email) {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(email);
        }

        // ============================================================
        // CALENDAR FUNCTIONALITY
        // ============================================================

        const calendarContainer = $('.availability-calendar-container');
        if (calendarContainer.length) {
            calendarState.vehicleId = calendarContainer.data('vehicle-id');
            if (calendarState.vehicleId) {
                loadCalendarMonth();
            }
        }

        /**
         * Load calendar data for current month via AJAX
         */
        function loadCalendarMonth() {
            const formData = {
                action: 'ckl_get_calendar_availability',
                nonce: cklVehicleData.nonce,
                vehicle_id: calendarState.vehicleId,
                month: calendarState.currentMonth
            };

            $.ajax({
                url: cklVehicleData.ajax_url,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        renderCalendar(response.data);
                    } else {
                        calendarContainer.html('<div class="text-center text-red-600 p-4">Error loading calendar</div>');
                    }
                },
                error: function() {
                    calendarContainer.html('<div class="text-center text-red-600 p-4">Network error loading calendar</div>');
                }
            });
        }

        /**
         * Render calendar grid dynamically
         */
        function renderCalendar(data) {
            let html = '';

            // Header with navigation
            html += '<div class="flex items-center justify-between mb-4">';
            html += '<h3 class="font-bold text-2xl">Availability Calendar</h3>';
            html += '<div class="flex items-center gap-2">';
            html += '<button type="button" class="calendar-prev-month p-2 hover:bg-gray-100 rounded" title="Previous month">';
            html += '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
            html += '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>';
            html += '</svg></button>';
            html += '<span class="font-semibold min-w-[120px] text-center">' + escapeHtml(data.month_name) + '</span>';
            html += '<button type="button" class="calendar-next-month p-2 hover:bg-gray-100 rounded" title="Next month">';
            html += '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
            html += '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>';
            html += '</svg></button>';
            html += '</div></div>';

            // Legend
            html += '<div class="flex flex-wrap gap-4 mb-4 text-sm">';
            html += '<div class="flex items-center gap-1">';
            html += '<div class="w-4 h-4 bg-green-100 border rounded"></div>';
            html += '<span>Available</span>';
            html += '</div>';
            html += '<div class="flex items-center gap-1">';
            html += '<div class="w-4 h-4 bg-red-100 border rounded"></div>';
            html += '<span>Fully Booked</span>';
            html += '</div>';
            html += '<div class="flex items-center gap-1">';
            html += '<div class="w-4 h-4 bg-yellow-100 border rounded"></div>';
            html += '<span>Limited</span>';
            html += '</div>';
            html += '</div>';

            // Calendar grid
            html += '<div class="grid grid-cols-7 gap-1 text-center">';

            // Day headers
            const dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            dayNames.forEach(function(day) {
                html += '<div class="font-semibold text-sm p-2">' + day + '</div>';
            });

            // Empty cells before first day
            for (let i = 0; i < data.first_day; i++) {
                html += '<div class="p-2"></div>';
            }

            // Calendar days
            $.each(data.days, function(dayNum, dayData) {
                let bgClass = '';
                let clickHandler = '';

                switch(dayData.status) {
                    case 'past':
                        bgClass = 'bg-gray-50 text-gray-400';
                        break;
                    case 'full':
                        bgClass = 'bg-red-100 text-red-800';
                        break;
                    case 'limited':
                        bgClass = 'bg-yellow-100 text-yellow-800';
                        break;
                    case 'available':
                    default:
                        bgClass = 'bg-green-100 text-green-800 hover:bg-green-200 cursor-pointer';
                        clickHandler = ' data-calendar-date="' + escapeHtml(dayData.date) + '"';
                        break;
                }

                html += '<div class="p-2 border rounded ' + bgClass + '"' + clickHandler + '>' + dayNum + '</div>';
            });

            html += '</div>';

            calendarContainer.html(html);

            // Attach event handlers
            attachCalendarHandlers();
        }

        /**
         * Attach event handlers to calendar elements
         */
        function attachCalendarHandlers() {
            // Month navigation
            calendarContainer.find('.calendar-prev-month').on('click', function() {
                const current = new Date(calendarState.currentMonth + '-01');
                current.setMonth(current.getMonth() - 1);
                calendarState.currentMonth = current.toISOString().slice(0, 7);
                loadCalendarMonth();
            });

            calendarContainer.find('.calendar-next-month').on('click', function() {
                const current = new Date(calendarState.currentMonth + '-01');
                current.setMonth(current.getMonth() + 1);
                calendarState.currentMonth = current.toISOString().slice(0, 7);
                loadCalendarMonth();
            });

            // Date click
            calendarContainer.find('[data-calendar-date]').on('click', function() {
                const date = $(this).data('calendar-date');
                const pickupInput = $('input[name="pickup_date"]');
                if (pickupInput.length) {
                    pickupInput.val(date);
                    pickupInput.trigger('change');

                    // Scroll to booking form
                    $('html, body').animate({
                        scrollTop: bookingForm.offset().top - 100
                    }, 500);
                }
            });
        }

        /**
         * Escape HTML to prevent XSS
         */
        function escapeHtml(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, function(m) { return map[m]; });
        }

        // ============================================================
        // IMAGE GALLERY (Lightweight implementation)
        // ============================================================

        const galleryContainer = $('.vehicle-gallery');
        if (galleryContainer.length) {
            const mainImage = galleryContainer.find('.main-image');
            const thumbnails = galleryContainer.find('.thumbnail');

            thumbnails.on('click', function() {
                const src = $(this).data('src');
                mainImage.attr('src', src);
                thumbnails.removeClass('active');
                $(this).addClass('active');
            });
        }

        // ============================================================
        // SHARE FUNCTIONALITY
        // ============================================================

        $('.share-button').on('click', function(e) {
            e.preventDefault();
            const platform = $(this).data('platform');
            const url = encodeURIComponent(window.location.href);
            const title = encodeURIComponent(document.title);

            let shareUrl;
            switch(platform) {
                case 'whatsapp':
                    shareUrl = `https://wa.me/?text=${title}%20${url}`;
                    break;
                case 'facebook':
                    shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
                    break;
                case 'twitter':
                    shareUrl = `https://twitter.com/intent/tweet?url=${url}&text=${title}`;
                    break;
            }

            window.open(shareUrl, '_blank', 'width=600,height=400');
        });

    });

})(jQuery);
