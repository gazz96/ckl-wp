<?php
/**
 * Force Initialize Settings
 *
 * Run this file once to force initialize all theme settings
 * Then delete this file for security
 *
 * USAGE: Visit /wp-admin/admin.php?page=force-init-settings
 * Or: Run this from WordPress admin
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add force init page
 */
function ckl_force_init_settings_admin_menu() {
    add_theme_page(
        'Force Init Settings',
        'Force Init Settings',
        'manage_options',
        'force-init-settings',
        'ckl_force_init_settings_page'
    );
}
add_action('admin_menu', 'ckl_force_init_settings_admin_menu');

/**
 * Render force init page and execute
 */
function ckl_force_init_settings_page() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.', 'ckl-car-rental'));
    }

    // Force initialize settings
    $result = array();

    // Initialize homepage sections
    $default_sections = ckl_get_default_homepage_sections();
    update_option('ckl_homepage_sections', $default_sections);
    $result[] = 'Homepage sections initialized';

    // Initialize hero settings
    update_option('ckl_hero_settings', ckl_get_default_hero_settings());
    $result[] = 'Hero settings initialized';

    // Initialize vehicle display settings
    update_option('ckl_vehicle_display_settings', ckl_get_default_vehicle_display_settings());
    $result[] = 'Vehicle display settings initialized';

    // Initialize pricing settings
    update_option('ckl_global_pricing', ckl_get_default_pricing_settings());
    $result[] = 'Pricing settings initialized';

    // Initialize amenities
    update_option('ckl_amenities_list', ckl_get_default_amenities());
    $result[] = 'Amenities initialized';

    // Initialize reviews (empty array)
    update_option('ckl_manual_reviews', array());
    $result[] = 'Manual reviews initialized';

    ?>
    <div class="wrap">
        <h1>Force Initialize Theme Settings</h1>

        <div class="notice notice-success">
            <p><strong>Success!</strong> All theme settings have been initialized:</p>
            <ul>
                <?php foreach ($result as $msg) : ?>
                    <li><?php echo esc_html($msg); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="card">
            <h2>What's Next?</h2>
            <ol>
                <li><a href="<?php echo admin_url('admin.php?page=ckl-theme-settings'); ?>">Go to CKL Settings</a> to customize your homepage</li>
                <li><a href="<?php echo home_url(); ?>">Visit your homepage</a> to see the changes</li>
                <li>Delete this file: <code>admin/force-init-settings.php</code> for security</li>
            </ol>
        </div>

        <div class="card" style="margin-top: 20px;">
            <h2>Current Settings</h2>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>Section</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sections = get_option('ckl_homepage_sections');
                    $section_names = array(
                        'hero' => 'Hero Section',
                        'mobile_search' => 'Mobile Search',
                        'how_it_works' => 'How It Works',
                        'vehicle_grid' => 'Vehicle Grid',
                        'reviews' => 'Reviews',
                        'faq' => 'FAQ',
                        'news_section' => 'News Section',
                    );

                    foreach ($sections as $key => $section) :
                        $enabled = isset($section['enabled']) && $section['enabled'];
                        $name = isset($section_names[$key]) ? $section_names[$key] : $key;
                    ?>
                        <tr>
                            <td><?php echo esc_html($name); ?></td>
                            <td>
                                <?php if ($enabled) : ?>
                                    <strong style="color: green;">✓ ENABLED</strong>
                                <?php else : ?>
                                    <span style="color: red;">✗ DISABLED</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
}
