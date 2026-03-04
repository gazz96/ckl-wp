<?php
/**
 * CKL Admin AJAX Handlers
 *
 * Handles all AJAX requests for admin operations
 * including peak prices, pricing rules, and templates
 */

if (!defined('ABSPATH')) {
    exit;
}

class CKL_Admin_AJAX {

    /**
     * Initialize the class and register AJAX handlers
     */
    public static function init() {
        // Peak Price AJAX handlers
        add_action('wp_ajax_ckl_add_peak_price', array(__CLASS__, 'add_peak_price'));
        add_action('wp_ajax_ckl_update_peak_price', array(__CLASS__, 'update_peak_price'));
        add_action('wp_ajax_ckl_delete_peak_price', array(__CLASS__, 'delete_peak_price'));
        add_action('wp_ajax_ckl_get_peak_prices', array(__CLASS__, 'get_peak_prices'));
        add_action('wp_ajax_ckl_toggle_peak_price', array(__CLASS__, 'toggle_peak_price'));
        add_action('wp_ajax_ckl_bulk_toggle_peak_prices', array(__CLASS__, 'bulk_toggle_peak_prices'));

        // Pricing Rules AJAX handlers
        add_action('wp_ajax_ckl_add_global_rule', array(__CLASS__, 'add_global_rule'));
        add_action('wp_ajax_ckl_update_global_rule', array(__CLASS__, 'update_global_rule'));
        add_action('wp_ajax_ckl_delete_global_rule', array(__CLASS__, 'delete_global_rule'));
        add_action('wp_ajax_ckl_toggle_global_rule', array(__CLASS__, 'toggle_global_rule'));
        add_action('wp_ajax_ckl_reorder_global_rules', array(__CLASS__, 'reorder_global_rules'));
        add_action('wp_ajax_ckl_get_global_rules', array(__CLASS__, 'get_global_rules'));

        // Template AJAX handlers
        add_action('wp_ajax_ckl_save_template', array(__CLASS__, 'save_template'));
        add_action('wp_ajax_ckl_get_templates', array(__CLASS__, 'get_templates'));
        add_action('wp_ajax_ckl_apply_template', array(__CLASS__, 'apply_template'));
        add_action('wp_ajax_ckl_delete_template', array(__CLASS__, 'delete_template'));
        add_action('wp_ajax_ckl_export_templates', array(__CLASS__, 'export_templates'));
        add_action('wp_ajax_ckl_import_templates', array(__CLASS__, 'import_templates'));

        // Bulk Actions AJAX handlers
        add_action('wp_ajax_ckl_bulk_apply_rule', array(__CLASS__, 'bulk_apply_rule'));
        add_action('wp_ajax_ckl_clone_vehicle_rule', array(__CLASS__, 'clone_vehicle_rule'));
        add_action('wp_ajax_ckl_preview_bulk_apply', array(__CLASS__, 'preview_bulk_apply'));
    }

