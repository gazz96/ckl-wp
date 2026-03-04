<?php
/**
 * Vehicle Pricing Helpers
 *
 * Helper functions for vehicle pricing calculations
 *
 * @package CKL_Car_Rental
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get vehicle peak pricing for a specific date range
 *
 * @param int $vehicle_id Vehicle post ID
 * @param string $start_date Start date (Y-m-d)
 * @param string $end_date End date (Y-m-d)
 * @return array Array of applicable peak pricing
 */
function ckl_get_vehicle_peak_pricing($vehicle_id, $start_date, $end_date) {
    $peak_pricing = get_post_meta($vehicle_id, '_peak_pricing', true);
    if (empty($peak_pricing)) {
        return array();
    }

    $applicable = array();
    foreach ($peak_pricing as $pricing) {
        // Check if date range overlaps
        if ($pricing['start_date'] <= $end_date && $pricing['end_date'] >= $start_date) {
            $applicable[] = $pricing;
        }
    }

    return $applicable;
}

/**
 * Calculate peak pricing surcharge for a vehicle
 *
 * @param int $vehicle_id Vehicle post ID
 * @param float $base_price Base daily price
 * @param string $start_date Start date (Y-m-d)
 * @param string $end_date End date (Y-m-d)
 * @return float Total surcharge amount
 */
function ckl_calculate_peak_pricing_surcharge($vehicle_id, $base_price, $start_date, $end_date) {
    $peak_pricing = ckl_get_vehicle_peak_pricing($vehicle_id, $start_date, $end_date);

    if (empty($peak_pricing)) {
        return 0;
    }

    // Calculate number of days
    $days = max(1, ceil((strtotime($end_date) - strtotime($start_date)) / DAY_IN_SECONDS));

    // Apply the highest peak pricing (could be changed to sum all)
    $total_surcharge = 0;
    foreach ($peak_pricing as $pricing) {
        if ($pricing['adjustment_type'] === 'percentage') {
            $total_surcharge += $base_price * ($pricing['amount'] / 100) * $days;
        } else {
            // Fixed amount per day
            $total_surcharge += $pricing['amount'] * $days;
        }
    }

    return $total_surcharge;
}

/**
 * Get all peak pricing periods for a vehicle
 *
 * @param int $vehicle_id Vehicle post ID
 * @return array Array of peak pricing periods
 */
function ckl_get_vehicle_all_peak_pricing($vehicle_id) {
    $peak_pricing = get_post_meta($vehicle_id, '_peak_pricing', true);
    if (empty($peak_pricing)) {
        return array();
    }

    // Sort by start date
    usort($peak_pricing, function($a, $b) {
        return strtotime($a['start_date']) - strtotime($b['start_date']);
    });

    return $peak_pricing;
}

/**
 * Get global peak periods
 *
 * @return array Array of global peak periods
 */
function ckl_get_global_peak_periods() {
    $peak_periods = get_option('ckl_global_peak_prices', array());

    // Sort by start date
    usort($peak_periods, function($a, $b) {
        return strtotime($a['start_date']) - strtotime($b['start_date']);
    });

    return $peak_periods;
}

/**
 * Check if a date falls within any peak period
 *
 * @param string $date Date to check (Y-m-d)
 * @return bool True if date is within a peak period
 */
function ckl_is_peak_date($date) {
    $peak_periods = ckl_get_global_peak_periods();

    foreach ($peak_periods as $period) {
        if (!$period['active']) {
            continue;
        }

        if ($date >= $period['start_date'] && $date <= $period['end_date']) {
            return true;
        }
    }

    return false;
}

/**
 * Get applicable peak pricing for a vehicle and date range
 *
 * This function merges global peak periods with vehicle-specific overrides.
 * Vehicle overrides take precedence for the same period.
 *
 * @param int $vehicle_id Vehicle post ID
 * @param string $start_date Start date (Y-m-d)
 * @param string $end_date End date (Y-m-d)
 * @return array Array of applicable peak pricing with global and override pricing
 */
