<?php
/**
 * Template Name: Contact Us
 * Modern contact form with React-style design
 *
 * @package CKL_Car_Rental
 */

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ckl_contact_form'])) {
    // Verify nonce
    if (!isset($_POST['ckl_contact_nonce']) || !wp_verify_nonce($_POST['ckl_contact_nonce'], 'ckl_contact_form_action')) {
        wp_die('Security check failed');
    }

    // Honeypot check for spam
    if (!empty($_POST['website'])) {
        // Silent fail for bots
        wp_redirect(home_url('/contact'));
        exit;
    }

    // Sanitize and validate input
    $name = sanitize_text_field($_POST['name'] ?? '');
    $email = sanitize_email($_POST['email'] ?? '');
    $phone = sanitize_text_field($_POST['phone'] ?? '');
    $subject = sanitize_text_field($_POST['subject'] ?? '');
    $message = sanitize_textarea_field($_POST['message'] ?? '');

    // Validation
    $errors = array();
    if (empty($name)) {
        $errors[] = 'Name is required';
    }
    if (empty($email) || !is_email($email)) {
        $errors[] = 'Valid email is required';
    }
    if (empty($subject)) {
        $errors[] = 'Subject is required';
    }
    if (empty($message) || strlen($message) < 10) {
        $errors[] = 'Message must be at least 10 characters long';
    }

    if (empty($errors)) {
        // Send email
        $to = get_option('admin_email');
        $subject_line = "New Contact Form Submission from {$name}: {$subject}";
        
        $email_body = "Name: {$name}\n";
        $email_body .= "Email: {$email}\n";
        if (!empty($phone)) {
            $email_body .= "Phone: {$phone}\n";
        }
        $email_body .= "Subject: {$subject}\n\n";
        $email_body .= "Message:\n{$message}";

        $headers = array(
            'Content-Type: text/plain; charset=UTF-8',
            'From: ' . $name . ' <' . $email . '>',
            'Reply-To: ' . $email
        );

        $sent = wp_mail($to, $subject_line, $email_body, $headers);

        if ($sent) {
            // Set success message
            set_transient('ckl_contact_success_' . get_current_user_id(), 'Message sent successfully!', 60);
        } else {
            // Set error message
            set_transient('ckl_contact_error_' . get_current_user_id(), 'Failed to send message. Please try again.', 60);
        }
    } else {
        // Set validation error
        set_transient('ckl_contact_error_' . get_current_user_id(), implode('<br>', $errors), 60);
    }

    // Redirect to prevent form resubmission
    wp_redirect(home_url('/contact'));
    exit;
}

get_header();

// Get messages from transients
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

// Contact Info Data
$contact_info = array(
    array(
        'title' => 'Phone',
        'content' => '+60 194 428 040',
        'href' => 'tel:+60194428040',
        'icon' => 'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z'
    ),
    array(
        'title' => 'Email',
        'content' => 'cklangkawi@gmail.com',
        'href' => 'mailto:cklangkawi@gmail.com',
        'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'
    ),
    array(
        'title' => 'Address',
        'content' => 'Lot Kedai No 3, Masjid Al-Aman Yooi, Jalan Padang Matsirat, 07000 Langkawi, Kedah, Malaysia',
        'href' => 'https://maps.google.com/?q=Masjid+Al-Aman+Yooi+Langkawi',
        'icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z'
    ),
    array(
        'title' => 'Business Hours',
        'content' => 'Open 24 Hours',
        'href' => null,
        'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'
    )
);

