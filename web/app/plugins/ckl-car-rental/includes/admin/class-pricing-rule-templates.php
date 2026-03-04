<?php
/**
 * CKL Pricing Rule Templates
 *
 * Manages pricing rule templates for quick application
 * to vehicles and global rules
 */

if (!defined('ABSPATH')) {
    exit;
}

class CKL_Pricing_Rule_Templates {

    /**
     * Option name for storing templates
     */
    const OPTION_NAME = 'ckl_pricing_rule_templates';

    /**
     * Default templates
     */
    private static $default_templates = array(
        'hari_raya' => array(
            'id' => 'hari_raya',
            'name' => 'Hari Raya',
            'adjustment_type' => 'percentage',
            'amount' => 50,
            'recurring' => 'yearly',
            'description' => 'Common Hari Raya peak period with 50% surcharge',
        ),
        'school_holidays' => array(
            'id' => 'school_holidays',
            'name' => 'School Holidays',
            'adjustment_type' => 'percentage',
            'amount' => 25,
            'recurring' => 'yearly',
            'description' => 'School holiday period with 25% surcharge',
        ),
        'weekend_surcharge' => array(
            'id' => 'weekend_surcharge',
            'name' => 'Weekend Surcharge',
            'adjustment_type' => 'percentage',
            'amount' => 15,
            'recurring' => 'weekly',
            'description' => 'Weekend pricing with 15% surcharge',
        ),
        'christmas_newyear' => array(
            'id' => 'christmas_newyear',
            'name' => 'Christmas & New Year',
            'adjustment_type' => 'percentage',
            'amount' => 40,
            'recurring' => 'yearly',
            'description' => 'Christmas to New Year period with 40% surcharge',
        ),
        'chinese_new_year' => array(
            'id' => 'chinese_new_year',
            'name' => 'Chinese New Year',
            'adjustment_type' => 'percentage',
            'amount' => 50,
            'recurring' => 'yearly',
            'description' => 'Chinese New Year period with 50% surcharge',
        ),
    );

    /**
     * Initialize the class
     */
    public static function init() {
        // Initialization happens via get_all_templates()
    }

    /**
     * Get all templates (default + custom)
     *
     * @return array
     */
    public static function get_all_templates() {
        $custom_templates = get_option(self::OPTION_NAME, array());

        // Merge default and custom templates
        $all_templates = array_merge(self::$default_templates, $custom_templates);

        return $all_templates;
    }

    /**
     * Get template by ID
     *
     * @param string $id
     * @return array|null
     */
    public static function get_template($id) {
        $templates = self::get_all_templates();

        foreach ($templates as $template) {
            if ($template['id'] === $id) {
                return $template;
            }
        }

        return null;
    }

    /**
     * Get default templates only
     *
     * @return array
     */
    public static function get_default_templates() {
        return self::$default_templates;
    }

    /**
     * Get custom templates only
     *
     * @return array
     */
    public static function get_custom_templates() {
        return get_option(self::OPTION_NAME, array());
    }

    /**
     * Save new template
     *
     * @param array $data
     * @return string|WP_Error The template ID or error
     */
    public static function save_template($data) {
        $custom_templates = self::get_custom_templates();

        // Validate required fields
        if (empty($data['name'])) {
            return new WP_Error('missing_name', __('Template name is required', 'ckl-car-rental'));
        }

        // Generate ID if not provided
        if (empty($data['id'])) {
            $data['id'] = uniqid('template_', true);
        }

        // Create new template
        $new_template = array(
            'id' => sanitize_key($data['id']),
            'name' => sanitize_text_field($data['name']),
            'adjustment_type' => sanitize_text_field($data['adjustment_type'] ?? 'percentage'),
            'amount' => floatval($data['amount'] ?? 0),
            'recurring' => sanitize_text_field($data['recurring'] ?? 'none'),
            'description' => sanitize_textarea_field($data['description'] ?? ''),
        );

        // Check if template with this ID already exists
        $existing_index = -1;
        foreach ($custom_templates as $i => $template) {
            if ($template['id'] === $new_template['id']) {
                $existing_index = $i;
                break;
            }
        }

        if ($existing_index >= 0) {
            // Update existing
            $custom_templates[$existing_index] = $new_template;
        } else {
            // Add new
            $custom_templates[] = $new_template;
        }

        update_option(self::OPTION_NAME, $custom_templates);

        return $new_template['id'];
    }

