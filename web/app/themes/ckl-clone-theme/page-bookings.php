<?php
/**
 * Template Name: My Bookings
 *
 * My Bookings page template for CK Langkawi Car Rental
 * Displays user's booking history
 * Also handles guest payment access
 */

// Check if this is a guest payment request
$show_payment_order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
$show_payment = isset($_GET['show_payment']) ? $_GET['show_payment'] === '1' : false;
$is_guest_payment = $show_payment && $show_payment_order_id && !is_user_logged_in();

// For guest payment, allow access without login
// For regular bookings page, require login
if (!$is_guest_payment && !is_user_logged_in()) {
    wp_redirect(wp_login_url(get_permalink()));
    exit;
}

get_header();

$user_id = get_current_user_id();

// Get filter from URL
$status_filter = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : '';

// Fetch user's bookings (WooCommerce orders)
$booking_args = array(
    'customer_id' => $user_id,
    'limit' => 20,
    'orderby' => 'date',
    'order' => 'DESC',
);

// Apply status filter if set
if ($status_filter && $status_filter !== 'all') {
    $booking_args['status'] = array($status_filter);
}

$bookings = wc_get_orders($booking_args);
?>

<!-- Hero Section -->
<section class="hero-section relative bg-gradient-to-r from-blue-600 to-blue-800 text-white py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-4xl font-bold mb-4">
                <?php echo $is_guest_payment ? __('Complete Payment', 'ckl-car-rental') : __('My Bookings', 'ckl-car-rental'); ?>
            </h1>
            <p class="text-lg">
                <?php echo $is_guest_payment ? __('Please complete your payment to confirm your booking', 'ckl-car-rental') : __('View and manage your rental bookings', 'ckl-car-rental'); ?>
            </p>
        </div>
    </div>
</section>

