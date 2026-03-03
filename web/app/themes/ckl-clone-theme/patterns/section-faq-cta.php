<?php
/**
 * Title: Section - FAQ CTA
 * Slug: ckl-cloner/section-faq-cta
 * Categories: ckl-contact
 * Block Types: core/group
 * Description: FAQ call-to-action section for contact page
 */
?>
<!-- wp:group {"align":"full","backgroundColor":"blue-600","textColor":"white","style":{"spacing":{"padding":{"top":"3rem","bottom":"3rem","left":"1.5rem","right":"1.5rem"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-blue-600-background-color has-text-color has-background has-white-color" style="padding-top:3rem;padding-right:1.5rem;padding-bottom:3rem;padding-left:1.5rem">
	<!-- wp:group {"align":"wide","layout":{"type":"constrained","justify":"center"},"className":"text-center"} -->
	<div class="wp-block-group alignwide text-center">
		<!-- wp:heading {"level":2,"className":"text-2xl md:text-3xl font-bold mb-4"} -->
		<h2 class="text-2xl md:text-3xl font-bold mb-4">Have More Questions?</h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"className":"text-xl text-blue-100 mb-6"} -->
		<p class="text-xl text-blue-100 mb-6">Check out our frequently asked questions for quick answers.</p>
		<!-- /wp:paragraph -->

		<!-- wp:html -->
		<a href="/faq/"
		   class="inline-flex items-center bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-blue-50 transition focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-600">
			View FAQ
			<svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
			</svg>
		</a>
		<!-- /wp:html -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
