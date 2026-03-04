<?php
/**
 * QuickSwap Admin Branding Settings
 *
 * Settings UI class - WordPress Settings API for branding options
 *
 * @package QuickSwap
 * @since 1.1.0
 */

defined('ABSPATH') || exit;

class QuickSwap_Admin_Branding_Settings {

    /**
     * Initialize branding settings
     */
    public static function init() {
        add_action('admin_init', array(__CLASS__, 'register_branding_settings'));
        add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueue_admin_scripts'));
    }

    /**
     * Register branding settings
     */
    public static function register_branding_settings() {
        register_setting('quickswap_branding_settings', 'quickswap_branding_settings', array(
            'sanitize_callback' => array(__CLASS__, 'sanitize_branding_settings'),
        ));

        // Main settings section
        add_settings_section(
            'quickswap_branding_main',
            __('Main Settings', 'quickswap'),
            array(__CLASS__, 'render_main_section'),
            'quickswap-branding'
        );

        // Enable branding toggle
        add_settings_field(
            'enable_branding',
            __('Enable Admin Branding', 'quickswap'),
            array(__CLASS__, 'render_enable_branding_field'),
            'quickswap-branding',
            'quickswap_branding_main'
        );

        // Logo settings section
        add_settings_section(
            'quickswap_branding_logos',
            __('Logo Customization', 'quickswap'),
            array(__CLASS__, 'render_logos_section'),
            'quickswap-branding'
        );

        // Admin logo
        add_settings_field(
            'admin_logo',
            __('Admin Area Logo', 'quickswap'),
            array(__CLASS__, 'render_admin_logo_field'),
            'quickswap-branding',
            'quickswap_branding_logos'
        );

        // Login logo
        add_settings_field(
            'login_logo',
            __('Login Page Logo', 'quickswap'),
            array(__CLASS__, 'render_login_logo_field'),
            'quickswap-branding',
            'quickswap_branding_logos'
        );

        // Login logo dimensions
        add_settings_field(
            'login_logo_dimensions',
            __('Login Logo Dimensions', 'quickswap'),
            array(__CLASS__, 'render_login_logo_dimensions_field'),
            'quickswap-branding',
            'quickswap_branding_logos'
        );

        // Color settings section
        add_settings_section(
            'quickswap_branding_colors',
            __('Color Scheme', 'quickswap'),
            array(__CLASS__, 'render_colors_section'),
            'quickswap-branding'
        );

        // Enable custom colors
        add_settings_field(
            'custom_colors_enabled',
            __('Enable Custom Colors', 'quickswap'),
            array(__CLASS__, 'render_custom_colors_enabled_field'),
            'quickswap-branding',
            'quickswap_branding_colors'
        );

        // Primary color
        add_settings_field(
            'admin_primary_color',
            __('Primary Color', 'quickswap'),
            array(__CLASS__, 'render_primary_color_field'),
            'quickswap-branding',
            'quickswap_branding_colors'
        );

        // Secondary color
        add_settings_field(
            'admin_secondary_color',
            __('Secondary Color', 'quickswap'),
            array(__CLASS__, 'render_secondary_color_field'),
            'quickswap-branding',
            'quickswap_branding_colors'
        );

        // Background color
        add_settings_field(
            'admin_bg_color',
            __('Background Color', 'quickswap'),
            array(__CLASS__, 'render_bg_color_field'),
            'quickswap-branding',
            'quickswap_branding_colors'
        );

        // Login page settings section
        add_settings_section(
            'quickswap_branding_login',
            __('Login Page Customization', 'quickswap'),
            array(__CLASS__, 'render_login_section'),
            'quickswap-branding'
        );

        // Login logo URL
        add_settings_field(
            'login_logo_url',
            __('Logo Link URL', 'quickswap'),
            array(__CLASS__, 'render_login_logo_url_field'),
            'quickswap-branding',
            'quickswap_branding_login'
        );

        // Login logo title
        add_settings_field(
            'login_logo_title',
            __('Logo Title/Tooltip', 'quickswap'),
            array(__CLASS__, 'render_login_logo_title_field'),
            'quickswap-branding',
            'quickswap_branding_login'
        );

        // Welcome message
        add_settings_field(
            'login_welcome_message',
            __('Welcome Message', 'quickswap'),
            array(__CLASS__, 'render_login_welcome_message_field'),
            'quickswap-branding',
            'quickswap_branding_login'
        );

        // Login background
        add_settings_field(
            'login_background',
            __('Login Background', 'quickswap'),
            array(__CLASS__, 'render_login_background_field'),
            'quickswap-branding',
            'quickswap_branding_login'
        );

        // Footer settings section
        add_settings_section(
            'quickswap_branding_footer',
            __('Footer Text', 'quickswap'),
            array(__CLASS__, 'render_footer_section'),
            'quickswap-branding'
        );

        // Left footer text
        add_settings_field(
            'footer_text_left',
            __('Left Footer Text', 'quickswap'),
            array(__CLASS__, 'render_footer_text_left_field'),
            'quickswap-branding',
            'quickswap_branding_footer'
        );

        // Right footer text
        add_settings_field(
            'footer_text_right',
            __('Right Footer Text', 'quickswap'),
            array(__CLASS__, 'render_footer_text_right_field'),
            'quickswap-branding',
            'quickswap_branding_footer'
        );
    }

