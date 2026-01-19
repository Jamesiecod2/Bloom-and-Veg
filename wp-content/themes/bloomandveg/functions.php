<?php

declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

define('BV_THEME_VERSION', '0.1.3');

define('BV_THEME_DIR', get_template_directory());
define('BV_THEME_URI', get_template_directory_uri());

add_action('after_setup_theme', static function (): void {
	load_theme_textdomain('bloomandveg', BV_THEME_DIR . '/languages');

	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
	add_theme_support('responsive-embeds');
	add_theme_support('custom-logo', [
		'height' => 96,
		'width' => 320,
		'flex-height' => true,
		'flex-width' => true,
	]);

	add_theme_support('html5', [
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
		'search-form',
	]);

	register_nav_menus([
		'primary' => __('Primary Menu', 'bloomandveg'),
		'footer' => __('Footer Menu', 'bloomandveg'),
	]);

	// WooCommerce support.
	add_theme_support('woocommerce');
	add_theme_support('wc-product-gallery-zoom');
	add_theme_support('wc-product-gallery-lightbox');
	add_theme_support('wc-product-gallery-slider');
});

add_action('init', static function (): void {
	if (!function_exists('register_block_pattern') || !function_exists('register_block_pattern_category')) {
		return;
	}

	register_block_pattern_category('bloomandveg', [
		'label' => __('Bloom & Veg', 'bloomandveg'),
	]);

	register_block_pattern('bloomandveg/home-hero', [
		'title' => __('Home hero (editable)', 'bloomandveg'),
		'categories' => ['bloomandveg'],
		'content' => implode("\n", [
			'<!-- wp:group {"className":"bv-home-hero-block","layout":{"type":"constrained"}} -->',
			'<div class="wp-block-group bv-home-hero-block">',
			'<!-- wp:heading {"textAlign":"center","level":1} -->',
			'<h1 class="wp-block-heading has-text-align-center">Bloom &amp; Veg</h1>',
			'<!-- /wp:heading -->',
			'<!-- wp:paragraph {"align":"center"} -->',
			'<p class="has-text-align-center">Homepage image (upload later). Add an Image block here (or set a background image).</p>',
			'<!-- /wp:paragraph -->',
			'<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->',
			'<div class="wp-block-buttons">',
			'<!-- wp:button -->',
			'<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="/shop/">Shop now</a></div>',
			'<!-- /wp:button -->',
			'</div>',
			'<!-- /wp:buttons -->',
			'</div>',
			'<!-- /wp:group -->',
			'<!-- wp:heading {"level":2} -->',
			'<h2 class="wp-block-heading">Home</h2>',
			'<!-- /wp:heading -->',
			'<!-- wp:paragraph -->',
			'<p>Welcome To Bloom And Veg Online Shop</p>',
			'<!-- /wp:paragraph -->',
		]),
	]);

	register_block_pattern('bloomandveg/featured-collections', [
		'title' => __('Featured collections', 'bloomandveg'),
		'categories' => ['bloomandveg'],
		'content' => implode("\n", [
			'<!-- wp:group {"className":"bv-featured-collections","layout":{"type":"constrained"}} -->',
			'<div class="wp-block-group bv-featured-collections">',
			'<!-- wp:heading {"level":2,"className":"bv-section-title"} -->',
			'<h2 class="wp-block-heading bv-section-title">Featured collections</h2>',
			'<!-- /wp:heading -->',
			'<!-- wp:group {"className":"bv-collection-grid","layout":{"type":"flex","flexWrap":"wrap"}} -->',
			'<div class="wp-block-group bv-collection-grid">',
			'<!-- wp:group {"className":"bv-collection-card","layout":{"type":"constrained"}} -->',
			'<div class="wp-block-group bv-collection-card">',
			'<!-- wp:cover {"overlayColor":"transparent","minHeight":260,"minHeightUnit":"px","isDark":false,"className":"bv-collection-media"} -->',
			'<div class="wp-block-cover is-light bv-collection-media" style="min-height:260px"><span aria-hidden="true" class="wp-block-cover__background has-transparent-background-color has-background-dim-0 has-background-dim"></span><div class="wp-block-cover__inner-container">',
			'<!-- wp:paragraph {"align":"center"} -->',
			'<p class="has-text-align-center">Upload image</p>',
			'<!-- /wp:paragraph -->',
			'</div></div>',
			'<!-- /wp:cover -->',
			'<!-- wp:paragraph {"className":"bv-collection-label"} -->',
			'<p class="bv-collection-label"><a href="#">FRUIT</a></p>',
			'<!-- /wp:paragraph -->',
			'</div>',
			'<!-- /wp:group -->',
			'<!-- wp:group {"className":"bv-collection-card","layout":{"type":"constrained"}} -->',
			'<div class="wp-block-group bv-collection-card">',
			'<!-- wp:cover {"overlayColor":"transparent","minHeight":260,"minHeightUnit":"px","isDark":false,"className":"bv-collection-media"} -->',
			'<div class="wp-block-cover is-light bv-collection-media" style="min-height:260px"><span aria-hidden="true" class="wp-block-cover__background has-transparent-background-color has-background-dim-0 has-background-dim"></span><div class="wp-block-cover__inner-container">',
			'<!-- wp:paragraph {"align":"center"} -->',
			'<p class="has-text-align-center">Upload image</p>',
			'<!-- /wp:paragraph -->',
			'</div></div>',
			'<!-- /wp:cover -->',
			'<!-- wp:paragraph {"className":"bv-collection-label"} -->',
			'<p class="bv-collection-label"><a href="#">VEGETABLES</a></p>',
			'<!-- /wp:paragraph -->',
			'</div>',
			'<!-- /wp:group -->',
			'<!-- wp:group {"className":"bv-collection-card","layout":{"type":"constrained"}} -->',
			'<div class="wp-block-group bv-collection-card">',
			'<!-- wp:cover {"overlayColor":"transparent","minHeight":260,"minHeightUnit":"px","isDark":false,"className":"bv-collection-media"} -->',
			'<div class="wp-block-cover is-light bv-collection-media" style="min-height:260px"><span aria-hidden="true" class="wp-block-cover__background has-transparent-background-color has-background-dim-0 has-background-dim"></span><div class="wp-block-cover__inner-container">',
			'<!-- wp:paragraph {"align":"center"} -->',
			'<p class="has-text-align-center">Upload image</p>',
			'<!-- /wp:paragraph -->',
			'</div></div>',
			'<!-- /wp:cover -->',
			'<!-- wp:paragraph {"className":"bv-collection-label"} -->',
			'<p class="bv-collection-label"><a href="#">FRESH BERRIES</a></p>',
			'<!-- /wp:paragraph -->',
			'</div>',
			'<!-- /wp:group -->',
			'<!-- wp:group {"className":"bv-collection-card","layout":{"type":"constrained"}} -->',
			'<div class="wp-block-group bv-collection-card">',
			'<!-- wp:cover {"overlayColor":"transparent","minHeight":260,"minHeightUnit":"px","isDark":false,"className":"bv-collection-media"} -->',
			'<div class="wp-block-cover is-light bv-collection-media" style="min-height:260px"><span aria-hidden="true" class="wp-block-cover__background has-transparent-background-color has-background-dim-0 has-background-dim"></span><div class="wp-block-cover__inner-container">',
			'<!-- wp:paragraph {"align":"center"} -->',
			'<p class="has-text-align-center">Upload image</p>',
			'<!-- /wp:paragraph -->',
			'</div></div>',
			'<!-- /wp:cover -->',
			'<!-- wp:paragraph {"className":"bv-collection-label"} -->',
			'<p class="bv-collection-label"><a href="#">ORGANIC</a></p>',
			'<!-- /wp:paragraph -->',
			'</div>',
			'<!-- /wp:group -->',
			'<!-- wp:group {"className":"bv-collection-card","layout":{"type":"constrained"}} -->',
			'<div class="wp-block-group bv-collection-card">',
			'<!-- wp:cover {"overlayColor":"transparent","minHeight":260,"minHeightUnit":"px","isDark":false,"className":"bv-collection-media"} -->',
			'<div class="wp-block-cover is-light bv-collection-media" style="min-height:260px"><span aria-hidden="true" class="wp-block-cover__background has-transparent-background-color has-background-dim-0 has-background-dim"></span><div class="wp-block-cover__inner-container">',
			'<!-- wp:paragraph {"align":"center"} -->',
			'<p class="has-text-align-center">Upload image</p>',
			'<!-- /wp:paragraph -->',
			'</div></div>',
			'<!-- /wp:cover -->',
			'<!-- wp:paragraph {"className":"bv-collection-label"} -->',
			'<p class="bv-collection-label"><a href="#">SEASONAL</a></p>',
			'<!-- /wp:paragraph -->',
			'</div>',
			'<!-- /wp:group -->',
			'</div>',
			'<!-- /wp:group -->',
			'</div>',
			'<!-- /wp:group -->',
		]),
	]);
});

