<?php
/**
 * Peak Price Calendar Admin Page
 *
 * Visual calendar interface for managing GLOBAL peak prices that apply to ALL vehicles
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Render Peak Price Calendar page
 */
function ckl_peak_price_calendar_page_html() {
    if (!current_user_can('manage_options')) {
        return;
    }

    // Get all peak prices
    $peak_prices = get_option('ckl_global_peak_prices', array());

    // Calculate stats
    $active_count = 0;
    $upcoming_count = 0;
    $current_date = current_time('Y-m-d');

    foreach ($peak_prices as $peak) {
        if ($peak['active']) {
            $active_count++;
            if ($peak['start_date'] >= $current_date) {
                $upcoming_count++;
            }
        }
    }
    ?>
    <div class="wrap ckl-peak-price-calendar">
        <h1>
            <span class="dashicons dashicons-calendar-alt" style="margin-top: 4px;"></span>
            <?php _e('Global Peak Price Calendar', 'ckl-car-rental'); ?>
        </h1>

        <div class="ckl-calendar-stats" style="margin-bottom: 20px;">
            <div class="ckl-stat-box" style="display: inline-block; background: #fff; padding: 15px 20px; margin-right: 15px; border-left: 4px solid #0073aa; box-shadow: 0 1px 1px rgba(0,0,0,.04);">
                <strong style="display: block; font-size: 14px; color: #666;">
                    <?php _e('Total Peak Periods', 'ckl-car-rental'); ?>
                </strong>
                <span style="font-size: 28px; font-weight: 600; color: #0073aa;"><?php echo count($peak_prices); ?></span>
            </div>
            <div class="ckl-stat-box" style="display: inline-block; background: #fff; padding: 15px 20px; margin-right: 15px; border-left: 4px solid #00a32a; box-shadow: 0 1px 1px rgba(0,0,0,.04);">
                <strong style="display: block; font-size: 14px; color: #666;">
                    <?php _e('Active', 'ckl-car-rental'); ?>
                </strong>
                <span style="font-size: 28px; font-weight: 600; color: #00a32a;"><?php echo $active_count; ?></span>
            </div>
            <div class="ckl-stat-box" style="display: inline-block; background: #fff; padding: 15px 20px; border-left: 4px solid #dba617; box-shadow: 0 1px 1px rgba(0,0,0,.04);">
                <strong style="display: block; font-size: 14px; color: #666;">
                    <?php _e('Upcoming', 'ckl-car-rental'); ?>
                </strong>
                <span style="font-size: 28px; font-weight: 600; color: #dba617;"><?php echo $upcoming_count; ?></span>
            </div>
        </div>

        <div class="ckl-calendar-toolbar" style="margin-bottom: 15px;">
            <button type="button" class="button button-primary" id="ckl-add-peak-price">
                <span class="dashicons dashicons-plus" style="margin-top: 3px;"></span>
                <?php _e('Add Peak Price Period', 'ckl-car-rental'); ?>
            </button>
            <button type="button" class="button" id="ckl-refresh-calendar">
                <span class="dashicons dashicons-update" style="margin-top: 3px;"></span>
                <?php _e('Refresh', 'ckl-car-rental'); ?>
            </button>
        </div>

        <div class="ckl-color-legend" style="background: #fff; padding: 15px; margin-bottom: 15px; border: 1px solid #ddd; box-shadow: 0 1px 1px rgba(0,0,0,.04);">
            <strong><?php _e('Color Legend:', 'ckl-car-rental'); ?></strong>
            <span style="display: inline-block; width: 16px; height: 16px; background: #ffc107; margin-left: 15px; vertical-align: middle; border-radius: 2px;"></span>
            <span style="margin-left: 5px;"><?php _e('0-20% increase', 'ckl-car-rental'); ?></span>
            <span style="display: inline-block; width: 16px; height: 16px; background: #fd7e14; margin-left: 15px; vertical-align: middle; border-radius: 2px;"></span>
            <span style="margin-left: 5px;"><?php _e('21-50% increase', 'ckl-car-rental'); ?></span>
            <span style="display: inline-block; width: 16px; height: 16px; background: #dc3545; margin-left: 15px; vertical-align: middle; border-radius: 2px;"></span>
            <span style="margin-left: 5px;"><?php _e('51%+ increase', 'ckl-car-rental'); ?></span>
            <span style="display: inline-block; width: 16px; height: 16px; background: #6f42c1; margin-left: 15px; vertical-align: middle; border-radius: 2px;"></span>
            <span style="margin-left: 5px;"><?php _e('Fixed amount', 'ckl-car-rental'); ?></span>
        </div>

        <div id="ckl-peak-price-calendar" style="background: #fff; padding: 20px; border: 1px solid #ddd; box-shadow: 0 1px 1px rgba(0,0,0,.04); min-height: 600px;"></div>
    </div>

    <!-- Add/Edit Peak Price Modal -->
    <div id="ckl-peak-price-modal" style="display: none;">
        <div class="ckl-modal-overlay" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 100000;"></div>
        <div class="ckl-modal-content" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: #fff; padding: 25px; width: 500px; max-width: 90%; max-height: 90vh; overflow-y: auto; z-index: 100001; box-shadow: 0 5px 15px rgba(0,0,0,0.3); border-radius: 4px;">
            <h2 id="ckl-modal-title" style="margin-top: 0;">
                <?php _e('Add Peak Price Period', 'ckl-car-rental'); ?>
            </h2>
            <form id="ckl-peak-price-form">
                <input type="hidden" id="ckl-peak-price-id" value="">
                <input type="hidden" id="ckl-editing" value="0">

                <table class="form-table">
                    <tr>
                        <th>
                            <label for="ckl-peak-name"><?php _e('Name', 'ckl-car-rental'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="ckl-peak-name" class="regular-text" required placeholder="<?php _e('e.g., Hari Raya 2026', 'ckl-car-rental'); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="ckl-peak-start"><?php _e('Start Date', 'ckl-car-rental'); ?></label>
                        </th>
                        <td>
                            <input type="date" id="ckl-peak-start" required>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="ckl-peak-end"><?php _e('End Date', 'ckl-car-rental'); ?></label>
                        </th>
                        <td>
                            <input type="date" id="ckl-peak-end" required>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="ckl-peak-type"><?php _e('Adjustment Type', 'ckl-car-rental'); ?></label>
                        </th>
                        <td>
                            <select id="ckl-peak-type" required>
                                <option value="percentage"><?php _e('Percentage', 'ckl-car-rental'); ?></option>
                                <option value="fixed"><?php _e('Fixed Amount (RM)', 'ckl-car-rental'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="ckl-peak-amount"><?php _e('Amount', 'ckl-car-rental'); ?></label>
                        </th>
                        <td>
                            <input type="number" id="ckl-peak-amount" step="0.01" min="0" required placeholder="<?php _e('e.g., 50 for 50% or RM50', 'ckl-car-rental'); ?>">
                            <p class="description" id="ckl-amount-description">
                                <?php _e('Percentage increase (e.g., 50 = 50% more)', 'ckl-car-rental'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="ckl-peak-recurring"><?php _e('Recurring', 'ckl-car-rental'); ?></label>
                        </th>
                        <td>
                            <select id="ckl-peak-recurring">
                                <option value="none"><?php _e('None (One-time)', 'ckl-car-rental'); ?></option>
                                <option value="yearly"><?php _e('Yearly', 'ckl-car-rental'); ?></option>
                                <option value="monthly"><?php _e('Monthly', 'ckl-car-rental'); ?></option>
                            </select>
                            <p class="description">
                                <?php _e('Repeat this peak price period automatically', 'ckl-car-rental'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="ckl-peak-active"><?php _e('Active', 'ckl-car-rental'); ?></label>
                        </th>
                        <td>
                            <label>
                                <input type="checkbox" id="ckl-peak-active" value="1" checked>
                                <?php _e('Enable this peak price period', 'ckl-car-rental'); ?>
                            </label>
                        </td>
                    </tr>
                </table>

                <p class="ckl-modal-actions" style="text-align: right; margin-top: 20px;">
                    <button type="button" class="button" id="ckl-cancel-modal">
                        <?php _e('Cancel', 'ckl-car-rental'); ?>
                    </button>
                    <button type="submit" class="button button-primary">
                        <?php _e('Save Peak Price', 'ckl-car-rental'); ?>
                    </button>
                </p>
            </form>
        </div>
    </div>

    <style>
        .ckl-peak-price-calendar .fc-event {
            cursor: pointer;
        }
        .ckl-peak-price-calendar .fc-event:hover {
            opacity: 0.8;
        }
        .ckl-peak-price-calendar .fc-day-grid-container {
            overflow-y: visible !important;
        }
    </style>

    <script>
    jQuery(document).ready(function($) {
        var peakPrices = <?php echo json_encode($peak_prices); ?>;
        var calendar;
        var nextId = <?php echo !empty($peak_prices) ? max(array_column($peak_prices, 'id')) + 1 : 1; ?>;

        // Get color for peak price based on type and amount
        function getPeakPriceColor(peak) {
            if (peak.adjustment_type === 'fixed') {
                return '#6f42c1'; // Purple for fixed amounts
            }
            // For percentage
            var amount = parseFloat(peak.amount);
            if (amount <= 20) {
                return '#ffc107'; // Yellow for 0-20%
            } else if (amount <= 50) {
                return '#fd7e14'; // Orange for 21-50%
            } else {
                return '#dc3545'; // Red for 51%+
            }
        }

        // Get event title
        function getEventTitle(peak) {
            var title = peak.name;
            if (peak.adjustment_type === 'percentage') {
                title += ' (+' + peak.amount + '%)';
            } else {
                title += ' (+RM' + peak.amount + ')';
            }
            return title;
        }

        // Generate events for calendar (including recurring)
        function generateEvents() {
            var events = [];
            var startDate = new Date();
            startDate.setFullYear(startDate.getFullYear() - 1);
            var endDate = new Date();
            endDate.setFullYear(endDate.getFullYear() + 2);

            peakPrices.forEach(function(peak) {
                if (!peak.active) return;

                var peakStart = new Date(peak.start_date);
                var peakEnd = new Date(peak.end_date);

                if (peak.recurring === 'yearly') {
                    // Generate for multiple years
                    for (var year = startDate.getFullYear(); year <= endDate.getFullYear(); year++) {
                        var eventStart = new Date(peakStart);
                        eventStart.setFullYear(year);
                        var eventEnd = new Date(peakEnd);
                        eventEnd.setFullYear(year);

                        if (eventEnd >= startDate && eventStart <= endDate) {
                            events.push({
                                id: peak.id,
                                title: getEventTitle(peak),
                                start: eventStart.toISOString().split('T')[0],
                                end: eventEnd.toISOString().split('T')[0],
                                backgroundColor: getPeakPriceColor(peak),
                                borderColor: getPeakPriceColor(peak),
                                extendedProps: peak
                            });
                        }
                    }
                } else {
                    // One-time or monthly
                    if (peak.recurring === 'monthly') {
                        // Generate for current month
                        var today = new Date();
                        var monthStart = new Date(today.getFullYear(), today.getMonth(), 1);
                        var monthEnd = new Date(today.getFullYear(), today.getMonth() + 1, 0);

                        var eventStart = new Date(peakStart);
                        eventStart.setFullYear(monthStart.getFullYear());
                        eventStart.setMonth(monthStart.getMonth());
                        var eventEnd = new Date(peakEnd);
                        eventEnd.setFullYear(monthEnd.getFullYear());
                        eventEnd.setMonth(monthEnd.getMonth());

                        events.push({
                            id: peak.id,
                            title: getEventTitle(peak),
                            start: eventStart.toISOString().split('T')[0],
                            end: eventEnd.toISOString().split('T')[0],
                            backgroundColor: getPeakPriceColor(peak),
                            borderColor: getPeakPriceColor(peak),
                            extendedProps: peak
                        });
                    } else {
                        // One-time
                        if (peakEnd >= startDate && peakStart <= endDate) {
                            events.push({
                                id: peak.id,
                                title: getEventTitle(peak),
                                start: peak.start_date,
                                end: peak.end_date,
                                backgroundColor: getPeakPriceColor(peak),
                                borderColor: getPeakPriceColor(peak),
                                extendedProps: peak
                            });
                        }
                    }
                }
            });

            return events;
        }

        // Initialize FullCalendar
        function initCalendar() {
            // Check if FullCalendar is available
            if ($.fn.fullCalendar) {
                calendar = $('#ckl-peak-price-calendar').fullCalendar({
                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'month,agendaWeek,agendaDay,listMonth'
                    },
                    height: 'auto',
                    navLinks: true,
                    editable: false,
                    eventLimit: true,
                    events: generateEvents(),
                    dayClick: function(date) {
                        // Pre-fill form with clicked date
                        $('#ckl-peak-start').val(date.format('YYYY-MM-DD'));
                        $('#ckl-peak-end').val(date.format('YYYY-MM-DD'));
                        openModal();
                    },
                    eventClick: function(calEvent) {
                        // Edit existing peak price
                        editPeakPrice(calEvent.extendedProps);
                    }
                });
            } else {
                // Fallback if FullCalendar not available
                $('#ckl-peak-price-calendar').html('<p><?php _e('Loading calendar...', 'ckl-car-rental'); ?></p>');

                // Try loading from CDN
                $.when(
                    $.getScript('<?php echo esc_url('https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js'); ?>'),
                    $.Deferred(function(deferred) {
                        $(deferred.resolve);
                    })
                ).then(function() {
                    // Also load moment.js if needed
                    if (typeof moment === 'undefined') {
                        $.getScript('<?php echo esc_url('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js'); ?>').then(function() {
                            initCalendar();
                        });
                    } else {
                        initCalendar();
                    }
                }).fail(function() {
                    $('#ckl-peak-price-calendar').html('<p><?php _e('Failed to load calendar. Please ensure FullCalendar library is available.', 'ckl-car-rental'); ?></p>');
                });
            }
        }

        // Initialize
        initCalendar();

        // Modal functions
        function openModal() {
            $('#ckl-peak-price-modal').show();
            $('#ckl-modal-title').text('<?php _e('Add Peak Price Period', 'ckl-car-rental'); ?>');
            $('#ckl-editing').val('0');
            $('#ckl-peak-price-id').val('');
        }

        function closeModal() {
            $('#ckl-peak-price-modal').hide();
            $('#ckl-peak-price-form')[0].reset();
        }

        function editPeakPrice(peak) {
            $('#ckl-modal-title').text('<?php _e('Edit Peak Price Period', 'ckl-car-rental'); ?>');
            $('#ckl-editing').val('1');
            $('#ckl-peak-price-id').val(peak.id);
            $('#ckl-peak-name').val(peak.name);
            $('#ckl-peak-start').val(peak.start_date);
            $('#ckl-peak-end').val(peak.end_date);
            $('#ckl-peak-type').val(peak.adjustment_type);
            $('#ckl-peak-amount').val(peak.amount);
            $('#ckl-peak-recurring').val(peak.recurring);
            $('#ckl-peak-active').prop('checked', peak.active);
            $('#ckl-peak-price-modal').show();
        }

        // Event handlers
        $('#ckl-add-peak-price').on('click', function() {
            openModal();
        });

        $('#ckl-cancel-modal').on('click', closeModal);
        $('.ckl-modal-overlay').on('click', closeModal);

        $('#ckl-peak-type').on('change', function() {
            var type = $(this).val();
            if (type === 'percentage') {
                $('#ckl-amount-description').text('<?php _e('Percentage increase (e.g., 50 = 50% more)', 'ckl-car-rental'); ?>');
            } else {
                $('#ckl-amount-description').text('<?php _e('Fixed amount in RM (e.g., 50 = RM50 more per day)', 'ckl-car-rental'); ?>');
            }
        });

        // Form submission
        $('#ckl-peak-price-form').on('submit', function(e) {
            e.preventDefault();

            var data = {
                action: 'ckl_save_peak_price',
                nonce: '<?php echo wp_create_nonce('ckl-peak-price-nonce'); ?>',
                editing: $('#ckl-editing').val(),
                peak_price: {
                    id: $('#ckl-peak-price-id').val() || nextId++,
                    name: $('#ckl-peak-name').val(),
                    start_date: $('#ckl-peak-start').val(),
                    end_date: $('#ckl-peak-end').val(),
                    adjustment_type: $('#ckl-peak-type').val(),
                    amount: $('#ckl-peak-amount').val(),
                    recurring: $('#ckl-peak-recurring').val(),
                    active: $('#ckl-peak-active').prop('checked') ? 1 : 0,
                    priority: 100,
                    created_at: new Date().toISOString(),
                    updated_at: new Date().toISOString()
                }
            };

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: data,
                beforeSend: function() {
                    $('#ckl-peak-price-form').find('button[type="submit"]').prop('disabled', true).text('<?php _e('Saving...', 'ckl-car-rental'); ?>');
                },
                success: function(response) {
                    if (response.success) {
                        closeModal();
                        location.reload();
                    } else {
                        alert(response.data.message || '<?php _e('Error saving peak price', 'ckl-car-rental'); ?>');
                        $('#ckl-peak-price-form').find('button[type="submit"]').prop('disabled', false).text('<?php _e('Save Peak Price', 'ckl-car-rental'); ?>');
                    }
                },
                error: function() {
                    alert('<?php _e('Server error', 'ckl-car-rental'); ?>');
                    $('#ckl-peak-price-form').find('button[type="submit"]').prop('disabled', false).text('<?php _e('Save Peak Price', 'ckl-car-rental'); ?>');
                }
            });
        });

        // Refresh calendar
        $('#ckl-refresh-calendar').on('click', function() {
            location.reload();
        });
    });
    </script>
    <?php
}

/**
 * AJAX handler for saving peak price
 */
function ckl_ajax_save_peak_price() {
    check_ajax_referer('ckl-peak-price-nonce', 'nonce');

    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => __('Permission denied', 'ckl-car-rental')));
    }

    $editing = isset($_POST['editing']) ? intval($_POST['editing']) : 0;
    $peak_price = isset($_POST['peak_price']) ? $_POST['peak_price'] : array();

    if (empty($peak_price['name']) || empty($peak_price['start_date']) || empty($peak_price['end_date'])) {
        wp_send_json_error(array('message' => __('Missing required fields', 'ckl-car-rental')));
    }

    // Get existing peak prices
    $peak_prices = get_option('ckl_global_peak_prices', array());

    if ($editing) {
        // Update existing
        $index = -1;
        foreach ($peak_prices as $i => $peak) {
            if ($peak['id'] == $peak_price['id']) {
                $index = $i;
                break;
            }
        }

        if ($index >= 0) {
            $peak_prices[$index] = array(
                'id' => intval($peak_price['id']),
                'name' => sanitize_text_field($peak_price['name']),
                'start_date' => sanitize_text_field($peak_price['start_date']),
                'end_date' => sanitize_text_field($peak_price['end_date']),
                'adjustment_type' => sanitize_text_field($peak_price['adjustment_type']),
                'amount' => floatval($peak_price['amount']),
                'recurring' => sanitize_text_field($peak_price['recurring']),
                'active' => boolval($peak_price['active']),
                'priority' => 100,
                'created_at' => $peak_prices[$index]['created_at'],
                'updated_at' => current_time('mysql')
            );
        }
    } else {
        // Add new
        $peak_prices[] = array(
            'id' => intval($peak_price['id']),
            'name' => sanitize_text_field($peak_price['name']),
            'start_date' => sanitize_text_field($peak_price['start_date']),
            'end_date' => sanitize_text_field($peak_price['end_date']),
            'adjustment_type' => sanitize_text_field($peak_price['adjustment_type']),
            'amount' => floatval($peak_price['amount']),
            'recurring' => sanitize_text_field($peak_price['recurring']),
            'active' => boolval($peak_price['active']),
            'priority' => 100,
            'created_at' => current_time('mysql'),
            'updated_at' => current_time('mysql')
        );
    }

    // Sort by start date
    usort($peak_prices, function($a, $b) {
        return strtotime($a['start_date']) - strtotime($b['start_date']);
    });

    update_option('ckl_global_peak_prices', $peak_prices);

    wp_send_json_success(array(
        'message' => __('Peak price saved successfully', 'ckl-car-rental'),
        'peak_prices' => $peak_prices
    ));
}
add_action('wp_ajax_ckl_save_peak_price', 'ckl_ajax_save_peak_price');