<!-- Bookings Content -->
<section class="bookings-content py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-7xl mx-auto">

            <?php if (is_user_logged_in()) : ?>
                <!-- Filters - Only for logged in users -->
                <div class="mb-8 flex flex-wrap items-center justify-between gap-4 bg-white rounded-lg shadow p-4">
                    <div class="flex flex-wrap gap-2">
                        <a href="<?php echo remove_query_arg('status'); ?>"
                           class="px-4 py-2 rounded-lg font-medium <?php echo !$status_filter ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'; ?>">
                            <?php _e('All', 'ckl-car-rental'); ?>
                            <?php if (!$status_filter) : ?>
                                (<?php echo count(wc_get_orders(array('customer_id' => $user_id, 'limit' => -1))); ?>)
                            <?php endif; ?>
                        </a>
                        <a href="<?php echo add_query_arg('status', 'wc-completed'); ?>"
                           class="px-4 py-2 rounded-lg font-medium <?php echo $status_filter === 'wc-completed' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'; ?>">
                            <?php _e('Completed', 'ckl-car-rental'); ?>
                        </a>
                        <a href="<?php echo add_query_arg('status', 'wc-processing'); ?>"
                           class="px-4 py-2 rounded-lg font-medium <?php echo $status_filter === 'wc-processing' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'; ?>">
                            <?php _e('Confirmed', 'ckl-car-rental'); ?>
                        </a>
                        <a href="<?php echo add_query_arg('status', 'wc-pending'); ?>"
                           class="px-4 py-2 rounded-lg font-medium <?php echo $status_filter === 'wc-pending' ? 'bg-yellow-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'; ?>">
                            <?php _e('Pending', 'ckl-car-rental'); ?>
                        </a>
                        <a href="<?php echo add_query_arg('status', 'wc-cancelled'); ?>"
                           class="px-4 py-2 rounded-lg font-medium <?php echo $status_filter === 'wc-cancelled' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'; ?>">
                            <?php _e('Cancelled', 'ckl-car-rental'); ?>
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <?php
            // Check if we need to show payment iframe for a specific order
            // Note: These variables are already defined at the top of the file for guest access

            if ($show_payment && $show_payment_order_id) :
                // Verify this order exists
                $order = wc_get_order($show_payment_order_id);

                // For logged in users, verify order belongs to them
                // For guest users, verify order has customer_id = 0
                if ($order) :
                    $order_customer_id = $order->get_customer_id();
                    $can_view = false;

                    if (is_user_logged_in()) {
                        // Logged in user: check if order belongs to them
                        $can_view = ($order_customer_id === $user_id);
                    } else {
                        // Guest user: check if this is a guest order
                        $can_view = ($order_customer_id === 0);
                    }

                    if ($can_view && $order->has_status('pending')) :
                        // Get Xendit invoice URL - trigger generation if not exists
                        $xendit_invoice_url = $order->get_meta('Xendit_invoice_url');

                        // If no invoice URL exists, try to generate it
                        if (!$xendit_invoice_url) {
                            $available_gateways = WC()->payment_gateways->get_available_payment_gateways();
                            if (isset($available_gateways['xendit_gateway'])) {
                                $xendit_gateway = $available_gateways['xendit_gateway'];
                                $order->set_payment_method($xendit_gateway);
                                $order->save();

                                // Try to process payment to generate invoice
                                $result = $xendit_gateway->process_payment($show_payment_order_id);

                                // Get the invoice URL again
                                $xendit_invoice_url = $order->get_meta('Xendit_invoice_url');
                            }
                        }

                        if ($xendit_invoice_url) :
                            // Show payment iframe
                            get_template_part('template-parts/myaccount/payment-iframe', null, array(
                                'order_id' => $show_payment_order_id,
                                'xendit_url' => $xendit_invoice_url
                            ));

                            // Show order details below iframe
                            echo '<div class="mt-8">';
                        endif;
                    endif;
                endif;
            endif;
            ?>

            <?php if (!is_user_logged_in() && !$show_payment) : ?>
                <!-- Guest without valid payment parameters -->
                <div class="text-center py-16 bg-white rounded-lg shadow">
                    <div class="text-6xl mb-6">🔒</div>
                    <h2 class="text-2xl font-bold mb-4 text-gray-700">
                        <?php _e('Access Required', 'ckl-car-rental'); ?>
                    </h2>
                    <p class="text-gray-600 mb-8 max-w-md mx-auto">
                        <?php _e('Please log in to view your bookings or complete your payment.', 'ckl-car-rental'); ?>
                    </p>
                    <a href="<?php echo wp_login_url(get_permalink()); ?>"
                       class="inline-block bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-primary/90 transition">
                        <?php _e('Log In', 'ckl-car-rental'); ?>
                    </a>
                </div>
            <?php elseif (is_user_logged_in()) : ?>
                <?php if (empty($bookings)) : ?>
                <!-- Empty State -->
                <div class="text-center py-16 bg-white rounded-lg shadow">
                    <div class="text-6xl mb-6">📅</div>
                    <h2 class="text-2xl font-bold mb-4 text-gray-700">
                        <?php _e('No Bookings Found', 'ckl-car-rental'); ?>
                    </h2>
                    <p class="text-gray-600 mb-8 max-w-md mx-auto">
                        <?php
                        if ($status_filter) {
                            _e('You don\'t have any bookings with this status.', 'ckl-car-rental');
                        } else {
                            _e('You haven\'t made any bookings yet. Start exploring our vehicles!', 'ckl-car-rental');
                        }
                        ?>
                    </p>
                    <a href="<?php echo get_post_type_archive_link('vehicle'); ?>"
                       class="inline-block bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-primary/90 transition">
                        <?php _e('Browse Vehicles', 'ckl-car-rental'); ?>
                    </a>
                </div>
            <?php else : ?>
                <!-- Bookings List -->
                <div class="space-y-4">
                    <?php foreach ($bookings as $booking) :
                        $booking_id = $booking->get_id();
                        $booking_status = $booking->get_status();
                        $booking_date = $booking->get_date_created()->date_i18n(get_option('date_format'));
                        $total = $booking->get_total();

                        // Get items from booking
                        $items = $booking->get_items();

                        // Get first item (vehicle)
                        $first_item = reset($items);
                        $vehicle_id = $first_item->get_product_id();
                        $vehicle = get_post($vehicle_id);

                        // Get booking meta data
                        $pickup_date = $booking->get_meta('pickup_date');
                        $return_date = $booking->get_meta('return_date');
                        $pickup_location = $booking->get_meta('pickup_location');
                        $return_location = $booking->get_meta('return_location');

                        // Status badge color
                        $status_colors = array(
                            'completed' => 'bg-green-100 text-green-800',
                            'processing' => 'bg-accent/10 text-primary',
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'cancelled' => 'bg-red-100 text-red-800',
                            'refunded' => 'bg-gray-100 text-gray-800',
                        );
                        $status_color = isset($status_colors[$booking_status]) ? $status_colors[$booking_status] : 'bg-gray-100 text-gray-800';

                        // Status label
                        $status_labels = array(
                            'completed' => __('Completed', 'ckl-car-rental'),
                            'processing' => __('Confirmed', 'ckl-car-rental'),
                            'pending' => __('Pending Payment', 'ckl-car-rental'),
                            'cancelled' => __('Cancelled', 'ckl-car-rental'),
                            'refunded' => __('Refunded', 'ckl-car-rental'),
                        );
                        $status_label = isset($status_labels[$booking_status]) ? $status_labels[$booking_status] : ucfirst($booking_status);
                    ?>
                        <div class="bg-white rounded-lg shadow hover:shadow-md transition overflow-hidden">
                            <div class="p-6">
                                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                                    <!-- Vehicle Info -->
                                    <div class="flex items-start gap-4">
                                        <?php if (has_post_thumbnail($vehicle_id)) : ?>
                                            <div class="flex-shrink-0">
                                                <a href="<?php echo get_permalink($vehicle_id); ?>">
                                                    <?php echo get_the_post_thumbnail($vehicle_id, 'thumbnail', array('class' => 'w-20 h-20 object-cover rounded')); ?>
                                                </a>
                                            </div>
                                        <?php endif; ?>

                                        <div>
                                            <div class="flex items-center gap-2 mb-1">
                                                <h3 class="font-bold text-lg">
                                                    <?php echo esc_html($vehicle->post_title); ?>
                                                </h3>
                                                <span class="px-3 py-1 rounded-full text-xs font-semibold <?php echo esc_attr($status_color); ?>">
                                                    <?php echo esc_html($status_label); ?>
                                                </span>
                                            </div>

                                            <?php if ($pickup_date || $return_date) : ?>
                                                <div class="text-sm text-gray-600 mb-2">
                                                    <?php if ($pickup_date) : ?>
                                                        <span class="mr-3">
                                                            <strong><?php _e('Pickup:', 'ckl-car-rental'); ?></strong>
                                                            <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($pickup_date))); ?>
                                                        </span>
                                                    <?php endif; ?>
                                                    <?php if ($return_date) : ?>
                                                        <span>
                                                            <strong><?php _e('Return:', 'ckl-car-rental'); ?></strong>
                                                            <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($return_date))); ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>

                                            <div class="text-sm text-gray-500">
                                                <?php _e('Booking #', 'ckl-car-rental'); ?><?php echo esc_html($booking_id); ?> •
                                                <?php echo esc_html($booking_date); ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Price and Actions -->
                                    <div class="flex lg:flex-col items-center lg:items-end justify-between lg:justify-center gap-4">
                                        <div class="text-right">
                                            <div class="text-2xl font-bold text-primary">
                                                RM <?php echo number_format($total, 2); ?>
                                            </div>
                                        </div>

                                        <div class="flex gap-2">
                                            <a href="<?php echo $booking->get_view_order_url(); ?>"
                                               class="px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition text-sm font-medium">
                                                <?php _e('View Details', 'ckl-car-rental'); ?>
                                            </a>
                                            <?php if ($booking->has_status('pending')) :
                                                // Get Xendit invoice URL
                                                $xendit_invoice_url = $booking->get_meta('Xendit_invoice_url');

                                                if ($xendit_invoice_url) :
                                                    $bookings_page = get_permalink(get_page_by_path('bookings'));
                                                    $payment_url = add_query_arg(array(
                                                        'order_id' => $booking_id,
                                                        'show_payment' => '1'
                                                    ), $bookings_page);
                                            ?>
                                                <a href="<?php echo esc_url($payment_url); ?>"
                                                   class="px-4 py-2 bg-primary text-white rounded hover:bg-primary/90 transition text-sm font-medium">
                                                    <?php _e('Pay Now', 'ckl-car-rental'); ?>
                                                </a>
                                            <?php
                                                endif;
                                            endif;
                                            ?>
                                            <?php if ($booking->has_status(array('pending', 'processing'))) : ?>
                                                <a href="<?php echo $booking->get_cancel_order_url(); ?>"
                                                   class="px-4 py-2 bg-red-100 text-red-700 rounded hover:bg-red-200 transition text-sm font-medium">
                                                    <?php _e('Cancel', 'ckl-car-rental'); ?>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <?php if ($pickup_location || $return_location) : ?>
                                    <div class="mt-4 pt-4 border-t grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                        <?php if ($pickup_location) : ?>
                                            <div>
                                                <span class="font-semibold text-gray-700"><?php _e('Pickup Location:', 'ckl-car-rental'); ?></span>
                                                <span class="text-gray-600"><?php echo esc_html($pickup_location); ?></span>
                                            </div>
                                        <?php endif; ?>
                                        <?php if ($return_location) : ?>
                                            <div>
                                                <span class="font-semibold text-gray-700"><?php _e('Return Location:', 'ckl-car-rental'); ?></span>
                                                <span class="text-gray-600"><?php echo esc_html($return_location); ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($bookings->max_num_pages > 1) : ?>
                    <div class="mt-8">
                        <?php
                        echo paginate_links(array(
                            'total' => $bookings->max_num_pages,
                            'current' => max(1, get_query_var('paged')),
                            'prev_text' => __('&laquo; Previous', 'ckl-car-rental'),
                            'next_text' => __('Next &raquo;', 'ckl-car-rental'),
                        ));
                        ?>
                    </div>
                <?php endif; ?>

                <?php
                // Close the div we opened for payment iframe section
                if ($show_payment && $show_payment_order_id) :
                    $order = wc_get_order($show_payment_order_id);
                    if ($order) :
                        $order_customer_id = $order->get_customer_id();
                        $can_view = false;

                        if (is_user_logged_in()) {
                            $can_view = ($order_customer_id === $user_id);
                        } else {
                            $can_view = ($order_customer_id === 0);
                        }

                        if ($can_view) :
                            $xendit_invoice_url = $order->get_meta('Xendit_invoice_url');
                            if ($xendit_invoice_url) :
                                echo '</div>'; // Close the wrapper div
                            endif;
                        endif;
                    endif;
                endif;
                ?>
            <?php endif; ?>
            <?php endif; // End is_user_logged_in() for bookings list ?>

        </div>
    </div>
</section>

<script>
// Optional: Add any booking-specific JavaScript here
document.addEventListener('DOMContentLoaded', function() {
    // Cancel booking confirmation
    const cancelButtons = document.querySelectorAll('a[href*="cancel_order"]');

    cancelButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('<?php _e('Are you sure you want to cancel this booking?', 'ckl-car-rental'); ?>')) {
                e.preventDefault();
            }
        });
    });
});
</script>

<?php get_footer(); ?>
