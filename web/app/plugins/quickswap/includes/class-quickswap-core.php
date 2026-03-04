<?php
/**
 * QuickSwap Core Class
 *
 * Handles core functionality: assets loading, modal rendering, keyboard shortcuts
 *
 * @package QuickSwap
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

class QuickSwap_Core {

    /**
     * Initialize core functionality
     */
    public static function init() {
        add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueue_admin_assets'));
        add_action('admin_footer', array(__CLASS__, 'render_modal'));
        add_action('wp_enqueue_scripts', array(__CLASS__, 'enqueue_frontend_assets'));
        add_action('wp_footer', array(__CLASS__, 'render_modal'));
    }

    /**
     * Enqueue admin assets
     */
    public static function enqueue_admin_assets($hook) {
        // Check if user has access
        if (!current_user_can('quickswap_use_search')) {
            return;
        }

        $settings = get_option('quickswap_settings', array());
        $keyboard_shortcut = $settings['keyboard_shortcut'] ?? 'cmd+k';

        // Enqueue CSS
        wp_enqueue_style(
            'quickswap',
            QUICKSWAP_PLUGIN_URL . 'assets/css/quickswap.css',
            array(),
            QUICKSWAP_VERSION
        );

        wp_enqueue_style(
            'quickswap-admin',
            QUICKSWAP_PLUGIN_URL . 'assets/css/quickswap-admin.css',
            array('quickswap'),
            QUICKSWAP_VERSION
        );

        // Enqueue JavaScript
        wp_enqueue_script(
            'quickswap-fuzzy',
            QUICKSWAP_PLUGIN_URL . 'assets/js/quickswap-fuzzy.js',
            array(),
            QUICKSWAP_VERSION,
            true
        );

        wp_enqueue_script(
            'quickswap-keyboard',
            QUICKSWAP_PLUGIN_URL . 'assets/js/quickswap-keyboard.js',
            array('quickswap-fuzzy'),
            QUICKSWAP_VERSION,
            true
        );

        wp_enqueue_script(
            'quickswap-search',
            QUICKSWAP_PLUGIN_URL . 'assets/js/quickswap-search.js',
            array('quickswap-keyboard'),
            QUICKSWAP_VERSION,
            true
        );

        wp_enqueue_script(
            'quickswap',
            QUICKSWAP_PLUGIN_URL . 'assets/js/quickswap.js',
            array('quickswap-search', 'jquery'),
            QUICKSWAP_VERSION,
            true
        );

        // Localize script
        wp_localize_script('quickswap', 'quickswapData', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('quickswap_search_nonce'),
            'restUrl' => rest_url('quickswap/v1/'),
            'settings' => array(
                'keyboardShortcut' => $keyboard_shortcut,
                'maxResults' => intval($settings['max_results'] ?? 10),
                'enableFuzzy' => !empty($settings['enable_fuzzy']),
                'fuzzyThreshold' => intval($settings['fuzzy_threshold'] ?? 70),
            ),
            'i18n' => array(
                'searchPlaceholder' => __('Search anything...', 'quickswap'),
                'noResults' => __('No results found', 'quickswap'),
                'loading' => __('Loading...', 'quickswap'),
                'error' => __('An error occurred. Please try again.', 'quickswap'),
                'open' => __('Open', 'quickswap'),
                'edit' => __('Edit', 'quickswap'),
                'view' => __('View', 'quickswap'),
                'activate' => __('Activate', 'quickswap'),
                'deactivate' => __('Deactivate', 'quickswap'),
                'customize' => __('Customize', 'quickswap'),
                'settings' => __('Settings', 'quickswap'),
            ),
        ));
    }

    /**
     * Enqueue frontend assets
     */
    public static function enqueue_frontend_assets() {
        $settings = get_option('quickswap_settings', array());

        // Check if frontend search is enabled
        if (empty($settings['enable_frontend'])) {
            return;
        }

        // Check if user has access
        if (!current_user_can('quickswap_use_search')) {
            return;
        }

        // Enqueue CSS
        wp_enqueue_style(
            'quickswap',
            QUICKSWAP_PLUGIN_URL . 'assets/css/quickswap.css',
            array(),
            QUICKSWAP_VERSION
        );

        // Enqueue JavaScript
        wp_enqueue_script(
            'quickswap-fuzzy',
            QUICKSWAP_PLUGIN_URL . 'assets/js/quickswap-fuzzy.js',
            array(),
            QUICKSWAP_VERSION,
            true
        );

        wp_enqueue_script(
            'quickswap-keyboard',
            QUICKSWAP_PLUGIN_URL . 'assets/js/quickswap-keyboard.js',
            array('quickswap-fuzzy'),
            QUICKSWAP_VERSION,
            true
        );

        wp_enqueue_script(
            'quickswap-search',
            QUICKSWAP_PLUGIN_URL . 'assets/js/quickswap-search.js',
            array('quickswap-keyboard'),
            QUICKSWAP_VERSION,
            true
        );

        wp_enqueue_script(
            'quickswap',
            QUICKSWAP_PLUGIN_URL . 'assets/js/quickswap.js',
            array('quickswap-search', 'jquery'),
            QUICKSWAP_VERSION,
            true
        );

        // Localize script
        wp_localize_script('quickswap', 'quickswapData', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('quickswap_search_nonce'),
            'isFrontend' => true,
            'settings' => array(
                'maxResults' => intval($settings['max_results'] ?? 10),
                'enableFuzzy' => !empty($settings['enable_fuzzy']),
                'fuzzyThreshold' => intval($settings['fuzzy_threshold'] ?? 70),
            ),
            'i18n' => array(
                'searchPlaceholder' => __('Search anything...', 'quickswap'),
                'noResults' => __('No results found', 'quickswap'),
                'loading' => __('Loading...', 'quickswap'),
                'error' => __('An error occurred. Please try again.', 'quickswap'),
            ),
        ));
    }

    /**
     * Render the search modal
     */
    public static function render_modal() {
        // Check if user has access
        if (!current_user_can('quickswap_use_search')) {
            return;
        }

        // Check if this is the frontend and frontend search is disabled
        if (!is_admin()) {
            $settings = get_option('quickswap_settings', array());
            if (empty($settings['enable_frontend'])) {
                return;
            }
        }

        $settings = get_option('quickswap_settings', array());
        $keyboard_shortcut = self::get_keyboard_shortcut_display($settings['keyboard_shortcut'] ?? 'cmd+k');

        ?>
        <div id="quickswap-modal" class="quickswap-modal" aria-hidden="true" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e('Quick Search', 'quickswap'); ?>">
            <div class="quickswap-overlay" tabindex="-1"></div>

            <div class="quickswap-container">
                <div class="quickswap-header">
                    <div class="quickswap-search-wrapper">
                        <svg class="quickswap-icon-search" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M9 17A8 8 0 1 0 9 1a8 8 0 0 0 0 16zM19 19l-4.35-4.35" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <input
                            type="text"
                            id="quickswap-input"
                            class="quickswap-input"
                            placeholder="<?php esc_attr_e('Search anything...', 'quickswap'); ?>"
                            autocomplete="off"
                            aria-label="<?php esc_attr_e('Search', 'quickswap'); ?>"
                            aria-autocomplete="list"
                            aria-controls="quickswap-results"
                            aria-expanded="false"
                        />
                        <div class="quickswap-shortcut"><?php echo esc_html($keyboard_shortcut); ?></div>
                    </div>
                    <button type="button" class="quickswap-close" aria-label="<?php esc_attr_e('Close', 'quickswap'); ?>" title="<?php esc_attr_e('Press ESC to close', 'quickswap'); ?>">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M4 4l12 12M4 16L16 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>

                <div id="quickswap-results" class="quickswap-results" role="listbox" aria-label="<?php esc_attr_e('Search results', 'quickswap'); ?>">
                    <!-- Results will be dynamically inserted here -->
                </div>

                <div class="quickswap-footer">
                    <div class="quickswap-hints">
                        <span class="quickswap-hint">
                            <kbd>↑</kbd><kbd>↓</kbd> <?php esc_html_e('Navigate', 'quickswap'); ?>
                        </span>
                        <span class="quickswap-hint">
                            <kbd>Enter</kbd> <?php esc_html_e('Open', 'quickswap'); ?>
                        </span>
                        <span class="quickswap-hint">
                            <kbd>Esc</kbd> <?php esc_html_e('Close', 'quickswap'); ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Get keyboard shortcut display text
     */
    private static function get_keyboard_shortcut_display($shortcut) {
        $shortcuts = array(
            'cmd+k' => self::is_macintosh() ? '⌘K' : 'Ctrl+K',
            'ctrl+shift+k' => 'Ctrl+Shift+K',
            'ctrl+space' => 'Ctrl+Space',
            'alt+shift+s' => 'Alt+Shift+S',
        );

        return $shortcuts[$shortcut] ?? (self::is_macintosh() ? '⌘K' : 'Ctrl+K');
    }

    /**
     * Check if current OS is Mac
     */
    private static function is_macintosh() {
        return stripos(PHP_OS, 'DARWIN') !== false || stripos(PHP_OS, 'MAC') !== false;
    }
}
