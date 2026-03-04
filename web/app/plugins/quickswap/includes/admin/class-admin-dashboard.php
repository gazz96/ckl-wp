<?php
/**
 * QuickSwap Admin Dashboard
 *
 * Modern admin dashboard with card-based widgets for projects,
 * team members, activity log, tasks, progress charts, budget, and meetings.
 *
 * @package QuickSwap
 * @since 1.2.0
 */

defined('ABSPATH') || exit;

class QuickSwap_Admin_Dashboard {

    /**
     * Dashboard settings key
     */
    const SETTINGS_KEY = 'quickswap_dashboard_settings';

    /**
     * Nonce action for AJAX requests
     */
    const NONCE_ACTION = 'quickswap_dashboard_nonce';

    /**
     * Initialize admin dashboard
     */
    public static function init() {
        add_action('current_screen', array(__CLASS__, 'maybe_override_dashboard'));
        add_action('wp_dashboard_setup', array(__CLASS__, 'register_widgets'));
        add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueue_assets'));
        add_action('wp_ajax_quickswap_dashboard_data', array(__CLASS__, 'ajax_get_widget_data'));
        add_action('wp_ajax_quickswap_dashboard_refresh', array(__CLASS__, 'ajax_refresh_widget'));
    }

    /**
     * Override the dashboard page with our custom template
     */
    public static function maybe_override_dashboard($current_screen) {
        if (!self::is_enabled()) {
            return;
        }

        // Check if this is the main dashboard screen
        if ($current_screen->id !== 'dashboard') {
            return;
        }

        if (!isset($_GET['page']) && !isset($_GET['post_type'])) {
            // Load and display our custom dashboard template
            include QUICKSWAP_PLUGIN_DIR . 'templates/admin-dashboard.php';
            exit;
        }
    }

    /**
     * Check if modern dashboard is enabled
     */
    public static function is_enabled() {
        $settings = get_option(self::SETTINGS_KEY, array());
        return !empty($settings['enable_modern_dashboard']);
    }

    /**
     * Check if specific widget is enabled
     */
    public static function is_widget_enabled($widget_id) {
        $settings = get_option(self::SETTINGS_KEY, array());
        if (empty($settings['dashboard_widgets'])) {
            // Default widgets enabled
            $default_widgets = array(
                'projects_overview' => true,
                'team_members' => true,
                'activity_log' => true,
                'my_tasks' => true,
                'progress_chart' => true,
                'budget_overview' => false,
                'upcoming_meetings' => false,
            );
            return !empty($default_widgets[$widget_id]);
        }
        return !empty($settings['dashboard_widgets'][$widget_id]);
    }

    /**
     * Get dashboard settings
     */
    public static function get_settings() {
        $defaults = array(
            'enable_modern_dashboard' => true,
            'dashboard_widgets' => array(
                'projects_overview' => true,
                'team_members' => true,
                'activity_log' => true,
                'my_tasks' => true,
                'progress_chart' => true,
                'budget_overview' => false,
                'upcoming_meetings' => false,
            ),
            'project_post_types' => array('post', 'page'),
            'team_roles' => array('administrator', 'editor', 'author'),
        );
        return wp_parse_args(get_option(self::SETTINGS_KEY, array()), $defaults);
    }

    /**
     * Remove default WordPress dashboard widgets
     */
    private static function remove_default_widgets() {
        // Remove default widgets
        remove_meta_box('dashboard_primary', 'dashboard', 'side');
        remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
        remove_meta_box('dashboard_right_now', 'dashboard', 'normal');
        remove_meta_box('dashboard_activity', 'dashboard', 'normal');
        remove_meta_box('dashboard_site_health', 'dashboard', 'normal');
        remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
        remove_meta_box('dashboard_plugins', 'dashboard', 'normal');
        remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
        remove_meta_box('dashboard_secondary', 'dashboard', 'side');

        // Allow developers to remove additional widgets
        do_action('quickswap_dashboard_remove_widgets');
    }

    /**
     * Register dashboard widgets (fallback for when custom template is disabled)
     */
    public static function register_widgets() {
        if (!self::is_enabled()) {
            return;
        }

        // Remove default widgets
        self::remove_default_widgets();

        // Note: Widgets are now rendered directly in the template
        // This is kept for backward compatibility
        do_action('quickswap_dashboard_register_widgets');
    }

