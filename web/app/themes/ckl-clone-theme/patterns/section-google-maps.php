<?php
/**
 * Title: Section - Google Maps
 * Slug: ckl-cloner/section-google-maps
 * Categories: ckl-contact
 * Block Types: core/group
 * Description: Google Maps embed section for contact page
 */
?>
<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group">
	<!-- wp:heading {"level":3,"className":"text-lg font-semibold mb-4"} -->
	<h3 class="text-lg font-semibold mb-4">Our Location</h3>
	<!-- /wp:heading -->

	<!-- wp:html -->
	<div class="relative rounded-lg overflow-hidden shadow-lg">
		<div class="h-96 w-full grayscale hover:grayscale-0 transition-all duration-500">
			<iframe
				src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3982.123456789!2d99.8456789!3d6.3123456!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x304c3fcc1234567%3A0x123456789abcdef!2sMasjid+Al-Aman+Yooi!5e0!3m2!1sen!2smy!4v1234567890123"
				width="100%"
				height="100%"
				style="border:0;"
				allowfullscreen=""
				loading="lazy"
				referrerpolicy="no-referrer-when-downgrade"
				title="CK Langkawi Location - Masjid Al-Aman Yooi">
			</iframe>
		</div>
		<!-- Location Badge -->
		<div class="absolute bottom-4 right-4 bg-white px-4 py-2 rounded-lg shadow-md">
			<a href="https://maps.google.com/?q=Masjid+Al-Aman+Yooi+Langkawi"
			   target="_blank"
			   rel="noopener noreferrer"
			   class="flex items-center text-blue-600 hover:text-blue-700 transition text-sm font-semibold">
				<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
				</svg>
				Open in Google Maps
			</a>
		</div>
	</div>
	<!-- /wp:html -->
</div>
<!-- /wp:group -->
