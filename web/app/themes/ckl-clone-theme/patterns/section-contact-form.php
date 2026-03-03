<?php
/**
 * Title: Section - Contact Form
 * Slug: ckl-cloner/section-contact-form
 * Categories: ckl-contact
 * Block Types: core/group
 * Description: Contact form section for contact page with name, email, phone, subject, and message fields
 */
?>
<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group">
	<!-- wp:heading {"level":2,"className":"text-2xl md:text-3xl font-bold text-primary mb-6"} -->
	<h2 class="text-2xl md:text-3xl font-bold text-primary mb-6">Send us a Message</h2>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"className":"text-gray-600 mb-8"} -->
	<p class="text-gray-600 mb-8">Fill out the form below and we'll respond within 24 hours.</p>
	<!-- /wp:paragraph -->

	<!-- wp:html -->
	<form id="contact-form" method="post" action="" class="space-y-4" novalidate>
		<?php wp_nonce_field('ckl_contact_form', 'ckl_contact_nonce'); ?>

		<!-- Name Field -->
		<div>
			<label for="name" class="block text-sm font-medium mb-2">
				Name <span class="text-red-500">*</span>
			</label>
			<input type="text"
				   id="name"
				   name="name"
				   required
				   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
				   placeholder="Your full name">
		</div>

		<!-- Email Field -->
		<div>
			<label for="email" class="block text-sm font-medium mb-2">
				Email <span class="text-red-500">*</span>
			</label>
			<input type="email"
				   id="email"
				   name="email"
				   required
				   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
				   placeholder="your@email.com">
		</div>

		<!-- Phone Field -->
		<div>
			<label for="phone" class="block text-sm font-medium mb-2">
				Phone Number
			</label>
			<input type="tel"
				   id="phone"
				   name="phone"
				   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
				   placeholder="+60 12-345 6789">
			<p class="text-xs text-gray-500 mt-1">Format: +60 XX-XXX XXXX</p>
		</div>

		<!-- Subject Field -->
		<div>
			<label for="subject" class="block text-sm font-medium mb-2">
				Subject <span class="text-red-500">*</span>
			</label>
			<select id="subject"
					name="subject"
					required
					class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white">
				<option value="">Select a subject</option>
				<option value="General Question">General Question</option>
				<option value="Booking Inquiry">Booking Inquiry</option>
				<option value="Support">Support</option>
				<option value="Other">Other</option>
			</select>
		</div>

		<!-- Message Field -->
		<div>
			<label for="message" class="block text-sm font-medium mb-2">
				Message <span class="text-red-500">*</span>
			</label>
			<textarea id="message"
					  name="message"
					  rows="6"
					  required
					  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition resize-none"
					  placeholder="Tell us how we can help you..."></textarea>
			<div class="flex justify-between mt-1">
				<p class="text-xs text-gray-500">Min: 10 characters</p>
				<p class="text-xs text-gray-500">
					<span id="char-count">0</span>/500
				</p>
			</div>
		</div>

		<!-- Honeypot for spam protection -->
		<div style="display:none;">
			<label for="website">Leave this blank</label>
			<input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
		</div>

		<!-- Submit Button -->
		<button type="submit"
				class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 flex items-center justify-center">
			<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
			</svg>
			Send Message
		</button>

		<p class="mt-4 text-sm text-gray-500 text-center">
			We'll get back to you within 24 hours
		</p>
	</form>
	<!-- /wp:html -->
</div>
<!-- /wp:group -->
