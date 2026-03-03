<?php
/**
 * Contact Form Template Part
 *
 * @package CKL_Car_Rental
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<form id="contact-form" method="post" action="" class="space-y-4" novalidate>
    <?php wp_nonce_field('ckl_contact_form', 'ckl_contact_nonce'); ?>

    <!-- Name Field -->
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
            <?php _e('Name', 'ckl-car-rental'); ?> <span class="text-red-500">*</span>
        </label>
        <input type="text"
               id="name"
               name="name"
               required
               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
               placeholder="<?php _e('Your full name', 'ckl-car-rental'); ?>">
    </div>

    <!-- Email Field -->
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
            <?php _e('Email', 'ckl-car-rental'); ?> <span class="text-red-500">*</span>
        </label>
        <input type="email"
               id="email"
               name="email"
               required
               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
               placeholder="<?php _e('your@email.com', 'ckl-car-rental'); ?>">
    </div>

    <!-- Phone Field -->
    <div>
        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
            <?php _e('Phone Number', 'ckl-car-rental'); ?>
        </label>
        <input type="tel"
               id="phone"
               name="phone"
               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
               placeholder="<?php _e('+60 12-345 6789', 'ckl-car-rental'); ?>">
        <p class="text-xs text-gray-500 mt-1"><?php _e('Format: +60 XX-XXX XXXX', 'ckl-car-rental'); ?></p>
    </div>

    <!-- Subject Field -->
    <div>
        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
            <?php _e('Subject', 'ckl-car-rental'); ?> <span class="text-red-500">*</span>
        </label>
        <select id="subject"
                name="subject"
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white">
            <option value=""><?php _e('Select a subject', 'ckl-car-rental'); ?></option>
            <option value="General Question"><?php _e('General Question', 'ckl-car-rental'); ?></option>
            <option value="Booking Inquiry"><?php _e('Booking Inquiry', 'ckl-car-rental'); ?></option>
            <option value="Support"><?php _e('Support', 'ckl-car-rental'); ?></option>
            <option value="Other"><?php _e('Other', 'ckl-car-rental'); ?></option>
        </select>
    </div>

    <!-- Message Field -->
    <div>
        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
            <?php _e('Message', 'ckl-car-rental'); ?> <span class="text-red-500">*</span>
        </label>
        <textarea id="message"
                  name="message"
                  rows="6"
                  required
                  minlength="10"
                  maxlength="500"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition resize-none"
                  placeholder="<?php _e('Tell us how we can help you...', 'ckl-car-rental'); ?>"></textarea>
        <div class="flex justify-between mt-1">
            <p class="text-xs text-gray-500"><?php _e('Min: 10 characters', 'ckl-car-rental'); ?></p>
            <p class="text-xs text-gray-500">
                <span id="char-count">0</span>/500
            </p>
        </div>
    </div>

    <!-- Honeypot for spam protection -->
    <div style="display:none;">
        <label for="website"><?php _e('Leave this blank', 'ckl-car-rental'); ?></label>
        <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
    </div>

    <!-- Submit Button -->
    <button type="submit"
            class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
        </svg>
        <span><?php _e('Send Message', 'ckl-car-rental'); ?></span>
    </button>

    <p class="mt-4 text-sm text-gray-500 text-center">
        <?php _e('We\'ll get back to you within 24 hours', 'ckl-car-rental'); ?>
    </p>
</form>
