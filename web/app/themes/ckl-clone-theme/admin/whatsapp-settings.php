<?php
/**
 * WhatsApp Settings Page
 *
 * Admin interface for WhatsApp floating button configuration
 * Integrated with WordPress Settings API
 *
 * @package CKL_Car_Rental
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register WhatsApp settings
 */
function ckl_register_whatsapp_settings() {
    // Main WhatsApp settings group
    register_setting('ckl_whatsapp_settings', 'ckl_whatsapp_config', array(
        'type' => 'array',
        'default' => ckl_get_default_whatsapp_config(),
        'sanitize_callback' => 'ckl_sanitize_whatsapp_config'
    ));
}
add_action('admin_init', 'ckl_register_whatsapp_settings');

/**
 * Get default WhatsApp configuration
 */
function ckl_get_default_whatsapp_config() {
    return array(
        'phone' => '60194428040',
        'message' => 'Hi, I\'m interested in renting a car from CK Langkawi. Can you help me?',
        'tooltip' => 'Chat with us!',
        'position' => 'bottom-right',
        'enabled' => true,
        'button_color' => '#22C55E',
        'button_size' => 'medium',
        'show_on_pages' => 'all',
        'exclude_pages' => array(),
        'business_hours_only' => false,
        'business_hours_start' => '09:00',
        'business_hours_end' => '18:00'
    );
}

/**
 * Sanitize WhatsApp configuration
 */
function ckl_sanitize_whatsapp_config($input) {
    $sanitized = array();
    
    // Phone number - strip non-numeric except leading +
    $sanitized['phone'] = preg_replace('/[^0-9]/', '', $input['phone']);
    
    // Message - strip tags but preserve formatting
    $sanitized['message'] = sanitize_text_field($input['message']);
    
    // Tooltip - simple text field
    $sanitized['tooltip'] = sanitize_text_field($input['tooltip']);
    
    // Position - only allow specific values
    $allowed_positions = array('bottom-right', 'bottom-left', 'top-right', 'top-left');
    $sanitized['position'] = in_array($input['position'], $allowed_positions) ? $input['position'] : 'bottom-right';
    
    // Enabled - boolean
    $sanitized['enabled'] = isset($input['enabled']) ? (bool) $input['enabled'] : false;
    
    // Button color - hex color validation
    $sanitized['button_color'] = sanitize_hex_color($input['button_color']);
    if (!$sanitized['button_color']) {
        $sanitized['button_color'] = '#22C55E'; // Default WhatsApp green
    }
    
    // Button size
    $allowed_sizes = array('small', 'medium', 'large');
    $sanitized['button_size'] = in_array($input['button_size'], $allowed_sizes) ? $input['button_size'] : 'medium';
    
    // Show on pages
    $sanitized['show_on_pages'] = sanitize_text_field($input['show_on_pages']);
    
    // Exclude pages - array of page IDs
    if (isset($input['exclude_pages']) && is_array($input['exclude_pages'])) {
        $sanitized['exclude_pages'] = array_map('intval', $input['exclude_pages']);
    } else {
        $sanitized['exclude_pages'] = array();
    }
    
    // Business hours only
    $sanitized['business_hours_only'] = isset($input['business_hours_only']) ? (bool) $input['business_hours_only'] : false;
    
    // Business hours
    $sanitized['business_hours_start'] = sanitize_text_field($input['business_hours_start']);
    $sanitized['business_hours_end'] = sanitize_text_field($input['business_hours_end']);
    
    return $sanitized;
}

/**
 * WhatsApp settings page HTML
 */
