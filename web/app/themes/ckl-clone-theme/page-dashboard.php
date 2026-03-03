<?php
/**
 * Template Name: User Dashboard
 *
 * @package CKL_Car_Rental
 */

get_header();

// Redirect if not logged in
if (!is_user_logged_in()) {
    wp_redirect(wp_login_url(get_permalink()));
    exit;
}

$current_user = wp_get_current_user();
$user_roles = $current_user->roles;
$primary_role = !empty($user_roles) ? $user_roles[0] : 'subscriber';

// Get dashboard template based on role
$dashboard_template = locate_template('templates/dashboard-' . $primary_role . '.php');

if ($dashboard_template) {
    require $dashboard_template;
} else {
    // Default dashboard for subscribers/renters
    require locate_template('templates/dashboard-renter.php');
}

get_footer();
