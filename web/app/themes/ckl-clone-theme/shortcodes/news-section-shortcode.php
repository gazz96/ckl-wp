<?php
/**
 * News Section Shortcode
 *
 * Usage: [ckl_news count="3" columns="3" category="" show_excerpt="true"]
 *
 * @package CKL_Car_Rental
 */

defined('ABSPATH') || exit;

/**
 * Register news section shortcode
 */
function ckl_news_shortcode($atts) {
    $atts = shortcode_atts(array(
        'count'         => '3',
        'columns'       => '3',
        'category'      => '', // Category slug
        'show_excerpt'  => 'true',
        'class'         => '',
    ), $atts);

    // Sanitize attributes
    $count = ckl_sanitize_item_count($atts['count'], 1, 12, 3);
    $columns = ckl_sanitize_column_count($atts['columns'], 1, 4, 3);
    $show_excerpt = ckl_string_to_bool($atts['show_excerpt']);

    // Build query args
    $query_args = array(
        'post_type'      => 'post',
        'posts_per_page' => $count,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
    );

    // Add category filter if specified
    if (!empty($atts['category'])) {
        $query_args['category_name'] = sanitize_text_field($atts['category']);
    }

    // Get blog posts
    $blog_posts = get_posts($query_args);

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
    <section class="ckl-news ckl-shortcode-news news-section py-20 bg-white <?php echo esc_attr($atts['class']); ?>">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-primary">
                    <?php _e('News & Promotions', 'ckl-car-rental'); ?>
                </h2>
            </div>

            <?php if (!empty($blog_posts)) : ?>
                <div class="grid <?php echo $grid_class; ?> gap-6">
                    <?php foreach ($blog_posts as $post) : ?>
                        <article class="rounded-lg border bg-card text-card-foreground shadow-sm overflow-hidden hover:shadow-lg transition-all duration-300 flex flex-col h-full">
                            <!-- Featured Image -->
                            <?php if (has_post_thumbnail($post->ID)) : ?>
                                <a href="<?php echo get_permalink($post->ID); ?>" class="block relative aspect-video">
                                    <?php echo get_the_post_thumbnail($post->ID, 'medium', array('class' => 'w-full h-full object-cover')); ?>
                                </a>
                            <?php else : ?>
                                <div class="w-full aspect-video bg-gradient-to-br from-secondary/20 to-secondary/10 flex items-center justify-center">
                                    <span class="text-6xl">📰</span>
                                </div>
                            <?php endif; ?>

                            <!-- Content -->
                            <div class="p-5 flex flex-col flex-1">
                                <!-- Categories -->
                                <?php
                                $categories = get_the_category($post->ID);
                                if (!empty($categories)) :
                                    ?>
                                    <div class="mb-3">
                                        <span class="inline-block px-3 py-1 text-xs font-semibold bg-secondary/10 text-secondary rounded-full">
                                            <?php echo esc_html($categories[0]->name); ?>
                                        </span>
                                    </div>
                                <?php endif; ?>

                                <!-- Title -->
                                <h3 class="text-lg font-bold mb-3 line-clamp-2">
                                    <a href="<?php echo get_permalink($post->ID); ?>"
                                       class="hover:text-primary transition">
                                        <?php echo esc_html($post->post_title); ?>
                                    </a>
                                </h3>

                                <!-- Excerpt -->
                                <?php if ($show_excerpt) : ?>
                                    <p class="text-muted-foreground mb-4 line-clamp-3 text-sm flex-1">
                                        <?php echo wp_trim_words($post->post_excerpt ?: $post->post_content, 15); ?>
                                    </p>
                                <?php endif; ?>

                                <!-- Meta -->
                                <div class="flex items-center justify-between pt-4 border-t border-border mt-auto">
                                    <div class="flex items-center text-sm text-muted-foreground">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <?php echo get_the_date('', $post->ID); ?>
                                    </div>
                                    <a href="<?php echo get_permalink($post->ID); ?>"
                                       class="text-primary hover:text-primary/80 font-medium text-sm inline-flex items-center">
                                        <?php _e('Read', 'ckl-car-rental'); ?>
                                        <svg class="inline-block w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <!-- Placeholder when no blog posts exist -->
                <div class="text-center py-16">
                    <div class="max-w-md mx-auto">
                        <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-muted flex items-center justify-center">
                            <svg class="w-12 h-12 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold mb-3">
                            <?php _e('No Articles Yet', 'ckl-car-rental'); ?>
                        </h3>
                        <p class="text-muted-foreground mb-6">
                            <?php _e('We\'re working on bringing you great content about car rentals, travel tips, and Langkawi attractions. Check back soon!', 'ckl-car-rental'); ?>
                        </p>
                        <a class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 border bg-background h-10 px-4 py-2 border-primary text-primary hover:bg-primary hover:text-primary-foreground"
                           href="<?php echo home_url('/blog/'); ?>">
                            <?php _e('Browse All News', 'ckl-car-rental'); ?>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2 h-4 w-4">
                                <path d="M5 12h14"></path>
                                <path d="m12 5 7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    </style>
    <?php
    return ob_get_clean();
}
add_shortcode('ckl_news', 'ckl_news_shortcode');