    /**
     * Sanitize branding settings
     */
    public static function sanitize_branding_settings($input) {
        $sanitized = array();
        $current = get_option('quickswap_branding_settings', array());

        // Main toggle
        $sanitized['enable_branding'] = !empty($input['enable_branding']);

        // Admin logo
        $sanitized['admin_logo_id'] = !empty($input['admin_logo_id']) ? absint($input['admin_logo_id']) : 0;

        // Login logo
        $sanitized['login_logo_id'] = !empty($input['login_logo_id']) ? absint($input['login_logo_id']) : 0;

        // Login logo dimensions
        $sanitized['login_logo_width'] = isset($input['login_logo_width']) ? max(20, min(300, intval($input['login_logo_width']))) : 80;
        $sanitized['login_logo_height'] = isset($input['login_logo_height']) ? max(20, min(300, intval($input['login_logo_height']))) : 80;

        // Colors
        $sanitized['custom_colors_enabled'] = !empty($input['custom_colors_enabled']);
        $sanitized['admin_primary_color'] = self::sanitize_color($input['admin_primary_color'] ?? '#2271b1');
        $sanitized['admin_secondary_color'] = self::sanitize_color($input['admin_secondary_color'] ?? '#135e96');
        $sanitized['admin_bg_color'] = self::sanitize_color($input['admin_bg_color'] ?? '#f0f0f1');

        // Login page
        $sanitized['login_logo_url'] = !empty($input['login_logo_url']) ? esc_url_raw($input['login_logo_url']) : '';
        $sanitized['login_logo_title'] = !empty($input['login_logo_title']) ? sanitize_text_field($input['login_logo_title']) : '';
        $sanitized['login_welcome_message'] = !empty($input['login_welcome_message']) ? wp_kses_post($input['login_welcome_message']) : '';
        $sanitized['login_bg_image_id'] = !empty($input['login_bg_image_id']) ? absint($input['login_bg_image_id']) : 0;
        $sanitized['login_bg_color'] = self::sanitize_color($input['login_bg_color'] ?? '#ffffff');

        // Footer
        $sanitized['footer_text_left'] = !empty($input['footer_text_left']) ? wp_kses_post($input['footer_text_left']) : '';
        $sanitized['footer_text_right'] = !empty($input['footer_text_right']) ? wp_kses_post($input['footer_text_right']) : '';

        return $sanitized;
    }

    /**
     * Sanitize color value
     */
    private static function sanitize_color($color) {
        // Remove any spaces
        $color = str_replace(' ', '', $color);

        // Check if it's a valid hex color
        if (preg_match('/^#?([a-f0-9]{3}|[a-f0-9]{6})$/i', $color)) {
            return '#' . ltrim($color, '#');
        }

        return '#2271b1'; // Default fallback
    }

