<?php
/**
 * Template Name: FAQs
 *
 * Frequently Asked Questions page template for CK Langkawi Car Rental
 */

get_header();

// FAQ Data
$faqs = array(
    'general' => array(
        'title' => __('General Booking Questions', 'ckl-car-rental'),
        'icon' => '📋',
        'questions' => array(
            array(
                'question' => __('How do I book a vehicle?', 'ckl-car-rental'),
                'answer' => __('Booking a vehicle is easy! Simply browse our available vehicles, select your preferred dates, choose the vehicle that suits your needs, and complete the booking process online. You\'ll receive a confirmation email with all the details.', 'ckl-car-rental')
            ),
            array(
                'question' => __('What documents do I need to rent a vehicle?', 'ckl-car-rental'),
                'answer' => __('You\'ll need a valid driving license (international license if not in English), passport or ID card, and a credit/debit card for the security deposit. The minimum age requirement is 21 years old for cars and 18 years old for motorcycles.', 'ckl-car-rental')
            ),
            array(
                'question' => __('Can I cancel my booking?', 'ckl-car-rental'),
                'answer' => __('Yes, you can cancel your booking. Free cancellation is available up to 48 hours before your pickup date. Cancellations made within 48 hours may incur a cancellation fee. Please refer to our Terms & Conditions for detailed information.', 'ckl-car-rental')
            ),
            array(
                'question' => __('How early should I book?', 'ckl-car-rental'),
                'answer' => __('We recommend booking as early as possible, especially during peak seasons (December-January, school holidays, and public holidays) to ensure availability and get the best rates.', 'ckl-car-rental')
            ),
            array(
                'question' => __('Do you offer airport pickup?', 'ckl-car-rental'),
                'answer' => __('Yes! We offer airport pickup and drop-off services at Langkawi International Airport. Please indicate your flight details when booking, and our team will be ready to welcome you.', 'ckl-car-rental')
            ),
        )
    ),
    'terms' => array(
        'title' => __('Rental Terms & Conditions', 'ckl-car-rental'),
        'icon' => '📝',
        'questions' => array(
            array(
                'question' => __('What are the age requirements for renting?', 'ckl-car-rental'),
                'answer' => __('For cars and MPVs, the minimum age is 21 years old with a valid license. For motorcycles, the minimum age is 18 years old. For luxury vehicles and large MPVs, the minimum age may be 25 years old. A young driver surcharge may apply for drivers under 25.', 'ckl-car-rental')
            ),
            array(
                'question' => __('What is the deposit requirement?', 'ckl-car-rental'),
                'answer' => __('A security deposit is required upon pickup. The amount varies by vehicle type: RM 200-500 for motorcycles, RM 300-800 for cars, and RM 500-1500 for MPVs and luxury vehicles. The deposit is refundable upon safe return of the vehicle.', 'ckl-car-rental')
            ),
            array(
                'question' => __('What is your fuel policy?', 'ckl-car-rental'),
                'answer' => __('Vehicles are provided with a full tank of fuel and should be returned with a full tank. If returned with less fuel, you\'ll be charged for the missing fuel plus a refueling service fee.', 'ckl-car-rental')
            ),
            array(
                'question' => __('Is there a mileage limit?', 'ckl-car-rental'),
                'answer' => __('Most of our rentals come with unlimited mileage within Langkawi island. For certain specialty vehicles or long-term rentals, mileage limits may apply. Please check the specific terms for your chosen vehicle.', 'ckl-car-rental')
            ),
            array(
                'question' => __('What happens if I return the vehicle late?', 'ckl-car-rental'),
                'answer' => __('A grace period of 30 minutes is provided. After that, late return fees may apply equivalent to the daily rental rate or hourly charges, depending on how late the vehicle is returned. Please contact us if you anticipate being late.', 'ckl-car-rental')
            ),
        )
    ),
    'payment' => array(
        'title' => __('Payment Information', 'ckl-car-rental'),
        'icon' => '💳',
        'questions' => array(
            array(
                'question' => __('What payment methods do you accept?', 'ckl-car-rental'),
                'answer' => __('We accept all major credit and debit cards (Visa, Mastercard, American Express), online bank transfers, and e-wallets (GrabPay, Touch \'n Go). Cash payments are also accepted for the security deposit and additional charges.', 'ckl-car-rental')
            ),
            array(
                'question' => __('Is payment required upfront?', 'ckl-car-rental'),
                'answer' => __('Yes, full payment is required at the time of booking to secure your reservation. This can be done securely through our online payment system. The security deposit is collected separately upon vehicle pickup.', 'ckl-car-rental')
            ),
            array(
                'question' => __('What is your refund policy?', 'ckl-car-rental'),
                'answer' => __('Refunds are processed within 5-7 business days. Free cancellations up to 48 hours before pickup receive a full refund. Cancellations within 48 hours may incur fees. Refunds are made to the original payment method.', 'ckl-car-rental')
            ),
            array(
                'question' => __('Are there any hidden fees?', 'ckl-car-rental'),
                'answer' => __('No! We believe in transparent pricing. The price you see includes basic insurance, unlimited mileage, and standard vehicle features. Optional add-ons like GPS, child seats, and additional drivers are clearly priced before you add them to your booking.', 'ckl-car-rental')
            ),
        )
    ),
    'vehicle' => array(
        'title' => __('Vehicle Information', 'ckl-car-rental'),
        'icon' => '🚗',
        'questions' => array(
            array(
                'question' => __('What insurance coverage is included?', 'ckl-car-rental'),
                'answer' => __('All rentals come with basic insurance coverage including third-party liability and collision damage waiver with excess. Comprehensive insurance options are available for additional coverage. Please review the insurance terms before booking.', 'ckl-car-rental')
            ),
            array(
                'question' => __('Can I drive to other states?', 'ckl-car-rental'),
                'answer' => __('Our vehicles are licensed for use within Langkawi island only. Taking vehicles to the mainland (Penang, Kuala Lumpur, etc.) is not permitted unless specifically arranged with prior approval. Additional charges and documentation may apply for inter-state travel.', 'ckl-car-rental')
            ),
            array(
                'question' => __('Are pets allowed in the vehicles?', 'ckl-car-rental'),
                'answer' => __('Pets are allowed in selected vehicles only. Please inform us at the time of booking if you plan to travel with a pet. A cleaning fee may apply. We recommend using pet carriers to keep the vehicle clean.', 'ckl-car-rental')
            ),
            array(
                'question' => __('Can I add an additional driver?', 'ckl-car-rental'),
                'answer' => __('Yes, additional drivers can be added for a small daily fee. All additional drivers must present valid documentation at pickup and be named on the rental agreement. There\'s no fee for spouse drivers.', 'ckl-car-rental')
            ),
            array(
                'question' => __('What if the vehicle breaks down?', 'ckl-car-rental'),
                'answer' => __('In the unlikely event of a breakdown, contact our 24/7 emergency hotline immediately. We provide roadside assistance and will arrange a replacement vehicle if necessary. Do not attempt to repair the vehicle yourself.', 'ckl-car-rental')
            ),
        )
    ),
);
?>

