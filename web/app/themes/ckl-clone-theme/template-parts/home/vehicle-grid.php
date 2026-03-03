<?php
/**
 * Vehicle Grid Section
 *
 * Displays featured vehicles with category filtering tabs
 */

$homepage_sections = get_option('ckl_homepage_sections', ckl_get_default_homepage_sections());
$vehicle_settings = get_option('ckl_vehicle_display_settings', ckl_get_default_vehicle_display_settings());

// Skip if disabled
if (!isset($homepage_sections['vehicle_grid']['enabled']) || !$homepage_sections['vehicle_grid']['enabled']) {
    return;
}

$show_tabs = isset($vehicle_settings['show_category_tabs']) ? $vehicle_settings['show_category_tabs'] : true;
$number_of_vehicles = isset($vehicle_settings['number_of_vehicles']) ? intval($vehicle_settings['number_of_vehicles']) : 8;
$grid_columns = isset($vehicle_settings['grid_columns']) ? intval($vehicle_settings['grid_columns']) : 4;

// Column classes
$column_classes = array(
    2 => 'grid-cols-1 md:grid-cols-2',
    3 => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3',
    4 => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4',
    5 => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5',
);
$grid_class = isset($column_classes[$grid_columns]) ? $column_classes[$grid_columns] : $column_classes[4];
?>

