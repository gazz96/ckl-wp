<?php
/**
 * Services Selection Form Template Part
 *
 * Displays service selection options on booking form
 *
 * @package CKL_Car_Rental
 */

$vehicle_id = get_the_ID();
$services = ckl_get_vehicle_services($vehicle_id);

// Get vehicle-specific service overrides
$vehicle_services = get_post_meta($vehicle_id, '_vehicle_services', array());
?>

<?php if (!empty($services)) : ?>
<div class="booking-services-section">
    <h3 class="text-xl font-bold mb-4"><?php _e('Enhance Your Rental', 'ckl-car-rental'); ?></h3>
    <p class="text-gray-600 mb-4"><?php _e('Add optional services to your booking:', 'ckl-car-rental'); ?></p>

    <div class="space-y-3">
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
            $service_type = isset($vehicle_services[$service['id']]['type'])
                ? $vehicle_services[$service['id']]['type']
                : $service['type'];

            // Format price display
            $price_display = '';
            $data_price = '';
            $data_pricing_type = $pricing_type;

            if ($pricing_type === 'daily' && $price_per_day) {
                $price_display = sprintf('RM %s/%s', number_format($price_per_day, 2), __('day', 'ckl-car-rental'));
                $data_price = $price_per_day;
            } elseif ($pricing_type === 'hourly' && $price_per_hour) {
                $price_display = sprintf('RM %s/%s', number_format($price_per_hour, 2), __('hour', 'ckl-car-rental'));
                $data_price = $price_per_hour;
            } elseif ($pricing_type === 'one_time' && $price_one_time) {
                $price_display = sprintf('RM %s', number_format($price_one_time, 2));
                $data_price = $price_one_time;
            }
        ?>
            <div class="service-option flex items-center justify-between p-4 border rounded-lg hover:border-primary transition-colors">
                <div class="flex items-center gap-3">
                    <?php if ($service['icon']) : ?>
                        <span class="dashicons <?php echo esc_attr($service['icon']); ?> text-xl text-gray-600"></span>
                    <?php else : ?>
                        <span class="dashicons dashicons-cart text-xl text-gray-600"></span>
                    <?php endif; ?>

                    <div>
                        <label class="font-semibold cursor-pointer" for="service-<?php echo esc_attr($service['id']); ?>">
                            <?php echo esc_html($service['title']); ?>
                        </label>
                        <?php if ($service['description']) : ?>
                            <p class="text-sm text-gray-500"><?php echo esc_html($service['description']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <span class="font-semibold text-primary"><?php echo esc_html($price_display); ?></span>

                    <?php if ($service_type === 'quantity') : ?>
                        <input type="number"
                               name="services[<?php echo esc_attr($service['id']); ?>]"
                               id="service-<?php echo esc_attr($service['id']); ?>"
                               class="w-16 border rounded px-2 py-1 text-center"
                               min="0"
                               max="10"
                               value="0"
                               data-service-id="<?php echo esc_attr($service['id']); ?>"
                               data-price="<?php echo esc_attr($data_price); ?>"
                               data-pricing-type="<?php echo esc_attr($data_pricing_type); ?>">
                    <?php else : ?>
                        <input type="checkbox"
                               name="services[<?php echo esc_attr($service['id']); ?>]"
                               id="service-<?php echo esc_attr($service['id']); ?>"
                               class="w-5 h-5 text-primary"
                               data-service-id="<?php echo esc_attr($service['id']); ?>"
                               data-price="<?php echo esc_attr($data_price); ?>"
                               data-pricing-type="<?php echo esc_attr($data_pricing_type); ?>">
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (empty($services)) : ?>
        <p class="text-gray-500"><?php _e('No additional services available.', 'ckl-car-rental'); ?></p>
    <?php endif; ?>
</div>

<script>
jQuery(document).ready(function($) {
    // Update service pricing when checkbox changes
    $('.service-option input[type="checkbox"], .service-option input[type="number"]').on('change', function() {
        // Trigger custom event for booking form to handle
        $(document).trigger('ckl_services_changed');
    });
});
</script>
<?php endif; ?>
