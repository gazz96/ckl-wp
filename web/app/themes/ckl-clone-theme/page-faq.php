<?php
/**
 * Template Name: FAQ
 * FAQ page template with dynamic FAQ custom post type
 *
 * @package CKL_Car_Rental
 */

get_header();

// Get all FAQ categories
$faq_categories = get_terms(array(
    'taxonomy'   => 'faq_category',
    'hide_empty' => true,
    'orderby'    => 'name',
    'order'      => 'ASC',
));

// Query all FAQs
$faq_query = new WP_Query(array(
    'post_type'      => 'faq',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order title',
    'order'          => 'ASC',
    'post_status'    => 'publish',
));
?>

<main class="pt-20">
    <!-- Hero Section -->
    <section class="bg-accent py-20">
        <div class="container mx-auto px-4">
            <div class="flex flex-col items-center justify-center text-white">
                <h1 class="text-5xl md:text-6xl font-bold mb-4">
                    <?php _e('FAQ', 'ckl-car-rental'); ?>
                </h1>
                <p class="text-lg text-white/80 mb-4 text-center max-w-2xl">
                    <?php _e('Find answers to commonly asked questions about our car rental services', 'ckl-car-rental'); ?>
                </p>
                <div class="flex items-center gap-2 text-lg">
                    <a class="hover:text-primary transition-colors" href="/">
                        <?php _e('Home', 'ckl-car-rental'); ?>
                    </a>
                    <span class="text-primary">/</span>
                    <span class="text-primary"><?php _e('FAQ', 'ckl-car-rental'); ?></span>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Content Section -->
    <section class="py-16 bg-background">
        <div class="container mx-auto px-4">

            <!-- Category Filter Buttons -->
            <div class="flex flex-wrap justify-center gap-2 mb-10">
                <button class="faq-filter-btn inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 h-9 rounded-md px-3 bg-accent hover:bg-accent/90 text-white"
                        data-category="all">
                    <?php _e('All', 'ckl-car-rental'); ?>
                </button>

                <?php if (!empty($faq_categories) && !is_wp_error($faq_categories)) : ?>
                    <?php foreach ($faq_categories as $category) : ?>
                        <button class="faq-filter-btn inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 rounded-md px-3"
                                data-category="<?php echo esc_attr($category->slug); ?>">
                            <?php echo esc_html($category->name); ?>
                        </button>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- FAQ Accordion -->
            <div class="max-w-4xl mx-auto">
                <div class="space-y-3" data-orientation="vertical">

                    <?php if ($faq_query->have_posts()) : ?>
                        <?php while ($faq_query->have_posts()) : $faq_query->the_post(); ?>

                            <?php
                            // Get FAQ categories for this item
                            $categories = get_the_terms(get_the_ID(), 'faq_category');
                            $category_slugs = array();
                            if ($categories && !is_wp_error($categories)) {
                                foreach ($categories as $cat) {
                                    $category_slugs[] = $cat->slug;
                                }
                            }
                            ?>

                            <div class="faq-item border border-border rounded-lg overflow-hidden bg-card"
                                 data-categories="<?php echo esc_attr(implode(',', $category_slugs)); ?>">
                                <h3 data-orientation="vertical">
                                    <button type="button"
                                            class="faq-trigger flex flex-1 items-center justify-between text-left font-semibold text-base hover:no-underline px-6 py-5 hover:bg-muted/50 transition-colors w-full"
                                            aria-expanded="false">
                                        <div class="flex items-start gap-3">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 width="24"
                                                 height="24"
                                                 viewBox="0 0 24 24"
                                                 fill="none"
                                                 stroke="currentColor"
                                                 stroke-width="2"
                                                 stroke-linecap="round"
                                                 stroke-linejoin="round"
                                                 class="w-5 h-5 text-accent mt-0.5 flex-shrink-0"
                                                 aria-hidden="true">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                                                <path d="M12 17h.01"></path>
                                            </svg>
                                            <span><?php the_title(); ?></span>
                                        </div>
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             width="24"
                                             height="24"
                                             viewBox="0 0 24 24"
                                             fill="none"
                                             stroke="currentColor"
                                             stroke-width="2"
                                             stroke-linecap="round"
                                             stroke-linejoin="round"
                                             class="faq-icon h-4 w-4 shrink-0 transition-transform duration-200"
                                             aria-hidden="true">
                                            <path d="m6 9 6 6 6-6"></path>
                                        </svg>
                                    </button>
                                </h3>
                                <div class="faq-content overflow-hidden text-sm transition-all"
                                     role="region"
                                     aria-hidden="true">
                                    <div class="px-6 pb-5 pl-14">
                                        <?php the_content(); ?>
                                    </div>
                                </div>
                            </div>

                        <?php endwhile; ?>
                        <?php wp_reset_postdata(); ?>
                    <?php else : ?>

                        <div class="text-center py-12">
                            <div class="text-6xl mb-4">❓</div>
                            <h2 class="text-2xl font-bold mb-2">
                                <?php _e('No FAQs found', 'ckl-car-rental'); ?>
                            </h2>
                            <p class="text-gray-600 mb-4">
                                <?php _e('Please check back later or contact us for more information.', 'ckl-car-rental'); ?>
                            </p>
                        </div>

                    <?php endif; ?>

                </div>
            </div>

            <!-- CTA Section -->
            <div class="max-w-4xl mx-auto mt-16">
                <div class="bg-accent/5 border border-accent/20 rounded-2xl p-8 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         width="24"
                         height="24"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2"
                         stroke-linecap="round"
                         stroke-linejoin="round"
                         class="w-12 h-12 text-accent mx-auto mb-4"
                         aria-hidden="true">
                        <path d="M2.992 16.342a2 2 0 0 1 .094 1.167l-1.065 3.29a1 1 0 0 0 1.236 1.168l3.413-.998a2 2 0 0 1 1.099.092 10 10 0 1 0-4.777-4.719"></path>
                    </svg>
                    <h3 class="text-2xl font-bold mb-2">
                        <?php _e('Still have questions?', 'ckl-car-rental'); ?>
                    </h3>
                    <p class="text-muted-foreground mb-6">
                        <?php _e("Can't find the answer you're looking for? Please contact our friendly team.", 'ckl-car-rental'); ?>
                    </p>
                    <a class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 h-10 px-4 py-2 bg-accent hover:bg-accent/90 text-white"
                       href="<?php echo esc_url(home_url('/contact/')); ?>">
                        <?php _e('Contact Us', 'ckl-car-rental'); ?>
                    </a>
                </div>
            </div>

        </div>
    </section>
</main>

<?php get_footer(); ?>
