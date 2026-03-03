<?php
/**
 * Mobile Search Template Part
 *
 * @package CKL_Car_Rental
 */

$settings = get_option('ckl_hero_settings', ckl_get_default_hero_settings());
$search_button_text = $settings['search_button_text'] ?? __('Search Vehicles', 'ckl-car-rental');
?>

<!-- Mobile Search Section -->
<section class="ckl-mobile-search bg-white border-t border-b border-gray-200 py-6 lg:hidden">
    <div class="container mx-auto px-4">
        <form action="<?php echo esc_url(home_url('/vehicles/')); ?>" method="GET" class="space-y-4">
            <!-- Pickup Location -->
            <div>
                <label for="mobile-pickup-location" class="block text-sm font-medium text-gray-700 mb-1">
                    <?php _e('Pick-up Location', 'ckl-car-rental'); ?>
                </label>
                <input type="text"
                       id="mobile-pickup-location"
                       name="location"
                       placeholder="<?php _e('Langkawi Airport or Hotel', 'ckl-car-rental'); ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <!-- Pickup Date -->
            <div>
                <label for="mobile-pickup-date" class="block text-sm font-medium text-gray-700 mb-1">
                    <?php _e('Pick-up Date', 'ckl-car-rental'); ?>
                </label>
                <input type="date"
                       id="mobile-pickup-date"
                       name="pickup_date"
                       min="<?php echo date('Y-m-d'); ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <!-- Return Date -->
            <div>
                <label for="mobile-return-date" class="block text-sm font-medium text-gray-700 mb-1">
                    <?php _e('Return Date', 'ckl-car-rental'); ?>
                </label>
                <input type="date"
                       id="mobile-return-date"
                       name="return_date"
                       min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <!-- Vehicle Category -->
            <div>
                <label for="mobile-category" class="block text-sm font-medium text-gray-700 mb-1">
                    <?php _e('Vehicle Type', 'ckl-car-rental'); ?>
                </label>
                <select id="mobile-category"
                        name="category"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value=""><?php _e('All Vehicles', 'ckl-car-rental'); ?></option>
                    <option value="cars"><?php _e('Cars', 'ckl-car-rental'); ?></option>
                    <option value="motorcycles"><?php _e('Motorcycles', 'ckl-car-rental'); ?></option>
                </select>
            </div>

            <!-- Search Button -->
            <button type="submit"
                    class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 transition-colors flex items-center justify-center">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.34-4.34"/>
                    <circle cx="11" cy="11" r="8"/>
                </svg>
            </button>
        </form>
    </div>
</section>

<script>
jQuery(document).ready(function($) {
    // Set minimum return date based on pickup date
    $('#mobile-pickup-date').on('change', function() {
        var pickupDate = $(this).val();
        if (pickupDate) {
            var nextDay = new Date(pickupDate);
            nextDay.setDate(nextDay.getDate() + 1);
            $('#mobile-return-date').attr('min', nextDay.toISOString().split('T')[0]);
        }
    });
});
</script>
