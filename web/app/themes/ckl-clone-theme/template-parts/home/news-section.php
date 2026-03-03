<?php
/**
 * News Section
 *
 * Displays latest blog posts
 */

$homepage_sections = get_option('ckl_homepage_sections', ckl_get_default_homepage_sections());

// Skip if disabled
if (!isset($homepage_sections['news_section']['enabled']) || !$homepage_sections['news_section']['enabled']) {
    return;
}

// Get latest blog posts
$blog_posts = get_posts(array(
    'post_type' => 'post',
    'posts_per_page' => 3,
    'post_status' => 'publish',
    'orderby' => 'date',
    'order' => 'DESC',
));
?>

<section class="news-section py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-12">
            <div>
                <h2 class="text-3xl md:text-4xl font-bold mb-2">
                    <?php _e('Latest News & Tips', 'ckl-car-rental'); ?>
                </h2>
                <p class="text-gray-600">
                    <?php _e('Discover Langkawi travel tips and updates from our team.', 'ckl-car-rental'); ?>
                </p>
            </div>
            <a href="<?php echo home_url('/blog/'); ?>"
               class="hidden md:inline-flex items-center text-blue-600 hover:text-blue-700 font-semibold">
                <?php _e('View All Posts', 'ckl-car-rental'); ?>
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
        </div>

        <?php if (!empty($blog_posts)) : ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($blog_posts as $post) : ?>
                    <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <!-- Featured Image -->
                        <?php if (has_post_thumbnail($post->ID)) : ?>
                            <a href="<?php echo get_permalink($post->ID); ?>" class="block">
                                <img src="<?php echo get_the_post_thumbnail_url($post->ID, 'medium'); ?>"
                                     alt="<?php echo esc_attr($post->post_title); ?>"
                                     class="w-full h-48 object-cover">
                            </a>
                        <?php else : ?>
                            <div class="w-full h-48 bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                                <span class="text-white text-6xl">📰</span>
                            </div>
                        <?php endif; ?>

                        <!-- Content -->
                        <div class="p-6">
                            <!-- Categories -->
                            <?php
                            $categories = get_the_category($post->ID);
                            if (!empty($categories)) :
                                ?>
                                <div class="mb-3">
                                    <span class="inline-block px-3 py-1 text-xs font-semibold bg-blue-100 text-blue-600 rounded-full">
                                        <?php echo esc_html($categories[0]->name); ?>
                                    </span>
                                </div>
                            <?php endif; ?>

                            <!-- Title -->
                            <h3 class="text-xl font-bold mb-3 line-clamp-2">
                                <a href="<?php echo get_permalink($post->ID); ?>"
                                   class="hover:text-blue-600 transition">
                                    <?php echo esc_html($post->post_title); ?>
                                </a>
                            </h3>

                            <!-- Excerpt -->
                            <p class="text-gray-600 mb-4 line-clamp-3">
                                <?php echo wp_trim_words($post->post_excerpt ?: $post->post_content, 15); ?>
                            </p>

                            <!-- Meta -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <?php echo get_the_date('', $post->ID); ?>
                                </div>
                                <a href="<?php echo get_permalink($post->ID); ?>"
                                   class="text-blue-600 hover:text-blue-700 font-medium text-sm">
                                    <?php _e('Read More', 'ckl-car-rental'); ?>
                                    <svg class="inline-block w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <!-- Mobile View All Button -->
            <div class="mt-10 text-center md:hidden">
                <a href="<?php echo home_url('/blog/'); ?>"
                   class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                    <?php _e('View All Posts', 'ckl-car-rental'); ?>
                </a>
            </div>
        <?php else : ?>
            <!-- Placeholder when no blog posts exist -->
            <div class="text-center py-16 bg-white rounded-lg border-2 border-dashed border-gray-200">
                <div class="text-6xl mb-4">📝</div>
                <h3 class="text-2xl font-bold mb-2">
                    <?php _e('No Articles Yet', 'ckl-car-rental'); ?>
                </h3>
                <p class="text-gray-600 mb-6 max-w-md mx-auto">
                    <?php _e('We\'re working on creating helpful content about Langkawi travel tips and car rental advice.', 'ckl-car-rental'); ?>
                </p>
                <a href="<?php echo home_url('/contact/'); ?>"
                   class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                    <?php _e('Contact Us', 'ckl-car-rental'); ?>
                </a>
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
