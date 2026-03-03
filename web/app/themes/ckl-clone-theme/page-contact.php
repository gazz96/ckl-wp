<?php
/**
 * Template Name: Contact Us
 * Hybrid Gutenberg + PHP approach
 *
 * @package CKL_Car_Rental
 */

get_header();

// Check for form submission status
$user_id = get_current_user_id();
$success_message = get_transient('ckl_contact_success_' . $user_id);
$error_message = get_transient('ckl_contact_error_' . $user_id);

// Clear transients after display
if ($success_message) {
    delete_transient('ckl_contact_success_' . $user_id);
}
if ($error_message) {
    delete_transient('ckl_contact_error_' . $user_id);
}
?>

<main class="pt-20">
    <!-- Hero Section - Editable in Gutenberg -->
    <?php
    if (have_posts()) {
        while (have_posts()) {
            the_post();
            the_content();
        }
    }
    ?>

    <!-- Main Contact Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">

                <?php if ($success_message) : ?>
                    <div class="mb-8 bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-lg flex items-center" role="alert">
                        <svg class="w-6 h-6 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="font-semibold">
                            <?php _e('Thank you! Your message has been sent successfully. We will respond within 24 hours.', 'ckl-car-rental'); ?>
                        </p>
                    </div>
                <?php endif; ?>

                <?php if ($error_message) : ?>
                    <div class="mb-8 bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-lg flex items-center" role="alert">
                        <svg class="w-6 h-6 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="font-semibold">
                            <?php _e('Error sending message. Please try again or contact us directly via email or phone.', 'ckl-car-rental'); ?>
                        </p>
                    </div>
                <?php endif; ?>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    <!-- Left Column: Contact Form -->
                    <div class="lg:col-span-1">
                        <h2 class="text-2xl md:text-3xl font-bold text-blue-600 mb-6">
                            <?php _e('Send us a Message', 'ckl-car-rental'); ?>
                        </h2>
                        <p class="text-gray-600 mb-8">
                            <?php _e('Fill out the form below and we\'ll respond within 24 hours.', 'ckl-car-rental'); ?>
                        </p>
                        <?php get_template_part('template-parts/contact-form'); ?>
                    </div>

                    <!-- Right Column: Contact Info -->
                    <div class="lg:col-span-1">
                        <h2 class="text-2xl md:text-3xl font-bold text-blue-600 mb-6">
                            <?php _e('Get in Touch', 'ckl-car-rental'); ?>
                        </h2>
                        <p class="text-gray-600 mb-8">
                            <?php _e('You can also reach us through the following channels.', 'ckl-car-rental'); ?>
                        </p>

                        <?php get_template_part('template-parts/contact-info-cards'); ?>

                        <div class="mt-10">
                            <h3 class="text-lg font-semibold mb-4">
                                <?php _e('Follow Us', 'ckl-car-rental'); ?>
                            </h3>
                            <?php get_template_part('template-parts/social-links'); ?>
                        </div>

                        <div class="mt-10">
                            <h3 class="text-lg font-semibold mb-4">
                                <?php _e('Our Location', 'ckl-car-rental'); ?>
                            </h3>
                            <?php get_template_part('template-parts/google-maps'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ CTA Section -->
    <?php get_template_part('template-parts/faq-cta'); ?>
</main>

<script>
// Inline form validation
document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contact-form');

    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            // Honeypot check
            const honeypot = document.getElementById('website');
            if (honeypot && honeypot.value !== '') {
                e.preventDefault();
                return false;
            }
            return true;
        });
    }
});
</script>

<?php get_footer(); ?>
