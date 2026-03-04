<?php

/**
 * Load additional-services.php early for frontend access
 * The ckl_get_vehicle_services() function is needed in template files
 */
require_once get_template_directory() . '/admin/additional-services.php';

/**
 * Load shortcodes
 */
require_once get_template_directory() . '/shortcodes/guest-booking-lookup.php';
require_once get_template_directory() . '/shortcodes/auth-form-shortcode.php';
require_once get_template_directory() . '/shortcodes/homepage-sections.php';

/**
 * Load includes
 *
 * Core classes for QR code generation and display.
 * These must be loaded early for hooks to register properly.
 */
require_once get_template_directory() . '/includes/class-qr-code-generator.php';
require_once get_template_directory() . '/includes/class-admin-order-qr.php';
require_once get_template_directory() . '/includes/class-booking-qr.php';

/**
 * Vehicle pricing helper functions
 */
require_once get_template_directory() . '/includes/vehicle-pricing-helpers.php';

/**
 * Theme setup and configuration
 */
function ckl_clone_theme_setup() {
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'ckl-car-rental'),
        'footer' => __('Footer Menu', 'ckl-car-rental'),
    ));

    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));

    // Add custom logo support
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-width'  => true,
        'flex-height' => true,
        'header-text' => array('site-title', 'site-description'),
    ));
}
add_action('after_setup_theme', 'ckl_clone_theme_setup');

/**
 * Load admin files
 */
function ckl_load_admin_files() {
    // Admin-only files
    // Note: additional-services.php is loaded at the top of functions.php for frontend access
    if (is_admin()) {
        require_once get_template_directory() . '/admin/theme-settings.php';
        require_once get_template_directory() . '/admin/cklangkawi-menu.php';
        require_once get_template_directory() . '/admin/migrate-vehicle-types.php';
        require_once get_template_directory() . '/admin/vehicle-meta-tabs.php';
        require_once get_template_directory() . '/admin/init-settings.php';
        require_once get_template_directory() . '/admin/force-init-settings.php';
    }
}
add_action('after_setup_theme', 'ckl_load_admin_files');

/**
 * Fix WooCommerce Bookings script dependency error
 *
 * This fixes the error: "The script with the handle 'wc-accommodation-bookings-form'
 * was enqueued with dependencies that are not registered: wc-bookings-booking-form"
 */
function ckl_fix_wc_bookings_script_error() {
    // Only fix if WooCommerce is active but Bookings is not
    if (class_exists('WooCommerce') && !class_exists('WC_Bookings')) {
        // Register a dummy dependency to prevent the error
        wp_register_script(
            'wc-bookings-booking-form',
            false,
            array(),
            '1.0.0',
            true
        );

        // Alternatively, deregister the problematic script entirely
        wp_deregister_script('wc-accommodation-bookings-form');
    }
}
add_action('wp_enqueue_scripts', 'ckl_fix_wc_bookings_script_error', 5);

/**
 * Enqueue Google Fonts - Inter and Outfit font families
 * Loaded before main stylesheets with priority 5
 */
function ckl_clone_theme_fonts() {
    wp_enqueue_style(
        'ckl-google-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700&display=swap',
        array(),
        null
    );
}
add_action('wp_enqueue_scripts', 'ckl_clone_theme_fonts', 5);

/**
 * Enqueue theme scripts and styles.
 */
function ckl_clone_theme_scripts() {
    // Enqueue main stylesheet (contains all Tailwind utilities)
    wp_enqueue_style(
        'ckl-clone-theme-style',
        get_stylesheet_uri(),
        array(),
        '1.0.0'
    );

    // Enqueue Tailwind CSS (if using compiled version)
    if (file_exists(get_template_directory() . '/assets/dist/output.css')) {
        wp_enqueue_style(
            'ckl-clone-theme-tailwind',
            get_template_directory_uri() . '/assets/dist/output.css',
            array('ckl-clone-theme-style'),
            '1.0.0'
        );
    }

    // Enqueue theme JavaScript
    wp_enqueue_script(
        'ckl-clone-theme-main',
        get_template_directory_uri() . '/assets/js/main.js',
        array(),
        '1.0.0',
        true
    );

    // Enqueue homepage-specific JavaScript
    if (is_front_page()) {
        wp_enqueue_script(
            'ckl-homepage',
            get_template_directory_uri() . '/assets/js/homepage.js',
            array('jquery'),
            '1.0.0',
            true
        );
    }

    // Enqueue customer dashboard JavaScript
    if (is_ckl_account_page()) {
        wp_enqueue_script(
            'ckl-customer-dashboard',
            get_template_directory_uri() . '/assets/js/customer-dashboard.js',
            array('jquery'),
            '1.0.0',
            true
        );

        // Localize script with AJAX data
        wp_localize_script('ckl-customer-dashboard', 'ckl_dashboard', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'i18n' => array(
                'confirm_cancel' => __('Are you sure you want to cancel this booking?', 'ckl-car-rental'),
                'canceling' => __('Canceling...', 'ckl-car-rental'),
                'cancel' => __('Cancel', 'ckl-car-rental'),
                'error' => __('An error occurred. Please try again.', 'ckl-car-rental'),
                'saving' => __('Saving...', 'ckl-car-rental'),
                'save_changes' => __('Save Changes', 'ckl-car-rental'),
                'uploading' => __('Uploading...', 'ckl-car-rental'),
                'upload' => __('Upload Documents', 'ckl-car-rental'),
                'select_file' => __('Please select a file to upload.', 'ckl-car-rental'),
                'sending' => __('Sending...', 'ckl-car-rental'),
                'send_message' => __('Send Message', 'ckl-car-rental'),
            ),
        ));
    }

    // Enqueue checkout payment page JavaScript
    if (function_exists('is_checkout_pay_page') && is_checkout_pay_page()) {
        wp_enqueue_script(
            'ckl-checkout-pay',
            get_template_directory_uri() . '/assets/js/checkout-pay.js',
            array('jquery'),
            '1.0.0',
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'ckl_clone_theme_scripts');

/**
 * Check if current page is WooCommerce account page
 */
function is_ckl_account_page() {
    if (!function_exists('is_account_page') || !function_exists('is_wc_endpoint_url')) {
        return false;
    }

    return is_account_page() || is_wc_endpoint_url();
}

/**
 * Fallback primary menu with active state support
 *
 * @param array $args Optional arguments to configure menu output
 *                    - 'mobile' (bool) Whether to render mobile menu layout
 */
function ckl_default_menu($args = array()) {
    $is_mobile = isset($args['mobile']) && $args['mobile'];
    $current_url = esc_url(add_query_arg(NULL, NULL));
    $home_url = home_url('/');

    // Helper function to check if URL matches current page
    $is_active = function($url) use ($current_url, $home_url) {
        // Exact match for home page
        if ($url === $home_url) {
            return $current_url === $home_url;
        }
        // Partial match for other pages (handles sub-pages like blog posts)
        return strpos($current_url, $url) === 0;
    };

    // Helper function to render link with proper Tailwind classes
    $render_link = function($url, $text, $active) {
        // Tailwind 3.4.17 compatible classes
        $base_classes = 'font-medium transition-colors relative';

        if ($active) {
            // Active state with underline (using arbitrary values for Tailwind 3.x)
            $classes = $base_classes . ' text-blue-600 after:content-[""] after:absolute after:bottom-[-8px] after:left-0 after:right-0 after:h-[2px] after:bg-current';
        } else {
            // Default state
            $classes = $base_classes . ' text-gray-700 hover:text-blue-600';
        }

        return sprintf(
            '<a href="%s" class="%s">%s</a>',
            esc_url($url),
            esc_attr($classes),
            esc_html__($text, 'ckl-car-rental')
        );
    };

    // Menu items array - defines structure and order
    $menu_items = array(
        array('url' => $home_url, 'text' => 'Home'),
        array('url' => home_url('/vehicles/'), 'text' => 'Vehicles'),
        array('url' => home_url('/faq/'), 'text' => 'FAQ'),
        array('url' => home_url('/about/'), 'text' => 'About Us'),
        array('url' => home_url('/contact/'), 'text' => 'Contact Us'),
        array('url' => home_url('/how-to-book/'), 'text' => 'How To Book'),
        array('url' => home_url('/blog/'), 'text' => 'Blog'),
    );

    // Render menu based on context
    if ($is_mobile) {
        // Mobile menu: vertical list with <ul>
        echo '<ul class="flex flex-col space-y-4">';
        foreach ($menu_items as $item) {
            printf('<li>%s</li>', $render_link($item['url'], $item['text'], $is_active($item['url'])));
        }
        echo '</ul>';
    } else {
        // Desktop menu: horizontal flex container with <div>
        echo '<div class="hidden lg:flex items-center gap-8">';
        foreach ($menu_items as $item) {
            echo $render_link($item['url'], $item['text'], $is_active($item['url']));
        }
        echo '</div>';
    }
}

/**
 * Fallback footer menu
 */
function ckl_default_footer_menu() {
    echo '<ul class="space-y-2">';
    echo '<li><a href="' . esc_url(home_url('/')) . '">' . esc_html__('Home', 'ckl-car-rental') . '</a></li>';
    echo '<li><a href="' . esc_url(home_url('/about/')) . '">' . esc_html__('About Us', 'ckl-car-rental') . '</a></li>';
    echo '<li><a href="' . esc_url(home_url('/vehicles/')) . '">' . esc_html__('Our Vehicles', 'ckl-car-rental') . '</a></li>';
    echo '<li><a href="' . esc_url(home_url('/contact/')) . '">' . esc_html__('Contact Us', 'ckl-car-rental') . '</a></li>';
    echo '<li><a href="' . esc_url(home_url('/faq/')) . '">' . esc_html__('FAQs', 'ckl-car-rental') . '</a></li>';
    echo '</ul>';
}

/**
 * Process contact form submission
 */
function ckl_process_contact_form() {
    // Check if form was submitted
    if (!isset($_POST['ckl_contact_nonce']) || !wp_verify_nonce($_POST['ckl_contact_nonce'], 'ckl_contact_form')) {
        return;
    }

    // Sanitize and validate input
    $name = sanitize_text_field($_POST['name'] ?? '');
    $email = sanitize_email($_POST['email'] ?? '');
    $phone = sanitize_text_field($_POST['phone'] ?? '');
    $subject_raw = sanitize_text_field($_POST['subject'] ?? 'General Question');
    $message = sanitize_textarea_field($_POST['message'] ?? '');

    // Basic validation
    if (empty($name) || empty($email) || empty($message)) {
        return;
    }

    if (!is_email($email)) {
        return;
    }

    // Validate subject field
    $valid_subjects = array('General Question', 'Booking Inquiry', 'Support', 'Other');
    if (!in_array($subject_raw, $valid_subjects)) {
        $subject_raw = 'General Question';
    }

    // Prepare email
    $to = get_option('admin_email', 'contact@cklangkawi.com');
    $subject = sprintf('[CK Langkawi] %s - Contact from %s', $subject_raw, $name);

    $body = sprintf(
        "Subject: %s\n\nName: %s\n\nEmail: %s\n\nPhone: %s\n\nMessage:\n\n%s",
        $subject_raw,
        $name,
        $email,
        $phone ? $phone : 'Not provided',
        $message
    );

    $headers = array(
        'From: ' . $name . ' <' . $email . '>',
        'Reply-To: ' . $email,
        'Content-Type: text/plain; charset=UTF-8'
    );

    // Send email
    $sent = wp_mail($to, $subject, $body, $headers);

    // Store result in transient for display on page
    if ($sent) {
        set_transient('ckl_contact_success_' . get_current_user_id(), true, 30);
    } else {
        set_transient('ckl_contact_error_' . get_current_user_id(), true, 30);
    }
}
add_action('init', 'ckl_process_contact_form');

/**
 * Enqueue contact page scripts
 */
function ckl_enqueue_contact_scripts() {
    if (is_page_template('page-contact.php')) {
        wp_enqueue_script(
            'ckl-contact-page',
            get_template_directory_uri() . '/assets/js/contact-page.js',
            array(),
            '1.0.0',
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'ckl_enqueue_contact_scripts');

/**
 * Add bookmark via AJAX
 */
function ckl_add_bookmark() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'ckl_bookmark_nonce')) {
        wp_send_json_error(array('message' => __('Invalid security token', 'ckl-car-rental')));
    }

    $vehicle_id = intval($_POST['vehicle_id'] ?? 0);

    if (!$vehicle_id) {
        wp_send_json_error(array('message' => __('Invalid vehicle ID', 'ckl-car-rental')));
    }

    $user_id = get_current_user_id();

    if (!$user_id) {
        wp_send_json_error(array('message' => __('You must be logged in', 'ckl-car-rental')));
    }

    $bookmarks = get_user_meta($user_id, '_vehicle_bookmarks', true);

    if (!is_array($bookmarks)) {
        $bookmarks = array();
    }

    // Add bookmark if not already saved
    if (!in_array($vehicle_id, $bookmarks)) {
        $bookmarks[] = $vehicle_id;
        update_user_meta($user_id, '_vehicle_bookmarks', $bookmarks);
    }

    wp_send_json_success(array(
        'count' => count($bookmarks),
        'message' => __('Vehicle added to bookmarks', 'ckl-car-rental')
    ));
}
add_action('wp_ajax_ckl_add_bookmark', 'ckl_add_bookmark');
add_action('wp_ajax_nopriv_ckl_add_bookmark', 'ckl_add_bookmark');

