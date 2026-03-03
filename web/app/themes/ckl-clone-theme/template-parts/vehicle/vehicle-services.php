<?php
/**
 * Vehicle Services Template Part
 *
 * Displays available additional services for a vehicle
 *
 * @package CKL_Car_Rental
 */

$services = ckl_get_vehicle_services();

// Get vehicle-specific service overrides (if any)
$vehicle_services = get_post_meta(get_the_ID(), '_vehicle_services', array());
?>

<?php if (!empty($services)) : ?>
<div class="rounded-lg border bg-card shadow-sm p-6 mt-6">
    <h3 class="font-bold text-2xl mb-4"><?php _e('Additional Services', 'ckl-car-rental'); ?></h3>
    <div class="space-y-4">
        <?php foreach ($services as $service) :
            // Check if service is enabled for this vehicle
            if (isset($vehicle_services[$service['id']]['enabled']) && !$vehicle_services[$service['id']]['enabled']) {
                continue;
            }

            // Get pricing (can override per vehicle)
            $price_per_day = isset($vehicle_services[$service['id']]['price_per_day'])
                ? $vehicle_services[$service['id']]['price_per_day']
                : $service['price_per_day'];
            $price_per_hour = isset($vehicle_services[$service['id']]['price_per_hour'])
                ? $vehicle_services[$service['id']]['price_per_hour']
                : $service['price_per_hour'];
            $price_one_time = isset($vehicle_services[$service['id']]['price_one_time'])
                ? $vehicle_services[$service['id']]['price_one_time']
                : $service['price_one_time'];
            $pricing_type = isset($vehicle_services[$service['id']]['pricing_type'])
                ? $vehicle_services[$service['id']]['pricing_type']
                : $service['pricing_type'];

            // Format price display
            $price_display = '';
            if ($pricing_type === 'daily' && $price_per_day) {
                $price_display = sprintf('RM %s/%s', number_format($price_per_day, 2), __('day', 'ckl-car-rental'));
            } elseif ($pricing_type === 'hourly' && $price_per_hour) {
                $price_display = sprintf('RM %s/%s', number_format($price_per_hour, 2), __('hour', 'ckl-car-rental'));
            } elseif ($pricing_type === 'one_time' && $price_one_time) {
                $price_display = sprintf('RM %s', number_format($price_one_time, 2));
            }
        ?>
            <div class="service-item flex items-start justify-between p-4 border rounded-lg hover:bg-gray-50 transition-colors">
                <div class="flex items-start gap-3">
                    <?php if ($service['icon']) : ?>
                        <span class="dashicons <?php echo esc_attr($service['icon']); ?> text-2xl text-primary"></span>
                    <?php else : ?>
                        <span class="dashicons dashicons-cart text-2xl text-primary"></span>
                    <?php endif; ?>
                    <div>
                        <h4 class="font-semibold text-lg"><?php echo esc_html($service['title']); ?></h4>
                        <?php if ($service['description']) : ?>
                            <p class="text-gray-600 text-sm"><?php echo esc_html($service['description']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if ($price_display) : ?>
                    <div class="text-right">
                        <span class="font-bold text-lg text-primary"><?php echo esc_html($price_display); ?></span>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>