    /**
     * Delete template
     *
     * @param string $id
     * @return bool|WP_Error
     */
    public static function delete_template($id) {
        $custom_templates = self::get_custom_templates();

        // Cannot delete default templates
        if (isset(self::$default_templates[$id])) {
            return new WP_Error('default_template', __('Cannot delete default templates', 'ckl-car-rental'));
        }

        $custom_templates = array_filter($custom_templates, function($template) use ($id) {
            return $template['id'] !== $id;
        });

        update_option(self::OPTION_NAME, array_values($custom_templates));

        return true;
    }

    /**
     * Apply template to vehicles
     *
     * @param string $template_id
     * @param array $vehicle_ids
     * @param array $dates Optional date range
     * @return array|WP_Error Result with stats or error
     */
    public static function apply_template_to_vehicles($template_id, $vehicle_ids, $dates = array()) {
        $template = self::get_template($template_id);

        if (!$template) {
            return new WP_Error('template_not_found', __('Template not found', 'ckl-car-rental'));
        }

        if (empty($vehicle_ids)) {
            return new WP_Error('no_vehicles', __('No vehicles specified', 'ckl-car-rental'));
        }

        $success_count = 0;
        $failed_count = 0;

        foreach ($vehicle_ids as $vehicle_id) {
            $vehicle = get_post($vehicle_id);

            if (!$vehicle || $vehicle->post_type !== 'vehicle') {
                $failed_count++;
                continue;
            }

            // Get existing rules
            $rules = get_post_meta($vehicle_id, '_vehicle_pricing_rules', true);
            if (!is_array($rules)) {
                $rules = array();
            }

            // Create new rule from template
            $new_rule = array(
                'id' => uniqid('rule_', true),
                'name' => $template['name'],
                'start_date' => isset($dates['start_date']) ? sanitize_text_field($dates['start_date']) : date('Y-m-d'),
                'end_date' => isset($dates['end_date']) ? sanitize_text_field($dates['end_date']) : date('Y-m-d', strtotime('+7 days')),
                'type' => $template['adjustment_type'],
                'amount' => $template['amount'],
                'recurring' => $template['recurring'],
                'priority' => 50,
                'active' => 'yes',
            );

            $rules[] = $new_rule;

            // Sort by priority
            usort($rules, function($a, $b) {
                return ($b['priority'] ?? 0) - ($a['priority'] ?? 0);
            });

            update_post_meta($vehicle_id, '_vehicle_pricing_rules', $rules);
            $success_count++;
        }

        return array(
            'success_count' => $success_count,
            'failed_count' => $failed_count,
            'template_name' => $template['name'],
        );
    }

    /**
     * Apply template as global rule
     *
     * @param string $template_id
     * @param array $dates Date range (start_date, end_date)
     * @return string|WP_Error The new rule ID or error
     */
    public static function apply_template_as_global_rule($template_id, $dates) {
        $template = self::get_template($template_id);

        if (!$template) {
            return new WP_Error('template_not_found', __('Template not found', 'ckl-car-rental'));
        }

        if (empty($dates['start_date']) || empty($dates['end_date'])) {
            return new WP_Error('missing_dates', __('Start and end dates are required', 'ckl-car-rental'));
        }

        $global_rules = get_option('ckl_global_pricing_rules', array());

        $new_rule = array(
            'id' => uniqid('global_', true),
            'name' => $template['name'],
            'start_date' => sanitize_text_field($dates['start_date']),
            'end_date' => sanitize_text_field($dates['end_date']),
            'adjustment_type' => $template['adjustment_type'],
            'amount' => $template['amount'],
            'recurring' => $template['recurring'],
            'priority' => 90,
            'active' => true,
        );

        $global_rules[] = $new_rule;

        // Sort by priority
        usort($global_rules, function($a, $b) {
            return $b['priority'] - $a['priority'];
        });

        update_option('ckl_global_pricing_rules', $global_rules);

        return $new_rule['id'];
    }

