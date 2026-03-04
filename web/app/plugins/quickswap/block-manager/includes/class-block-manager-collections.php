<?php
/**
 * Block Manager Collections Class
 *
 * @package QuickSwap\Block_Manager
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Collections management class
 */
class QuickSwap_Block_Manager_Collections {

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
        // Collections initialization
    }

    /**
     * Initialize the class
     */
    public static function init() {
        $self = self::get_instance();
    }

    /**
     * Get default collections
     *
     * @return array Default collections
     */
    public static function get_default_collections() {
        return array(
            array(
                'id' => 'common-blocks',
                'name' => __('Common Blocks', 'quickswap'),
                'description' => __('Blocks that are commonly used', 'quickswap'),
                'icon' => 'star-filled',
                'blocks' => array(
                    'core/paragraph',
                    'core/heading',
                    'core/list',
                    'core/image',
                    'core/quote',
                ),
                'created_at' => current_time('mysql'),
                'updated_at' => current_time('mysql'),
            ),
            array(
                'id' => 'formatting',
                'name' => __('Formatting', 'quickswap'),
                'description' => __('Text formatting blocks', 'quickswap'),
                'icon' => 'editor-bold',
                'blocks' => array(
                    'core/paragraph',
                    'core/heading',
                    'core/list',
                    'core/quote',
                    'core/preformatted',
                    'core/pullquote',
                    'core/verse',
                    'core/table',
                    'core/code',
                ),
                'created_at' => current_time('mysql'),
                'updated_at' => current_time('mysql'),
            ),
            array(
                'id' => 'media',
                'name' => __('Media Elements', 'quickswap'),
                'description' => __('Images, videos, and other media', 'quickswap'),
                'icon' => 'format-image',
                'blocks' => array(
                    'core/image',
                    'core/gallery',
                    'core/audio',
                    'core/cover',
                    'core/video',
                    'core/file',
                ),
                'created_at' => current_time('mysql'),
                'updated_at' => current_time('mysql'),
            ),
        );
    }

    /**
     * Get collections for a user
     *
     * @param int|null $user_id User ID. Defaults to current user.
     * @return array List of collections
     */
    public static function get_collections($user_id = null) {
        if (!$user_id) {
            $user_id = get_current_user_id();
        }

        $collections = get_user_meta($user_id, 'quickswap_block_collections', true);

        if (empty($collections)) {
            $collections = self::get_default_collections();
            update_user_meta($user_id, 'quickswap_block_collections', $collections);
        }

        return $collections;
    }

    /**
     * Get single collection
     *
     * @param string $collection_id Collection ID
     * @param int|null $user_id User ID. Defaults to current user.
     * @return array|false Collection data or false if not found
     */
    public static function get_collection($collection_id, $user_id = null) {
        $collections = self::get_collections($user_id);

        foreach ($collections as $collection) {
            if ($collection['id'] === $collection_id) {
                return $collection;
            }
        }

        return false;
    }

    /**
     * Create a new collection
     *
     * @param string $name Collection name
     * @param string $description Collection description
     * @param array $blocks Array of block names
     * @param string $icon Icon identifier
     * @return array Created collection
     */
    public static function create_collection($name, $description = '', $blocks = array(), $icon = 'star-filled') {
        $user_id = get_current_user_id();

        $collection = array(
            'id' => uniqid('collection-'),
            'name' => sanitize_text_field($name),
            'description' => sanitize_textarea_field($description),
            'icon' => sanitize_text_field($icon),
            'blocks' => array_map('sanitize_text_field', $blocks),
            'created_at' => current_time('mysql'),
            'updated_at' => current_time('mysql'),
        );

        $collections = self::get_collections($user_id);
        $collections[] = $collection;

        update_user_meta($user_id, 'quickswap_block_collections', $collections);

        return $collection;
    }

    /**
     * Update a collection
     *
     * @param string $collection_id Collection ID
     * @param string|null $name Collection name
     * @param string|null $description Collection description
     * @param array|null $blocks Array of block names
     * @param string|null $icon Icon identifier
     * @return array|WP_Error Updated collection or error
     */
    public static function update_collection($collection_id, $name = null, $description = null, $blocks = null, $icon = null) {
        $user_id = get_current_user_id();
        $collections = self::get_collections($user_id);

        $found = false;

        foreach ($collections as &$collection) {
            if ($collection['id'] === $collection_id) {
                $found = true;

                if ($name !== null) {
                    $collection['name'] = sanitize_text_field($name);
                }
                if ($description !== null) {
                    $collection['description'] = sanitize_textarea_field($description);
                }
                if ($blocks !== null) {
                    $collection['blocks'] = array_map('sanitize_text_field', $blocks);
                }
                if ($icon !== null) {
                    $collection['icon'] = sanitize_text_field($icon);
                }
                $collection['updated_at'] = current_time('mysql');

                break;
            }
        }

        if (!$found) {
            return new WP_Error('collection_not_found', __('Collection not found', 'quickswap'));
        }

        update_user_meta($user_id, 'quickswap_block_collections', $collections);

        return self::get_collection($collection_id, $user_id);
    }

    /**
     * Delete a collection
     *
     * @param string $collection_id Collection ID
     * @return bool|WP_Error True on success, error on failure
     */
    public static function delete_collection($collection_id) {
        $user_id = get_current_user_id();
        $collections = self::get_collections($user_id);

        $found = false;
        $filtered = array();

        foreach ($collections as $collection) {
            if ($collection['id'] === $collection_id) {
                $found = true;
            } else {
                $filtered[] = $collection;
            }
        }

        if (!$found) {
            return new WP_Error('collection_not_found', __('Collection not found', 'quickswap'));
        }

        update_user_meta($user_id, 'quickswap_block_collections', $filtered);

        return true;
    }

    /**
     * Get favorite blocks
     *
     * @param int|null $user_id User ID. Defaults to current user.
     * @return array Array of favorite block names
     */
    public static function get_favorites($user_id = null) {
        if (!$user_id) {
            $user_id = get_current_user_id();
        }

        $favorites = get_user_meta($user_id, 'quickswap_favorite_blocks', true);

        return $favorites ? $favorites : array();
    }

    /**
     * Toggle favorite status for a block
     *
     * @param string $block_name Block name
     * @param int|null $user_id User ID. Defaults to current user.
     * @return array Updated favorites array
     */
    public static function toggle_favorite($block_name, $user_id = null) {
        if (!$user_id) {
            $user_id = get_current_user_id();
        }

        $favorites = self::get_favorites($user_id);

        if (in_array($block_name, $favorites)) {
            $favorites = array_diff($favorites, array($block_name));
        } else {
            $favorites[] = $block_name;
        }

        update_user_meta($user_id, 'quickswap_favorite_blocks', array_values($favorites));

        return array_values($favorites);
    }

    /**
     * Get recent blocks
     *
     * @param int|null $user_id User ID. Defaults to current user.
     * @return array Array of recent blocks with timestamps
     */
    public static function get_recent_blocks($user_id = null) {
        if (!$user_id) {
            $user_id = get_current_user_id();
        }

        $recent = get_user_meta($user_id, 'quickswap_recent_blocks', true);

        return $recent ? $recent : array();
    }

    /**
     * Track block usage
     *
     * @param string $block_name Block name
     * @param int|null $user_id User ID. Defaults to current user.
     * @return bool True on success
     */
    public static function track_block_usage($block_name, $user_id = null) {
        if (!$user_id) {
            $user_id = get_current_user_id();
        }

        $recent = self::get_recent_blocks($user_id);

        // Update timestamp
        $recent[$block_name] = current_time('mysql');

        // Keep only last 50
        if (count($recent) > 50) {
            uasort($recent, function($a, $b) {
                return strtotime($b) - strtotime($a);
            });
            $recent = array_slice($recent, 0, 50, true);
        }

        update_user_meta($user_id, 'quickswap_recent_blocks', $recent);

        return true;
    }

    /**
     * Import collections from JSON
     *
     * @param string $json JSON data
     * @return array|WP_Error Imported collections or error
     */
    public static function import_collections($json) {
        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return new WP_Error('invalid_json', __('Invalid JSON data', 'quickswap'));
        }

        if (!isset($data['collections']) || !is_array($data['collections'])) {
            return new WP_Error('invalid_format', __('Invalid collections format', 'quickswap'));
        }

        $user_id = get_current_user_id();
        $imported = array();

        foreach ($data['collections'] as $collection) {
            $new_collection = self::create_collection(
                isset($collection['name']) ? $collection['name'] : __('Imported Collection', 'quickswap'),
                isset($collection['description']) ? $collection['description'] : '',
                isset($collection['blocks']) ? $collection['blocks'] : array(),
                isset($collection['icon']) ? $collection['icon'] : 'star-filled'
            );
            $imported[] = $new_collection;
        }

        return $imported;
    }

    /**
     * Export collections to JSON
     *
     * @return string JSON data
     */
    public static function export_collections() {
        $collections = self::get_collections();

        $data = array(
            'version' => '1.0',
            'exported_at' => current_time('mysql'),
            'collections' => $collections,
        );

        return wp_json_encode($data);
    }
}