/**
 * Remove bookmark via AJAX
 */
function ckl_remove_bookmark() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'ckl_bookmark_nonce')) {
        wp_send_json_error(array('message' => __('Invalid security token', 'ckl-car-rental')));
    }

    $vehicle_id = intval($_POST['vehicle_id'] ?? 0);

    if (!$vehicle_id) {
        wp_send_json_error(array('message' => __('Invalid vehicle ID', 'ckl-car-rental')));
    }

    $user_id = get_current_user_id();

    if (!$user_id) {
        wp_send_json_error(array('message' => __('You must be logged in', 'ckl-car-rental')));
    }

    $bookmarks = get_user_meta($user_id, '_vehicle_bookmarks', true);

    if (!is_array($bookmarks)) {
        $bookmarks = array();
    }

    // Remove bookmark
    $key = array_search($vehicle_id, $bookmarks);
    if ($key !== false) {
        unset($bookmarks[$key]);
        update_user_meta($user_id, '_vehicle_bookmarks', array_values($bookmarks));
    }

    wp_send_json_success(array(
        'count' => count($bookmarks),
        'message' => __('Vehicle removed from bookmarks', 'ckl-car-rental')
    ));
}
add_action('wp_ajax_ckl_remove_bookmark', 'ckl_remove_bookmark');
add_action('wp_ajax_nopriv_ckl_remove_bookmark', 'ckl_remove_bookmark');

/**
 * Localize script for AJAX
 */
function ckl_localize_ajax_script() {
    wp_localize_script('ckl-clone-theme-main', 'cklAjax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('ckl_bookmark_nonce'),
    ));
}
add_action('wp_enqueue_scripts', 'ckl_localize_ajax_script');

/**
 * Register Block Patterns
 */
function ckl_register_block_patterns() {
    // Register pattern categories
    register_block_pattern_category('ckl-hero', array(
        'label' => __('CKL Hero Sections', 'ckl-car-rental'),
        'description' => __('Hero section patterns for CK Langkawi pages', 'ckl-car-rental'),
    ));

    register_block_pattern_category('ckl-sections', array(
        'label' => __('CKL Sections', 'ckl-car-rental'),
        'description' => __('Reusable section patterns for CK Langkawi', 'ckl-car-rental'),
    ));

    register_block_pattern_category('ckl-about', array(
        'label' => __('CKL About Page', 'ckl-car-rental'),
        'description' => __('Patterns for the About Us page', 'ckl-car-rental'),
    ));

    register_block_pattern_category('ckl-contact', array(
        'label' => __('CKL Contact Page', 'ckl-car-rental'),
        'description' => __('Patterns for the Contact Us page', 'ckl-car-rental'),
    ));

    register_block_pattern_category('ckl-faq', array(
        'label' => __('CKL FAQ Page', 'ckl-car-rental'),
        'description' => __('Patterns for the FAQ page', 'ckl-car-rental'),
    ));

    register_block_pattern_category('ckl-cta', array(
        'label' => __('CKL Call to Action', 'ckl-car-rental'),
        'description' => __('Call to action section patterns', 'ckl-car-rental'),
    ));

    register_block_pattern_category('ckl-vehicles', array(
        'label' => __('CKL Vehicles', 'ckl-car-rental'),
        'description' => __('Vehicle listing and filter patterns', 'ckl-car-rental'),
    ));

    // Auto-register all pattern files from the patterns directory
    $pattern_dir = get_template_directory() . '/patterns';

    if (is_dir($pattern_dir)) {
        foreach (glob($pattern_dir . '/*.php') as $pattern_file) {
            $pattern_name = basename($pattern_file, '.php');
            $pattern_data = get_file_data($pattern_file, array(
                'pattern_name' => 'Pattern Name',
                'pattern_categories' => 'Pattern Categories',
                'block_types' => 'Block Types',
                'description' => 'Description',
            ));

            if (!empty($pattern_data['pattern_name'])) {
                $pattern_content = file_get_contents($pattern_file);

                // Remove PHP comments and extract block HTML
                $pattern_content = preg_replace('/<\?php.*?\?>/', '', $pattern_content);

                register_block_pattern(
                    'ckl/' . $pattern_name,
                    array(
                        'title' => $pattern_data['pattern_name'],
                        'content' => trim($pattern_content),
                        'categories' => array_map('trim', explode(',', $pattern_data['pattern_categories'])),
                        'blockTypes' => array_map('trim', explode(',', $pattern_data['block_types'])),
                        'description' => $pattern_data['description'] ?? '',
                    )
                );
            }
        }
    }
}
add_action('init', 'ckl_register_block_patterns');

/**
 * Get filtered vehicles based on search and filters
 *
 * @param string $search_term Search keyword
 * @param string $pickup_date Pickup date
 * @param string $return_date Return date
 * @param array $vehicle_types Vehicle type filters
 * @return WP_Query
 */
function ckl_get_filtered_vehicles($search_term = '', $pickup_date = '', $return_date = '', $vehicle_types = array(), $vehicle_category = '') {
    $args = array(
        'post_type' => 'vehicle',
        'posts_per_page' => 12,
        'post_status' => 'publish',
        'paged' => get_query_var('paged') ? get_query_var('paged') : 1,
    );

    // Search filter
    if (!empty($search_term)) {
        $args['s'] = $search_term;
    }

    // Taxonomy query for vehicle category
    $tax_query = array();

    if (!empty($vehicle_category)) {
        // Get parent category term
        $parent_term = get_term_by('slug', $vehicle_category, 'vehicle_category');

        if ($parent_term && !is_wp_error($parent_term)) {
            // Get all child terms
            $child_terms = get_terms(array(
                'taxonomy' => 'vehicle_category',
                'parent' => $parent_term->term_id,
                'fields' => 'ids',
            ));

            if (!empty($child_terms)) {
                $tax_query[] = array(
                    'taxonomy' => 'vehicle_category',
                    'field' => 'term_id',
                    'terms' => $child_terms,
                );
            }
        }
    } elseif (!empty($vehicle_types) && is_array($vehicle_types)) {
        // Legacy support: map old vehicle types to taxonomy terms
        $type_mapping = array(
            'sedan' => 'Sedan',
            'compact' => 'Compact',
            'mpv' => 'MPV',
            'luxury_mpv' => 'Luxury MPV',
            'suv' => 'SUV',
            '4x4' => '4x4',
            'scooter' => 'Scooter',
            'moped' => 'Moped',
            'sports_bike' => 'Sports Bike',
        );

        $terms = array();
        foreach ($vehicle_types as $type) {
            if (isset($type_mapping[$type])) {
                $term = get_term_by('name', $type_mapping[$type], 'vehicle_category');
                if ($term && !is_wp_error($term)) {
                    $terms[] = $term->term_id;
                }
            }
        }

        if (!empty($terms)) {
            $tax_query[] = array(
                'taxonomy' => 'vehicle_category',
                'field' => 'term_id',
                'terms' => $terms,
            );
        }
    }

    // Add taxonomy query to args
    if (!empty($tax_query)) {
        $args['tax_query'] = $tax_query;
    }

    // Availability check based on dates (optional enhancement)
    // This would require checking booking dates against vehicle availability
    // For now, we'll just filter by category and search

    return new WP_Query($args);
}

/**
 * AJAX handler for vehicle filtering
 */
