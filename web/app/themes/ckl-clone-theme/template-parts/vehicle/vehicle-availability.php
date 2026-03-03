<?php
/**
 * Vehicle Availability Calendar Template Part
 *
 * @package CKL_Car_Rental
 */

extract($args);

if (empty($vehicle_id)) {
    return;
}
?>

<div class="rounded-lg border bg-card shadow-sm p-6">
    <div class="availability-calendar-container" data-vehicle-id="<?php echo esc_attr($vehicle_id); ?>">
        <div class="text-center p-12">
            <svg class="animate-spin h-8 w-8 text-primary mx-auto mb-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="text-gray-600">Loading calendar...</p>
        </div>
    </div>
</div>
