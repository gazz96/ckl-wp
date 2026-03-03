<?php
/**
 * Vehicle Special Pricing Template Part
 *
 * @package CKL_Car_Rental
 */

extract($args);

if (empty($special_pricing) || empty($base_price)) {
    return;
}
?>

<div class="rounded-lg border bg-card shadow-sm p-6">
    <h3 class="font-bold text-2xl mb-4">Special Offers</h3>

    <div class="space-y-4">
        <?php foreach ($special_pricing as $offer) :
            if (empty($offer['name']) || empty($offer['start_date']) || empty($offer['end_date']) || empty($offer['price'])) {
                continue;
            }
            ?>
            <div class="flex items-center justify-between p-4 bg-green-50 border border-green-200 rounded-lg">
                <div>
                    <div class="font-bold text-lg text-green-800"><?php echo esc_html($offer['name']); ?></div>
                    <div class="text-sm text-green-600">
                        <?php
                        $start = date('M j', strtotime($offer['start_date']));
                        $end = date('M j, Y', strtotime($offer['end_date']));
                        echo esc_html($start . ' - ' . $end);
                        ?>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-sm text-green-600 line-through">
                        RM <?php echo number_format($base_price, 0); ?>/day
                    </div>
                    <div class="text-2xl font-bold text-green-700">
                        RM <?php echo number_format($offer['price'], 0); ?>
                        <span class="text-sm font-normal">/day</span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
