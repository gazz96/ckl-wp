<?php
/**
 * FAQ Shortcode
 *
 * Usage: [ckl_faq count="6" category="" show_view_all="true"]
 *
 * @package CKL_Car_Rental
 */

defined('ABSPATH') || exit;

/**
 * Register FAQ shortcode
 */
function ckl_faq_shortcode($atts) {
    $atts = shortcode_atts(array(
        'count'         => '6',
        'category'      => '', // FAQ category slug
        'show_view_all' => 'true',
        'class'         => '',
    ), $atts);

    // Sanitize attributes
    $count = ckl_sanitize_item_count($atts['count'], 1, 50, 6);
    $show_view_all = ckl_string_to_bool($atts['show_view_all']);

    // Build query args
    $query_args = array(
        'post_type'      => 'faq',
        'posts_per_page' => $count,
        'post_status'    => 'publish',
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    );

    // Add category filter if specified
    if (!empty($atts['category'])) {
        $query_args['tax_query'] = array(
            array(
                'taxonomy' => 'faq_category',
                'field'    => 'slug',
                'terms'    => sanitize_text_field($atts['category']),
            ),
        );
    }

    // Get FAQs
    $faqs = get_posts($query_args);

    // Generate unique ID for this instance to avoid JS conflicts
    $instance_id = 'ckl-faq-' . uniqid();

    ob_start();
    ?>
    <section class="ckl-faq ckl-shortcode-faq faq-section py-16 <?php echo esc_attr($atts['class']); ?>">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl md:text-4xl font-bold text-center mb-4">
                <?php _e('Frequently Asked Questions', 'ckl-car-rental'); ?>
            </h2>
            <p class="text-center text-gray-600 mb-12 max-w-2xl mx-auto">
                <?php _e('Find answers to common questions about our car rental services.', 'ckl-car-rental'); ?>
            </p>

            <?php if (!empty($faqs)) : ?>
                <div class="max-w-3xl mx-auto space-y-4" id="<?php echo esc_attr($instance_id); ?>">
                    <?php foreach ($faqs as $faq) : ?>
                        <div class="faq-item bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <button class="faq-question w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition"
                                    aria-expanded="false"
                                    data-faq-id="<?php echo $faq->ID; ?>"
                                    data-faq-instance="<?php echo esc_attr($instance_id); ?>">
                                <span class="font-semibold text-gray-900 pr-4">
                                    <?php echo esc_html($faq->post_title); ?>
                                </span>
                                <svg class="faq-icon w-6 h-6 text-blue-600 flex-shrink-0 transform transition-transform duration-200"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div class="faq-answer hidden px-6 pb-4">
                                <div class="text-gray-600 leading-relaxed">
                                    <?php echo wp_kses_post(wpautop($faq->post_content)); ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($show_view_all) : ?>
                    <!-- View All FAQ Link -->
                    <div class="text-center mt-10">
                        <a href="<?php echo home_url('/faq/'); ?>"
                           class="inline-block bg-white text-blue-600 border-2 border-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-blue-50 transition">
                            <?php _e('View All FAQs', 'ckl-car-rental'); ?>
                            <svg class="inline-block w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                    </div>
                <?php endif; ?>
            <?php else : ?>
                <!-- Placeholder when no FAQs exist -->
                <div class="text-center py-12 max-w-2xl mx-auto">
                    <div class="text-6xl mb-4">❓</div>
                    <h3 class="text-2xl font-bold mb-2">
                        <?php _e('No FAQs Yet', 'ckl-car-rental'); ?>
                    </h3>
                    <p class="text-gray-600 mb-6">
                        <?php _e('We\'re working on adding frequently asked questions. Check back soon!', 'ckl-car-rental'); ?>
                    </p>
                    <a href="<?php echo home_url('/contact/'); ?>"
                       class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                        <?php _e('Contact Us', 'ckl-car-rental'); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <script>
    (function() {
        const containerId = '<?php echo $instance_id; ?>';
        const container = document.getElementById(containerId);

        if (!container) return;

        const faqQuestions = container.querySelectorAll('.faq-question');

        faqQuestions.forEach(function(question) {
            question.addEventListener('click', function() {
                const isExpanded = this.getAttribute('aria-expanded') === 'true';
                const answer = this.nextElementSibling;
                const icon = this.querySelector('.faq-icon');
                const instance = this.getAttribute('data-faq-instance');

                // Close all other FAQs within this instance (accordion style)
                faqQuestions.forEach(function(q) {
                    if (q !== question && q.getAttribute('data-faq-instance') === instance) {
                        q.setAttribute('aria-expanded', 'false');
                        q.nextElementSibling.classList.add('hidden');
                        q.querySelector('.faq-icon').classList.remove('rotate-180');
                    }
                });

                // Toggle current FAQ
                this.setAttribute('aria-expanded', !isExpanded);
                answer.classList.toggle('hidden');
                icon.classList.toggle('rotate-180');
            });
        });

        // Open first FAQ by default
        if (faqQuestions.length > 0) {
            faqQuestions[0].click();
        }
    })();
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('ckl_faq', 'ckl_faq_shortcode');
