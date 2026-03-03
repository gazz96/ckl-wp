<?php
/**
 * Vehicle Card Template Part
 * Modern design with full vehicle specs
 */

global $post;
$vehicle_id = get_the_ID();

// Get vehicle meta
$vehicle_type = get_post_meta($vehicle_id, '_vehicle_type', true);
$passenger_capacity = get_post_meta($vehicle_id, '_vehicle_passenger_capacity', true);
$doors = get_post_meta($vehicle_id, '_vehicle_doors', true);
$luggage_capacity = get_post_meta($vehicle_id, '_vehicle_luggage_capacity', true);
$price_per_day = get_post_meta($vehicle_id, '_vehicle_price_per_day', true);
$transmission = get_post_meta($vehicle_id, '_vehicle_transmission', true);
$has_air_conditioning = get_post_meta($vehicle_id, '_vehicle_has_air_conditioning', true);
$fuel_type = get_post_meta($vehicle_id, '_vehicle_fuel_type', true);

// Get average rating
$average_rating = 0;
if (class_exists('CKL_Reviews')) {
    $average_rating = CKL_Reviews::get_vehicle_average_rating($vehicle_id);
}

// Vehicle type labels
$type_labels = array(
    'sedan' => __('Sedan', 'ckl-car-rental'),
    'compact' => __('Compact', 'ckl-car-rental'),
    'mpv' => __('MPV', 'ckl-car-rental'),
    'luxury_mpv' => __('Luxury MPV', 'ckl-car-rental'),
    'suv' => __('SUV', 'ckl-car-rental'),
    '4x4' => __('4x4', 'ckl-car-rental'),
    'motorcycle' => __('Motorcycle', 'ckl-car-rental'),
);

$type_label = isset($type_labels[$vehicle_type]) ? $type_labels[$vehicle_type] : ucfirst(str_replace('_', ' ', $vehicle_type));

// Transmission label
$transmission_label = $transmission === 'automatic' ? __('Auto', 'ckl-car-rental') : __('Manual', 'ckl-car-rental');

// Check if bookmarked
$user_id = get_current_user_id();
$is_bookmarked = false;
if ($user_id) {
    $bookmarks = get_user_meta($user_id, '_vehicle_bookmarks', true);
    $is_bookmarked = is_array($bookmarks) && in_array($vehicle_id, $bookmarks);
}
?>

<article class="vehicle-card bg-white rounded-lg shadow hover:shadow-xl transition-all duration-300 overflow-hidden border border-transparent hover:border-primary/50 h-full flex flex-col">
    <div class="relative">
        <?php if (has_post_thumbnail()) : ?>
            <a href="<?php the_permalink(); ?>" class="block aspect-w-16 aspect-h-9">
                <?php the_post_thumbnail('medium', array('class' => 'w-full h-48 object-cover')); ?>
            </a>
        <?php else : ?>
            <div class="bg-gray-200 h-48 flex items-center justify-center">
                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </div>
        <?php endif; ?>

        <!-- Type Badge -->
        <span class="absolute top-3 left-3 bg-primary text-white text-xs px-3 py-1 rounded-full font-medium">
            <?php echo esc_html($type_label); ?>
        </span>

        <!-- Rating Badge -->
        <?php if ($average_rating > 0) : ?>
            <div class="absolute top-3 right-14 bg-white px-2 py-1 rounded text-sm font-medium shadow">
                ⭐ <?php echo esc_html($average_rating); ?>
            </div>
        <?php endif; ?>

        <!-- Bookmark Button -->
        <button
            class="bookmark-btn absolute top-3 right-3 bg-white p-2 rounded-full shadow hover:scale-110 transition"
            data-vehicle-id="<?php echo esc_attr($vehicle_id); ?>"
            data-nonce="<?php echo wp_create_nonce('ckl_bookmark_nonce'); ?>"
            data-bookmarked="<?php echo $is_bookmarked ? '1' : '0'; ?>"
        >
            <svg class="w-5 h-5 <?php echo $is_bookmarked ? 'text-red-500 fill-current' : 'text-gray-600'; ?>" fill="<?php echo $is_bookmarked ? 'currentColor' : 'none'; ?>" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
        </button>
    </div>

    <div class="p-5 flex flex-col flex-1">
        <!-- Vehicle Name -->
        <h3 class="text-xl font-bold mb-3">
            <a href="<?php the_permalink(); ?>" class="hover:text-primary transition-colors">
                <?php the_title(); ?>
            </a>
        </h3>

        <!-- Vehicle Specs Grid -->
        <div class="grid grid-cols-3 gap-3 mb-4">
            <!-- Seats -->
            <?php if ($passenger_capacity) : ?>
                <div class="flex flex-col items-center text-center p-2 bg-gray-50 rounded">
                    <svg class="w-5 h-5 text-gray-600 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="text-xs text-gray-600"><?php echo esc_html($passenger_capacity); ?> <?php _e('Seats', 'ckl-car-rental'); ?></span>
                </div>
            <?php endif; ?>

            <!-- Doors -->
            <?php if ($doors) : ?>
                <div class="flex flex-col items-center text-center p-2 bg-gray-50 rounded">
                    <svg class="w-5 h-5 text-gray-600 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path>
                    </svg>
                    <span class="text-xs text-gray-600"><?php echo esc_html($doors); ?> <?php _e('Doors', 'ckl-car-rental'); ?></span>
                </div>
            <?php endif; ?>

            <!-- Luggage -->
            <?php if ($luggage_capacity) : ?>
                <div class="flex flex-col items-center text-center p-2 bg-gray-50 rounded">
                    <svg class="w-5 h-5 text-gray-600 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <span class="text-xs text-gray-600"><?php echo esc_html($luggage_capacity); ?> <?php _e('Bags', 'ckl-car-rental'); ?></span>
                </div>
            <?php endif; ?>

            <!-- Air Conditioning -->
            <?php if ($has_air_conditioning) : ?>
                <div class="flex flex-col items-center text-center p-2 bg-gray-50 rounded">
                    <svg class="w-5 h-5 text-gray-600 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                    <span class="text-xs text-gray-600"><?php _e('A/C', 'ckl-car-rental'); ?></span>
                </div>
            <?php endif; ?>

            <!-- Transmission -->
            <?php if ($transmission) : ?>
                <div class="flex flex-col items-center text-center p-2 bg-gray-50 rounded">
                    <svg class="w-5 h-5 text-gray-600 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="text-xs text-gray-600"><?php echo esc_html($transmission_label); ?></span>
                </div>
            <?php endif; ?>

            <!-- Fuel Type -->
            <?php if ($fuel_type) : ?>
                <div class="flex flex-col items-center text-center p-2 bg-gray-50 rounded">
                    <svg class="w-5 h-5 text-gray-600 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path>
                    </svg>
                    <span class="text-xs text-gray-600"><?php echo esc_html(ucfirst($fuel_type)); ?></span>
                </div>
            <?php endif; ?>
        </div>

        <!-- Price and CTA -->
        <div class="flex items-center justify-between pt-4 border-t border-gray-200 mt-auto">
            <div>
                <?php if ($price_per_day) : ?>
                    <span class="text-2xl font-bold text-primary">
                        RM <?php echo number_format($price_per_day, 0); ?>
                    </span>
                    <span class="text-gray-500 text-sm">
                        /<?php _e('day', 'ckl-car-rental'); ?>
                    </span>
                <?php endif; ?>
            </div>
            <a href="<?php the_permalink(); ?>" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary/90 transition font-medium">
                <?php _e('Book Now', 'ckl-car-rental'); ?>
            </a>
        </div>
    </div>
</article>
