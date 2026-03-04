<?php
/**
 * Block Manager REST API Class
 *
 * @package QuickSwap\Block_Manager
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * REST API endpoints class
 */
class QuickSwap_Block_Manager_REST {

    /**
     * Single instance of the class
     */
    private static $instance = null;

    /**
     * REST API namespace
     */
    const NAMESPACE = 'quickswap-block-manager/v1';

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
        // REST initialization
    }

    /**
     * Initialize the class
     */
    public static function init() {
        $self = self::get_instance();
        add_action('rest_api_init', array($self, 'register_routes'));
    }

    /**
     * Register REST routes
     */
    public function register_routes() {
        // Collections endpoints
        register_rest_route(self::NAMESPACE, '/collections', array(
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_collections'),
                'permission_callback' => array($this, 'check_permission'),
            ),
            array(
                'methods' => 'POST',
                'callback' => array($this, 'create_collection'),
                'permission_callback' => array($this, 'check_permission'),
            ),
        ));

        register_rest_route(self::NAMESPACE, '/collections/(?P<id>[a-zA-Z0-9-]+)', array(
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_collection'),
                'permission_callback' => array($this, 'check_permission'),
            ),
            array(
                'methods' => 'PUT',
                'callback' => array($this, 'update_collection'),
                'permission_callback' => array($this, 'check_permission'),
            ),
            array(
                'methods' => 'DELETE',
                'callback' => array($this, 'delete_collection'),
                'permission_callback' => array($this, 'check_permission'),
            ),
        ));

        // Preferences endpoints
        register_rest_route(self::NAMESPACE, '/preferences', array(
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_preferences'),
                'permission_callback' => array($this, 'check_permission'),
            ),
            array(
                'methods' => 'POST',
                'callback' => array($this, 'update_preferences'),
                'permission_callback' => array($this, 'check_permission'),
            ),
        ));

        // Blocks endpoint
        register_rest_route(self::NAMESPACE, '/blocks', array(
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_blocks'),
                'permission_callback' => array($this, 'check_permission'),
            ),
        ));

        // Favorites endpoints
        register_rest_route(self::NAMESPACE, '/favorites', array(
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_favorites'),
                'permission_callback' => array($this, 'check_permission'),
            ),
            array(
                'methods' => 'POST',
                'callback' => array($this, 'toggle_favorite'),
                'permission_callback' => array($this, 'check_permission'),
            ),
        ));

        // Recent blocks endpoint
        register_rest_route(self::NAMESPACE, '/recent', array(
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_recent_blocks'),
                'permission_callback' => array($this, 'check_permission'),
            ),
        ));

        // Track block usage
        register_rest_route(self::NAMESPACE, '/track-usage', array(
            array(
                'methods' => 'POST',
                'callback' => array($this, 'track_block_usage'),
                'permission_callback' => array($this, 'check_permission'),
            ),
        ));
    }

    /**
     * Check API permission
     */
    public function check_permission() {
        return current_user_can('edit_posts');
    }

    /**
     * Get collections
     */
    public function get_collections($request) {
        $user_id = get_current_user_id();
        $collections = QuickSwap_Block_Manager_Collections::get_collections($user_id);
        return rest_ensure_response($collections);
    }

    /**
     * Get single collection
     */
    public function get_collection($request) {
        $id = $request->get_param('id');
        $user_id = get_current_user_id();
        $collections = QuickSwap_Block_Manager_Collections::get_collections($user_id);

        foreach ($collections as $collection) {
            if ($collection['id'] === $id) {
                return rest_ensure_response($collection);
            }
        }

        return new WP_Error('collection_not_found', __('Collection not found', 'quickswap'), array('status' => 404));
    }

    /**
     * Create collection
     */
    public function create_collection($request) {
        $params = $request->get_json_params();

        if (empty($params['name'])) {
            return new WP_Error('missing_name', __('Collection name is required', 'quickswap'), array('status' => 400));
        }

        $collection = QuickSwap_Block_Manager_Collections::create_collection(
            $params['name'],
            isset($params['description']) ? $params['description'] : '',
            isset($params['blocks']) ? $params['blocks'] : array(),
            isset($params['icon']) ? $params['icon'] : 'star-filled'
        );

        return rest_ensure_response($collection);
    }

    /**
     * Update collection
     */
    public function update_collection($request) {
        $id = $request->get_param('id');
        $params = $request->get_json_params();

        $collection = QuickSwap_Block_Manager_Collections::update_collection(
            $id,
            isset($params['name']) ? $params['name'] : null,
            isset($params['description']) ? $params['description'] : null,
            isset($params['blocks']) ? $params['blocks'] : null,
            isset($params['icon']) ? $params['icon'] : null
        );

        if (is_wp_error($collection)) {
            return $collection;
        }

        return rest_ensure_response($collection);
    }

    /**
     * Delete collection
     */
    public function delete_collection($request) {
        $id = $request->get_param('id');
        $result = QuickSwap_Block_Manager_Collections::delete_collection($id);

        if (is_wp_error($result)) {
            return $result;
        }

        return rest_ensure_response(array('success' => true));
    }

    /**
     * Get preferences
     */
    public function get_preferences($request) {
        $user_id = get_current_user_id();
        $preferences = get_user_meta($user_id, 'quickswap_block_manager_settings', true);

        if (empty($preferences)) {
            $preferences = array(
                'keyboard_shortcut' => 'mod+shift+b',
                'show_favorites' => true,
                'show_recent' => true,
                'panel_position' => 'top-right',
            );
        }

        return rest_ensure_response($preferences);
    }

    /**
     * Update preferences
     */
    public function update_preferences($request) {
        $params = $request->get_json_params();
        $user_id = get_current_user_id();

        $current = get_user_meta($user_id, 'quickswap_block_manager_settings', true);
        if (empty($current)) {
            $current = array();
        }

        $updated = array_merge($current, $params);
        update_user_meta($user_id, 'quickswap_block_manager_settings', $updated);

        return rest_ensure_response($updated);
    }

    /**
     * Get blocks
     */
    public function get_blocks($request) {
        $blocks = QuickSwap_Block_Manager_Core::get_registered_blocks();

        // Add favorite and recent info
        $user_id = get_current_user_id();
        $favorites = get_user_meta($user_id, 'quickswap_favorite_blocks', true);
        $recent = get_user_meta($user_id, 'quickswap_recent_blocks', true);

        foreach ($blocks as &$block) {
            $block['isFavorite'] = in_array($block['name'], (array) $favorites);
            $block['lastUsed'] = isset($recent[$block['name']]) ? $recent[$block['name']] : null;
        }

        return rest_ensure_response($blocks);
    }

    /**
     * Get favorites
     */
    public function get_favorites($request) {
        $user_id = get_current_user_id();
        $favorites = QuickSwap_Block_Manager_Collections::get_favorites($user_id);

        $blocks = QuickSwap_Block_Manager_Core::get_registered_blocks();
        $favorite_blocks = array();

        foreach ($blocks as $block) {
            if (in_array($block['name'], $favorites)) {
                $block['isFavorite'] = true;
                $favorite_blocks[] = $block;
            }
        }

        return rest_ensure_response($favorite_blocks);
    }

    /**
     * Toggle favorite
     */
    public function toggle_favorite($request) {
        $params = $request->get_json_params();

        if (empty($params['block'])) {
            return new WP_Error('missing_block', __('Block name is required', 'quickswap'), array('status' => 400));
        }

        $user_id = get_current_user_id();
        $favorites = QuickSwap_Block_Manager_Collections::toggle_favorite($params['block'], $user_id);

        return rest_ensure_response(array('favorites' => $favorites));
    }

    /**
     * Get recent blocks
     */
    public function get_recent_blocks($request) {
        $user_id = get_current_user_id();
        $recent = QuickSwap_Block_Manager_Collections::get_recent_blocks($user_id);

        $blocks = QuickSwap_Block_Manager_Core::get_registered_blocks();
        $recent_blocks = array();

        // Sort by last used
        uasort($recent, function($a, $b) {
            return strtotime($b) - strtotime($a);
        });

        // Get top 50
        $recent = array_slice($recent, 0, 50, true);

        foreach ($blocks as $block) {
            if (isset($recent[$block['name']])) {
                $block['lastUsed'] = $recent[$block['name']];
                $recent_blocks[] = $block;
            }
        }

        return rest_ensure_response($recent_blocks);
    }

    /**
     * Track block usage
     */
    public function track_block_usage($request) {
        $params = $request->get_json_params();

        if (empty($params['block'])) {
            return new WP_Error('missing_block', __('Block name is required', 'quickswap'), array('status' => 400));
        }

        $user_id = get_current_user_id();
        QuickSwap_Block_Manager_Collections::track_block_usage($params['block'], $user_id);

        return rest_ensure_response(array('success' => true));
    }
}