function ckl_get_applicable_peak_pricing($vehicle_id, $start_date, $end_date) {
    // Get global peak periods
    $global_periods = get_option('ckl_global_peak_prices', array());

    // Get vehicle-specific peak pricing overrides
    $vehicle_overrides = get_post_meta($vehicle_id, '_peak_pricing', true);
    if (!is_array($vehicle_overrides)) {
        $vehicle_overrides = array();
    }

    $applicable = array();

    // First, add global periods that overlap with the date range
    foreach ($global_periods as $period) {
        if (!$period['active']) {
            continue;
        }

        // Check if date range overlaps
        if ($period['start_date'] <= $end_date && $period['end_date'] >= $start_date) {
            $applicable[$period['id']] = array(
                'period_id' => $period['id'],
                'name' => $period['name'],
                'start_date' => $period['start_date'],
                'end_date' => $period['end_date'],
                'adjustment_type' => $period['adjustment_type'] ?? 'percentage',
                'amount' => $period['amount'] ?? 0,
                'source' => 'global',
                'is_override' => false
            );
        }
    }

    // Then, apply vehicle overrides (matching by global_period_id or by name/date)
    foreach ($vehicle_overrides as $override) {
        // Check if this override references a global period
        $global_period_id = isset($override['global_period_id']) ? intval($override['global_period_id']) : 0;

        if ($global_period_id && isset($applicable[$global_period_id])) {
            // This is an override of a global period
            if (isset($override['override_pricing']) && $override['override_pricing']) {
                $applicable[$global_period_id]['adjustment_type'] = $override['adjustment_type'];
                $applicable[$global_period_id]['amount'] = $override['amount'];
                $applicable[$global_period_id]['is_override'] = true;
                $applicable[$global_period_id]['source'] = 'vehicle_override';
            }
        } else {
            // This is a vehicle-specific peak period (not linked to global)
            if ($override['start_date'] <= $end_date && $override['end_date'] >= $start_date) {
                $applicable['vehicle_' . $override['name']] = array(
                    'period_id' => 'vehicle_' . $override['name'],
                    'name' => $override['name'],
                    'start_date' => $override['start_date'],
                    'end_date' => $override['end_date'],
                    'adjustment_type' => $override['adjustment_type'],
                    'amount' => $override['amount'],
                    'source' => 'vehicle_only',
                    'is_override' => false
                );
            }
        }
    }

    // Re-index array
    return array_values($applicable);
}

/**
 * Calculate total peak pricing surcharge for a booking
 *
 * @param int $vehicle_id Vehicle post ID
 * @param float $base_price Base daily price
 * @param string $start_date Start date (Y-m-d)
 * @param string $end_date End date (Y-m-d)
 * @return float Total surcharge amount
 */
function ckl_calculate_peak_surcharge($vehicle_id, $base_price, $start_date, $end_date) {
    $applicable_pricing = ckl_get_applicable_peak_pricing($vehicle_id, $start_date, $end_date);

    if (empty($applicable_pricing)) {
        return 0;
    }

    // Calculate number of days in booking
    $days = max(1, ceil((strtotime($end_date) - strtotime($start_date)) / DAY_IN_SECONDS));

    // Use the highest surcharge (could be changed to sum all)
    $total_surcharge = 0;
    foreach ($applicable_pricing as $pricing) {
        $surcharge = 0;
        if ($pricing['adjustment_type'] === 'percentage') {
            $surcharge = $base_price * ($pricing['amount'] / 100) * $days;
        } else {
            // Fixed amount per day
            $surcharge = $pricing['amount'] * $days;
        }
        $total_surcharge = max($total_surcharge, $surcharge);
    }

    return $total_surcharge;
}

/**
 * ============================================================================
 * DATA MIGRATION FUNCTIONS
 * ============================================================================
 */

/**
 * Get the current migration version
 *
 * @return string Current migration version
 */
function ckl_get_migration_version() {
    return get_option('ckl_migration_version', '1.0.0');
}

/**
 * Update the migration version
 *
 * @param string $version New version
 */
function ckl_set_migration_version($version) {
    update_option('ckl_migration_version', $version);
}

/**
 * Migrate peak periods to include pricing fields
 *
 * Adds default pricing (25% surcharge) to existing peak periods
 * that don't have adjustment_type and amount fields.
 *
 * @return int Number of periods migrated
 */
