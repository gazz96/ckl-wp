<?php
/**
 * CKL Customer Dashboard AJAX Handlers
 *
 * Handles AJAX requests for customer dashboard actions
 *
 * @package CKL_Car_Rental
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

class CKL_Customer_Dashboard_AJAX {

    /**
     * Initialize AJAX handlers
     */
    public static function init() {
        // Cancel booking
        add_action('wp_ajax_ckl_cancel_booking', array(__CLASS__, 'cancel_booking'));
        add_action('wp_ajax_nopriv_ckl_cancel_booking', array(__CLASS__, 'must_login'));

        // Update profile
        add_action('wp_ajax_ckl_update_profile', array(__CLASS__, 'update_profile'));
        add_action('wp_ajax_nopriv_ckl_update_profile', array(__CLASS__, 'must_login'));

        // Upload document
        add_action('wp_ajax_ckl_upload_document', array(__CLASS__, 'upload_document'));
        add_action('wp_ajax_nopriv_ckl_upload_document', array(__CLASS__, 'must_login'));

        // Send support message
        add_action('wp_ajax_ckl_send_support', array(__CLASS__, 'send_support'));
        add_action('wp_ajax_nopriv_ckl_send_support', array(__CLASS__, 'must_login'));
    }

    /**
     * Must login error
     */
    public static function must_login() {
        wp_send_json_error(array('message' => __('You must be logged in to perform this action.', 'ckl-car-rental')));
    }

    /**
     * Cancel booking
     */
    public static function cancel_booking() {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'ckl_cancel_booking_' . intval($_POST['booking_id']))) {
            wp_send_json_error(array('message' => __('Invalid security token.', 'ckl-car-rental')));
        }

        $booking_id = intval($_POST['booking_id']);
        $user_id = get_current_user_id();

        if (!$user_id) {
            wp_send_json_error(array('message' => __('You must be logged in to cancel a booking.', 'ckl-car-rental')));
        }

        if (!class_exists('WC_Booking')) {
            wp_send_json_error(array('message' => __('Booking system not available.', 'ckl-car-rental')));
        }

        // Get booking
        $booking = get_wc_booking($booking_id);

        if (!$booking) {
            wp_send_json_error(array('message' => __('Booking not found.', 'ckl-car-rental')));
        }

        // Verify ownership
        if (!ckl_verify_booking_ownership($booking_id, $user_id)) {
            wp_send_json_error(array('message' => __('You do not have permission to cancel this booking.', 'ckl-car-rental')));
        }

        // Check if cancellable
        $status = $booking->get_status();
        if (!in_array($status, array('pending-confirmation', 'confirmed', 'paid'))) {
            wp_send_json_error(array('message' => __('This booking cannot be cancelled.', 'ckl-car-rental')));
        }

        // Cancel booking
        $booking->update_status('cancelled');

        // Add order note
        $order_id = $booking->get_order_id();
        if ($order_id) {
            $order = wc_get_order($order_id);
            if ($order) {
                $order->add_order_note(
                    sprintf(
                        __('Booking #%d cancelled by customer.', 'ckl-car-rental'),
                        $booking_id
                    )
                );
            }
        }

        // Send confirmation email (optional)
        do_action('ckl_booking_cancelled_by_customer', $booking_id, $user_id);

        wp_send_json_success(array(
            'message' => __('Booking cancelled successfully.', 'ckl-car-rental'),
            'booking_id' => $booking_id,
        ));
    }

    /**
     * Update profile
     */
    public static function update_profile() {
        // Verify nonce
        if (!isset($_POST['ckl_profile_nonce']) || !wp_verify_nonce($_POST['ckl_profile_nonce'], 'ckl_update_profile')) {
            wp_send_json_error(array('message' => __('Invalid security token.', 'ckl-car-rental')));
        }

        $user_id = get_current_user_id();

        if (!$user_id) {
            wp_send_json_error(array('message' => __('You must be logged in.', 'ckl-car-rental')));
        }

        // Sanitize and validate input
        $first_name = sanitize_text_field($_POST['first_name'] ?? '');
        $last_name = sanitize_text_field($_POST['last_name'] ?? '');
        $phone = sanitize_text_field($_POST['phone'] ?? '');
        $date_of_birth = sanitize_text_field($_POST['date_of_birth'] ?? '');

        // Address fields
        $address_1 = sanitize_text_field($_POST['address_1'] ?? '');
        $address_2 = sanitize_text_field($_POST['address_2'] ?? '');
        $city = sanitize_text_field($_POST['city'] ?? '');
        $postcode = sanitize_text_field($_POST['postcode'] ?? '');
        $country = sanitize_text_field($_POST['country'] ?? '');
        $state = sanitize_text_field($_POST['state'] ?? '');

        // Driver's license
        $license_number = sanitize_text_field($_POST['license_number'] ?? '');
        $license_expiry = sanitize_text_field($_POST['license_expiry'] ?? '');

        // Emergency contact
        $emergency_name = sanitize_text_field($_POST['emergency_name'] ?? '');
        $emergency_relationship = sanitize_text_field($_POST['emergency_relationship'] ?? '');
        $emergency_phone = sanitize_text_field($_POST['emergency_phone'] ?? '');

        // Validation
        if (empty($first_name) || empty($last_name)) {
            wp_send_json_error(array('message' => __('First name and last name are required.', 'ckl-car-rental')));
        }

        if (empty($phone)) {
            wp_send_json_error(array('message' => __('Phone number is required.', 'ckl-car-rental')));
        }

        // Update user data
        wp_update_user(array(
            'ID' => $user_id,
            'first_name' => $first_name,
            'last_name' => $last_name,
        ));

        // Update user meta
        update_user_meta($user_id, 'billing_phone', $phone);
        update_user_meta($user_id, 'date_of_birth', $date_of_birth);

        // Update address
        update_user_meta($user_id, 'billing_address_1', $address_1);
        update_user_meta($user_id, 'billing_address_2', $address_2);
        update_user_meta($user_id, 'billing_city', $city);
        update_user_meta($user_id, 'billing_postcode', $postcode);
        update_user_meta($user_id, 'billing_country', $country);
        update_user_meta($user_id, 'billing_state', $state);

        // Update driver's license
        update_user_meta($user_id, 'driving_license', $license_number);
        update_user_meta($user_id, 'license_expiry', $license_expiry);

        // Update emergency contact
        update_user_meta($user_id, 'emergency_contact_name', $emergency_name);
        update_user_meta($user_id, 'emergency_contact_relationship', $emergency_relationship);
        update_user_meta($user_id, 'emergency_contact_phone', $emergency_phone);

        wp_send_json_success(array(
            'message' => __('Profile updated successfully.', 'ckl-car-rental'),
        ));
    }

    /**
     * Upload document
     */
    public static function upload_document() {
        // Verify nonce
        if (!isset($_POST['ckl_document_nonce']) || !wp_verify_nonce($_POST['ckl_document_nonce'], 'ckl_upload_document')) {
            wp_send_json_error(array('message' => __('Invalid security token.', 'ckl-car-rental')));
        }

        $user_id = get_current_user_id();

        if (!$user_id) {
            wp_send_json_error(array('message' => __('You must be logged in.', 'ckl-car-rental')));
        }

        // Check if file was uploaded
        if (!isset($_FILES['license_upload']) && !isset($_FILES['id_upload'])) {
            wp_send_json_error(array('message' => __('No file uploaded.', 'ckl-car-rental')));
        }

        $uploaded_files = array();

        // Process license upload
        if (isset($_FILES['license_upload']) && $_FILES['license_upload']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['license_upload'];

            // Validate file type
            $allowed_types = array('application/pdf', 'image/jpeg', 'image/png', 'image/jpg');
            $file_type = wp_check_filetype_and_ext($file['tmp_name'], $file['name']);

            if (!in_array($file_type['type'], $allowed_types)) {
                wp_send_json_error(array('message' => __('Invalid file type. Please upload JPG, PNG, or PDF.', 'ckl-car-rental')));
            }

            // Validate file size (5MB max)
            if ($file['size'] > 5 * 1024 * 1024) {
                wp_send_json_error(array('message' => __('File size too large. Maximum size is 5MB.', 'ckl-car-rental')));
            }

            // Upload file
            $upload = wp_handle_upload($file, array('test_form' => false));

            if (isset($upload['error'])) {
                wp_send_json_error(array('message' => $upload['error']));
            }

            // Create attachment
            $attachment = array(
                'post_mime_type' => $upload['type'],
                'post_title' => sanitize_file_name($file['name']),
                'post_content' => '',
                'post_status' => 'inherit',
            );

            $attach_id = wp_insert_attachment($attachment, $upload['file']);
            if (!is_wp_error($attach_id)) {
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                $attach_data = wp_generate_attachment_metadata($attach_id, $upload['file']);
                wp_update_attachment_metadata($attach_id, $attach_data);

                // Save to user meta
                update_user_meta($user_id, '_document_license', $attach_id);
                $uploaded_files['license'] = $upload['url'];
            }
        }

        // Process ID upload
        if (isset($_FILES['id_upload']) && $_FILES['id_upload']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['id_upload'];

            // Validate file type
            $allowed_types = array('application/pdf', 'image/jpeg', 'image/png', 'image/jpg');
            $file_type = wp_check_filetype_and_ext($file['tmp_name'], $file['name']);

            if (!in_array($file_type['type'], $allowed_types)) {
                wp_send_json_error(array('message' => __('Invalid file type. Please upload JPG, PNG, or PDF.', 'ckl-car-rental')));
            }

            // Validate file size (5MB max)
            if ($file['size'] > 5 * 1024 * 1024) {
                wp_send_json_error(array('message' => __('File size too large. Maximum size is 5MB.', 'ckl-car-rental')));
            }

            // Upload file
            $upload = wp_handle_upload($file, array('test_form' => false));

            if (isset($upload['error'])) {
                wp_send_json_error(array('message' => $upload['error']));
            }

            // Create attachment
            $attachment = array(
                'post_mime_type' => $upload['type'],
                'post_title' => sanitize_file_name($file['name']),
                'post_content' => '',
                'post_status' => 'inherit',
            );

            $attach_id = wp_insert_attachment($attachment, $upload['file']);
            if (!is_wp_error($attach_id)) {
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                $attach_data = wp_generate_attachment_metadata($attach_id, $upload['file']);
                wp_update_attachment_metadata($attach_id, $attach_data);

                // Save to user meta
                update_user_meta($user_id, '_document_id', $attach_id);
                $uploaded_files['id'] = $upload['url'];
            }
        }

        if (empty($uploaded_files)) {
            wp_send_json_error(array('message' => __('No files were uploaded successfully.', 'ckl-car-rental')));
        }

        wp_send_json_success(array(
            'message' => __('Documents uploaded successfully.', 'ckl-car-rental'),
            'files' => $uploaded_files,
        ));
    }

    /**
     * Send support message
     */
    public static function send_support() {
        // Verify nonce
        if (!isset($_POST['ckl_support_nonce']) || !wp_verify_nonce($_POST['ckl_support_nonce'], 'ckl_support_form')) {
            wp_send_json_error(array('message' => __('Invalid security token.', 'ckl-car-rental')));
        }

        $user_id = get_current_user_id();

        if (!$user_id) {
            wp_send_json_error(array('message' => __('You must be logged in.', 'ckl-car-rental')));
        }

        // Sanitize input
        $name = sanitize_text_field($_POST['support_name'] ?? '');
        $email = sanitize_email($_POST['support_email'] ?? '');
        $subject = sanitize_text_field($_POST['support_subject'] ?? '');
        $message = sanitize_textarea_field($_POST['support_message'] ?? '');

        // Validation
        if (empty($name) || empty($email) || empty($subject) || empty($message)) {
            wp_send_json_error(array('message' => __('All fields are required.', 'ckl-car-rental')));
        }

        if (!is_email($email)) {
            wp_send_json_error(array('message' => __('Invalid email address.', 'ckl-car-rental')));
        }

        // Get support email
        $support_email = get_option('ckl_support_email', get_option('admin_email'));

        // Format subject
        $subject_map = array(
            'booking' => __('Booking Inquiry', 'ckl-car-rental'),
            'cancellation' => __('Cancellation Request', 'ckl-car-rental'),
            'payment' => __('Payment Issue', 'ckl-car-rental'),
            'vehicle' => __('Vehicle Question', 'ckl-car-rental'),
            'technical' => __('Technical Issue', 'ckl-car-rental'),
            'other' => __('Other', 'ckl-car-rental'),
        );

        $subject_prefix = isset($subject_map[$subject]) ? $subject_map[$subject] : __('Support Request', 'ckl-car-rental');
        $email_subject = sprintf('[%s] %s - %s', $subject_prefix, $name, $subject);

        // Build email body
        $email_body = sprintf(
            __("Name: %s\nEmail: %s\nUser ID: %d\n\nSubject: %s\n\nMessage:\n%s", 'ckl-car-rental'),
            $name,
            $email,
            $user_id,
            $subject_prefix,
            $message
        );

        // Headers
        $headers = array(
            'Content-Type: text/plain; charset=UTF-8',
            'From: ' . $name . ' <' . $email . '>',
            'Reply-To: ' . $email,
        );

        // Send email
        $sent = wp_mail($support_email, $email_subject, $email_body, $headers);

        if ($sent) {
            // Send confirmation to user
            $confirmation_subject = __('We received your message - CKL Car Rental', 'ckl-car-rental');
            $confirmation_body = sprintf(
                __("Hi %s,\n\nThank you for contacting us. We have received your message and will get back to you within 24-48 hours.\n\nYour message:\n%s\n\nBest regards,\nCKL Car Rental Support Team", 'ckl-car-rental'),
                $name,
                $message
            );

            wp_mail($email, $confirmation_subject, $confirmation_body);

            wp_send_json_success(array(
                'message' => __('Message sent successfully. We\'ll get back to you soon!', 'ckl-car-rental'),
            ));
        } else {
            wp_send_json_error(array('message' => __('Failed to send message. Please try again.', 'ckl-car-rental')));
        }
    }
}

// Initialize AJAX handlers
CKL_Customer_Dashboard_AJAX::init();
