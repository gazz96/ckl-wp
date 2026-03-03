<?php
/**
 * Title: Page Hero - How To Book
 * Slug: ckl-cloner/page-hero-how-to-book
 * Categories: ckl-hero
 * Block Types: core/group, core/heading, core/paragraph
 * Description: Hero section for How To Book page with gradient accent background
 */
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"80px","bottom":"80px","left":"20px","right":"20px"}}},"backgroundColor":"accent","textColor":"white","className":"hero-section how-to-book-hero"} -->
<div class="wp-block-group alignfull hero-section how-to-book-hero bg-accent text-white" style="padding-top:80px;padding-right:20px;padding-bottom:80px;padding-left:20px">
	<!-- wp:group {"align":"wide","layout":{"type":"constrained"},"className":"text-center"} -->
	<div class="wp-block-group alignwide text-center">
		<!-- wp:html -->
		<div class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 hover:bg-primary/80 mb-4 bg-white/20 text-white border-white/30">
			Simple & Secure Process
		</div>
		<!-- /wp:html -->

		<!-- wp:heading {"level":1,"align":"center","className":"text-5xl md:text-6xl font-bold mb-6"} -->
		<h1 class="has-text-align-center text-5xl md:text-6xl font-bold mb-6">How To Book</h1>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"align":"center","className":"text-xl text-white/90 mb-8"} -->
		<p class="has-text-align-center text-xl text-white/90 mb-8">Rent a car in Langkawi in 4 easy steps. From browsing to driving, we've made it simple, secure, and seamless.</p>
		<!-- /wp:paragraph -->

		<!-- wp:html -->
		<div class="flex flex-col sm:flex-row gap-4 justify-center">
			<a href="/auth/">
				<button class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-secondary text-secondary-foreground hover:bg-secondary/80 h-11 rounded-md px-8 gap-2">
					Sign Up
				</button>
			</a>
		</div>
		<!-- /wp:html -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
