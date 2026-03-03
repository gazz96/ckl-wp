<?php
/**
 * Blog post card template part
 *
 * @package CKL_Car_Rental
 */

// Get post data
$post_id = get_the_ID();
$post_title = get_the_title();
$post_excerpt = get_the_excerpt();
$post_date = get_the_date('F j, Y');
$post_author = get_the_author();
$post_link = get_permalink();
$reading_time = ckl_get_reading_time();

// Get featured image
$featured_image = get_the_post_thumbnail_url($post_id, 'medium');
$featured_image_alt = get_post_meta(get_post_thumbnail_id($post_id), '_wp_attachment_image_alt', true);

// Get categories
$categories = get_the_category($post_id);
?>

<article class="group bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-300">
    <!-- Featured Image -->
    <?php if ($featured_image) : ?>
        <a href="<?php echo esc_url($post_link); ?>" class="block aspect-video overflow-hidden">
            <img
                src="<?php echo esc_url($featured_image); ?>"
                alt="<?php echo esc_attr($featured_image_alt); ?>"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                loading="lazy"
            >
        </a>
    <?php else : ?>
        <a href="<?php echo esc_url($post_link); ?>" class="block aspect-video overflow-hidden bg-gradient-to-br from-blue-50 to-blue-100">
            <div class="w-full h-full flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-400">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                </svg>
            </div>
        </a>
    <?php endif; ?>

    <!-- Content -->
    <div class="p-6">
        <!-- Categories -->
        <?php if (!empty($categories)) : ?>
            <div class="flex flex-wrap gap-2 mb-3">
                <?php foreach ($categories as $category) : ?>
                    <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 hover:bg-blue-200 transition-colors">
                        <?php echo esc_html($category->name); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Title -->
        <h3 class="text-xl font-bold mb-3 line-clamp-2 group-hover:text-blue-600 transition-colors">
            <a href="<?php echo esc_url($post_link); ?>">
                <?php echo esc_html($post_title); ?>
            </a>
        </h3>

        <!-- Excerpt -->
        <p class="text-gray-600 mb-4 line-clamp-3">
            <?php echo esc_html($post_excerpt); ?>
        </p>

        <!-- Meta -->
        <div class="flex items-center justify-between text-sm text-gray-500 border-t border-gray-100 pt-4">
            <div class="flex items-center gap-4">
                <!-- Date -->
                <div class="flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    <span><?php echo esc_html($post_date); ?></span>
                </div>

                <!-- Reading Time -->
                <div class="flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    <span><?php echo esc_html($reading_time); ?></span>
                </div>
            </div>

            <!-- Read More Link -->
            <a href="<?php echo esc_url($post_link); ?>" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-700 font-medium group-hover:gap-2 transition-all">
                <?php _e('Read', 'ckl-car-rental'); ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14"></path>
                    <path d="m12 5 7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>
</article>