add_action('widgets_init', static function (): void {
	register_sidebar([
		'name' => __('Sidebar', 'bloomandveg'),
		'id' => 'sidebar-1',
		'description' => __('Main sidebar area.', 'bloomandveg'),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget' => '</section>',
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
	]);
});

add_action('wp_enqueue_scripts', static function (): void {
	$main_css_path = BV_THEME_DIR . '/assets/css/main.css';
	$main_js_path = BV_THEME_DIR . '/assets/js/main.js';
	$main_css_ver = BV_THEME_VERSION;
	$main_js_ver = BV_THEME_VERSION;

	if (file_exists($main_css_path)) {
		$main_css_ver .= '-' . (string) filemtime($main_css_path);
	}
	if (file_exists($main_js_path)) {
		$main_js_ver .= '-' . (string) filemtime($main_js_path);
	}

	wp_enqueue_style(
		'bv-style',
		get_stylesheet_uri(),
		[],
		BV_THEME_VERSION
	);

	wp_enqueue_style(
		'bv-main',
		BV_THEME_URI . '/assets/css/main.css',
		[],
		$main_css_ver
	);

	wp_enqueue_script(
		'bv-main',
		BV_THEME_URI . '/assets/js/main.js',
		[],
		$main_js_ver,
		true
	);
});

