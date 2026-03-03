<?php
/**
 * Template Name: Reviews
 *
 * Reviews page template for CK Langkawi Car Rental
 * Displays customer reviews with filtering and pagination
 */

get_header();

// Get filter parameters
$rating_filter = isset($_GET['rating']) ? intval($_GET['rating']) : 0;
$vehicle_type_filter = isset($_GET['vehicle_type']) ? sanitize_text_field($_GET['vehicle_type']) : '';
$sort_by = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : 'recent';

// Pagination
$paged = get_query_var('paged') ? get_query_var('paged') : 1;
$per_page = 6;

// Build query args for reviews (using comments with comment_type 'review')
$comment_args = array(
    'post_type' => 'vehicle',
    'post_status' => 'publish',
    'comment_type' => 'review',
    'comment_approved' => '1',
    'number' => $per_page,
    'paged' => $paged,
);

// Apply sorting
if ($sort_by === 'highest') {
    $comment_args['orderby'] = 'comment_meta__rating';
    $comment_args['order'] = 'DESC';
} elseif ($sort_by === 'lowest') {
    $comment_args['orderby'] = 'comment_meta__rating';
    $comment_args['order'] = 'ASC';
} else {
    $comment_args['orderby'] = 'comment_date';
    $comment_args['order'] = 'DESC';
}

$reviews = get_comments($comment_args);
$total_reviews = get_comments(array_merge($comment_args, array('number' => 0, 'count' => true)));

// Filter reviews (post-query filtering due to limitations in comment queries)
if ($rating_filter || $vehicle_type_filter) {
    $filtered_reviews = array();
    foreach ($reviews as $review) {
        $rating = get_comment_meta($review->comment_ID, 'rating', true);

        // Rating filter
        if ($rating_filter && $rating < $rating_filter) {
            continue;
        }

        // Vehicle type filter
        if ($vehicle_type_filter) {
            $vehicle_type = get_post_meta($review->comment_post_ID, '_vehicle_type', true);
            if ($vehicle_type !== $vehicle_type_filter) {
                continue;
            }
        }

        $filtered_reviews[] = $review;
    }
    $reviews = $filtered_reviews;
}

// Calculate average rating
$all_ratings = array();
foreach (get_comments(array('post_type' => 'vehicle', 'comment_type' => 'review', 'status' => 'approve', 'number' => 0)) as $r) {
    $rating = get_comment_meta($r->comment_ID, 'rating', true);
    if ($rating) {
        $all_ratings[] = floatval($rating);
    }
}
$average_rating = !empty($all_ratings) ? array_sum($all_ratings) / count($all_ratings) : 0;
$total_reviews_count = count($all_ratings);
?>

<!-- Hero Section -->
<section class="hero-section relative bg-gradient-to-r from-blue-600 to-blue-800 text-white py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-4xl font-bold mb-4">
                <?php _e('Customer Reviews', 'ckl-car-rental'); ?>
            </h1>
            <p class="text-lg mb-6">
                <?php _e('See what our customers say about their experience', 'ckl-car-rental'); ?>
            </p>

            <!-- Stats -->
            <div class="flex items-center justify-center gap-8 flex-wrap">
                <div class="flex items-center">
                    <div class="text-5xl font-bold mr-3">
                        <?php echo number_format($average_rating, 1); ?>
                    </div>
                    <div class="text-left">
                        <div class="text-yellow-400 text-xl">
                            <?php echo str_repeat('★', round($average_rating)); ?>
                            <?php echo str_repeat('☆', 5 - round($average_rating)); ?>
                        </div>
                        <div class="text-sm opacity-80">
                            <?php printf(_n('%d review', '%d reviews', $total_reviews_count, 'ckl-car-rental'), $total_reviews_count); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Filter Bar -->