<section class="vehicle-grid py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-4">
            <?php _e('Our Vehicles', 'ckl-car-rental'); ?>
        </h2>
        <p class="text-center text-gray-600 mb-12 max-w-2xl mx-auto">
            <?php _e('Choose from our wide selection of well-maintained vehicles perfect for exploring Langkawi.', 'ckl-car-rental'); ?>
        </p>

        <!-- Category Tabs -->
        <?php if ($show_tabs) : ?>
            <div class="flex justify-center mb-10">
                <div class="inline-flex flex-wrap rounded-lg border border-gray-200 p-1 bg-white gap-1" role="tablist">
                    <button class="vehicle-tab px-4 py-2 rounded-md text-sm font-medium transition <?php echo !isset($_GET['type']) ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100'; ?>"
                            data-type="all"
                            role="tab">
                        <?php _e('All', 'ckl-car-rental'); ?>
                    </button>
                    <button class="vehicle-tab px-4 py-2 rounded-md text-sm font-medium transition <?php echo isset($_GET['type']) && $_GET['type'] === 'sedan' ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100'; ?>"
                            data-type="sedan"
                            role="tab">
                        <?php _e('Sedan', 'ckl-car-rental'); ?>
                    </button>
                    <button class="vehicle-tab px-4 py-2 rounded-md text-sm font-medium transition <?php echo isset($_GET['type']) && $_GET['type'] === 'compact' ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100'; ?>"
                            data-type="compact"
                            role="tab">
                        <?php _e('Compact', 'ckl-car-rental'); ?>
                    </button>
                    <button class="vehicle-tab px-4 py-2 rounded-md text-sm font-medium transition <?php echo isset($_GET['type']) && $_GET['type'] === 'mpv' ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100'; ?>"
                            data-type="mpv"
                            role="tab">
                        <?php _e('MPV', 'ckl-car-rental'); ?>
                    </button>
                    <button class="vehicle-tab px-4 py-2 rounded-md text-sm font-medium transition <?php echo isset($_GET['type']) && $_GET['type'] === 'luxury-mpv' ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100'; ?>"
                            data-type="luxury-mpv"
                            role="tab">
                        <?php _e('Luxury MPV', 'ckl-car-rental'); ?>
                    </button>
                    <button class="vehicle-tab px-4 py-2 rounded-md text-sm font-medium transition <?php echo isset($_GET['type']) && $_GET['type'] === 'suv' ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100'; ?>"
                            data-type="suv"
                            role="tab">
                        <?php _e('SUV', 'ckl-car-rental'); ?>
                    </button>
                    <button class="vehicle-tab px-4 py-2 rounded-md text-sm font-medium transition <?php echo isset($_GET['type']) && $_GET['type'] === '4x4' ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100'; ?>"
                            data-type="4x4"
                            role="tab">
                        <?php _e('4x4', 'ckl-car-rental'); ?>
                    </button>
                    <button class="vehicle-tab px-4 py-2 rounded-md text-sm font-medium transition <?php echo isset($_GET['type']) && $_GET['type'] === 'motorcycle' ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100'; ?>"
                            data-type="motorcycle"
                            role="tab">
                        <?php _e('Motorcycle', 'ckl-car-rental'); ?>
                    </button>
                </div>
            </div>
        <?php endif; ?>

        <!-- Vehicle Grid -->
        <div class="grid <?php echo $grid_class; ?> gap-6" id="vehicle-grid-container">
            <?php
            // Define categories to query
            $vehicle_categories = array('sedan', 'compact', 'mpv', 'luxury-mpv', 'suv', '4x4', 'motorcycle');

            // Store all vehicles
            $all_vehicles = array();

            // Query 3 vehicles from each category
            foreach ($vehicle_categories as $cat_slug) {
                $term = get_term_by('slug', $cat_slug, 'vehicle_category');

                if ($term && !is_wp_error($term)) {
                    $query_args = array(
                        'post_type' => 'vehicle',
                        'posts_per_page' => 3,
                        'post_status' => 'publish',
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'vehicle_category',
                                'field' => 'term_id',
                                'terms' => $term->term_id,
                            ),
                        ),
                    );

                    $cat_vehicles = new WP_Query($query_args);

                    if ($cat_vehicles->have_posts()) {
                        while ($cat_vehicles->have_posts()) {
                            $cat_vehicles->the_post();
                            global $post;
                            $all_vehicles[] = $post;
                        }
                        wp_reset_postdata();
                    }
                }
            }

            // Get current active type for filtering
            $current_type = isset($_GET['type']) ? sanitize_text_field($_GET['type']) : 'all';

            if (!empty($all_vehicles)) :
                foreach ($all_vehicles as $post) :
                    setup_postdata($post);
                    ?>
                    <div class="vehicle-card group" data-type="<?php echo ckl_get_vehicle_type_slug(get_the_ID()); ?>">
                        <?php get_template_part('template-parts/content', 'vehicle-card'); ?>
                    </div>
                    <?php
                endforeach;
                wp_reset_postdata();
            else :
                ?>
                <div class="col-span-full text-center py-12">
                    <div class="text-6xl mb-4">🚗</div>
                    <h3 class="text-2xl font-bold mb-2">
                        <?php _e('No vehicles found', 'ckl-car-rental'); ?>
                    </h3>
                    <p class="text-gray-600 mb-4">
                        <?php _e('Try selecting a different category.', 'ckl-car-rental'); ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>

        <!-- View All Button -->
        <div class="text-center mt-12">
            <a href="<?php echo get_post_type_archive_link('vehicle'); ?>"
               class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                <?php _e('View All Vehicles', 'ckl-car-rental'); ?>
                <svg class="inline-block w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab functionality
    const tabs = document.querySelectorAll('.vehicle-tab');
    const vehicleCards = document.querySelectorAll('.vehicle-card');

    tabs.forEach(function(tab) {
        tab.addEventListener('click', function() {
            const type = this.getAttribute('data-type');

            // Update active tab
            tabs.forEach(function(t) {
                t.classList.remove('bg-blue-600', 'text-white');
                t.classList.add('text-gray-700', 'hover:bg-gray-100');
            });
            this.classList.remove('text-gray-700', 'hover:bg-gray-100');
            this.classList.add('bg-blue-600', 'text-white');

            // Filter vehicles
            vehicleCards.forEach(function(card) {
                const cardType = card.getAttribute('data-type');

                if (type === 'all' || cardType === type) {
                    card.style.display = 'block';
                    card.style.animation = 'fadeIn 0.3s ease-in-out';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
});
</script>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<?php
/**
 * Helper function to get vehicle's type slug
 */
function ckl_get_vehicle_type_slug($vehicle_id) {
    // Get vehicle type from post meta
    $vehicle_type = get_post_meta($vehicle_id, '_vehicle_type', true);

    // If vehicle type is set, return it
    if (!empty($vehicle_type)) {
        // Convert underscore to hyphen if needed (e.g., luxury_mpv -> luxury-mpv)
        return str_replace('_', '-', $vehicle_type);
    }

    // Fallback to taxonomy
    $terms = wp_get_object_terms($vehicle_id, 'vehicle_category');

    if (empty($terms) || is_wp_error($terms)) {
        return 'other';
    }

    // Get the first term
    $term = $terms[0];

    // If this is a child term, get its slug
    if ($term->parent !== 0) {
        return $term->slug;
    }

    // If this is a parent term (Motorcycles), return the slug
    if ($term->slug === 'motorcycles') {
        return 'motorcycles';
    }

    return 'other';
}
?>
