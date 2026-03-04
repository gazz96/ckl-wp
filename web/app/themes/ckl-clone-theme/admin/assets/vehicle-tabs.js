/**
 * Vehicle Meta Box Tabs
 */

(function($) {
    'use strict';

    // Initialize on document ready
    $(document).ready(function() {
        initVehicleTabs();
        initRepeaterFields();
        initCalendarPreview();
        initImageUploaders();
        initServicePriceOverride();
    });

    /**
     * Initialize tabbed interface
     */
    function initVehicleTabs() {
        $('.ckl-tabs-nav').on('click', '.ckl-tab-button', function(e) {
            e.preventDefault();

            const $button = $(this);
            const tabId = $button.data('tab');
            const $container = $button.closest('.ckl-tabs-container');

            // Remove active class from all tabs and contents
            $container.find('.ckl-tab-button').removeClass('active');
            $container.find('.ckl-tab-content').removeClass('active');

            // Add active class to clicked tab and corresponding content
            $button.addClass('active');
            $container.find('#' + tabId).addClass('active');

            // Store active tab in localStorage
            localStorage.setItem('ckl_active_vehicle_tab', tabId);
        });

        // Restore active tab from localStorage
        const activeTab = localStorage.getItem('ckl_active_vehicle_tab');
        if (activeTab) {
            const $tabButton = $('.ckl-tab-button[data-tab="' + activeTab + '"]');
            if ($tabButton.length) {
                $tabButton.trigger('click');
            }
        }
    }

    /**
     * Initialize repeater fields (special pricing, peak pricing, etc.)
     */
    function initRepeaterFields() {
        // Add repeater item
        $('.ckl-add-repeater').on('click', function(e) {
            e.preventDefault();

            const $button = $(this);
            const template = $button.data('template');

            // Check if there's a data-target attribute
            let $container;
            if ($button.data('target')) {
                $container = $('#' + $button.data('target'));
            } else {
                $container = $button.prev('.ckl-repeater-container');
            }

            if (!$container.length) {
                console.error('Repeater container not found');
                return;
            }

            const index = Date.now();

            let html = template.replace(/\{index\}/g, index);

            // Build the new repeater item HTML
            let newItemHtml = '<div class="ckl-repeater-item">';

            if (template.adjustment_type !== undefined) {
                // Peak pricing template
                newItemHtml += `
                    <div class="ckl-repeater-header">
                        <span class="ckl-repeater-title">${template.name || 'New Peak Pricing'}</span>
                        <button type="button" class="button ckl-remove-repeater" data-confirm="Remove this peak pricing?">
                            Remove
                        </button>
                    </div>
                    <table class="form-table">
                        <tr>
                            <th>Period Name</th>
                            <td><input type="text" name="peak_pricing[${index}][name]" value="${template.name || ''}" class="regular-text" placeholder="e.g., Hari Raya 2026"></td>
                        </tr>
                        <tr>
                            <th>Start Date</th>
                            <td><input type="date" name="peak_pricing[${index}][start_date]" value="${template.start_date || ''}"></td>
                        </tr>
                        <tr>
                            <th>End Date</th>
                            <td><input type="date" name="peak_pricing[${index}][end_date]" value="${template.end_date || ''}"></td>
                        </tr>
                        <tr>
                            <th>Adjustment Type</th>
                            <td>
                                <select name="peak_pricing[${index}][adjustment_type]">
                                    <option value="percentage" ${template.adjustment_type === 'percentage' ? 'selected' : ''}>Percentage</option>
                                    <option value="fixed" ${template.adjustment_type === 'fixed' ? 'selected' : ''}>Fixed Amount (RM)</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>Amount</th>
                            <td>
                                <input type="number" name="peak_pricing[${index}][amount]" value="${template.amount || ''}" step="0.01" min="0" placeholder="e.g., 50 for 50% or RM50">
                                <p class="description">Percentage increase (e.g., 50 = 50% more) or fixed RM amount per day</p>
                            </td>
                        </tr>
                    </table>
                `;
            } else {
                // Special pricing template
                newItemHtml += `
                    <div class="ckl-repeater-header">
                        <span class="ckl-repeater-title">${template.name || 'New Pricing Offer'}</span>
                        <button type="button" class="button ckl-remove-repeater" data-confirm="Remove this pricing offer?">
                            Remove
                        </button>
                    </div>
                    <table class="form-table">
                        <tr>
                            <th>Offer Name</th>
                            <td><input type="text" name="special_pricing[${index}][name]" value="${template.name || ''}" class="regular-text"></td>
                        </tr>
                        <tr>
                            <th>Start Date</th>
                            <td><input type="date" name="special_pricing[${index}][start_date]" value="${template.start_date || ''}"></td>
                        </tr>
                        <tr>
                            <th>End Date</th>
                            <td><input type="date" name="special_pricing[${index}][end_date]" value="${template.end_date || ''}"></td>
                        </tr>
                        <tr>
                            <th>Special Price (RM/day)</th>
                            <td><input type="number" name="special_pricing[${index}][price]" value="${template.price || ''}" step="0.01" min="0"></td>
                        </tr>
                    </table>
                `;
            }

            newItemHtml += '</div>';
            $container.append(newItemHtml);

            // Initialize any new elements
            $container.find('.ckl-repeater-item:last').find(':input').first().focus();
        });

        // Remove repeater item
        $(document).on('click', '.ckl-remove-repeater', function(e) {
            e.preventDefault();

            const $item = $(this).closest('.ckl-repeater-item');

            // Confirm before removing
            const confirmMsg = $item.find('.ckl-remove-repeater').data('confirm') || 'Are you sure?';
            if (!confirm(confirmMsg)) {
                return;
            }

            $item.fadeOut(300, function() {
                $(this).remove();
            });
        });

        // Update repeater title when name input changes
        $(document).on('input', '.ckl-repeater-item input[name*="[name]"]', function() {
            const $item = $(this).closest('.ckl-repeater-item');
            const name = $(this).val() || 'New Entry';
            $item.find('.ckl-repeater-title').text(name);
        });
    }

    /**
     * Initialize calendar preview
     */
    function initCalendarPreview() {
        const $preview = $('.ckl-calendar-preview');

        if (!$preview.length) return;

        const vehicleId = $preview.data('vehicle-id');
        const today = new Date();
        let currentMonth = today.getMonth();
        let currentYear = today.getFullYear();

        renderCalendar(currentMonth, currentYear);

        // Month navigation
        $preview.find('.ckl-calendar-prev').on('click', function(e) {
            e.preventDefault();
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            renderCalendar(currentMonth, currentYear);
        });

        $preview.find('.ckl-calendar-next').on('click', function(e) {
            e.preventDefault();
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            renderCalendar(currentMonth, currentYear);
        });

        function renderCalendar(month, year) {
            // Get availability data via AJAX
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'ckl_get_admin_calendar_availability',
                    nonce: cklVehicleAdmin.nonce,
                    vehicle_id: vehicleId,
                    month: year + '-' + String(month + 1).padStart(2, '0')
                },
                success: function(response) {
                    if (response.success) {
                        renderCalendarGrid(response.data, month, year);
                    }
                }
            });
        }

        function renderCalendarGrid(data, month, year) {
            const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                               'July', 'August', 'September', 'October', 'November', 'December'];

            // Update header
            $preview.find('.ckl-calendar-month-year').text(monthNames[month] + ' ' + year);

            // Clear existing grid
            const $grid = $preview.find('.ckl-calendar-grid');
            $grid.empty();

            // Add day headers
            const dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            dayNames.forEach(function(day) {
                $grid.append('<div class="ckl-calendar-day-header">' + day + '</div>');
            });

            // Get first day of month and total days
            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const todayStr = today.toISOString().split('T')[0];

            // Add empty cells before first day
            for (let i = 0; i < firstDay; i++) {
                $grid.append('<div class="ckl-calendar-day empty"></div>');
            }

            // Add days
            for (let day = 1; day <= daysInMonth; day++) {
                const dateStr = year + '-' + String(month + 1).padStart(2, '0') + '-' + String(day).padStart(2, '0');
                let statusClass = 'available';
                let statusText = '';

                if (dateStr < todayStr) {
                    statusClass = 'past';
                    statusText = 'Past';
                } else if (data.days && data.days[day]) {
                    const dayData = data.days[day];
                    statusClass = dayData.status;
                    statusText = dayData.status === 'full' ? 'Booked' : 'Available';
                }

                $grid.append(
                    '<div class="ckl-calendar-day ' + statusClass + '" data-date="' + dateStr + '">' +
                        day +
                        '<span class="status-text">' + statusText + '</span>' +
                    '</div>'
                );
            }
        }
    }

    /**
     * Initialize image uploaders
     */
    function initImageUploaders() {
        $(document).on('click', '.ckl-upload-image-button', function(e) {
            e.preventDefault();

            const $button = $(this);
            const $input = $button.prev('input[type="hidden"]');
            const $preview = $button.siblings('.ckl-image-preview');

            // Create media uploader frame
            const frame = wp.media({
                title: $button.data('title') || 'Select Image',
                button: {
                    text: $button.data('button-text') || 'Use This Image'
                },
                multiple: false
            });

            frame.on('select', function() {
                const attachment = frame.state().get('selection').first().toJSON();
                $input.val(attachment.id);

                if ($preview.length) {
                    $preview.attr('src', attachment.url).show();
                }

                $button.text($button.data('change-text') || 'Change Image');
            });

            frame.open();
        });

        // Remove image
        $(document).on('click', '.ckl-remove-image-button', function(e) {
            e.preventDefault();

            const $button = $(this);
            const $input = $button.siblings('input[type="hidden"]');
            const $preview = $button.siblings('.ckl-image-preview');
            const $uploadButton = $button.siblings('.ckl-upload-image-button');

            $input.val('');

            if ($preview.length) {
                $preview.attr('src', '').hide();
            }

            $uploadButton.text($uploadButton.data('original-text') || 'Upload Image');
        });
    }

    /**
     * Sync pricing fields
     */
    function syncPricingFields() {
        const $hourlyRate = $('#vehicle_price_per_hour');
        const $dailyRate = $('#vehicle_price_per_day');
        const $multiplier = $('#pricing_multiplier');

        if (!$hourlyRate.length || !$dailyRate.length) return;

        // Calculate daily rate from hourly
        $hourlyRate.on('input', function() {
            const hourly = parseFloat($(this).val()) || 0;
            const multiplier = parseFloat($multiplier.val()) || 4;
            $dailyRate.val((hourly * multiplier).toFixed(2));
        });

        // Calculate hourly rate from daily
        $dailyRate.on('input', function() {
            const daily = parseFloat($(this).val()) || 0;
            const multiplier = parseFloat($multiplier.val()) || 4;
            $hourlyRate.val((daily / multiplier).toFixed(2));
        });
    }

    /**
     * Initialize service price override checkboxes
     */
    function initServicePriceOverride() {
        $(document).on('change', '#tab-services input[name*="[override_price]"]', function() {
            const $checkbox = $(this);
            const $priceInput = $checkbox.siblings('input[type="number"]');

            if ($checkbox.is(':checked')) {
                $priceInput.prop('disabled', false).focus();
            } else {
                $priceInput.prop('disabled', true);
            }
        });

        // Initialize on page load
        $('#tab-services input[name*="[override_price]"]').each(function() {
            const $checkbox = $(this);
            if (!$checkbox.is(':checked')) {
                $checkbox.siblings('input[type="number"]').prop('disabled', true);
            }
        });
    }

    /**
     * Validate required fields
     */
    function validateRequiredFields() {
        let isValid = true;

        $('#post-body').find('[data-required]').each(function() {
            const $field = $(this);
            const value = $field.val();

            if (!value || value.trim() === '') {
                isValid = false;
                $field.addClass('error');
            } else {
                $field.removeClass('error');
            }
        });

        return isValid;
    }

    // Make functions available globally
    window.cklVehicleTabs = {
        init: initVehicleTabs,
        syncPricing: syncPricingFields,
        validate: validateRequiredFields
    };

})(jQuery);

/**
 * Publish/Update validation
 */
jQuery(document).ready(function($) {
    $('#publish, #save-post').on('click', function(e) {
        if (!window.cklVehicleTabs || !window.cklVehicleTabs.validate()) {
            e.preventDefault();
            alert('Please fill in all required fields.');
            return false;
        }
    });
});
