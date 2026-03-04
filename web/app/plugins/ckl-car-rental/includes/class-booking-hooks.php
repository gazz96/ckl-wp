<?php
/**
 * CKL Booking Hooks
 *
 * Hooks for managing WC Booking status updates based on order status
 *
 * @package CKL_Car_Rental
 * @since 1.0.2
 */

defined('ABSPATH') || exit;

/**
 * CKL_Booking_Hooks Class
 */
class CKL_Booking_Hooks {

    /**
     * Initialize hooks
     */
    public static function init() {
        // Update booking status when order status changes
        add_action('woocommerce_order_status_processing', array(__CLASS__, 'update_booking_status_on_payment'));
        add_action('woocommerce_order_status_completed', array(__CLASS__, 'update_booking_status_on_payment'));
        add_action('woocommerce_order_status_cancelled', array(__CLASS__, 'cancel_bookings_on_order_cancelled'));
        add_action('woocommerce_order_status_refunded', array(__CLASS__, 'cancel_bookings_on_order_cancelled'));
    }

    /**
     * Update booking status when order payment is complete
     *
     * @param int $order_id The order ID
     */
    public static function update_booking_status_on_payment($order_id) {
        if (!class_exists('WC_Booking_Data_Store')) {
            return;
        }

        $order = wc_get_order($order_id);
        if (!$order) {
            return;
        }

        // Get bookings linked to this order
        $booking_ids = WC_Booking_Data_Store::get_booking_ids_from_order_id($order_id);

        if (empty($booking_ids)) {
            return;
        }

        foreach ($booking_ids as $booking_id) {
            $booking = get_wc_booking($booking_id);
            if (!$booking) {
                continue;
            }

            // Get product to check if confirmation is required
            $product_id = $booking->get_product_id();
            $requires_confirmation = $product_id && function_exists('wc_booking_requires_confirmation') && wc_booking_requires_confirmation($product_id);

            // Update status based on order status
            if ($order->has_status('processing')) {
                // If booking requires confirmation, move to confirmed
                if ($booking->has_status('pending-confirmation')) {
                    $booking->update_status('confirmed');
                } elseif ($booking->has_status('unpaid')) {
                    $booking->update_status('paid');
                }
            } elseif ($order->has_status('completed')) {
                // Mark complete bookings
                if (!$booking->has_status(array('complete', 'cancelled'))) {
                    $booking->update_status('complete');
                }
            }

            // Add order note
            $order->add_order_note(
                sprintf(
                    __('Booking #%d status updated to %s', 'ckl-car-rental'),
                    $booking_id,
                    $booking->get_status()
                )
            );
        }
    }

    /**
     * Cancel bookings when order is cancelled or refunded
     *
     * @param int $order_id The order ID
     */
    public static function cancel_bookings_on_order_cancelled($order_id) {
        if (!class_exists('WC_Booking_Data_Store')) {
            return;
        }

        $order = wc_get_order($order_id);
        if (!$order) {
            return;
        }

        // Get bookings linked to this order
        $booking_ids = WC_Booking_Data_Store::get_booking_ids_from_order_id($order_id);

        if (empty($booking_ids)) {
            return;
        }

        foreach ($booking_ids as $booking_id) {
            $booking = get_wc_booking($booking_id);
            if (!$booking) {
                continue;
            }

            // Cancel the booking if not already cancelled
            if (!$booking->has_status('cancelled')) {
                $booking->update_status('cancelled');

                // Add order note
                $order->add_order_note(
                    sprintf(
                        __('Booking #%d cancelled due to order %s', 'ckl-car-rental'),
                        $booking_id,
                        $order->get_status()
                    )
                );
            }
        }
    }
}

// Initialize hooks
CKL_Booking_Hooks::init();