// Social Links Data
$social_links = array(
    array(
        'name' => 'Facebook',
        'href' => 'https://facebook.com/cklangkawi',
        'icon' => 'M18.77,7.46H14.5v-1.9c0-.9.6-1.1,1-1.1h3V.5h-4.33C10.24.5,9.5,3.44,9.5,5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4Z',
        'hover_color' => 'hover:bg-blue-600'
    ),
    array(
        'name' => 'Instagram',
        'href' => 'https://instagram.com/cklangkawi',
        'icon' => 'M12,2.16c3.2,0,3.58,0,4.85.07a6.65,6.65,0,0,1,2.23.41,4,4,0,0,1,2.28,2.28,6.65,6.65,0,0,1,.41,2.23c.06,1.27.07,1.65.07,4.85s0,3.58-.07,4.85a6.65,6.65,0,0,1-.41,2.23,4,4,0,0,1-2.28,2.28,6.65,6.65,0,0,1-2.23.41c-1.27.06-1.65.07-4.85.07s-3.58,0-4.85-.07a6.65,6.65,0,0,1-2.23-.41,4,4,0,0,1-2.28-2.28,6.65,6.65,0,0,1-.41-2.23C2.17,15.58,2.16,15.2,2.16,12s0-3.58.07-4.85a6.65,6.65,0,0,1,.41-2.23A4,4,0,0,1,4.92,2.64a6.65,6.65,0,0,1,2.23-.41C8.42,2.17,8.8,2.16,12,2.16M12,0C8.74,0,8.33,0,7.05.07A8.81,8.81,0,0,0,4.14.63,6.08,6.08,0,0,0,.63,4.14,8.81,8.81,0,0,0,.07,7.05C0,8.33,0,8.74,0,12s0,3.67.07,4.95a8.81,8.81,0,0,0,.56,2.91,6.08,6.08,0,0,0,3.51,3.51,8.81,8.81,0,0,0,2.91.56C8.33,24,8.74,24,12,24s3.67,0,4.95-.07a8.81,8.81,0,0,0,2.91-.56,6.08,6.08,0,0,0,3.51-3.51,8.81,8.81,0,0,0,.56-2.91C24,15.67,24,15.26,24,12s0-3.67-.07-4.95a8.81,8.81,0,0,0-.56-2.91,6.08,6.08,0,0,0-3.51-3.51A8.81,8.81,0,0,0,16.95.07C15.67,0,15.26,0,12,0Z M12,5.84A6.16,6.16,0,1,0,18.16,12,6.16,6.16,0,0,0,12,5.84ZM12,16a4,4,0,1,1,4-4A4,4,0,0,1,12,16Z M18.41,5.59A1.44,1.44,0,1,1,19.85,4.15,1.44,1.44,0,0,1,18.41,5.59Z',
        'hover_color' => 'hover:bg-pink-600'
    ),
    array(
        'name' => 'WhatsApp',
        'href' => 'https://wa.me/60194428040',
        'icon' => 'M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z',
        'hover_color' => 'hover:bg-green-600'
    )
);
?>

