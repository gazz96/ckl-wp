<?php
/**
 * QuickSwap Admin Branding
 *
 * Core branding functionality - handles WordPress hooks for applying customizations
 *
 * @package QuickSwap
 * @since 1.1.0
 */

defined('ABSPATH') || exit;

class QuickSwap_Admin_Branding {

    /**
     * Initialize admin branding
     *
     * Hooks are always registered - each callback checks if branding is enabled.
     * This allows branding to be enabled/disabled without needing to re-register hooks.
     */
    public static function init() {
        // Admin area hooks
        add_action('admin_head', array(__CLASS__, 'admin_custom_styles'));
        add_action('admin_footer_text', array(__CLASS__, 'custom_footer_text'));
        add_action('update_footer', array(__CLASS__, 'custom_footer_version'), 10);
        add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueue_branding_assets'));
        add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueue_tailwind_assets'));
        add_filter('admin_body_class', array(__CLASS__, 'add_admin_body_classes'));

        // Login page hooks
        add_action('login_head', array(__CLASS__, 'login_custom_styles'));
        add_filter('login_headerurl', array(__CLASS__, 'custom_login_url'));
        add_filter('login_headertitle', array(__CLASS__, 'custom_login_title'));
        add_filter('login_message', array(__CLASS__, 'custom_login_message'));
    }

    /**
     * Check if branding is enabled
     */
    private static function is_branding_enabled() {
        $settings = get_option('quickswap_branding_settings', array());
        return !empty($settings['enable_branding']);
    }

    /**
     * Get branding settings
     */
    private static function get_settings() {
        return get_option('quickswap_branding_settings', array());
    }

    /**
     * Get image URL by attachment ID
     */
    private static function get_image_url($image_id, $size = 'full') {
        if (empty($image_id)) {
            return '';
        }

        $image_url = wp_get_attachment_image_url($image_id, $size);
        return $image_url ? $image_url : '';
    }

    /**
     * Enqueue branding assets
     */
    public static function enqueue_branding_assets() {
        if (!self::is_branding_enabled()) {
            return;
        }

        wp_enqueue_style('quickswap-branding', QUICKSWAP_PLUGIN_URL . 'assets/css/quickswap-branding.css', array(), QUICKSWAP_VERSION);
    }

    /**
     * Output admin area custom styles
     */
    public static function admin_custom_styles() {
        if (!self::is_branding_enabled()) {
            return;
        }

        $settings = self::get_settings();
        $custom_css = '';

        // Admin logo customization
        $admin_logo_id = !empty($settings['admin_logo_id']) ? intval($settings['admin_logo_id']) : 0;
        if ($admin_logo_id) {
            $admin_logo_url = self::get_image_url($admin_logo_id);
            if ($admin_logo_url) {
                $custom_css .= '
                    #wpadminbar #wp-admin-bar-site-icon>.ab-item:before {
                        content: "" !important;
                        background-image: url("' . esc_url($admin_logo_url) . '") !important;
                        background-size: contain;
                        background-repeat: no-repeat;
                        background-position: center;
                    }
                ';
            }
        }

        // Custom colors
        if (!empty($settings['custom_colors_enabled'])) {
            $primary_color = !empty($settings['admin_primary_color']) ? $settings['admin_primary_color'] : '#2271b1';
            $secondary_color = !empty($settings['admin_secondary_color']) ? $settings['admin_secondary_color'] : '#135e96';
            $bg_color = !empty($settings['admin_bg_color']) ? $settings['admin_bg_color'] : '#f0f0f1';

            $custom_css .= '
                :root {
                    --quickswap-primary-color: ' . esc_attr($primary_color) . ';
                    --quickswap-secondary-color: ' . esc_attr($secondary_color) . ';
                    --quickswap-bg-color: ' . esc_attr($bg_color) . ';
                }

                /* Primary buttons */
                .button-primary,
                .page-title-action {
                    background-color: ' . esc_attr($primary_color) . ';
                    border-color: ' . esc_attr($primary_color) . ';
                }

                .button-primary:hover,
                .button-primary:focus,
                .page-title-action:hover,
                .page-title-action:focus {
                    background-color: ' . esc_attr($secondary_color) . ';
                    border-color: ' . esc_attr($secondary_color) . ';
                }

                /* Links */
                .wrap a,
                .wrap h1 a,
                .update-nag a,
                .notice a {
                    color: ' . esc_attr($primary_color) . ';
                }

                .wrap a:hover,
                .wrap a:focus,
                .update-nag a:hover,
                .notice a:hover {
                    color: ' . esc_attr($secondary_color) . ';
                }

                /* Menu active states */
                #adminmenu li.current a.menu-top,
                #adminmenu li.wp-has-current-submenu a.wp-has-current-submenu,
                #adminmenu li.wp-has-current-submenu .wp-submenu-item.current {
                    background-color: ' . esc_attr($primary_color) . ';
                }

