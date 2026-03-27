<?php
/**
 * Template Name: Blog
 *
 * Dynamic blog page with search and filtering
 */

get_header();

// Get search query
$search_query = get_search_query();

// Get current page number
$paged = get_query_var('paged') ? get_query_var('paged') : 1;

// Query blog posts
$blog_args = array(
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => 12,
    'paged' => $paged,
    's' => $search_query,
);

$blog_query = new WP_Query($blog_args);
?>

<main class="pt-20">
    <!-- Hero Section -->
    <section class="relative h-[400px] overflow-hidden">
        <div class="mx-auto px-4 h-full">
            <div class="relative h-full overflow-hidden">
                <!-- Background Image -->
                <?php if (file_exists(get_template_directory() . '/assets/images/blog-hero.jpg')) : ?>
                    <img
                        src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/blog-hero.jpg"
                        alt="<?php esc_attr_e('Blog', 'ckl-car-rental'); ?>"
                        class="absolute inset-0 w-full h-full object-cover"
                    />
                <?php else : ?>
                    <!-- Fallback to external URL -->
                    <img
                        src="https://storage.baharihari.com/bahari/ck-langkawi/niels-baars-TQRnZev3OkQ-unsplash.jpg"
                        alt="<?php esc_attr_e('Blog', 'ckl-car-rental'); ?>"
                        class="absolute inset-0 w-full h-full object-cover"
                    />
                <?php endif; ?>

                <!-- Dark Overlay -->
                <div class="absolute inset-0 bg-black/60"></div>

                <!-- Content -->
                <div class="relative h-full flex flex-col items-center justify-center text-white">
                    <h1 class="text-5xl md:text-6xl font-bold mb-4">
                        <?php _e('Blog', 'ckl-car-rental'); ?>
                    </h1>

                    <!-- Breadcrumb -->
                    <div class="flex items-center gap-2 text-lg">
                        <a class="hover:text-primary transition-colors" href="<?php echo esc_url(home_url('/')); ?>">
                            <?php _e('Home', 'ckl-car-rental'); ?>
                        </a>
                        <span class="text-primary">/</span>
                        <span class="text-primary"><?php _e('Blog', 'ckl-car-rental'); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Search Section -->
    <section class="container mx-auto px-4 py-12">
        <div class="rounded-lg border bg-card text-card-foreground shadow-lg">
            <div class="p-6">
                <div class="space-y-4">
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400" aria-hidden="true">
                            <path d="m21 21-4.34-4.34"></path>
                            <circle cx="11" cy="11" r="8"></circle>
                        </svg>
                        <input
                            type="search"
                            class="flex h-10 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 md:text-sm pl-10"
                            placeholder="<?php esc_attr_e('Search blog posts...', 'ckl-car-rental'); ?>"
                            value="<?php echo esc_attr($search_query); ?>"
                            id="blog-search"
                            name="s"
                        >
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Blog Posts Section -->
    <section class="container mx-auto px-4 pb-16">
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="p-6">
                <?php if ($blog_query->have_posts()) : ?>
                    <!-- Blog Posts Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="blog-posts-grid">
                        <?php
                        while ($blog_query->have_posts()) :
                            $blog_query->the_post();
                            get_template_part('template-parts/content', 'blog-card');
                        endwhile;
                        wp_reset_postdata();
                        ?>
                    </div>

                    <!-- Pagination -->
                    <?php
                    $total_pages = $blog_query->max_num_pages;
                    if ($total_pages > 1) :
                    ?>
                        <div class="mt-8 flex justify-center">
                            <nav class="flex items-center gap-2">
                                <?php
                                $big = 999999999;
                                $pagination_args = array(
                                    'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                                    'format' => '?paged=%#%',
                                    'current' => max(1, $paged),
                                    'total' => $total_pages,
                                    'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>',
                                    'next_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>',
                                    'type' => 'plain',
                                    'before_page_number' => '<span class="inline-flex items-center justify-center w-10 h-10 rounded-md border border-gray-300">',
                                    'after_page_number' => '</span>',
                                );

                                echo paginate_links($pagination_args);
                                ?>
                            </nav>
                        </div>
                    <?php endif; ?>

                <?php else : ?>
                    <!-- No posts found -->
                    <div class="py-20 text-center">
                        <div class="text-6xl mb-4">📝</div>
                        <h3 class="text-lg font-semibold mb-2">
                            <?php _e('No blog posts found', 'ckl-car-rental'); ?>
                        </h3>
                        <p class="text-gray-600">
                            <?php _e('Check back soon for new content', 'ckl-car-rental'); ?>
                        </p>
                        <?php if (!empty($search_query)) : ?>
                            <a href="<?php echo esc_url(get_post_type_archive_link('post')); ?>" class="inline-block mt-4 text-blue-600 hover:text-blue-700">
                                <?php _e('View all blog posts', 'ckl-car-rental'); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="bg-gray-50 py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-2xl mx-auto text-center">
                <h2 class="text-3xl font-bold mb-4">
                    <?php _e('Stay Updated', 'ckl-car-rental'); ?>
                </h2>
                <p class="text-gray-600 mb-6">
                    <?php _e('Subscribe to our newsletter for travel tips, rental guides, and special offers.', 'ckl-car-rental'); ?>
                </p>
                <form class="flex flex-col sm:flex-row gap-3 justify-center">
                    <input
                        type="email"
                        placeholder="<?php esc_attr_e('Enter your email', 'ckl-car-rental'); ?>"
                        class="flex-1 max-w-md px-4 py-3 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    >
                    <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        <?php _e('Subscribe', 'ckl-car-rental'); ?>
                    </button>
                </form>
            </div>
        </div>
    </section>
</main>

<script>
// Simple search functionality
document.getElementById('blog-search').addEventListener('keyup', function(e) {
    if (e.key === 'Enter') {
        window.location.href = '<?php echo esc_url(home_url('/blog/')); ?>?s=' + encodeURIComponent(this.value);
    }
});
</script>

<?php get_footer(); ?>