    /**
     * Enqueue dashboard assets
     */
    public static function enqueue_assets($hook) {
        // Only load on dashboard page
        if ('index.php' !== $hook) {
            return;
        }

        if (!self::is_enabled()) {
            return;
        }

        // Don't enqueue here when using custom template (template handles it directly)
        // This prevents duplicate enqueuing
        global $pagenow;
        if ($pagenow === 'index.php' && !isset($_GET['page']) && !isset($_GET['post_type'])) {
            return; // Let the template handle asset loading
        }

        // Enqueue Tailwind from theme - WindPress will process it
        $theme_dir = get_template_directory();
        if (file_exists($theme_dir . '/assets/css/main.css')) {
            wp_enqueue_style(
                'quickswap-tailwind',
                get_template_directory_uri() . '/assets/css/main.css',
                array(),
                QUICKSWAP_VERSION
            );
        }

        // Enqueue dashboard scripts
        wp_enqueue_script(
            'quickswap-dashboard',
            QUICKSWAP_PLUGIN_URL . 'assets/js/quickswap-dashboard.js',
            array('jquery'),
            QUICKSWAP_VERSION,
            true
        );

        // Localize script
        wp_localize_script('quickswap-dashboard', 'quickswapDashboard', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce(self::NONCE_ACTION),
            'refreshInterval' => 300000, // 5 minutes
            'strings' => array(
                'loading' => __('Loading...', 'quickswap'),
                'error' => __('Error loading data. Please try again.', 'quickswap'),
                'noData' => __('No data available.', 'quickswap'),
                'refreshSuccess' => __('Widget refreshed successfully.', 'quickswap'),
            ),
        ));
    }

    /**
     * AJAX handler for getting widget data
     */
    public static function ajax_get_widget_data() {
        check_ajax_referer(self::NONCE_ACTION, 'nonce');

        if (!current_user_can('edit_posts')) {
            wp_send_json_error(array('message' => __('Permission denied.', 'quickswap')));
        }

        $widget_id = isset($_POST['widget_id']) ? sanitize_text_field($_POST['widget_id']) : '';

        if (empty($widget_id)) {
            wp_send_json_error(array('message' => __('Invalid widget ID.', 'quickswap')));
        }

        $data = self::get_widget_data($widget_id);

        if (is_wp_error($data)) {
            wp_send_json_error(array('message' => $data->get_error_message()));
        }

        wp_send_json_success(array('data' => $data));
    }

    /**
     * AJAX handler for refreshing a widget
     */
    public static function ajax_refresh_widget() {
        check_ajax_referer(self::NONCE_ACTION, 'nonce');

        if (!current_user_can('edit_posts')) {
            wp_send_json_error(array('message' => __('Permission denied.', 'quickswap')));
        }

        $widget_id = isset($_POST['widget_id']) ? sanitize_text_field($_POST['widget_id']) : '';

        if (empty($widget_id)) {
            wp_send_json_error(array('message' => __('Invalid widget ID.', 'quickswap')));
        }

        // Clear transients for this widget
        $transient_key = 'quickswap_dashboard_' . $widget_id . '_' . get_current_user_id();
        delete_transient($transient_key);

        $data = self::get_widget_data($widget_id);

        if (is_wp_error($data)) {
            wp_send_json_error(array('message' => $data->get_error_message()));
        }

        // Render the widget HTML
        ob_start();
        self::render_widget_by_id($widget_id, $data);
        $html = ob_get_clean();

        wp_send_json_success(array(
            'html' => $html,
            'data' => $data,
        ));
    }

    /**
     * Get widget data with caching
     */
    private static function get_widget_data($widget_id) {
        $transient_key = 'quickswap_dashboard_' . $widget_id . '_' . get_current_user_id();
        $cached = get_transient($transient_key);

        if (false !== $cached) {
            return $cached;
        }

        $data = null;
        switch ($widget_id) {
            case 'projects_overview':
                $data = self::get_projects_data();
                break;
            case 'team_members':
                $data = self::get_team_members_data();
                break;
            case 'activity_log':
                $data = self::get_activity_data();
                break;
            case 'my_tasks':
                $data = self::get_my_tasks_data();
                break;
            case 'progress_chart':
                $data = self::get_progress_stats();
                break;
            case 'budget_overview':
                $data = self::get_budget_data();
                break;
            case 'upcoming_meetings':
                $data = self::get_meetings_data();
                break;
            default:
                $data = new WP_Error('invalid_widget', __('Unknown widget.', 'quickswap'));
                break;
        }

        if (!is_wp_error($data)) {
            set_transient($transient_key, $data, 5 * MINUTE_IN_SECONDS);
        }

        return $data;
    }

    /**
     * Render widget by ID
     */
    private static function render_widget_by_id($widget_id, $data = null) {
        if (null === $data) {
            $data = self::get_widget_data($widget_id);
        }

        switch ($widget_id) {
            case 'projects_overview':
                self::render_projects_overview_widget($data);
                break;
            case 'team_members':
                self::render_team_members_widget($data);
                break;
            case 'activity_log':
                self::render_activity_log_widget($data);
                break;
            case 'my_tasks':
                self::render_my_tasks_widget($data);
                break;
            case 'progress_chart':
                self::render_progress_chart_widget($data);
                break;
            case 'budget_overview':
                self::render_budget_overview_widget($data);
                break;
            case 'upcoming_meetings':
                self::render_upcoming_meetings_widget($data);
                break;
        }
    }

    /**
     * Get projects data
     */
    private static function get_projects_data() {
        $settings = self::get_settings();
        $post_types = $settings['project_post_types'];

        $projects = array();

        foreach ($post_types as $post_type) {
            $posts = get_posts(array(
                'post_type' => $post_type,
                'post_status' => array('publish', 'draft', 'pending'),
                'posts_per_page' => 10,
                'orderby' => 'modified',
                'order' => 'DESC',
            ));

            foreach ($posts as $post) {
                $author = get_userdata($post->post_author);
                $status = $post->post_status;

                // Calculate progress based on post status
                $progress = 0;
                switch ($status) {
                    case 'publish':
                        $progress = 100;
                        break;
                    case 'draft':
                        $progress = 50;
                        break;
                    case 'pending':
                        $progress = 75;
                        break;
                    default:
                        $progress = 25;
                }

                $projects[] = array(
                    'id' => $post->ID,
                    'title' => $post->post_title,
                    'type' => $post_type,
                    'status' => $status,
                    'status_label' => get_post_status_object($status)->label ?? ucfirst($status),
                    'author' => $author ? $author->display_name : __('Unknown', 'quickswap'),
                    'author_avatar' => $author ? get_avatar_url($author->ID, array('size' => 32)) : '',
                    'progress' => $progress,
                    'modified' => get_the_modified_date('Y-m-d H:i', $post),
                );
            }
        }

        return $projects;
    }

    /**
     * Render Projects Overview widget with Tailwind CSS
     */
    public static function render_projects_overview_widget($projects = null) {
        if (null === $projects) {
            $projects = self::get_projects_data();
        }

        $status_classes = array(
            'publish' => 'bg-green-100 text-green-700',
            'draft' => 'bg-gray-100 text-gray-700',
            'pending' => 'bg-yellow-100 text-yellow-700',
        );

        ?>
        <div class="quickswap-projects-widget" data-widget-id="projects_overview">
            <?php if (empty($projects)) : ?>
                <div class="p-8 text-center">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-sm text-gray-500"><?php esc_html_e('No projects found.', 'quickswap'); ?></p>
                </div>
            <?php else : ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50/50 border-b border-border">
                                <th class="p-4 text-left font-semibold text-gray-700"><?php esc_html_e('Project', 'quickswap'); ?></th>
                                <th class="p-4 text-left font-semibold text-gray-700"><?php esc_html_e('Status', 'quickswap'); ?></th>
                                <th class="p-4 text-left font-semibold text-gray-700"><?php esc_html_e('Progress', 'quickswap'); ?></th>
                                <th class="p-4 text-left font-semibold text-gray-700"><?php esc_html_e('Assigned To', 'quickswap'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($projects as $project) :
                                $status_class = $status_classes[$project['status']] ?? 'bg-gray-100 text-gray-700';
                            ?>
                                <tr class="border-b border-border hover:bg-gray-50/30 transition-colors">
                                    <td class="p-4">
                                        <a href="<?php echo esc_url(get_edit_post_link($project['id'])); ?>"
                                           class="font-medium text-primary hover:underline block">
                                            <?php echo esc_html($project['title']); ?>
                                        </a>
                                        <span class="text-xs text-gray-500 mt-1 block"><?php echo esc_html($project['type']); ?></span>
                                    </td>
                                    <td class="p-4">
                                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold <?php echo esc_attr($status_class); ?>">
                                            <?php echo esc_html($project['status_label']); ?>
                                        </span>
                                    </td>
                                    <td class="p-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden max-w-[80px]">
                                                <div class="h-full bg-primary rounded-full transition-all duration-500" style="width: <?php echo esc_attr($project['progress']); ?>%;"></div>
                                            </div>
                                            <span class="text-xs text-gray-500"><?php echo esc_html($project['progress']); ?>%</span>
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        <div class="flex items-center gap-2">
                                            <?php if (!empty($project['author_avatar'])) : ?>
                                                <img src="<?php echo esc_url($project['author_avatar']); ?>"
                                                     alt="<?php echo esc_attr($project['author']); ?>"
                                                     class="w-6 h-6 rounded-full">
                                            <?php endif; ?>
                                            <span class="text-xs text-gray-600"><?php echo esc_html($project['author']); ?></span>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Get team members data
     */
    private static function get_team_members_data() {
        $settings = self::get_settings();
        $roles = $settings['team_roles'];

        $users = get_users(array(
            'role__in' => $roles,
            'orderby' => 'display_name',
            'order' => 'ASC',
            'number' => 8,
        ));

        $members = array();

        foreach ($users as $user) {
            $post_counts = count_user_posts($user->ID, array('post', 'page'), true);

            // Get pending posts
            $pending = get_posts(array(
                'author' => $user->ID,
                'post_status' => 'pending',
                'posts_per_page' => -1,
                'fields' => 'ids',
            ));

            // Get drafts
            $drafts = get_posts(array(
                'author' => $user->ID,
                'post_status' => 'draft',
                'posts_per_page' => -1,
                'fields' => 'ids',
            ));

            $members[] = array(
                'id' => $user->ID,
                'name' => $user->display_name,
                'email' => $user->user_email,
                'role' => implode(', ', $user->roles),
                'avatar' => get_avatar_url($user->ID, array('size' => 64)),
                'published_posts' => $post_counts,
                'pending_posts' => count($pending),
                'draft_posts' => count($drafts),
                'total_tasks' => $post_counts + count($pending) + count($drafts),
            );
        }

        return $members;
    }

    /**
     * Render Team Members widget with Tailwind CSS
     */
    public static function render_team_members_widget($members = null) {
        if (null === $members) {
            $members = self::get_team_members_data();
        }

        ?>
        <div class="quickswap-team-widget" data-widget-id="team_members">
            <?php if (empty($members)) : ?>
                <div class="p-8 text-center">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <p class="text-sm text-gray-500"><?php esc_html_e('No team members found.', 'quickswap'); ?></p>
                </div>
            <?php else : ?>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <?php foreach ($members as $member) : ?>
                        <div class="text-center p-4 rounded-lg hover:bg-gray-50 transition-colors group">
                            <div class="relative inline-block mb-3">
                                <img src="<?php echo esc_url($member['avatar']); ?>"
                                     alt="<?php echo esc_attr($member['name']); ?>"
                                     class="w-14 h-14 rounded-full mx-auto border-2 border-white shadow-sm group-hover:shadow-md transition-shadow">
                                <span class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-green-500 border-2 border-white rounded-full"></span>
                            </div>
                            <h4 class="font-semibold text-sm text-gray-900 truncate"><?php echo esc_html($member['name']); ?></h4>
                            <p class="text-xs text-gray-500 capitalize mb-3"><?php echo esc_html($member['role']); ?></p>
                            <div class="flex justify-center gap-4 text-xs">
                                <div>
                                    <p class="font-bold text-green-600"><?php echo esc_html($member['published_posts']); ?></p>
                                    <p class="text-gray-500"><?php esc_html_e('Pub', 'quickswap'); ?></p>
                                </div>
                                <div>
                                    <p class="font-bold text-yellow-600"><?php echo esc_html($member['pending_posts']); ?></p>
                                    <p class="text-gray-500"><?php esc_html_e('Pend', 'quickswap'); ?></p>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-400"><?php echo esc_html($member['draft_posts']); ?></p>
                                    <p class="text-gray-500"><?php esc_html_e('Draft', 'quickswap'); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Get activity log data
     */
    private static function get_activity_data() {
        $activities = array();

        // Get recent posts
        $recent_posts = get_posts(array(
            'post_status' => array('publish', 'draft', 'pending'),
            'posts_per_page' => 5,
            'orderby' => 'modified',
            'order' => 'DESC',
        ));

        foreach ($recent_posts as $post) {
            $author = get_userdata($post->post_author);
            $activities[] = array(
                'id' => $post->ID,
                'type' => 'post',
                'action' => 'modified',
                'title' => $post->post_title,
                'user' => $author ? $author->display_name : __('Unknown', 'quickswap'),
                'user_id' => $post->post_author,
                'avatar' => $author ? get_avatar_url($author->ID, array('size' => 32)) : '',
                'date' => get_the_modified_date('U', $post),
                'date_formatted' => self::time_elapsed(get_the_modified_date('U', $post)),
                'link' => get_edit_post_link($post->ID),
            );
        }

        // Get recent comments
        $recent_comments = get_comments(array(
            'number' => 5,
            'orderby' => 'comment_date',
            'order' => 'DESC',
        ));

        foreach ($recent_comments as $comment) {
            $activities[] = array(
                'id' => $comment->comment_ID,
                'type' => 'comment',
                'action' => 'commented',
                'title' => mb_substr($comment->comment_content, 0, 50) . (mb_strlen($comment->comment_content) > 50 ? '...' : ''),
                'user' => $comment->comment_author,
                'user_id' => $comment->user_id,
                'avatar' => get_avatar_url($comment->comment_author_email, array('size' => 32)),
                'date' => strtotime($comment->comment_date),
                'date_formatted' => self::time_elapsed(strtotime($comment->comment_date)),
                'link' => get_comment_link($comment->comment_ID),
            );
        }

        // Sort by date
        usort($activities, function($a, $b) {
            return $b['date'] - $a['date'];
        });

        return array_slice($activities, 0, 10);
    }

    /**
     * Format time elapsed
     */
    private static function time_elapsed($timestamp) {
        $diff = time() - $timestamp;

        if ($diff < HOUR_IN_SECONDS) {
            $minutes = floor($diff / MINUTE_IN_SECONDS);
            return sprintf(_n('%d minute ago', '%d minutes ago', $minutes, 'quickswap'), $minutes);
        } elseif ($diff < DAY_IN_SECONDS) {
            $hours = floor($diff / HOUR_IN_SECONDS);
            return sprintf(_n('%d hour ago', '%d hours ago', $hours, 'quickswap'), $hours);
        } elseif ($diff < WEEK_IN_SECONDS) {
            $days = floor($diff / DAY_IN_SECONDS);
            return sprintf(_n('%d day ago', '%d days ago', $days, 'quickswap'), $days);
        } else {
            return date_i18n(get_option('date_format'), $timestamp);
        }
    }

    /**
     * Render Activity Log widget with Tailwind CSS
     */
    public static function render_activity_log_widget($activities = null) {
        if (null === $activities) {
            $activities = self::get_activity_data();
        }

        ?>
        <div class="quickswap-activity-widget" data-widget-id="activity_log">
            <?php if (empty($activities)) : ?>
                <div class="p-8 text-center">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm text-gray-500"><?php esc_html_e('No recent activity.', 'quickswap'); ?></p>
                </div>
            <?php else : ?>
                <div class="space-y-4">
                    <?php foreach ($activities as $activity) : ?>
                        <div class="flex gap-3 items-start">
                            <div class="relative">
                                <?php if (!empty($activity['avatar'])) : ?>
                                    <img src="<?php echo esc_url($activity['avatar']); ?>"
                                         alt="<?php echo esc_attr($activity['user']); ?>"
                                         class="w-8 h-8 rounded-full">
                                <?php else : ?>
                                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                <?php endif; ?>
                                <div class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 bg-green-500 border-2 border-white rounded-full"></div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm">
                                    <span class="font-medium text-gray-900"><?php echo esc_html($activity['user']); ?></span>
                                    <span class="text-gray-500">
                                        <?php
                                        switch ($activity['action']) {
                                            case 'modified':
                                                esc_html_e('modified', 'quickswap');
                                                break;
                                            case 'commented':
                                                esc_html_e('commented on', 'quickswap');
                                                break;
                                            default:
                                                echo esc_html($activity['action']);
                                        }
                                        ?>
                                    </span>
                                </p>
                                <a href="<?php echo esc_url($activity['link']); ?>"
                                   class="text-sm text-primary hover:underline truncate block">
                                    <?php echo esc_html($activity['title']); ?>
                                </a>
                                <span class="text-xs text-gray-400"><?php echo esc_html($activity['date_formatted']); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Get my tasks data
     */
    private static function get_my_tasks_data() {
        $user_id = get_current_user_id();

        $drafts = get_posts(array(
            'author' => $user_id,
            'post_status' => 'draft',
            'posts_per_page' => -1,
            'fields' => 'ids',
        ));

        $pending = get_posts(array(
            'author' => $user_id,
            'post_status' => 'pending',
            'posts_per_page' => -1,
            'fields' => 'ids',
        ));

        $tasks = array();

        // Add drafts as low priority
        foreach ($drafts as $post_id) {
            $post = get_post($post_id);
            $tasks[] = array(
                'id' => $post_id,
                'title' => $post->post_title,
                'status' => 'draft',
                'priority' => 'low',
                'date' => get_the_modified_date('Y-m-d', $post),
            );
        }

        // Add pending as high priority
        foreach ($pending as $post_id) {
            $post = get_post($post_id);
            $tasks[] = array(
                'id' => $post_id,
                'title' => $post->post_title,
                'status' => 'pending',
                'priority' => 'high',
                'date' => get_the_modified_date('Y-m-d', $post),
            );
        }

        // Sort by priority and date
        usort($tasks, function($a, $b) {
            $priority_order = array('high' => 0, 'medium' => 1, 'low' => 2);
            $a_priority = $priority_order[$a['priority']] ?? 1;
            $b_priority = $priority_order[$b['priority']] ?? 1;

            if ($a_priority === $b_priority) {
                return strtotime($b['date']) - strtotime($a['date']);
            }

            return $a_priority - $b_priority;
        });

        return array_slice($tasks, 0, 10);
    }

    /**
     * Render My Tasks widget with Tailwind CSS
     */
    public static function render_my_tasks_widget($tasks = null) {
        if (null === $tasks) {
            $tasks = self::get_my_tasks_data();
        }

        $priority_colors = array(
            'high' => 'bg-red-100 text-red-700',
            'medium' => 'bg-yellow-100 text-yellow-700',
            'low' => 'bg-gray-100 text-gray-700',
        );

        ?>
        <div class="quickswap-tasks-widget" data-widget-id="my_tasks">
            <?php if (empty($tasks)) : ?>
                <div class="p-8 text-center">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p class="text-sm text-gray-500"><?php esc_html_e('No tasks found.', 'quickswap'); ?></p>
                </div>
            <?php else : ?>
                <ul class="divide-y divide-border">
                    <?php foreach ($tasks as $task) :
                        $priority_class = $priority_colors[$task['priority']] ?? 'bg-gray-100 text-gray-700';
                    ?>
                        <li class="p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start gap-3">
                                <div class="mt-0.5">
                                    <input type="checkbox" disabled
                                           class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <a href="<?php echo esc_url(get_edit_post_link($task['id'])); ?>"
                                       class="text-sm font-medium text-gray-900 hover:text-primary transition-colors block">
                                        <?php echo esc_html($task['title']); ?>
                                    </a>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium <?php echo esc_attr($priority_class); ?>">
                                            <?php echo esc_html(ucfirst($task['priority'])); ?>
                                        </span>
                                        <span class="text-xs text-gray-400"><?php echo esc_html($task['date']); ?></span>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Get progress statistics
     */
    private static function get_progress_stats() {
        $user_id = get_current_user_id();

        $stats = array(
            'published' => 0,
            'draft' => 0,
            'pending' => 0,
            'total' => 0,
        );

        $post_counts = count_user_posts($user_id, array('post', 'page'), true);
        $stats['published'] = $post_counts;

        $drafts = get_posts(array(
            'author' => $user_id,
            'post_status' => 'draft',
            'posts_per_page' => -1,
            'fields' => 'ids',
        ));
        $stats['draft'] = count($drafts);

        $pending = get_posts(array(
            'author' => $user_id,
            'post_status' => 'pending',
            'posts_per_page' => -1,
            'fields' => 'ids',
        ));
        $stats['pending'] = count($pending);

        $stats['total'] = $stats['published'] + $stats['draft'] + $stats['pending'];

        return $stats;
    }

    /**
     * Render Progress Chart widget with Tailwind CSS
     */
    public static function render_progress_chart_widget($stats = null) {
        if (null === $stats) {
            $stats = self::get_progress_stats();
        }

        $total = $stats['total'];
        $published_pct = $total > 0 ? round(($stats['published'] / $total) * 100) : 0;
        $draft_pct = $total > 0 ? round(($stats['draft'] / $total) * 100) : 0;
        $pending_pct = $total > 0 ? round(($stats['pending'] / $total) * 100) : 0;

        ?>
        <div class="quickswap-progress-widget" data-widget-id="progress_chart">
            <?php if ($total === 0) : ?>
                <div class="p-8 text-center">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                    </svg>
                    <p class="text-sm text-gray-500"><?php esc_html_e('No content statistics yet.', 'quickswap'); ?></p>
                </div>
            <?php else : ?>
                <div class="flex flex-col items-center gap-6">
                    <div class="relative w-40 h-40">
                        <svg viewBox="0 0 100 100" class="w-full h-full -rotate-90">
                            <!-- Background circle -->
                            <circle cx="50" cy="50" r="40" fill="none" stroke="#f3f4f6" stroke-width="20"/>
                            <!-- Published segment -->
                            <circle cx="50" cy="50" r="40" fill="none" stroke="#22c55e" stroke-width="20"
                                    stroke-dasharray="<?php echo esc_attr($published_pct * 2.51); ?> 251"
                                    stroke-dashoffset="0"
                                    class="transition-all duration-500"/>
                            <!-- Draft segment -->
                            <circle cx="50" cy="50" r="40" fill="none" stroke="#eab308" stroke-width="20"
                                    stroke-dasharray="<?php echo esc_attr($draft_pct * 2.51); ?> 251"
                                    stroke-dashoffset="-<?php echo esc_attr($published_pct * 2.51); ?>"
                                    class="transition-all duration-500"/>
                            <!-- Pending segment -->
                            <circle cx="50" cy="50" r="40" fill="none" stroke="#f97316" stroke-width="20"
                                    stroke-dasharray="<?php echo esc_attr($pending_pct * 2.51); ?> 251"
                                    stroke-dashoffset="-<?php echo esc_attr(($published_pct + $draft_pct) * 2.51); ?>"
                                    class="transition-all duration-500"/>
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-3xl font-bold text-gray-900"><?php echo esc_html($total); ?></span>
                            <span class="text-xs text-gray-500 uppercase tracking-wide"><?php esc_html_e('Total', 'quickswap'); ?></span>
                        </div>
                    </div>
                    <div class="w-full space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-green-500"></span>
                                <span class="text-gray-600"><?php esc_html_e('Published', 'quickswap'); ?></span>
                            </div>
                            <span class="font-semibold text-gray-900"><?php echo esc_html($stats['published']); ?></span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-yellow-500"></span>
                                <span class="text-gray-600"><?php esc_html_e('Drafts', 'quickswap'); ?></span>
                            </div>
                            <span class="font-semibold text-gray-900"><?php echo esc_html($stats['draft']); ?></span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-orange-500"></span>
                                <span class="text-gray-600"><?php esc_html_e('Pending', 'quickswap'); ?></span>
                            </div>
                            <span class="font-semibold text-gray-900"><?php echo esc_html($stats['pending']); ?></span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Get budget data
     */
    private static function get_budget_data() {
        // Check if WooCommerce is active
        if (!class_exists('WooCommerce')) {
            // Return mock data for demonstration
            return array(
                'total_budget' => 10000,
                'spent' => 6500,
                'remaining' => 3500,
                'categories' => array(
                    array(
                        'name' => __('Content Creation', 'quickswap'),
                        'budget' => 3000,
                        'spent' => 2100,
                    ),
                    array(
                        'name' => __('Development', 'quickswap'),
                        'budget' => 4000,
                        'spent' => 2800,
                    ),
                    array(
                        'name' => __('Marketing', 'quickswap'),
                        'budget' => 2000,
                        'spent' => 1200,
                    ),
                    array(
                        'name' => __('Operations', 'quickswap'),
                        'budget' => 1000,
                        'spent' => 400,
                    ),
                ),
            );
        }

        // Get WooCommerce order data
        $orders = wc_get_orders(array(
            'limit' => -1,
            'status' => array('completed', 'processing'),
            'type' => 'shop_order',
        ));

        $total_spent = 0;
        foreach ($orders as $order) {
            $total_spent += $order->get_total();
        }

        // Use post meta for budget if set
        $budget_limit = get_option('quickswap_budget_limit', 10000);

        return array(
            'total_budget' => $budget_limit,
            'spent' => $total_spent,
            'remaining' => max(0, $budget_limit - $total_spent),
            'categories' => array(
                array(
                    'name' => __('Sales', 'quickswap'),
                    'budget' => $budget_limit,
                    'spent' => $total_spent,
                ),
            ),
        );
    }

    /**
     * Render Budget Overview widget with Tailwind CSS
     */
    public static function render_budget_overview_widget($budget = null) {
        if (null === $budget) {
            $budget = self::get_budget_data();
        }

        $total = $budget['total_budget'];
        $spent = $budget['spent'];
        $remaining = $budget['remaining'];
        $spent_pct = $total > 0 ? round(($spent / $total) * 100) : 0;

        ?>
        <div class="quickswap-budget-widget" data-widget-id="budget_overview">
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="text-center">
                    <p class="text-xs text-gray-500 uppercase"><?php esc_html_e('Budget', 'quickswap'); ?></p>
                    <p class="text-lg font-bold text-gray-900"><?php echo esc_html(number_format_i18n($total)); ?></p>
                </div>
                <div class="text-center">
                    <p class="text-xs text-gray-500 uppercase"><?php esc_html_e('Spent', 'quickswap'); ?></p>
                    <p class="text-lg font-bold text-orange-600"><?php echo esc_html(number_format_i18n($spent)); ?></p>
                </div>
                <div class="text-center">
                    <p class="text-xs text-gray-500 uppercase"><?php esc_html_e('Left', 'quickswap'); ?></p>
                    <p class="text-lg font-bold text-green-600"><?php echo esc_html(number_format_i18n($remaining)); ?></p>
                </div>
            </div>

            <div class="mb-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-gray-600"><?php esc_html_e('Overall Progress', 'quickswap'); ?></span>
                    <span class="text-sm font-semibold"><?php echo esc_html($spent_pct); ?>%</span>
                </div>
                <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-orange-500 to-orange-600 rounded-full transition-all duration-500"
                         style="width: <?php echo esc_attr($spent_pct); ?>%;"></div>
                </div>
            </div>

            <?php if (!empty($budget['categories'])) : ?>
                <div class="space-y-4">
                    <h4 class="text-sm font-semibold text-gray-700"><?php esc_html_e('Categories', 'quickswap'); ?></h4>
                    <?php foreach ($budget['categories'] as $category) :
                        $cat_pct = $category['budget'] > 0 ? round(($category['spent'] / $category['budget']) * 100) : 0;
                    ?>
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs text-gray-600"><?php echo esc_html($category['name']); ?></span>
                                <span class="text-xs text-gray-500"><?php echo esc_html(number_format_i18n($category['spent'])); ?> / <?php echo esc_html(number_format_i18n($category['budget'])); ?></span>
                            </div>
                            <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-primary rounded-full transition-all duration-300" style="width: <?php echo esc_attr($cat_pct); ?>%;"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Get meetings data
     */
    private static function get_meetings_data() {
        // Check for custom post type 'meeting' or similar
        $meetings = get_posts(array(
            'post_type' => array('meeting', 'event', 'schedule'),
            'posts_per_page' => 5,
            'orderby' => 'meta_value',
            'meta_key' => '_meeting_date',
            'order' => 'ASC',
            'meta_query' => array(
                array(
                    'key' => '_meeting_date',
                    'value' => current_time('Y-m-d'),
                    'compare' => '>=',
                ),
            ),
        ));

        $meeting_data = array();

        foreach ($meetings as $meeting) {
            $date = get_post_meta($meeting->ID, '_meeting_date', true);
            $time = get_post_meta($meeting->ID, '_meeting_time', true);
            $location = get_post_meta($meeting->ID, '_meeting_location', true);

            $meeting_data[] = array(
                'id' => $meeting->ID,
                'title' => $meeting->post_title,
                'date' => $date ? date_i18n(get_option('date_format'), strtotime($date)) : '',
                'time' => $time ?? '',
                'location' => $location ?? __('N/A', 'quickswap'),
                'description' => mb_substr(wp_strip_all_tags($meeting->post_content), 0, 100),
            );
        }

        // If no custom meeting post type, return sample data
        if (empty($meeting_data)) {
            $meeting_data = array(
                array(
                    'id' => 0,
                    'title' => __('Weekly Team Standup', 'quickswap'),
                    'date' => date_i18n(get_option('date_format'), strtotime('+1 day')),
                    'time' => '10:00 AM',
                    'location' => __('Zoom', 'quickswap'),
                    'description' => __('Weekly team sync to discuss project progress and blockers.', 'quickswap'),
                ),
                array(
                    'id' => 0,
                    'title' => __('Content Planning Session', 'quickswap'),
                    'date' => date_i18n(get_option('date_format'), strtotime('+3 days')),
                    'time' => '2:00 PM',
                    'location' => __('Conference Room A', 'quickswap'),
                    'description' => __('Plan content calendar for next month.', 'quickswap'),
                ),
                array(
                    'id' => 0,
                    'title' => __('Client Review Meeting', 'quickswap'),
                    'date' => date_i18n(get_option('date_format'), strtotime('+5 days')),
                    'time' => '11:00 AM',
                    'location' => __('Google Meet', 'quickswap'),
                    'description' => __('Review project deliverables with client.', 'quickswap'),
                ),
            );
        }

        return $meeting_data;
    }

    /**
     * Render Upcoming Meetings widget with Tailwind CSS
     */
    public static function render_upcoming_meetings_widget($meetings = null) {
        if (null === $meetings) {
            $meetings = self::get_meetings_data();
        }

        ?>
        <div class="quickswap-meetings-widget" data-widget-id="upcoming_meetings">
            <?php if (empty($meetings)) : ?>
                <div class="p-8 text-center">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-sm text-gray-500"><?php esc_html_e('No upcoming meetings scheduled.', 'quickswap'); ?></p>
                </div>
            <?php else : ?>
                <div class="space-y-4">
                    <?php foreach ($meetings as $meeting) :
                        $day = $meeting['date'] ? date_i18n('d', strtotime($meeting['date'])) : '--';
                        $month = $meeting['date'] ? date_i18n('M', strtotime($meeting['date'])) : '--';
                    ?>
                        <div class="flex gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="flex-shrink-0 w-12 h-12 bg-primary/10 rounded-lg flex flex-col items-center justify-center">
                                <span class="text-lg font-bold text-primary"><?php echo esc_html($day); ?></span>
                                <span class="text-xs text-primary/70 uppercase"><?php echo esc_html($month); ?></span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-semibold text-gray-900 truncate"><?php echo esc_html($meeting['title']); ?></h4>
                                <div class="flex items-center gap-3 mt-1">
                                    <span class="flex items-center gap-1 text-xs text-gray-500">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <?php echo esc_html($meeting['time']); ?>
                                    </span>
                                    <span class="flex items-center gap-1 text-xs text-gray-500">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <?php echo esc_html($meeting['location']); ?>
                                    </span>
                                </div>
                                <?php if (!empty($meeting['description'])) : ?>
                                    <p class="text-xs text-gray-500 mt-2 line-clamp-2"><?php echo esc_html($meeting['description']); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}
