<?php
/**
 * CKL Dynamic Pricing
 *
 * Handles dynamic pricing based on date ranges, seasons, and holidays
 */

if (!defined('ABSPATH')) {
    exit;
}

class CKL_Dynamic_Pricing {

    /**
     * Initialize the class
     */
    public static function init() {
        add_action('add_meta_boxes', array(__CLASS__, 'add_pricing_rules_meta_box'));
        add_action('save_post_vehicle', array(__CLASS__, 'save_pricing_rules'), 20, 2);
        add_filter('woocommerce_booking_get_price', array(__CLASS__, 'apply_dynamic_pricing'), 10, 2);
        add_filter('woocommerce_bookings_get_price', array(__CLASS__, 'apply_dynamic_pricing_to_booking_cost'), 10, 3);
        add_action('wp_ajax_ckl_get_dynamic_price', array(__CLASS__, 'ajax_get_dynamic_price'));

        // Include admin classes
        if (is_admin()) {
            require_once CKL_CAR_RENTAL_PLUGIN_DIR . 'includes/admin/class-peak-price-manager.php';
            require_once CKL_CAR_RENTAL_PLUGIN_DIR . 'includes/admin/class-pricing-rule-templates.php';
            require_once CKL_CAR_RENTAL_PLUGIN_DIR . 'includes/admin/class-admin-ajax.php';
            CKL_Admin_AJAX::init();
        }
    }

    /**
     * Add pricing rules meta box to vehicle
     */
    public static function add_pricing_rules_meta_box() {
        add_meta_box(
            'vehicle_dynamic_pricing',
            __('Dynamic Pricing Rules', 'ckl-car-rental'),
            array(__CLASS__, 'pricing_rules_meta_box_html'),
            'vehicle',
            'normal',
            'default'
        );
    }

    /**
     * Render pricing rules meta box
     */
    public static function pricing_rules_meta_box_html($post) {
        wp_nonce_field('ckl_save_pricing_rules', 'ckl_pricing_rules_nonce');

        $rules = get_post_meta($post->ID, '_vehicle_pricing_rules', true);
        if (!is_array($rules)) {
            $rules = array();
        }

        ?>
        <div class="ckl-dynamic-pricing-rules">
            <div id="pricing-rules-container">
                <?php foreach ($rules as $index => $rule): ?>
                    <?php self::render_rule_row($index, $rule); ?>
                <?php endforeach; ?>
            </div>

            <button type="button" class="button" id="add-pricing-rule">
                <?php _e('+ Add Pricing Rule', 'ckl-car-rental'); ?>
            </button>

            <p class="description">
                <?php _e('Rules are applied by priority (highest first). Specific date ranges override general rules.', 'ckl-car-rental'); ?>
            </p>
        </div>

        <script type="text/html" id="tmpl-ckl-pricing-rule">
            <?php self::render_rule_row('INDEX', array(), true); ?>
        </script>

        <style>
            .ckl-pricing-rule {
                background: #f9f9f9;
                border: 1px solid #ddd;
                padding: 15px;
                margin-bottom: 10px;
                position: relative;
            }
            .ckl-pricing-rule h4 {
                margin-top: 0;
            }
            .ckl-pricing-rule .rule-fields {
                display: grid;
                grid-template-columns: 1fr 1fr 1fr;
                gap: 10px;
                margin-bottom: 10px;
            }
            .ckl-pricing-rule .rule-fields .field {
                display: flex;
                flex-direction: column;
            }
            .ckl-pricing-rule .rule-fields label {
                font-size: 12px;
                font-weight: bold;
                margin-bottom: 5px;
            }
            .ckl-pricing-rule .remove-rule {
                position: absolute;
                top: 10px;
                right: 10px;
                color: #a00;
                cursor: pointer;
            }
        </style>

        <script>
        jQuery(document).ready(function($) {
            var ruleIndex = <?php echo count($rules); ?>;

            $('#add-pricing-rule').on('click', function() {
                var template = wp.template('ckl-pricing-rule');
                var html = template({index: ruleIndex});
                $('#pricing-rules-container').append(html);
                ruleIndex++;
            });

            $(document).on('click', '.remove-rule', function() {
                $(this).closest('.ckl-pricing-rule').remove();
            });
        });
        </script>
        <?php
    }

