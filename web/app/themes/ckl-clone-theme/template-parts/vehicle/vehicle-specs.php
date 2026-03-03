<?php
/**
 * Vehicle Specifications Template Part
 *
 * @package CKL_Car_Rental
 */

extract($args);

if (empty($meta)) {
    return;
}
?>

<div class="rounded-lg border bg-card shadow-sm p-6">
    <h3 class="font-bold text-2xl mb-4">Vehicle Specifications</h3>

    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <?php if (!empty($meta['passenger_capacity'])) : ?>
            <div class="flex items-center gap-2 p-3 border rounded">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <div>
                    <div class="text-xs text-muted-foreground">Seats</div>
                    <div class="font-semibold"><?php echo esc_html($meta['passenger_capacity']); ?></div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($meta['doors'])) : ?>
            <div class="flex items-center gap-2 p-3 border rounded">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
                <div>
                    <div class="text-xs text-muted-foreground">Doors</div>
                    <div class="font-semibold"><?php echo esc_html($meta['doors']); ?></div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($meta['luggage'])) : ?>
            <div class="flex items-center gap-2 p-3 border rounded">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <div>
                    <div class="text-xs text-muted-foreground">Luggage</div>
                    <div class="font-semibold"><?php echo esc_html($meta['luggage']); ?> bags</div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($meta['transmission'])) : ?>
            <div class="flex items-center gap-2 p-3 border rounded">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <div>
                    <div class="text-xs text-muted-foreground">Transmission</div>
                    <div class="font-semibold"><?php echo esc_html(ucfirst($meta['transmission'])); ?></div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($meta['fuel_type'])) : ?>
            <div class="flex items-center gap-2 p-3 border rounded">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <div class="text-xs text-muted-foreground">Fuel Type</div>
                    <div class="font-semibold"><?php echo esc_html($meta['fuel_type']); ?></div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($meta['has_air_conditioning'])) : ?>
            <div class="flex items-center gap-2 p-3 border rounded">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
                <div>
                    <div class="text-xs text-muted-foreground">Air Conditioning</div>
                    <div class="font-semibold">Yes</div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
