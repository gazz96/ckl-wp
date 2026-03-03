<?php
/**
 * Booking QR Code Display
 *
 * Displays QR codes on customer booking details pages.
 * Customers can show their QR code for quick scanning.
 *
 * @package CKL_Car_Rental
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

/**
 * Booking QR Code Display Class
 */
class CKL_Booking_QR {

	/**
	 * QR Code Generator instance
	 *
	 * @var CKL_QR_Code_Generator
	 */
	private $qr_generator;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->qr_generator = new CKL_QR_Code_Generator();

		// Add QR code to booking details page (after pricing)
		add_action('ckl_booking_details_after_pricing', array($this, 'display_qr_on_booking_page'), 10, 1);

		// Enqueue frontend styles
		add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_styles'));
	}

	/**
	 * Display QR code on booking details page
	 *
	 * @param array $booking_data Booking data array.
	 * @return void
	 */
	public function display_qr_on_booking_page($booking_data) {
		if (!isset($booking_data['booking_id'])) {
			return;
		}

		$booking_id = intval($booking_data['booking_id']);
		$order_id = isset($booking_data['order_id']) ? intval($booking_data['order_id']) : 0;

		$qr_url = $this->qr_generator->generate_booking_qr($booking_id, 200);

		if (empty($qr_url)) {
			return;
		}

		// Get QR data for display
		$qr_data = $this->qr_generator->get_qr_data($booking_id);

		// Load template
		$template_args = array(
			'qr_url'       => $qr_url,
			'booking_id'   => $booking_id,
			'order_id'     => $order_id,
			'qr_data'      => $qr_data,
			'booking_data' => $booking_data,
			'context'      => 'booking',
		);

		ckl_booking_get_template('booking-qr-code.php', $template_args);
	}

	/**
	 * Enqueue frontend styles for QR code display
	 *
	 * @return void
	 */
	public function enqueue_frontend_styles() {
		// Only load on my account pages
		if (!is_account_page()) {
			return;
		}

		wp_enqueue_style(
			'ckl-qr-frontend-styles',
			get_template_directory_uri() . '/includes/admin/qr-styles.css',
			array(),
			'1.0.0'
		);
	}
}

/**
 * Template loading helper function for booking pages
 *
 * @param string $template_name Template name.
 * @param array  $args          Template arguments.
 * @return void
 */
function ckl_booking_get_template($template_name, $args = array()) {
	extract($args); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

	$template_path = locate_template(array('template-parts/qr/' . $template_name));

	if (!$template_path) {
		return;
	}

	include $template_path;
}

// Initialize booking QR display
function ckl_booking_qr() {
	return new CKL_Booking_QR();
}
add_action('init', 'ckl_booking_qr');
