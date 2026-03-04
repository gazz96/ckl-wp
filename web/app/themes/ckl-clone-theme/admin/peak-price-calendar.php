<?php
/**
 * Peak Periods Calendar Admin Page
 *
 * Visual calendar interface for managing GLOBAL peak periods that apply to ALL vehicles
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
            <?php _e('Peak Periods Calendar', 'ckl-car-rental'); ?>
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
                <?php _e('Add Peak Period', 'ckl-car-rental'); ?>
            </button>
            <button type="button" class="button" id="ckl-refresh-calendar">
                <span class="dashicons dashicons-update" style="margin-top: 3px;"></span>
                <?php _e('Refresh', 'ckl-car-rental'); ?>
            </button>
        </div>

        <table class="wp-list-table widefat fixed striped" id="ckl-peak-price-table">
            <thead>
                <tr>
                    <th scope="col" style="width: 25%;"><?php _e('Name', 'ckl-car-rental'); ?></th>
                    <th scope="col" style="width: 25%;"><?php _e('Date Range', 'ckl-car-rental'); ?></th>
                    <th scope="col" style="width: 15%;"><?php _e('Adjustment', 'ckl-car-rental'); ?></th>
                    <th scope="col" style="width: 10%;"><?php _e('Recurring', 'ckl-car-rental'); ?></th>
                    <th scope="col" style="width: 10%;"><?php _e('Status', 'ckl-car-rental'); ?></th>
                    <th scope="col" style="width: 15%;"><?php _e('Actions', 'ckl-car-rental'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($peak_prices)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px;">
                            <?php _e('No peak periods found. Click "Add Peak Period" to create one.', 'ckl-car-rental'); ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($peak_prices as $peak): ?>
                        <tr data-peak-id="<?php echo esc_attr($peak['id']); ?>" data-peak-json="<?php echo esc_attr(json_encode($peak)); ?>">
                            <td>
                                <strong><?php echo esc_html($peak['name']); ?></strong>
                            </td>
                            <td>
                                <code><?php echo esc_html($peak['start_date']); ?></code> →
                                <code><?php echo esc_html($peak['end_date']); ?></code>
                                <?php if ($peak['recurring'] !== 'none'): ?>
                                    <br><small class="description"><?php echo esc_html(ucfirst($peak['recurring'])); ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                $adjustment_type = isset($peak['adjustment_type']) ? $peak['adjustment_type'] : 'percentage';
                                $amount = isset($peak['amount']) ? $peak['amount'] : 0;
                                if ($adjustment_type === 'percentage' && $amount > 0):
                                ?>
                                    <span style="color: #d63638; font-weight: 600;">+<?php echo esc_html($amount); ?>%</span>
                                <?php elseif ($adjustment_type === 'fixed' && $amount > 0): ?>
                                    <span style="color: #d63638; font-weight: 600;">+RM<?php echo esc_html(number_format($amount, 2)); ?></span>
                                <?php else: ?>
                                    <span style="color: #999;">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($peak['recurring'] === 'yearly'): ?>
                                    <span class="dashicons dashicons-calendar-alt" style="color: #0073aa;" title="<?php _e('Yearly', 'ckl-car-rental'); ?>"></span>
                                <?php elseif ($peak['recurring'] === 'monthly'): ?>
                                    <span class="dashicons dashicons-clock" style="color: #0073aa;" title="<?php _e('Monthly', 'ckl-car-rental'); ?>"></span>
                                <?php else: ?>
                                    <span class="dashicons dashicons-calendar" style="color: #999;" title="<?php _e('One-time', 'ckl-car-rental'); ?>"></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($peak['active']): ?>
                                    <span class="dashicons dashicons-yes-alt" style="color: #00a32a;"></span>
                                    <?php _e('Active', 'ckl-car-rental'); ?>
                                <?php else: ?>
                                    <span class="dashicons dashicons-dismiss" style="color: #d63638;"></span>
                                    <?php _e('Inactive', 'ckl-car-rental'); ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button type="button" class="button button-small edit-peak-price" data-id="<?php echo esc_attr($peak['id']); ?>">
                                    <?php _e('Edit', 'ckl-car-rental'); ?>
                                </button>
                                <button type="button" class="button button-small delete-peak-price" data-id="<?php echo esc_attr($peak['id']); ?>" style="color: #d63638;">
                                    <?php _e('Delete', 'ckl-car-rental'); ?>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Add/Edit Peak Period Modal -->
    <div id="ckl-peak-price-modal" style="display: none;">
        <div class="ckl-modal-overlay" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 100000;"></div>
        <div class="ckl-modal-content" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: #fff; padding: 25px; width: 500px; max-width: 90%; max-height: 90vh; overflow-y: auto; z-index: 100001; box-shadow: 0 5px 15px rgba(0,0,0,0.3); border-radius: 4px;">
            <h2 id="ckl-modal-title" style="margin-top: 0;">
                <?php _e('Add Peak Period', 'ckl-car-rental'); ?>
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
                            <label for="ckl-peak-recurring"><?php _e('Recurring', 'ckl-car-rental'); ?></label>
                        </th>
                        <td>
                            <select id="ckl-peak-recurring">
                                <option value="none"><?php _e('None (One-time)', 'ckl-car-rental'); ?></option>
                                <option value="yearly"><?php _e('Yearly', 'ckl-car-rental'); ?></option>
                                <option value="monthly"><?php _e('Monthly', 'ckl-car-rental'); ?></option>
                            </select>
                            <p class="description">
                                <?php _e('Repeat this peak period automatically', 'ckl-car-rental'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="ckl-peak-adjustment-type"><?php _e('Adjustment Type', 'ckl-car-rental'); ?></label>
                        </th>
                        <td>
                            <select id="ckl-peak-adjustment-type">
                                <option value="percentage"><?php _e('Percentage', 'ckl-car-rental'); ?></option>
                                <option value="fixed"><?php _e('Fixed Amount (RM)', 'ckl-car-rental'); ?></option>
                            </select>
                            <p class="description">
                                <?php _e('How the pricing adjustment is calculated', 'ckl-car-rental'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="ckl-peak-amount"><?php _e('Adjustment Amount', 'ckl-car-rental'); ?></label>
                        </th>
                        <td>
                            <input type="number" id="ckl-peak-amount" step="0.01" min="0" value="0">
                            <p class="description" id="ckl-peak-amount-desc">
                                <?php _e('Percentage increase (e.g., 25 = 25% more)', 'ckl-car-rental'); ?>
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
                                <?php _e('Enable this peak period', 'ckl-car-rental'); ?>
                            </label>
                        </td>
                    </tr>
                </table>

                <p class="ckl-modal-actions" style="text-align: right; margin-top: 20px;">
                    <button type="button" class="button" id="ckl-cancel-modal">
                        <?php _e('Cancel', 'ckl-car-rental'); ?>
                    </button>
                    <button type="submit" class="button button-primary">
                        <?php _e('Save Peak Period', 'ckl-car-rental'); ?>
                    </button>
                </p>
            </form>
        </div>
    </div>

    <script>
    jQuery(document).ready(function($) {
        var peakPrices = <?php echo json_encode($peak_prices); ?>;
        var nextId = <?php echo !empty($peak_prices) ? max(array_column($peak_prices, 'id')) + 1 : 1; ?>;

        // Modal functions
        function openModal() {
            $('#ckl-peak-price-modal').show();
            $('#ckl-modal-title').text('<?php _e('Add Peak Period', 'ckl-car-rental'); ?>');
            $('#ckl-editing').val('0');
            $('#ckl-peak-price-id').val('');
        }

        function closeModal() {
            $('#ckl-peak-price-modal').hide();
            $('#ckl-peak-price-form')[0].reset();
        }

        function editPeakPrice(peak) {
            $('#ckl-modal-title').text('<?php _e('Edit Peak Period', 'ckl-car-rental'); ?>');
            $('#ckl-editing').val('1');
            $('#ckl-peak-price-id').val(peak.id);
            $('#ckl-peak-name').val(peak.name);
            $('#ckl-peak-start').val(peak.start_date);
            $('#ckl-peak-end').val(peak.end_date);
            $('#ckl-peak-recurring').val(peak.recurring);
            $('#ckl-peak-adjustment-type').val(peak.adjustment_type || 'percentage');
            $('#ckl-peak-amount').val(peak.amount || 0);
            $('#ckl-peak-active').prop('checked', peak.active == 1);
            $('#ckl-peak-price-modal').show();
        }

        function deletePeakPrice(id) {
            if (!confirm('<?php _e('Are you sure you want to delete this peak period?', 'ckl-car-rental'); ?>')) {
                return;
            }

            var data = {
                action: 'ckl_delete_peak_price',
                nonce: '<?php echo wp_create_nonce('ckl-peak-price-nonce'); ?>',
                id: id
            };

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: data,
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.data.message || '<?php _e('Error deleting peak period', 'ckl-car-rental'); ?>');
                    }
                },
                error: function() {
                    alert('<?php _e('Server error', 'ckl-car-rental'); ?>');
                }
            });
        }

        // Event handlers
        $('#ckl-add-peak-price').on('click', openModal);
        $('#ckl-cancel-modal, .ckl-modal-overlay').on('click', closeModal);

        // Edit button - load data from data attribute
        $(document).on('click', '.edit-peak-price', function() {
            var row = $(this).closest('tr');
            var peakData = row.data('peak-json');
            if (peakData) {
                editPeakPrice(peakData);
            }
        });

        // Delete button
        $(document).on('click', '.delete-peak-price', function() {
            var id = $(this).data('id');
            deletePeakPrice(id);
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
                    recurring: $('#ckl-peak-recurring').val(),
                    adjustment_type: $('#ckl-peak-adjustment-type').val(),
                    amount: parseFloat($('#ckl-peak-amount').val()) || 0,
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
                        alert(response.data.message || '<?php _e('Error saving peak period', 'ckl-car-rental'); ?>');
                        $('#ckl-peak-price-form').find('button[type="submit"]').prop('disabled', false).text('<?php _e('Save Peak Period', 'ckl-car-rental'); ?>');
                    }
                },
                error: function() {
                    alert('<?php _e('Server error', 'ckl-car-rental'); ?>');
                    $('#ckl-peak-price-form').find('button[type="submit"]').prop('disabled', false).text('<?php _e('Save Peak Period', 'ckl-car-rental'); ?>');
                }
            });
        });

        // Refresh table
        $('#ckl-refresh-calendar').on('click', function() {
            location.reload();
        });

        // Update amount description based on adjustment type
        $('#ckl-peak-adjustment-type').on('change', function() {
            var type = $(this).val();
            if (type === 'percentage') {
                $('#ckl-peak-amount-desc').text('<?php _e('Percentage increase (e.g., 25 = 25% more)', 'ckl-car-rental'); ?>');
            } else {
                $('#ckl-peak-amount-desc').text('<?php _e('Fixed RM amount per day (e.g., 50 = RM50 more per day)', 'ckl-car-rental'); ?>');
            }
        });
    });
    </script>
    <?php
}