    /**
     * Export templates to JSON
     *
     * @param array $template_ids Optional array of template IDs to export
     * @return string JSON string
     */
    public static function export_templates($template_ids = array()) {
        $templates = self::get_all_templates();

        if (!empty($template_ids)) {
            $templates = array_filter($templates, function($template) use ($template_ids) {
                return in_array($template['id'], $template_ids);
            });
            $templates = array_values($templates);
        }

        $export_data = array(
            'version' => '1.0',
            'exported_at' => current_time('mysql'),
            'templates' => $templates,
        );

        return wp_json_encode($export_data, JSON_PRETTY_PRINT);
    }

    /**
     * Import templates from JSON
     *
     * @param string $json JSON string
     * @return array|WP_Error Result with stats or error
     */
    public static function import_templates($json) {
        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return new WP_Error('invalid_json', __('Invalid JSON format', 'ckl-car-rental'));
        }

        if (!isset($data['templates']) || !is_array($data['templates'])) {
            return new WP_Error('invalid_format', __('Invalid template data format', 'ckl-car-rental'));
        }

        $imported_count = 0;
        $skipped_count = 0;
        $custom_templates = self::get_custom_templates();

        foreach ($data['templates'] as $template) {
            // Skip default templates (they can't be overwritten)
            if (isset(self::$default_templates[$template['id'] ?? ''])) {
                $skipped_count++;
                continue;
            }

            // Validate required fields
            if (empty($template['name'])) {
                $skipped_count++;
                continue;
            }

            // Generate new ID if not provided or conflicts with default
            if (empty($template['id']) || isset(self::$default_templates[$template['id']])) {
                $template['id'] = uniqid('imported_', true);
            }

            // Sanitize
            $sanitized_template = array(
                'id' => sanitize_key($template['id']),
                'name' => sanitize_text_field($template['name']),
                'adjustment_type' => sanitize_text_field($template['adjustment_type'] ?? 'percentage'),
                'amount' => floatval($template['amount'] ?? 0),
                'recurring' => sanitize_text_field($template['recurring'] ?? 'none'),
                'description' => sanitize_textarea_field($template['description'] ?? ''),
            );

            // Check if template with this ID exists in custom templates
            $existing_index = -1;
            foreach ($custom_templates as $i => $existing) {
                if ($existing['id'] === $sanitized_template['id']) {
                    $existing_index = $i;
                    break;
                }
            }

            if ($existing_index >= 0) {
                // Update existing
                $custom_templates[$existing_index] = $sanitized_template;
            } else {
                // Add new
                $custom_templates[] = $sanitized_template;
            }

            $imported_count++;
        }

        update_option(self::OPTION_NAME, $custom_templates);

        return array(
            'imported_count' => $imported_count,
            'skipped_count' => $skipped_count,
        );
    }

    /**
     * Create a template from existing rule
     *
     * @param array $rule
     * @param string $template_name
     * @return string|WP_Error The template ID or error
     */
    public static function create_from_rule($rule, $template_name = '') {
        $template_data = array(
            'name' => !empty($template_name) ? $template_name : $rule['name'],
            'adjustment_type' => $rule['type'] ?? $rule['adjustment_type'] ?? 'percentage',
            'amount' => floatval($rule['amount'] ?? 0),
            'recurring' => $rule['recurring'] ?? 'none',
            'description' => sprintf(__('Created from rule: %s', 'ckl-car-rental'), $rule['name']),
        );

        return self::save_template($template_data);
    }
}
