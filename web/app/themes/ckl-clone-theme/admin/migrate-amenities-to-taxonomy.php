<?php
/**
 * Migrate Vehicle Amenities from Meta to Taxonomy
 *
 * One-time migration script to convert post meta based amenities
 * to taxonomy terms
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add migration menu item
 */
function ckl_amenity_migration_menu() {
    add_submenu_page(
        'cklangkawi-settings',
        __('Migrate Amenities', 'ckl-car-rental'),
        __('Migrate Amenities', 'ckl-car-rental'),
        'manage_options',
        'migrate-amenities-to-taxonomy',
        'ckl_render_amenity_migration_page'
    );
}
add_action('admin_menu', 'ckl_amenity_migration_menu');

/**
 * Render migration page
 */
function ckl_render_amenity_migration_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    $migration_completed = get_option('ckl_amenity_taxonomy_migration_completed', false);
    ?>
    <div class="wrap">
        <h1><?php _e('Migrate Vehicle Amenities to Taxonomy', 'ckl-car-rental'); ?></h1>

        <?php if ($migration_completed) : ?>
            <div class="notice notice-success">
                <p><?php _e('Migration has been completed!', 'ckl-car-rental'); ?></p>
            </div>
        <?php endif; ?>

        <div class="card" style="max-width: 600px; margin-top: 20px;">
            <p><?php _e('This will migrate all vehicle amenities from post meta to the new taxonomy system.', 'ckl-car-rental'); ?></p>
            <ul>
                <li><?php _e('Read all existing <code>_vehicle_amenities</code> post meta', 'ckl-car-rental'); ?></li>
                <li><?php _e('Create taxonomy terms from default amenities list', 'ckl-car-rental'); ?></li>
                <li><?php _e('Assign terms to vehicles based on their meta values', 'ckl-car-rental'); ?></li>
            </ul>

            <form method="post">
                <?php wp_nonce_field('ckl_migrate_amenities', 'ckl_migrate_nonce'); ?>
                <input type="hidden" name="ckl_migrate_amenities" value="1">
                <?php submit_button($migration_completed ? __('Run Migration Again', 'ckl-car-rental') : __('Start Migration', 'ckl-car-rental')); ?>
            </form>
        </div>
    </div>
    <?php
}

/**
 * Handle migration submission
 */
function ckl_handle_amenity_migration() {
    if (!isset($_POST['ckl_migrate_amenities']) || !isset($_POST['ckl_migrate_nonce'])) {
        return;
    }

    if (!current_user_can('manage_options')) {
        return;
    }

    if (!wp_verify_nonce($_POST['ckl_migrate_nonce'], 'ckl_migrate_amenities')) {
        wp_die(__('Security check failed.', 'ckl-car-rental'));
    }

    // Get default amenities
    $default_amenities = ckl_get_default_amenities();

    // First, ensure all default amenity terms exist
    $term_map = array();
    foreach ($default_amenities as $key => $amenity) {
        if (!$amenity['enabled']) {
            continue;
        }

        // Check if term exists
        $term = term_exists($amenity['label'], 'vehicle_amenity');

        if (!$term) {
            // Create term
            $term = wp_insert_term($amenity['label'], 'vehicle_amenity', array(
                'slug' => $key
            ));
            $term_map[$key] = $term['term_id'];
        } else {
            $term_map[$key] = $term['term_id'];
        }
    }

    // Get all vehicles
    $vehicles = get_posts(array(
        'post_type' => 'vehicle',
        'posts_per_page' => -1,
        'fields' => 'ids'
    ));

    $migrated_count = 0;

    foreach ($vehicles as $vehicle_id) {
        // Get old meta
        $amenities_meta = get_post_meta($vehicle_id, '_vehicle_amenities', true);

        if (!is_array($amenities_meta)) {
            continue;
        }

        // Collect term IDs to assign
        $term_ids = array();

        foreach ($amenities_meta as $key => $has_amenity) {
            if ($has_amenity && isset($term_map[$key])) {
                $term_ids[] = $term_map[$key];
            }
        }

        // Assign terms to vehicle
        if (!empty($term_ids)) {
            wp_set_post_terms($vehicle_id, $term_ids, 'vehicle_amenity', false);
            $migrated_count++;
        }

        // Optional: Delete old meta
        // delete_post_meta($vehicle_id, '_vehicle_amenities');
    }

    // Mark migration as complete
    update_option('ckl_amenity_taxonomy_migration_completed', current_time('mysql'));

    // Show success message
    add_action('admin_notices', function() use ($migrated_count) {
        echo '<div class="notice notice-success is-dismissible">';
        echo '<p>' . sprintf(__('Successfully migrated %d vehicles to the new taxonomy system.', 'ckl-car-rental'), $migrated_count) . '</p>';
        echo '</div>';
    });
}
add_action('admin_init', 'ckl_handle_amenity_migration');