add_action('customize_register', static function (WP_Customize_Manager $wp_customize): void {
	$wp_customize->add_section('bv_footer', [
		'title' => __('Footer', 'bloomandveg'),
		'priority' => 160,
	]);

	$settings = [
		'bv_footer_visit_title' => [
			'label' => __('Visit title', 'bloomandveg'),
			'default' => __('Visit Bloom & Veg', 'bloomandveg'),
			'type' => 'text',
		],
		'bv_footer_visit_body' => [
			'label' => __('Visit text (multi-line)', 'bloomandveg'),
			'default' => "The Bloom & Veg Farm Shop\nCentral Farm, Aveley Rd,\nUpminster RM14 2TW",
			'type' => 'textarea',
		],
		'bv_footer_directions_label' => [
			'label' => __('Directions button label', 'bloomandveg'),
			'default' => __('Get Directions', 'bloomandveg'),
			'type' => 'text',
		],
		'bv_footer_directions_url' => [
			'label' => __('Directions URL', 'bloomandveg'),
			'default' => '#',
			'type' => 'url',
		],
		'bv_footer_hours_title' => [
			'label' => __('Opening hours title', 'bloomandveg'),
			'default' => __('Opening Hours', 'bloomandveg'),
			'type' => 'text',
		],
		'bv_footer_hours_body' => [
			'label' => __('Opening hours (multi-line)', 'bloomandveg'),
			'default' => "Monday – Saturday: 8am til 5pm\nSunday: 9am til 4pm",
			'type' => 'textarea',
		],
		'bv_footer_phone' => [
			'label' => __('Phone', 'bloomandveg'),
			'default' => '',
			'type' => 'text',
		],
		'bv_footer_email' => [
			'label' => __('Email', 'bloomandveg'),
			'default' => '',
			'type' => 'text',
		],
		'bv_footer_zones_title' => [
			'label' => __('Delivery zones title', 'bloomandveg'),
			'default' => __('Delivery Zones', 'bloomandveg'),
			'type' => 'text',
		],
		'bv_footer_zones_intro' => [
			'label' => __('Delivery zones intro', 'bloomandveg'),
			'default' => __('We deliver to homes in the following postal code areas:', 'bloomandveg'),
			'type' => 'text',
		],
		'bv_footer_zones_list' => [
			'label' => __('Delivery zones list (multi-line)', 'bloomandveg'),
			'default' => '',
			'type' => 'textarea',
		],
		'bv_footer_zones_bullets' => [
			'label' => __('Delivery bullets (one per line)', 'bloomandveg'),
			'default' => "Morning + afternoon delivery slots\n£4.99 fixed delivery fee\nOrder by 10am for next day delivery\nMinimum spend of £25 applies",
			'type' => 'textarea',
		],
	];

	foreach ($settings as $key => $cfg) {
		$wp_customize->add_setting($key, [
			'default' => $cfg['default'],
			'capability' => 'edit_theme_options',
			'sanitize_callback' => ('url' === $cfg['type']) ? 'esc_url_raw' : 'sanitize_textarea_field',
		]);

		$control_type = ('textarea' === $cfg['type']) ? 'textarea' : 'text';
		$wp_customize->add_control($key, [
			'section' => 'bv_footer',
			'label' => $cfg['label'],
			'type' => $control_type,
		]);
	}
});

