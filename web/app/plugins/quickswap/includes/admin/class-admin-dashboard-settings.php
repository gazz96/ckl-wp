<?php
/**
 * QuickSwap Admin Dashboard Settings
 *
 * Settings page for the modern admin dashboard feature.
 *
 * @package QuickSwap
 * @since 1.2.0
 */

defined('ABSPATH') || exit;

class QuickSwap_Admin_Dashboard_Settings {

    /**
     * Settings key
     */
    const SETTINGS_KEY = 'quickswap_dashboard_settings';

    /**
     * Initialize dashboard settings
     */
    public static function init() {
        add_action('admin_init', array(__CLASS__, 'register_settings'));
        add_action('quickswap_settings_render_dashboard_tab', array(__CLASS__, 'render_dashboard_tab'));
        add_action('admin_post_quickswap_save_dashboard_settings', array(__CLASS__, 'save_settings'));
    }

    /**
     * Register settings using WordPress Settings API
     */
    public static function register_settings() {
        register_setting(
            self::SETTINGS_KEY,
            self::SETTINGS_KEY,
            array(
                'sanitize_callback' => array(__CLASS__, 'sanitize_settings'),
                'default' => self::get_default_settings(),
            )
        );
    }

    /**
     * Get default settings
     */
    private static function get_default_settings() {
        return array(
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
    }

    /**
     * Get current settings
     */
    private static function get_settings() {
        return wp_parse_args(
            get_option(self::SETTINGS_KEY, array()),
            self::get_default_settings()
        );
    }

    /**
     * Sanitize settings
     */
    public static function sanitize_settings($input) {
        $sanitized = array();
        $current = self::get_settings();

        // Enable modern dashboard
        $sanitized['enable_modern_dashboard'] = !empty($input['enable_modern_dashboard']);

        // Dashboard widgets
        $available_widgets = array(
            'projects_overview',
            'team_members',
            'activity_log',
            'my_tasks',
            'progress_chart',
            'budget_overview',
            'upcoming_meetings',
        );

        $sanitized['dashboard_widgets'] = array();
        foreach ($available_widgets as $widget_id) {
            $sanitized['dashboard_widgets'][$widget_id] = !empty($input['dashboard_widgets'][$widget_id]);
        }

        // Project post types
        $available_post_types = get_post_types(array('public' => true), 'names');
        $sanitized['project_post_types'] = array();
        if (!empty($input['project_post_types']) && is_array($input['project_post_types'])) {
            foreach ($input['project_post_types'] as $post_type) {
                if (isset($available_post_types[$post_type])) {
                    $sanitized['project_post_types'][] = $post_type;
                }
            }
        }

        // Ensure at least one post type is selected
        if (empty($sanitized['project_post_types'])) {
            $sanitized['project_post_types'] = array('post', 'page');
        }

        // Team roles
        $available_roles = array_keys(get_editable_roles());
        $sanitized['team_roles'] = array();
        if (!empty($input['team_roles']) && is_array($input['team_roles'])) {
            foreach ($input['team_roles'] as $role) {
                if (in_array($role, $available_roles, true)) {
                    $sanitized['team_roles'][] = $role;
                }
            }
        }

        // Ensure at least one role is selected
        if (empty($sanitized['team_roles'])) {
            $sanitized['team_roles'] = array('administrator', 'editor', 'author');
        }

        return $sanitized;
    }

    /**
     * Render dashboard settings tab
     */
    public static function render_dashboard_tab() {
        $settings = self::get_settings();
        $post_types = get_post_types(array('public' => true), 'objects');
        $roles = get_editable_roles();

        ?>
        <div class="quickswap-dashboard-settings">
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <input type="hidden" name="action" value="quickswap_save_dashboard_settings">
                <?php wp_nonce_field('quickswap_dashboard_settings_nonce', 'quickswap_dashboard_nonce'); ?>

                <div class="quickswap-settings-section">
                    <h3><?php esc_html_e('General Settings', 'quickswap'); ?></h3>

                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="enable_modern_dashboard">
                                    <?php esc_html_e('Enable Modern Dashboard', 'quickswap'); ?>
                                </label>
                            </th>
                            <td>
                                <input
                                    type="checkbox"
                                    name="enable_modern_dashboard"
                                    id="enable_modern_dashboard"
                                    value="1"
                                    <?php checked(!empty($settings['enable_modern_dashboard'])); ?>
                                >
                                <label for="enable_modern_dashboard" class="description">
                                    <?php esc_html_e('Enable the modern card-based dashboard interface', 'quickswap'); ?>
                                </label>
                                <p class="description">
                                    <?php esc_html_e('When enabled, this will replace the default WordPress dashboard with a modern, card-based interface featuring project overviews, team members, activity logs, and more.', 'quickswap'); ?>
                                </p>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="quickswap-settings-section">
                    <h3><?php esc_html_e('Widget Selection', 'quickswap'); ?></h3>
                    <p class="description">
                        <?php esc_html_e('Choose which widgets to display on the modern dashboard.', 'quickswap'); ?>
                    </p>

                    <table class="form-table">
                        <tr>
                            <th scope="row"><?php esc_html_e('Available Widgets', 'quickswap'); ?></th>
                            <td>
                                <fieldset>
                                    <legend class="screen-reader-text"><?php esc_html_e('Available Widgets', 'quickswap'); ?></legend>
                                    <?php foreach (self::get_widget_info() as $widget_id => $widget_info) : ?>
                                        <label class="quickswap-widget-label">
                                            <input
                                                type="checkbox"
                                                name="dashboard_widgets[<?php echo esc_attr($widget_id); ?>]"
                                                value="1"
                                                <?php checked(!empty($settings['dashboard_widgets'][$widget_id])); ?>
                                            >
                                            <span class="quickswap-widget-name"><?php echo esc_html($widget_info['title']); ?></span>
                                            <?php if (!empty($widget_info['description'])) : ?>
                                                <span class="description">— <?php echo esc_html($widget_info['description']); ?></span>
                                            <?php endif; ?>
                                        </label>
                                        <br>
                                    <?php endforeach; ?>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="quickswap-settings-section">
                    <h3><?php esc_html_e('Content Sources', 'quickswap'); ?></h3>
                    <p class="description">
                        <?php esc_html_e('Configure which content types and user roles are displayed in dashboard widgets.', 'quickswap'); ?>
                    </p>

                    <table class="form-table">
                        <tr>
                            <th scope="row"><?php esc_html_e('Project Post Types', 'quickswap'); ?></th>
                            <td>
                                <fieldset>
                                    <legend class="screen-reader-text"><?php esc_html_e('Project Post Types', 'quickswap'); ?></legend>
                                    <?php foreach ($post_types as $post_type) : ?>
                                        <?php if (in_array($post_type->name, array('attachment', 'revision', 'nav_menu_item'), true)) {
                                            continue;
                                        } ?>
                                        <label class="quickswap-post-type-label">
                                            <input
                                                type="checkbox"
                                                name="project_post_types[]"
                                                value="<?php echo esc_attr($post_type->name); ?>"
                                                <?php checked(in_array($post_type->name, $settings['project_post_types'], true)); ?>
                                            >
                                            <span><?php echo esc_html($post_type->labels->name); ?></span>
                                            <span class="description">(<?php echo esc_html($post_type->name); ?>)</span>
                                        </label>
                                        <br>
                                    <?php endforeach; ?>
                                </fieldset>
                                <p class="description">
                                    <?php esc_html_e('Select which post types should appear in the Projects Overview widget.', 'quickswap'); ?>
                                </p>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row"><?php esc_html_e('Team Member Roles', 'quickswap'); ?></th>
                            <td>
                                <fieldset>
                                    <legend class="screen-reader-text"><?php esc_html_e('Team Member Roles', 'quickswap'); ?></legend>
                                    <?php foreach ($roles as $role_name => $role_info) : ?>
                                        <label class="quickswap-role-label">
                                            <input
                                                type="checkbox"
                                                name="team_roles[]"
                                                value="<?php echo esc_attr($role_name); ?>"
                                                <?php checked(in_array($role_name, $settings['team_roles'], true)); ?>
                                            >
                                            <span><?php echo esc_html($role_info['name']); ?></span>
                                        </label>
                                        <br>
                                    <?php endforeach; ?>
                                </fieldset>
                                <p class="description">
                                    <?php esc_html_e('Select which user roles should appear in the Team Members widget.', 'quickswap'); ?>
                                </p>
                            </td>
                        </tr>
                    </table>
                </div>

                <?php do_action('quickswap_dashboard_settings_after_fields'); ?>

                <div class="quickswap-settings-actions">
                    <?php submit_button(__('Save Dashboard Settings', 'quickswap'), 'primary', 'submit', false); ?>
                </div>
            </form>
        </div>

        <style>
            .quickswap-dashboard-settings .quickswap-widget-label,
            .quickswap-dashboard-settings .quickswap-post-type-label,
            .quickswap-dashboard-settings .quickswap-role-label {
                display: inline-block;
                margin-right: 15px;
                margin-bottom: 8px;
            }

            .quickswap-dashboard-settings .quickswap-widget-name {
                font-weight: 500;
            }

            .quickswap-dashboard-settings .quickswap-settings-section {
                margin-bottom: 30px;
                padding: 20px;
                background: #fff;
                border: 1px solid #c3c4c7;
                box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
            }

            .quickswap-dashboard-settings .quickswap-settings-section h3 {
                margin-top: 0;
                padding-bottom: 10px;
                border-bottom: 1px solid #ddd;
            }

            .quickswap-dashboard-settings .quickswap-settings-actions {
                margin-top: 20px;
                text-align: right;
            }

            .quickswap-dashboard-settings .description {
                color: #646970;
                font-style: italic;
            }
        </style>
        <?php
    }

    /**
     * Get widget information
     */
    private static function get_widget_info() {
        return array(
            'projects_overview' => array(
                'title' => __('Projects Overview', 'quickswap'),
                'description' => __('Display recent posts with progress tracking', 'quickswap'),
            ),
            'team_members' => array(
                'title' => __('Team Members', 'quickswap'),
                'description' => __('Show team members with their task counts', 'quickswap'),
            ),
            'activity_log' => array(
                'title' => __('Activity Log', 'quickswap'),
                'description' => __('Timeline of recent posts and comments', 'quickswap'),
            ),
            'my_tasks' => array(
                'title' => __('My Tasks', 'quickswap'),
                'description' => __('Personal task list with priorities', 'quickswap'),
            ),
            'progress_chart' => array(
                'title' => __('Task Progress', 'quickswap'),
                'description' => __('Visual chart of content statistics', 'quickswap'),
            ),
            'budget_overview' => array(
                'title' => __('Budget Overview', 'quickswap'),
                'description' => __('Budget tracking and category breakdowns', 'quickswap'),
            ),
            'upcoming_meetings' => array(
                'title' => __('Upcoming Meetings', 'quickswap'),
                'description' => __('Schedule of upcoming meetings and events', 'quickswap'),
            ),
        );
    }

    /**
     * Save settings
     */
    public static function save_settings() {
        // Verify nonce
        if (!isset($_POST['quickswap_dashboard_nonce']) ||
            !wp_verify_nonce($_POST['quickswap_dashboard_nonce'], 'quickswap_dashboard_settings_nonce')) {
            wp_die(__('Security check failed.', 'quickswap'));
        }

        // Check permissions
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have permission to save these settings.', 'quickswap'));
        }

        // Sanitize and save
        $input = array(
            'enable_modern_dashboard' => !empty($_POST['enable_modern_dashboard']),
            'dashboard_widgets' => $_POST['dashboard_widgets'] ?? array(),
            'project_post_types' => $_POST['project_post_types'] ?? array(),
            'team_roles' => $_POST['team_roles'] ?? array(),
        );

        $sanitized = self::sanitize_settings($input);
        update_option(self::SETTINGS_KEY, $sanitized);

        // Clear transients
        $transients = array(
            'quickswap_dashboard_projects_overview_' . get_current_user_id(),
            'quickswap_dashboard_team_members_' . get_current_user_id(),
            'quickswap_dashboard_activity_log_' . get_current_user_id(),
            'quickswap_dashboard_my_tasks_' . get_current_user_id(),
            'quickswap_dashboard_progress_chart_' . get_current_user_id(),
            'quickswap_dashboard_budget_overview_' . get_current_user_id(),
            'quickswap_dashboard_upcoming_meetings_' . get_current_user_id(),
        );

        foreach ($transients as $transient) {
            delete_transient($transient);
        }

        // Redirect back to settings page with success message
        $redirect_url = add_query_arg(
            array(
                'page' => 'quickswap-settings',
                'tab' => 'dashboard',
                'settings-updated' => 'true',
            ),
            admin_url('options-general.php')
        );

        wp_redirect($redirect_url);
        exit;
    }

    /**
     * Add settings updated notice
     */
    public static function admin_notices() {
        if (isset($_GET['settings-updated']) && $_GET['settings-updated'] === 'true' &&
            isset($_GET['tab']) && $_GET['tab'] === 'dashboard') {
            ?>
            <div class="notice notice-success is-dismissible">
                <p><?php esc_html_e('Dashboard settings saved successfully.', 'quickswap'); ?></p>
            </div>
            <?php
        }
    }
}
