<?php
/**
 * Vehicle Reviews Template Part
 *
 * @package CKL_Car_Rental
 */

$vehicle_id = get_the_ID();
$average_rating = 0;

if (class_exists('CKL_Reviews')) {
    $average_rating = CKL_Reviews::get_vehicle_average_rating($vehicle_id);
}
?>

<div class="rounded-lg border bg-card shadow-sm p-6">
    <h3 class="font-bold text-2xl mb-4">Customer Reviews</h3>

    <?php if ($average_rating > 0) : ?>
        <div class="flex items-center gap-2 mb-6">
            <span class="text-yellow-500 text-2xl">⭐</span>
            <span class="text-2xl font-bold"><?php echo esc_html($average_rating); ?></span>
            <span class="text-gray-500">
                (<?php
                $comments_count = wp_count_comments($vehicle_id)->approved;
                echo esc_html($comments_count . ' ' . _n('review', 'reviews', $comments_count, 'ckl-car-rental'));
                ?>)
            </span>
        </div>
    <?php endif; ?>

    <?php if (class_exists('CKL_Reviews')) : ?>
        <?php echo do_shortcode('[ckl_vehicle_reviews vehicle_id="' . $vehicle_id . '" limit="5"]'); ?>
    <?php else : ?>
        <?php comments_template(); ?>
    <?php endif; ?>
</div>
