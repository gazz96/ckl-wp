<?php
/**
 * Initialize Theme Settings
 *
 * Run this script once to initialize all default theme settings
 * Access: /wp-admin/?page=ckl-init-settings
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add initialization page
 */
function ckl_add_init_settings_page() {
    add_theme_page(
        __('Initialize Settings', 'ckl-car-rental'),
        __('Initialize Settings', 'ckl-car-rental'),
        'manage_options',
        'ckl-init-settings',
        'ckl_render_init_settings_page'
    );
}
add_action('admin_menu', 'ckl_add_init_settings_page');

/**
 * Render initialization page
 */
function ckl_render_init_settings_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    $message = '';

    // Handle form submission
    if (isset($_POST['ckl_init_settings']) && check_admin_referer('ckl_init_settings_action')) {
        // Initialize all default settings
        update_option('ckl_homepage_sections', ckl_get_default_homepage_sections());
        update_option('ckl_hero_settings', ckl_get_default_hero_settings());
        update_option('ckl_vehicle_display_settings', ckl_get_default_vehicle_display_settings());
        update_option('ckl_global_pricing', ckl_get_default_pricing_settings());
        update_option('ckl_amenities_list', ckl_get_default_amenities());
        update_option('ckl_manual_reviews', array());

        $message = '<div class="notice notice-success"><p>All theme settings have been initialized to their default values!</p></div>';
    }

    // Check current settings
    $homepage_sections = get_option('ckl_homepage_sections');
    $hero_settings = get_option('ckl_hero_settings');
    $has_settings = ($homepage_sections && $hero_settings);
    ?>
    <div class="wrap">
        <h1><?php _e('Initialize CKL Theme Settings', 'ckl-car-rental'); ?></h1>

        <?php echo $message; ?>

        <?php if ($has_settings) : ?>
            <div class="notice notice-info">
                <p><strong><?php _e('Settings Status:', 'ckl-car-rental'); ?></strong></p>
                <ul>
                    <li><?php printf(__('Homepage Sections: %s', 'ckl-car-rental'), $homepage_sections ? __('✓ Configured', 'ckl-car-rental') : __('✗ Not set', 'ckl-car-rental')); ?></li>
                    <li><?php printf(__('Hero Settings: %s', 'ckl-car-rental'), $hero_settings ? __('✓ Configured', 'ckl-car-rental') : __('✗ Not set', 'ckl-car-rental')); ?></li>
                </ul>
            </div>
        <?php endif; ?>

        <div class="card">
            <h2><?php _e('Initialize Default Settings', 'ckl-car-rental'); ?></h2>
            <p><?php _e('Click the button below to initialize all theme settings to their default values. This will:', 'ckl-car-rental'); ?></p>
            <ul>
                <li><?php _e('Enable all homepage sections (Hero, Mobile Search, How It Works, Vehicles, Reviews, FAQ, News)', 'ckl-car-rental'); ?></li>
                <li><?php _e('Set default hero title and subtitle', 'ckl-car-rental'); ?></li>
                <li><?php _e('Configure default vehicle display settings', 'ckl-car-rental'); ?></li>
                <li><?php _e('Set up default pricing multipliers', 'ckl-car-rental'); ?></li>
                <li><?php _e('Create default amenities list', 'ckl-car-rental'); ?></li>
            </ul>

            <form method="post" action="">
                <?php wp_nonce_field('ckl_init_settings_action'); ?>
                <input type="submit" name="ckl_init_settings" class="button button-primary" value="<?php _e('Initialize Settings', 'ckl-car-rental'); ?>">
            </form>
        </div>

        <div class="card" style="margin-top: 20px;">
            <h2><?php _e('Current Homepage Section Status', 'ckl-car-rental'); ?></h2>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php _e('Section', 'ckl-car-rental'); ?></th>
                        <th><?php _e('Status', 'ckl-car-rental'); ?></th>
                        <th><?php _e('Order', 'ckl-car-rental'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sections = get_option('ckl_homepage_sections', ckl_get_default_homepage_sections());
                    $section_names = array(
                        'hero' => __('Hero Section', 'ckl-car-rental'),
                        'mobile_search' => __('Mobile Search', 'ckl-car-rental'),
                        'how_it_works' => __('How It Works', 'ckl-car-rental'),
                        'vehicle_grid' => __('Vehicle Grid', 'ckl-car-rental'),
                        'reviews' => __('Reviews', 'ckl-car-rental'),
                        'faq' => __('FAQ', 'ckl-car-rental'),
                        'news_section' => __('News Section', 'ckl-car-rental'),
                    );

                    foreach ($sections as $key => $section) :
                        $enabled = isset($section['enabled']) && $section['enabled'];
                        $order = isset($section['order']) ? $section['order'] : 0;
                        $name = isset($section_names[$key]) ? $section_names[$key] : $key;
                    ?>
                        <tr>
                            <td><?php echo esc_html($name); ?></td>
                            <td>
                                <?php if ($enabled) : ?>
                                    <span class="dashicons dashicons-yes-alt" style="color: green;"></span>
                                    <?php _e('Enabled', 'ckl-car-rental'); ?>
                                <?php else : ?>
                                    <span class="dashicons dashicons-dismiss" style="color: red;"></span>
                                    <?php _e('Disabled', 'ckl-car-rental'); ?>
                                <?php endif; ?>
                            </td>
                            <td><?php echo intval($order); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
}
