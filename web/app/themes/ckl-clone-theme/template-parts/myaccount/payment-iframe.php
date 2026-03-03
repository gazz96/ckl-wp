<?php
/**
 * Payment Iframe Template Part
 *
 * Displays Xendit payment in an iframe with fallback button
 *
 * @package CKL_Car_Rental
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

$order_id = intval($args['order_id'] ?? 0);
$xendit_url = esc_url($args['xendit_url'] ?? '');

if (!$order_id || !$xendit_url) {
    return;
}

$order = wc_get_order($order_id);
if (!$order) {
    return;
}

// Don't show iframe if order is already paid/completed
if (!$order->has_status('pending')) {
    return;
}
?>

<div class="payment-iframe-container bg-white rounded-lg shadow-lg overflow-hidden">
    <div class="p-4 bg-gray-50 border-b flex items-center justify-between">
        <div>
            <h3 class="font-bold text-lg"><?php _e('Complete Your Payment', 'ckl-car-rental'); ?></h3>
            <p class="text-sm text-gray-600">
                <?php _e('Order #', 'ckl-car-rental'); ?><?php echo esc_html($order_id); ?>
            </p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                <span class="w-2 h-2 bg-yellow-500 rounded-full mr-2 animate-pulse"></span>
                <?php _e('Awaiting Payment', 'ckl-car-rental'); ?>
            </span>
        </div>
    </div>

    <!-- Iframe Container -->
    <div class="relative" style="height: 600px;">
        <iframe
            id="xendit-payment-iframe"
            src="<?php echo esc_url($xendit_url); ?>"
            class="w-full h-full border-0"
            frameborder="0"
            allow="payment; clipboard-write"
            allowpaymentrequest
            loading="eager"
            title="<?php _e('Xendit Payment', 'ckl-car-rental'); ?>"
        ></iframe>

        <!-- Fallback: If iframe is blocked -->
        <div id="iframe-fallback" class="hidden absolute inset-0 bg-white flex flex-col items-center justify-center p-8">
            <div class="text-center max-w-md">
                <div class="text-6xl mb-4"></div>
                <h3 class="text-xl font-bold mb-2"><?php _e('Payment Secure Window', 'ckl-car-rental'); ?></h3>
                <p class="text-gray-600 mb-6">
                    <?php _e('For your security, payment will open in a new window. Please complete your payment there.', 'ckl-car-rental'); ?>
                </p>
                <a
                    href="<?php echo esc_url($xendit_url); ?>"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex items-center px-6 py-3 bg-primary text-white rounded-lg font-semibold hover:bg-primary/90 transition"
                    id="open-payment-btn"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    <?php _e('Open Payment Page', 'ckl-car-rental'); ?>
                </a>
            </div>
        </div>
    </div>

    <!-- Fallback Button (always visible below iframe) -->
    <div class="p-4 bg-gray-50 border-t">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-sm text-gray-600">
                <?php _e('Having trouble with the payment form above?', 'ckl-car-rental'); ?>
            </p>
            <a
                href="<?php echo esc_url($xendit_url); ?>"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition"
            >
                <?php _e('Open in New Tab', 'ckl-car-rental'); ?>
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Detect if iframe is blocked (X-Frame-Options)
    const iframe = document.getElementById('xendit-payment-iframe');
    const fallback = document.getElementById('iframe-fallback');
    const order_id = <?php echo json_encode($order_id); ?>;

    // Try to detect iframe load errors
    iframe.addEventListener('error', function() {
        console.warn('Iframe failed to load');
        fallback.classList.remove('hidden');
    });

    // Fallback: Check if iframe appears to be blocked
    setTimeout(function() {
        try {
            // This will fail if iframe is from different origin (which is expected)
            // But if we can't access it at all, it might be blocked
            iframe.contentWindow.location.href;
        } catch (e) {
            // Cross-origin is normal, but show fallback option after 5 seconds
            setTimeout(function() {
                // User can manually click fallback if needed
                console.log('Payment iframe loaded (cross-origin)');
            }, 5000);
        }
    }, 2000);

    // Poll for order status updates (auto-refresh)
    let pollCount = 0;
    const maxPolls = 60; // Poll for 5 minutes (every 5 seconds)

    const pollInterval = setInterval(function() {
        pollCount++;

        if (pollCount > maxPolls) {
            clearInterval(pollInterval);
            return;
        }

        // AJAX request to check order status
        jQuery.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'ckl_check_order_status',
                order_id: order_id,
                nonce: '<?php echo wp_create_nonce('ckl_check_order_status'); ?>'
            },
            success: function(response) {
                if (response.success && response.data.status_changed) {
                    // Order status changed - reload page
                    location.reload();
                }
            }
        });
    }, 5000);

    // Clean up interval when user navigates away
    window.addEventListener('beforeunload', function() {
        clearInterval(pollInterval);
    });
});
</script>
