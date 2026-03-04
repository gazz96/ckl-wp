<?php
/**
 * CKL Peak Price Manager
 *
 * Handles CRUD operations for global peak prices
 * that apply to ALL vehicles
 */

if (!defined('ABSPATH')) {
    exit;
}

class CKL_Peak_Price_Manager {

    /**
     * Option name for storing peak prices
     */
    const OPTION_NAME = 'ckl_global_peak_prices';

    /**
     * Initialize the class
     */
    public static function init() {
        // AJAX handlers are registered in the theme admin file
        // This class provides utility methods for working with peak prices
    }

    /**
     * Get all peak prices
     *
     * @return array
     */
    public static function get_all_peak_prices() {
        return get_option(self::OPTION_NAME, array());
    }

    /**
     * Get peak price by ID
     *
     * @param int|string $id
     * @return array|null
     */
    public static function get_peak_price($id) {
        $peak_prices = self::get_all_peak_prices();

        foreach ($peak_prices as $peak) {
            if ($peak['id'] == $id) {
                return $peak;
            }
        }

        return null;
    }

    /**
     * Add new peak price
     *
     * @param array $data
     * @return int|WP_Error The ID of the created peak price or error
     */
    public static function add_peak_price($data) {
        $peak_prices = self::get_all_peak_prices();

        // Validate required fields
        if (empty($data['name']) || empty($data['start_date']) || empty($data['end_date'])) {
            return new WP_Error('missing_data', __('Missing required fields', 'ckl-car-rental'));
        }

        // Create new peak price
        $new_peak = array(
            'id' => isset($data['id']) ? intval($data['id']) : uniqid('pp_', true),
            'name' => sanitize_text_field($data['name']),
            'start_date' => sanitize_text_field($data['start_date']),
            'end_date' => sanitize_text_field($data['end_date']),
            'adjustment_type' => sanitize_text_field($data['adjustment_type'] ?? 'percentage'),
            'amount' => floatval($data['amount'] ?? 0),
            'recurring' => sanitize_text_field($data['recurring'] ?? 'none'),
            'active' => isset($data['active']) ? (bool) $data['active'] : true,
            'priority' => isset($data['priority']) ? intval($data['priority']) : 100,
            'created_at' => current_time('mysql'),
            'updated_at' => current_time('mysql'),
        );

        $peak_prices[] = $new_peak;

        // Sort by start date
        usort($peak_prices, function($a, $b) {
            return strtotime($a['start_date']) - strtotime($b['start_date']);
        });

        update_option(self::OPTION_NAME, $peak_prices);

        return $new_peak['id'];
    }

    /**
     * Update existing peak price
     *
     * @param int|string $id
     * @param array $data
     * @return bool|WP_Error
     */
    public static function update_peak_price($id, $data) {
        $peak_prices = self::get_all_peak_prices();

        $index = -1;
        foreach ($peak_prices as $i => $peak) {
            if ($peak['id'] == $id) {
                $index = $i;
                break;
            }
        }

        if ($index < 0) {
            return new WP_Error('not_found', __('Peak price not found', 'ckl-car-rental'));
        }

        // Update fields
        if (isset($data['name'])) {
            $peak_prices[$index]['name'] = sanitize_text_field($data['name']);
        }
        if (isset($data['start_date'])) {
            $peak_prices[$index]['start_date'] = sanitize_text_field($data['start_date']);
        }
        if (isset($data['end_date'])) {
            $peak_prices[$index]['end_date'] = sanitize_text_field($data['end_date']);
        }
        if (isset($data['adjustment_type'])) {
            $peak_prices[$index]['adjustment_type'] = sanitize_text_field($data['adjustment_type']);
        }
        if (isset($data['amount'])) {
            $peak_prices[$index]['amount'] = floatval($data['amount']);
        }
        if (isset($data['recurring'])) {
            $peak_prices[$index]['recurring'] = sanitize_text_field($data['recurring']);
        }
        if (isset($data['active'])) {
            $peak_prices[$index]['active'] = (bool) $data['active'];
        }
        if (isset($data['priority'])) {
            $peak_prices[$index]['priority'] = intval($data['priority']);
        }

        $peak_prices[$index]['updated_at'] = current_time('mysql');

        // Sort by start date
        usort($peak_prices, function($a, $b) {
            return strtotime($a['start_date']) - strtotime($b['start_date']);
        });

        update_option(self::OPTION_NAME, $peak_prices);

        return true;
    }

