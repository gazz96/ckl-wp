<?php
/**
 * Title: Section - CTA
 * Slug: ckl-cloner/section-cta
 * Categories: ckl-sections, ckl-cta
 * Block Types: core/group, core/heading, core/paragraph, core/buttons
 * Description: Call to action section with blue background and buttons
 */
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"80px","bottom":"80px","left":"20px","right":"20px"}}},"backgroundColor":"blue-600","textColor":"white","layout":{"type":"constrained"},"className":"cta-section"} -->
<div class="wp-block-group alignfull cta-section has-blue-600-background-color has-text-color has-background has-white-color" style="padding-top:80px;padding-right:20px;padding-bottom:80px;padding-left:20px">
	<!-- wp:group {"align":"wide"} -->
	<div class="wp-block-group alignwide">
		<!-- wp:heading {"level":2,"align":"center","className":"text-4xl font-bold mb-6"} -->
		<h2 class="has-text-align-center text-4xl font-bold mb-6">Ready to Explore Langkawi?</h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"align":"center","className":"text-xl mb-8"} -->
		<p class="has-text-align-center text-xl mb-8">Book your vehicle today and experience the CK Langkawi difference!</p>
		<!-- /wp:paragraph -->

		<!-- wp:buttons {"align":"center","className":"flex flex-col sm:flex-row gap-4 justify-center","layout":{"type":"flex","allowStacking":true}} -->
		<div class="wp-block-buttons aligncenter flex flex-col sm:flex-row gap-4 justify-center">
			<!-- wp:button {"backgroundColor":"white","textColor":"blue-600","className":"is-style-outline"} -->
			<div class="wp-block-button"><a class="wp-block-button__link has-white-background-color has-blue-600-color has-text-color has-background" href="/vehicles/">Browse Vehicles</a></div>
			<!-- /wp:button -->

			<!-- wp:button {"backgroundColor":"white","textColor":"blue-600","className":"is-style-outline"} -->
			<div class="wp-block-button"><a class="wp-block-button__link has-white-background-color has-blue-600-color has-text-color has-background" href="/contact/">Contact Us</a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
