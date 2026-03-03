<?php
/**
 * My Account Support Section
 *
 * Displays contact options and FAQ
 *
 * @package CKL_Car_Rental
 * @version 1.0.0
 *
 * @var string $phone Support phone number
 * @var string $whatsapp WhatsApp number
 * @var string $email Support email
 */

defined('ABSPATH') || exit;

if (!isset($phone)) {
    $phone = '+60 12-345-6789';
}
if (!isset($whatsapp)) {
    $whatsapp = '+60123456789';
}
if (!isset($email)) {
    $email = 'support@cklangkawi.com';
}
?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Quick Contact -->
    <div class="lg:col-span-2 space-y-6">

        <!-- Contact Methods -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">
                <?php esc_html_e('Get in Touch', 'ckl-car-rental'); ?>
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Phone -->
                <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone)); ?>" class="flex flex-col items-center p-6 border border-gray-200 rounded-xl hover:border-[#cc2e28] hover:shadow-md transition-all">
                    <div class="w-14 h-14 bg-red-50 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-7 h-7 text-[#cc2e28]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900"><?php esc_html_e('Call Us', 'ckl-car-rental'); ?></h3>
                    <p class="text-sm text-gray-600 mt-1"><?php echo esc_html($phone); ?></p>
                </a>

                <!-- WhatsApp -->
                <a href="https://wa.me/<?php echo esc_attr(preg_replace('/[^0-9]/', '', $whatsapp)); ?>" target="_blank" class="flex flex-col items-center p-6 border border-gray-200 rounded-xl hover:border-[#25D366] hover:shadow-md transition-all">
                    <div class="w-14 h-14 bg-green-50 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-7 h-7 text-[#25D366]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900">WhatsApp</h3>
                    <p class="text-sm text-gray-600 mt-1"><?php esc_html_e('Chat with us', 'ckl-car-rental'); ?></p>
                </a>

                <!-- Email -->
                <a href="mailto:<?php echo esc_attr($email); ?>" class="flex flex-col items-center p-6 border border-gray-200 rounded-xl hover:border-[#cc2e28] hover:shadow-md transition-all">
                    <div class="w-14 h-14 bg-blue-50 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900"><?php esc_html_e('Email Us', 'ckl-car-rental'); ?></h3>
                    <p class="text-sm text-gray-600 mt-1"><?php echo esc_html($email); ?></p>
                </a>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">
                <?php esc_html_e('Send us a Message', 'ckl-car-rental'); ?>
            </h2>

            <form method="post" class="space-y-4" id="ckl-support-form">
                <?php wp_nonce_field('ckl_support_form', 'ckl_support_nonce'); ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="support_name" class="block text-sm font-medium text-gray-700 mb-1">
                            <?php esc_html_e('Your Name', 'ckl-car-rental'); ?> <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="support_name" name="support_name" required
                               value="<?php echo esc_attr(wp_get_current_user()->display_name); ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#cc2e28] focus:border-transparent">
                    </div>

                    <div>
                        <label for="support_email" class="block text-sm font-medium text-gray-700 mb-1">
                            <?php esc_html_e('Your Email', 'ckl-car-rental'); ?> <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="support_email" name="support_email" required
                               value="<?php echo esc_attr(wp_get_current_user()->user_email); ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#cc2e28] focus:border-transparent">
                    </div>
                </div>

                <div>
                    <label for="support_subject" class="block text-sm font-medium text-gray-700 mb-1">
                        <?php esc_html_e('Subject', 'ckl-car-rental'); ?> <span class="text-red-500">*</span>
                    </label>
                    <select id="support_subject" name="support_subject" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#cc2e28] focus:border-transparent">
                        <option value=""><?php esc_html_e('Select a topic', 'ckl-car-rental'); ?></option>
                        <option value="booking"><?php esc_html_e('Booking Inquiry', 'ckl-car-rental'); ?></option>
                        <option value="cancellation"><?php esc_html_e('Cancellation', 'ckl-car-rental'); ?></option>
                        <option value="payment"><?php esc_html_e('Payment Issue', 'ckl-car-rental'); ?></option>
                        <option value="vehicle"><?php esc_html_e('Vehicle Question', 'ckl-car-rental'); ?></option>
                        <option value="technical"><?php esc_html_e('Technical Issue', 'ckl-car-rental'); ?></option>
                        <option value="other"><?php esc_html_e('Other', 'ckl-car-rental'); ?></option>
                    </select>
                </div>

                <div>
                    <label for="support_message" class="block text-sm font-medium text-gray-700 mb-1">
                        <?php esc_html_e('Message', 'ckl-car-rental'); ?> <span class="text-red-500">*</span>
                    </label>
                    <textarea id="support_message" name="support_message" rows="5" required
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#cc2e28] focus:border-transparent"
                              placeholder="<?php esc_attr_e('Describe your issue or question...', 'ckl-car-rental'); ?>"></textarea>
                </div>

                <button type="submit" name="ckl_send_support" value="1"
                        class="w-full px-6 py-3 bg-[#cc2e28] text-white rounded-lg font-semibold hover:bg-[#a8241f] transition-colors">
                    <?php esc_html_e('Send Message', 'ckl-car-rental'); ?>
                </button>
            </form>
        </div>
    </div>

    <!-- FAQ -->
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-[#cc2e28]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <?php esc_html_e('FAQ', 'ckl-car-rental'); ?>
            </h2>

            <div class="space-y-3">
                <!-- FAQ Item 1 -->
                <div class="border border-gray-200 rounded-lg">
                    <button class="ckl-faq-toggle w-full px-4 py-3 text-left flex items-center justify-between">
                        <span class="font-medium text-gray-900"><?php esc_html_e('How do I cancel my booking?', 'ckl-car-rental'); ?></span>
                        <svg class="w-5 h-5 text-gray-500 transform transition-transform ckl-faq-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="ckl-faq-content hidden px-4 pb-3">
                        <p class="text-sm text-gray-600">
                            <?php esc_html_e('Go to My Account > Bookings, find the booking you want to cancel, and click the Cancel button. You can only cancel bookings that are pending or confirmed.', 'ckl-car-rental'); ?>
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 2 -->
                <div class="border border-gray-200 rounded-lg">
                    <button class="ckl-faq-toggle w-full px-4 py-3 text-left flex items-center justify-between">
                        <span class="font-medium text-gray-900"><?php esc_html_e('What is your late return policy?', 'ckl-car-rental'); ?></span>
                        <svg class="w-5 h-5 text-gray-500 transform transition-transform ckl-faq-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="ckl-faq-content hidden px-4 pb-3">
                        <p class="text-sm text-gray-600">
                            <?php esc_html_e('Late returns are charged based on our hourly rates. Additional fees may apply for returns more than 2 hours late. Please contact us if you anticipate being late.', 'ckl-car-rental'); ?>
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 3 -->
                <div class="border border-gray-200 rounded-lg">
                    <button class="ckl-faq-toggle w-full px-4 py-3 text-left flex items-center justify-between">
                        <span class="font-medium text-gray-900"><?php esc_html_e('What payment methods do you accept?', 'ckl-car-rental'); ?></span>
                        <svg class="w-5 h-5 text-gray-500 transform transition-transform ckl-faq-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="ckl-faq-content hidden px-4 pb-3">
                        <p class="text-sm text-gray-600">
                            <?php esc_html_e('We accept all major credit cards (Visa, Mastercard, American Express) and online bank transfers. A credit card is required for the security deposit.', 'ckl-car-rental'); ?>
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 4 -->
                <div class="border border-gray-200 rounded-lg">
                    <button class="ckl-faq-toggle w-full px-4 py-3 text-left flex items-center justify-between">
                        <span class="font-medium text-gray-900"><?php esc_html_e('Do I need insurance?', 'ckl-car-rental'); ?></span>
                        <svg class="w-5 h-5 text-gray-500 transform transition-transform ckl-faq-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="ckl-faq-content hidden px-4 pb-3">
                        <p class="text-sm text-gray-600">
                            <?php esc_html_e('Basic insurance is included in all rentals. Additional coverage options are available during checkout. See our Insurance Policy for details.', 'ckl-car-rental'); ?>
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 5 -->
                <div class="border border-gray-200 rounded-lg">
                    <button class="ckl-faq-toggle w-full px-4 py-3 text-left flex items-center justify-between">
                        <span class="font-medium text-gray-900"><?php esc_html_e('Can I modify my booking?', 'ckl-car-rental'); ?></span>
                        <svg class="w-5 h-5 text-gray-500 transform transition-transform ckl-faq-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="ckl-faq-content hidden px-4 pb-3">
                        <p class="text-sm text-gray-600">
                            <?php esc_html_e('Modifications are subject to availability. Please contact us at least 24 hours before your pickup time to make changes. Additional charges may apply.', 'ckl-car-rental'); ?>
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 6 -->
                <div class="border border-gray-200 rounded-lg">
                    <button class="ckl-faq-toggle w-full px-4 py-3 text-left flex items-center justify-between">
                        <span class="font-medium text-gray-900"><?php esc_html_e('What documents do I need?', 'ckl-car-rental'); ?></span>
                        <svg class="w-5 h-5 text-gray-500 transform transition-transform ckl-faq-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="ckl-faq-content hidden px-4 pb-3">
                        <p class="text-sm text-gray-600">
                            <?php esc_html_e('You\'ll need a valid driver\'s license, ID card or passport, and a credit card for the security deposit. See the Documents section for more details.', 'ckl-car-rental'); ?>
                        </p>
                    </div>
                </div>
            </div>

            <p class="text-sm text-gray-500 mt-4">
                <?php esc_html_e('Can\'t find what you\'re looking for? Contact our support team.', 'ckl-car-rental'); ?>
            </p>
        </div>
    </div>

</div>

<script>
// FAQ Toggle
document.querySelectorAll('.ckl-faq-toggle').forEach(button => {
    button.addEventListener('click', function() {
        const content = this.nextElementSibling;
        const icon = this.querySelector('.ckl-faq-icon');

        content.classList.toggle('hidden');
        icon.classList.toggle('rotate-180');
    });
});
</script>