    /**
     * Delete peak price
     *
     * @param int|string $id
     * @return bool
     */
    public static function delete_peak_price($id) {
        $peak_prices = self::get_all_peak_prices();

        $peak_prices = array_filter($peak_prices, function($peak) use ($id) {
            return $peak['id'] != $id;
        });

        update_option(self::OPTION_NAME, array_values($peak_prices));

        return true;
    }

    /**
     * Toggle peak price active status
     *
     * @param int|string $id
     * @param bool $active
     * @return bool
     */
    public static function toggle_peak_price($id, $active = null) {
        $peak_prices = self::get_all_peak_prices();

        foreach ($peak_prices as &$peak) {
            if ($peak['id'] == $id) {
                if ($active === null) {
                    // Toggle current status
                    $peak['active'] = !$peak['active'];
                } else {
                    $peak['active'] = (bool) $active;
                }
                $peak['updated_at'] = current_time('mysql');
                break;
            }
        }

        update_option(self::OPTION_NAME, $peak_prices);

        return true;
    }

    /**
     * Get peak price for specific date
     *
     * @param string $date Date in Y-m-d format
     * @return array|null The matching peak price or null
     */
    public static function get_peak_price_for_date($date) {
        $peak_prices = self::get_all_peak_prices();

        foreach ($peak_prices as $peak) {
            if (!$peak['active']) {
                continue;
            }

            if (self::is_date_in_peak_price($date, $peak)) {
                return $peak;
            }
        }

        return null;
    }

    /**
     * Check if date is in peak price range
     *
     * @param string $check_date Date in Y-m-d format
     * @param array $peak_price Peak price array
     * @return bool
     */
    public static function is_date_in_peak_price($check_date, $peak_price) {
        $check_timestamp = strtotime($check_date);
        $start_timestamp = strtotime($peak_price['start_date']);
        $end_timestamp = strtotime($peak_price['end_date'] . ' 23:59:59');

        // Check if in date range
        if ($check_timestamp < $start_timestamp || $check_timestamp > $end_timestamp) {
            return false;
        }

        // Handle recurring rules
        if ($peak_price['recurring'] !== 'none') {
            $check_year = date('Y', $check_timestamp);
            $rule_year = date('Y', $start_timestamp);

            // For yearly recurring, check month and day
            if ($peak_price['recurring'] === 'yearly') {
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
            if ($peak_price['recurring'] === 'monthly') {
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
        }

        return true;
    }

    /**
     * Get peak prices for a date range
     *
     * @param string $start_date Start date in Y-m-d format
     * @param string $end_date End date in Y-m-d format
     * @return array Array of peak prices that affect the date range
     */
    public static function get_peak_prices_for_range($start_date, $end_date) {
        $peak_prices = self::get_all_peak_prices();
        $matching = array();

        foreach ($peak_prices as $peak) {
            if (!$peak['active']) {
                continue;
            }

            // Check if any date in the range falls within this peak price
            $current = strtotime($start_date);
            $end = strtotime($end_date);

            while ($current <= $end) {
                if (self::is_date_in_peak_price(date('Y-m-d', $current), $peak)) {
                    $matching[] = $peak;
                    break;
                }
                $current = strtotime('+1 day', $current);
            }
        }

        return $matching;
    }

    /**
     * Get color for peak price based on type and amount
     *
     * @param array $peak_price
     * @return string Hex color code
     */
    public static function get_peak_price_color($peak_price) {
        if ($peak_price['adjustment_type'] === 'fixed') {
            return '#6f42c1'; // Purple for fixed amounts
        }

        // For percentage
        $amount = floatval($peak_price['amount']);
        if ($amount <= 20) {
            return '#ffc107'; // Yellow for 0-20%
        } elseif ($amount <= 50) {
            return '#fd7e14'; // Orange for 21-50%
        } else {
            return '#dc3545'; // Red for 51%+
        }
    }
}
