<?php
/**
 * CKLANGKAWI Admin Menu
 *
 * Top-level menu for all CKL Angkawi related settings
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add CKLANGKAWI top-level menu
 */
function ckl_add_cklangkawi_menu() {
    add_menu_page(
        __('CKLANGKAWI Settings', 'ckl-car-rental'),
        __('CKLANGKAWI', 'ckl-car-rental'),
        'manage_options',
        'cklangkawi-settings',
        'ckl_settings_page_html', // Reuse existing settings page
        'dashicons-admin-generic',
        30
    );

    // Add submenu for the main settings (same as parent)
    add_submenu_page(
        'cklangkawi-settings',
        __('CKL Settings', 'ckl-car-rental'),
        __('General Settings', 'ckl-car-rental'),
        'manage_options',
        'cklangkawi-settings',
        'ckl_settings_page_html'
    );

    // Add Peak Periods Calendar submenu
    add_submenu_page(
        'cklangkawi-settings',
        __('Peak Periods Calendar', 'ckl-car-rental'),
        __('Peak Periods Calendar', 'ckl-car-rental'),
        'manage_options',
        'ckl-peak-calendar',
        'ckl_peak_price_calendar_page_html'
    );
}
add_action('admin_menu', 'ckl_add_cklangkawi_menu', 9); // Priority 9 to run before original menu

/**
 * Load admin page files
 */
function ckl_load_admin_page_files() {
    require_once get_template_directory() . '/admin/peak-price-calendar.php';
    require_once get_template_directory() . '/admin/whatsapp-settings.php';
}
add_action('admin_menu', 'ckl_load_admin_page_files', 10);

/**
 * Load vehicle services initialization
 */
add_action('admin_init', function() {
    if (current_user_can('manage_options')) {
        require_once dirname(__FILE__) . '/vehicle-services-init.php';
    }
});

/**
 * Load peak calendar AJAX handlers early (before admin_menu)
 *
 * AJAX requests happen independently of admin page loading and don't trigger
 * the admin_menu hook. We need to load the AJAX handlers on admin_init to ensure
 * the wp_ajax_* actions are registered before the AJAX request is processed.
 */
add_action('admin_init', function() {
    if (current_user_can('manage_options')) {
        require_once dirname(__FILE__) . '/peak-calendar-ajax.php';
    }
});
