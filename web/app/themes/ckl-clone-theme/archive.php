<?php
/**
 * Archive Template for Blog Posts
 * Displays blog post listings with sidebar
 */

get_header();
?>

<!-- Hero Section -->
<section class="relative h-[400px] overflow-hidden">
    <div class="mx-auto px-4 h-full">
        <div class="relative h-full overflow-hidden">
            <!-- Background Image -->
            <img
                src="https://img.baharihari.com/insecure/q:80/f:webp/plain/s3://bahari/ck-langkawi/niels-baars-TQRnZev3OkQ-unsplash.jpg"
                alt="Blog"
                class="absolute inset-0 w-full h-full object-cover"
            />

            <!-- Dark Overlay -->
            <div class="absolute inset-0 bg-black/60"></div>

            <!-- Content -->
            <div class="relative h-full flex flex-col items-center justify-center text-white">
                <h1 class="text-5xl md:text-6xl font-bold mb-4">
                    <?php
                    if (is_category()) {
                        single_cat_title();
                    } elseif (is_tag()) {
                        single_tag_title();
                    } elseif (is_author()) {
                        printf(__('All Posts by %s', 'ckl-car-rental'), get_the_author());
                    } elseif (is_date()) {
                        if (is_day()) {
                            printf(__('Daily Archives: %s', 'ckl-car-rental'), get_the_date());
                        } elseif (is_month()) {
                            printf(__('Monthly Archives: %s', 'ckl-car-rental'), get_the_date('F Y'));
                        } elseif (is_year()) {
                            printf(__('Yearly Archives: %s', 'ckl-car-rental'), get_the_date('Y'));
                        }
                    } else {
                        _e('Blog', 'ckl-car-rental');
                    }
                    ?>
                </h1>

                <!-- Breadcrumb -->
                <div class="flex items-center gap-2 text-lg">
                    <a href="<?php echo home_url('/'); ?>" class="hover:text-primary transition-colors">
                        Home
                    </a>
                    <span class="text-primary">/</span>
                    <span class="text-primary">
                        <?php
                        if (is_category()) {
                            single_cat_title();
                        } elseif (is_tag()) {
                            single_tag_title();
                        } elseif (is_author()) {
                            echo get_the_author();
                        } elseif (is_date()) {
                            _e('Archives', 'ckl-car-rental');
                        } else {
                            _e('Blog', 'ckl-car-rental');
                        }
                        ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Blog Content -->
<section class="blog-content py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <?php if (have_posts()) : ?>
                        <div class="space-y-8">
                            <?php while (have_posts()) : the_post(); ?>
                                <article id="post-<?php the_ID(); ?>" <?php post_class('bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition'); ?>>
                                    <!-- Featured Image -->
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="relative">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_post_thumbnail('medium_large', array('class' => 'w-full h-64 object-cover')); ?>
                                            </a>
                                            <?php
                                            // Category tag
                                            $categories = get_the_category();
                                            if (!empty($categories)) :
                                                $category = $categories[0];
                                            ?>
                                                <span class="absolute top-4 left-4 bg-primary text-white text-xs px-3 py-1 rounded">
                                                    <?php echo esc_html($category->name); ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Post Content -->
                                    <div class="p-6">
                                        <!-- Post Meta -->
                                        <div class="flex items-center text-sm text-gray-500 mb-3">
                                            <span class="mr-4">
                                                <?php echo get_the_date(); ?>
                                            </span>
                                            <span>
                                                <?php _e('By', 'ckl-car-rental'); ?> <?php the_author(); ?>
                                            </span>
                                        </div>

                                        <!-- Post Title -->
                                        <h2 class="text-2xl font-bold mb-3">
                                            <a href="<?php the_permalink(); ?>" class="hover:text-primary transition">
                                                <?php the_title(); ?>
                                            </a>
                                        </h2>

                                        <!-- Post Excerpt -->
                                        <div class="text-gray-600 mb-4">
                                            <?php the_excerpt(); ?>
                                        </div>

                                        <!-- Read More -->
                                        <a href="<?php the_permalink(); ?>"
                                           class="inline-block text-primary font-semibold hover:text-primary/90 transition">
                                            <?php _e('Read More &rarr;', 'ckl-car-rental'); ?>
                                        </a>
                                    </div>
                                </article>
                            <?php endwhile; ?>
                        </div>

                        <!-- Pagination -->
                        <?php
                        the_posts_pagination(array(
                            'mid_size' => 2,
                            'prev_text' => __('&laquo; Previous', 'ckl-car-rental'),
                            'next_text' => __('Next &raquo;', 'ckl-car-rental'),
                            'screen_reader_text' => __('Posts navigation', 'ckl-car-rental'),
                        ));
                        ?>
                    <?php else : ?>
                        <!-- No Posts Found -->
                        <div class="text-center py-16 bg-white rounded-lg shadow">
                            <div class="text-6xl mb-6">📝</div>
                            <h2 class="text-2xl font-bold mb-4 text-gray-700">
                                <?php _e('No Posts Found', 'ckl-car-rental'); ?>
                            </h2>
                            <p class="text-gray-600">
                                <?php _e('Sorry, no posts matched your criteria.', 'ckl-car-rental'); ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Sidebar -->
                <aside class="lg:col-span-1">
                    <div class="space-y-6">
                        <!-- Search Widget -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-bold mb-4">
                                <?php _e('Search', 'ckl-car-rental'); ?>
                            </h3>
                            <?php get_search_form(); ?>
                        </div>

                        <!-- Categories Widget -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-bold mb-4">
                                <?php _e('Categories', 'ckl-car-rental'); ?>
                            </h3>
                            <ul class="space-y-2">
                                <?php wp_list_categories(array(
                                    'title_li' => '',
                                    'show_count' => true,
                                )); ?>
                            </ul>
                        </div>

                        <!-- Recent Posts Widget -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-bold mb-4">
                                <?php _e('Recent Posts', 'ckl-car-rental'); ?>
                            </h3>
                            <ul class="space-y-3">
                                <?php
                                $recent_posts = wp_get_recent_posts(array(
                                    'numberposts' => 5,
                                    'post_status' => 'publish'
                                ));
                                foreach ($recent_posts as $post) :
                                ?>
                                    <li>
                                        <a href="<?php echo get_permalink($post['ID']); ?>"
                                           class="text-gray-700 hover:text-primary transition">
                                            <?php echo esc_html($post['post_title']); ?>
                                        </a>
                                        <div class="text-xs text-gray-500 mt-1">
                                            <?php echo get_the_date('', $post['ID']); ?>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <!-- Archive Widget -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-bold mb-4">
                                <?php _e('Archives', 'ckl-car-rental'); ?>
                            </h3>
                            <ul class="space-y-2">
                                <?php wp_get_archives(array('type' => 'monthly')); ?>
                            </ul>
                        </div>

                        <!-- Tag Cloud Widget -->
                        <?php
                        $tags = get_tags();
                        if (!empty($tags)) :
                        ?>
                            <div class="bg-white rounded-lg shadow p-6">
                                <h3 class="text-lg font-bold mb-4">
                                    <?php _e('Tags', 'ckl-car-rental'); ?>
                                </h3>
                                <div class="flex flex-wrap gap-2">
                                    <?php
                                    $tag_cloud = wp_tag_cloud(array(
                                        'echo' => false,
                                        'smallest' => 12,
                                        'largest' => 16,
                                        'unit' => 'px',
                                    ));
                                    echo $tag_cloud;
                                    ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
