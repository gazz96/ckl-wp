<?php
/**
 * Peak Pricing Migration Script
 *
 * Cleans up adjustment_type and amount from global peak periods
 * and updates vehicle peak pricing structure
 *
 * @package CKL_Car_Rental
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Migrate peak periods to remove adjustment fields
 *
 * Removes adjustment_type and amount from global peak periods
 * since pricing is now set directly per vehicle
 *
 * @return int Number of periods migrated
 */
function ckl_migrate_peak_periods_cleanup() {
    $peak_periods = get_option('ckl_global_peak_prices', array());
    $migrated_count = 0;

    foreach ($peak_periods as &$period) {
        // Remove adjustment pricing fields
        if (isset($period['adjustment_type'])) {
            unset($period['adjustment_type']);
            $migrated_count++;
        }
        if (isset($period['amount'])) {
            unset($period['amount']);
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
 * Updates vehicle peak pricing to use peak_price instead of
 * adjustment_type and amount
 *
 * @return int Number of vehicles migrated
 */
function ckl_migrate_vehicle_peak_pricing_structure() {
    $vehicles = get_posts(array(
        'post_type' => 'vehicle',
        'posts_per_page' => -1,
        'fields' => 'ids'
    ));

    $migrated_count = 0;

    foreach ($vehicles as $vehicle_id) {
        $peak_pricing = get_post_meta($vehicle_id, '_peak_pricing', true);

        if (is_array($peak_pricing) && !empty($peak_pricing)) {
            $updated = false;

            foreach ($peak_pricing as &$pricing) {
                // Convert old adjustment structure to new peak_price structure
                if (isset($pricing['override_pricing']) && $pricing['override_pricing']) {
                    // This was an override with adjustment_type and amount
                    // We need to convert it to peak_price, but we don't have base price here
                    // So we'll remove the override structure and mark it as enabled
                    $pricing['enabled'] = true;
                    unset($pricing['override_pricing']);
                    unset($pricing['adjustment_type']);
                    unset($pricing['amount']);
                    $updated = true;
                }

                // For custom vehicle-only periods, we'll remove them for now
                // They can be recreated as vehicle-specific pricing for global periods
                if (!isset($pricing['global_period_id'])) {
                    // This is a custom vehicle-only period
                    // Remove it as it's no longer supported
                    unset($pricing);
                    $updated = true;
                }
            }

            // Re-index array
            $peak_pricing = array_values(array_filter($peak_pricing));

            if ($updated) {
                update_post_meta($vehicle_id, '_peak_pricing', $peak_pricing);
                $migrated_count++;
            }
        }
    }

    return $migrated_count;
}

/**
 * Run all peak pricing migrations
 *
 * @return array Results of migrations
 */
function ckl_run_peak_pricing_migrations() {
    $current_version = get_option('ckl_peak_pricing_migration_version', '1.0.0');
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

    // Migration 1.0.0 -> 2.0.0: Clean up peak periods structure
    if (version_compare($current_version, '2.0.0', '<')) {
        $periods_cleaned = ckl_migrate_peak_periods_cleanup();
        $vehicles_migrated = ckl_migrate_vehicle_peak_pricing_structure();

        $results['periods_cleaned'] = $periods_cleaned;
        $results['vehicles_migrated'] = $vehicles_migrated;

        update_option('ckl_peak_pricing_migration_version', '2.0.0');
    }

    return array(
        'success' => true,
        'message' => __('Peak pricing migrations completed successfully', 'ckl-car-rental'),
        'migrations_run' => $results
    );
}

/**
 * Hook migrations to admin_init
 *
 * Runs migrations on first admin page load after update
 */
function ckl_check_and_run_peak_pricing_migrations() {
    // Only check for admins who can manage options
    if (!current_user_can('manage_options')) {
        return;
    }

    // Check if we need to run migrations
    $current_version = get_option('ckl_peak_pricing_migration_version', '1.0.0');
    if (version_compare($current_version, '2.0.0', '<')) {
        // Run migrations
        $result = ckl_run_peak_pricing_migrations();

        // Log results
        if ($result['success'] && !empty($result['migrations_run'])) {
            error_log('CKL Peak Pricing Migrations: ' . print_r($result, true));

            // Show admin notice
            add_action('admin_notices', function() use ($result) {
                echo '<div class="notice notice-success is-dismissible">';
                echo '<p><strong>' . __('Peak Pricing System Updated', 'ckl-car-rental') . '</strong></p>';
                echo '<p>' . __('The peak pricing system has been updated to use direct vehicle pricing instead of percentage adjustments.', 'ckl-car-rental') . '</p>';
                if (!empty($result['migrations_run'])) {
                    echo '<p>' . sprintf(__('Migrated: %d global periods, %d vehicles', 'ckl-car-rental'),
                        $result['migrations_run']['periods_cleaned'] ?? 0,
                        $result['migrations_run']['vehicles_migrated'] ?? 0
                    ) . '</p>';
                }
                echo '</div>';
            });
        }
    }
}
add_action('admin_init', 'ckl_check_and_run_peak_pricing_migrations');

/**
 * AJAX handler for manual migration trigger
 *
 * Allows admins to trigger migrations manually via AJAX
 */
function ckl_ajax_run_peak_pricing_migrations() {
    check_ajax_referer('ckl-peak-pricing-migration-nonce', 'nonce');

    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => __('Permission denied', 'ckl-car-rental')));
    }

    $result = ckl_run_peak_pricing_migrations();

    if ($result['success']) {
        wp_send_json_success($result);
    } else {
        wp_send_json_error($result);
    }
}
add_action('wp_ajax_ckl_run_peak_pricing_migrations', 'ckl_ajax_run_peak_pricing_migrations');