<?php
/**
 * Template Name: Vehicle Listing Page
 *
 * Vehicle listing page with hybrid Gutenberg + PHP approach
 * Hero section is editable in Gutenberg, filters and grid are PHP templates
 */

get_header();

// Get filter parameters from URL
$search_term = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
$pickup_date = isset($_GET['pickup_date']) ? sanitize_text_field($_GET['pickup_date']) : '';
$return_date = isset($_GET['return_date']) ? sanitize_text_field($_GET['return_date']) : '';
$vehicle_types = isset($_GET['vehicle_type']) ? array_map('sanitize_text_field', $_GET['vehicle_type']) : array();

// Fetch vehicles based on filters
$vehicles = ckl_get_filtered_vehicles($search_term, $pickup_date, $return_date, $vehicle_types);
?>

<main class="pt-20">

    <!-- Hero Section - Editable in Gutenberg -->
    <?php
    if (have_posts()) {
        while (have_posts()) {
            the_post();
            the_content();
        }
    }
    ?>

    <!-- Vehicle Listing Section -->
    <section class="py-13">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

                <!-- Sidebar Filters -->
                <aside class="lg:col-span-1">
                    <?php get_template_part('template-parts/vehicle-filters'); ?>
                </aside>

                <!-- Vehicle Grid -->
                <div class="lg:col-span-3">

                    <!-- Results Count -->
                    <div class="mb-6 text-gray-600">
                        <?php
                        if ($vehicles->have_posts()) {
                            printf(
                                __('Showing %d of %d vehicles', 'ckl-car-rental'),
                                $vehicles->post_count,
                                $vehicles->found_posts
                            );
                        } else {
                            _e('No vehicles found', 'ckl-car-rental');
                        }
                        ?>
                    </div>

                    <!-- Vehicle Grid -->
                    <div id="vehicle-grid" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        <?php
                        if ($vehicles->have_posts()) {
                            while ($vehicles->have_posts()) {
                                $vehicles->the_post();
                                get_template_part('template-parts/content', 'vehicle-card');
                            }
                        } else {
                            ?>
                            <div class="col-span-full text-center py-12">
                                <div class="text-6xl mb-4">🚗</div>
                                <h2 class="text-2xl font-bold mb-2">
                                    <?php _e('No vehicles found', 'ckl-car-rental'); ?>
                                </h2>
                                <p class="text-gray-600 mb-4">
                                    <?php _e('Try adjusting your filters or search criteria.', 'ckl-car-rental'); ?>
                                </p>
                                <a href="<?php echo get_post_type_archive_link('vehicle'); ?>"
                                   class="inline-block bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary/90 transition">
                                    <?php _e('View All Vehicles', 'ckl-car-rental'); ?>
                                </a>
                            </div>
                            <?php
                        }
                        wp_reset_postdata();
                        ?>
                    </div>

                    <!-- Pagination -->
                    <?php
                    if ($vehicles->max_num_pages > 1) {
                        $pagination = paginate_links(array(
                            'total' => $vehicles->max_num_pages,
                            'current' => max(1, get_query_var('paged')),
                            'prev_text' => __('&laquo; Previous', 'ckl-car-rental'),
                            'next_text' => __('Next &raquo;', 'ckl-car-rental'),
                        ));

                        if ($pagination) {
                            echo '<div class="mt-8 flex justify-center">';
                            echo '<div class="pagination">' . $pagination . '</div>';
                            echo '</div>';
                        }
                    }
                    ?>

                </div>
            </div>
        </div>
    </section>

</main>

<?php get_footer(); ?>
