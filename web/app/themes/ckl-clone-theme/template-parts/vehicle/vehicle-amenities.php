<?php
/**
 * Vehicle Amenities Template Part
 *
 * @package CKL_Car_Rental
 */

extract($args);

// Get vehicle's amenity terms
$amenities = wp_get_post_terms(get_the_ID(), 'vehicle_amenity');

// Hide section if no amenities
if (empty($amenities) || is_wp_error($amenities)) {
    return;
}
?>

<div class="rounded-lg border bg-card shadow-sm p-6">
    <h3 class="font-bold text-2xl mb-4">Premium Amenities</h3>
    <div class="flex flex-wrap gap-3">
        <?php foreach ($amenities as $amenity) : ?>
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-primary flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span class="text-gray-700"><?php echo esc_html($amenity->name); ?></span>
            </div>
        <?php endforeach; ?>
    </div>
</div>