function ckl_filter_vehicles_ajax() {
    // Verify nonce
    check_ajax_referer('ckl_vehicle_filter', 'nonce');

    // Get filter parameters
    $search_term = sanitize_text_field($_POST['search'] ?? '');
    $pickup_date = sanitize_text_field($_POST['pickup_date'] ?? '');
    $return_date = sanitize_text_field($_POST['return_date'] ?? '');
    $vehicle_types = isset($_POST['vehicle_types']) ? array_map('sanitize_text_field', $_POST['vehicle_types']) : array();
    $vehicle_category = sanitize_text_field($_POST['category'] ?? '');
    $paged = intval($_POST['paged'] ?? 1);

    // Set paged for query
    set_query_var('paged', $paged);

    // Query vehicles
    $vehicles = ckl_get_filtered_vehicles($search_term, $pickup_date, $return_date, $vehicle_types, $vehicle_category);

    // Render vehicle cards
    ob_start();
    if ($vehicles->have_posts()) {
        while ($vehicles->have_posts()) {
            $vehicles->the_post();
            get_template_part('template-parts/content', 'vehicle-card');
        }
    } else {
        echo '<div class="col-span-full text-center py-12">';
        echo '<div class="text-6xl mb-4">🚗</div>';
        echo '<h2 class="text-2xl font-bold mb-2">' . esc_html__('No vehicles found', 'ckl-car-rental') . '</h2>';
        echo '<p class="text-gray-600 mb-4">' . esc_html__('Try adjusting your filters or search criteria.', 'ckl-car-rental') . '</p>';
        echo '</div>';
    }
    wp_reset_postdata();

    $html = ob_get_clean();

    // Generate pagination
    $pagination = '';
    if ($vehicles->max_num_pages > 1) {
        ob_start();
        $pagination_links = paginate_links(array(
            'total' => $vehicles->max_num_pages,
            'current' => $paged,
            'prev_text' => __('&laquo; Previous', 'ckl-car-rental'),
            'next_text' => __('Next &raquo;', 'ckl-car-rental'),
        ));
        echo '<div class="mt-8 flex justify-center">';
        echo '<div class="pagination">' . $pagination_links . '</div>';
        echo '</div>';
        $pagination = ob_get_clean();
    }

    wp_send_json_success(array(
        'html' => $html,
        'pagination' => $pagination,
        'count' => $vehicles->post_count,
        'found_posts' => $vehicles->found_posts,
        'max_pages' => $vehicles->max_num_pages,
    ));
}
add_action('wp_ajax_ckl_filter_vehicles', 'ckl_filter_vehicles_ajax');
add_action('wp_ajax_nopriv_ckl_filter_vehicles', 'ckl_filter_vehicles_ajax');

/**
 * Enqueue vehicle filtering scripts
 */
function ckl_enqueue_vehicle_scripts() {
    // Only enqueue on vehicle pages
    if (is_page_template('page-vehicles.php') || is_post_type_archive('vehicle')) {
        wp_enqueue_script(
            'ckl-vehicle-filters',
            get_template_directory_uri() . '/assets/js/vehicle-filters.js',
            array('jquery'),
            '1.0.0',
            true
        );

        // Localize script with AJAX data
        wp_localize_script('ckl-vehicle-filters', 'cklVehicleFilters', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ckl_vehicle_filter'),
            'current_url' => home_url($_SERVER['REQUEST_URI']),
        ));
    }
}
add_action('wp_enqueue_scripts', 'ckl_enqueue_vehicle_scripts');

/**
 * Register FAQ Custom Post Type
 */
