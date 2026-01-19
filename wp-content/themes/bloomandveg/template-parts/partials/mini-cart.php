<?php

if (!defined('ABSPATH')) {
	exit;
}

if (!function_exists('WC')) {
	return;
}

$cart_url = function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/');
$checkout_url = function_exists('wc_get_checkout_url') ? wc_get_checkout_url() : home_url('/');

$cart_count = 0;
if (null !== WC()->cart) {
	$cart_count = (int) WC()->cart->get_cart_contents_count();
}
?>

<div class="bv-mini-cart" data-bv-mini-cart>
	<a class="bv-cart-link" href="<?php echo esc_url($cart_url); ?>" aria-label="<?php esc_attr_e('View cart', 'bloomandveg'); ?>">
		<span class="bv-cart-icon" aria-hidden="true">
			<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<path d="M6 6h15l-1.5 9h-12z" />
				<path d="M6 6l-2-3H1" />
				<circle cx="9" cy="20" r="1" />
				<circle cx="18" cy="20" r="1" />
			</svg>
		</span>
		<span class="bv-cart-label"><?php esc_html_e('Cart', 'bloomandveg'); ?></span>
		<span class="bv-cart-count" data-bv-cart-count><?php echo esc_html((string) $cart_count); ?></span>
	</a>

	<button class="bv-cart-toggle" type="button" aria-expanded="false" aria-controls="bv-mini-cart-panel">
		<?php esc_html_e('Open cart', 'bloomandveg'); ?>
	</button>

	<div id="bv-mini-cart-panel" class="bv-mini-cart-panel" hidden>
		<div class="bv-mini-cart-panel__header">
			<strong><?php esc_html_e('Your basket', 'bloomandveg'); ?></strong>
			<button class="bv-mini-cart-close" type="button" aria-label="<?php esc_attr_e('Close cart', 'bloomandveg'); ?>">×</button>
		</div>

		<div class="bv-mini-cart-panel__body" data-bv-mini-cart-body>
			<?php
			woocommerce_mini_cart();
			?>
		</div>

		<div class="bv-mini-cart-panel__footer">
			<a class="button" href="<?php echo esc_url($cart_url); ?>">
				<?php esc_html_e('View cart', 'bloomandveg'); ?>
			</a>
			<a class="button button-primary" href="<?php echo esc_url($checkout_url); ?>">
				<?php esc_html_e('Checkout', 'bloomandveg'); ?>
			</a>
		</div>
	</div>
</div>
