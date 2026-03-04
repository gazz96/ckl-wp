<?php
/**
 * Uninstall plugin
 *
 * @package QuickSwap
 */

// Exit if accessed directly
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete options
delete_option('quickswap_settings');
delete_site_option('quickswap_settings');

// Remove capabilities
$roles = array('administrator', 'editor', 'author', 'contributor', 'subscriber');

foreach ($roles as $role_name) {
    $role = get_role($role_name);

    if ($role) {
        $role->remove_cap('quickswap_use_search');
        $role->remove_cap('quickswap_admin_pages');
        $role->remove_cap('quickswap_manage_plugins');
        $role->remove_cap('quickswap_manage_themes');
        $role->remove_cap('quickswap_manage_users');
        $role->remove_cap('quickswap_woocommerce');
    }
}

// Drop custom tables
global $wpdb;

$table_name = $wpdb->prefix . 'quickswap_search_index';
$log_table = $wpdb->prefix . 'quickswap_search_log';

$wpdb->query("DROP TABLE IF EXISTS $table_name");
$wpdb->query("DROP TABLE IF EXISTS $log_table");

// Clear any cached data
wp_cache_flush();