function ckl_register_faq_post_type() {
    $labels = array(
        'name'                  => _x('FAQs', 'Post Type General Name', 'ckl-car-rental'),
        'singular_name'         => _x('FAQ', 'Post Type Singular Name', 'ckl-car-rental'),
        'menu_name'             => __('FAQs', 'ckl-car-rental'),
        'name_admin_bar'        => __('FAQ', 'ckl-car-rental'),
        'archives'              => __('FAQ Archives', 'ckl-car-rental'),
        'attributes'            => __('FAQ Attributes', 'ckl-car-rental'),
        'parent_item_colon'     => __('Parent FAQ:', 'ckl-car-rental'),
        'all_items'             => __('All FAQs', 'ckl-car-rental'),
        'add_new_item'          => __('Add New FAQ', 'ckl-car-rental'),
        'add_new'               => __('Add New', 'ckl-car-rental'),
        'new_item'              => __('New FAQ', 'ckl-car-rental'),
        'edit_item'             => __('Edit FAQ', 'ckl-car-rental'),
        'update_item'           => __('Update FAQ', 'ckl-car-rental'),
        'view_item'             => __('View FAQ', 'ckl-car-rental'),
        'view_items'            => __('View FAQs', 'ckl-car-rental'),
        'search_items'          => __('Search FAQ', 'ckl-car-rental'),
        'not_found'             => __('Not found', 'ckl-car-rental'),
        'not_found_in_trash'    => __('Not found in Trash', 'ckl-car-rental'),
        'featured_image'        => __('FAQ Icon', 'ckl-car-rental'),
        'set_featured_image'    => __('Set FAQ icon', 'ckl-car-rental'),
        'remove_featured_image' => __('Remove FAQ icon', 'ckl-car-rental'),
        'use_featured_image'    => __('Use as FAQ icon', 'ckl-car-rental'),
    );

    $args = array(
        'label'                 => __('FAQ', 'ckl-car-rental'),
        'description'           => __('Frequently Asked Questions', 'ckl-car-rental'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'page-attributes'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 25,
        'menu_icon'             => 'dashicons-editor-help',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
    );

    register_post_type('faq', $args);
}
add_action('init', 'ckl_register_faq_post_type');

/**
 * Register FAQ Category Taxonomy
 */
function ckl_register_faq_category_taxonomy() {
    $labels = array(
        'name'                       => _x('FAQ Categories', 'Taxonomy General Name', 'ckl-car-rental'),
        'singular_name'              => _x('FAQ Category', 'Taxonomy Singular Name', 'ckl-car-rental'),
        'menu_name'                  => __('FAQ Categories', 'ckl-car-rental'),
        'all_items'                  => __('All FAQ Categories', 'ckl-car-rental'),
        'parent_item'                => __('Parent Category', 'ckl-car-rental'),
        'parent_item_colon'          => __('Parent Category:', 'ckl-car-rental'),
        'new_item_name'              => __('New Category Name', 'ckl-car-rental'),
        'add_new_item'               => __('Add New FAQ Category', 'ckl-car-rental'),
        'edit_item'                  => __('Edit FAQ Category', 'ckl-car-rental'),
        'update_item'                => __('Update FAQ Category', 'ckl-car-rental'),
        'view_item'                  => __('View FAQ Category', 'ckl-car-rental'),
        'separate_items_with_commas' => __('Separate categories with commas', 'ckl-car-rental'),
        'add_or_remove_items'        => __('Add or remove categories', 'ckl-car-rental'),
        'choose_from_most_used'      => __('Choose from the most used', 'ckl-car-rental'),
        'popular_items'              => __('Popular Categories', 'ckl-car-rental'),
        'search_items'               => __('Search categories', 'ckl-car-rental'),
        'not_found'                  => __('Not Found', 'ckl-car-rental'),
        'no_terms'                   => __('No categories', 'ckl-car-rental'),
        'items_list'                 => __('Categories list', 'ckl-car-rental'),
        'items_list_navigation'      => __('Categories list navigation', 'ckl-car-rental'),
    );

    $args = array(
        'labels'            => $labels,
        'hierarchical'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud'     => false,
        'show_in_rest'      => true,
    );

    register_taxonomy('faq_category', array('faq'), $args);

    // Insert default FAQ categories if they don't exist
    $default_categories = array(
        'Booking & Payment',
        'During Rental',
        'Eligibility & Documents',
        'Insurance & Liability',
        'Other Fees',
        'Pick-up & Return',
    );

    foreach ($default_categories as $category_name) {
        if (!term_exists($category_name, 'faq_category')) {
            wp_insert_term($category_name, 'faq_category');
        }
    }
}
add_action('init', 'ckl_register_faq_category_taxonomy');

/**
 * Register Vehicle Category Taxonomy
 */
function ckl_register_vehicle_category_taxonomy() {
    $labels = array(
        'name'                       => _x('Vehicle Categories', 'Taxonomy General Name', 'ckl-car-rental'),
        'singular_name'              => _x('Vehicle Category', 'Taxonomy Singular Name', 'ckl-car-rental'),
        'menu_name'                  => __('Vehicle Categories', 'ckl-car-rental'),
        'all_items'                  => __('All Vehicle Categories', 'ckl-car-rental'),
        'parent_item'                => __('Parent Category', 'ckl-car-rental'),
        'parent_item_colon'          => __('Parent Category:', 'ckl-car-rental'),
        'new_item_name'              => __('New Category Name', 'ckl-car-rental'),
        'add_new_item'               => __('Add New Vehicle Category', 'ckl-car-rental'),
        'edit_item'                  => __('Edit Vehicle Category', 'ckl-car-rental'),
        'update_item'                => __('Update Vehicle Category', 'ckl-car-rental'),
        'view_item'                  => __('View Vehicle Category', 'ckl-car-rental'),
        'separate_items_with_commas' => __('Separate categories with commas', 'ckl-car-rental'),
        'add_or_remove_items'        => __('Add or remove categories', 'ckl-car-rental'),
        'choose_from_most_used'      => __('Choose from the most used', 'ckl-car-rental'),
        'popular_items'              => __('Popular Categories', 'ckl-car-rental'),
        'search_items'               => __('Search categories', 'ckl-car-rental'),
        'not_found'                  => __('Not Found', 'ckl-car-rental'),
        'no_terms'                   => __('No categories', 'ckl-car-rental'),
        'items_list'                 => __('Categories list', 'ckl-car-rental'),
        'items_list_navigation'      => __('Categories list navigation', 'ckl-car-rental'),
    );

    $args = array(
        'labels'            => $labels,
        'hierarchical'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud'     => false,
        'show_in_rest'      => true,
    );

    register_taxonomy('vehicle_category', array('vehicle'), $args);

    // Insert default vehicle categories if they don't exist
    $default_categories = array(
        // Cars (parent)
        'Cars' => array(
            'Sedan',
            'Compact',
            'MPV',
            'Luxury MPV',
            'SUV',
            '4x4',
        ),
        // Motorcycles (parent)
        'Motorcycles' => array(
            'Scooter',
            'Moped',
            'Sports Bike',
        ),
    );

    foreach ($default_categories as $parent => $children) {
        // Create parent term if it doesn't exist
        $parent_term = term_exists($parent, 'vehicle_category');

        if (!$parent_term) {
            $parent_term = wp_insert_term($parent, 'vehicle_category');
            $parent_id = $parent_term['term_id'];
        } else {
            $parent_id = $parent_term['term_id'];
        }

        // Create child terms
        foreach ($children as $child_name) {
            if (!term_exists($child_name, 'vehicle_category')) {
                wp_insert_term($child_name, 'vehicle_category', array(
                    'parent' => $parent_id
                ));
            }
        }
    }
}
add_action('init', 'ckl_register_vehicle_category_taxonomy');


/**
 * Register Vehicle Service Custom Post Type
 */
function ckl_register_vehicle_service_post_type() {
    $labels = array(
        'name'                  => _x('Additional Services', 'Post Type General Name', 'ckl-car-rental'),
        'singular_name'         => _x('Service', 'Post Type Singular Name', 'ckl-car-rental'),
        'menu_name'             => __('Services', 'ckl-car-rental'),
        'name_admin_bar'        => __('Service', 'ckl-car-rental'),
        'archives'              => __('Service Archives', 'ckl-car-rental'),
        'attributes'            => __('Service Attributes', 'ckl-car-rental'),
        'parent_item_colon'     => __('Parent Service:', 'ckl-car-rental'),
        'all_items'             => __('All Services', 'ckl-car-rental'),
        'add_new_item'          => __('Add New Service', 'ckl-car-rental'),
        'add_new'               => __('Add New', 'ckl-car-rental'),
        'new_item'              => __('New Service', 'ckl-car-rental'),
        'edit_item'             => __('Edit Service', 'ckl-car-rental'),
        'update_item'           => __('Update Service', 'ckl-car-rental'),
        'view_item'             => __('View Service', 'ckl-car-rental'),
        'view_items'            => __('View Services', 'ckl-car-rental'),
        'search_items'          => __('Search Services', 'ckl-car-rental'),
        'not_found'             => __('Not found', 'ckl-car-rental'),
        'not_found_in_trash'    => __('Not found in Trash', 'ckl-car-rental'),
        'featured_image'        => __('Service Icon', 'ckl-car-rental'),
        'set_featured_image'    => __('Set service icon', 'ckl-car-rental'),
        'remove_featured_image' => __('Remove service icon', 'ckl-car-rental'),
        'use_featured_image'    => __('Use as service icon', 'ckl-car-rental'),
    );

    $args = array(
        'label'                 => __('Service', 'ckl-car-rental'),
        'description'           => __('Additional services for vehicle rentals', 'ckl-car-rental'),
        'labels'                => $labels,
        'supports'              => array('title', 'thumbnail', 'page-attributes'),
        'hierarchical'          => false,
        'public'                => false,
        'show_ui'               => true,
        'show_in_menu'          => 'edit.php?post_type=vehicle',
        'menu_position'         => 20,
        'menu_icon'             => 'dashicons-cart',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => false,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => true,
        'publicly_queryable'    => false,
        'capability_type'       => 'post',
        'show_in_rest'          => false,
    );

    register_post_type('vehicle_service', $args);
}
add_action('init', 'ckl_register_vehicle_service_post_type');

/**
 * Initialize default theme settings on activation
 */
function ckl_initialize_theme_settings() {
    // Initialize all default settings if they don't exist
    if (false === get_option('ckl_homepage_sections', false)) {
        update_option('ckl_homepage_sections', ckl_get_default_homepage_sections());
    }

    if (false === get_option('ckl_hero_settings', false)) {
        update_option('ckl_hero_settings', ckl_get_default_hero_settings());
    }

    if (false === get_option('ckl_vehicle_display_settings', false)) {
        update_option('ckl_vehicle_display_settings', ckl_get_default_vehicle_display_settings());
    }

    if (false === get_option('ckl_global_pricing', false)) {
        update_option('ckl_global_pricing', ckl_get_default_pricing_settings());
    }

    if (false === get_option('ckl_manual_reviews', false)) {
        update_option('ckl_manual_reviews', array());
    }
}
add_action('after_switch_theme', 'ckl_initialize_theme_settings');

/**
 * Auto-initialize settings if missing (with safeguard)
 *
 * Ensures homepage sections and related settings exist in database.
 * Runs on admin_init to check and initialize only if completely missing.
 */
function ckl_ensure_settings_initialized() {
    // Check if settings exist
    $sections = get_option('ckl_homepage_sections', false);

    // Only initialize if completely missing (not just empty)
    if (false === $sections) {
        // Initialize all settings
        update_option('ckl_homepage_sections', ckl_get_default_homepage_sections());
        update_option('ckl_hero_settings', ckl_get_default_hero_settings());
        update_option('ckl_vehicle_display_settings', ckl_get_default_vehicle_display_settings());
        update_option('ckl_global_pricing', ckl_get_default_pricing_settings());
        update_option('ckl_manual_reviews', array());
    }
}
add_action('admin_init', 'ckl_ensure_settings_initialized');

/**
 * Force front-page.php template for homepage
 *
 * Ensures front-page.php is always used for the homepage,
 * overriding any plugin template loaders (e.g., WooCommerce)
 */
function ckl_force_front_page_template($template) {
    global $wp_query;

    // Check if we're on the front page or home
    $is_front = (is_front_page() || is_home() || (isset($wp_query->is_front_page) && $wp_query->is_front_page) || (isset($wp_query->is_home) && $wp_query->is_home));

    if ($is_front) {
        // Get the front-page.php path
        $front_page_template = locate_template(array('front-page.php'));

        // If front-page.php exists, use it
        if (!empty($front_page_template)) {
            return $front_page_template;
        }
    }

    return $template;
}
add_filter('template_include', 'ckl_force_front_page_template', 999);

/**
 * Enqueue FAQ page scripts
 */
function ckl_enqueue_faq_scripts() {
    if (is_page('faq') || is_post_type_archive('faq')) {
        wp_enqueue_script(
            'ckl-faq-page',
            get_template_directory_uri() . '/assets/js/faq-page.js',
            array(),
            '1.0.0',
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'ckl_enqueue_faq_scripts');

/**
 * Calculate reading time for a post
 *
 * @param int $post_id Optional. Post ID. Defaults to current post.
 * @return string Reading time in format "X min read"
 */
function ckl_get_reading_time($post_id = 0) {
    $post_id = $post_id ? $post_id : get_the_ID();
    $content = get_post_field('post_content', $post_id);

    // Strip tags and get word count
    $word_count = str_word_count(strip_tags($content));

    // Calculate reading time (average 200 words per minute)
    $reading_time = ceil($word_count / 200);

    // Ensure minimum of 1 minute
    if ($reading_time < 1) {
        $reading_time = 1;
    }

    return sprintf(__('%d min read', 'ckl-car-rental'), $reading_time);
}

/**
 * Enqueue blog page scripts
 */
function ckl_enqueue_blog_scripts() {
    if (is_page_template('page-blog.php') || is_home() || is_archive()) {
        wp_enqueue_script(
            'ckl-blog-page',
            get_template_directory_uri() . '/assets/js/blog-page.js',
            array(),
            '1.0.0',
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'ckl_enqueue_blog_scripts');

/**
 * Get all vehicle meta in one call
 *
 * @param int $vehicle_id Vehicle post ID
 * @return array Vehicle meta data
 */
function ckl_get_vehicle_meta($vehicle_id) {
    return array(
        'type'                   => get_post_meta($vehicle_id, '_vehicle_type', true),
        'passenger_capacity'     => get_post_meta($vehicle_id, '_vehicle_passenger_capacity', true),
        'doors'                  => get_post_meta($vehicle_id, '_vehicle_doors', true),
        'luggage'                => get_post_meta($vehicle_id, '_vehicle_luggage', true),
        'has_air_conditioning'   => get_post_meta($vehicle_id, '_vehicle_has_air_conditioning', true),
        'transmission'           => get_post_meta($vehicle_id, '_vehicle_transmission', true),
        'fuel_type'              => get_post_meta($vehicle_id, '_vehicle_fuel_type', true),
        'plate_number'           => get_post_meta($vehicle_id, '_vehicle_plate_number', true),
        'units_available'        => get_post_meta($vehicle_id, '_vehicle_units_available', true),
        'price_per_day'          => get_post_meta($vehicle_id, '_vehicle_price_per_day', true),
        'price_per_hour'         => get_post_meta($vehicle_id, '_vehicle_price_per_hour', true),
        'minimum_booking_days'   => get_post_meta($vehicle_id, '_vehicle_minimum_booking_days', true) ?: 2,
        'late_fee_per_hour'      => get_post_meta($vehicle_id, '_vehicle_late_fee_per_hour', true),
        'woocommerce_product_id' => get_post_meta($vehicle_id, '_vehicle_woocommerce_product_id', true),
    );
}

/**
 * Get special pricing (filtered to remove expired)
 *
 * @param int $vehicle_id Vehicle post ID
 * @return array Special pricing offers
 */
function ckl_get_vehicle_special_pricing($vehicle_id) {
    $special_pricing = get_post_meta($vehicle_id, '_special_pricing', true);

    if (empty($special_pricing) || !is_array($special_pricing)) {
        return array();
    }

    $today = current_time('Y-m-d');
    return array_filter($special_pricing, function($pricing) use ($today) {
        return isset($pricing['end_date']) && $pricing['end_date'] >= $today;
    });
}

/**
 * Get vehicle amenities with defaults
 *
 * @param int $vehicle_id Vehicle post ID
 * @return array Vehicle amenities
 */
function ckl_get_vehicle_amenities($vehicle_id) {
    $amenities = get_post_meta($vehicle_id, '_vehicle_amenities', true);

    $defaults = array(
        'music_system' => false,
        'abs' => false,
        'bluetooth' => false,
        'usb_charger' => false,
        'gps_navigation' => false,
        'rear_camera' => false,
        'child_seat' => false,
        'sunroof' => false,
    );

    return wp_parse_args($amenities, $defaults);
}

/**
 * Get availability for date range
 *
 * @param int $vehicle_id Vehicle post ID
 * @param string $start_date Start date (Y-m-d format)
 * @param string $end_date End date (Y-m-d format)
 * @return array Availability data
 */
function ckl_get_vehicle_availability($vehicle_id, $start_date = null, $end_date = null) {
    $availability = get_post_meta($vehicle_id, '_vehicle_availability', true);

    if (empty($availability) || !is_array($availability)) {
        return array();
    }

    if ($start_date && $end_date) {
        $dates = array();
        $current = strtotime($start_date);
        $end = strtotime($end_date);

        while ($current <= $end) {
            $date_key = date('Y-m-d', $current);
            if (isset($availability[$date_key])) {
                $dates[$date_key] = $availability[$date_key];
            }
            $current = strtotime('+1 day', $current);
        }

        return $dates;
    }

    return $availability;
}

/**
 * Calculate rental price based on duration
 *
 * @param int $vehicle_id Vehicle post ID
 * @param int $pickup_timestamp Pickup timestamp
 * @param int $return_timestamp Return timestamp
 * @return array Price breakdown
 */
function ckl_calculate_rental_price($vehicle_id, $pickup_timestamp, $return_timestamp) {
    $price_per_day = floatval(get_post_meta($vehicle_id, '_vehicle_price_per_day', true));
    $price_per_hour = floatval(get_post_meta($vehicle_id, '_vehicle_price_per_hour', true));

    if (!$price_per_hour) {
        // Default to 1/24 of daily rate if hourly rate not set
        $price_per_hour = $price_per_day / 24;
    }

    $duration_seconds = $return_timestamp - $pickup_timestamp;
    $duration_days = floor($duration_seconds / (24 * 60 * 60));
    $duration_hours = ceil(($duration_seconds % (24 * 60 * 60)) / (60 * 60));

    // If there's any remainder hours, count them
    if ($duration_hours >= 24) {
        $duration_days++;
        $duration_hours = 0;
    }

    $daily_price = $duration_days * $price_per_day;
    $hourly_price = $duration_hours * $price_per_hour;
    $total_price = $daily_price + $hourly_price;

    return array(
        'duration_days' => $duration_days,
        'duration_hours' => $duration_hours,
        'price_per_day' => $price_per_day,
        'price_per_hour' => $price_per_hour,
        'daily_total' => $daily_price,
        'hourly_total' => $hourly_price,
        'total_price' => $total_price,
        'formatted_total' => 'RM ' . number_format($total_price, 2)
    );
}

/**
 * Check availability via AJAX
 */
function ckl_check_availability_ajax() {
    // Verify nonce
    if (!isset($_POST['availability_nonce']) || !wp_verify_nonce($_POST['availability_nonce'], 'ckl_vehicle_availability')) {
        wp_send_json_error(array('message' => __('Invalid security token', 'ckl-car-rental')));
    }

    $vehicle_id = intval($_POST['vehicle_id'] ?? 0);
    $pickup_date = sanitize_text_field($_POST['pickup_date'] ?? '');
    $return_date = sanitize_text_field($_POST['return_date'] ?? '');
    $pickup_time = sanitize_text_field($_POST['pickup_time'] ?? '10:00');
    $return_time = sanitize_text_field($_POST['return_time'] ?? '10:00');

    // Services parameter
    $selected_services = isset($_POST['services']) ? $_POST['services'] : array();

    if (!$vehicle_id || empty($pickup_date) || empty($return_date)) {
        wp_send_json_error(array('message' => __('Missing required fields', 'ckl-car-rental')));
    }

    // Validate dates
    if (!strtotime($pickup_date) || !strtotime($return_date)) {
        wp_send_json_error(array('message' => __('Invalid date format', 'ckl-car-rental')));
    }

    // Combine date and time
    $pickup_timestamp = strtotime($pickup_date . ' ' . $pickup_time);
    $return_timestamp = strtotime($return_date . ' ' . $return_time);

    if ($return_timestamp <= $pickup_timestamp) {
        wp_send_json_error(array('message' => __('Return date must be after pickup date', 'ckl-car-rental')));
    }

    // Check minimum booking duration
    $min_booking_days = intval(get_post_meta($vehicle_id, '_vehicle_minimum_booking_days', true) ?: 2);
    $min_seconds = $min_booking_days * 24 * 60 * 60;
    $duration_seconds = $return_timestamp - $pickup_timestamp;

    if ($duration_seconds < $min_seconds) {
        wp_send_json_error(array(
            'message' => sprintf(__('Minimum booking duration is %d days', 'ckl-car-rental'), $min_booking_days),
            'error_code' => 'min_booking_not_met'
        ));
    }

    // Check availability for each day in the range
    $availability = get_post_meta($vehicle_id, '_vehicle_availability', true);
    $current = strtotime($pickup_date);
    $end = strtotime($return_date);

    while ($current <= $end) {
        $date = date('Y-m-d', $current);
        if (isset($availability[$date]) && $availability[$date]['status'] === 'full') {
            wp_send_json_error(array(
                'message' => sprintf(__('Vehicle is not available on %s', 'ckl-car-rental'), date('F j, Y', $current)),
                'error_code' => 'not_available'
            ));
        }
        $current = strtotime('+1 day', $current);
    }

    // Calculate pricing
    $pricing = ckl_calculate_rental_price($vehicle_id, $pickup_timestamp, $return_timestamp);

    // Calculate service costs
    $service_costs = array('total' => 0, 'items' => array());
    if (!empty($selected_services)) {
        // Include the service calculation function from the plugin
        $plugin_path = WP_PLUGIN_DIR . '/ckl-car-rental/includes/class-vehicle-booking-ajax.php';
        if (file_exists($plugin_path) && !function_exists('ckl_calculate_service_costs')) {
            include_once($plugin_path);
        }

        if (function_exists('ckl_calculate_service_costs')) {
            $service_costs = ckl_calculate_service_costs($vehicle_id, $selected_services, $pickup_timestamp, $return_timestamp);
        }
    }

    // Update success response to include services
    $pricing['total_price'] = $pricing['total_price'] + $service_costs['total'];
    $pricing['formatted_total'] = 'RM ' . number_format($pricing['total_price'], 2);
    $pricing['services'] = array_values($service_costs['items']);
    $pricing['services_total'] = $service_costs['total'];

    wp_send_json_success($pricing);
}
add_action('wp_ajax_ckl_check_availability', 'ckl_check_availability_ajax');
add_action('wp_ajax_nopriv_ckl_check_availability', 'ckl_check_availability_ajax');

/**
 * Get calendar availability data via AJAX
 */
function ckl_get_calendar_availability_ajax() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'ckl_vehicle_calendar')) {
        wp_send_json_error(array('message' => __('Invalid security token', 'ckl-car-rental')));
    }

    $vehicle_id = intval($_POST['vehicle_id'] ?? 0);
    $month = sanitize_text_field($_POST['month'] ?? date('Y-m'));

    if (!$vehicle_id) {
        wp_send_json_error(array('message' => __('Invalid vehicle ID', 'ckl-car-rental')));
    }

    // Get availability data
    $availability = get_post_meta($vehicle_id, '_vehicle_availability', true);
    if (!is_array($availability)) {
        $availability = array();
    }

    // Get all days in the month
    $timestamp = strtotime($month . '-01');
    $days_in_month = cal_days_in_month(CAL_GREGORIAN, date('m', $timestamp), date('Y', $timestamp));
    $today = date('Y-m-d');

    $calendar_data = array();
    for ($day = 1; $day <= $days_in_month; $day++) {
        $date = date('Y-m-d', strtotime(date('Y-m-', $timestamp) . sprintf('%02d', $day)));
        $is_past = strtotime($date) < strtotime($today);

        if ($is_past) {
            $status = 'past';
        } elseif (isset($availability[$date])) {
            $status = $availability[$date]['status'];
        } else {
            $status = 'available';
        }

        $calendar_data[$day] = array(
            'date' => $date,
            'status' => $status
        );
    }

    wp_send_json_success(array(
        'month_name' => date('F Y', $timestamp),
        'days' => $calendar_data,
        'first_day' => date('w', $timestamp)
    ));
}
add_action('wp_ajax_ckl_get_calendar_availability', 'ckl_get_calendar_availability_ajax');
add_action('wp_ajax_nopriv_ckl_get_calendar_availability', 'ckl_get_calendar_availability_ajax');

/**
 * Redirect shop page to vehicles archive
 *
 * Redirects /shop/ to /vehicles/ for a better user experience
 */
function ckl_redirect_shop_to_vehicles() {
    // Check if this is the shop page
    if (function_exists('is_shop') && is_shop()) {
        wp_redirect(home_url('/vehicles/'), 301);
        exit;
    }
}
add_action('template_redirect', 'ckl_redirect_shop_to_vehicles');

/**
 * =============================================================================
 * WOOCOMMERCE AUTOMATION
 * =============================================================================
 */

/**
 * Auto-create WooCommerce product when vehicle is published
 */
function ckl_auto_create_vehicle_product($post_id, $post, $update) {
    if ($post->post_type !== 'vehicle') return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (wp_is_post_revision($post_id)) return;

    $existing_product_id = get_post_meta($post_id, '_vehicle_woocommerce_product_id', true);
    $price_per_day = get_post_meta($post_id, '_vehicle_price_per_day', true);

    if (empty($price_per_day)) return;

    // Update existing product
    if ($update && $existing_product_id) {
        ckl_sync_vehicle_to_product($post_id, $existing_product_id);
        return;
    }

    // Create new product
    $product_id = wp_insert_post(array(
        'post_title'   => $post->post_title,
        'post_content' => $post->post_content,
        'post_status'  => 'publish',
        'post_type'    => 'product',
        'post_excerpt' => get_the_excerpt($post_id),
    ));

    if (is_wp_error($product_id)) return;

    // Set as booking product if WC Bookings is active
    if (class_exists('WC_Bookings')) {
        wp_set_object_terms($product_id, 'booking', 'product_type');
    }

    // Set pricing
    update_post_meta($product_id, '_regular_price', $price_per_day);
    update_post_meta($product_id, '_price', $price_per_day);
    update_post_meta($product_id, '_stock_status', 'instock');
    update_post_meta($product_id, '_manage_stock', 'yes');

    // Sync inventory
    $units = get_post_meta($post_id, '_vehicle_units_available', true);
    if ($units) update_post_meta($product_id, '_stock', $units);

    // Set booking config if WC Bookings is active
    if (class_exists('WC_Bookings')) {
        update_post_meta($product_id, '_wc_booking_duration', '1');
        update_post_meta($product_id, '_wc_booking_duration_unit', 'day');
        update_post_meta($product_id, '_wc_booking_min_duration', '1');
        update_post_meta($product_id, '_wc_booking_max_duration', '30');
    }

    // Sync featured image
    $thumbnail_id = get_post_thumbnail_id($post_id);
    if ($thumbnail_id) set_post_thumbnail($product_id, $thumbnail_id);

    // Link bidirectionally
    update_post_meta($post_id, '_vehicle_woocommerce_product_id', $product_id);
    update_post_meta($product_id, '_linked_vehicle_id', $post_id);
}
add_action('save_post_vehicle', 'ckl_auto_create_vehicle_product', 10, 3);

/**
 * Sync vehicle data to product
 */
function ckl_sync_vehicle_to_product($vehicle_id, $product_id) {
    $vehicle = get_post($vehicle_id);

    wp_update_post(array(
        'ID' => $product_id,
        'post_title' => $vehicle->post_title,
        'post_content' => $vehicle->post_content,
    ));

    // Sync price
    $price = get_post_meta($vehicle_id, '_vehicle_price_per_day', true);
    if ($price) {
        update_post_meta($product_id, '_regular_price', $price);
        update_post_meta($product_id, '_price', $price);
    }

    // Sync stock
    $units = get_post_meta($vehicle_id, '_vehicle_units_available', true);
    if ($units !== '') {
        update_post_meta($product_id, '_stock', $units);
        update_post_meta($product_id, '_stock_status', $units > 0 ? 'instock' : 'outofstock');
    }

    // Sync image
    $thumbnail_id = get_post_thumbnail_id($vehicle_id);
    if ($thumbnail_id) set_post_thumbnail($product_id, $thumbnail_id);
}

/**
 * Sync inventory when vehicle meta changes
 */
function ckl_sync_vehicle_inventory($meta_id, $object_id, $meta_key) {
    if ($meta_key !== '_vehicle_units_available') return;

    $product_id = get_post_meta($object_id, '_vehicle_woocommerce_product_id', true);
    if (!$product_id) return;

    $units = get_post_meta($object_id, '_vehicle_units_available', true);
    update_post_meta($product_id, '_stock', $units);

    $product = wc_get_product($product_id);
    if ($product) {
        $product->set_stock_status($units > 0 ? 'instock' : 'outofstock');
        $product->save();
    }
}
add_action('updated_post_meta', 'ckl_sync_vehicle_inventory', 10, 3);

/**
 * Update availability when booking is created
 */
function ckl_booking_created_update_availability($booking_id) {
    if (!class_exists('WC_Booking')) return;

    $booking = get_wc_booking($booking_id);
    if (!$booking) return;

    $product = $booking->get_product();
    if (!$product) return;

    $vehicle_id = get_post_meta($product->get_id(), '_linked_vehicle_id', true);
    if (!$vehicle_id) return;

    $start = $booking->get_start_date('Y-m-d');
    $end = $booking->get_end_date('Y-m-d');

    $availability = get_post_meta($vehicle_id, '_vehicle_availability', true);
    if (!is_array($availability)) $availability = array();

    // Mark dates as fully booked
    $current = strtotime($start);
    $end_timestamp = strtotime($end);

    while ($current <= $end_timestamp) {
        $date = date('Y-m-d', $current);
        $availability[$date] = array(
            'status' => 'full',
            'booking_id' => $booking_id
        );
        $current = strtotime('+1 day', $current);
    }

    update_post_meta($vehicle_id, '_vehicle_availability', $availability);
}
add_action('wc_booking_created', 'ckl_booking_created_update_availability');

/**
 * Restore availability when booking is cancelled
 */
function ckl_booking_cancelled_restore_availability($booking_id) {
    if (!class_exists('WC_Booking')) return;

    $booking = get_wc_booking($booking_id);
    if (!$booking) return;

    $product = $booking->get_product();
    if (!$product) return;

    $vehicle_id = get_post_meta($product->get_id(), '_linked_vehicle_id', true);
    if (!$vehicle_id) return;

    $availability = get_post_meta($vehicle_id, '_vehicle_availability', true);
    if (!is_array($availability)) return;

    $units = get_post_meta($vehicle_id, '_vehicle_units_available', true);

    foreach ($availability as $date => $data) {
        if (isset($data['booking_id']) && $data['booking_id'] == $booking_id) {
            if ($units > 0) {
                $availability[$date]['status'] = 'available';
            } else {
                unset($availability[$date]);
            }
        }
    }

    update_post_meta($vehicle_id, '_vehicle_availability', $availability);
}
add_action('wc_booking_cancelled', 'ckl_booking_cancelled_restore_availability');

/**
 * =============================================================================
 * ADMIN META BOXES
 * =============================================================================
 */

/**
 * Register meta boxes for vehicles
 */
function ckl_vehicle_meta_boxes() {
    add_meta_box('vehicle_special_pricing', 'Special Pricing', 'ckl_special_pricing_mb', 'vehicle', 'normal', 'default');
    add_meta_box('vehicle_amenities', 'Amenities', 'ckl_amenities_mb', 'vehicle', 'normal', 'default');
    add_meta_box('vehicle_pricing_details', 'Pricing Details', 'ckl_pricing_details_mb', 'vehicle', 'side', 'high');
}
add_action('add_meta_boxes_vehicle', 'ckl_vehicle_meta_boxes');

/**
 * Special pricing meta box callback
 */
function ckl_special_pricing_mb($post) {
    wp_nonce_field('ckl_special_pricing', 'sp_nonce');

    $pricing = get_post_meta($post->ID, '_special_pricing', true);
    if (!is_array($pricing)) $pricing = array();
    ?>
    <div id="sp-container">
        <?php foreach ($pricing as $i => $p) : ?>
            <div class="sp-row" style="margin-bottom:10px; padding:10px; border:1px solid #ddd; background:#f9f9f9;">
                <table class="form-table">
                    <tr>
                        <th><label>Offer Name</label></th>
                        <td><input type="text" name="sp[<?php echo $i; ?>][name]" placeholder="e.g., Early Bird Promo" value="<?php echo esc_attr($p['name']); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th><label>Start Date</label></th>
                        <td><input type="date" name="sp[<?php echo $i; ?>][start]" value="<?php echo esc_attr($p['start_date']); ?>"></td>
                    </tr>
                    <tr>
                        <th><label>End Date</label></th>
                        <td><input type="date" name="sp[<?php echo $i; ?>][end]" value="<?php echo esc_attr($p['end_date']); ?>"></td>
                    </tr>
                    <tr>
                        <th><label>Special Price (RM/day)</label></th>
                        <td><input type="number" name="sp[<?php echo $i; ?>][price]" value="<?php echo esc_attr($p['price']); ?>" step="0.01" min="0"></td>
                    </tr>
                </table>
                <button type="button" class="button remove-sp" style="margin-top:10px;">Remove This Offer</button>
            </div>
        <?php endforeach; ?>
    </div>
    <button type="button" class="button" id="add-sp" style="margin-top:10px;">+ Add Pricing Offer</button>
    <script>
    jQuery(document).ready(function($) {
        $('#add-sp').click(function() {
            var index = Date.now();
            $('#sp-container').append(`
                <div class="sp-row" style="margin-bottom:10px; padding:10px; border:1px solid #ddd; background:#f9f9f9;">
                    <table class="form-table">
                        <tr>
                            <th><label>Offer Name</label></th>
                            <td><input type="text" name="sp[${index}][name]" placeholder="e.g., Early Bird Promo" class="regular-text"></td>
                        </tr>
                        <tr>
                            <th><label>Start Date</label></th>
                            <td><input type="date" name="sp[${index}][start]"></td>
                        </tr>
                        <tr>
                            <th><label>End Date</label></th>
                            <td><input type="date" name="sp[${index}][end]"></td>
                        </tr>
                        <tr>
                            <th><label>Special Price (RM/day)</label></th>
                            <td><input type="number" name="sp[${index}][price]" step="0.01" min="0"></td>
                        </tr>
                    </table>
                    <button type="button" class="button remove-sp" style="margin-top:10px;">Remove This Offer</button>
                </div>
            `);
        });
        $(document).on('click', '.remove-sp', function() {
            $(this).closest('.sp-row').remove();
        });
    });
    </script>
    <style>
    #sp-container .form-table { margin: 0; }
    #sp-container th { width: 150px; }
    </style>
    <?php
}

/**
 * Amenities meta box callback
 */
function ckl_amenities_mb($post) {
    wp_nonce_field('ckl_amenities', 'amenities_nonce');

    $amenities = get_post_meta($post->ID, '_vehicle_amenities', true);
    $list = array(
        'music_system' => 'Music System',
        'abs' => 'ABS (Anti-lock Braking System)',
        'bluetooth' => 'Bluetooth Connectivity',
        'usb_charger' => 'USB Charger',
        'gps_navigation' => 'GPS Navigation',
        'rear_camera' => 'Rear Camera',
        'child_seat' => 'Child Seat Available',
    );
    ?>
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; max-width:600px;">
        <?php foreach ($list as $key => $label) : ?>
            <label style="display:flex; align-items:center; padding:5px 0;">
                <input type="checkbox" name="amenities[<?php echo $key; ?>]" value="1"
                    <?php checked(isset($amenities[$key]) ? $amenities[$key] : false); ?>
                    style="margin-right:8px;">
                <?php echo esc_html($label); ?>
            </label>
        <?php endforeach; ?>
    </div>
    <?php
}

/**
 * Pricing details meta box callback
 */
function ckl_pricing_details_mb($post) {
    wp_nonce_field('ckl_pricing_details', 'pricing_details_nonce');

    $price_per_hour = get_post_meta($post->ID, '_vehicle_price_per_hour', true);
    $min_booking_days = get_post_meta($post->ID, '_vehicle_minimum_booking_days', true) ?: 2;
    $late_fee_per_hour = get_post_meta($post->ID, '_vehicle_late_fee_per_hour', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="price_per_hour">Hourly Rate (RM)</label></th>
            <td>
                <input type="number" name="price_per_hour" id="price_per_hour"
                       value="<?php echo esc_attr($price_per_hour); ?>"
                       step="0.01" min="0" class="regular-text">
                <p class="description">Rate charged for partial days (e.g., 2 days 3 hours)</p>
            </td>
        </tr>
        <tr>
            <th><label for="minimum_booking_days">Min. Booking Days</label></th>
            <td>
                <input type="number" name="minimum_booking_days" id="minimum_booking_days"
                       value="<?php echo esc_attr($min_booking_days); ?>"
                       step="1" min="1" max="30" class="regular-text">
                <p class="description">Minimum rental duration in days</p>
            </td>
        </tr>
        <tr>
            <th><label for="late_fee_per_hour">Late Fee (RM/hour)</label></th>
            <td>
                <input type="number" name="late_fee_per_hour" id="late_fee_per_hour"
                       value="<?php echo esc_attr($late_fee_per_hour); ?>"
                       step="0.01" min="0" class="regular-text">
                <p class="description">Fee for late returns (optional)</p>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Save vehicle meta boxes
 */
function ckl_save_vehicle_meta($post_id) {
    // Don't save on autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    // Check permissions
    if (!current_user_can('edit_post', $post_id)) return;

    // Save special pricing
    if (isset($_POST['sp']) && isset($_POST['sp_nonce'])) {
        if (!wp_verify_nonce($_POST['sp_nonce'], 'ckl_special_pricing')) return;

        $sanitized = array();
        if (is_array($_POST['sp'])) {
            foreach ($_POST['sp'] as $p) {
                if (!empty($p['name']) && !empty($p['start']) && !empty($p['end']) && !empty($p['price'])) {
                    $sanitized[] = array(
                        'name' => sanitize_text_field($p['name']),
                        'start_date' => sanitize_text_field($p['start']),
                        'end_date' => sanitize_text_field($p['end']),
                        'price' => floatval($p['price']),
                    );
                }
            }
        }
        update_post_meta($post_id, '_special_pricing', $sanitized);
    }

    // Save amenities
    if (isset($_POST['amenities']) && isset($_POST['amenities_nonce'])) {
        if (!wp_verify_nonce($_POST['amenities_nonce'], 'ckl_amenities')) return;

        $amenities = array();
        if (is_array($_POST['amenities'])) {
            foreach ($_POST['amenities'] as $key => $value) {
                $amenities[$key] = true;
            }
        }
        update_post_meta($post_id, '_vehicle_amenities', $amenities);
    }

    // Save pricing details
    if (isset($_POST['pricing_details_nonce'])) {
        if (!wp_verify_nonce($_POST['pricing_details_nonce'], 'ckl_pricing_details')) return;

        if (isset($_POST['price_per_hour'])) {
            update_post_meta($post_id, '_vehicle_price_per_hour', floatval($_POST['price_per_hour']));
        }
        if (isset($_POST['minimum_booking_days'])) {
            update_post_meta($post_id, '_vehicle_minimum_booking_days', intval($_POST['minimum_booking_days']));
        }
        if (isset($_POST['late_fee_per_hour'])) {
            update_post_meta($post_id, '_vehicle_late_fee_per_hour', floatval($_POST['late_fee_per_hour']));
        }
    }
}
add_action('save_post_vehicle', 'ckl_save_vehicle_meta', 20, 1);

/**
 * =============================================================================
 * ENQUEUE VEHICLE SINGLE PAGE SCRIPTS
 * =============================================================================
 */

/**
 * Enqueue vehicle single page scripts
 */
function ckl_enqueue_vehicle_single_scripts() {
    if (is_singular('vehicle')) {
        wp_enqueue_script(
            'ckl-vehicle-single',
            get_template_directory_uri() . '/assets/js/vehicle-single.js',
            array('jquery'),
            '1.0.0',
            true
        );

        // Enqueue gallery script
        wp_enqueue_script(
            'ckl-vehicle-gallery',
            get_template_directory_uri() . '/assets/js/vehicle-gallery.js',
            array('jquery'),
            '1.0.0',
            true
        );

        // Localize script for AJAX
        wp_localize_script('ckl-vehicle-single', 'cklVehicleData', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ckl_vehicle_calendar'),
            'availability_nonce' => wp_create_nonce('ckl_vehicle_availability'),
            'booking_nonce' => wp_create_nonce('ckl_booking_form'),
            'vehicle_id' => get_queried_object_id(),
        ));
    }
}
add_action('wp_enqueue_scripts', 'ckl_enqueue_vehicle_single_scripts');

/**
 * =============================================================================
 * WOOCOMMERCE BREADCRUMB CUSTOMIZATION
 * =============================================================================
 */

/**
 * Customize WooCommerce breadcrumb defaults - remove wrapping tags
 */
function ckl_woocommerce_breadcrumb_defaults($defaults) {
	$defaults['wrap_before'] = '';
	$defaults['wrap_after']  = '';
	$defaults['before']      = '';
	$defaults['after']       = '';
	$defaults['delimiter']   = '';
	$defaults['home']        = __('Home', 'woocommerce');
	return $defaults;
}
add_filter('woocommerce_breadcrumb_defaults', 'ckl_woocommerce_breadcrumb_defaults');

/**
 * Remove default WooCommerce breadcrumb output and replace with custom
 * We use our custom template instead
 */
function ckl_setup_woocommerce_breadcrumb() {
	// Remove default breadcrumb
	remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

	// Add our custom breadcrumb with clean output
	add_action('woocommerce_before_main_content', function() {
		if (function_exists('woocommerce_breadcrumb')) {
			woocommerce_breadcrumb(array(
				'wrap_before' => '',
				'wrap_after'  => '',
				'before'      => '',
				'after'       => '',
				'delimiter'   => '',
			));
		}
	}, 5);
}
add_action('init', 'ckl_setup_woocommerce_breadcrumb');

/**
 * =============================================================================
 * HELPER FUNCTIONS (moved from admin/theme-settings.php for frontend access)
 * =============================================================================
 */

/**
 * Get default homepage sections
 */
function ckl_get_default_homepage_sections() {
    return array(
        'hero' => array('enabled' => true, 'order' => 1),
        'mobile_search' => array('enabled' => true, 'order' => 2),
        'how_it_works' => array('enabled' => true, 'order' => 3),
        'vehicle_grid' => array('enabled' => true, 'order' => 4),
        'reviews' => array('enabled' => true, 'order' => 5),
        'faq' => array('enabled' => true, 'order' => 6),
        'news_section' => array('enabled' => true, 'order' => 7),
    );
}

/**
 * Get default hero settings
 */
function ckl_get_default_hero_settings() {
    return array(
        'title' => __('Car Rental in Langkawi', 'ckl-car-rental'),
        'subtitle' => __('Explore Langkawi with the perfect vehicle. From compact cars to luxury MPVs, we have it all.', 'ckl-car-rental'),
        'background_images' => array(),
        'overlay_opacity' => 50,
        'show_search_form' => true,
        'search_button_text' => __('Search Vehicles', 'ckl-car-rental'),
    );
}

/**
 * Get default vehicle display settings
 */
function ckl_get_default_vehicle_display_settings() {
    return array(
        'number_of_vehicles' => 8,
        'sort_by' => 'date',
        'sort_order' => 'DESC',
        'show_category_tabs' => true,
        'featured_vehicles_only' => false,
        'grid_columns' => 4,
    );
}

/**
 * Get default pricing settings
 */
function ckl_get_default_pricing_settings() {
    return array(
        'default_hourly_rate' => 15.00,
        'daily_rate_multiplier' => 4,
        'weekly_discount' => 10,
        'monthly_discount' => 20,
        'seasonal_pricing' => array(),
        'vehicle_type_multipliers' => array(
            'sedan' => 1.0,
            'compact' => 0.8,
            'mpv' => 1.3,
            'luxury_mpv' => 1.8,
            'suv' => 1.5,
            '4x4' => 1.7,
            'scooter' => 0.3,
            'moped' => 0.25,
            'sports_bike' => 0.5,
        ),
    );
}

/**
 * =============================================================================
 * CUSTOMER DASHBOARD HELPER FUNCTIONS
 * =============================================================================
 */

/**
 * Get customer booking statistics
 *
 * @param int $user_id User ID
 * @return array Statistics array
 */
function ckl_get_customer_booking_stats($user_id) {
    $stats = array(
        'active' => 0,
        'completed' => 0,
        'cancelled' => 0,
        'total_spent' => 0,
        'next_pickup' => null,
    );

    if (!class_exists('WC_Booking')) {
        return $stats;
    }

    // Get customer bookings
    $bookings = ckl_get_customer_bookings($user_id, array('limit' => -1));

    foreach ($bookings as $booking) {
        if (!$booking instanceof WC_Booking) {
            continue;
        }

        $status = $booking->get_status();
        $order = wc_get_order($booking->get_order_id());

        // Count by status
        if (in_array($status, array('pending-confirmation', 'confirmed', 'paid', 'in-progress'))) {
            $stats['active']++;
        } elseif ($status === 'complete') {
            $stats['completed']++;
        } elseif ($status === 'cancelled') {
            $stats['cancelled']++;
        }

        // Add to total spent
        if ($order && !$order->has_status(array('cancelled', 'refunded'))) {
            $stats['total_spent'] += $order->get_total();
        }

        // Find next pickup
        if (in_array($status, array('confirmed', 'paid')) && !$stats['next_pickup']) {
            $start_date = $booking->get_start_date();
            if ($start_date && strtotime($start_date) >= current_time('timestamp')) {
                $stats['next_pickup'] = date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($start_date));
            }
        }
    }

    return $stats;
}

/**
 * Get customer bookings
 *
 * @param int $user_id User ID
 * @param array $args Query arguments
 * @return array Array of WC_Booking objects
 */
function ckl_get_customer_bookings($user_id, $args = array()) {
    $defaults = array(
        'limit' => 10,
        'status' => array(),
        'orderby' => 'start_date',
        'order' => 'DESC',
    );

    $args = wp_parse_args($args, $defaults);

    if (!class_exists('WC_Booking')) {
        return array();
    }

    // Build query args
    $query_args = array(
        'post_type' => 'wc_booking',
        'posts_per_page' => $args['limit'],
        'orderby' => $args['orderby'],
        'order' => $args['order'],
    );

    // Add status filter
    if (!empty($args['status'])) {
        $query_args['post_status'] = $args['status'];
    }

    // Filter by customer (via order)
    $query_args['meta_query'] = array(
        array(
            'key' => '_booking_customer_id',
            'value' => $user_id,
            'compare' => '=',
        ),
    );

    // Get bookings
    $bookings = get_posts($query_args);
    $booking_objects = array();

    foreach ($bookings as $booking_post) {
        $booking = get_wc_booking($booking_post->ID);
        if ($booking) {
            // Double-check ownership via order
            $order = wc_get_order($booking->get_order_id());
            if ($order && $order->get_user_id() === $user_id) {
                $booking_objects[] = $booking;
            }
        }
    }

    return $booking_objects;
}

/**
 * Get bookings by guest email/phone
 *
 * @param string $email Guest email address
 * @param string $phone Guest phone number (optional)
 * @param array $args Query arguments
 * @return array Array of WC_Booking objects
 */
function ckl_get_guest_bookings($email, $phone = '', $args = array()) {
    $defaults = array(
        'limit' => 10,
        'status' => array(),
        'orderby' => 'start_date',
        'order' => 'DESC',
    );

    $args = wp_parse_args($args, $defaults);

    if (!class_exists('WC_Booking') || empty($email)) {
        return array();
    }

    // Get all orders with this billing email
    $order_args = array(
        'limit' => $args['limit'],
        'type' => 'shop_order',
        'billing_email' => $email,
    );

    $orders = wc_get_orders($order_args);
    $booking_objects = array();

    foreach ($orders as $order) {
        // If phone provided, verify it matches
        if (!empty($phone)) {
            $order_phone = $order->get_billing_phone();
            // Simple phone comparison (remove spaces, dashes, etc.)
            $normalized_order_phone = preg_replace('/[^0-9]/', '', $order_phone);
            $normalized_phone = preg_replace('/[^0-9]/', '', $phone);

            if ($normalized_order_phone !== $normalized_phone) {
                continue;
            }
        }

        // Get bookings for this order
        $booking_ids = WC_Booking_Data_Store::get_booking_ids_from_order_id($order->get_id());

        foreach ($booking_ids as $booking_id) {
            $booking = get_wc_booking($booking_id);
            if ($booking) {
                // Filter by status if specified
                if (!empty($args['status']) && !in_array($booking->get_status(), $args['status'])) {
                    continue;
                }

                $booking_objects[] = $booking;
            }
        }
    }

    // Sort bookings
    usort($booking_objects, function($a, $b) use ($args) {
        $date_a = $a->get_start_date();
        $date_b = $b->get_start_date();

        if ($args['order'] === 'DESC') {
            return strtotime($date_b) - strtotime($date_a);
        } else {
            return strtotime($date_a) - strtotime($date_b);
        }
    });

    // Apply limit
    if ($args['limit'] > 0) {
        $booking_objects = array_slice($booking_objects, 0, $args['limit']);
    }

    return $booking_objects;
}

/**
 * Get bookings for a customer (supports both logged-in and guest)
 *
 * @param int|string $identifier User ID or email for guest
 * @param string $type Type of identifier: 'user_id' or 'email'
 * @param array $args Query arguments
 * @return array Array of WC_Booking objects
 */
function ckl_get_bookings($identifier, $type = 'user_id', $args = array()) {
    if ($type === 'email') {
        return ckl_get_guest_bookings($identifier, '', $args);
    } else {
        return ckl_get_customer_bookings($identifier, $args);
    }
}

/**
 * Verify guest booking ownership
 *
 * @param int $booking_id Booking ID
 * @param string $email Guest email
 * @param string $phone Guest phone (optional)
 * @return bool True if booking belongs to guest
 */
function ckl_verify_guest_booking_ownership($booking_id, $email, $phone = '') {
    if (!class_exists('WC_Booking')) {
        return false;
    }

    $booking = get_wc_booking($booking_id);
    if (!$booking) {
        return false;
    }

    $order = wc_get_order($booking->get_order_id());
    if (!$order) {
        return false;
    }

    // Check email
    if ($order->get_billing_email() !== $email) {
        return false;
    }

    // Check phone if provided
    if (!empty($phone)) {
        $order_phone = $order->get_billing_phone();
        $normalized_order_phone = preg_replace('/[^0-9]/', '', $order_phone);
        $normalized_phone = preg_replace('/[^0-9]/', '', $phone);

        if ($normalized_order_phone !== $normalized_phone) {
            return false;
        }
    }

    return true;
}

/**
 * Get booking status label
 *
 * @param string $status Booking status
 * @return string Status label
 */
function ckl_get_booking_status_label($status) {
    $labels = array(
        'pending-confirmation' => __('Pending', 'ckl-car-rental'),
        'confirmed' => __('Confirmed', 'ckl-car-rental'),
        'paid' => __('Paid', 'ckl-car-rental'),
        'complete' => __('Completed', 'ckl-car-rental'),
        'in-progress' => __('In Progress', 'ckl-car-rental'),
        'cancelled' => __('Cancelled', 'ckl-car-rental'),
    );

    return isset($labels[$status]) ? $labels[$status] : __('Unknown', 'ckl-car-rental');
}

/**
 * Get booking status color class
 *
 * @param string $status Booking status
 * @return string Tailwind color classes
 */
function ckl_get_booking_status_color($status) {
    $colors = array(
        'pending-confirmation' => 'bg-yellow-100 text-yellow-800',
        'confirmed' => 'bg-blue-100 text-blue-800',
        'paid' => 'bg-green-100 text-green-800',
        'complete' => 'bg-green-100 text-green-800',
        'in-progress' => 'bg-purple-100 text-purple-800',
        'cancelled' => 'bg-red-100 text-red-800',
    );

    return isset($colors[$status]) ? $colors[$status] : 'bg-gray-100 text-gray-800';
}

/**
 * Get booking details
 *
 * @param int $booking_id Booking ID
 * @return array|null Booking details or null if not found
 */
function ckl_get_booking_details($booking_id) {
    if (!class_exists('WC_Booking')) {
        return null;
    }

    $booking = get_wc_booking($booking_id);

    if (!$booking) {
        return null;
    }

    $order = wc_get_order($booking->get_order_id());
    $vehicle_id = get_post_meta($booking_id, '_booking_vehicle_id', true);
    $pickup_location = get_post_meta($booking_id, '_pickup_location', true);
    $return_location = get_post_meta($booking_id, '_return_location', true);

    return array(
        'booking_id' => $booking_id,
        'order_id' => $booking->get_order_id(),
        'vehicle_id' => $vehicle_id,
        'vehicle_name' => $vehicle_id ? get_the_title($vehicle_id) : '',
        'vehicle_image' => $vehicle_id ? get_the_post_thumbnail_url($vehicle_id, 'large') : '',
        'start_date' => $booking->get_start_date(),
        'end_date' => $booking->get_end_date(),
        'start_date_formatted' => date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($booking->get_start_date())),
        'end_date_formatted' => date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($booking->get_end_date())),
        'pickup_location' => $pickup_location,
        'return_location' => $return_location,
        'duration_days' => get_post_meta($booking_id, '_rental_duration_days', true),
        'duration_hours' => get_post_meta($booking_id, '_rental_duration_hours', true),
        'status' => $booking->get_status(),
        'customer_id' => $order ? $order->get_user_id() : 0,
        'total' => $order ? $order->get_total() : 0,
        'order' => $order,
    );
}