                #adminmenu li.menu-top:hover,
                #adminmenu li.opensub > a.menu-top {
                    background-color: ' . esc_attr($bg_color) . ';
                }

                /* Admin bar */
                #wpadminbar .quicklinks .ab-item:hover,
                #wpadminbar .quicklinks .ab-item:focus,
                #wpadminbar .quicklinks > ul > li > a:hover,
                #wpadminbar .quicklinks > ul > li > a:focus {
                    background-color: ' . esc_attr($secondary_color) . ';
                }

                /* Page title */
                .wrap h1 {
                    color: ' . esc_attr($primary_color) . ';
                }

                /* Tabs */
                .nav-tab-wrapper .nav-tab.nav-tab-active,
                .nav-tab-wrapper .nav-tab:hover {
                    color: ' . esc_attr($primary_color) . ';
                    border-bottom-color: ' . esc_attr($primary_color) . ';
                }

                /* Background */
                body.wp-admin {
                    background-color: ' . esc_attr($bg_color) . ';
                }
            ';
        }

        if (!empty($custom_css)) {
            echo '<style type="text/css" id="quickswap-admin-branding">' . $custom_css . '</style>';
        }
    }

    /**
     * Output login page custom styles
     */
    public static function login_custom_styles() {
        if (!self::is_branding_enabled()) {
            return;
        }

        $settings = self::get_settings();
        $custom_css = '';

        // Login logo customization
        $login_logo_id = !empty($settings['login_logo_id']) ? intval($settings['login_logo_id']) : 0;
        $logo_width = !empty($settings['login_logo_width']) ? intval($settings['login_logo_width']) : 80;
        $logo_height = !empty($settings['login_logo_height']) ? intval($settings['login_logo_height']) : 80;

        if ($login_logo_id) {
            $login_logo_url = self::get_image_url($login_logo_id);
            if ($login_logo_url) {
                $custom_css .= '
                    .login h1 a {
                        background-image: url("' . esc_url($login_logo_url) . '") !important;
                        background-size: contain;
                        background-repeat: no-repeat;
                        background-position: center;
                        width: ' . esc_attr($logo_width) . 'px;
                        height: ' . esc_attr($logo_height) . 'px;
                    }
                ';
            }
        }

        // Custom colors for login page
        if (!empty($settings['custom_colors_enabled'])) {
            $primary_color = !empty($settings['admin_primary_color']) ? $settings['admin_primary_color'] : '#2271b1';

            $custom_css .= '
                .login #backtoblog a,
                .login #nav a {
                    color: ' . esc_attr($primary_color) . ';
                }

                .login #backtoblog a:hover,
                .login #nav a:hover {
                    color: ' . esc_attr($primary_color) . ';
                }

                .login .button-primary {
                    background-color: ' . esc_attr($primary_color) . ';
                    border-color: ' . esc_attr($primary_color) . ';
                }

                .login .button-primary:hover,
                .login .button-primary:focus {
                    background-color: ' . esc_attr($primary_color) . ';
                    border-color: ' . esc_attr($primary_color) . ';
                }
            ';
        }

        // Login background
        $bg_color = !empty($settings['login_bg_color']) ? $settings['login_bg_color'] : '#ffffff';
        $bg_image_id = !empty($settings['login_bg_image_id']) ? intval($settings['login_bg_image_id']) : 0;

        $custom_css .= '
            body.login {
                background-color: ' . esc_attr($bg_color) . ';
        ';

        if ($bg_image_id) {
            $bg_image_url = self::get_image_url($bg_image_id);
            if ($bg_image_url) {
                $custom_css .= '
                    background-image: url("' . esc_url($bg_image_url) . '");
                    background-size: cover;
                    background-position: center;
                    background-repeat: no-repeat;
                ';
            }
        }

        $custom_css .= '
            }
        ';

        if (!empty($custom_css)) {
            echo '<style type="text/css" id="quickswap-login-branding">' . $custom_css . '</style>';
        }
    }

    /**
     * Custom login logo URL
     */
    public static function custom_login_url($url) {
        $settings = self::get_settings();
        $custom_url = !empty($settings['login_logo_url']) ? $settings['login_logo_url'] : '';

        if (!empty($custom_url)) {
            return esc_url($custom_url);
        }

        return $url;
    }

    /**
     * Custom login logo title
     */
    public static function custom_login_title($title) {
        $settings = self::get_settings();
        $custom_title = !empty($settings['login_logo_title']) ? $settings['login_logo_title'] : '';

        if (!empty($custom_title)) {
            return esc_attr($custom_title);
        }

        return $title;
    }

    /**
     * Custom login welcome message
     */
    public static function custom_login_message($message) {
        $settings = self::get_settings();
        $welcome_message = !empty($settings['login_welcome_message']) ? $settings['login_welcome_message'] : '';

        if (!empty($welcome_message)) {
            $allowed_html = array(
                'p' => array(),
                'a' => array('href' => array(), 'title' => array()),
                'strong' => array(),
                'em' => array(),
                'br' => array(),
                'span' => array('class' => array(), 'style' => array()),
            );
            $message .= '<div class="quickswap-welcome-message">' . wp_kses($welcome_message, $allowed_html) . '</div>';
        }

        return $message;
    }

    /**
     * Custom left footer text
     */
    public static function custom_footer_text($text) {
        $settings = self::get_settings();
        $custom_text = !empty($settings['footer_text_left']) ? $settings['footer_text_left'] : '';

        if (!empty($custom_text)) {
            return wp_kses($custom_text, array(
                'a' => array('href' => array(), 'title' => array(), 'target' => array()),
                'strong' => array(),
                'em' => array(),
                'span' => array('class' => array()),
            ));
        }

        return $text;
    }

    /**
     * Custom right footer text (version)
     */
    public static function custom_footer_version($text) {
        $settings = self::get_settings();
        $custom_text = !empty($settings['footer_text_right']) ? $settings['footer_text_right'] : '';

        if (!empty($custom_text)) {
            return wp_kses($custom_text, array(
                'a' => array('href' => array(), 'title' => array(), 'target' => array()),
                'strong' => array(),
                'em' => array(),
                'span' => array('class' => array()),
            ));
        }

        return $text;
    }

    /**
     * Enqueue Tailwind CSS assets for all admin pages
     *
     * Loads Tailwind-inspired styles globally across wp-admin when branding or modern dashboard is enabled.
     * Also attempts to load the theme's compiled Tailwind for full utility support.
     *
     * @since 1.3.0
     */
    public static function enqueue_tailwind_assets() {
        // Only load if branding or modern dashboard is enabled
        $branding_settings = get_option('quickswap_branding_settings', array());
        $dashboard_settings = get_option('quickswap_dashboard_settings', array());

        $branding_enabled = !empty($branding_settings['enable_branding']);
        $dashboard_enabled = !empty($dashboard_settings['enable_modern_dashboard']);

        if (!$branding_enabled && !$dashboard_enabled) {
            return;
        }

        // Enqueue admin Tailwind styles
        wp_enqueue_style(
            'quickswap-admin-tailwind',
            QUICKSWAP_PLUGIN_URL . 'assets/css/quickswap-admin-tailwind.css',
            array(),
            QUICKSWAP_VERSION
        );

        // Also load theme's Tailwind for full utility classes
        $theme_css = get_template_directory() . '/assets/css/main.css';
        if (file_exists($theme_css)) {
            wp_enqueue_style(
                'quickswap-theme-tailwind',
                get_template_directory_uri() . '/assets/css/main.css',
                array(),
                QUICKSWAP_VERSION
            );
        }
    }

    /**
     * Add admin body classes for modern styling
     *
     * Adds the quickswap-modern-admin body class when branding or modern dashboard is enabled.
     * This class enables WordPress core style overrides.
     *
     * @since 1.3.0
     * @param string $classes Existing body classes
     * @return string Modified body classes
     */
    public static function add_admin_body_classes($classes) {
        $branding_settings = get_option('quickswap_branding_settings', array());
        $dashboard_settings = get_option('quickswap_dashboard_settings', array());

        if (!empty($branding_settings['enable_branding']) || !empty($dashboard_settings['enable_modern_dashboard'])) {
            $classes .= ' quickswap-modern-admin';
        }
        return $classes;
    }
}
