<?php
/**
 * Vehicle content template part
 */

$vehicle_id = get_the_ID();
$vehicle_type = get_post_meta($vehicle_id, '_vehicle_type', true);
$passenger_capacity = get_post_meta($vehicle_id, '_vehicle_passenger_capacity', true);
$price_per_day = get_post_meta($vehicle_id, '_vehicle_price_per_day', true);
$transmission = get_post_meta($vehicle_id, '_vehicle_transmission', true);
$has_air_conditioning = get_post_meta($vehicle_id, '_vehicle_has_air_conditioning', true);

// Get average rating
$average_rating = 0;
if (class_exists('CKL_Reviews')) {
    $average_rating = CKL_Reviews::get_vehicle_average_rating($vehicle_id);
}

// Type icons
$type_icons = array(
    'sedan' => '🚗',
    'compact' => '🚙',
    'mpv' => '🚐',
    'luxury_mpv' => '🚐',
    'suv' => '🚙',
    '4x4' => '🚙',
    'motorcycle' => '🏍️',
);
$icon = isset($type_icons[$vehicle_type]) ? $type_icons[$vehicle_type] : '🚗';
?>

<div class="vehicle-card bg-white rounded-lg shadow hover:shadow-lg transition overflow-hidden">
    <?php if (has_post_thumbnail()) : ?>
        <div class="vehicle-image relative">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('medium', array('class' => 'w-full h-48 object-cover')); ?>
            </a>

            <?php if ($average_rating > 0) : ?>
                <div class="absolute top-2 right-2 bg-white px-2 py-1 rounded text-sm">
                    ⭐ <?php echo $average_rating; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php else : ?>
        <div class="vehicle-image bg-gray-200 h-48 flex items-center justify-center">
            <span class="text-6xl"><?php echo $icon; ?></span>
        </div>
    <?php endif; ?>

    <div class="p-4">
        <h3 class="text-lg font-semibold mb-2">
            <a href="<?php the_permalink(); ?>" class="hover:text-primary">
                <?php the_title(); ?>
            </a>
        </h3>

        <div class="vehicle-specs grid grid-cols-2 gap-2 text-sm text-gray-600 mb-4">
            <?php if ($passenger_capacity) : ?>
                <div class="flex items-center">
                    <span class="mr-1">👥</span>
                    <?php echo $passenger_capacity; ?> <?php _e('seats', 'ckl-car-rental'); ?>
                </div>
            <?php endif; ?>

            <?php if ($transmission) : ?>
                <div class="flex items-center">
                    <span class="mr-1">⚙️</span>
                    <?php echo $transmission === 'automatic' ? __('Auto', 'ckl-car-rental') : __('Manual', 'ckl-car-rental'); ?>
                </div>
            <?php endif; ?>

            <?php if ($has_air_conditioning) : ?>
                <div class="flex items-center">
                    <span class="mr-1">❄️</span>
                    <?php _e('A/C', 'ckl-car-rental'); ?>
                </div>
            <?php endif; ?>

            <div class="flex items-center">
                <span class="mr-1">🏷️</span>
                <?php echo ucfirst(str_replace('_', ' ', $vehicle_type)); ?>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <div class="price">
                <?php if ($price_per_day) : ?>
                    <span class="text-2xl font-bold text-primary">
                        RM <?php echo number_format($price_per_day, 0); ?>
                    </span>
                    <span class="text-gray-500 text-sm">
                        /<?php _e('day', 'ckl-car-rental'); ?>
                    </span>
                <?php endif; ?>
            </div>

            <a href="<?php the_permalink(); ?>"
               class="bg-primary text-white px-4 py-2 rounded hover:bg-primary/90 transition text-sm">
                <?php _e('Book Now', 'ckl-car-rental'); ?>
            </a>
        </div>
    </div>
</div>