<section class="filter-bar py-6 bg-white border-b">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <form method="get" action="" class="flex flex-wrap items-center gap-4">
                <!-- Rating Filter -->
                <div class="flex items-center gap-2">
                    <label class="text-sm font-medium"><?php _e('Rating:', 'ckl-car-rental'); ?></label>
                    <select name="rating" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value=""><?php _e('All Ratings', 'ckl-car-rental'); ?></option>
                        <option value="5" <?php selected($rating_filter, 5); ?>><?php _e('5 Stars Only', 'ckl-car-rental'); ?></option>
                        <option value="4" <?php selected($rating_filter, 4); ?>><?php _e('4+ Stars', 'ckl-car-rental'); ?></option>
                        <option value="3" <?php selected($rating_filter, 3); ?>><?php _e('3+ Stars', 'ckl-car-rental'); ?></option>
                    </select>
                </div>

                <!-- Vehicle Type Filter -->
                <div class="flex items-center gap-2">
                    <label class="text-sm font-medium"><?php _e('Vehicle Type:', 'ckl-car-rental'); ?></label>
                    <select name="vehicle_type" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value=""><?php _e('All Types', 'ckl-car-rental'); ?></option>
                        <option value="motorcycle" <?php selected($vehicle_type_filter, 'motorcycle'); ?>><?php _e('Motorcycle', 'ckl-car-rental'); ?></option>
                        <option value="sedan" <?php selected($vehicle_type_filter, 'sedan'); ?>><?php _e('Sedan', 'ckl-car-rental'); ?></option>
                        <option value="mpv" <?php selected($vehicle_type_filter, 'mpv'); ?>><?php _e('MPV', 'ckl-car-rental'); ?></option>
                        <option value="suv" <?php selected($vehicle_type_filter, 'suv'); ?>><?php _e('SUV', 'ckl-car-rental'); ?></option>
                        <option value="luxury_mpv" <?php selected($vehicle_type_filter, 'luxury_mpv'); ?>><?php _e('Luxury MPV', 'ckl-car-rental'); ?></option>
                    </select>
                </div>

                <!-- Sort By -->
                <div class="flex items-center gap-2">
                    <label class="text-sm font-medium"><?php _e('Sort By:', 'ckl-car-rental'); ?></label>
                    <select name="sort" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="recent" <?php selected($sort_by, 'recent'); ?>><?php _e('Most Recent', 'ckl-car-rental'); ?></option>
                        <option value="highest" <?php selected($sort_by, 'highest'); ?>><?php _e('Highest Rated', 'ckl-car-rental'); ?></option>
                        <option value="lowest" <?php selected($sort_by, 'lowest'); ?>><?php _e('Lowest Rated', 'ckl-car-rental'); ?></option>
                    </select>
                </div>

                <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary/90 transition">
                    <?php _e('Apply Filters', 'ckl-car-rental'); ?>
                </button>

                <?php if ($rating_filter || $vehicle_type_filter) : ?>
                    <a href="<?php echo remove_query_arg(array('rating', 'vehicle_type')); ?>" class="text-primary hover:underline">
                        <?php _e('Clear Filters', 'ckl-car-rental'); ?>
                    </a>
                <?php endif; ?>
            </form>
        </div>
    </div>
</section>