<!-- Hero Section -->
<section class="hero-section relative bg-gradient-to-r from-blue-600 to-blue-800 text-white py-20">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-5xl font-bold mb-6">
                <?php _e('Frequently Asked Questions', 'ckl-car-rental'); ?>
            </h1>
            <p class="text-xl">
                <?php _e('Find answers to common questions about our services', 'ckl-car-rental'); ?>
            </p>
        </div>
    </div>
</section>

<!-- FAQ Search -->
<section class="faq-search py-8 bg-white border-b">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto">
            <div class="relative">
                <input type="text"
                       id="faq-search"
                       placeholder="<?php _e('Search for answers...', 'ckl-car-rental'); ?>"
                       class="w-full px-6 py-4 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:outline-none text-lg">
                <svg class="absolute right-4 top-1/2 transform -translate-y-1/2 w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Categories -->
<section class="faq-content py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <?php foreach ($faqs as $category_key => $category) : ?>
                <div class="faq-category mb-12" data-category="<?php echo esc_attr($category_key); ?>">
                    <!-- Category Header -->
                    <div class="flex items-center mb-6">
                        <div class="text-4xl mr-4"><?php echo $category['icon']; ?></div>
                        <h2 class="text-2xl font-bold text-primary">
                            <?php echo esc_html($category['title']); ?>
                        </h2>
                    </div>

                    <!-- FAQ Items -->
                    <div class="space-y-4">
                        <?php foreach ($category['questions'] as $index => $qa) : ?>
                            <div class="faq-item bg-white rounded-lg shadow-md overflow-hidden" data-index="<?php echo $index; ?>">
                                <button class="faq-question w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition">
                                    <span class="font-semibold text-lg text-gray-800 pr-4">
                                        <?php echo esc_html($qa['question']); ?>
                                    </span>
                                    <span class="faq-icon flex-shrink-0 w-8 h-8 bg-accent/10 rounded-full flex items-center justify-center text-primary">
                                        <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </span>
                                </button>
                                <div class="faq-answer hidden px-6 pb-4">
                                    <div class="pt-2 text-gray-700 leading-relaxed">
                                        <?php echo esc_html($qa['answer']); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Still Have Questions -->
