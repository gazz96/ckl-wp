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

    // Add Peak Price Calendar submenu
    add_submenu_page(
        'cklangkawi-settings',
        __('Peak Price Calendar', 'ckl-car-rental'),
        __('Peak Price Calendar', 'ckl-car-rental'),
        'manage_options',
        'ckl-peak-calendar',
        'ckl_peak_price_calendar_page_html'
    );

    // Add Pricing Rules submenu
    add_submenu_page(
        'cklangkawi-settings',
        __('Pricing Rules', 'ckl-car-rental'),
        __('Pricing Rules', 'ckl-car-rental'),
        'manage_options',
        'ckl-pricing-rules',
        'ckl_pricing_rules_page_html'
    );
}
add_action('admin_menu', 'ckl_add_cklangkawi_menu', 9); // Priority 9 to run before original menu

/**
 * Load admin page files
 */
function ckl_load_admin_page_files() {
    require_once get_template_directory() . '/admin/peak-price-calendar.php';
    require_once get_template_directory() . '/admin/pricing-rules.php';
}
add_action('admin_menu', 'ckl_load_admin_page_files', 10);
