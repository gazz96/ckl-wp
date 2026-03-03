<?php
/**
 * Vehicle Archive Template
 */

get_header();

// Get URL parameters
$pickup_date = isset($_GET['pickup_date']) ? sanitize_text_field($_GET['pickup_date']) : '';
$return_date = isset($_GET['return_date']) ? sanitize_text_field($_GET['return_date']) : '';
$vehicle_type = isset($_GET['vehicle_type']) ? sanitize_text_field($_GET['vehicle_type']) : '';
$category = isset($_GET['category']) ? sanitize_text_field($_GET['category']) : '';
$pickup_location = isset($_GET['pickup_location']) ? sanitize_text_field($_GET['pickup_location']) : '';
$return_location = isset($_GET['return_location']) ? sanitize_text_field($_GET['return_location']) : '';
$pickup_time = isset($_GET['pickup_time']) ? sanitize_text_field($_GET['pickup_time']) : '';
$return_time = isset($_GET['return_time']) ? sanitize_text_field($_GET['return_time']) : '';
?>

<main class="container mx-auto my-8 px-4">
    <h1 class="text-4xl font-bold mb-8 text-center">
        <?php post_type_archive_title(); ?>
    </h1>

    <!-- Search/Filter Form -->
    <div class="bg-gray-50 p-6 rounded-lg mb-8">
        <form id="vehicle-filter-form" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium mb-2"><?php _e('Pick-up Date', 'ckl-car-rental'); ?></label>
                <input type="date" name="pickup_date" id="pickup_date"
                       value="<?php echo esc_attr($pickup_date); ?>"
                       class="w-full px-3 py-2 border rounded">
            </div>
            <div>
                <label class="block text-sm font-medium mb-2"><?php _e('Return Date', 'ckl-car-rental'); ?></label>
                <input type="date" name="return_date" id="return_date"
                       value="<?php echo esc_attr($return_date); ?>"
                       class="w-full px-3 py-2 border rounded">
            </div>
            <div>
                <label class="block text-sm font-medium mb-2"><?php _e('Vehicle Type', 'ckl-car-rental'); ?></label>
                <select name="vehicle_type" id="vehicle_type" class="w-full px-3 py-2 border rounded">
                    <option value=""><?php _e('All Types', 'ckl-car-rental'); ?></option>
                    <option value="sedan" <?php selected($vehicle_type, 'sedan'); ?>><?php _e('Sedan', 'ckl-car-rental'); ?></option>
                    <option value="compact" <?php selected($vehicle_type, 'compact'); ?>><?php _e('Compact', 'ckl-car-rental'); ?></option>
                    <option value="mpv" <?php selected($vehicle_type, 'mpv'); ?>><?php _e('MPV', 'ckl-car-rental'); ?></option>
                    <option value="luxury_mpv" <?php selected($vehicle_type, 'luxury_mpv'); ?>><?php _e('Luxury MPV', 'ckl-car-rental'); ?></option>
                    <option value="suv" <?php selected($vehicle_type, 'suv'); ?>><?php _e('SUV', 'ckl-car-rental'); ?></option>
                    <option value="4x4" <?php selected($vehicle_type, '4x4'); ?>><?php _e('4x4', 'ckl-car-rental'); ?></option>
                    <option value="motorcycle" <?php selected($vehicle_type, 'motorcycle'); ?>><?php _e('Motorcycle', 'ckl-car-rental'); ?></option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-primary text-white px-6 py-2 rounded hover:bg-primary/90 transition">
                    <?php _e('Filter Vehicles', 'ckl-car-rental'); ?>
                </button>
            </div>
        </form>

        <!-- Hidden inputs to preserve hero search parameters -->
        <?php if ($category): ?>
            <input type="hidden" name="category" value="<?php echo esc_attr($category); ?>">
        <?php endif; ?>
        <?php if ($pickup_location): ?>
            <input type="hidden" name="pickup_location" value="<?php echo esc_attr($pickup_location); ?>">
        <?php endif; ?>
        <?php if ($return_location): ?>
            <input type="hidden" name="return_location" value="<?php echo esc_attr($return_location); ?>">
        <?php endif; ?>
        <?php if ($pickup_time): ?>
            <input type="hidden" name="pickup_time" value="<?php echo esc_attr($pickup_time); ?>">
        <?php endif; ?>
        <?php if ($return_time): ?>
            <input type="hidden" name="return_time" value="<?php echo esc_attr($return_time); ?>">
        <?php endif; ?>

        <?php if ($pickup_date || $return_date || $vehicle_type || $category || $pickup_location) : ?>
            <div class="mt-4">
                <a href="<?php echo get_post_type_archive_link('vehicle'); ?>" class="text-primary hover:underline">
                    <?php _e('Clear Filters', 'ckl-car-rental'); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Results Count -->
    <?php
    $args = array(
        'post_type' => 'vehicle',
        'posts_per_page' => 12,
        'post_status' => 'publish',
        'paged' => get_query_var('paged') ? get_query_var('paged') : 1,
    );

    // Filter by vehicle type
    if ($vehicle_type) {
        $args['meta_query'] = array(
            array(
                'key' => '_vehicle_type',
                'value' => $vehicle_type,
                'compare' => '=',
            ),
        );
    }

    // Filter by category (cars vs motorcycles)
    if ($category && ('cars' === $category || 'motorcycles' === $category)) {
        // Map category to taxonomy term
        $category_term = 'cars' === $category ? 'car' : 'motorcycle';

        // Check if vehicle_category taxonomy exists and filter by it
        if (taxonomy_exists('vehicle_category')) {
            $tax_query = array(
                array(
                    'taxonomy' => 'vehicle_category',
                    'field' => 'slug',
                    'terms' => $category_term,
                ),
            );

            // Merge with existing tax_query if any
            if (isset($args['tax_query'])) {
                $args['tax_query']['relation'] = 'AND';
                $args['tax_query'][] = $tax_query[0];
            } else {
                $args['tax_query'] = $tax_query;
            }
        }
    }

    $vehicles_query = new WP_Query($args);
    ?>

    <!-- Search Summary -->
    <?php if ($pickup_date || $return_date || $pickup_location || $category): ?>
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <h3 class="font-semibold text-blue-900 mb-2"><?php _e('Your Search', 'ckl-car-rental'); ?></h3>
        <div class="text-sm text-blue-800 space-y-1">
            <?php if ($category): ?>
                <div>
                    <span class="font-medium"><?php _e('Vehicle Type:', 'ckl-car-rental'); ?></span>
                    <?php echo esc_html('cars' === $category ? 'Car Rental' : 'Bike/Motorcycle'); ?>
                </div>
            <?php endif; ?>

            <?php if ($pickup_location && class_exists('CKL_Hero_Search_Locations')): ?>
                <?php
                $all_locations = CKL_Hero_Search_Locations::get_all_locations();
                $pickup_loc_name = '';
                foreach ($all_locations['free'] as $slug => $loc) {
                    if ($slug === $pickup_location) $pickup_loc_name = $loc['name'];
                }
                foreach ($all_locations['custom'] as $slug => $loc) {
                    if ($slug === $pickup_location) $pickup_loc_name = $loc['name'];
                }
                ?>
                <?php if ($pickup_loc_name): ?>
                    <div>
                        <span class="font-medium"><?php _e('Pick-up:', 'ckl-car-rental'); ?></span>
                        <?php echo esc_html($pickup_loc_name); ?>
                        <?php if ($pickup_time): ?>
                            <?php echo esc_html(date('h:i A', strtotime($pickup_time))); ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ($return_location && class_exists('CKL_Hero_Search_Locations')): ?>
                <?php
                $return_loc_name = '';
                foreach ($all_locations['free'] as $slug => $loc) {
                    if ($slug === $return_location) $return_loc_name = $loc['name'];
                }
                foreach ($all_locations['custom'] as $slug => $loc) {
                    if ($slug === $return_location) $return_loc_name = $loc['name'];
                }
                ?>
                <?php if ($return_loc_name): ?>
                    <div>
                        <span class="font-medium"><?php _e('Return:', 'ckl-car-rental'); ?></span>
                        <?php echo esc_html($return_loc_name); ?>
                        <?php if ($return_time): ?>
                            <?php echo esc_html(date('h:i A', strtotime($return_time))); ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ($pickup_date && $return_date): ?>
                <div>
                    <span class="font-medium"><?php _e('Dates:', 'ckl-car-rental'); ?></span>
                    <?php echo esc_html(date('M j, Y', strtotime($pickup_date))); ?>
                    <?php _e('to', 'ckl-car-rental'); ?>
                    <?php echo esc_html(date('M j, Y', strtotime($return_date))); ?>
                </div>
            <?php endif; ?>

            <?php if ($return_location && $pickup_location !== $return_location && class_exists('CKL_Hero_Search_Locations')): ?>
                <?php
                $dropoff_fee = CKL_Hero_Search_Locations::calculate_dropoff_fee($pickup_location, $return_location);
                if ($dropoff_fee > 0):
                ?>
                    <div class="text-orange-600 font-medium mt-2">
                        <?php _e('Drop-off fee applies:', 'ckl-car-rental'); ?> RM<?php echo esc_html($dropoff_fee); ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="mb-6 text-gray-600">
        <?php
        printf(
            __('Showing %d of %d vehicles', 'ckl-car-rental'),
            $vehicles_query->post_count,
            $vehicles_query->found_posts
        );
        ?>
    </div>

    <!-- Dynamic Pricing Notice -->
    <?php if ($pickup_date && $return_date && class_exists('CKL_Dynamic_Pricing')) : ?>
        <div id="dynamic-pricing-notice" class="hidden mb-6">
            <!-- Will be populated via AJAX -->
        </div>
    <?php endif; ?>

    <!-- Vehicle Grid -->
    <?php if ($vehicles_query->have_posts()) : ?>
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php while ($vehicles_query->have_posts()) : $vehicles_query->the_post(); ?>
                <?php get_template_part('template-parts/content', 'vehicle'); ?>
            <?php endwhile; ?>
        </div>

        <!-- Pagination -->
        <?php
        $pagination = paginate_links(array(
            'total' => $vehicles_query->max_num_pages,
            'current' => max(1, get_query_var('paged')),
            'prev_text' => __('&laquo; Previous', 'ckl-car-rental'),
            'next_text' => __('Next &raquo;', 'ckl-car-rental'),
        ));

        if ($pagination) :
        ?>
            <div class="mt-8 flex justify-center">
                <div class="pagination">
                    <?php echo $pagination; ?>
                </div>
            </div>
        <?php endif; ?>

    <?php else : ?>
        <div class="text-center py-12">
            <div class="text-6xl mb-4">🚗</div>
            <h2 class="text-2xl font-bold mb-2">
                <?php _e('No vehicles found', 'ckl-car-rental'); ?>
            </h2>
            <p class="text-gray-600 mb-4">
                <?php _e('Try adjusting your filters or search criteria.', 'ckl-car-rental'); ?>
            </p>
            <a href="<?php echo get_post_type_archive_link('vehicle'); ?>"
               class="inline-block bg-primary text-white px-6 py-2 rounded hover:bg-primary/90 transition">
                <?php _e('View All Vehicles', 'ckl-car-rental'); ?>
            </a>
        </div>
    <?php endif; ?>

    <?php wp_reset_postdata(); ?>
</main>

<script>
// Auto-submit form on change
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('vehicle-filter-form');
    const inputs = form.querySelectorAll('select, input');

    inputs.forEach(function(input) {
        input.addEventListener('change', function() {
            form.submit();
        });
    });

    // Set minimum dates
    const today = new Date().toISOString().split('T')[0];
    const pickupDate = document.getElementById('pickup_date');
    const returnDate = document.getElementById('return_date');

    pickupDate.setAttribute('min', today);

    pickupDate.addEventListener('change', function() {
        returnDate.setAttribute('min', this.value);
    });

    // Check dynamic pricing if dates are selected
    <?php if ($pickup_date && $return_date) : ?>
        document.querySelectorAll('.vehicle-card').forEach(function(card) {
            const vehicleId = card.dataset.vehicleId;
            // Add data-vehicle-id attribute to vehicle cards
            // This can be enhanced with AJAX calls for dynamic pricing
        });
    <?php endif; ?>
});
</script>

<?php get_footer(); ?>
