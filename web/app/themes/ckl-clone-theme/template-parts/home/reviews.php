<?php
/**
 * Reviews Section
 *
 * Displays manual customer reviews from settings
 */

$homepage_sections = get_option('ckl_homepage_sections', ckl_get_default_homepage_sections());

// Skip if disabled
if (!isset($homepage_sections['reviews']['enabled']) || !$homepage_sections['reviews']['enabled']) {
    return;
}

$manual_reviews = get_option('ckl_manual_reviews', array());

// Filter to show only featured reviews
$featured_reviews = array_filter($manual_reviews, function($review) {
    return isset($review['featured']) && $review['featured'] === true;
});

// Sort by order
usort($featured_reviews, function($a, $b) {
    $order_a = isset($a['order']) ? intval($a['order']) : 999;
    $order_b = isset($b['order']) ? intval($b['order']) : 999;
    return $order_a - $order_b;
});

// Limit to 6 reviews
$featured_reviews = array_slice($featured_reviews, 0, 6);
?>

<section class="reviews-section py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-4">
            <?php _e('What Our Customers Say', 'ckl-car-rental'); ?>
        </h2>
        <p class="text-center text-gray-600 mb-12 max-w-2xl mx-auto">
            <?php _e('Real experiences from real customers who explored Langkawi with us.', 'ckl-car-rental'); ?>
        </p>

        <?php if (!empty($featured_reviews)) : ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($featured_reviews as $review) : ?>
                    <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
                        <!-- Vehicle Info -->
                        <?php if (!empty($review['vehicle_name'])) : ?>
                            <div class="flex items-center mb-4">
                                <?php
                                $vehicle_image = '';
                                if (!empty($review['vehicle_id'])) {
                                    $vehicle_image = get_the_post_thumbnail_url($review['vehicle_id'], 'thumbnail');
                                }
                                if ($vehicle_image) :
                                    ?>
                                    <img src="<?php echo esc_url($vehicle_image); ?>"
                                         alt="<?php echo esc_attr($review['vehicle_name']); ?>"
                                         class="w-12 h-12 rounded-full object-cover mr-3">
                                <?php else : ?>
                                    <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                        <span class="text-2xl">🚗</span>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">
                                        <?php echo esc_html($review['vehicle_name']); ?>
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        <?php _e('Rented this vehicle', 'ckl-car-rental'); ?>
                                    </p>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Rating -->
                        <div class="flex items-center mb-3">
                            <?php
                            $rating = isset($review['rating']) ? intval($review['rating']) : 5;
                            for ($i = 1; $i <= 5; $i++) :
                                ?>
                                <svg class="w-5 h-5 <?php echo $i <= $rating ? 'text-yellow-400' : 'text-gray-300'; ?>"
                                     fill="currentColor"
                                     viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            <?php endfor; ?>
                        </div>

                        <!-- Review Text -->
                        <p class="text-gray-700 mb-4 leading-relaxed">
                            <?php echo esc_html($review['review_text']); ?>
                        </p>

                        <!-- Reviewer Info -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <div class="flex items-center">
                                <!-- Country Flag -->
                                <?php if (!empty($review['country_flag'])) : ?>
                                    <span class="text-2xl mr-2" role="img" aria-label="Country flag">
                                        <?php echo esc_html($review['country_flag']); ?>
                                    </span>
                                <?php endif; ?>
                                <div>
                                    <p class="font-semibold text-gray-900">
                                        <?php echo esc_html($review['reviewer_name']); ?>
                                    </p>
                                    <?php if (!empty($review['date'])) : ?>
                                        <p class="text-sm text-gray-500">
                                            <?php echo esc_html($review['date']); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <!-- Placeholder when no reviews exist -->
            <div class="text-center py-12">
                <div class="text-6xl mb-4">💬</div>
                <h3 class="text-2xl font-bold mb-2">
                    <?php _e('No Reviews Yet', 'ckl-car-rental'); ?>
                </h3>
                <p class="text-gray-600 mb-6">
                    <?php _e('Be the first to share your experience!', 'ckl-car-rental'); ?>
                </p>
                <a href="<?php echo get_post_type_archive_link('vehicle'); ?>"
                   class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                    <?php _e('Rent a Vehicle', 'ckl-car-rental'); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>