<section class="cta-section py-20 bg-primary text-white text-center">
    <div class="container mx-auto px-4">
        <h2 class="text-4xl font-bold mb-6">
            <?php _e('Still Have Questions?', 'ckl-car-rental'); ?>
        </h2>
        <p class="text-xl mb-8 max-w-2xl mx-auto">
            <?php _e('Our friendly team is here to help! Contact us and we\'ll get back to you as soon as possible.', 'ckl-car-rental'); ?>
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="<?php echo home_url('/contact/'); ?>"
               class="inline-block bg-white text-primary px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                <?php _e('Contact Us', 'ckl-car-rental'); ?>
            </a>
            <a href="mailto:contact@cklangkawi.com"
               class="inline-block bg-transparent border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-primary transition">
                <?php _e('Email Us', 'ckl-car-rental'); ?>
            </a>
        </div>
    </div>
</section>

<script>
// FAQ Accordion Functionality
document.addEventListener('DOMContentLoaded', function() {
    // Handle FAQ accordion
    const faqItems = document.querySelectorAll('.faq-item');

    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        const answer = item.querySelector('.faq-answer');
        const icon = item.querySelector('.faq-icon svg');

        question.addEventListener('click', () => {
            const isOpen = !answer.classList.contains('hidden');

            // Close all other items
            faqItems.forEach(otherItem => {
                const otherAnswer = otherItem.querySelector('.faq-answer');
                const otherIcon = otherItem.querySelector('.faq-icon svg');
                otherAnswer.classList.add('hidden');
                otherIcon.style.transform = 'rotate(0deg)';
            });

            // Toggle current item
            if (!isOpen) {
                answer.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
            }
        });
    });

    // FAQ Search Functionality
    const searchInput = document.getElementById('faq-search');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            const faqItems = document.querySelectorAll('.faq-item');
            const categories = document.querySelectorAll('.faq-category');

            if (searchTerm === '') {
                // Show all
                faqItems.forEach(item => {
                    item.style.display = 'block';
                });
                categories.forEach(category => {
                    category.style.display = 'block';
                });
                return;
            }

            // Hide all categories first
            categories.forEach(category => {
                category.style.display = 'none';
            });

            // Search and show matching items
            faqItems.forEach(item => {
                const question = item.querySelector('.faq-question span').textContent.toLowerCase();
                const answer = item.querySelector('.faq-answer').textContent.toLowerCase();
                const category = item.closest('.faq-category');

                if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                    item.style.display = 'block';
                    category.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });

            // Hide empty categories
            categories.forEach(category => {
                const visibleItems = category.querySelectorAll('.faq-item:not([style*="display: none"])');
                if (visibleItems.length === 0) {
                    category.style.display = 'none';
                }
            });
        });
    }

    // Open FAQ from URL hash
    if (window.location.hash) {
        const hash = window.location.hash.substring(1);
        const targetElement = document.querySelector(`[data-category="${hash}"]`);
        if (targetElement) {
            targetElement.scrollIntoView({ behavior: 'smooth' });
        }
    }
});
</script>

<?php get_footer(); ?>
