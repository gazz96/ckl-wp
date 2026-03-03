<?php
/**
 * FAQ Section
 *
 * Displays frequently asked questions in an accordion format
 */

$homepage_sections = get_option('ckl_homepage_sections', ckl_get_default_homepage_sections());

// Skip if disabled
if (!isset($homepage_sections['faq']['enabled']) || !$homepage_sections['faq']['enabled']) {
    return;
}

// Get FAQ posts
$faqs = get_posts(array(
    'post_type' => 'faq',
    'posts_per_page' => 6,
    'post_status' => 'publish',
    'orderby' => 'menu_order',
    'order' => 'ASC',
));
?>

<section class="faq-section py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-4">
            <?php _e('Frequently Asked Questions', 'ckl-car-rental'); ?>
        </h2>
        <p class="text-center text-gray-600 mb-12 max-w-2xl mx-auto">
            <?php _e('Find answers to common questions about our car rental services.', 'ckl-car-rental'); ?>
        </p>

        <?php if (!empty($faqs)) : ?>
            <div class="max-w-3xl mx-auto space-y-4">
                <?php foreach ($faqs as $index => $faq) : ?>
                    <div class="faq-item bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <button class="faq-question w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition"
                                aria-expanded="false"
                                data-faq-id="<?php echo $faq->ID; ?>">
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
document.addEventListener('DOMContentLoaded', function() {
    const faqQuestions = document.querySelectorAll('.faq-question');

    faqQuestions.forEach(function(question) {
        question.addEventListener('click', function() {
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            const answer = this.nextElementSibling;
            const icon = this.querySelector('.faq-icon');

            // Close all other FAQs (accordion style)
            faqQuestions.forEach(function(q) {
                if (q !== question) {
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
});
</script>
