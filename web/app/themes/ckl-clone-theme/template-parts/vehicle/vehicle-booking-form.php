<?php
/**
 * Vehicle Booking Form Template Part
 *
 * @package CKL_Car_Rental
 */

extract($args);

if (empty($vehicle_id) || empty($product_id)) {
    return;
}

$product = wc_get_product($product_id);

if (!$product) {
    return;
}
?>

<div class="rounded-lg border bg-card shadow-sm p-6">
    <h3 class="font-bold text-2xl mb-4">Book This Vehicle</h3>

    <?php if (!empty($meta['price_per_day'])) : ?>
        <div class="mb-6 pb-6 border-b">
            <div class="text-3xl font-bold text-primary">
                RM <?php echo number_format($meta['price_per_day'], 0); ?>
            </div>
            <div class="text-gray-500">
                <?php esc_html_e('per day', 'ckl-car-rental'); ?>
            </div>
            <?php if (!empty($meta['price_per_hour'])) : ?>
                <div class="text-sm text-gray-500 mt-1">
                    RM <?php echo number_format($meta['price_per_hour'], 2); ?> / hour (partial days)
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Quick Booking Form -->
    <form id="ckl-booking-form" class="space-y-4" data-product-url="<?php echo esc_url(get_permalink($product_id)); ?>">
        <?php wp_nonce_field('ckl_booking_form', 'booking_nonce'); ?>

        <input type="hidden" name="vehicle_id" value="<?php echo esc_attr($vehicle_id); ?>">
        <input type="hidden" name="product_id" value="<?php echo esc_attr($product_id); ?>">

        <!-- Pick-up Date & Time -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Pick-up Date</label>
                <input type="date" name="pickup_date" required min="<?php echo esc_attr(date('Y-m-d')); ?>"
                       class="w-full border rounded-md px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Pick-up Time</label>
                <input type="time" name="pickup_time" value="10:00" required
                       class="w-full border rounded-md px-3 py-2">
            </div>
        </div>

        <!-- Return Date & Time -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Return Date</label>
                <input type="date" name="return_date" required min="<?php echo esc_attr(date('Y-m-d', strtotime('+2 days'))); ?>"
                       class="w-full border rounded-md px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Return Time</label>
                <input type="time" name="return_time" value="10:00" required
                       class="w-full border rounded-md px-3 py-2">
            </div>
        </div>

        <!-- Pick-up Location -->
        <div>
            <label class="block text-sm font-medium mb-1">Pick-up Location</label>
            <select name="pickup_location" class="w-full border rounded-md px-3 py-2">
                <option value="airport">Langkawi International Airport</option>
                <option value="jetty">Kuah Jetty</option>
                <option value="hotel">Hotel (Specify)</option>
                <option value="other">Other Location</option>
            </select>
        </div>

        <div id="hotel-name-field" class="hidden">
            <label class="block text-sm font-medium mb-1">Hotel Name</label>
            <input type="text" name="hotel_name" class="w-full border rounded-md px-3 py-2">
        </div>

        <!-- Return Location -->
        <div>
            <label class="block text-sm font-medium mb-1">Return Location</label>
            <select name="return_location" class="w-full border rounded-md px-3 py-2">
                <option value="same_as_pickup">Same as Pick-up</option>
                <option value="airport">Langkawi International Airport</option>
                <option value="jetty">Kuah Jetty</option>
                <option value="hotel">Hotel (Specify)</option>
                <option value="other">Other Location</option>
            </select>
        </div>

        <div id="return-hotel-name-field" class="hidden">
            <label class="block text-sm font-medium mb-1">Hotel Name</label>
            <input type="text" name="return_hotel_name" class="w-full border rounded-md px-3 py-2">
        </div>

        <!-- Guest Contact Fields (for non-logged users) -->
        <?php if (!is_user_logged_in()) : ?>
        <div class="guest-contact-fields space-y-4 mt-4 p-4 bg-gray-50 rounded-lg border">
            <h4 class="font-medium text-gray-900 mb-2">Contact Information</h4>
            <div>
                <label class="block text-sm font-medium mb-1">Email Address *</label>
                <input type="email" name="guest_email" required
                       class="w-full border rounded-md px-3 py-2"
                       placeholder="your@email.com">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Phone Number *</label>
                <input type="tel" name="guest_phone" required
                       class="w-full border rounded-md px-3 py-2"
                       placeholder="+60123456789">
            </div>
        </div>
        <?php endif; ?>

        <!-- Additional charge warning -->
        <div id="additional-charge-warning" class="hidden">
            <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded">
                Additional charges may apply for locations outside our designated area.
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Promo Code (Optional)</label>
            <input type="text" name="promo_code" class="w-full border rounded-md px-3 py-2">
        </div>

        <!-- Services Selection -->
        <?php get_template_part('template-parts/booking/services-selection'); ?>

        <!-- Availability Result Container -->
        <div id="availability-result"></div>

        <button type="submit" class="w-full bg-primary text-white py-3 rounded-md font-semibold hover:bg-primary/90 transition">
            Book Now
        </button>
    </form>

    <!-- Benefits -->
    <div class="mt-6 text-sm text-gray-600 space-y-2">
        <div class="flex items-center">
            <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <?php esc_html_e('Free cancellation up to 24 hours before pickup', 'ckl-car-rental'); ?>
        </div>
        <div class="flex items-center">
            <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <?php esc_html_e('No hidden fees', 'ckl-car-rental'); ?>
        </div>
        <div class="flex items-center">
            <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <?php esc_html_e('Comprehensive insurance included', 'ckl-car-rental'); ?>
        </div>
        <div class="flex items-center">
            <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <?php esc_html_e('24/7 roadside assistance', 'ckl-car-rental'); ?>
        </div>
    </div>

    <!-- Share -->
    <div class="mt-6 pt-6 border-t">
        <div class="flex gap-2">
            <a href="https://wa.me/?text=<?php echo urlencode(get_permalink()); ?>"
               target="_blank"
               class="flex-1 bg-green-500 text-white text-center py-2 rounded hover:bg-green-600 transition text-sm">
                <?php esc_html_e('Share', 'ckl-car-rental'); ?>
            </a>
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>"
               target="_blank"
               class="flex-1 bg-blue-600 text-white text-center py-2 rounded hover:bg-blue-700 transition text-sm">
                <?php esc_html_e('Share', 'ckl-car-rental'); ?>
            </a>
        </div>
    </div>
</div>
