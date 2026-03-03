<?php
/**
 * Vehicle Type Migration Script
 *
 * Migrates existing vehicle types from post meta to the vehicle_category taxonomy
 * Run this script once to convert all existing vehicle data
 *
 * Usage: Visit /wp-admin/admin.php?page=migrate-vehicle-types
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add admin menu for migration
 */
function ckl_migration_menu() {
    add_submenu_page(
        'ckl-theme-settings',
        __('Migrate Vehicle Types', 'ckl-car-rental'),
        __('Migrate Vehicle Types', 'ckl-car-rental'),
        'manage_options',
        'migrate-vehicle-types',
        'ckl_migration_page_html'
    );
}
add_action('admin_menu', 'ckl_migration_menu');

/**
 * Render migration page
 */
function ckl_migration_page_html() {
    if (!current_user_can('manage_options')) {
        return;
    }

    $migration_completed = get_option('ckl_vehicle_type_migration_completed', false);
    $message = '';
    $error = '';

    // Handle migration
    if (isset($_POST['ckl_migrate_vehicle_types']) && check_admin_referer('ckl_migrate_types', 'ckl_migration_nonce')) {
        $result = ckl_migrate_vehicle_types();

        if (is_wp_error($result)) {
            $error = $result->get_error_message();
        } else {
            $message = $result;
            update_option('ckl_vehicle_type_migration_completed', true);
            $migration_completed = true;
        }
    }

    ?>
    <div class="wrap">
        <h1><?php _e('Migrate Vehicle Types to Taxonomy', 'ckl-car-rental'); ?></h1>

        <?php if ($message) : ?>
            <div class="notice notice-success is-dismissible">
                <p><?php echo esc_html($message); ?></p>
            </div>
        <?php endif; ?>

        <?php if ($error) : ?>
            <div class="notice notice-error is-dismissible">
                <p><?php echo esc_html($error); ?></p>
            </div>
        <?php endif; ?>

        <?php if ($migration_completed) : ?>
            <div class="notice notice-warning">
                <p><?php _e('<strong>Migration already completed.</strong> Running it again may create duplicates.', 'ckl-car-rental'); ?></p>
            </div>
        <?php endif; ?>

        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2><?php _e('What This Does', 'ckl-car-rental'); ?></h2>
            <p><?php _e('This script migrates vehicle type data from the old post meta system (_vehicle_type) to the new vehicle_category taxonomy.', 'ckl-car-rental'); ?></p>

            <h3><?php _e('Migration Process', 'ckl-car-rental'); ?></h3>
            <ol>
                <li><?php _e('Finds all vehicles with _vehicle_type meta value', 'ckl-car-rental'); ?></li>
                <li><?php _e('Maps old type values to new taxonomy terms:', 'ckl-car-rental'); ?></li>
                <ul>
                    <li><code>sedan</code> → Cars > Sedan</li>
                    <li><code>compact</code> → Cars > Compact</li>
                    <li><code>mpv</code> → Cars > MPV</li>
                    <li><code>luxury_mpv</code> → Cars > Luxury MPV</li>
                    <li><code>suv</code> → Cars > SUV</li>
                    <li><code>4x4</code> → Cars > 4x4</li>
                    <li><code>scooter</code> → Motorcycles > Scooter</li>
                    <li><code>moped</code> → Motorcycles > Moped</li>
                    <li><code>sports_bike</code> → Motorcycles > Sports Bike</li>
                    <li><code>motorcycle</code> → Motorcycles > Scooter (default)</li>
                </ul>
                <li><?php _e('Assigns the corresponding taxonomy term to each vehicle', 'ckl-car-rental'); ?></li>
                <li><?php _e('Removes the old _vehicle_type meta field (optional)', 'ckl-car-rental'); ?></li>
            </ol>

            <h3><?php _e('Before You Begin', 'ckl-car-rental'); ?></h3>
            <ul>
                <li><?php _e('Backup your database before running this migration', 'ckl-car-rental'); ?></li>
                <li><?php _e('Ensure the vehicle_category taxonomy has been registered', 'ckl-car-rental'); ?></li>
                <li><?php _e('This script can be run multiple times safely (it checks for existing assignments)', 'ckl-car-rental'); ?></li>
            </ul>

            <form method="post" action="">
                <?php wp_nonce_field('ckl_migrate_types', 'ckl_migration_nonce'); ?>

                <p>
                    <label>
                        <input type="checkbox" name="remove_old_meta" value="1">
                        <?php _e('Remove old _vehicle_type meta fields after migration', 'ckl-car-rental'); ?>
                    </label>
                </p>

                <p class="submit">
                    <button type="submit" name="ckl_migrate_vehicle_types" class="button button-primary button-large">
                        <?php _e('Run Migration', 'ckl-car-rental'); ?>
                    </button>
                </p>
            </form>
        </div>
    </div>
    <?php
}

