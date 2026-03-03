<?php
/**
 * Output a single payment method
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment-method.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.5.0
 */

if (!defined('ABSPATH')) {
	exit;
}
?>
<div class="relative">
	<input id="payment_method_<?php echo esc_attr($gateway->id); ?>" type="radio" class="input-radio sr-only" name="payment_method" value="<?php echo esc_attr($gateway->id); ?>" <?php checked($gateway->chosen, true); ?> data-order_button_text="<?php echo esc_attr($gateway->order_button_text); ?>" />

	<label for="payment_method_<?php echo esc_attr($gateway->id); ?>" class="flex items-center justify-between p-4 cursor-pointer transition-all duration-200 border-2 rounded-lg <?php echo $gateway->chosen ? 'border-blue-500 bg-blue-50' : 'border-gray-200 bg-white hover:border-gray-300'; ?>">
		<div class="flex items-center space-x-3">
			<div class="flex-shrink-0 w-5 h-5 rounded-full border-2 flex items-center justify-center transition-colors <?php echo $gateway->chosen ? 'border-blue-500' : 'border-gray-300'; ?>">
				<?php if ($gateway->chosen) : ?>
					<div class="w-3 h-3 rounded-full bg-blue-500"></div>
				<?php endif; ?>
			</div>
			<div class="flex items-center space-x-2">
				<span class="font-medium text-gray-900"><?php echo $gateway->get_title(); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></span>
				<?php echo $gateway->get_icon(); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?>
			</div>
		</div>
		<svg class="w-5 h-5 text-gray-400 transition-transform <?php echo $gateway->chosen ? 'transform rotate-180' : ''; ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
			<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
		</svg>
	</label>

	<?php if ($gateway->has_fields() || $gateway->get_description()) : ?>
		<div class="payment_box payment_method_<?php echo esc_attr($gateway->id); ?> mt-2 p-4 bg-gray-50 rounded-lg border <?php echo $gateway->chosen ? 'border-blue-200 block' : 'border-gray-200 hidden'; ?>">
			<?php $gateway->payment_fields(); ?>
		</div>
	<?php endif; ?>
</div>
