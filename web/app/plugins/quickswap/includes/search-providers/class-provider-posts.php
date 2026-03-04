<?php
/**
 * Posts Search Provider
 *
 * Searches posts, pages, and custom post types
 *
 * @package QuickSwap
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

class QuickSwap_Provider_Posts extends QuickSwap_Abstract_Provider {

    /**
     * Get provider ID
     */
    public function get_id() {
        return 'posts';
    }

    /**
     * Get provider label
     */
    public function get_label() {
        return __('Posts', 'quickswap');
    }

    /**
     * Get provider icon
     */
    public function get_icon() {
        return '📄';
    }

    /**
     * Initialize provider
     */
    public static function init() {
        // Register this provider
        add_action('quickswap_register_providers', function() {
            QuickSwap_Search::register_provider('posts', new self());
        });
    }

    /**
     * Search for posts
     */
    public function search($query, $args = array()) {
        $limit = isset($args['limit']) ? intval($args['limit']) : 10;
        $enable_fuzzy = isset($args['enable_fuzzy']) ? (bool) $args['enable_fuzzy'] : true;

        // Parse query for operators
        $parsed = QuickSwap_Search::parse_query($query);
        $search_query = $parsed['query'];
        $operators = $parsed['operators'];

        // Build query args
        $query_args = array(
            'post_type' => array('post', 'page'),
            'post_status' => array('publish', 'draft', 'pending'),
            'posts_per_page' => $limit,
            'orderby' => 'relevance',
            's' => $search_query,
            'suppress_filters' => false,
        );

        // Apply operators with validation
        if (isset($operators['type']) && !empty($operators['type'])) {
            $query_args['post_type'] = $operators['type'];
        }
        if (isset($operators['status'])) {
            $query_args['post_status'] = $operators['status'];
        }
        if (isset($operators['author'])) {
            $user = get_user_by('login', $operators['author']);
            if ($user) {
                $query_args['author'] = $user->ID;
            }
        }

        // Get post types user can edit (with fallback)
        $post_types = get_post_types(array('show_ui' => true), 'names');
        $editable_types = array();

        foreach ($post_types as $post_type) {
            $post_type_object = get_post_type_object($post_type);
            if ($post_type_object && current_user_can($post_type_object->cap->edit_posts)) {
                $editable_types[] = $post_type;
            }
        }

        // Ensure post_type is never empty
        if (!empty($editable_types)) {
            $query_args['post_type'] = $editable_types;
        }

        // Final safety check - ensure we have valid post types
        if (empty($query_args['post_type'])) {
            $query_args['post_type'] = array('post');
        }

        try {
            // Perform search
            $posts_query = new WP_Query($query_args);
        } catch (Exception $e) {
            // Log error and return empty results
            error_log('QuickSwap search error: ' . $e->getMessage());
            return array(
                'label' => $this->get_label(),
                'items' => array(),
            );
        }
        $items = array();

        if ($posts_query->have_posts()) {
            while ($posts_query->have_posts()) {
                $posts_query->the_post();
                global $post;

                $item = $this->format_post_item($post, $search_query, $enable_fuzzy);
                if ($item !== null) {
                    $items[] = $item;
                }
            }
            wp_reset_postdata();
        }

        // Fuzzy search for more results if needed
        if ($enable_fuzzy && count($items) < $limit && !empty($search_query)) {
            $fuzzy_results = $this->fuzzy_search($search_query, $limit - count($items), $query_args);
            $items = array_merge($items, $fuzzy_results);
        }

        return array(
            'label' => $this->get_label(),
            'items' => $items,
            'more_url' => admin_url('edit.php? s=' . urlencode($search_query)),
        );
    }

    /**
     * Format post item
     */
    protected function format_post_item($post, $query, $enable_fuzzy) {
        $post_type_object = get_post_type_object($post->post_type);
        $post_type_label = $post_type_object ? $post_type_object->labels->singular_name : $post->post_type;

        // Calculate score
        $title_score = $this->calculate_score($post->post_title, $query);
        $content_score = 0;

        if ($title_score < 20 && $enable_fuzzy) {
            $content_score = $this->calculate_score(strip_tags($post->post_content), $query, array('date' => $post->post_date));
        }

        $final_score = max($title_score, $content_score);

        // Skip if score is too low
        if ($final_score < 5 && !empty($query)) {
            return null;
        }

        $status_badge = '';
        if ($post->post_status === 'draft') {
            $status_badge = '<span class="quickswap-badge quickswap-badge-draft">' . __('Draft', 'quickswap') . '</span>';
        } elseif ($post->post_status === 'pending') {
            $status_badge = '<span class="quickswap-badge quickswap-badge-pending">' . __('Pending', 'quickswap') . '</span>';
        }

        return $this->format_item(array(
            'id' => $post->ID,
            'title' => $post->post_title . ' ' . $status_badge,
            'subtitle' => sprintf('%s • %s', $post_type_label, get_the_date('', $post->ID)),
            'url' => get_edit_post_link($post->ID),
            'score' => $final_score,
            'meta' => array(
                'type' => $post->post_type,
                'status' => $post->post_status,
                'date' => $post->post_date,
                'author' => get_the_author_meta('display_name', $post->post_author),
            ),
            'actions' => $this->get_post_actions($post),
        ));
    }

    /**
     * Get post actions
     */
    protected function get_post_actions($post) {
        $actions = array();

        // View action
        if (current_user_can('edit_post', $post->ID)) {
            $actions[] = array(
                'id' => 'edit',
                'label' => __('Edit', 'quickswap'),
                'icon' => '✏️',
                'url' => get_edit_post_link($post->ID),
            );
        }

        // View on frontend
        $permalink = get_permalink($post->ID);
        if ($permalink) {
            $actions[] = array(
                'id' => 'view',
                'label' => __('View', 'quickswap'),
                'icon' => '👁️',
                'url' => $permalink,
            );
        }

        return $actions;
    }

    /**
     * Perform fuzzy search
     */
    protected function fuzzy_search($query, $limit, $base_args) {
        $items = array();

        // Get all posts (limited)
        $fuzzy_args = wp_parse_args($base_args, array(
            'posts_per_page' => 100,
            's' => '', // Remove search to get more results
        ));

        $fuzzy_query = new WP_Query($fuzzy_args);

        if ($fuzzy_query->have_posts()) {
            while ($fuzzy_query->have_posts() && count($items) < $limit) {
                $fuzzy_query->the_post();
                global $post;

                $title = $post->post_title;
                $similarity = QuickSwap_Fuzzy::similarity($query, $title);

                if ($similarity >= 70) {
                    $item = $this->format_post_item($post, $query, true);
                    if ($item !== null) {
                        $items[] = $item;
                    }
                }
            }
            wp_reset_postdata();
        }

        return $items;
    }

    /**
     * Get item URL
     */
    public function get_item_url($item) {
        return get_edit_post_link($item['id']);
    }

    /**
     * Get items for caching
     */
    public static function get_items_for_cache() {
        $cache = array();

        $post_types = get_post_types(array('show_ui' => true), 'names');

        $recent_posts = get_posts(array(
            'post_type' => $post_types,
            'post_status' => array('publish', 'draft'),
            'posts_per_page' => 50,
            'orderby' => 'date',
            'order' => 'DESC',
        ));

        foreach ($recent_posts as $post) {
            $cache[] = array(
                'id' => $post->ID,
                'title' => $post->post_title,
                'type' => $post->post_type,
                'date' => $post->post_date,
            );
        }

        return $cache;
    }
}