    /**
     * Render main section
     */
    public static function render_main_section() {
        echo '<p>' . esc_html__('Enable admin branding features to customize the WordPress admin appearance.', 'quickswap') . '</p>';
    }

    /**
     * Render enable branding field
     */
    public static function render_enable_branding_field() {
        $settings = get_option('quickswap_branding_settings', array());
        $value = !empty($settings['enable_branding']);
        ?>
        <label>
            <input type="checkbox" name="quickswap_branding_settings[enable_branding]" value="1" <?php checked($value); ?>>
            <?php esc_html_e('Enable all admin branding features', 'quickswap'); ?>
        </label>
        <p class="description"><?php esc_html_e('When enabled, all customizations below will be applied to the WordPress admin area.', 'quickswap'); ?></p>
        <?php
    }

    /**
     * Render logos section
     */
    public static function render_logos_section() {
        echo '<p>' . esc_html__('Upload custom logos for the admin area and login page.', 'quickswap') . '</p>';
    }

    /**
     * Render admin logo field
     */
    public static function render_admin_logo_field() {
        $settings = get_option('quickswap_branding_settings', array());
        $logo_id = !empty($settings['admin_logo_id']) ? intval($settings['admin_logo_id']) : 0;
        self::render_image_uploader('admin_logo', $logo_id, __('Recommended size: 32x32px', 'quickswap'));
    }

    /**
     * Render login logo field
     */
    public static function render_login_logo_field() {
        $settings = get_option('quickswap_branding_settings', array());
        $logo_id = !empty($settings['login_logo_id']) ? intval($settings['login_logo_id']) : 0;
        self::render_image_uploader('login_logo', $logo_id, __('Recommended size: 80x80px', 'quickswap'));
    }

    /**
     * Render login logo dimensions field
     */
    public static function render_login_logo_dimensions_field() {
        $settings = get_option('quickswap_branding_settings', array());
        $width = !empty($settings['login_logo_width']) ? intval($settings['login_logo_width']) : 80;
        $height = !empty($settings['login_logo_height']) ? intval($settings['login_logo_height']) : 80;
        ?>
        <label>
            <?php esc_html_e('Width:', 'quickswap'); ?>
            <input type="number" name="quickswap_branding_settings[login_logo_width]" value="<?php echo esc_attr($width); ?>" min="20" max="300" style="width: 80px;">
            <?php esc_html_e('px', 'quickswap'); ?>
        </label>
        <label style="margin-left: 20px;">
            <?php esc_html_e('Height:', 'quickswap'); ?>
            <input type="number" name="quickswap_branding_settings[login_logo_height]" value="<?php echo esc_attr($height); ?>" min="20" max="300" style="width: 80px;">
            <?php esc_html_e('px', 'quickswap'); ?>
        </label>
        <p class="description"><?php esc_html_e('Adjust the display size of your login page logo.', 'quickswap'); ?></p>
        <?php
    }

    /**
     * Render colors section
     */
    public static function render_colors_section() {
        echo '<p>' . esc_html__('Customize the color scheme used throughout the WordPress admin area.', 'quickswap') . '</p>';
    }

    /**
     * Render custom colors enabled field
     */
    public static function render_custom_colors_enabled_field() {
        $settings = get_option('quickswap_branding_settings', array());
        $value = !empty($settings['custom_colors_enabled']);
        ?>
        <label>
            <input type="checkbox" name="quickswap_branding_settings[custom_colors_enabled]" value="1" <?php checked($value); ?>>
            <?php esc_html_e('Enable custom color scheme', 'quickswap'); ?>
        </label>
        <p class="description"><?php esc_html_e('Apply custom colors to buttons, links, and admin elements.', 'quickswap'); ?></p>
        <?php
    }

