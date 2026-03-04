<?php
/**
 * Shortcode Helper Functions
 *
 * Utility functions for shortcode attribute merging and output handling
 *
 * @package CKL_Car_Rental
 */

defined('ABSPATH') || exit;

/**
 * Merge shortcode attributes with theme settings and defaults
 *
 * Priority: shortcode atts > theme settings > defaults
 *
 * @param array $atts User-provided shortcode attributes
 * @param array $settings Theme settings array (option-based)
 * @param array $defaults Default values
 * @return array Merged attributes
 */
function ckl_merge_shortcode_attributes($atts, $settings = array(), $defaults = array()) {
    // First merge settings with defaults
    $merged = wp_parse_args($settings, $defaults);

    // Then merge shortcode atts (highest priority)
    $merged = wp_parse_args($atts, $merged);

    return $merged;
}

/**
 * Wrap shortcode output in a container for CSS scoping
 *
 * @param string $html The HTML content to wrap
 * @param string $section_class The section-specific class name
 * @param array $additional_classes Optional additional CSS classes
 * @return string Wrapped HTML
 */
function ckl_shortcode_wrapper($html, $section_class, $additional_classes = array()) {
    $classes = array_merge(array('ckl-shortcode-wrapper', $section_class), $additional_classes);
    $class_string = esc_attr(implode(' ', array_filter($classes)));

    return sprintf('<div class="%s">%s</div>', $class_string, $html);
}

/**
 * Parse a comma-separated list of categories into an array
 *
 * @param string $categories Comma-separated category slugs
 * @return array Array of category slugs
 */
function ckl_parse_categories_attribute($categories) {
    if (empty($categories)) {
        return array();
    }

    return array_map('trim', explode(',', $categories));
}

/**
 * Sanitize and validate column count attribute
 *
 * @param int|string $columns Column count
 * @param int $min Minimum columns
 * @param int $max Maximum columns
 * @param int $default Default columns
 * @return int Validated column count
 */
function ckl_sanitize_column_count($columns, $min = 1, $max = 6, $default = 3) {
    $columns = intval($columns);

    if ($columns < $min || $columns > $max) {
        return $default;
    }

    return $columns;
}

/**
 * Sanitize and validate count attribute
 *
 * @param int|string $count Item count
 * @param int $min Minimum count
 * @param int $max Maximum count
 * @param int $default Default count
 * @return int Validated count
 */
function ckl_sanitize_item_count($count, $min = 1, $max = 100, $default = 10) {
    $count = intval($count);

    if ($count < $min || $count > $max) {
        return $default;
    }

    return $count;
}

/**
 * Convert boolean string attribute to actual boolean
 *
 * @param string|bool $value Boolean value (various formats)
 * @return bool Actual boolean value
 */
function ckl_string_to_bool($value) {
    if (is_bool($value)) {
        return $value;
    }

    if (is_numeric($value)) {
        return (bool) intval($value);
    }

    if (is_string($value)) {
        return in_array(strtolower($value), array('yes', 'true', '1', 'on'), true);
    }

    return false;
}