/**
 * Perform vehicle type migration
 *
 * @return string|WP_Error Success message or error
 */
function ckl_migrate_vehicle_types() {
    // Mapping of old meta values to new taxonomy terms
    $type_mapping = array(
        'sedan'       => 'Sedan',
        'compact'     => 'Compact',
        'mpv'         => 'MPV',
        'luxury_mpv'  => 'Luxury MPV',
        'suv'         => 'SUV',
        '4x4'         => '4x4',
        'scooter'     => 'Scooter',
        'moped'       => 'Moped',
        'sports_bike' => 'Sports Bike',
        'motorcycle'  => 'Scooter', // Default motorcycle to scooter
    );

    // Get all vehicles with _vehicle_type meta
    $vehicles = get_posts(array(
        'post_type'      => 'vehicle',
        'post_status'    => 'any',
        'posts_per_page' => -1,
        'meta_query'     => array(
            array(
                'key'     => '_vehicle_type',
                'compare' => 'EXISTS',
            ),
        ),
    ));

    if (empty($vehicles)) {
        return new WP_Error('no_vehicles', __('No vehicles found with _vehicle_type meta field.', 'ckl-car-rental'));
    }

    $migrated = 0;
    $skipped = 0;
    $errors = array();

    foreach ($vehicles as $vehicle) {
        $old_type = get_post_meta($vehicle->ID, '_vehicle_type', true);

        if (empty($old_type)) {
            continue;
        }

        // Map old type to new term
        $term_name = isset($type_mapping[$old_type]) ? $type_mapping[$old_type] : null;

        if (!$term_name) {
            $errors[] = sprintf(__('Vehicle #%d: Unknown type "%s"', 'ckl-car-rental'), $vehicle->ID, $old_type);
            continue;
        }

        // Get the term
        $term = get_term_by('name', $term_name, 'vehicle_category');

        if (!$term || is_wp_error($term)) {
            $errors[] = sprintf(__('Vehicle #%d: Term "%s" not found', 'ckl-car-rental'), $vehicle->ID, $term_name);
            continue;
        }

        // Check if vehicle already has this term
        $existing_terms = wp_get_object_terms($vehicle->ID, 'vehicle_category', array('fields' => 'ids'));
        if (in_array($term->term_id, $existing_terms)) {
            $skipped++;
            continue;
        }

        // Assign term to vehicle
        $result = wp_set_object_terms($vehicle->ID, $term->term_id, 'vehicle_category', true);

        if (is_wp_error($result)) {
            $errors[] = sprintf(__('Vehicle #%d: %s', 'ckl-car-rental'), $vehicle->ID, $result->get_error_message());
            continue;
        }

        $migrated++;
    }

    // Optionally remove old meta
    $remove_meta = isset($_POST['remove_old_meta']) && $_POST['remove_old_meta'];
    if ($remove_meta && $migrated > 0) {
        foreach ($vehicles as $vehicle) {
            delete_post_meta($vehicle->ID, '_vehicle_type');
        }
    }

    // Build result message
    $message = sprintf(
        __('Migration completed! Migrated: %d, Skipped: %d', 'ckl-car-rental'),
        $migrated,
        $skipped
    );

    if (!empty($errors)) {
        $message .= ' ' . sprintf(__('Errors: %d', 'ckl-car-rental'), count($errors));
        // Log errors for debugging
        error_log('CKL Vehicle Type Migration Errors: ' . print_r($errors, true));
    }

    if ($remove_meta) {
        $message .= ' ' . __('Old meta fields removed.', 'ckl-car-rental');
    }

    return $message;
}

/**
 * AJAX handler for migration (alternative method)
 */
function ckl_ajax_migrate_vehicle_types() {
    check_ajax_referer('ckl_migration_nonce', 'nonce');

    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => __('Permission denied', 'ckl-car-rental')));
    }

    $result = ckl_migrate_vehicle_types();

    if (is_wp_error($result)) {
        wp_send_json_error(array('message' => $result->get_error_message()));
    } else {
        update_option('ckl_vehicle_type_migration_completed', true);
        wp_send_json_success(array('message' => $result));
    }
}
// Uncomment to enable AJAX migration
// add_action('wp_ajax_ckl_migrate_vehicle_types', 'ckl_ajax_migrate_vehicle_types');