add_filter('woocommerce_add_to_cart_fragments', static function (array $fragments): array {
	if (!function_exists('WC') || null === WC()->cart) {
		return $fragments;
	}

	ob_start();
	?>
	<span class="bv-cart-count" data-bv-cart-count><?php echo esc_html((string) WC()->cart->get_cart_contents_count()); ?></span>
	<?php
	$fragments['span[data-bv-cart-count]'] = ob_get_clean();

	ob_start();
	?>
	<div class="bv-mini-cart-panel__body" data-bv-mini-cart-body>
		<?php woocommerce_mini_cart(); ?>
	</div>
	<?php
	$fragments['div[data-bv-mini-cart-body]'] = ob_get_clean();

	return $fragments;
});

// WooCommerce: add a simple "Profile" backend under My Account.
add_action('init', static function (): void {
	if (!function_exists('add_rewrite_endpoint')) {
		return;
	}

	add_rewrite_endpoint('profile', EP_ROOT | EP_PAGES);
});

add_filter('query_vars', static function (array $vars): array {
	$vars[] = 'profile';
	return $vars;
});

add_filter('woocommerce_get_query_vars', static function (array $vars): array {
	// Tell WooCommerce about our custom endpoint.
	$vars['profile'] = 'profile';
	return $vars;
});

add_filter('woocommerce_account_menu_items', static function (array $items): array {
	// Insert after "Dashboard" when possible.
	$new_items = [];
	$inserted = false;
	foreach ($items as $key => $label) {
		$new_items[$key] = $label;
		if ('dashboard' === $key) {
			$new_items['profile'] = __('Profile', 'bloomandveg');
			$inserted = true;
		}
	}

	if (!$inserted) {
		$new_items = ['profile' => __('Profile', 'bloomandveg')] + $items;
	}

	return $new_items;
}, 30);

