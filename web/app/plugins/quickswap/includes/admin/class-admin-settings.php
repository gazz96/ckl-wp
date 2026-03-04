<?php
/**
 * QuickSwap Admin Settings
 *
 * Settings page and management
 *
 * @package QuickSwap
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

class QuickSwap_Admin_Settings {

    /**
     * Initialize admin settings
     */
    public static function init() {
        add_action('admin_menu', array(__CLASS__, 'add_menu_page'));
        add_action('admin_init', array(__CLASS__, 'register_settings'));
        add_action('admin_init', array(__CLASS__, 'handle_role_save'));
        add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueue_scripts'));
    }

    /**
     * Add settings menu page
     */
    public static function add_menu_page() {
        add_options_page(
            __('QuickSwap Settings', 'quickswap'),
            __('QuickSwap', 'quickswap'),
            'manage_options',
            'quickswap-settings',
            array(__CLASS__, 'render_settings_page')
        );
    }

    /**
     * Get current active tab
     */
    private static function get_current_tab() {
        return isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'general';
    }

    /**
     * Get available tabs
     */
    private static function get_tabs() {
        return array(
            'general' => __('General', 'quickswap'),
            'branding' => __('Admin Branding', 'quickswap'),
            'dashboard' => __('Modern Dashboard', 'quickswap'),
        );
    }

    /**
     * Register settings
     */
    public static function register_settings() {
        register_setting('quickswap_settings', 'quickswap_settings', array(
            'sanitize_callback' => array(__CLASS__, 'sanitize_settings'),
        ));

        // General settings section
        add_settings_section(
            'quickswap_general',
            __('General Settings', 'quickswap'),
            array(__CLASS__, 'render_general_section'),
            'quickswap-settings'
        );

        // Keyboard shortcut
        add_settings_field(
            'keyboard_shortcut',
            __('Keyboard Shortcut', 'quickswap'),
            array(__CLASS__, 'render_keyboard_field'),
            'quickswap-settings',
            'quickswap_general'
        );

        // Max results
        add_settings_field(
            'max_results',
            __('Maximum Results', 'quickswap'),
            array(__CLASS__, 'render_max_results_field'),
            'quickswap-settings',
            'quickswap_general'
        );

        // Fuzzy search settings
        add_settings_field(
            'enable_fuzzy',
            __('Enable Fuzzy Search', 'quickswap'),
            array(__CLASS__, 'render_fuzzy_field'),
            'quickswap-settings',
            'quickswap_general'
        );

        // Fuzzy threshold
        add_settings_field(
            'fuzzy_threshold',
            __('Fuzzy Match Threshold', 'quickswap'),
            array(__CLASS__, 'render_fuzzy_threshold_field'),
            'quickswap-settings',
            'quickswap_general'
        );

        // Frontend search
        add_settings_field(
            'enable_frontend',
            __('Enable Frontend Search', 'quickswap'),
            array(__CLASS__, 'render_frontend_field'),
            'quickswap-settings',
            'quickswap_general'
        );

        // Access control section
        add_settings_section(
            'quickswap_access',
            __('Access Control', 'quickswap'),
            array(__CLASS__, 'render_access_section'),
            'quickswap-settings'
        );

        // Role capabilities
        foreach (get_editable_roles() as $role_name => $role_info) {
            add_settings_field(
                'role_' . $role_name,
                $role_info['name'],
                function() use ($role_name) {
                    self::render_role_field($role_name);
                },
                'quickswap-settings',
                'quickswap_access'
            );
        }
    }

    /**
     * Sanitize settings
     */
    public static function sanitize_settings($input) {
        $sanitized = array();
        $current = get_option('quickswap_settings', array());

        // Keyboard shortcut
        $sanitized['keyboard_shortcut'] = sanitize_key($input['keyboard_shortcut'] ?? 'cmd+k');
        if (!in_array($sanitized['keyboard_shortcut'], array('cmd+k', 'ctrl+shift+k', 'ctrl+space', 'alt+shift+s'))) {
            $sanitized['keyboard_shortcut'] = 'cmd+k';
        }

        // Max results
        $sanitized['max_results'] = max(5, min(50, intval($input['max_results'] ?? 10)));

        // Fuzzy search
        $sanitized['enable_fuzzy'] = !empty($input['enable_fuzzy']);

        // Fuzzy threshold
        $sanitized['fuzzy_threshold'] = max(50, min(100, intval($input['fuzzy_threshold'] ?? 70)));

        // Frontend search
        $sanitized['enable_frontend'] = !empty($input['enable_frontend']);

        return $sanitized;
    }

    /**
     * Handle role saving when settings are updated
     */
    public static function handle_role_save() {
        if (!isset($_POST['option_page']) || $_POST['option_page'] !== 'quickswap_settings') {
            return;
        }

        if (!current_user_can('manage_options')) {
            return;
        }

        // Check nonce
        check_admin_referer('quickswap-settings-options');

        // Save roles
        if (isset($_POST['quickswap_roles'])) {
            self::save_roles($_POST);
        }
    }

    /**
     * Save role capabilities
     */
    public static function save_roles($input) {
        if (!isset($input['quickswap_roles']) || !is_array($input['quickswap_roles'])) {
            return;
        }

        // Get all WordPress roles
        $wp_roles = wp_roles();
        $role_names = $wp_roles->get_names();

        // Define capability mappings
        $capability_map = array(
            'search' => 'quickswap_use_search',
            'admin' => 'quickswap_admin_pages',
            'plugins' => 'quickswap_manage_plugins',
            'themes' => 'quickswap_manage_themes',
        );

        foreach ($role_names as $role_key => $role_name) {
            $role = get_role($role_key);

            if (!$role) {
                continue;
            }

            // Process each capability for this role
            foreach ($capability_map as $checkbox_key => $capability) {
                $is_checked = isset($input['quickswap_roles'][$role_key][$checkbox_key]) && $input['quickswap_roles'][$role_key][$checkbox_key] === '1';

                if ($is_checked) {
                    $role->add_cap($capability);
                } else {
                    $role->remove_cap($capability);
                }
            }
        }
    }

    /**
     * Render settings page
     */
    public static function render_settings_page() {
        if (!current_user_can('manage_options')) {
            return;
        }

        $current_tab = self::get_current_tab();
        $tabs = self::get_tabs();
        $settings_url = admin_url('options-general.php?page=quickswap-settings');

        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

            <?php // Tab navigation ?>
            <nav class="nav-tab-wrapper wp-clearfix" style="margin-bottom: 20px;">
                <?php foreach ($tabs as $tab_slug => $tab_label): ?>
                    <a href="<?php echo esc_url(add_query_arg('tab', $tab_slug, $settings_url)); ?>"
                       class="nav-tab <?php echo $current_tab === $tab_slug ? 'nav-tab-active' : ''; ?>">
                        <?php echo esc_html($tab_label); ?>
                    </a>
                <?php endforeach; ?>
            </nav>

            <div class="quickswap-settings-wrapper">
                <div class="quickswap-settings-main">
                    <?php if ($current_tab === 'general'): ?>
                        <form action="options.php" method="post">
                            <?php
                            settings_fields('quickswap_settings');
                            do_settings_sections('quickswap-settings');
                            submit_button();
                            ?>
                        </form>
                    <?php elseif ($current_tab === 'branding'): ?>
                        <form action="options.php" method="post">
                            <?php
                            settings_fields('quickswap_branding_settings');
                            do_settings_sections('quickswap-branding');
                            submit_button();
                            ?>
                        </form>
                    <?php elseif ($current_tab === 'dashboard'): ?>
                        <?php do_action('quickswap_settings_render_dashboard_tab'); ?>
                    <?php endif; ?>
                </div>

                <?php if ($current_tab === 'general'): ?>
                    <div class="quickswap-settings-sidebar">
                        <div class="quickswap-card">
                            <h2><?php esc_html_e('Keyboard Shortcuts', 'quickswap'); ?></h2>
                            <table class="quickswap-shortcuts-table">
                                <tr>
                                    <td><kbd><?php echo is_macintosh() ? '⌘' : 'Ctrl'; ?> K</kbd></td>
                                    <td><?php esc_html_e('Open search', 'quickswap'); ?></td>
                                </tr>
                                <tr>
                                    <td><kbd>↑</kbd> <kbd>↓</kbd></td>
                                    <td><?php esc_html_e('Navigate results', 'quickswap'); ?></td>
                                </tr>
                                <tr>
                                    <td><kbd>Enter</kbd></td>
                                    <td><?php esc_html_e('Open selected', 'quickswap'); ?></td>
                                </tr>
                                <tr>
                                    <td><kbd>Esc</kbd></td>
                                    <td><?php esc_html_e('Close search', 'quickswap'); ?></td>
                                </tr>
                            </table>
                        </div>

                        <div class="quickswap-card">
                            <h2><?php esc_html_e('Search Operators', 'quickswap'); ?></h2>
                            <ul class="quickswap-operators-list">
                                <li><code>type:post</code> - <?php esc_html_e('Search posts only', 'quickswap'); ?></li>
                                <li><code>type:page</code> - <?php esc_html_e('Search pages only', 'quickswap'); ?></li>
                                <li><code>status:draft</code> - <?php esc_html_e('Search draft posts', 'quickswap'); ?></li>
                                <li><code>user:john</code> - <?php esc_html_e('Search by author', 'quickswap'); ?></li>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <style>
            .quickswap-settings-wrapper {
                display: flex;
                gap: 20px;
                margin-top: 20px;
            }
            .quickswap-settings-main {
                flex: 1;
                max-width: 800px;
            }
            .quickswap-settings-sidebar {
                width: 300px;
            }
            .quickswap-card {
                background: #fff;
                border: 1px solid #c3c4c7;
                padding: 15px;
                margin-bottom: 15px;
                border-radius: 4px;
            }
            .quickswap-card h2 {
                margin-top: 0;
                font-size: 16px;
            }
            .quickswap-shortcuts-table td {
                padding: 8px;
            }
            .quickswap-shortcuts-table kbd {
                background: #f0f0f1;
                border: 1px solid #c3c4c7;
                border-radius: 3px;
                padding: 2px 6px;
                font-family: monospace;
            }
            .quickswap-operators-list {
                margin: 0;
                padding-left: 20px;
            }
            .quickswap-operators-list li {
                margin-bottom: 5px;
            }
            .quickswap-operators-list code {
                background: #f0f0f1;
                padding: 2px 6px;
                border-radius: 3px;
            }
        </style>
        <?php
    }

    /**
     * Render general section
     */
    public static function render_general_section() {
        echo '<p>' . esc_html__('Configure general QuickSwap settings.', 'quickswap') . '</p>';
    }

    /**
     * Render keyboard shortcut field
     */
    public static function render_keyboard_field() {
        $settings = get_option('quickswap_settings', array());
        $value = $settings['keyboard_shortcut'] ?? 'cmd+k';
        ?>
        <select name="quickswap_settings[keyboard_shortcut]">
            <option value="cmd+k" <?php selected($value, 'cmd+k'); ?>>
                <?php echo is_macintosh() ? '⌘K' : 'Ctrl+K'; ?>
            </option>
            <option value="ctrl+shift+k" <?php selected($value, 'ctrl+shift+k'); ?>>
                Ctrl+Shift+K
            </option>
            <option value="ctrl+space" <?php selected($value, 'ctrl+space'); ?>>
                Ctrl+Space
            </option>
            <option value="alt+shift+s" <?php selected($value, 'alt+shift+s'); ?>>
                Alt+Shift+S
            </option>
        </select>
        <p class="description"><?php esc_html_e('Keyboard shortcut to open QuickSwap search.', 'quickswap'); ?></p>
        <?php
    }

    /**
     * Render max results field
     */
    public static function render_max_results_field() {
        $settings = get_option('quickswap_settings', array());
        $value = intval($settings['max_results'] ?? 10);
        ?>
        <input type="number" name="quickswap_settings[max_results]" value="<?php echo esc_attr($value); ?>" min="5" max="50">
        <p class="description"><?php esc_html_e('Maximum number of results to display per provider.', 'quickswap'); ?></p>
        <?php
    }

    /**
     * Render fuzzy search field
     */
    public static function render_fuzzy_field() {
        $settings = get_option('quickswap_settings', array());
        $value = !empty($settings['enable_fuzzy']);
        ?>
        <label>
            <input type="checkbox" name="quickswap_settings[enable_fuzzy]" value="1" <?php checked($value); ?>>
            <?php esc_html_e('Enable fuzzy matching for typo-tolerant search', 'quickswap'); ?>
        </label>
        <p class="description"><?php esc_html_e('Find results even with typos (e.g., "pst" finds "post").', 'quickswap'); ?></p>
        <?php
    }

    /**
     * Render fuzzy threshold field
     */
    public static function render_fuzzy_threshold_field() {
        $settings = get_option('quickswap_settings', array());
        $value = intval($settings['fuzzy_threshold'] ?? 70);
        ?>
        <input type="range" name="quickswap_settings[fuzzy_threshold]" value="<?php echo esc_attr($value); ?>" min="50" max="100" oninput="this.nextElementSibling.textContent = this.value + '%'">
        <span><?php echo esc_html($value); ?>%</span>
        <p class="description"><?php esc_html_e('Minimum similarity for fuzzy matches. Higher = more exact matches.', 'quickswap'); ?></p>
        <?php
    }

    /**
     * Render frontend search field
     */
    public static function render_frontend_field() {
        $settings = get_option('quickswap_settings', array());
        $value = !empty($settings['enable_frontend']);
        ?>
        <label>
            <input type="checkbox" name="quickswap_settings[enable_frontend]" value="1" <?php checked($value); ?>>
            <?php esc_html_e('Enable QuickSwap on the frontend', 'quickswap'); ?>
        </label>
        <p class="description"><?php esc_html_e('Allow logged-in users to search from the frontend site.', 'quickswap'); ?></p>
        <?php
    }

    /**
     * Render access section
     */
    public static function render_access_section() {
        echo '<p>' . esc_html__('Control which user roles can access QuickSwap features.', 'quickswap') . '</p>';
    }

    /**
     * Render role field
     */
    public static function render_role_field($role_name) {
        $role = get_role($role_name);

        if (!$role) {
            return;
        }

        $has_search = $role->has_cap('quickswap_use_search');
        $has_admin = $role->has_cap('quickswap_admin_pages');
        $has_plugins = $role->has_cap('quickswap_manage_plugins');
        $has_themes = $role->has_cap('quickswap_manage_themes');

        ?>
        <label>
            <input type="checkbox" name="quickswap_roles[<?php echo esc_attr($role_name); ?>][search]" value="1" <?php checked($has_search); ?>>
            <?php esc_html_e('Search', 'quickswap'); ?>
        </label>
        <label style="margin-left: 10px;">
            <input type="checkbox" name="quickswap_roles[<?php echo esc_attr($role_name); ?>][admin]" value="1" <?php checked($has_admin); ?>>
            <?php esc_html_e('Admin Pages', 'quickswap'); ?>
        </label>
        <label style="margin-left: 10px;">
            <input type="checkbox" name="quickswap_roles[<?php echo esc_attr($role_name); ?>][plugins]" value="1" <?php checked($has_plugins); ?>>
            <?php esc_html_e('Plugins', 'quickswap'); ?>
        </label>
        <label style="margin-left: 10px;">
            <input type="checkbox" name="quickswap_roles[<?php echo esc_attr($role_name); ?>][themes]" value="1" <?php checked($has_themes); ?>>
            <?php esc_html_e('Themes', 'quickswap'); ?>
        </label>
        <?php
    }

    /**
     * Enqueue admin scripts
     */
    public static function enqueue_scripts($hook) {
        if ('settings_page_quickswap-settings' !== $hook) {
            return;
        }

        wp_enqueue_style('quickswap-admin', QUICKSWAP_PLUGIN_URL . 'assets/css/quickswap-admin.css', array(), QUICKSWAP_VERSION);
    }
}

/**
 * Check if current OS is Mac
 */
function is_macintosh() {
    return stripos(PHP_OS, 'DARWIN') !== false || stripos(PHP_OS, 'MAC') !== false;
}
