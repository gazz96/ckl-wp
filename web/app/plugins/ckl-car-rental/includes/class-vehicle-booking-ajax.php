<?php
/**
 * CKL Vehicle Booking AJAX Handlers
 *
 * Handles AJAX requests for vehicle booking order creation
 * and Xendit invoice generation
 *
 * @package CKL_Car_Rental
 * @since 1.0.1
 */

defined('ABSPATH') || exit;

/**
 * Calculate rental price based on duration
 *
 * @param int $vehicle_id Vehicle post ID
 * @param int $pickup_timestamp Pickup timestamp
 * @param int $return_timestamp Return timestamp
 * @return array Price breakdown
 */
function ckl_calculate_rental_price_for_booking($vehicle_id, $pickup_timestamp, $return_timestamp) {
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
 * Calculate service costs for a booking
 *
 * @param int $vehicle_id Vehicle post ID
 * @param array $selected_services Selected services with quantities
 * @param int $pickup_timestamp Pickup timestamp
 * @param int $return_timestamp Return timestamp
 * @return array Service costs breakdown
 */
function ckl_calculate_service_costs($vehicle_id, $selected_services, $pickup_timestamp, $return_timestamp) {
    $service_costs = array('total' => 0, 'items' => array());

    if (empty($selected_services)) {
        return $service_costs;
    }

    // Get helper function if not already available
    if (!function_exists('ckl_get_vehicle_services')) {
        include_once(get_template_directory() . '/admin/additional-services.php');
    }

    $all_services = ckl_get_vehicle_services();
    if (empty($all_services)) {
        return $service_costs;
    }

    $vehicle_services = get_post_meta($vehicle_id, '_vehicle_services', true);
    if (!is_array($vehicle_services)) {
        $vehicle_services = array();
    }

    $duration_seconds = $return_timestamp - $pickup_timestamp;
    $duration_days = floor($duration_seconds / (24 * 60 * 60));
    $duration_hours = ceil(($duration_seconds % (24 * 60 * 60)) / (60 * 60));

    foreach ($selected_services as $service_id => $quantity) {
        if (empty($quantity) || $quantity === '0' || $quantity === 0) {
            continue;
        }

        // Find the service in all services
        $service = null;
        foreach ($all_services as $s) {
            if ($s['id'] == $service_id) {
                $service = $s;
                break;
            }
        }

        if (!$service) {
            continue;
        }

        // Check if service is enabled for this vehicle
        if (isset($vehicle_services[$service_id]['enabled']) && !$vehicle_services[$service_id]['enabled']) {
            continue;
        }

        // Get pricing (vehicle override or default)
        $pricing_type = $vehicle_services[$service_id]['pricing_type'] ?? $service['pricing_type'];
        $price_per_day = $vehicle_services[$service_id]['price_per_day'] ?? $service['price_per_day'];
        $price_per_hour = $vehicle_services[$service_id]['price_per_hour'] ?? $service['price_per_hour'];
        $price_one_time = $vehicle_services[$service_id]['price_one_time'] ?? $service['price_one_time'];

        $cost = 0;
        $quantity = intval($quantity);

        if ($pricing_type === 'daily' && $price_per_day) {
            $cost = $price_per_day * $duration_days * $quantity;
        } elseif ($pricing_type === 'hourly' && $price_per_hour) {
            $cost = $price_per_hour * $duration_hours * $quantity;
        } elseif ($pricing_type === 'one_time' && $price_one_time) {
            $cost = $price_one_time * $quantity;
        }

        if ($cost > 0) {
            $service_costs['items'][$service_id] = array(
                'service_id' => $service_id,
                'title' => $service['title'],
                'quantity' => $quantity,
                'pricing_type' => $pricing_type,
                'unit_price' => $pricing_type === 'daily' ? $price_per_day : ($pricing_type === 'hourly' ? $price_per_hour : $price_one_time),
                'duration_days' => $pricing_type === 'daily' ? $duration_days : 0,
                'duration_hours' => $pricing_type === 'hourly' ? $duration_hours : 0,
                'total' => $cost
            );
            $service_costs['total'] += $cost;
        }
    }

    return $service_costs;
}

class CKL_Vehicle_Booking_AJAX {

    /**
     * Initialize AJAX handlers
     */
    public static function init() {
        add_action('wp_ajax_ckl_create_booking_order', array(__CLASS__, 'create_booking_order'));
        add_action('wp_ajax_nopriv_ckl_create_booking_order', array(__CLASS__, 'create_booking_order'));
        add_action('wp_ajax_ckl_check_order_status', array(__CLASS__, 'check_order_status'));
    }

    /**
     * Create booking order and generate Xendit invoice
     */
    public static function create_booking_order() {
        // Verify nonce
        if (!isset($_POST['booking_nonce']) || !wp_verify_nonce($_POST['booking_nonce'], 'ckl_booking_form')) {
            wp_send_json_error(array('message' => __('Invalid security token.', 'ckl-car-rental')));
        }

        // Sanitize and validate input
        $vehicle_id = intval($_POST['vehicle_id'] ?? 0);
        $product_id = intval($_POST['product_id'] ?? 0);
        $pickup_date = sanitize_text_field($_POST['pickup_date'] ?? '');
        $pickup_time = sanitize_text_field($_POST['pickup_time'] ?? '10:00');
        $return_date = sanitize_text_field($_POST['return_date'] ?? '');
        $return_time = sanitize_text_field($_POST['return_time'] ?? '10:00');
        $pickup_location = sanitize_text_field($_POST['pickup_location'] ?? '');
        $return_location = sanitize_text_field($_POST['return_location'] ?? '');
        $hotel_name = sanitize_text_field($_POST['hotel_name'] ?? '');
        $return_hotel_name = sanitize_text_field($_POST['return_hotel_name'] ?? '');
        $promo_code = sanitize_text_field($_POST['promo_code'] ?? '');

        // Services parameter
        $selected_services = isset($_POST['services']) ? $_POST['services'] : array();

        // Guest user fields
        $guest_email = sanitize_email($_POST['guest_email'] ?? '');
        $guest_phone = sanitize_text_field($_POST['guest_phone'] ?? '');

        // Validate required fields
        if (!$vehicle_id || !$product_id) {
            wp_send_json_error(array('message' => __('Invalid vehicle or product.', 'ckl-car-rental')));
        }

        if (empty($pickup_date) || empty($return_date)) {
            wp_send_json_error(array('message' => __('Pickup and return dates are required.', 'ckl-car-rental')));
        }

        if (empty($pickup_location) || empty($return_location)) {
            wp_send_json_error(array('message' => __('Pickup and return locations are required.', 'ckl-car-rental')));
        }

        // Validate dates
        if (!strtotime($pickup_date) || !strtotime($return_date)) {
            wp_send_json_error(array('message' => __('Invalid date format.', 'ckl-car-rental')));
        }

        // Combine date and time
        $pickup_timestamp = strtotime($pickup_date . ' ' . $pickup_time);
        $return_timestamp = strtotime($return_date . ' ' . $return_time);

        if ($return_timestamp <= $pickup_timestamp) {
            wp_send_json_error(array('message' => __('Return date must be after pickup date.', 'ckl-car-rental')));
        }

        // Validate guest email and phone for non-logged users
        $user_id = get_current_user_id();
        if (!$user_id) {
            if (empty($guest_email) || empty($guest_phone)) {
                wp_send_json_error(array('message' => __('Email and phone are required.', 'ckl-car-rental')));
            }

            if (!is_email($guest_email)) {
                wp_send_json_error(array('message' => __('Invalid email address.', 'ckl-car-rental')));
            }
        }

        // Check if product exists
        $product = wc_get_product($product_id);
        if (!$product) {
            wp_send_json_error(array('message' => __('Product not found.', 'ckl-car-rental')));
        }

        // Check if vehicle exists
        $vehicle = get_post($vehicle_id);
        if (!$vehicle || $vehicle->post_type !== 'vehicle') {
            wp_send_json_error(array('message' => __('Vehicle not found.', 'ckl-car-rental')));
        }

        try {
            // Calculate base pricing
            $pricing = ckl_calculate_rental_price_for_booking($vehicle_id, $pickup_timestamp, $return_timestamp);

            // Apply peak pricing to base rental cost
            $base_price = $pricing['total_price'];
            $pickup_date = date('Y-m-d', $pickup_timestamp);
            $return_date_for_pricing = date('Y-m-d', $return_timestamp);

            // Apply peak pricing using the helper function
            if (function_exists('ckl_calculate_peak_pricing_surcharge')) {
                $peak_surcharge = ckl_calculate_peak_pricing_surcharge($vehicle_id, $base_price, $pickup_date, $return_date_for_pricing);
                $pricing['peak_surcharge'] = $peak_surcharge;
                $pricing['total_price'] = $base_price + $peak_surcharge;
                $pricing['formatted_total'] = 'RM ' . number_format($pricing['total_price'], 2);
            }

            // Calculate service costs
            $service_costs = ckl_calculate_service_costs($vehicle_id, $selected_services, $pickup_timestamp, $return_timestamp);

            // Create WooCommerce order
            $order = wc_create_order(array(
                'customer_id' => $user_id,
            ));

            if (is_wp_error($order)) {
                wp_send_json_error(array('message' => __('Failed to create order.', 'ckl-car-rental')));
            }

            // Set billing information
            if (!$user_id) {
                $order->set_billing_email($guest_email);
                $order->set_billing_phone($guest_phone);
                $order->set_billing_first_name('Guest');
                $order->set_billing_last_name('Customer');
            } else {
                $order->set_billing_first_name(get_user_meta($user_id, 'first_name', true) ?: '');
                $order->set_billing_last_name(get_user_meta($user_id, 'last_name', true) ?: '');
                $order->set_billing_email(get_user_meta($user_id, 'billing_email', true) ?: get_user_meta($user_id, 'email', true) ?: '');
                $order->set_billing_phone(get_user_meta($user_id, 'billing_phone', true) ?: get_user_meta($user_id, 'phone', true) ?: '');
            }

            // Set custom price on product for this order (calculated rental price + services)
            $total_booking_price = $pricing['total_price'] + $service_costs['total'];
            $product->set_price($total_booking_price);

            // Add product to order FIRST (before calculate_totals)
            $order->add_product($product, 1);

            // Add services as order line items
            if (!empty($service_costs['items'])) {
                foreach ($service_costs['items'] as $service_item) {
                    $service_line_item = new WC_Order_Item_Fee();
                    $item_name = $service_item['title'];
                    if ($service_item['quantity'] > 1) {
                        $item_name .= ' (×' . $service_item['quantity'] . ')';
                    }
                    if ($service_item['pricing_type'] === 'daily' && $service_item['duration_days'] > 0) {
                        $item_name .= ' - ' . $service_item['duration_days'] . ' day(s)';
                    } elseif ($service_item['pricing_type'] === 'hourly' && $service_item['duration_hours'] > 0) {
                        $item_name .= ' - ' . $service_item['duration_hours'] . ' hour(s)';
                    }
                    $service_line_item->set_name($item_name);
                    $service_line_item->set_amount($service_item['total']);
                    $service_line_item->set_tax_class('zero-rate');
                    $order->add_item($service_line_item);
                }
            }

            // Calculate totals and save to get the order ID
            $order->calculate_totals();
            $order->save();

            // Store booking metadata
            $order_id = $order->get_id();
            update_post_meta($order_id, '_booking_vehicle_id', $vehicle_id);
            update_post_meta($order_id, '_booking_pickup_date', $pickup_date);
            update_post_meta($order_id, '_booking_pickup_time', $pickup_time);
            update_post_meta($order_id, '_booking_pickup_timestamp', $pickup_timestamp);
            update_post_meta($order_id, '_booking_return_date', $return_date);
            update_post_meta($order_id, '_booking_return_time', $return_time);
            update_post_meta($order_id, '_booking_return_timestamp', $return_timestamp);
            update_post_meta($order_id, '_booking_pickup_location', $pickup_location);
            update_post_meta($order_id, '_booking_return_location', $return_location);

            if ($pickup_location === 'hotel' && !empty($hotel_name)) {
                update_post_meta($order_id, '_booking_pickup_hotel', $hotel_name);
            }

            if ($return_location === 'hotel' && !empty($return_hotel_name)) {
                update_post_meta($order_id, '_booking_return_hotel', $return_hotel_name);
            }

            if (!empty($promo_code)) {
                update_post_meta($order_id, '_booking_promo_code', $promo_code);
            }

            // Store services in order meta
            if (!empty($service_costs['items'])) {
                update_post_meta($order_id, '_booking_services', $service_costs['items']);
                update_post_meta($order_id, '_booking_services_total', $service_costs['total']);
            }

            // Store pricing breakdown
            update_post_meta($order_id, '_booking_duration_days', $pricing['duration_days']);
            update_post_meta($order_id, '_booking_duration_hours', $pricing['duration_hours']);
            update_post_meta($order_id, '_booking_daily_total', $pricing['daily_total']);
            update_post_meta($order_id, '_booking_hourly_total', $pricing['hourly_total']);

            // Create WC Booking post if WooCommerce Bookings is active
            $booking_id = null;
            if (class_exists('WC_Booking')) {
                // Determine if booking requires confirmation
                $requires_confirmation = $product && method_exists($product, 'get_requires_confirmation') ? $product->get_requires_confirmation() : false;
                $initial_status = $requires_confirmation ? 'pending-confirmation' : 'unpaid';

                // Create booking data
                $booking_data = array(
                    'product_id'     => $product_id,
                    'cost'           => $pricing['total_price'] + $service_costs['total'],
                    'start_date'     => $pickup_timestamp,
                    'end_date'       => $return_timestamp,
                    'all_day'        => false,
                    'local_timezone' => wp_timezone_string(),
                    'customer_id'    => $user_id,
                );

                // Create the booking
                try {
                    $new_booking = new WC_Booking($booking_data);
                    $new_booking->create($initial_status);
                    $booking_id = $new_booking->get_id();

                    // Store booking-specific metadata
                    update_post_meta($booking_id, '_booking_vehicle_id', $vehicle_id);
                    update_post_meta($booking_id, '_booking_pickup_date', $pickup_date);
                    update_post_meta($booking_id, '_booking_pickup_time', $pickup_time);
                    update_post_meta($booking_id, '_booking_return_date', $return_date);
                    update_post_meta($booking_id, '_booking_return_time', $return_time);
                    update_post_meta($booking_id, '_booking_pickup_location', $pickup_location);
                    update_post_meta($booking_id, '_booking_return_location', $return_location);

                    if ($pickup_location === 'hotel' && !empty($hotel_name)) {
                        update_post_meta($booking_id, '_booking_pickup_hotel', $hotel_name);
                    }

                    if ($return_location === 'hotel' && !empty($return_hotel_name)) {
                        update_post_meta($booking_id, '_booking_return_hotel', $return_hotel_name);
                    }

                    if (!empty($promo_code)) {
                        update_post_meta($booking_id, '_booking_promo_code', $promo_code);
                    }

                    // Store services in booking meta
                    if (!empty($service_costs['items'])) {
                        update_post_meta($booking_id, '_booking_services', $service_costs['items']);
                        update_post_meta($booking_id, '_booking_services_total', $service_costs['total']);
                    }

                    // Link booking to order (will be updated after order item is added)
                    update_post_meta($booking_id, '_booking_order_id', $order_id);

                    // Log booking creation
                    error_log("CKL: Created WC Booking #{$booking_id} for order #{$order_id}");
                } catch (Exception $e) {
                    error_log('CKL: Failed to create WC Booking: ' . $e->getMessage());
                }
            }

            // Link booking to order item (product was already added before calculate_totals)
            if ($booking_id && class_exists('WC_Booking')) {
                $order_items = $order->get_items();
                $order_item_id = null;

                foreach ($order_items as $item_id => $item) {
                    if ($item->get_product_id() == $product_id) {
                        $order_item_id = $item_id;
                        break;
                    }
                }

                if ($order_item_id) {
                    // Update the booking with order item information
                    $booking = get_wc_booking($booking_id);
                    if ($booking) {
                        $booking->set_order_id($order_id);
                        $booking->set_order_item_id($order_item_id);
                        $booking->save();

                        // Also update customer ID
                        update_post_meta($booking_id, '_booking_customer_id', $user_id);

                        error_log("CKL: Linked WC Booking #{$booking_id} to order item #{$order_item_id}");
                    }
                }
            }

            // Add order note
            $order->add_order_note(
                sprintf(
                    __('Booking created for vehicle #%d. Pickup: %s %s, Return: %s %s', 'ckl-car-rental'),
                    $vehicle_id,
                    $pickup_date,
                    $pickup_time,
                    $return_date,
                    $return_time
                )
            );

            // Add order note about booking creation
            if ($booking_id) {
                $order->add_order_note(
                    sprintf(
                        __('WC Booking #%d created.', 'ckl-car-rental'),
                        $booking_id
                    )
                );
            }

            // Set payment method to Xendit and prepare payment
            $available_gateways = WC()->payment_gateways->get_available_payment_gateways();

            if (isset($available_gateways['xendit_gateway'])) {
                $xendit_gateway = $available_gateways['xendit_gateway'];
                $order->set_payment_method($xendit_gateway);
                $order->set_payment_method_title($xendit_gateway->get_title());
                $order->save();

                // Redirect directly to WooCommerce checkout payment page
                // This uses WooCommerce's built-in method for existing order payment
                $order_pay_url = $order->get_checkout_payment_url();

                wp_send_json_success(array(
                    'message' => __('Order created. Redirecting to payment...', 'ckl-car-rental'),
                    'redirect_url' => $order_pay_url,
                    'order_id' => $order_id
                ));
            } else {
                // Xendit gateway not available
                $order->update_status('failed');
                $order->add_order_note(__('Xendit payment gateway not available.', 'ckl-car-rental'));
                wp_send_json_error(array('message' => __('Payment gateway not available. Please contact support.', 'ckl-car-rental')));
            }

        } catch (Exception $e) {
            // Cancel order on critical errors
            if (isset($order) && $order->get_status() === 'pending') {
                $order->update_status('failed');
                $order->add_order_note('Booking failed: ' . $e->getMessage());
            }

            wp_send_json_error(array('message' => $e->getMessage()));
        }
    }

    /**
     * Check order status for polling
     */
    public static function check_order_status() {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'ckl_check_order_status')) {
            wp_send_json_error(array('message' => __('Invalid security token.', 'ckl-car-rental')));
        }

        $order_id = intval($_POST['order_id'] ?? 0);
        $user_id = get_current_user_id();

        if (!$order_id || !$user_id) {
            wp_send_json_error(array('message' => __('Invalid request.', 'ckl-car-rental')));
        }

        $order = wc_get_order($order_id);

        if (!$order) {
            wp_send_json_error(array('message' => __('Order not found.', 'ckl-car-rental')));
        }

        // Verify order belongs to user
        if ($order->get_customer_id() !== $user_id) {
            wp_send_json_error(array('message' => __('Access denied.', 'ckl-car-rental')));
        }

        // Get stored status from transient (to detect changes)
        $last_status = get_transient('ckl_order_status_' . $order_id . '_' . $user_id);
        $current_status = $order->get_status();

        // Update transient
        set_transient('ckl_order_status_' . $order_id . '_' . $user_id, $current_status, 5 * MINUTE_IN_SECONDS);

        // Check if status changed
        $status_changed = ($last_status && $last_status !== $current_status);

        wp_send_json_success(array(
            'status' => $current_status,
            'status_changed' => $status_changed,
            'status_label' => wc_get_order_status_name($current_status)
        ));
    }
}

// Initialize AJAX handlers
CKL_Vehicle_Booking_AJAX::init();