add_action('wp_loaded', static function (): void {
	if (!function_exists('is_account_page') || !function_exists('wc_get_account_endpoint_url') || !function_exists('wc_add_notice')) {
		return;
	}

	if (!is_account_page() || !is_user_logged_in()) {
		return;
	}

	// Only handle submissions from our Profile endpoint.
	if (!isset($_POST['bv_profile_action']) || 'update' !== (string) $_POST['bv_profile_action']) {
		return;
	}

	$endpoint = function_exists('WC') ? WC()->query->get_current_endpoint() : '';
	if ('profile' !== $endpoint) {
		return;
	}

	if (!isset($_POST['bv_profile_nonce']) || !wp_verify_nonce((string) $_POST['bv_profile_nonce'], 'bv_profile_update')) {
		wc_add_notice(__('Security check failed. Please try again.', 'bloomandveg'), 'error');
		wp_safe_redirect(wc_get_account_endpoint_url('profile'));
		exit;
	}

	$user_id = get_current_user_id();
	$first_name = isset($_POST['first_name']) ? sanitize_text_field((string) $_POST['first_name']) : '';
	$last_name = isset($_POST['last_name']) ? sanitize_text_field((string) $_POST['last_name']) : '';
	$email = isset($_POST['account_email']) ? sanitize_email((string) $_POST['account_email']) : '';
	$phone = isset($_POST['billing_phone']) ? sanitize_text_field((string) $_POST['billing_phone']) : '';

	$ship_first_name = isset($_POST['shipping_first_name']) ? sanitize_text_field((string) $_POST['shipping_first_name']) : '';
	$ship_last_name = isset($_POST['shipping_last_name']) ? sanitize_text_field((string) $_POST['shipping_last_name']) : '';
	$ship_address_1 = isset($_POST['shipping_address_1']) ? sanitize_text_field((string) $_POST['shipping_address_1']) : '';
	$ship_address_2 = isset($_POST['shipping_address_2']) ? sanitize_text_field((string) $_POST['shipping_address_2']) : '';
	$ship_city = isset($_POST['shipping_city']) ? sanitize_text_field((string) $_POST['shipping_city']) : '';
	$ship_postcode = isset($_POST['shipping_postcode']) ? sanitize_text_field((string) $_POST['shipping_postcode']) : '';
	$ship_country = isset($_POST['shipping_country']) ? wc_strtoupper(sanitize_text_field((string) $_POST['shipping_country'])) : '';
	$ship_state = isset($_POST['shipping_state']) ? sanitize_text_field((string) $_POST['shipping_state']) : '';

	if ('' !== $email && !is_email($email)) {
		wc_add_notice(__('Please enter a valid email address.', 'bloomandveg'), 'error');
		wp_safe_redirect(wc_get_account_endpoint_url('profile'));
		exit;
	}

	if ('' === $email) {
		// Email is required for accounts; keep it required here as well.
		wc_add_notice(__('Email address is required.', 'bloomandveg'), 'error');
		wp_safe_redirect(wc_get_account_endpoint_url('profile'));
		exit;
	}

	$existing_user_id = email_exists($email);
	if ($existing_user_id && (int) $existing_user_id !== (int) $user_id) {
		wc_add_notice(__('That email address is already in use.', 'bloomandveg'), 'error');
		wp_safe_redirect(wc_get_account_endpoint_url('profile'));
		exit;
	}

	if (function_exists('WC') && WC()->countries) {
		$allowed_countries = WC()->countries->get_countries();
		if ('' !== $ship_country && !isset($allowed_countries[$ship_country])) {
			$ship_country = '';
		}
	}

	$update = wp_update_user([
		'ID' => $user_id,
		'first_name' => $first_name,
		'last_name' => $last_name,
		'user_email' => $email,
	]);

	if (is_wp_error($update)) {
		wc_add_notice($update->get_error_message(), 'error');
		wp_safe_redirect(wc_get_account_endpoint_url('profile'));
		exit;
	}

	update_user_meta($user_id, 'billing_phone', $phone);

	// Delivery address (stored in WooCommerce shipping meta)
	update_user_meta($user_id, 'shipping_first_name', $ship_first_name);
	update_user_meta($user_id, 'shipping_last_name', $ship_last_name);
	update_user_meta($user_id, 'shipping_address_1', $ship_address_1);
	update_user_meta($user_id, 'shipping_address_2', $ship_address_2);
	update_user_meta($user_id, 'shipping_city', $ship_city);
	update_user_meta($user_id, 'shipping_postcode', $ship_postcode);
	update_user_meta($user_id, 'shipping_country', $ship_country);
	update_user_meta($user_id, 'shipping_state', $ship_state);

	wc_add_notice(__('Profile updated.', 'bloomandveg'), 'success');
	wp_safe_redirect(wc_get_account_endpoint_url('profile'));
	exit;
});

add_action('woocommerce_account_profile_endpoint', static function (): void {
	get_template_part('template-parts/account/profile');
});