function ckl_whatsapp_settings_page_html() {
    // Check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }
    
    // Get current settings
    $whatsapp_config = get_option('ckl_whatsapp_config', ckl_get_default_whatsapp_config());
    
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        
        <div class="ckl-whatsapp-settings-wrapper">
            <!-- Settings Tabs -->
            <h2 class="nav-tab-wrapper">
                <a href="#tab-general" class="nav-tab nav-tab-active">General Settings</a>
                <a href="#tab-appearance" class="nav-tab">Appearance</a>
                <a href="#tab-advanced" class="nav-tab">Advanced</a>
            </h2>
            
            <form method="post" action="options.php" id="ckl-whatsapp-form">
                <?php settings_fields('ckl_whatsapp_settings'); ?>
                <?php do_settings_sections('ckl_whatsapp_settings'); ?>
                
                <input type="hidden" name="ckl_whatsapp_config[enabled]" value="0">
                
                <!-- General Settings Tab -->
                <div id="tab-general" class="tab-content active">
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="ckl_whatsapp_enabled">Enable WhatsApp Button</label>
                            </th>
                            <td>
                                <input type="checkbox" 
                                       id="ckl_whatsapp_enabled" 
                                       name="ckl_whatsapp_config[enabled]" 
                                       value="1" 
                                       <?php checked($whatsapp_config['enabled'], true); ?>>
                                <p class="description">Show WhatsApp floating button on website</p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="ckl_whatsapp_phone">WhatsApp Number</label>
                            </th>
                            <td>
                                <input type="text" 
                                       id="ckl_whatsapp_phone" 
                                       name="ckl_whatsapp_config[phone]" 
                                       value="<?php echo esc_attr($whatsapp_config['phone']); ?>" 
                                       class="regular-text"
                                       placeholder="60194428040">
                                <p class="description">
                                    Format: Country code + number (no spaces or +).<br>
                                    Example: 60194428040 (Malaysia), 6281234567890 (Indonesia)
                                </p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="ckl_whatsapp_message">Default Message</label>
                            </th>
                            <td>
                                <textarea id="ckl_whatsapp_message" 
                                          name="ckl_whatsapp_config[message]" 
                                          rows="3" 
                                          class="large-text"><?php echo esc_textarea($whatsapp_config['message']); ?></textarea>
                                <p class="description">
                                    Pre-filled message when users click the WhatsApp button.<br>
                                    Use {name} placeholder for user's name if supported.
                                </p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="ckl_whatsapp_tooltip">Tooltip Text</label>
                            </th>
                            <td>
                                <input type="text" 
                                       id="ckl_whatsapp_tooltip" 
                                       name="ckl_whatsapp_config[tooltip]" 
                                       value="<?php echo esc_attr($whatsapp_config['tooltip']); ?>" 
                                       class="regular-text">
                                <p class="description">Text shown on button hover</p>
                            </td>
                        </tr>
                    </table>
                </div>
                
                <!-- Appearance Tab -->
                <div id="tab-appearance" class="tab-content" style="display:none;">
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="ckl_whatsapp_position">Button Position</label>
                            </th>
                            <td>
                                <select id="ckl_whatsapp_position" name="ckl_whatsapp_config[position]">
                                    <option value="bottom-right" <?php selected($whatsapp_config['position'], 'bottom-right'); ?>>
                                        Bottom Right (Default)
                                    </option>
                                    <option value="bottom-left" <?php selected($whatsapp_config['position'], 'bottom-left'); ?>>
                                        Bottom Left
                                    </option>
                                    <option value="top-right" <?php selected($whatsapp_config['position'], 'top-right'); ?>>
                                        Top Right
                                    </option>
                                    <option value="top-left" <?php selected($whatsapp_config['position'], 'top-left'); ?>>
                                        Top Left
                                    </option>
                                </select>
                                <p class="description">Choose where the WhatsApp button appears on screen</p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="ckl_whatsapp_button_color">Button Color</label>
                            </th>
                            <td>
                                <input type="color" 
                                       id="ckl_whatsapp_button_color" 
                                       name="ckl_whatsapp_config[button_color]" 
                                       value="<?php echo esc_attr($whatsapp_config['button_color']); ?>" 
                                       class="color-picker">
                                <p class="description">Custom button color (default: #22C55E - WhatsApp green)</p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="ckl_whatsapp_button_size">Button Size</label>
                            </th>
                            <td>
                                <select id="ckl_whatsapp_button_size" name="ckl_whatsapp_config[button_size]">
                                    <option value="small" <?php selected($whatsapp_config['button_size'], 'small'); ?>>
                                        Small (48px)
                                    </option>
                                    <option value="medium" <?php selected($whatsapp_config['button_size'], 'medium'); ?>>
                                        Medium (64px) - Recommended
                                    </option>
                                    <option value="large" <?php selected($whatsapp_config['button_size'], 'large'); ?>>
                                        Large (80px)
                                    </option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>
                
                <!-- Advanced Tab -->
                <div id="tab-advanced" class="tab-content" style="display:none;">
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="ckl_whatsapp_show_on_pages">Display Mode</label>
                            </th>
                            <td>
                                <select id="ckl_whatsapp_show_on_pages" name="ckl_whatsapp_config[show_on_pages]">
                                    <option value="all" <?php selected($whatsapp_config['show_on_pages'], 'all'); ?>>
                                        Show on all pages
                                    </option>
                                    <option value="selected" <?php selected($whatsapp_config['show_on_pages'], 'selected'); ?>>
                                        Show on selected pages only
                                    </option>
                                    <option value="exclude" <?php selected($whatsapp_config['show_on_pages'], 'exclude'); ?>>
                                        Exclude selected pages
                                    </option>
                                </select>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="ckl_whatsapp_business_hours_only">Business Hours Only</label>
                            </th>
                            <td>
                                <input type="checkbox" 
                                       id="ckl_whatsapp_business_hours_only" 
                                       name="ckl_whatsapp_config[business_hours_only]" 
                                       value="1" 
                                       <?php checked($whatsapp_config['business_hours_only'], true); ?>>
                                <p class="description">Only show WhatsApp button during business hours</p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="ckl_whatsapp_business_hours_start">Business Hours Start</label>
                            </th>
                            <td>
                                <input type="time" 
                                       id="ckl_whatsapp_business_hours_start" 
                                       name="ckl_whatsapp_config[business_hours_start]" 
                                       value="<?php echo esc_attr($whatsapp_config['business_hours_start']); ?>">
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="ckl_whatsapp_business_hours_end">Business Hours End</label>
                            </th>
                            <td>
                                <input type="time" 
                                       id="ckl_whatsapp_business_hours_end" 
                                       name="ckl_whatsapp_config[business_hours_end]" 
                                       value="<?php echo esc_attr($whatsapp_config['business_hours_end']); ?>">
                            </td>
                        </tr>
                    </table>
                </div>
                
                <!-- Live Preview Section -->
                <div class="ckl-whatsapp-preview" style="margin: 20px 0; padding: 20px; border: 1px dashed #ccc; background: #f9f9f9;">
                    <h3>Live Preview</h3>
                    <p class="description">Test your WhatsApp button configuration:</p>
                    <div style="height: 200px; position: relative; border: 1px solid #ddd; background: white;">
                        <?php
                        // Generate preview URL
                        $preview_url = 'https://api.whatsapp.com/send/?phone=' . $whatsapp_config['phone'] . '&text=' . urlencode($whatsapp_config['message']) . '&type=phone_number&app_absent=0';
                        
                        // Generate position classes for preview
                        $position_classes = 'ckl-preview-button z-50 text-white rounded-full shadow-lg flex items-center justify-center';
                        $position_classes .= ' ' . $whatsapp_config['position'];
                        ?>
                        <a href="<?php echo esc_url($preview_url); ?>" 
                           target="_blank"
                           class="<?php echo esc_attr($position_classes); ?>"
                           style="background-color: <?php echo esc_attr($whatsapp_config['button_color']); ?>; width: 64px; height: 64px; position: absolute; text-decoration: none;">
                            <svg style="width: 32px; height: 32px;" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                            </svg>
                        </a>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <?php submit_button('Save WhatsApp Settings'); ?>
            </form>
        </div>
    </div>
    
    <style>
    .ckl-whatsapp-settings-wrapper {
        max-width: 800px;
        margin-top: 20px;
    }
    
    .tab-content {
        display: none;
    }
    
    .tab-content.active {
        display: block;
    }
    
    .ckl-preview-button {
        transition: transform 0.2s;
    }
    
    .ckl-preview-button:hover {
        transform: scale(1.1);
    }
    
    .ckl-whatsapp-preview {
        border-radius: 8px;
    }
    
    .color-picker {
        width: 60px;
        height: 40px;
        border: 1px solid #ddd;
        border-radius: 4px;
        cursor: pointer;
    }
    
    /* Position classes for preview */
    .bottom-right { bottom: 10px; right: 10px; }
    .bottom-left { bottom: 10px; left: 10px; }
    .top-right { top: 10px; right: 10px; }
    .top-left { top: 10px; left: 10px; }
    </style>
    
    <script>
    jQuery(document).ready(function($) {
        // Tab functionality
        $('.nav-tab-wrapper .nav-tab').on('click', function(e) {
            e.preventDefault();
            
            var target = $(this).attr('href');
            
            // Update active tab
            $('.nav-tab-wrapper .nav-tab').removeClass('nav-tab-active');
            $(this).addClass('nav-tab-active');
            
            // Show target content
            $('.tab-content').removeClass('active').hide();
            $(target).addClass('active').show();
        });
        
        // Live preview update
        $('#ckl-whatsapp-form').on('change keyup', function() {
            // Update preview based on form changes
            var position = $('#ckl_whatsapp_position').val();
            var color = $('#ckl_whatsapp_button_color').val();
            var phone = $('#ckl_whatsapp_phone').val();
            var message = $('#ckl_whatsapp_message').val();
            
            // Update button position
            $('.ckl-preview-button').removeClass('bottom-right bottom-left top-right top-left').addClass(position);
            
            // Update button color
            $('.ckl-preview-button').css('background-color', color);
            
            // Update href
            var url = 'https://api.whatsapp.com/send/?phone=' + phone + '&text=' + encodeURIComponent(message) + '&type=phone_number&app_absent=0';
            $('.ckl-preview-button').attr('href', url);
        });
    });
    </script>
    <?php
}

/**
 * Add WhatsApp settings menu item
 */
function ckl_add_whatsapp_settings_menu() {
    add_submenu_page(
        'cklangkawi-settings',
        __('WhatsApp Settings', 'ckl-car-rental'),
        __('WhatsApp Settings', 'ckl-car-rental'),
        'manage_options',
        'ckl-whatsapp-settings',
        'ckl_whatsapp_settings_page_html'
    );
}
add_action('admin_menu', 'ckl_add_whatsapp_settings_menu', 20); // Priority 20 to add after existing menus