/**
 * =============================================================================
 * REMOVE UNWANTED HEADER ELEMENTS FROM MY-ACCOUNT PAGES
 * =============================================================================
 */

/**
 * Remove WooCommerce default page title on my-account pages
 */
function ckl_remove_account_page_title() {
    if (!function_exists('is_account_page')) {
        return;
    }

    if (is_account_page()) {
        // Remove WooCommerce default navigation (we have custom sidebar)
        remove_action('woocommerce_account_content', 'woocommerce_account_navigation', 10);

        // Remove breadcrumb on my-account pages
        remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
        remove_action('woocommerce_before_main_content', 'ckl_setup_woocommerce_breadcrumb', 5);
    }
}
add_action('template_redirect', 'ckl_remove_account_page_title', 9);

/**
 * Render my-account endpoint content
 * Loads the appropriate template for each WooCommerce account endpoint
 */
function ckl_account_endpoint_content() {
    // Get current endpoint using the same method as navigation.php
    $request_uri = $_SERVER['REQUEST_URI'];
    $path = parse_url($request_uri, PHP_URL_PATH);
    $my_account_url = wc_get_page_permalink('myaccount');
    $my_account_path = parse_url($my_account_url, PHP_URL_PATH);

    if (strpos($path, $my_account_path) === 0) {
        $endpoint_part = substr($path, strlen($my_account_path));
        $endpoint_part = trim($endpoint_part, '/');
        $endpoint_part = explode('/', $endpoint_part);
        $endpoint = $endpoint_part[0] ?: 'dashboard';
    } else {
        $endpoint = 'dashboard';
    }

    // Map endpoints to their template files
    $endpoint_templates = array(
        'dashboard' => 'dashboard.php',
        'orders'    => 'orders.php',
        'bookings'  => 'bookings.php',
        'profile'   => 'profile.php',
        'support'   => 'support.php',
        'documents' => 'documents.php',
        'edit-account' => 'edit-account.php',
        'customer-logout' => 'logout.php',
    );

    // Get template file for this endpoint
    $template_file = $endpoint_templates[$endpoint] ?? 'dashboard.php';

    // =====================================================================
    // Set up variables for WooCommerce endpoints that need them
    // =====================================================================
    $template_args = array();

    if ($endpoint === 'orders') {
        // Get current page from query var
        $current_page = empty(get_query_var('orders')) ? 1 : absint(get_query_var('orders'));

        // Get customer orders
        $customer_orders = wc_get_orders(
            apply_filters(
                'woocommerce_my_account_my_orders_query',
                array(
                    'customer' => get_current_user_id(),
                    'page'     => $current_page,
                    'paginate' => true,
                )
            )
        );

        // Set up template arguments that WooCommerce normally provides
        $template_args = array(
            'current_page'    => absint($current_page),
            'customer_orders' => $customer_orders,
            'has_orders'      => 0 < $customer_orders->total,
            'wp_button_class' => wc_wp_theme_get_element_class_name('button')
                ? ' ' . wc_wp_theme_get_element_class_name('button')
                : '',
        );
    }

    // =====================================================================
    // Load the template with proper variables
    // =====================================================================
    $template_path = locate_template(array(
        'woocommerce/myaccount/' . $template_file
    ));

    if ($template_path) {
        // For theme templates, extract variables into local scope
        if (!empty($template_args)) {
            extract($template_args);
        }
        include $template_path;
    } else {
        // For WooCommerce core templates, pass variables explicitly
        if (!empty($template_args)) {
            wc_get_template('myaccount/' . $template_file, $template_args);
        } else {
            wc_get_template('myaccount/' . $template_file);
        }
    }
}

