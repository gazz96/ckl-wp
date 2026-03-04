<?php
/**
 * Reviews Shortcode
 *
 * Usage: [ckl_reviews count="6" columns="3" featured_only="true" show_vehicle="true"]
 *
 * @package CKL_Car_Rental
 */

defined('ABSPATH') || exit;

/**
 * Register reviews shortcode
 */
function ckl_reviews_shortcode($atts) {
    $atts = shortcode_atts(array(
        'count'         => '6',
        'columns'       => '3',
        'featured_only' => 'true',
        'show_vehicle'  => 'true',
        'class'         => '',
    ), $atts);

    // Sanitize attributes
    $count = ckl_sanitize_item_count($atts['count'], 1, 20, 6);
    $columns = ckl_sanitize_column_count($atts['columns'], 1, 4, 3);
    $featured_only = ckl_string_to_bool($atts['featured_only']);
    $show_vehicle = ckl_string_to_bool($atts['show_vehicle']);

    // Get manual reviews from settings
    $manual_reviews = get_option('ckl_manual_reviews', array());

    if (empty($manual_reviews)) {
        // Show placeholder when no reviews exist
        ob_start();
        ?>
        <section class="ckl-reviews ckl-shortcode-reviews py-16 bg-gray-50 <?php echo esc_attr($atts['class']); ?>">
            <div class="container mx-auto px-4">
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
            </div>
        </section>
        <?php
        return ob_get_clean();
    }

    // Filter reviews
    if ($featured_only) {
        $manual_reviews = array_filter($manual_reviews, function($review) {
            return isset($review['featured']) && $review['featured'] === true;
        });
    }

    // Sort by order
    usort($manual_reviews, function($a, $b) {
        $order_a = isset($a['order']) ? intval($a['order']) : 999;
        $order_b = isset($b['order']) ? intval($b['order']) : 999;
        return $order_a - $order_b;
    });

    // Limit count
    $manual_reviews = array_slice($manual_reviews, 0, $count);

    // Column grid classes
    $column_classes = array(
        1 => 'grid-cols-1 max-w-2xl mx-auto',
        2 => 'grid-cols-1 md:grid-cols-2',
        3 => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3',
        4 => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4',
    );
    $grid_class = isset($column_classes[$columns]) ? $column_classes[$columns] : $column_classes[3];

    ob_start();
    ?>
    <section class="ckl-reviews ckl-shortcode-reviews reviews-section py-16 bg-gray-50 <?php echo esc_attr($atts['class']); ?>">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl md:text-4xl font-bold text-center mb-4">
                <?php _e('What Our Customers Say', 'ckl-car-rental'); ?>
            </h2>
            <p class="text-center text-gray-600 mb-12 max-w-2xl mx-auto">
                <?php _e('Real experiences from real customers who explored Langkawi with us.', 'ckl-car-rental'); ?>
            </p>

            <div class="grid <?php echo $grid_class; ?> gap-8">
                <?php foreach ($manual_reviews as $review) : ?>
                    <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
                        <!-- Vehicle Info -->
                        <?php if ($show_vehicle && !empty($review['vehicle_name'])) : ?>
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
        </div>
    </section>
    <?php
    return ob_get_clean();
}
add_shortcode('ckl_reviews', 'ckl_reviews_shortcode');
