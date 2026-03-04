<?php
/**
 * Abstract Search Provider
 *
 * Base class for all search providers
 *
 * @package QuickSwap
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

abstract class QuickSwap_Abstract_Provider {

    /**
     * Provider ID
     */
    protected $id = '';

    /**
     * Provider label
     */
    protected $label = '';

    /**
     * Required capability to access this provider
     */
    protected $capability = 'quickswap_use_search';

    /**
     * Priority for sorting results (higher = first)
     */
    protected $priority = 10;

    /**
     * Constructor
     */
    public function __construct() {
        $this->id = $this->get_id();
        $this->label = $this->get_label();
    }

    /**
     * Get provider ID
     */
    abstract public function get_id();

    /**
     * Get provider label
     */
    abstract public function get_label();

    /**
     * Get provider icon (emoji or SVG)
     */
    abstract public function get_icon();

    /**
     * Search for items
     *
     * @param string $query Search query
     * @param array $args Search arguments
     * @return array Search results with 'label', 'items', and optionally 'more_url'
     */
    abstract public function search($query, $args = array());

    /**
     * Check if user can access this provider
     *
     * @param int $user_id User ID
     * @return bool
     */
    public function can_access($user_id = null) {
        if ($user_id === null) {
            $user_id = get_current_user_id();
        }

        return user_can($user_id, $this->capability);
    }

    /**
     * Get item URL
     *
     * @param array $item Item data
     * @return string URL
     */
    abstract public function get_item_url($item);

    /**
     * Get item actions
     *
     * @param array $item Item data
     * @return array Array of actions (id, label, icon)
     */
    public function get_item_actions($item) {
        return array(
            array(
                'id' => 'open',
                'label' => __('Open', 'quickswap'),
                'icon' => '🔗',
            ),
        );
    }

    /**
     * Get provider priority
     */
    public function get_priority() {
        return $this->priority;
    }

    /**
     * Get provider capability
     */
    public function get_capability() {
        return $this->capability;
    }

    /**
     * Get search result item structure
     *
     * @param array $data Item data
     * @return array Formatted item
     */
    protected function format_item($data) {
        $defaults = array(
            'id' => '',
            'title' => '',
            'subtitle' => '',
            'url' => '',
            'type' => $this->id,
            'icon' => $this->get_icon(),
            'score' => 0,
            'meta' => array(),
            'actions' => array(),
        );

        $item = wp_parse_args($data, $defaults);

        // Generate URL if not provided
        if (empty($item['url']) && !empty($item['id'])) {
            $item['url'] = $this->get_item_url($item);
        }

        // Generate actions if not provided
        if (empty($item['actions'])) {
            $item['actions'] = $this->get_item_actions($item);
        }

        return $item;
    }

    /**
     * Calculate search score for an item
     *
     * @param string $haystack Text to search in
     * @param string $needle Search query
     * @param array $item Item data for additional scoring
     * @return float Score
     */
    protected function calculate_score($haystack, $needle, $item = array()) {
        $settings = get_option('quickswap_settings', array());
        $enable_fuzzy = !empty($settings['enable_fuzzy']);
        $fuzzy_threshold = intval($settings['fuzzy_threshold'] ?? 70);

        $haystack_lower = strtolower($haystack);
        $needle_lower = strtolower($needle);

        $score = 0;

        // Exact match (40 points)
        if ($haystack_lower === $needle_lower) {
            $score += 40;
        }
        // Starts with query (20 points)
        elseif (strpos($haystack_lower, $needle_lower) === 0) {
            $score += 20;
        }
        // Contains query (10 points)
        elseif (strpos($haystack_lower, $needle_lower) !== false) {
            $score += 10;
        }
        // Fuzzy match (0-30 points)
        elseif ($enable_fuzzy) {
            $similarity = QuickSwap_Fuzzy::similarity($needle_lower, $haystack_lower);
            if ($similarity >= $fuzzy_threshold) {
                $score += ($similarity / 100) * 30;
            }
        }

        // Recency bonus (0-10 points)
        if (isset($item['date'])) {
            $days_old = (time() - strtotime($item['date'])) / DAY_IN_SECONDS;
            if ($days_old < 7) {
                $score += max(0, 10 - ($days_old / 7 * 10));
            }
        }

        // Popularity bonus (comment count, view count, etc.)
        if (isset($item['comment_count']) && $item['comment_count'] > 0) {
            $score += min(5, $item['comment_count'] / 10);
        }

        return $score;
    }

    /**
     * Execute action on item
     *
     * @param string $action_id Action ID
     * @param array $item Item data
     * @return array|WP_Error Result or error
     */
    public function execute_action($action_id, $item) {
        return new WP_Error(
            'not_implemented',
            __('Action not implemented', 'quickswap')
        );
    }

    /**
     * Initialize provider
     */
    public static function init() {
        // To be implemented by child classes
    }
}
