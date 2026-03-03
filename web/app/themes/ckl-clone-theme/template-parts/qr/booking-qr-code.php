<?php
/**
 * Booking QR Code Template
 *
 * Displays scannable QR code for booking/order.
 * Used in both admin order pages and customer booking pages.
 *
 * @package CKL_Car_Rental
 * @version 1.0.0
 *
 * @var string   $qr_url       QR code image URL
 * @var int      $booking_id   Booking ID
 * @var int      $order_id     Order ID
 * @var array    $qr_data      QR code data array
 * @var array    $booking_data Booking data (frontend only)
 * @var string   $context      Display context: 'admin' or 'booking'
 */

defined('ABSPATH') || exit;

if (empty($qr_url)) {
	return;
}

$context = isset($context) ? $context : 'booking';
$is_admin = 'admin' === $context;
?>

<!-- Booking QR Code -->
<div class="ckl-qr-code-container <?php echo $is_admin ? 'ckl-qr-admin' : 'ckl-qr-frontend'; ?>">
	<div class="ckl-qr-card">
		<div class="ckl-qr-header">
			<h3>
				<?php
				if ($is_admin) {
					esc_html_e('Booking QR Code', 'ckl-car-rental');
				} else {
					esc_html_e('Your Booking QR Code', 'ckl-car-rental');
				}
				?>
			</h3>
			<?php if (!$is_admin) : ?>
				<p class="ckl-qr-subtitle">
					<?php esc_html_e('Show this QR code at pickup', 'ckl-car-rental'); ?>
				</p>
			<?php endif; ?>
		</div>

		<div class="ckl-qr-code-wrapper">
			<img src="<?php echo esc_url($qr_url); ?>"
			     alt="<?php esc_attr_e('Booking QR Code', 'ckl-car-rental'); ?>"
			     width="200"
			     height="200"
			     class="ckl-qr-code-image" />
		</div>

		<div class="ckl-qr-footer">
			<p class="ckl-qr-label">
				<?php
				if ($is_admin) {
					esc_html_e('Scan to view booking details', 'ckl-car-rental');
				} else {
					esc_html_e('Scan for quick check-in', 'ckl-car-rental');
				}
				?>
			</p>

			<?php if ($booking_id || $order_id) : ?>
				<div class="ckl-qr-ids">
					<?php if ($order_id) : ?>
						<span class="ckl-qr-id">
							<strong><?php esc_html_e('Order:', 'ckl-car-rental'); ?></strong>
							#<?php echo esc_html($order_id); ?>
						</span>
					<?php endif; ?>
					<?php if ($booking_id) : ?>
						<span class="ckl-qr-id">
							<strong><?php esc_html_e('Booking:', 'ckl-car-rental'); ?></strong>
							#<?php echo esc_html($booking_id); ?>
						</span>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if (!$is_admin) : ?>
				<button type="button" class="ckl-qr-print-btn" onclick="window.print();">
					<svg class="ckl-qr-print-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<path d="M6 9V2h12v7"></path>
						<path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
						<path d="M6 14h12v8H6z"></path>
					</svg>
					<?php esc_html_e('Print Booking', 'ckl-car-rental'); ?>
				</button>
			<?php endif; ?>
		</div>
	</div>

	<?php
	/**
	 * Debug mode - show QR data in development
	 * Comment out this section in production
	 */
	if (defined('WP_DEBUG') && WP_DEBUG && current_user_can('manage_options')) :
	?>
	<details class="ckl-qr-debug">
		<summary><?php esc_html_e('QR Code Data (Debug)', 'ckl-car-rental'); ?></summary>
		<pre class="ckl-qr-debug-data"><?php echo esc_html(wp_json_encode($qr_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)); ?></pre>
	</details>
	<?php endif; ?>
</div>

<style>
/* QR Code Container Styles */
.ckl-qr-code-container {
	margin: 20px 0;
}

.ckl-qr-card {
	background: #ffffff;
	border: 1px solid #e5e7eb;
	border-radius: 12px;
	padding: 24px;
	box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.ckl-qr-header {
	text-align: center;
	margin-bottom: 20px;
}

.ckl-qr-header h3 {
	font-size: 18px;
	font-weight: 700;
	color: #111827;
	margin: 0 0 8px 0;
}

.ckl-qr-subtitle {
	font-size: 14px;
	color: #6b7280;
	margin: 0;
}

.ckl-qr-code-wrapper {
	display: flex;
	justify-content: center;
	align-items: center;
	padding: 20px;
	background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
	border-radius: 8px;
	margin-bottom: 20px;
}

.ckl-qr-code-image {
	display: block;
	max-width: 200px;
	height: auto;
}

.ckl-qr-footer {
	text-align: center;
}

.ckl-qr-label {
	font-size: 14px;
	color: #6b7280;
	margin-bottom: 12px;
}

.ckl-qr-ids {
	display: flex;
	justify-content: center;
	gap: 16px;
	margin-bottom: 16px;
	flex-wrap: wrap;
}

.ckl-qr-id {
	font-size: 13px;
	color: #374151;
	background: #f3f4f6;
	padding: 4px 12px;
	border-radius: 6px;
}

.ckl-qr-print-btn {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	gap: 8px;
	padding: 10px 20px;
	background: #cc2e28;
	color: #ffffff;
	border: none;
	border-radius: 8px;
	font-size: 14px;
	font-weight: 600;
	cursor: pointer;
	transition: background-color 0.2s ease;
}

.ckl-qr-print-btn:hover {
	background: #a8241f;
}

.ckl-qr-print-icon {
	width: 16px;
	height: 16px;
}

/* Debug Section */
.ckl-qr-debug {
	margin-top: 16px;
	border: 1px solid #fecaca;
	border-radius: 6px;
	background: #fef2f2;
}

.ckl-qr-debug summary {
	padding: 8px 12px;
	cursor: pointer;
	font-size: 12px;
	font-weight: 600;
	color: #991b1b;
}

.ckl-qr-debug-data {
	margin: 0;
	padding: 12px;
	font-size: 11px;
	color: #7f1d1d;
	overflow-x: auto;
	background: #ffffff;
	border-top: 1px solid #fecaca;
}

/* Admin-specific overrides */
.ckl-qr-admin .ckl-qr-card {
	background: #f9fafb;
	border-color: #d1d5db;
}

/* Responsive adjustments */
@media (max-width: 640px) {
	.ckl-qr-card {
		padding: 16px;
	}

	.ckl-qr-code-image {
		max-width: 160px;
	}

	.ckl-qr-ids {
		flex-direction: column;
		gap: 8px;
	}
}

/* Print styles */
@media print {
	.ckl-qr-card {
		page-break-inside: avoid;
		border: 1px solid #000000;
		box-shadow: none;
	}

	.ckl-qr-print-btn {
		display: none;
	}

	.ckl-qr-code-image {
		max-width: 250px !important;
	}
}
</style>