<main class="pt-20">
    <!-- Hero Section -->
    <section class="bg-accent py-16 md:py-24">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-5xl md:text-6xl font-bold text-white mb-4">
                Contact Us
            </h1>
            <p class="text-lg text-white/90 max-w-2xl mx-auto">
                Have questions about renting a car in Langkawi? We're here to help!
                Reach out to us and our team will get back to you as soon as possible.
            </p>
        </div>
    </section>

    <!-- Contact Content -->
    <section class="py-16 md:py-24">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 max-w-6xl mx-auto">
                
                <!-- Contact Form -->
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-primary mb-6">
                        Send us a Message
                    </h2>
                    <p class="text-muted-foreground mb-8" style="color: #6b7280;">
                        Fill out the form below and we'll respond within 24 hours.
                    </p>

                    <?php if ($success_message): ?>
                        <div class="mb-8 bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-lg flex items-center" role="alert">
                            <svg class="w-6 h-6 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <p class="font-semibold"><?php echo esc_html($success_message); ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if ($error_message): ?>
                        <div class="mb-8 bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-lg flex items-center" role="alert">
                            <svg class="w-6 h-6 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            <p class="font-semibold"><?php echo wp_kses_post($error_message); ?></p>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="" id="contact-form" class="space-y-6">
                        <?php wp_nonce_field('ckl_contact_form_action', 'ckl_contact_nonce'); ?>
                        <input type="hidden" name="ckl_contact_form" value="1">
                        
                        <!-- Honeypot field for spam -->
                        <input type="text" name="website" id="website" style="display:none;" tabindex="-1" autocomplete="off">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="name" class="block text-sm font-medium mb-2">
                                    Your Name *
                                </label>
                                <input
                                    id="name"
                                    name="name"
                                    type="text"
                                    placeholder="John Doe"
                                    required
                                    class="w-full h-12 px-4 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium mb-2">
                                    Email Address *
                                </label>
                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    placeholder="john@example.com"
                                    required
                                    class="w-full h-12 px-4 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="phone" class="block text-sm font-medium mb-2">
                                    Phone Number
                                </label>
                                <input
                                    id="phone"
                                    name="phone"
                                    type="tel"
                                    placeholder="+60 12-345 6789"
                                    class="w-full h-12 px-4 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                            </div>
                            <div>
                                <label for="subject" class="block text-sm font-medium mb-2">
                                    Subject *
                                </label>
                                <input
                                    id="subject"
                                    name="subject"
                                    type="text"
                                    placeholder="Booking inquiry"
                                    required
                                    class="w-full h-12 px-4 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                            </div>
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium mb-2">
                                Your Message *
                            </label>
                            <textarea
                                id="message"
                                name="message"
                                placeholder="How can we help you?"
                                required
                                rows="6"
                                class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                            ></textarea>
                            <div class="mt-2 text-sm text-gray-500">
                                Characters: <span id="char-count">0</span> / 500
                            </div>
                        </div>

                        <button
                            type="submit"
                            id="submit-btn"
                            class="w-full md:w-auto bg-secondary hover:bg-secondary/90 text-white h-12 px-8 rounded-md font-medium transition-colors flex items-center justify-center"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            <span id="submit-text">Send Message</span>
                        </button>
                    </form>
                </div>

                <!-- Contact Information -->
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-primary mb-6">
                        Get in Touch
                    </h2>
                    <p class="text-muted-foreground mb-8" style="color: #6b7280;">
                        You can also reach us through the following channels.
                    </p>

                    <div class="space-y-4 mb-10">
                        <?php foreach ($contact_info as $item): ?>
                            <?php if ($item['href']): ?>
                                <a href="<?php echo esc_url($item['href']); ?>" 
                                   class="block hover:shadow-md transition-shadow"
                                   <?php echo (strpos($item['href'], 'http') === 0) ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
                                    <div class="bg-white border border-gray-200 rounded-lg p-4 flex items-start gap-4">
                                        <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo esc_attr($item['icon']); ?>"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-gray-900"><?php echo esc_html($item['title']); ?></h3>
                                            <p class="text-sm text-gray-600"><?php echo esc_html($item['content']); ?></p>
                                        </div>
                                    </div>
                                </a>
                            <?php else: ?>
                                <div class="bg-white border border-gray-200 rounded-lg p-4 flex items-start gap-4 hover:shadow-md transition-shadow">
                                    <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo esc_attr($item['icon']); ?>"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-900"><?php echo esc_html($item['title']); ?></h3>
                                        <p class="text-sm text-gray-600"><?php echo esc_html($item['content']); ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>

                    <!-- Social Media -->
                    <div class="mb-10">
                        <h3 class="text-lg font-semibold mb-4">Follow Us</h3>
                        <div class="flex gap-3">
                            <?php foreach ($social_links as $social): ?>
                                <a
                                    href="<?php echo esc_url($social['href']); ?>"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="w-12 h-12 rounded-full bg-primary/10 text-primary flex items-center justify-center transition-all hover:text-white <?php echo esc_attr($social['hover_color']); ?>"
                                    aria-label="<?php echo esc_attr($social['name']); ?>"
                                >
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="<?php echo esc_attr($social['icon']); ?>"></path>
                                    </svg>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Google Maps -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Our Location</h3>
                        <div class="rounded-lg overflow-hidden border border-gray-200 h-64">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3969.7777777777777!2d99.72861111111111!3d6.328888888888889!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x304c6c2a7b4f7a4b%3A0x7a8f7a8f7a8f7a8f!2sLangkawi%20International%20Airport!5e0!3m2!1sen!2smy!4v1234567890123!5m2!1sen!2smy"
                                width="100%"
                                height="100%"
                                style="border: 0;"
                                allowfullscreen
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                                title="CK Langkawi Location"
                            ></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ CTA -->
    <section class="bg-gray-50 py-12">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-2xl font-bold mb-4">Have More Questions?</h2>
            <p class="text-gray-600 mb-6">
                Check out our frequently asked questions for quick answers.
            </p>
            <a
                href="<?php echo home_url('/faq'); ?>"
                class="inline-block px-6 py-3 border-2 border-primary text-primary rounded-md font-medium transition-colors hover:bg-primary hover:text-white"
            >
                View FAQ
            </a>
        </div>
    </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Character counter for message
    const messageInput = document.getElementById('message');
    const charCount = document.getElementById('char-count');

    if (messageInput && charCount) {
        messageInput.addEventListener('input', function() {
            const count = this.value.length;
            charCount.textContent = count;

            if (count > 500) {
                this.value = this.value.slice(0, 500);
                charCount.textContent = 500;
            }

            if (count > 500 || count < 10) {
                charCount.classList.add('text-red-500');
            } else {
                charCount.classList.remove('text-red-500');
            }
        });

        // Initialize counter
        charCount.textContent = messageInput.value.length;
    }

    // Form submission loading state
    const contactForm = document.getElementById('contact-form');
    const submitBtn = document.getElementById('submit-btn');
    const submitText = document.getElementById('submit-text');
    
    if (contactForm && submitBtn && submitText) {
        contactForm.addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitText.textContent = 'Sending...';
        });
    }

    // Real-time email validation
    const emailInput = document.getElementById('email');
    if (emailInput) {
        emailInput.addEventListener('blur', function() {
            const email = this.value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (email && !emailRegex.test(email)) {
                this.classList.add('border-red-500');
            } else {
                this.classList.remove('border-red-500');
            }
        });

        emailInput.addEventListener('input', function() {
            if (this.classList.contains('border-red-500')) {
                this.classList.remove('border-red-500');
            }
        });
    }

    // Phone number formatting (Malaysian format: +60 XX-XXX XXXX)
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');

            // Add +60 prefix if not present
            if (value.length > 0 && !value.startsWith('60')) {
                value = '60' + value;
            }

            // Format: +60 XX-XXX XXXX
            if (value.length > 2) {
                value = '+' + value;
            }
            if (value.length > 5) {
                value = value.slice(0, 5) + ' ' + value.slice(5);
            }
            if (value.length > 9) {
                value = value.slice(0, 9) + ' ' + value.slice(9);
            }
            if (value.length > 13) {
                value = value.slice(0, 13);
            }

            e.target.value = value;
        });
    }
});
</script>

<?php get_footer(); ?>
