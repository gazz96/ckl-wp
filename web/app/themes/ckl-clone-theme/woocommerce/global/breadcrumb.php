<?php
/**
 * Shop breadcrumb
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/breadcrumb.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     2.3.0
 * @see         woocommerce_breadcrumb()
 */

if (!defined('ABSPATH')) {
	exit;
}

if (!empty($breadcrumb)) {
	?>
	<nav class="ckl-woocommerce-breadcrumb" aria-label="<?php esc_attr_e('Breadcrumb', 'woocommerce'); ?>">
		<div class="ckl-breadcrumb-wrapper max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
			<ol class="flex items-center space-x-2 text-sm overflow-x-auto">
				<?php
				foreach ($breadcrumb as $key => $crumb) {
					$is_last = (sizeof($breadcrumb) === $key + 1);
					?>
					<li class="flex items-center">
						<?php if (!empty($crumb[1]) && !$is_last) : ?>
							<a href="<?php echo esc_url($crumb[1]); ?>"
							   class="flex items-center text-gray-600 hover:text-blue-600 transition-colors duration-200 font-medium">
								<?php if ($key === 0) : ?>
									<svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
									</svg>
								<?php endif; ?>
								<span><?php echo esc_html($crumb[0]); ?></span>
							</a>
						<?php else : ?>
							<span class="flex items-center text-gray-900 font-semibold">
								<?php if ($key === 0) : ?>
									<svg class="w-4 h-4 mr-1 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
									</svg>
								<?php endif; ?>
								<span><?php echo esc_html($crumb[0]); ?></span>
							</span>
						<?php endif; ?>
					</li>
					<?php if (!$is_last) : ?>
						<li class="flex items-center text-gray-400">
							<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
							</svg>
						</li>
					<?php endif; ?>
				<?php } ?>
			</ol>
		</div>
	</nav>
	<?php
}
