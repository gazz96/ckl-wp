<?php
/**
 * Vehicle Grid Shortcode
 *
 * Usage: [ckl_vehicle_grid columns="4" count="8" show_tabs="true" categories="sedan,compact,mpv" featured_only="false"]
 *
 * @package CKL_Car_Rental
 */

defined('ABSPATH') || exit;

/**
 * Helper function to get vehicle's type slug
 * (Duplicated from template part for shortcode use)
 */
function ckl_shortcode_get_vehicle_type_slug($vehicle_id) {
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

/**
 * Register vehicle grid shortcode
 */
function ckl_vehicle_grid_shortcode($atts) {
    $atts = shortcode_atts(array(
        'columns'       => '4',
        'count'         => '8',
        'show_tabs'     => 'true',
        'categories'    => '', // Comma-separated list or 'all'
        'featured_only' => 'false',
        'class'         => '',
    ), $atts);

    // Sanitize attributes
    $columns = ckl_sanitize_column_count($atts['columns'], 2, 5, 4);
    $count = ckl_sanitize_item_count($atts['count'], 1, 50, 8);
    $show_tabs = ckl_string_to_bool($atts['show_tabs']);
    $featured_only = ckl_string_to_bool($atts['featured_only']);

    // Parse categories
    $specified_categories = array();
    if (!empty($atts['categories']) && strtolower($atts['categories']) !== 'all') {
        $specified_categories = ckl_parse_categories_attribute($atts['categories']);
    }

    // Define available categories
    $all_categories = array('sedan', 'compact', 'mpv', 'luxury-mpv', 'suv', '4x4', 'motorcycle');

    // Filter to specified categories if provided
    $categories_to_query = !empty($specified_categories) ?
        array_intersect($all_categories, $specified_categories) :
        $all_categories;

    // Column classes
    $column_classes = array(
        2 => 'grid-cols-1 md:grid-cols-2',
        3 => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3',
        4 => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4',
        5 => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5',
    );
    $grid_class = isset($column_classes[$columns]) ? $column_classes[$columns] : $column_classes[4];

    // Store all vehicles
    $all_vehicles = array();

    // Query vehicles from each category
    $vehicles_per_category = $featured_only ? 3 : ceil($count / max(1, count($categories_to_query)));

    foreach ($categories_to_query as $cat_slug) {
        $term = get_term_by('slug', $cat_slug, 'vehicle_category');

        if ($term && !is_wp_error($term)) {
            $query_args = array(
                'post_type' => 'vehicle',
                'posts_per_page' => $vehicles_per_category,
                'post_status' => 'publish',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'vehicle_category',
                        'field' => 'term_id',
                        'terms' => $term->term_id,
                    ),
                ),
            );

            // Add featured filter if specified
            if ($featured_only) {
                $query_args['meta_query'] = array(
                    array(
                        'key' => '_featured_vehicle',
                        'value' => 'yes',
                        'compare' => '=',
                    ),
                );
            }

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

    // Limit total count
    $all_vehicles = array_slice($all_vehicles, 0, $count);

    // Generate unique ID for this instance
    $instance_id = 'ckl-vehicle-grid-' . uniqid();

    // Determine active tabs
    $active_tabs = !empty($specified_categories) ? $specified_categories : array_merge(array('all'), $all_categories);

    ob_start();
    ?>
    <section class="ckl-vehicle-grid ckl-shortcode-vehicle-grid vehicle-grid py-20 bg-white <?php echo esc_attr($atts['class']); ?>">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold mb-8 text-primary">
                    <?php _e('Our Vehicles', 'ckl-car-rental'); ?>
                </h2>
            </div>

            <?php if ($show_tabs && !empty($active_tabs)) : ?>
                <!-- Category Tabs -->
                <div class="flex justify-center gap-4 mb-8 flex-wrap" role="tablist" id="<?php echo esc_attr($instance_id); ?>-tabs">
                    <?php if (empty($specified_categories)) : ?>
                        <button class="vehicle-tab px-6 py-2 rounded-full text-sm font-medium transition-all bg-gray-100 text-foreground border-b-2 border-secondary"
                                data-type="all"
                                role="tab"
                                data-grid-instance="<?php echo esc_attr($instance_id); ?>">
                            <?php _e('All', 'ckl-car-rental'); ?>
                        </button>
                    <?php endif; ?>

                    <?php
                    // Tab labels mapping
                    $tab_labels = array(
                        'sedan' => __('Sedan', 'ckl-car-rental'),
                        'compact' => __('Compact', 'ckl-car-rental'),
                        'mpv' => __('MPV', 'ckl-car-rental'),
                        'luxury-mpv' => __('Luxury MPV', 'ckl-car-rental'),
                        'suv' => __('SUV', 'ckl-car-rental'),
                        '4x4' => __('4x4', 'ckl-car-rental'),
                        'motorcycle' => __('Motorcycle', 'ckl-car-rental'),
                    );

                    foreach ($active_tabs as $cat_slug) :
                        if ($cat_slug === 'all') continue;
                        ?>
                        <button class="vehicle-tab px-6 py-2 rounded-full text-sm font-medium transition-all text-foreground/60 hover:text-foreground hover:bg-gray-50"
                                data-type="<?php echo esc_attr($cat_slug); ?>"
                                role="tab"
                                data-grid-instance="<?php echo esc_attr($instance_id); ?>">
                            <?php echo isset($tab_labels[$cat_slug]) ? esc_html($tab_labels[$cat_slug]) : esc_html(ucfirst($cat_slug)); ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Vehicle Grid -->
            <div class="grid <?php echo $grid_class; ?> gap-6 max-w-6xl mx-auto" id="<?php echo esc_attr($instance_id); ?>">
                <?php if (!empty($all_vehicles)) :
                    $delay_counter = 0;
                    foreach ($all_vehicles as $post) :
                        setup_postdata($post);
                        $delay = $delay_counter * 100;
                        $delay_counter++;
                        if ($delay_counter > 2) $delay_counter = 0;

                        $vehicle_type = ckl_shortcode_get_vehicle_type_slug($post->ID);
                        ?>
                        <div class="vehicle-card group" data-type="<?php echo esc_attr($vehicle_type); ?>" data-grid-instance="<?php echo esc_attr($instance_id); ?>" style="transition-delay: <?php echo $delay; ?>ms;">
                            <?php
                            // Check if the vehicle card template part exists
                            if (locate_template('template-parts/content-vehicle-card.php')) {
                                get_template_part('template-parts/content', 'vehicle-card');
                            } else {
                                // Fallback: render a simple card
                                ?>
                                <div class="bg-white rounded-lg border shadow-sm overflow-hidden hover:shadow-lg transition-shadow">
                                    <?php if (has_post_thumbnail($post->ID)) : ?>
                                        <div class="aspect-video">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_post_thumbnail('medium', array('class' => 'w-full h-full object-cover')); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    <div class="p-4">
                                        <h3 class="font-bold text-lg mb-2">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h3>
                                        <?php if (get_post_meta($post->ID, '_daily_rate', true)) : ?>
                                            <p class="text-blue-600 font-semibold">
                                                RM<?php echo number_format(get_post_meta($post->ID, '_daily_rate', true), 0); ?>
                                                <span class="text-gray-500 text-sm font-normal">/day</span>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
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
        </div>
    </section>

    <?php if ($show_tabs) : ?>
    <script>
    (function() {
        const instanceId = '<?php echo $instance_id; ?>';

        // Find tabs and cards for this instance only
        const tabs = document.querySelectorAll('[data-grid-instance="' + instanceId + '"].vehicle-tab');
        const vehicleCards = document.querySelectorAll('[data-grid-instance="' + instanceId + '"].vehicle-card');

        if (tabs.length === 0) return;

        tabs.forEach(function(tab) {
            tab.addEventListener('click', function() {
                const type = this.getAttribute('data-type');

                // Update active tab styling
                tabs.forEach(function(t) {
                    t.classList.remove('bg-gray-100', 'text-foreground', 'border-b-2', 'border-secondary');
                    t.classList.add('text-foreground/60', 'hover:text-foreground', 'hover:bg-gray-50');
                });
                this.classList.remove('text-foreground/60', 'hover:text-foreground', 'hover:bg-gray-50');
                this.classList.add('bg-gray-100', 'text-foreground', 'border-b-2', 'border-secondary');

                // Filter vehicles for this instance only
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
    })();
    </script>

    <style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    </style>
    <?php endif; ?>
    <?php
    return ob_get_clean();
}
add_shortcode('ckl_vehicle_grid', 'ckl_vehicle_grid_shortcode');