<!-- Reviews List -->
<section class="reviews-content py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">

            <?php if (empty($reviews)) : ?>
                <!-- Empty State -->
                <div class="text-center py-16">
                    <div class="text-6xl mb-6">⭐</div>
                    <h2 class="text-2xl font-bold mb-4 text-gray-700">
                        <?php _e('No Reviews Found', 'ckl-car-rental'); ?>
                    </h2>
                    <p class="text-gray-600 mb-8 max-w-md mx-auto">
                        <?php _e('No reviews match your current filters. Try adjusting your criteria.', 'ckl-car-rental'); ?>
                    </p>
                    <a href="<?php echo remove_query_arg(array('rating', 'vehicle_type', 'sort')); ?>"
                       class="inline-block bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-primary/90 transition">
                        <?php _e('View All Reviews', 'ckl-car-rental'); ?>
                    </a>
                </div>
            <?php else : ?>
                <!-- Reviews Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php foreach ($reviews as $review) :
                        $rating = get_comment_meta($review->comment_ID, 'rating', true);
                        $vehicle_id = $review->comment_post_ID;
                        $vehicle = get_post($vehicle_id);
                        $verified = get_comment_meta($review->comment_ID, 'verified_booking', true);
                    ?>
                        <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
                            <!-- Review Header -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-accent/10 rounded-full flex items-center justify-center mr-3">
                                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-bold">
                                            <?php echo esc_html($review->comment_author); ?>
                                        </div>
                                        <?php if ($verified) : ?>
                                            <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">
                                                <?php _e('Verified Booking', 'ckl-car-rental'); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-yellow-500 text-lg">
                                        <?php echo str_repeat('★', $rating); ?>
                                        <?php echo str_repeat('☆', 5 - $rating); ?>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        <?php echo get_comment_date(get_option('date_format'), $review->comment_ID); ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Vehicle Info -->
                            <?php if ($vehicle) : ?>
                                <div class="mb-3 pb-3 border-b">
                                    <div class="text-sm text-gray-600 mb-1">
                                        <?php _e('Rented:', 'ckl-car-rental'); ?>
                                    </div>
                                    <a href="<?php echo get_permalink($vehicle_id); ?>"
                                       class="font-semibold text-primary hover:underline">
                                        <?php echo esc_html($vehicle->post_title); ?>
                                    </a>
                                </div>
                            <?php endif; ?>

                            <!-- Review Content -->
                            <div class="text-gray-700 mb-4">
                                <?php echo wpautop(wp_kses_post($review->comment_content)); ?>
                            </div>

                            <!-- Review Footer -->
                            <?php
                            // Check if review has useful votes
                            $useful_count = get_comment_meta($review->comment_ID, 'useful_count', true);
                            $useful_count = $useful_count ? $useful_count : 0;
                            ?>
                            <div class="flex items-center justify-between text-sm text-gray-500">
                                <div>
                                    <?php
                                    $review_date = get_comment_date('U', $review->comment_ID);
                                    $time_diff = human_time_diff($review_date, current_time('U'));
                                    printf(__('%s ago', 'ckl-car-rental'), $time_diff);
                                    ?>
                                </div>
                                <?php /*
                                <button class="flex items-center hover:text-primary transition">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                                    </svg>
                                    <?php printf(__('Useful (%d)', 'ckl-car-rental'), $useful_count); ?>
                                </button>
                                */ ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($total_reviews > $per_page) : ?>
                    <div class="mt-12">
                        <?php
                        $pagination_args = array(
                            'total' => ceil($total_reviews / $per_page),
                            'current' => $paged,
                            'prev_text' => __('&laquo; Previous', 'ckl-car-rental'),
                            'next_text' => __('Next &raquo;', 'ckl-car-rental'),
                            'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                        );

                        // Preserve query args in pagination
                        $query_args = array();
                        if ($rating_filter) $query_args['rating'] = $rating_filter;
                        if ($vehicle_type_filter) $query_args['vehicle_type'] = $vehicle_type_filter;
                        if ($sort_by) $query_args['sort'] = $sort_by;

                        if (!empty($query_args)) {
                            $pagination_args['add_args'] = $query_args;
                        }

                        echo paginate_links($pagination_args);
                        ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <!-- CTA Section -->
            <?php if (!is_user_logged_in()) : ?>
                <div class="mt-16 bg-accent/10 rounded-lg p-8 text-center">
                    <h3 class="text-2xl font-bold mb-4">
                        <?php _e('Rented With Us Before?', 'ckl-car-rental'); ?>
                    </h3>
                    <p class="text-gray-600 mb-6 max-w-2xl mx-auto">
                        <?php _e('Share your experience with other customers. Log in to leave a review for your recent rental.', 'ckl-car-rental'); ?>
                    </p>
                    <a href="<?php echo wp_login_url(get_permalink()); ?>"
                       class="inline-block bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-primary/90 transition">
                        <?php _e('Log In to Leave a Review', 'ckl-car-rental'); ?>
                    </a>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form on change for better UX
    const selectElements = document.querySelectorAll('select[name="rating"], select[name="vehicle_type"], select[name="sort"]');

    selectElements.forEach(select => {
        select.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });
});
</script>

<?php get_footer(); ?>