    /**
     * Render primary color field
     */
    public static function render_primary_color_field() {
        $settings = get_option('quickswap_branding_settings', array());
        $value = !empty($settings['admin_primary_color']) ? $settings['admin_primary_color'] : '#2271b1';
        self::render_color_field('admin_primary_color', $value, __('Main accent color for buttons and links', 'quickswap'));
    }

    /**
     * Render secondary color field
     */
    public static function render_secondary_color_field() {
        $settings = get_option('quickswap_branding_settings', array());
        $value = !empty($settings['admin_secondary_color']) ? $settings['admin_secondary_color'] : '#135e96';
        self::render_color_field('admin_secondary_color', $value, __('Hover/active state color', 'quickswap'));
    }

    /**
     * Render background color field
     */
    public static function render_bg_color_field() {
        $settings = get_option('quickswap_branding_settings', array());
        $value = !empty($settings['admin_bg_color']) ? $settings['admin_bg_color'] : '#f0f0f1';
        self::render_color_field('admin_bg_color', $value, __('Background color for admin area', 'quickswap'));
    }

    /**
     * Render color input field
     */
    private static function render_color_field($name, $value, $description) {
        ?>
        <input type="color" name="quickswap_branding_settings[<?php echo esc_attr($name); ?>]" value="<?php echo esc_attr($value); ?>" class="quickswap-color-picker">
        <input type="text" name="quickswap_branding_settings[<?php echo esc_attr($name); ?>]_text" value="<?php echo esc_attr($value); ?>" class="quickswap-color-text" style="width: 100px; margin-left: 10px;">
        <p class="description"><?php echo esc_html($description); ?></p>
        <?php
    }

    /**
     * Render login section
     */
    public static function render_login_section() {
        echo '<p>' . esc_html__('Customize the appearance and behavior of the WordPress login page.', 'quickswap') . '</p>';
    }

    /**
     * Render login logo URL field
     */
    public static function render_login_logo_url_field() {
        $settings = get_option('quickswap_branding_settings', array());
        $value = !empty($settings['login_logo_url']) ? $settings['login_logo_url'] : '';
        ?>
        <input type="url" name="quickswap_branding_settings[login_logo_url]" value="<?php echo esc_attr($value); ?>" class="regular-text">
        <p class="description"><?php esc_html_e('URL where the login logo links to. Default: your site homepage.', 'quickswap'); ?></p>
        <?php
    }

    /**
     * Render login logo title field
     */
    public static function render_login_logo_title_field() {
        $settings = get_option('quickswap_branding_settings', array());
        $value = !empty($settings['login_logo_title']) ? $settings['login_logo_title'] : '';
        ?>
        <input type="text" name="quickswap_branding_settings[login_logo_title]" value="<?php echo esc_attr($value); ?>" class="regular-text">
        <p class="description"><?php esc_html_e('Tooltip text shown when hovering over the login logo.', 'quickswap'); ?></p>
        <?php
    }

    /**
     * Render login welcome message field
     */
    public static function render_login_welcome_message_field() {
        $settings = get_option('quickswap_branding_settings', array());
        $value = !empty($settings['login_welcome_message']) ? $settings['login_welcome_message'] : '';
        ?>
        <textarea name="quickswap_branding_settings[login_welcome_message]" rows="4" class="large-text"><?php echo esc_textarea($value); ?></textarea>
        <p class="description"><?php esc_html_e('HTML allowed. Message displayed above the login form.', 'quickswap'); ?></p>
        <?php
    }

    /**
     * Render login background field
     */
    public static function render_login_background_field() {
        $settings = get_option('quickswap_branding_settings', array());
        $bg_image_id = !empty($settings['login_bg_image_id']) ? intval($settings['login_bg_image_id']) : 0;
        $bg_color = !empty($settings['login_bg_color']) ? $settings['login_bg_color'] : '#ffffff';
        ?>
        <div>
            <?php self::render_image_uploader('login_bg_image', $bg_image_id, __('Background image for login page', 'quickswap')); ?>
        </div>
        <div style="margin-top: 15px;">
            <label>
                <?php esc_html_e('Background Color:', 'quickswap'); ?>
                <input type="color" name="quickswap_branding_settings[login_bg_color]" value="<?php echo esc_attr($bg_color); ?>" class="quickswap-color-picker">
                <input type="text" name="quickswap_branding_settings[login_bg_color]_text" value="<?php echo esc_attr($bg_color); ?>" class="quickswap-color-text" style="width: 100px; margin-left: 10px;">
            </label>
            <p class="description"><?php esc_html_e('Fallback color shown when no background image is set.', 'quickswap'); ?></p>
        </div>
        <?php
    }

