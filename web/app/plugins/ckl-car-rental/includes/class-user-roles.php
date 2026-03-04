<?php
/**
 * CKL User Roles
 *
 * Handles custom user roles for the car rental system
 */

if (!defined('ABSPATH')) {
    exit;
}

class CKL_User_Roles {

    /**
     * Initialize the class
     */
    public static function init() {
        add_action('init', array(__CLASS__, 'register_roles'));
        add_action('init', array(__CLASS__, 'add_capabilities'));
    }

    /**
     * Register custom user roles
     */
    public static function register_roles() {
        // Renter Role
        add_role(
            'ckl_renter',
            __('Renter', 'ckl-car-rental'),
            array(
                'read' => true,
                'read_product' => true,
                'read_vehicle' => true,
                'publish_posts' => false,
                'edit_posts' => false,
                'delete_posts' => false,
            )
        );

        // Owner Role
        add_role(
            'ckl_owner',
            __('Vehicle Owner', 'ckl-car-rental'),
            array(
                'read' => true,
                'read_product' => true,
                'read_vehicle' => true,
                'edit_vehicle' => true,
                'edit_vehicles' => true,
                'edit_published_vehicles' => true,
                'publish_vehicles' => true,
                'delete_vehicles' => false,
                'delete_published_vehicles' => false,
                'read_shop_order' => true,
                'edit_shop_order' => false,
                'read_shop_orders' => true,
            )
        );
    }

    /**
     * Add capabilities to administrator
     */
    public static function add_capabilities() {
        // Get administrator role
        $admin_role = get_role('administrator');

        if ($admin_role) {
            // Add custom post type capabilities
            $admin_role->add_cap('edit_vehicle');
            $admin_role->add_cap('read_vehicle');
            $admin_role->add_cap('delete_vehicle');
            $admin_role->add_cap('edit_vehicles');
            $admin_role->add_cap('edit_others_vehicles');
            $admin_role->add_cap('publish_vehicles');
            $admin_role->add_cap('read_private_vehicles');
            $admin_role->add_cap('delete_vehicles');
            $admin_role->add_cap('delete_private_vehicles');
            $admin_role->add_cap('delete_published_vehicles');
            $admin_role->add_cap('delete_others_vehicles');
            $admin_role->add_cap('edit_private_vehicles');
            $admin_role->add_cap('edit_published_vehicles');
        }
    }

    /**
     * Check if user is renter
     */
    public static function is_renter($user_id = 0) {
        if (!$user_id) {
            $user_id = get_current_user_id();
        }
        $user = get_userdata($user_id);
        return $user && in_array('ckl_renter', $user->roles);
    }

    /**
     * Check if user is owner
     */
    public static function is_owner($user_id = 0) {
        if (!$user_id) {
            $user_id = get_current_user_id();
        }
        $user = get_userdata($user_id);
        return $user && in_array('ckl_owner', $user->roles);
    }

    /**
     * Check if user can manage vehicles
     */
    public static function can_manage_vehicles($user_id = 0) {
        if (!$user_id) {
            $user_id = get_current_user_id();
        }
        return self::is_owner($user_id) || current_user_can('edit_vehicles');
    }

    /**
     * Get vehicles owned by user (for owners)
     */
    public static function get_user_vehicles($user_id = 0) {
        if (!$user_id) {
            $user_id = get_current_user_id();
        }

        // Only owners should have vehicles assigned
        // For now, return all vehicles for admins
        if (current_user_can('edit_vehicles')) {
            return get_posts(array(
                'post_type' => 'vehicle',
                'posts_per_page' => -1,
                'post_status' => 'publish',
            ));
        }

        return array();
    }
}
