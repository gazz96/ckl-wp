<?php
/**
 * QR Code Generator
 *
 * Generates QR codes for bookings and orders using Google Charts API.
 * QR codes contain booking details for easy scanning by staff.
 *
 * @package CKL_Car_Rental
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

/**
 * QR Code Generator Class
 */
class CKL_QR_Code_Generator {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Initialize hooks if needed
	}

	/**
	 * Get QR data for a booking
	 *
	 * @param int $booking_id Booking ID.
	 * @return array|false QR data array or false on failure
	 */
	public function get_qr_data($booking_id) {
		$booking = get_post($booking_id);

		if (!$booking || 'wc_booking' !== $booking->post_type) {
			return false;
		}

		// Get order ID from booking
		$order_id = get_post_meta($booking_id, '_booking_order_id', true);
		if (!$order_id) {
			return false;
		}

		$order = wc_get_order($order_id);
		if (!$order) {
			return false;
		}

		// Get vehicle data
		$vehicle_id = get_post_meta($booking_id, '_booking_product_id', true);
		$vehicle_name = $vehicle_id ? get_the_title($vehicle_id) : '';
		$plate_number = get_post_meta($vehicle_id, '_plate_number', true);

		// Get booking dates
		$start_date = get_post_meta($booking_id, '_booking_start', true);
		$end_date = get_post_meta($booking_id, '_booking_end', true);

		// Get locations
		$pickup_location = get_post_meta($booking_id, '_pickup_location', true);
		$return_location = get_post_meta($booking_id, '_return_location', true);

		// Format dates
		$start_formatted = $start_date ? date_i18n('Y-m-d H:i', $start_date) : '';
		$end_formatted = $end_date ? date_i18n('Y-m-d H:i', $end_date) : '';

		// Get customer data from order
		$customer_name = $order->get_formatted_full_name();
		$customer_email = $order->get_billing_email();
		$customer_phone = $order->get_billing_phone();

		// Get status
		$booking_status = get_post_meta($booking_id, '_booking_status', true);

		// Build QR data payload (shortened keys for smaller QR)
		$qr_data = array(
			't'  => 'CKL',
			'oid' => (string) $order_id,
			'bid' => (string) $booking_id,
			'v'   => $vehicle_name,
			'p'   => $plate_number ? $plate_number : '',
			's'   => $start_formatted,
			'e'   => $end_formatted,
			'pu'  => $pickup_location ? $pickup_location : '',
			'r'   => $return_location ? $return_location : '',
			'c'   => $customer_name,
			'ph'  => $customer_phone ? $customer_phone : '',
			'em'  => $customer_email ? $customer_email : '',
			'st'  => $booking_status,
		);

		return apply_filters('ckl_qr_data', $qr_data, $booking_id, $order_id);
	}

	/**
	 * Get QR data for an order
	 *
	 * @param int $order_id Order ID.
	 * @return array|false QR data array or false on failure
	 */
	public function get_order_qr_data($order_id) {
		$order = wc_get_order($order_id);
		if (!$order) {
			return false;
		}

		// Find booking associated with this order
		$booking_id = $this->get_booking_id_from_order($order_id);

		if ($booking_id) {
			return $this->get_qr_data($booking_id);
		}

		// Fallback to order-only data if no booking
		$qr_data = array(
			't'  => 'CKL',
			'oid' => (string) $order_id,
			'bid' => '',
			'c'   => $order->get_formatted_full_name(),
			'em'  => $order->get_billing_email(),
			'st'  => $order->get_status(),
		);

		return $qr_data;
	}

	/**
	 * Get booking ID from order
	 *
	 * @param int $order_id Order ID.
	 * @return int|false Booking ID or false
	 */
	private function get_booking_id_from_order($order_id) {
		$bookings = wc_get_bookings(array(
			'order_id' => $order_id,
			'limit'    => 1,
		));

		if (!empty($bookings)) {
			return $bookings[0]->get_id();
		}

		return false;
	}

	/**
	 * Generate QR code URL using Google Charts API
	 *
	 * @param array $data QR data array.
	 * @param int   $size Size in pixels (default: 200).
	 * @return string QR code image URL
	 */
	public function generate_qr_url($data, $size = 200) {
		$json_data = wp_json_encode($data);

		if (false === $json_data) {
			return '';
		}

		// URL encode the JSON data
		$encoded_data = rawurlencode($json_data);

		// Build Google Charts API URL
		$qr_url = add_query_arg(array(
			'cht' => 'qr',  // QR code
			'chs' => $size . 'x' . $size,
			'chl' => $encoded_data,
			'choe' => 'UTF-8',
		), 'https://chart.googleapis.com/chart');

		return apply_filters('ckl_qr_code_url', $qr_url, $data, $size);
	}

	/**
	 * Generate QR code for a booking
	 *
	 * @param int $booking_id Booking ID.
	 * @param int $size       QR code size in pixels.
	 * @return string QR code image URL
	 */
	public function generate_booking_qr($booking_id, $size = 200) {
		$data = $this->get_qr_data($booking_id);

		if (!$data) {
			return '';
		}

		return $this->generate_qr_url($data, $size);
	}

	/**
	 * Generate QR code for an order
	 *
	 * @param int $order_id Order ID.
	 * @param int $size     QR code size in pixels.
	 * @return string QR code image URL
	 */
	public function generate_order_qr($order_id, $size = 200) {
		$data = $this->get_order_qr_data($order_id);

		if (!$data) {
			return '';
		}

		return $this->generate_qr_url($data, $size);
	}

	/**
	 * Display QR code image HTML
	 *
	 * @param string $qr_url    QR code image URL.
	 * @param string $alt_text  Alt text for the image.
	 * @param int    $size      Image size attribute.
	 * @return string Image HTML
	 */
	public function display_qr_image($qr_url, $alt_text = '', $size = 200) {
		if (empty($qr_url)) {
			return '';
		}

		$alt = $alt_text ? esc_attr($alt_text) : esc_html__('Booking QR Code', 'ckl-car-rental');

		return sprintf(
			'<img src="%s" alt="%s" width="%d" height="%d" class="ckl-qr-code-image" style="width:%dpx;height:%dpx;" />',
			esc_url($qr_url),
			$alt,
			intval($size),
			intval($size),
			intval($size),
			intval($size)
		);
	}

	/**
	 * Get QR data as formatted text for display/debugging
	 *
	 * @param array $data QR data array.
	 * @return string Formatted JSON string
	 */
	public function format_qr_data_for_display($data) {
		return wp_json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
	}
}

// Initialize the QR code generator
function ckl_qr_code_generator() {
	return new CKL_QR_Code_Generator();
}