    /**
     * Render single rule row
     */
    private static function render_rule_row($index, $rule = array(), $is_template = false) {
        $defaults = array(
            'name' => '',
            'start_date' => '',
            'end_date' => '',
            'type' => 'percentage',
            'amount' => '',
            'recurring' => 'none',
            'priority' => 10,
            'active' => 'yes',
        );

        $rule = wp_parse_args($rule, $defaults);
        $name = $is_template ? '' : 'pricing_rules[' . $index . ']';

        ?>
        <div class="ckl-pricing-rule" data-index="<?php echo esc_attr($index); ?>">
            <span class="remove-rule">✕</span>
            <h4><?php echo esc_html($rule['name'] ?: __('New Rule', 'ckl-car-rental')); ?></h4>

            <div class="rule-fields">
                <div class="field">
                    <label><?php _e('Rule Name', 'ckl-car-rental'); ?></label>
                    <input type="text"
                           name="<?php echo esc_attr($name); ?>[name]"
                           value="<?php echo esc_attr($rule['name']); ?>"
                           placeholder="<?php _e('e.g., Hari Raya 2026', 'ckl-car-rental'); ?>">
                </div>

                <div class="field">
                    <label><?php _e('Start Date', 'ckl-car-rental'); ?></label>
                    <input type="date"
                           name="<?php echo esc_attr($name); ?>[start_date]"
                           value="<?php echo esc_attr($rule['start_date']); ?>">
                </div>

                <div class="field">
                    <label><?php _e('End Date', 'ckl-car-rental'); ?></label>
                    <input type="date"
                           name="<?php echo esc_attr($name); ?>[end_date]"
                           value="<?php echo esc_attr($rule['end_date']); ?>">
                </div>

                <div class="field">
                    <label><?php _e('Adjustment Type', 'ckl-car-rental'); ?></label>
                    <select name="<?php echo esc_attr($name); ?>[type]">
                        <option value="percentage" <?php selected($rule['type'], 'percentage'); ?>>
                            <?php _e('Percentage', 'ckl-car-rental'); ?>
                        </option>
                        <option value="fixed" <?php selected($rule['type'], 'fixed'); ?>>
                            <?php _e('Fixed Amount', 'ckl-car-rental'); ?>
                        </option>
                    </select>
                </div>

                <div class="field">
                    <label><?php _e('Amount', 'ckl-car-rental'); ?></label>
                    <input type="number"
                           name="<?php echo esc_attr($name); ?>[amount]"
                           value="<?php echo esc_attr($rule['amount']); ?>"
                           step="0.01"
                           placeholder="<?php _e('e.g., 50 for 50% or RM50', 'ckl-car-rental'); ?>">
                </div>

                <div class="field">
                    <label><?php _e('Recurring', 'ckl-car-rental'); ?></label>
                    <select name="<?php echo esc_attr($name); ?>[recurring]">
                        <option value="none" <?php selected($rule['recurring'], 'none'); ?>>
                            <?php _e('None (One-time)', 'ckl-car-rental'); ?>
                        </option>
                        <option value="yearly" <?php selected($rule['recurring'], 'yearly'); ?>>
                            <?php _e('Yearly', 'ckl-car-rental'); ?>
                        </option>
                        <option value="monthly" <?php selected($rule['recurring'], 'monthly'); ?>>
                            <?php _e('Monthly', 'ckl-car-rental'); ?>
                        </option>
                        <option value="weekly" <?php selected($rule['recurring'], 'weekly'); ?>>
                            <?php _e('Weekly', 'ckl-car-rental'); ?>
                        </option>
                    </select>
                </div>

                <div class="field">
                    <label><?php _e('Priority', 'ckl-car-rental'); ?></label>
                    <input type="number"
                           name="<?php echo esc_attr($name); ?>[priority]"
                           value="<?php echo esc_attr($rule['priority']); ?>"
                           min="1"
                           max="100"
                           placeholder="<?php _e('Higher = more important', 'ckl-car-rental'); ?>">
                </div>

                <div class="field">
                    <label><?php _e('Active', 'ckl-car-rental'); ?></label>
                    <select name="<?php echo esc_attr($name); ?>[active]">
                        <option value="yes" <?php selected($rule['active'], 'yes'); ?>>
                            <?php _e('Yes', 'ckl-car-rental'); ?>
                        </option>
                        <option value="no" <?php selected($rule['active'], 'no'); ?>>
                            <?php _e('No', 'ckl-car-rental'); ?>
                        </option>
                    </select>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Save pricing rules
     */
    public static function save_pricing_rules($post_id, $post) {
        // Verify nonce
        if (!isset($_POST['ckl_pricing_rules_nonce']) || !wp_verify_nonce($_POST['ckl_pricing_rules_nonce'], 'ckl_save_pricing_rules')) {
            return;
        }

        // Check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save rules
        if (isset($_POST['pricing_rules']) && is_array($_POST['pricing_rules'])) {
            $rules = array();

            foreach ($_POST['pricing_rules'] as $rule_data) {
                // Sanitize and validate
                $rule = array(
                    'name' => sanitize_text_field($rule_data['name']),
                    'start_date' => sanitize_text_field($rule_data['start_date']),
                    'end_date' => sanitize_text_field($rule_data['end_date']),
                    'type' => sanitize_text_field($rule_data['type']),
                    'amount' => floatval($rule_data['amount']),
                    'recurring' => sanitize_text_field($rule_data['recurring']),
                    'priority' => intval($rule_data['priority']),
                    'active' => sanitize_text_field($rule_data['active']),
                );

                // Only add valid rules
                if (!empty($rule['name']) && !empty($rule['start_date']) && !empty($rule['end_date']) && $rule['amount'] > 0) {
                    $rules[] = $rule;
                }
            }

            // Sort by priority
            usort($rules, function($a, $b) {
                return $b['priority'] - $a['priority'];
            });

            update_post_meta($post_id, '_vehicle_pricing_rules', $rules);
        } else {
            delete_post_meta($post_id, '_vehicle_pricing_rules');
        }
    }

    /**
     * Apply dynamic pricing to base price
     */
    public static function apply_dynamic_pricing($price, $booking_product) {
        $vehicle_id = get_post_meta($booking_product->get_id(), '_vehicle_id', true);

        if (!$vehicle_id) {
            return $price;
        }

        // Get current date or booking date
        $check_date = isset($_POST['start_date']) ? $_POST['start_date'] : current_time('Y-m-d');

        // Use priority-based pricing system
        return self::apply_all_pricing_rules($price, $vehicle_id, $check_date, $check_date);
    }

    /**
     * Apply dynamic pricing to booking cost
     */
    public static function apply_dynamic_pricing_to_booking_cost($cost, $booking_product, $data) {
        $vehicle_id = get_post_meta($booking_product->get_id(), '_vehicle_id', true);

        if (!$vehicle_id || !isset($data['start_date'])) {
            return $cost;
        }

        $start_date = $data['start_date'];
        $end_date = isset($data['end_date']) ? $data['end_date'] : $start_date;

        // Use priority-based pricing system
        $adjusted_cost = self::apply_all_pricing_rules($cost, $vehicle_id, $start_date, $end_date);

        // For fixed amounts, calculate per day if duration is available
        $duration = isset($data['duration']) ? $data['duration'] : 1;
        if ($adjusted_cost > $cost && $duration > 1) {
            // Check if a fixed amount was applied
            $peak_price = self::get_global_peak_price_for_date($start_date);
            $global_rule = self::get_global_pricing_rule_for_date($start_date);

            if (($peak_price && $peak_price['adjustment_type'] === 'fixed') ||
                ($global_rule && $global_rule['adjustment_type'] === 'fixed')) {
                // The adjustment is a fixed amount per day, multiply by duration
                $fixed_amount = $peak_price ? $peak_price['amount'] : $global_rule['amount'];
                $adjusted_cost = $cost + ($fixed_amount * $duration);
            }
        }

        return $adjusted_cost;
    }

    /**
     * Check if date is in rule range
     */
    private static function is_date_in_rule($check_date, $rule) {
        $check_timestamp = strtotime($check_date);
        $start_timestamp = strtotime($rule['start_date']);
        $end_timestamp = strtotime($rule['end_date'] . ' 23:59:59');

        // Check if in date range
        if ($check_timestamp < $start_timestamp || $check_timestamp > $end_timestamp) {
            return false;
        }

        // Handle recurring rules
        if ($rule['recurring'] !== 'none') {
            $check_year = date('Y', $check_timestamp);
            $rule_year = date('Y', $start_timestamp);

            // For yearly recurring, check month and day
            if ($rule['recurring'] === 'yearly') {
                $check_month_day = date('md', $check_timestamp);
                $start_month_day = date('md', $start_timestamp);
                $end_month_day = date('md', $end_timestamp);

                // Handle year boundary (e.g., Dec 25 - Jan 5)
                if ($start_month_day > $end_month_day) {
                    return ($check_month_day >= $start_month_day || $check_month_day <= $end_month_day);
                } else {
                    return ($check_month_day >= $start_month_day && $check_month_day <= $end_month_day);
                }
            }
        }

        return true;
    }

    /**
     * Get active pricing rule for date
     */
    public static function get_active_rule_for_date($vehicle_id, $date) {
        $rules = get_post_meta($vehicle_id, '_vehicle_pricing_rules', true);
        if (!is_array($rules)) {
            return null;
        }

        foreach ($rules as $rule) {
            if ($rule['active'] === 'yes' && self::is_date_in_rule($date, $rule)) {
                return $rule;
            }
        }

        return null;
    }

    /**
     * Display dynamic pricing notice on frontend
     */
    public static function display_pricing_notice($vehicle_id, $start_date, $end_date) {
        $base_price = get_post_meta($vehicle_id, '_vehicle_price_per_day', true);
        $product_id = get_post_meta($vehicle_id, '_vehicle_woocommerce_product_id', true);

        if (!$product_id) {
            return;
        }

        $product = wc_get_product($product_id);
        if (!$product) {
            return;
        }

        $final_price = self::apply_dynamic_pricing($base_price, $product);

        if ($final_price > $base_price) {
            $increase = $final_price - $base_price;

            // Get the applied rule (from priority system)
            $applied_rule = null;
            $rule_type = '';

            // Check in priority order
            $peak_price = self::get_global_peak_price_for_date($start_date);
            if ($peak_price) {
                $applied_rule = $peak_price;
                $rule_type = 'global_peak';
            } else {
                $global_rule = self::get_global_pricing_rule_for_date($start_date);
                if ($global_rule) {
                    $applied_rule = $global_rule;
                    $rule_type = 'global_rule';
                } else {
                    $vehicle_rule = self::get_active_rule_for_date($vehicle_id, $start_date);
                    if ($vehicle_rule) {
                        $applied_rule = $vehicle_rule;
                        $rule_type = 'vehicle_rule';
                    }
                }
            }

            ?>
            <div class="ckl-dynamic-pricing-notice" style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 12px; margin: 15px 0;">
                <p style="margin: 0; font-weight: bold;">
                    <?php
                    if ($rule_type === 'global_peak') {
                        _e('Global Peak Price Applied', 'ckl-car-rental');
                    } elseif ($rule_type === 'global_rule') {
                        _e('Global Pricing Rule Applied', 'ckl-car-rental');
                    } else {
                        _e('Peak Season Pricing Applied', 'ckl-car-rental');
                    }
                    ?>
                </p>
                <?php if ($applied_rule): ?>
                    <p style="margin: 5px 0 0 0;">
                        <?php
                        if ($applied_rule['adjustment_type'] === 'percentage') {
                            $percentage = round(($increase / $base_price) * 100);
                            printf(
                                __('Price includes %d%% surcharge for %s', 'ckl-car-rental'),
                                $percentage,
                                esc_html($applied_rule['name'])
                            );
                        } else {
                            printf(
                                __('Price includes RM%s surcharge for %s', 'ckl-car-rental'),
                                number_format($applied_rule['amount'], 2),
                                esc_html($applied_rule['name'])
                            );
                        }
                        ?>
                    </p>
                <?php endif; ?>
            </div>
            <?php
        }
    }

    /**
     * AJAX handler for dynamic price calculation
     */
    public static function ajax_get_dynamic_price() {
        check_ajax_referer('ckl-dynamic-pricing', 'nonce');

        $vehicle_id = intval($_POST['vehicle_id']);
        $start_date = sanitize_text_field($_POST['start_date']);
        $end_date = sanitize_text_field($_POST['end_date']);

        $base_price = get_post_meta($vehicle_id, '_vehicle_price_per_day', true);
        $product_id = get_post_meta($vehicle_id, '_vehicle_woocommerce_product_id', true);

        if (!$product_id) {
            wp_send_json_error(array('message' => __('Vehicle not found', 'ckl-car-rental')));
        }

        $product = wc_get_product($product_id);

        // Calculate price with dynamic rules
        $data = array(
            'start_date' => $start_date,
            'end_date' => $end_date,
            'duration' => self::calculate_duration($start_date, $end_date),
        );

        $final_price = self::apply_dynamic_pricing_to_booking_cost($base_price, $product, $data);
        $rule = self::get_active_rule_for_date($vehicle_id, $start_date);

        wp_send_json_success(array(
            'base_price' => $base_price,
            'final_price' => $final_price,
            'rule' => $rule,
            'savings' => $final_price - $base_price,
        ));
    }

    /**
     * Calculate duration in days
     */
    private static function calculate_duration($start_date, $end_date) {
        $start = new DateTime($start_date);
        $end = new DateTime($end_date);
        return $start->diff($end)->days;
    }

    // ====================
    // GLOBAL PEAK PRICES
    // ====================

    /**
     * Get global peak price for specific date
     *
     * @param string $date Date in Y-m-d format
     * @return array|null The matching peak price or null
     */
    public static function get_global_peak_price_for_date($date) {
        $peak_prices = get_option('ckl_global_peak_prices', array());

        foreach ($peak_prices as $peak) {
            if (!$peak['active']) {
                continue;
            }

            if (self::is_date_in_global_rule($date, $peak)) {
                return $peak;
            }
        }

        return null;
    }

    // ====================
    // GLOBAL PRICING RULES
    // ====================

    /**
     * Get global pricing rule for specific date
     *
     * @param string $date Date in Y-m-d format
     * @return array|null The matching global rule or null
     */
    public static function get_global_pricing_rule_for_date($date) {
        $global_rules = get_option('ckl_global_pricing_rules', array());

        foreach ($global_rules as $rule) {
            if (!$rule['active']) {
                continue;
            }

            if (self::is_date_in_global_rule($date, $rule)) {
                return $rule;
            }
        }

        return null;
    }

    /**
     * Check if date is in global rule range (for peak prices and global rules)
     *
     * @param string $check_date Date in Y-m-d format
     * @param array $rule Rule array with start_date, end_date, recurring
     * @return bool
     */
    private static function is_date_in_global_rule($check_date, $rule) {
        $check_timestamp = strtotime($check_date);
        $start_timestamp = strtotime($rule['start_date']);
        $end_timestamp = strtotime($rule['end_date'] . ' 23:59:59');

        // Check if in date range
        if ($check_timestamp < $start_timestamp || $check_timestamp > $end_timestamp) {
            return false;
        }

        // Handle recurring rules
        if (isset($rule['recurring']) && $rule['recurring'] !== 'none') {
            $check_year = date('Y', $check_timestamp);
            $rule_year = date('Y', $start_timestamp);

            // For yearly recurring, check month and day
            if ($rule['recurring'] === 'yearly') {
                $check_month_day = date('md', $check_timestamp);
                $start_month_day = date('md', $start_timestamp);
                $end_month_day = date('md', $end_timestamp);

                // Handle year boundary (e.g., Dec 25 - Jan 5)
                if ($start_month_day > $end_month_day) {
                    return ($check_month_day >= $start_month_day || $check_month_day <= $end_month_day);
                } else {
                    return ($check_month_day >= $start_month_day && $check_month_day <= $end_month_day);
                }
            }

            // For monthly recurring, check day of month
            if ($rule['recurring'] === 'monthly') {
                $check_day = date('j', $check_timestamp);
                $start_day = date('j', $start_timestamp);
                $end_day = date('j', $end_timestamp);

                // Handle month boundary
                if ($start_day > $end_day) {
                    return ($check_day >= $start_day || $check_day <= $end_day);
                } else {
                    return ($check_day >= $start_day && $check_day <= $end_day);
                }
            }

            // For weekly recurring, check day of week
            if ($rule['recurring'] === 'weekly') {
                $check_day_of_week = date('N', $check_timestamp);
                $start_day_of_week = date('N', $start_timestamp);
                $end_day_of_week = date('N', $end_timestamp);

                if ($start_day_of_week > $end_day_of_week) {
                    return ($check_day_of_week >= $start_day_of_week || $check_day_of_week <= $end_day_of_week);
                } else {
                    return ($check_day_of_week >= $start_day_of_week && $check_day_of_week <= $end_day_of_week);
                }
            }
        }

        return true;
    }

    /**
     * Apply all pricing rules in priority order
     *
     * Priority Order (Highest to Lowest):
     * 1. Global Peak Prices (Priority 100) - Apply to ALL vehicles
     * 2. Global Pricing Rules (Priority 90) - Apply to ALL vehicles
     * 3. Vehicle-Specific Rules (Priority 50) - Per-vehicle rules
     * 4. Theme Seasonal Pricing (Priority 10) - From theme settings (legacy)
     *
     * @param float $base_price The base price
     * @param int $vehicle_id The vehicle ID
     * @param string $start_date Start date in Y-m-d format
     * @param string $end_date End date in Y-m-d format
     * @return float The final price after applying rules
     */
    public static function apply_all_pricing_rules($base_price, $vehicle_id, $start_date, $end_date) {
        $final_price = $base_price;
        $applied_rule = null;

        // 1. Apply global peak prices (Priority 100)
        $peak_price = self::get_global_peak_price_for_date($start_date);
        if ($peak_price) {
            if ($peak_price['adjustment_type'] === 'percentage') {
                $final_price = $final_price * (1 + ($peak_price['amount'] / 100));
            } else {
                $final_price = $final_price + $peak_price['amount'];
            }
            $applied_rule = $peak_price;
            return $final_price; // Stop after highest priority rule
        }

        // 2. Apply global pricing rules (Priority 90)
        $global_rule = self::get_global_pricing_rule_for_date($start_date);
        if ($global_rule) {
            if ($global_rule['adjustment_type'] === 'percentage') {
                $final_price = $final_price * (1 + ($global_rule['amount'] / 100));
            } else {
                $final_price = $final_price + $global_rule['amount'];
            }
            $applied_rule = $global_rule;
            return $final_price; // Stop after highest priority rule
        }

        // 3. Apply vehicle-specific rules (Priority 50)
        $vehicle_rule = self::get_active_rule_for_date($vehicle_id, $start_date);
        if ($vehicle_rule) {
            if ($vehicle_rule['type'] === 'percentage') {
                $final_price = $final_price * (1 + ($vehicle_rule['amount'] / 100));
            } else {
                $final_price = $final_price + $vehicle_rule['amount'];
            }
            $applied_rule = $vehicle_rule;
            return $final_price; // Stop after highest priority rule
        }

        // 4. Apply theme seasonal pricing (Priority 10) - Legacy support
        $global_pricing = get_option('ckl_global_pricing', array());
        if (isset($global_pricing['seasonal_pricing']) && is_array($global_pricing['seasonal_pricing'])) {
            foreach ($global_pricing['seasonal_pricing'] as $season) {
                if (self::is_date_in_seasonal_pricing($start_date, $season)) {
                    $final_price = $final_price * ($season['multiplier'] ?? 1);
                    break;
                }
            }
        }

        return $final_price;
    }

    /**
     * Check if date is in seasonal pricing range
     *
     * @param string $check_date Date in Y-m-d format
     * @param array $season Season array with start_date, end_date
     * @return bool
     */
    private static function is_date_in_seasonal_pricing($check_date, $season) {
        $check_timestamp = strtotime($check_date);
        $start_timestamp = strtotime($season['start_date']);
        $end_timestamp = strtotime($season['end_date'] . ' 23:59:59');

        return ($check_timestamp >= $start_timestamp && $check_timestamp <= $end_timestamp);
    }
}
