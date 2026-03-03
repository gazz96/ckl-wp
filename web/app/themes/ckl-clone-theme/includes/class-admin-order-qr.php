<?php
/**
 * Admin Order QR Code Display
 *
 * Displays QR codes on WooCommerce admin order pages.
 * Staff can scan to quickly view booking details.
 *
 * @package CKL_Car_Rental
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

/**
 * Admin Order QR Code Display Class
 */
class CKL_Admin_Order_QR {

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

		// Add QR code to admin order details page
		add_action('woocommerce_order_details_after_order_table', array($this, 'display_qr_on_order_page'), 10, 1);

		// Add QR code to admin order meta boxes
		add_action('add_meta_boxes', array($this, 'add_qr_metabox'));

		// Enqueue admin styles
		add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));
	}

	/**
	 * Display QR code on order details page
	 *
	 * @param WC_Order $order Order object.
	 * @return void
	 */
	public function display_qr_on_order_page($order) {
		if (!$order) {
			return;
		}

		$order_id = $order->get_id();
		$qr_url = $this->qr_generator->generate_order_qr($order_id, 200);

		if (empty($qr_url)) {
			return;
		}

		// Get booking data for display
		$booking_id = $this->get_booking_id_from_order($order_id);
		$qr_data = $this->qr_generator->get_order_qr_data($order_id);

		// Load template
		$template_args = array(
			'qr_url'     => $qr_url,
			'order_id'   => $order_id,
			'booking_id' => $booking_id,
			'qr_data'    => $qr_data,
			'context'    => 'admin',
		);

		ckl_get_template('booking-qr-code.php', $template_args);
	}

	/**
	 * Add QR code metabox to order edit screen
	 *
	 * @return void
	 */
	public function add_qr_metabox() {
		add_meta_box(
			'ckl_booking_qr',
			__('Booking QR Code', 'ckl-car-rental'),
			array($this, 'render_qr_metabox'),
			'shop_order',
			'side',
			'default'
		);
	}

	/**
	 * Render QR code metabox content
	 *
	 * @param WP_Post $post Post object.
	 * @return void
	 */
	public function render_qr_metabox($post) {
		$order_id = $post->ID;
		$qr_url = $this->qr_generator->generate_order_qr($order_id, 180);

		if (empty($qr_url)) {
			echo '<p>' . esc_html__('No QR code available for this order.', 'ckl-car-rental') . '</p>';
			return;
		}

		$booking_id = $this->get_booking_id_from_order($order_id);

		?>
		<div class="ckl-qr-metabox">
			<div class="ckl-qr-code-wrapper">
				<?php echo $this->qr_generator->display_qr_image($qr_url, '', 180); ?>
			</div>
			<p class="ckl-qr-label"><?php esc_html_e('Scan to view booking details', 'ckl-car-rental'); ?></p>
			<?php if ($booking_id) : ?>
				<p class="ckl-qr-ids">
					<strong><?php esc_html_e('Order:', 'ckl-car-rental'); ?></strong> #<?php echo esc_html($order_id); ?><br>
					<strong><?php esc_html_e('Booking:', 'ckl-car-rental'); ?></strong> #<?php echo esc_html($booking_id); ?>
				</p>
			<?php endif; ?>
		</div>
		<?php
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
	 * Enqueue admin styles for QR code display
	 *
	 * @param string $hook Current admin page hook.
	 * @return void
	 */
	public function enqueue_admin_styles($hook) {
		// Only load on order pages
		if (('post.php' === $hook || 'post-new.php' === $hook) && isset($_GET['post'])) {
			$post = get_post(intval($_GET['post']));
			if ($post && 'shop_order' === $post->post_type) {
				wp_enqueue_style(
					'ckl-qr-admin-styles',
					get_template_directory_uri() . '/includes/admin/qr-styles.css',
					array(),
					'1.0.0'
				);
			}
		}

		// Also load on order details pages (HPOS compatible)
		if (isset($_GET['page']) && 'wc-orders' === $_GET['page']) {
			wp_enqueue_style(
				'ckl-qr-admin-styles',
				get_template_directory_uri() . '/includes/admin/qr-styles.css',
				array(),
				'1.0.0'
			);
		}
	}
}

/**
 * Template loading helper function
 *
 * @param string $template_name Template name.
 * @param array  $args          Template arguments.
 * @return void
 */
function ckl_get_template($template_name, $args = array()) {
	extract($args); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

	$template_path = locate_template(array('template-parts/qr/' . $template_name));

	if (!$template_path) {
		return;
	}

	include $template_path;
}

// Initialize admin order QR display
function ckl_admin_order_qr() {
	return new CKL_Admin_Order_QR();
}
add_action('plugins_loaded', 'ckl_admin_order_qr');
