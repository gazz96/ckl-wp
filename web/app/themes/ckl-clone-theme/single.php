<?php
/**
 * Single Post Template for Blog Posts
 * Displays full blog post with comments and related posts
 */

get_header();
?>

<?php while (have_posts()) : the_post(); ?>

    <!-- Hero Section -->
    <section class="hero-section relative bg-gradient-to-r from-blue-600 to-blue-800 text-white py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <!-- Category -->
                <?php
                $categories = get_the_category();
                if (!empty($categories)) :
                    $category = $categories[0];
                ?>
                    <div class="mb-4">
                        <a href="<?php echo get_category_link($category->term_id); ?>"
                           class="inline-block bg-white bg-opacity-20 text-white text-sm px-4 py-1 rounded hover:bg-opacity-30 transition">
                            <?php echo esc_html($category->name); ?>
                        </a>
                    </div>
                <?php endif; ?>

                <!-- Post Title -->
                <h1 class="text-4xl md:text-5xl font-bold mb-6">
                    <?php the_title(); ?>
                </h1>

                <!-- Post Meta -->
                <div class="flex flex-wrap items-center gap-4 text-white opacity-90">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3">
                            <?php echo get_avatar(get_the_author_meta('ID'), 40); ?>
                        </div>
                        <div>
                            <div class="font-semibold">
                                <?php the_author(); ?>
                            </div>
                            <div class="text-sm opacity-80">
                                <?php _e('Author', 'ckl-car-rental'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="w-px h-8 bg-white bg-opacity-30 hidden md:block"></div>

                    <div>
                        <div class="font-semibold">
                            <?php echo get_the_date(); ?>
                        </div>
                        <div class="text-sm opacity-80">
                            <?php _e('Published', 'ckl-car-rental'); ?>
                        </div>
                    </div>

                    <?php
                    $reading_time = ceil(str_word_count(get_the_content()) / 200);
                    ?>
                    <div class="w-px h-8 bg-white bg-opacity-30 hidden md:block"></div>

                    <div>
                        <div class="font-semibold">
                            <?php printf(__('%d min read', 'ckl-car-rental'), $reading_time); ?>
                        </div>
                        <div class="text-sm opacity-80">
                            <?php _e('Reading Time', 'ckl-car-rental'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Image -->
    <?php if (has_post_thumbnail()) : ?>
        <section class="featured-image">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto -mt-8 relative z-10">
                    <div class="rounded-lg shadow-2xl overflow-hidden">
                        <?php the_post_thumbnail('large', array('class' => 'w-full')); ?>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Post Content -->
    <section class="post-content py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-lg shadow-lg p-8 md:p-12">
                    <div class="prose prose-lg max-w-none">
                        <?php
                        the_content();

                        wp_link_pages(array(
                            'before' => '<div class="page-links">' . __('Pages:', 'ckl-car-rental'),
                            'after' => '</div>',
                        ));
                        ?>
                    </div>

                    <!-- Tags -->
                    <?php
                    $tags = get_the_tags();
                    if (!empty($tags)) :
                    ?>
                        <div class="mt-8 pt-8 border-t">
                            <div class="flex items-center flex-wrap gap-2">
                                <span class="font-semibold text-gray-700"><?php _e('Tags:', 'ckl-car-rental'); ?></span>
                                <?php foreach ($tags as $tag) : ?>
                                    <a href="<?php echo get_tag_link($tag->term_id); ?>"
                                       class="bg-gray-100 text-gray-700 px-3 py-1 rounded hover:bg-gray-200 transition text-sm">
                                        <?php echo esc_html($tag->name); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Share Buttons -->
                    <div class="mt-8 pt-8 border-t">
                        <h3 class="font-semibold text-gray-700 mb-4">
                            <?php _e('Share this post:', 'ckl-car-rental'); ?>
                        </h3>
                        <div class="flex gap-3">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="bg-primary text-white px-4 py-2 rounded hover:bg-primary/90 transition">
                                Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="bg-sky-500 text-white px-4 py-2 rounded hover:bg-sky-600 transition">
                                Twitter
                            </a>
                            <a href="https://wa.me/?text=<?php echo urlencode(get_the_title() . ' ' . get_permalink()); ?>"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                                WhatsApp
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Author Bio -->
                <div class="mt-8 bg-accent/10 rounded-lg p-8">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mr-6">
                            <?php echo get_avatar(get_the_author_meta('ID'), 100, '', '', array('class' => 'w-24 h-24 rounded-full')); ?>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold mb-2">
                                <?php _e('About the Author', 'ckl-car-rental'); ?>
                            </h3>
                            <h4 class="font-semibold text-lg text-primary mb-3">
                                <?php the_author(); ?>
                            </h4>
                            <div class="text-gray-700">
                                <?php echo wp_kses_post(get_the_author_meta('description')); ?>
                            </div>
                            <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"
                               class="inline-block mt-4 text-primary hover:underline font-semibold">
                                <?php _e('View all posts by', 'ckl-car-rental'); ?> <?php the_author(); ?> &rarr;
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Related Posts -->
                <?php
                $related_args = array(
                    'category__in' => wp_get_post_categories($post->ID),
                    'post__not_in' => array($post->ID),
                    'posts_per_page' => 3,
                    'post_status' => 'publish',
                );

                $related_posts = new WP_Query($related_args);

                if ($related_posts->have_posts()) :
                ?>
                    <div class="mt-12">
                        <h3 class="text-2xl font-bold mb-6">
                            <?php _e('Related Posts', 'ckl-car-rental'); ?>
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <?php while ($related_posts->have_posts()) : $related_posts->the_post(); ?>
                                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('medium', array('class' => 'w-full h-48 object-cover')); ?>
                                        </a>
                                    <?php endif; ?>
                                    <div class="p-4">
                                        <h4 class="font-bold mb-2">
                                            <a href="<?php the_permalink(); ?>" class="hover:text-primary transition">
                                                <?php the_title(); ?>
                                            </a>
                                        </h4>
                                        <div class="text-sm text-gray-500">
                                            <?php echo get_the_date(); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                    <?php wp_reset_postdata(); ?>
                <?php endif; ?>

                <!-- Comments -->
                <?php if (comments_open() || get_comments_number()) : ?>
                    <div class="mt-12 bg-white rounded-lg shadow-lg p-8">
                        <?php comments_template(); ?>
                    </div>
                <?php endif; ?>

                <!-- Post Navigation -->
                <div class="mt-12 flex justify-between">
                    <div class="flex-1">
                        <?php
                        $prev_post = get_previous_post();
                        if (!empty($prev_post)) :
                        ?>
                            <a href="<?php echo get_permalink($prev_post->ID); ?>"
                               class="flex items-center text-primary hover:text-primary/90 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                <div>
                                    <div class="text-sm text-gray-500"><?php _e('Previous Post', 'ckl-car-rental'); ?></div>
                                    <div class="font-semibold"><?php echo esc_html($prev_post->post_title); ?></div>
                                </div>
                            </a>
                        <?php endif; ?>
                    </div>

                    <div class="flex-1 text-right">
                        <?php
                        $next_post = get_next_post();
                        if (!empty($next_post)) :
                        ?>
                            <a href="<?php echo get_permalink($next_post->ID); ?>"
                               class="flex items-center justify-end text-primary hover:text-primary/90 transition">
                                <div>
                                    <div class="text-sm text-gray-500"><?php _e('Next Post', 'ckl-car-rental'); ?></div>
                                    <div class="font-semibold"><?php echo esc_html($next_post->post_title); ?></div>
                                </div>
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php endwhile; ?>

<?php get_footer(); ?>
