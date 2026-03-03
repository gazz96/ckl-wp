<?php
/**
 * Vehicle Filters Template Part
 */

// Get current filter values
$search_term = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
$pickup_date = isset($_GET['pickup_date']) ? sanitize_text_field($_GET['pickup_date']) : '';
$return_date = isset($_GET['return_date']) ? sanitize_text_field($_GET['return_date']) : '';
$vehicle_types = isset($_GET['vehicle_type']) ? (array) $_GET['vehicle_type'] : array();

// Define vehicle types
$all_vehicle_types = array(
    'sedan' => __('Sedan', 'ckl-car-rental'),
    'compact' => __('Compact', 'ckl-car-rental'),
    'mpv' => __('MPV', 'ckl-car-rental'),
    'luxury_mpv' => __('Luxury MPV', 'ckl-car-rental'),
    'suv' => __('SUV', 'ckl-car-rental'),
    '4x4' => __('4x4', 'ckl-car-rental'),
    'motorcycle' => __('Motorcycle', 'ckl-car-rental'),
);
?>

<div class="bg-white rounded-lg shadow-md p-6">
    <h3 class="text-xl font-bold mb-6 text-gray-900"><?php _e('Filter Vehicles', 'ckl-car-rental'); ?></h3>

    <form id="vehicle-filter-form" class="space-y-6">

        <!-- Search Input -->
        <div>
            <label for="search-vehicles" class="block text-sm font-medium text-gray-700 mb-2">
                <?php _e('Search', 'ckl-car-rental'); ?>
            </label>
            <div class="relative">
                <input
                    type="text"
                    id="search-vehicles"
                    name="s"
                    value="<?php echo esc_attr($search_term); ?>"
                    placeholder="<?php _e('Search vehicles...', 'ckl-car-rental'); ?>"
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                />
                <svg class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>

        <!-- Pickup Date -->
        <div>
            <label for="pickup-date" class="block text-sm font-medium text-gray-700 mb-2">
                <?php _e('Pick-up Date', 'ckl-car-rental'); ?>
            </label>
            <div class="relative">
                <input
                    type="date"
                    id="pickup-date"
                    name="pickup_date"
                    value="<?php echo esc_attr($pickup_date); ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                />
                <svg class="w-5 h-5 absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>

        <!-- Return Date -->
        <div>
            <label for="return-date" class="block text-sm font-medium text-gray-700 mb-2">
                <?php _e('Return Date', 'ckl-car-rental'); ?>
            </label>
            <div class="relative">
                <input
                    type="date"
                    id="return-date"
                    name="return_date"
                    value="<?php echo esc_attr($return_date); ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                />
                <svg class="w-5 h-5 absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>

        <!-- Vehicle Type Checkboxes -->
        <div>
            <h4 class="block text-sm font-medium text-gray-700 mb-3">
                <?php _e('Vehicle Type', 'ckl-car-rental'); ?>
            </h4>
            <div class="space-y-2">
                <?php foreach ($all_vehicle_types as $type_value => $type_label) : ?>
                    <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded transition">
                        <input
                            type="checkbox"
                            name="vehicle_type[]"
                            value="<?php echo esc_attr($type_value); ?>"
                            class="vehicle-type-filter w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary"
                            <?php checked(in_array($type_value, $vehicle_types)); ?>
                        />
                        <span class="ml-3 text-gray-700"><?php echo esc_html($type_label); ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Submit Button -->
        <button
            type="submit"
            class="w-full bg-primary text-white px-6 py-3 rounded-lg hover:bg-primary/90 transition font-medium"
        >
            <?php _e('Apply Filters', 'ckl-car-rental'); ?>
        </button>

        <!-- Clear Filters -->
        <?php if ($search_term || $pickup_date || $return_date || !empty($vehicle_types)) : ?>
            <a href="<?php echo get_post_type_archive_link('vehicle'); ?>" class="block text-center text-primary hover:underline">
                <?php _e('Clear All Filters', 'ckl-car-rental'); ?>
            </a>
        <?php endif; ?>

    </form>
</div>
