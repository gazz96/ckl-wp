<?php
/**
 * Initialize Default Services
 *
 * Runs on theme activation to create default vehicle services
 *
 * @package CKL_Car_Rental
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Load the services data file
require_once dirname(__FILE__) . '/vehicle-services-data.php';

/**
 * Check if services need initialization and run if needed on admin_init
 */
function ckl_maybe_initialize_services() {
    // Only check on admin side
    if (!is_admin()) {
        return;
    }

    // Only check if user can manage options
    if (!current_user_can('manage_options')) {
        return;
    }

    // Auto-initialize on theme activation (after_switch_theme hook)
    // This is handled separately via ckl_initialize_default_services()
}
add_action('admin_init', 'ckl_maybe_initialize_services');

/**
 * Admin notice if services are not initialized
 */
function ckl_services_init_admin_notice() {
    // Only show to users who can manage options
    if (!current_user_can('manage_options')) {
        return;
    }

    // Check if services need initialization
    if (!ckl_needs_service_initialization()) {
        return;
    }

    // Don't show on certain screens where it might be distracting
    $screen = get_current_screen();
    if ($screen && $screen->base === 'update') {
        return;
    }

    $dismissed = get_user_meta(get_current_user_id(), 'ckl_dismiss_services_notice', true);
    if ($dismissed) {
        return;
    }

    ?>
    <div class="notice notice-info is-dismissible ckl-services-notice" data-nonce="<?php echo wp_create_nonce('ckl_dismiss_services_notice'); ?>">
        <p>
            <strong><?php _e('CKL Car Rental:', 'ckl-car-rental'); ?></strong>
            <?php _e('Default vehicle services are ready to be installed.', 'ckl-car-rental'); ?>
            <button type="button" class="button button-primary button-small" id="ckl-init-services" style="margin-left: 10px;">
                <?php _e('Install Now', 'ckl-car-rental'); ?>
            </button>
        </p>
    </div>
    <style>
    .ckl-services-notice {
        position: relative;
        padding-right: 40px;
    }
    </style>
    <script>
    jQuery(document).ready(function($) {
        // Initialize services button
        $('#ckl-init-services').on('click', function() {
            var $button = $(this);
            var $notice = $button.closest('.ckl-services-notice');

            $button.prop('disabled', true).text('<?php _e('Installing...', 'ckl-car-rental'); ?>');

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'ckl_initialize_services',
                    nonce: '<?php echo wp_create_nonce('ckl_services_init'); ?>'
                },
                success: function(response) {
                    if (response.success) {
                        $notice.removeClass('notice-info').addClass('notice-success')
                            .find('p').html('<strong><?php _e('Success!', 'ckl-car-rental'); ?></strong> <?php _e('Default services have been installed.', 'ckl-car-rental'); ?>');
                        setTimeout(function() {
                            $notice.fadeOut();
                        }, 3000);
                    } else {
                        $button.prop('disabled', false).text('<?php _e('Try Again', 'ckl-car-rental'); ?>');
                        alert(response.data.message || '<?php _e('Error initializing services', 'ckl-car-rental'); ?>');
                    }
                },
                error: function() {
                    $button.prop('disabled', false).text('<?php _e('Try Again', 'ckl-car-rental'); ?>');
                    alert('<?php _e('Server error. Please try again.', 'ckl-car-rental'); ?>');
                }
            });
        });

        // Dismissible notice handling
        $(document).on('click', '.ckl-services-notice .notice-dismiss', function() {
            var $notice = $(this).closest('.ckl-services-notice');
            var nonce = $notice.data('nonce');

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'ckl_dismiss_services_notice',
                    nonce: nonce
                }
            });
        });
    });
    </script>
    <?php
}
add_action('admin_notices', 'ckl_services_init_admin_notice');

/**
 * AJAX handler for manual service initialization
 */
function ckl_ajax_initialize_services() {
    check_ajax_referer('ckl_services_init', 'nonce');

    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => __('Permission denied', 'ckl-car-rental')));
    }

    $result = ckl_initialize_default_services();

    wp_send_json_success(array('message' => __('Services initialized successfully', 'ckl-car-rental')));
}
add_action('wp_ajax_ckl_initialize_services', 'ckl_ajax_initialize_services');

/**
 * AJAX handler for dismissing the services notice
 */
function ckl_ajax_dismiss_services_notice() {
    $nonce = isset($_POST['nonce']) ? $_POST['nonce'] : '';

    if (!wp_verify_nonce($nonce, 'ckl_dismiss_services_notice')) {
        wp_send_json_error();
    }

    update_user_meta(get_current_user_id(), 'ckl_dismiss_services_notice', true);
    wp_send_json_success();
}
add_action('wp_ajax_ckl_dismiss_services_notice', 'ckl_ajax_dismiss_services_notice');

/**
 * Reset services notice dismissal (for testing)
 *
 * Call this function to reset the dismissed state:
 * ckl_reset_services_notice();
 */
function ckl_reset_services_notice() {
    delete_user_meta(get_current_user_id(), 'ckl_dismiss_services_notice');
}

/**
 * Hook into theme activation to initialize services automatically
 */
function ckl_activate_theme_services() {
    // Delay slightly to ensure all post types are registered
    add_action('init', 'ckl_initialize_default_services', 99);
}
// This hook is triggered when theme is activated via after_switch_theme
add_action('after_switch_theme', 'ckl_activate_theme_services');
