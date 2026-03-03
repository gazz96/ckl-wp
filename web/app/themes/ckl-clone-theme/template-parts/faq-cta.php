<?php
/**
 * FAQ CTA Template Part
 *
 * @package CKL_Car_Rental
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<section class="bg-blue-600 text-white py-12">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-2xl md:text-3xl font-bold mb-4">
            <?php _e('Have More Questions?', 'ckl-car-rental'); ?>
        </h2>
        <p class="text-xl text-blue-100 mb-6">
            <?php _e('Check out our frequently asked questions for quick answers.', 'ckl-car-rental'); ?>
        </p>
        <a href="<?php echo esc_url(home_url('/faq/')); ?>"
           class="inline-flex items-center bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-blue-50 transition focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-600">
            <?php _e('View FAQ', 'ckl-car-rental'); ?>
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
            </svg>
        </a>
    </div>
</section>