function ckl_migrate_peak_periods_to_pricing() {
    $peak_periods = get_option('ckl_global_peak_prices', array());
    $migrated_count = 0;

    foreach ($peak_periods as &$period) {
        // Add pricing fields if not present
        if (!isset($period['adjustment_type'])) {
            $period['adjustment_type'] = 'percentage';
            $period['amount'] = 25; // Default 25% surcharge
            $migrated_count++;
        }
    }

    if ($migrated_count > 0) {
        update_option('ckl_global_peak_prices', $peak_periods);
    }

    return $migrated_count;
}

/**
 * Migrate vehicle peak pricing to new structure
 *
 * Converts existing vehicle-specific peak pricing to reference
 * global periods where possible by matching names.
 *
 * @return int Number of vehicles migrated
 */
function ckl_migrate_vehicle_peak_pricing() {
    $vehicles = get_posts(array(
        'post_type' => 'vehicle',
        'posts_per_page' => -1,
        'fields' => 'ids'
    ));

    $migrated_count = 0;
    $global_periods = get_option('ckl_global_peak_prices', array());

    // Create a lookup map for global periods by name
    $global_periods_by_name = array();
    foreach ($global_periods as $period) {
        $global_periods_by_name[sanitize_title($period['name'])] = $period['id'];
    }

    foreach ($vehicles as $vehicle_id) {
        $peak_pricing = get_post_meta($vehicle_id, '_peak_pricing', true);

        if (is_array($peak_pricing) && !empty($peak_pricing)) {
            $updated = false;

            foreach ($peak_pricing as &$pricing) {
                // Check if this matches a global period by name
                $name_slug = sanitize_title($pricing['name']);

                if (isset($global_periods_by_name[$name_slug])) {
                    // This matches a global period, add reference
                    $pricing['global_period_id'] = $global_periods_by_name[$name_slug];
                    $updated = true;
                }
            }

            if ($updated) {
                update_post_meta($vehicle_id, '_peak_pricing', $peak_pricing);
                $migrated_count++;
            }
        }
    }

    return $migrated_count;
}

/**
 * Run all pending migrations
 *
 * @return array Results of migrations
 */
function ckl_run_migrations() {
    $current_version = ckl_get_migration_version();
    $target_version = '2.0.0';
    $results = array();

    // Only run if version is below target
    if (version_compare($current_version, $target_version, '>=')) {
        return array(
            'success' => true,
            'message' => __('Already up to date', 'ckl-car-rental'),
            'migrations_run' => array()
        );
    }

    // Migration 1.0.0 -> 2.0.0: Add pricing to peak periods
    if (version_compare($current_version, '2.0.0', '<')) {
        $periods_migrated = ckl_migrate_peak_periods_to_pricing();
        $vehicles_migrated = ckl_migrate_vehicle_peak_pricing();

        $results['migrate_peak_periods'] = $periods_migrated;
        $results['migrate_vehicle_pricing'] = $vehicles_migrated;

        ckl_set_migration_version('2.0.0');
    }

    return array(
        'success' => true,
        'message' => __('Migrations completed successfully', 'ckl-car-rental'),
        'migrations_run' => $results
    );
}

/**
 * Hook migrations to admin_init
 *
 * Runs migrations on first admin page load after update
 */
function ckl_check_and_run_migrations() {
    // Only check for admins who can manage options
    if (!current_user_can('manage_options')) {
        return;
    }

    // Check if we need to run migrations
    $current_version = ckl_get_migration_version();
    if (version_compare($current_version, '2.0.0', '<')) {
        // Run migrations
        $result = ckl_run_migrations();

        // Optionally log results or show notice
        if ($result['success'] && !empty($result['migrations_run'])) {
            error_log('CKL Migrations: ' . print_r($result, true));
        }
    }
}
add_action('admin_init', 'ckl_check_and_run_migrations');

/**
 * AJAX handler for manual migration trigger
 *
 * Allows admins to trigger migrations manually via AJAX
 */
function ckl_ajax_run_migrations() {
    check_ajax_referer('ckl-migration-nonce', 'nonce');

    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => __('Permission denied', 'ckl-car-rental')));
    }

    $result = ckl_run_migrations();

    if ($result['success']) {
        wp_send_json_success($result);
    } else {
        wp_send_json_error($result);
    }
}
add_action('wp_ajax_ckl_run_migrations', 'ckl_ajax_run_migrations');
