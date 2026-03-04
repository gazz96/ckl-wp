<?php
/**
 * Vehicle Card Template Part
 * Modern design with full vehicle specs and stock availability
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
$total_units = get_post_meta($vehicle_id, '_vehicle_total_units', true);

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

// Show limited badge for vehicles with 3 or fewer units
$show_limited_badge = ($total_units && $total_units <= 3);
?>

<article class="rounded-lg border bg-card text-card-foreground shadow-sm overflow-hidden hover:shadow-lg transition-all duration-300 opacity-100 translate-y-0 h-full flex flex-col">
    <div class="relative bg-white p-6">
        <?php if (has_post_thumbnail()) : ?>
            <a href="<?php the_permalink(); ?>" class="block">
                <?php the_post_thumbnail('medium', array('class' => 'w-full h-40 object-contain')); ?>
            </a>
        <?php else : ?>
            <div class="bg-gray-200 h-40 flex items-center justify-center">
                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </div>
        <?php endif; ?>

        <!-- Disclaimer Overlay -->
        <span class="absolute bottom-2 right-2 text-[10px] text-muted-foreground/60 italic bg-white/80 px-1.5 py-0.5 rounded">
            *Car shown is for illustration only
        </span>

        <!-- LIMITED Badge -->
        <?php if ($show_limited_badge) : ?>
            <span class="absolute top-4 right-4 bg-destructive text-destructive-foreground text-xs px-2 py-1 rounded-full">LIMITED</span>
        <?php endif; ?>
    </div>

    <div class="p-5 flex flex-col flex-1">
        <!-- Vehicle Name -->
        <h3 class="text-lg font-bold mb-4">
            <a href="<?php the_permalink(); ?>" class="hover:text-primary transition-colors">
                <?php the_title(); ?>
            </a>
        </h3>

        <!-- Vehicle Specs Grid (6 columns) -->
        <div class="grid grid-cols-6 gap-2 mb-4 text-center">
            <!-- Passengers -->
            <?php if ($passenger_capacity) : ?>
                <div class="flex flex-col items-center">
                    <svg class="h-5 w-5 mb-1 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="text-xs font-semibold"><?php echo esc_html($passenger_capacity); ?></span>
                </div>
            <?php endif; ?>

            <!-- Doors -->
            <?php if ($doors) : ?>
                <div class="flex flex-col items-center">
                    <svg class="h-5 w-5 mb-1 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path>
                    </svg>
                    <span class="text-xs font-semibold"><?php echo esc_html($doors); ?></span>
                </div>
            <?php endif; ?>

            <!-- Luggage -->
            <?php if ($luggage_capacity) : ?>
                <div class="flex flex-col items-center">
                    <svg class="h-5 w-5 mb-1 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <span class="text-xs font-semibold"><?php echo esc_html($luggage_capacity); ?></span>
                </div>
            <?php endif; ?>

            <!-- Air Conditioning -->
            <div class="flex flex-col items-center">
                <svg class="h-5 w-5 mb-1 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                </svg>
                <span class="text-xs font-semibold"><?php echo $has_air_conditioning ? 'Yes' : 'No'; ?></span>
            </div>

            <!-- Transmission -->
            <?php if ($transmission) : ?>
                <div class="flex flex-col items-center">
                    <svg class="h-5 w-5 mb-1 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="text-xs font-semibold"><?php echo esc_html($transmission_label); ?></span>
                </div>
            <?php endif; ?>

            <!-- Fuel Type -->
            <?php if ($fuel_type) : ?>
                <div class="flex flex-col items-center">
                    <svg class="h-5 w-5 mb-1 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path>
                    </svg>
                    <span class="text-xs font-semibold"><?php echo esc_html(ucfirst($fuel_type)); ?></span>
                </div>
            <?php endif; ?>
        </div>

        <!-- Price and CTA -->
        <div class="flex items-center justify-between mt-6 mt-auto">
            <div>
                <p class="text-xs text-muted-foreground mb-1">From</p>
                <?php if ($price_per_day) : ?>
                    <p class="text-xl font-bold text-primary">
                        RM <?php echo number_format($price_per_day, 2); ?>
                    </p>
                <?php endif; ?>
                <?php if ($total_units) : ?>
                    <?php if ($total_units <= 2) : ?>
                        <p class="text-xs font-semibold mt-1 text-destructive">Only <?php echo esc_html($total_units); ?> units left!</p>
                    <?php elseif ($total_units <= 5) : ?>
                        <p class="text-xs font-semibold mt-1 text-secondary"><?php echo esc_html($total_units); ?> units left!</p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <a href="<?php the_permalink(); ?>" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 h-10 bg-secondary hover:bg-secondary/90 text-white px-6 py-2 rounded-md">
                <?php _e('Book Now', 'ckl-car-rental'); ?>
            </a>
        </div>
    </div>
</article>