    /**
     * Render footer section
     */
    public static function render_footer_section() {
        echo '<p>' . esc_html__('Customize the text shown in the admin footer.', 'quickswap') . '</p>';
    }

    /**
     * Render footer text left field
     */
    public static function render_footer_text_left_field() {
        $settings = get_option('quickswap_branding_settings', array());
        $value = !empty($settings['footer_text_left']) ? $settings['footer_text_left'] : '';
        ?>
        <input type="text" name="quickswap_branding_settings[footer_text_left]" value="<?php echo esc_attr($value); ?>" class="regular-text">
        <p class="description"><?php esc_html_e('Text shown on the left side of the footer. Default: "Thank you for creating with WordPress."', 'quickswap'); ?></p>
        <?php
    }

    /**
     * Render footer text right field
     */
    public static function render_footer_text_right_field() {
        $settings = get_option('quickswap_branding_settings', array());
        $value = !empty($settings['footer_text_right']) ? $settings['footer_text_right'] : '';
        ?>
        <input type="text" name="quickswap_branding_settings[footer_text_right]" value="<?php echo esc_attr($value); ?>" class="regular-text">
        <p class="description"><?php esc_html_e('Text shown on the right side of the footer. Default: WordPress version number.', 'quickswap'); ?></p>
        <?php
    }

    /**
     * Render image uploader field
     */
    private static function render_image_uploader($field_name, $image_id, $description = '') {
        $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'thumbnail') : '';
        ?>
        <div class="quickswap-image-uploader" data-field="<?php echo esc_attr($field_name); ?>">
            <div class="quickswap-image-preview">
                <?php if ($image_url): ?>
                    <img src="<?php echo esc_url($image_url); ?>" alt="">
                <?php endif; ?>
            </div>
            <div class="quickswap-image-actions">
                <button type="button" class="button quickswap-upload-image" data-field="<?php echo esc_attr($field_name); ?>">
                    <?php esc_html_e('Upload Image', 'quickswap'); ?>
                </button>
                <button type="button" class="button quickswap-remove-image" data-field="<?php echo esc_attr($field_name); ?>" <?php echo !$image_id ? 'style="display:none;"' : ''; ?>>
                    <?php esc_html_e('Remove', 'quickswap'); ?>
                </button>
                <input type="hidden" name="quickswap_branding_settings[<?php echo esc_attr($field_name); ?>_id]" value="<?php echo esc_attr($image_id); ?>">
            </div>
            <?php if ($description): ?>
                <p class="description"><?php echo esc_html($description); ?></p>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Enqueue admin scripts
     */
    public static function enqueue_admin_scripts($hook) {
        if ('settings_page_quickswap-settings' !== $hook) {
            return;
        }

        // Check if we're on the branding tab
        $current_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'general';
        if ('branding' !== $current_tab) {
            return;
        }

        wp_enqueue_media();
        wp_enqueue_script('quickswap-branding', QUICKSWAP_PLUGIN_URL . 'assets/js/quickswap-branding.js', array('jquery'), QUICKSWAP_VERSION, true);
        wp_enqueue_style('quickswap-branding-admin', QUICKSWAP_PLUGIN_URL . 'assets/css/quickswap-branding-admin.css', array(), QUICKSWAP_VERSION);

        // Localize script
        wp_localize_script('quickswap-branding', 'quickswapBranding', array(
            'uploadTitle' => __('Choose Image', 'quickswap'),
            'uploadButton' => __('Select Image', 'quickswap'),
        ));
    }
}