    /**
     * Verify permissions for all AJAX requests
     */
    private static function verify_permissions() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Permission denied', 'ckl-car-rental')));
        }
    }

    // ====================
    // PEAK PRICE HANDLERS
    // ====================

    /**
     * Add new peak price
     */
    public static function add_peak_price() {
        check_ajax_referer('ckl-peak-price-nonce', 'nonce');
        self::verify_permissions();

        $data = isset($_POST['peak_price']) ? $_POST['peak_price'] : array();

        $result = CKL_Peak_Price_Manager::add_peak_price($data);

        if (is_wp_error($result)) {
            wp_send_json_error(array('message' => $result->get_error_message()));
        }

        wp_send_json_success(array(
            'message' => __('Peak price added successfully', 'ckl-car-rental'),
            'id' => $result,
        ));
    }

    /**
     * Update existing peak price
     */
    public static function update_peak_price() {
        check_ajax_referer('ckl-peak-price-nonce', 'nonce');
        self::verify_permissions();

        $id = isset($_POST['id']) ? $_POST['id'] : 0;
        $data = isset($_POST['peak_price']) ? $_POST['peak_price'] : array();

        $result = CKL_Peak_Price_Manager::update_peak_price($id, $data);

        if (is_wp_error($result)) {
            wp_send_json_error(array('message' => $result->get_error_message()));
        }

        wp_send_json_success(array(
            'message' => __('Peak price updated successfully', 'ckl-car-rental'),
        ));
    }

    /**
     * Delete peak price
     */
    public static function delete_peak_price() {
        check_ajax_referer('ckl-peak-price-nonce', 'nonce');
        self::verify_permissions();

        $id = isset($_POST['id']) ? $_POST['id'] : 0;

        CKL_Peak_Price_Manager::delete_peak_price($id);

        wp_send_json_success(array(
            'message' => __('Peak price deleted successfully', 'ckl-car-rental'),
        ));
    }

    /**
     * Get all peak prices
     */
    public static function get_peak_prices() {
        check_ajax_referer('ckl-peak-price-nonce', 'nonce');
        self::verify_permissions();

        $peak_prices = CKL_Peak_Price_Manager::get_all_peak_prices();

        wp_send_json_success(array(
            'peak_prices' => $peak_prices,
        ));
    }

    /**
     * Toggle peak price active status
     */
    public static function toggle_peak_price() {
        check_ajax_referer('ckl-peak-price-nonce', 'nonce');
        self::verify_permissions();

        $id = isset($_POST['id']) ? $_POST['id'] : 0;
        $active = isset($_POST['active']) ? $_POST['active'] : null;

        CKL_Peak_Price_Manager::toggle_peak_price($id, $active);

        wp_send_json_success(array(
            'message' => __('Peak price updated', 'ckl-car-rental'),
        ));
    }

    /**
     * Bulk toggle peak prices
     */
    public static function bulk_toggle_peak_prices() {
        check_ajax_referer('ckl-peak-price-nonce', 'nonce');
        self::verify_permissions();

        $ids = isset($_POST['ids']) ? $_POST['ids'] : array();
        $active = isset($_POST['active']) ? boolval($_POST['active']) : true;

        foreach ($ids as $id) {
            CKL_Peak_Price_Manager::toggle_peak_price($id, $active);
        }

        wp_send_json_success(array(
            'message' => sprintf(__('%d peak prices updated', 'ckl-car-rental'), count($ids)),
        ));
    }

    // ====================
    // GLOBAL RULES HANDLERS
    // ====================

    /**
     * Add new global rule
     */
    public static function add_global_rule() {
        check_ajax_referer('ckl-pricing-rules-nonce', 'nonce');
        self::verify_permissions();

        $rule = isset($_POST['rule']) ? $_POST['rule'] : array();

        $global_rules = get_option('ckl_global_pricing_rules', array());

        if (empty($rule['name']) || empty($rule['start_date']) || empty($rule['end_date'])) {
            wp_send_json_error(array('message' => __('Missing required fields', 'ckl-car-rental')));
        }

        $new_rule = array(
            'id' => isset($rule['id']) ? intval($rule['id']) : uniqid('global_', true),
            'name' => sanitize_text_field($rule['name']),
            'start_date' => sanitize_text_field($rule['start_date']),
            'end_date' => sanitize_text_field($rule['end_date']),
            'adjustment_type' => sanitize_text_field($rule['type'] ?? 'percentage'),
            'amount' => floatval($rule['amount'] ?? 0),
            'recurring' => sanitize_text_field($rule['recurring'] ?? 'none'),
            'priority' => intval($rule['priority'] ?? 90),
            'active' => boolval($rule['active'] ?? true),
        );

        $global_rules[] = $new_rule;

        // Sort by priority
        usort($global_rules, function($a, $b) {
            return $b['priority'] - $a['priority'];
        });

        update_option('ckl_global_pricing_rules', $global_rules);

        wp_send_json_success(array(
            'message' => __('Global rule added successfully', 'ckl-car-rental'),
            'id' => $new_rule['id'],
        ));
    }

    /**
     * Update existing global rule
     */
    public static function update_global_rule() {
        check_ajax_referer('ckl-pricing-rules-nonce', 'nonce');
        self::verify_permissions();

        $rule_id = isset($_POST['rule_id']) ? $_POST['rule_id'] : 0;
        $rule = isset($_POST['rule']) ? $_POST['rule'] : array();

        $global_rules = get_option('ckl_global_pricing_rules', array());

        $index = -1;
        foreach ($global_rules as $i => $r) {
            if ($r['id'] == $rule_id) {
                $index = $i;
                break;
            }
        }

        if ($index < 0) {
            wp_send_json_error(array('message' => __('Rule not found', 'ckl-car-rental')));
        }

        // Update fields
        if (isset($rule['name'])) {
            $global_rules[$index]['name'] = sanitize_text_field($rule['name']);
        }
        if (isset($rule['start_date'])) {
            $global_rules[$index]['start_date'] = sanitize_text_field($rule['start_date']);
        }
        if (isset($rule['end_date'])) {
            $global_rules[$index]['end_date'] = sanitize_text_field($rule['end_date']);
        }
        if (isset($rule['type'])) {
            $global_rules[$index]['adjustment_type'] = sanitize_text_field($rule['type']);
        }
        if (isset($rule['amount'])) {
            $global_rules[$index]['amount'] = floatval($rule['amount']);
        }
        if (isset($rule['recurring'])) {
            $global_rules[$index]['recurring'] = sanitize_text_field($rule['recurring']);
        }
        if (isset($rule['priority'])) {
            $global_rules[$index]['priority'] = intval($rule['priority']);
        }
        if (isset($rule['active'])) {
            $global_rules[$index]['active'] = boolval($rule['active']);
        }

        // Sort by priority
        usort($global_rules, function($a, $b) {
            return $b['priority'] - $a['priority'];
        });

        update_option('ckl_global_pricing_rules', $global_rules);

        wp_send_json_success(array(
            'message' => __('Global rule updated successfully', 'ckl-car-rental'),
        ));
    }

    /**
     * Delete global rule
     */
    public static function delete_global_rule() {
        check_ajax_referer('ckl-pricing-rules-nonce', 'nonce');
        self::verify_permissions();

        $rule_id = isset($_POST['rule_id']) ? $_POST['rule_id'] : 0;

        $global_rules = get_option('ckl_global_pricing_rules', array());

        $global_rules = array_filter($global_rules, function($r) use ($rule_id) {
            return $r['id'] != $rule_id;
        });

        update_option('ckl_global_pricing_rules', array_values($global_rules));

        wp_send_json_success(array(
            'message' => __('Global rule deleted', 'ckl-car-rental'),
        ));
    }

    /**
     * Toggle global rule active status
     */
    public static function toggle_global_rule() {
        check_ajax_referer('ckl-pricing-rules-nonce', 'nonce');
        self::verify_permissions();

        $rule_id = isset($_POST['rule_id']) ? $_POST['rule_id'] : 0;

        $global_rules = get_option('ckl_global_pricing_rules', array());

        foreach ($global_rules as &$rule) {
            if ($rule['id'] == $rule_id) {
                $rule['active'] = !$rule['active'];
                break;
            }
        }

        update_option('ckl_global_pricing_rules', $global_rules);

        wp_send_json_success(array(
            'message' => __('Global rule updated', 'ckl-car-rental'),
        ));
    }

    /**
     * Reorder global rules
     */
    public static function reorder_global_rules() {
        check_ajax_referer('ckl-pricing-rules-nonce', 'nonce');
        self::verify_permissions();

        $rule_ids = isset($_POST['rule_ids']) ? $_POST['rule_ids'] : array();

        $global_rules = get_option('ckl_global_pricing_rules', array());

        // Create ordered array based on new order
        $ordered_rules = array();
        foreach ($rule_ids as $rule_id) {
            foreach ($global_rules as $rule) {
                if ($rule['id'] == $rule_id) {
                    $ordered_rules[] = $rule;
                    break;
                }
            }
        }

        // Update priorities based on new order
        $priority = 100;
        foreach ($ordered_rules as &$rule) {
            $rule['priority'] = $priority--;
        }

        update_option('ckl_global_pricing_rules', $ordered_rules);

        wp_send_json_success(array(
            'message' => __('Rules reordered', 'ckl-car-rental'),
        ));
    }

    /**
     * Get all global rules
     */
    public static function get_global_rules() {
        check_ajax_referer('ckl-pricing-rules-nonce', 'nonce');
        self::verify_permissions();

        $global_rules = get_option('ckl_global_pricing_rules', array());

        wp_send_json_success(array(
            'rules' => $global_rules,
        ));
    }

    // ====================
    // TEMPLATE HANDLERS
    // ====================

    /**
     * Save template
     */
    public static function save_template() {
        check_ajax_referer('ckl-pricing-rules-nonce', 'nonce');
        self::verify_permissions();

        $data = isset($_POST['template']) ? $_POST['template'] : array();

        $result = CKL_Pricing_Rule_Templates::save_template($data);

        if (is_wp_error($result)) {
            wp_send_json_error(array('message' => $result->get_error_message()));
        }

        wp_send_json_success(array(
            'message' => __('Template saved successfully', 'ckl-car-rental'),
            'id' => $result,
        ));
    }

    /**
     * Get all templates
     */
    public static function get_templates() {
        check_ajax_referer('ckl-pricing-rules-nonce', 'nonce');
        self::verify_permissions();

        $templates = CKL_Pricing_Rule_Templates::get_all_templates();

        wp_send_json_success(array(
            'templates' => $templates,
        ));
    }

    /**
     * Apply template
     */
    public static function apply_template() {
        check_ajax_referer('ckl-pricing-rules-nonce', 'nonce');
        self::verify_permissions();

        $template_id = isset($_POST['template_id']) ? $_POST['template_id'] : '';
        $apply_type = isset($_POST['apply_type']) ? $_POST['apply_type'] : 'vehicles';
        $vehicle_ids = isset($_POST['vehicle_ids']) ? $_POST['vehicle_ids'] : array();
        $dates = isset($_POST['dates']) ? $_POST['dates'] : array();

        if ($apply_type === 'global') {
            // Apply as global rule
            $result = CKL_Pricing_Rule_Templates::apply_template_as_global_rule($template_id, $dates);

            if (is_wp_error($result)) {
                wp_send_json_error(array('message' => $result->get_error_message()));
            }

            wp_send_json_success(array(
                'message' => __('Template applied as global rule', 'ckl-car-rental'),
                'rule_id' => $result,
            ));
        } else {
            // Apply to vehicles
            $result = CKL_Pricing_Rule_Templates::apply_template_to_vehicles($template_id, $vehicle_ids, $dates);

            if (is_wp_error($result)) {
                wp_send_json_error(array('message' => $result->get_error_message()));
            }

            wp_send_json_success(array(
                'message' => sprintf(__('Template applied to %d vehicles', 'ckl-car-rental'), $result['success_count']),
                'stats' => $result,
            ));
        }
    }

    /**
     * Delete template
     */
    public static function delete_template() {
        check_ajax_referer('ckl-pricing-rules-nonce', 'nonce');
        self::verify_permissions();

        $template_id = isset($_POST['template_id']) ? $_POST['template_id'] : '';

        $result = CKL_Pricing_Rule_Templates::delete_template($template_id);

        if (is_wp_error($result)) {
            wp_send_json_error(array('message' => $result->get_error_message()));
        }

        wp_send_json_success(array(
            'message' => __('Template deleted', 'ckl-car-rental'),
        ));
    }

    /**
     * Export templates
     */
    public static function export_templates() {
        check_ajax_referer('ckl-pricing-rules-nonce', 'nonce');
        self::verify_permissions();

        $template_ids = isset($_POST['template_ids']) ? $_POST['template_ids'] : array();

        $json = CKL_Pricing_Rule_Templates::export_templates($template_ids);

        wp_send_json_success(array(
            'json' => $json,
            'filename' => 'ckl-pricing-templates-' . date('Y-m-d') . '.json',
        ));
    }

    /**
     * Import templates
     */
    public static function import_templates() {
        check_ajax_referer('ckl-pricing-rules-nonce', 'nonce');
        self::verify_permissions();

        $json = isset($_POST['json']) ? $_POST['json'] : '';

        $result = CKL_Pricing_Rule_Templates::import_templates($json);

        if (is_wp_error($result)) {
            wp_send_json_error(array('message' => $result->get_error_message()));
        }

        wp_send_json_success(array(
            'message' => sprintf(__('%d templates imported', 'ckl-car-rental'), $result['imported_count']),
            'stats' => $result,
        ));
    }

    // ====================
    // BULK ACTIONS HANDLERS
    // ====================

    /**
     * Bulk apply rule to vehicles
     */
    public static function bulk_apply_rule() {
        check_ajax_referer('ckl-pricing-rules-nonce', 'nonce');
        self::verify_permissions();

        $vehicle_ids = isset($_POST['vehicle_ids']) ? $_POST['vehicle_ids'] : array();
        $rule = isset($_POST['rule']) ? $_POST['rule'] : array();

        if (empty($vehicle_ids) || empty($rule['name']) || empty($rule['start_date']) || empty($rule['end_date'])) {
            wp_send_json_error(array('message' => __('Missing required data', 'ckl-car-rental')));
        }

        $new_rule = array(
            'name' => sanitize_text_field($rule['name']),
            'start_date' => sanitize_text_field($rule['start_date']),
            'end_date' => sanitize_text_field($rule['end_date']),
            'type' => sanitize_text_field($rule['type']),
            'amount' => floatval($rule['amount']),
            'recurring' => sanitize_text_field($rule['recurring']),
            'priority' => 50,
            'active' => 'yes',
        );

        $count = 0;
        foreach ($vehicle_ids as $vehicle_id) {
            $rules = get_post_meta($vehicle_id, '_vehicle_pricing_rules', true);
            if (!is_array($rules)) {
                $rules = array();
            }

            $new_rule['id'] = uniqid();
            $rules[] = $new_rule;

            // Sort by priority
            usort($rules, function($a, $b) {
                return ($b['priority'] ?? 0) - ($a['priority'] ?? 0);
            });

            update_post_meta($vehicle_id, '_vehicle_pricing_rules', $rules);
            $count++;
        }

        wp_send_json_success(array(
            'message' => sprintf(__('Rule applied to %d vehicles', 'ckl-car-rental'), $count),
        ));
    }

    /**
     * Clone vehicle rule to another vehicle
     */
    public static function clone_vehicle_rule() {
        check_ajax_referer('ckl-pricing-rules-nonce', 'nonce');
        self::verify_permissions();

        $source_vehicle_id = isset($_POST['source_vehicle_id']) ? intval($_POST['source_vehicle_id']) : 0;
        $target_vehicle_ids = isset($_POST['target_vehicle_ids']) ? $_POST['target_vehicle_ids'] : array();
        $rule_id = isset($_POST['rule_id']) ? $_POST['rule_id'] : '';

        if (!$source_vehicle_id || empty($target_vehicle_ids) || empty($rule_id)) {
            wp_send_json_error(array('message' => __('Missing required data', 'ckl-car-rental')));
        }

        // Get source rule
        $source_rules = get_post_meta($source_vehicle_id, '_vehicle_pricing_rules', true);
        if (!is_array($source_rules)) {
            wp_send_json_error(array('message' => __('Source vehicle has no rules', 'ckl-car-rental')));
        }

        $rule_to_clone = null;
        foreach ($source_rules as $rule) {
            if ($rule['id'] === $rule_id) {
                $rule_to_clone = $rule;
                break;
            }
        }

        if (!$rule_to_clone) {
            wp_send_json_error(array('message' => __('Rule not found', 'ckl-car-rental')));
        }

        // Clone to target vehicles
        $count = 0;
        foreach ($target_vehicle_ids as $target_vehicle_id) {
            $target_rules = get_post_meta($target_vehicle_id, '_vehicle_pricing_rules', true);
            if (!is_array($target_rules)) {
                $target_rules = array();
            }

            $new_rule = $rule_to_clone;
            $new_rule['id'] = uniqid('cloned_', true);

            $target_rules[] = $new_rule;

            // Sort by priority
            usort($target_rules, function($a, $b) {
                return ($b['priority'] ?? 0) - ($a['priority'] ?? 0);
            });

            update_post_meta($target_vehicle_id, '_vehicle_pricing_rules', $target_rules);
            $count++;
        }

        wp_send_json_success(array(
            'message' => sprintf(__('Rule cloned to %d vehicles', 'ckl-car-rental'), $count),
        ));
    }

    /**
     * Preview bulk apply - show affected vehicles
     */
    public static function preview_bulk_apply() {
        check_ajax_referer('ckl-pricing-rules-nonce', 'nonce');
        self::verify_permissions();

        $vehicle_ids = isset($_POST['vehicle_ids']) ? $_POST['vehicle_ids'] : array();

        if (empty($vehicle_ids)) {
            wp_send_json_error(array('message' => __('No vehicles selected', 'ckl-car-rental')));
        }

        $vehicles = array();
        foreach ($vehicle_ids as $vehicle_id) {
            $vehicle = get_post($vehicle_id);
            if ($vehicle && $vehicle->post_type === 'vehicle') {
                $vehicles[] = array(
                    'id' => $vehicle->ID,
                    'title' => $vehicle->post_title,
                    'type' => get_post_meta($vehicle_id, '_vehicle_type', true),
                    'price' => get_post_meta($vehicle_id, '_vehicle_price_per_day', true),
                );
            }
        }

        wp_send_json_success(array(
            'vehicles' => $vehicles,
        ));
    }
}