/**
 * Add custom body class to logged-in my-account page only
 */
function ckl_my_account_logged_in_body_class($classes) {
    if (function_exists('is_account_page') && is_account_page() && is_user_logged_in()) {
        $classes[] = 'ckl-my-account-logged-in';
    }
    return $classes;
}
add_filter('body_class', 'ckl_my_account_logged_in_body_class');

/**
 * Add CSS to hide unwanted elements on my-account pages
 *
 * NOTE: Function removed as part of implementing consistent header across all pages.
 * The main site header now appears on all pages including My Account.
 */
// DEPRECATED: This function has been removed.

/**
 * Display current template file indicator for admins
 */
function ckl_show_template_file_indicator() {
    // Only show for administrators
    if (!current_user_can('manage_options')) {
        return;
    }

    // Get current template file
    global $template;

    // Use WordPress's built-in function to normalize paths
    $template_path = wp_normalize_path($template);
    $theme_path = wp_normalize_path(get_template_directory());

    // Get relative path from theme root
    if (strpos($template_path, $theme_path) === 0) {
        $template_path = substr($template_path, strlen($theme_path) + 1);
    } else {
        // Fallback: try stylesheet directory (for child themes)
        $stylesheet_path = wp_normalize_path(get_stylesheet_directory());
        if (strpos($template_path, $stylesheet_path) === 0) {
            $template_path = substr($template_path, strlen($stylesheet_path) + 1);
        }
    }
    ?>
    <div id="ckl-template-indicator" class="ckl-template-indicator">
        <div class="ckl-template-toggle" onclick="cklToggleTemplateInfo()">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
            </svg>
        </div>
        <div class="ckl-template-info" id="ckl-template-info">
            <div class="ckl-template-header">
                <span>Current Template</span>
                <button onclick="cklCloseTemplateInfo()">&times;</button>
            </div>
            <div class="ckl-template-path" onclick="cklCopyTemplatePath()" title="Click to copy">
                <code id="ckl-template-path-text"><?php echo esc_html($template_path); ?></code>
                <span class="ckl-copy-hint">Click to copy</span>
            </div>
        </div>
    </div>

    <style>
        .ckl-template-indicator {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 99999;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            font-size: 13px;
        }

        .ckl-template-toggle {
            width: 44px;
            height: 44px;
            background: #cc2e28;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(204, 46, 40, 0.4);
            transition: transform 0.2s, background 0.2s;
        }

        .ckl-template-toggle:hover {
            transform: scale(1.1);
            background: #a8241f;
        }

        .ckl-template-info {
            display: none;
            position: absolute;
            bottom: 54px;
            right: 0;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            min-width: 300px;
            max-width: 400px;
            overflow: hidden;
        }

        .ckl-template-info.show {
            display: block;
        }

        .ckl-template-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 16px;
            background: #f3f4f6;
            border-bottom: 1px solid #e5e7eb;
            font-weight: 600;
            color: #374151;
        }

        .ckl-template-header button {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: #6b7280;
            padding: 0;
            line-height: 1;
        }

        .ckl-template-header button:hover {
            color: #374151;
        }

        .ckl-template-path {
            padding: 16px;
            cursor: pointer;
            position: relative;
            transition: background 0.2s;
        }

        .ckl-template-path:hover {
            background: #f9fafb;
        }

        .ckl-template-path code {
            display: block;
            color: #cc2e28;
            word-break: break-all;
            font-family: "Monaco", "Menlo", monospace;
            font-size: 11px;
        }

        .ckl-copy-hint {
            display: block;
            margin-top: 8px;
            font-size: 11px;
            color: #9ca3af;
        }

        .ckl-template-info.copied .ckl-copy-hint::after {
            content: " ✓ Copied!";
            color: #10b981;
        }
    </style>

    <script>
        function cklToggleTemplateInfo() {
            document.getElementById('ckl-template-info').classList.toggle('show');
        }

        function cklCloseTemplateInfo() {
            document.getElementById('ckl-template-info').classList.remove('show');
        }

        function cklCopyTemplatePath() {
            const pathText = document.getElementById('ckl-template-path-text').textContent;
            navigator.clipboard.writeText(pathText).then(() => {
                const info = document.getElementById('ckl-template-info');
                info.classList.add('copied');
                setTimeout(() => info.classList.remove('copied'), 2000);
            });
        }
    </script>
    <?php
}
add_action('wp_footer', 'ckl_show_template_file_indicator');