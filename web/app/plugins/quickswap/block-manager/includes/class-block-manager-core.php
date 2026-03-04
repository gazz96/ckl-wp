<?php
/**
 * Block Manager Core Class
 *
 * @package QuickSwap\Block_Manager
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Core functionality class
 */
class QuickSwap_Block_Manager_Core {

    /**
     * Single instance of the class
     */
    private static $instance = null;

    /**
     * Get instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        // Core initialization
    }

    /**
     * Initialize the class
     */
    public static function init() {
        $self = self::get_instance();
    }

    /**
     * Get all registered blocks
     *
     * @return array List of registered blocks
     */
    public static function get_registered_blocks() {
        $block_registry = WP_Block_Type_Registry::get_instance();
        $blocks = $block_registry->get_all_registered();

        $block_list = array();

        foreach ($blocks as $block) {
            $block_list[] = array(
                'name' => $block->name,
                'title' => $block->title,
                'description' => $block->description,
                'icon' => $block->icon,
                'category' => $block->category,
                'keywords' => $block->keywords,
                'supports' => $block->supports,
            );
        }

        return $block_list;
    }

    /**
     * Get block categories
     *
     * @return array List of block categories
     */
    public static function get_block_categories() {
        $block_categories = array();

        // Default WordPress block categories
        $default_categories = array(
            'text' => array(
                'slug' => 'text',
                'title' => __('Text', 'quickswap'),
                'icon' => null,
            ),
            'media' => array(
                'slug' => 'media',
                'title' => __('Media', 'quickswap'),
                'icon' => null,
            ),
            'design' => array(
                'slug' => 'design',
                'title' => __('Design', 'quickswap'),
                'icon' => null,
            ),
            'widgets' => array(
                'slug' => 'widgets',
                'title' => __('Widgets', 'quickswap'),
                'icon' => null,
            ),
            'theme' => array(
                'slug' => 'theme',
                'title' => __('Theme', 'quickswap'),
                'icon' => null,
            ),
            'embed' => array(
                'slug' => 'embed',
                'title' => __('Embeds', 'quickswap'),
                'icon' => null,
            ),
            'reusable' => array(
                'slug' => 'reusable',
                'title' => __('Reusable Blocks', 'quickswap'),
                'icon' => null,
            ),
        );

        // Allow filtering categories
        $block_categories = apply_filters('quickswap_block_manager_categories', $default_categories);

        return $block_categories;
    }

    /**
     * Check if user can use block manager
     *
     * @return bool
     */
    public static function current_user_can_access() {
        return current_user_can('edit_posts');
    }
}
