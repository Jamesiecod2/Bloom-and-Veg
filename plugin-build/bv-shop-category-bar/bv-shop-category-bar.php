<?php
/**
 * Plugin Name: Bloom & Veg - Shop Category Bar
 * Description: Adds a horizontal product category bar on WooCommerce shop pages.
 * Version: 0.7.3
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

// Guard against the plugin being loaded twice (e.g. duplicate install folders).
if (defined('BV_SHOP_CATEGORY_BAR_LOADED')) {
	return;
}
define('BV_SHOP_CATEGORY_BAR_LOADED', true);

define('BV_SHOP_CATEGORY_BAR_VERSION', '0.7.3');

function bv_shop_category_bar_user_can_manage_product_categories(): bool {
	if (!is_user_logged_in()) {
		return false;
	}
	// WooCommerce product category management typically uses `manage_product_terms`.
	// Some setups grant `manage_woocommerce` instead.
	return current_user_can('manage_product_terms') || current_user_can('manage_woocommerce') || current_user_can('manage_options');
}

define('BV_SHOP_CATEGORY_BAR_OPTION_NAV_TEXT', 'bv_shop_catbar_nav_text');

function bv_shop_category_bar_default_nav_text(): string {
	if (!function_exists('taxonomy_exists') || !taxonomy_exists('product_cat')) {
		return '';
	}

	$lines = [];
	$top_terms = bv_shop_category_bar_get_terms_ordered(0);
	foreach ($top_terms as $term) {
		$children = bv_shop_category_bar_get_terms_ordered((int) $term->term_id);
		$child_slugs = [];
		foreach ($children as $child) {
			$child_slugs[] = (string) $child->slug;
		}

		$line = (string) $term->name . '|' . (string) $term->slug;
		if (!empty($child_slugs)) {
			$line .= '|' . implode(',', $child_slugs);
		}
		$lines[] = $line;
	}

	return implode("\n", $lines);
}

register_activation_hook(__FILE__, static function (): void {
	$existing = get_option(BV_SHOP_CATEGORY_BAR_OPTION_NAV_TEXT, null);
	$existing = is_string($existing) ? trim($existing) : '';
	if ($existing !== '') {
		return;
	}

	$default = bv_shop_category_bar_default_nav_text();
	if ($default !== '') {
		update_option(BV_SHOP_CATEGORY_BAR_OPTION_NAV_TEXT, $default, false);
	}
});

function bv_shop_category_bar_admin_capability(): string {
	// Prefer WooCommerce-specific caps so Shop Managers can access.
	if (current_user_can('manage_woocommerce')) {
		return 'manage_woocommerce';
	}
	if (current_user_can('manage_product_terms')) {
		return 'manage_product_terms';
	}
	return 'manage_options';
}

function bv_shop_category_bar_parse_nav_text(string $raw): array {
	$raw = str_replace("\r", '', $raw);
	$lines = preg_split('/\n+/', $raw) ?: [];
	$nav = [];

	foreach ($lines as $line) {
		$line = trim((string) $line);
		if ($line === '' || strpos($line, '#') === 0) {
			continue;
		}

		$parts = array_map('trim', explode('|', $line));
		$label = isset($parts[0]) ? (string) $parts[0] : '';
		$target = isset($parts[1]) ? (string) $parts[1] : '';
		$children_raw = isset($parts[2]) ? (string) $parts[2] : '';

		$label = $label !== '' ? wp_strip_all_tags($label) : '';
		$target = $target !== '' ? wp_strip_all_tags($target) : '';
		if ($label === '' || $target === '') {
			continue;
		}

		$item = [
			'label' => $label,
			'target' => $target,
		];

		$children = [];
		if ($children_raw !== '') {
			foreach (explode(',', $children_raw) as $child_part) {
				$child = trim((string) $child_part);
				$child = $child !== '' ? wp_strip_all_tags($child) : '';
				if ($child !== '') {
					$children[] = $child;
				}
			}
			$children = array_values(array_unique($children));
		}
		if (!empty($children)) {
			$item['children'] = $children;
		}

		$nav[] = $item;
	}

	return $nav;
}

function bv_shop_category_bar_resolve_link(string $target): string {
	$target = trim((string) $target);
	if ($target === '') {
		return '';
	}

	// Absolute URL.
	if (preg_match('#^https?://#i', $target)) {
		return $target;
	}

	// Site-relative path.
	if (strpos($target, '/') === 0) {
		return home_url($target);
	}

	// Otherwise treat as a product category slug.
	$slug = sanitize_title($target);
	if ($slug === '') {
		return '';
	}

	$term = get_term_by('slug', $slug, 'product_cat');
	if ($term instanceof WP_Term) {
		$link = get_term_link($term, 'product_cat');
		if (!is_wp_error($link)) {
			return (string) $link;
		}
	}

	return (string) home_url('/product-category/' . $slug . '/');
}

add_action('admin_menu', static function (): void {
	if (!is_user_logged_in()) {
		return;
	}
	$cap = bv_shop_category_bar_admin_capability();
	if (!current_user_can($cap)) {
		return;
	}

	$parent = function_exists('WC') ? 'woocommerce' : 'options-general.php';
	add_submenu_page(
		$parent,
		'Shop Category Bar',
		'Shop Category Bar',
		$cap,
		'bv-shop-category-bar',
		static function () use ($cap): void {
			if (!current_user_can($cap)) {
				return;
			}
			?>
			<div class="wrap">
				<h1>Shop Category Bar</h1>
				<p>Define the bar items directly (one per line).</p>
				<form method="post" action="options.php">
					<?php
					settings_fields('bv_shop_catbar');
					do_settings_sections('bv_shop_catbar');
					submit_button();
					?>
				</form>
				<hr />
				<p><a href="<?php echo esc_url(admin_url('edit-tags.php?taxonomy=product_cat&post_type=product')); ?>">Edit product categories</a></p>
			</div>
			<?php
		}
	);
});

add_action('admin_init', static function (): void {
	register_setting('bv_shop_catbar', BV_SHOP_CATEGORY_BAR_OPTION_NAV_TEXT, [
		'type' => 'string',
		'sanitize_callback' => static function ($value): string {
			$value = is_string($value) ? $value : '';
			$value = str_replace("\r", '', $value);
			$lines = preg_split('/\n+/', $value) ?: [];
			$out_lines = [];
			foreach ($lines as $line) {
				$line = trim((string) $line);
				if ($line === '') {
					continue;
				}
				$out_lines[] = $line;
			}
			return implode("\n", $out_lines);
		},
		'default' => '',
	]);

	add_settings_section('bv_shop_catbar_main', '', static function (): void {
		// No section header.
	}, 'bv_shop_catbar');

	add_settings_field(
		'bv_shop_catbar_nav_text',
		'Bar items',
		static function (): void {
			$current = get_option(BV_SHOP_CATEGORY_BAR_OPTION_NAV_TEXT, '');
			$current = is_string($current) ? (string) $current : '';
			?>
			<textarea name="<?php echo esc_attr(BV_SHOP_CATEGORY_BAR_OPTION_NAV_TEXT); ?>" rows="10" style="width:100%;max-width:820px"><?php echo esc_textarea($current); ?></textarea>
			<p class="description">One item per line:</p>
			<ul class="description" style="list-style:disc;padding-left:18px;">
				<li><code>Label|slug</code> (links to a WooCommerce product category slug)</li>
				<li><code>Label|/some-page/</code> (links to a page on this site)</li>
				<li><code>Label|https://example.com</code> (links to an external URL)</li>
				<li><code>Label|slug|child1,child2</code> (dropdown; children can be slugs or URLs)</li>
			</ul>
			<?php
		},
		'bv_shop_catbar',
		'bv_shop_catbar_main'
	);
});

/**
 * Enabled by default.
 *
 * To disable, add to wp-config.php:
 * define('BV_SHOP_CATEGORY_BAR_ENABLED', false);
 */
function bv_shop_category_bar_is_enabled(): bool {
	$enabled = defined('BV_SHOP_CATEGORY_BAR_ENABLED') ? (bool) BV_SHOP_CATEGORY_BAR_ENABLED : true;
	/**
	 * Filter: allow enabling/disabling without editing wp-config.php.
	 * Return true to enable.
	 */
	return (bool) apply_filters('bv_shop_category_bar_enabled', $enabled);
}


function bv_shop_category_bar_get_terms_ordered(int $parent_term_id): array {
	if (!function_exists('taxonomy_exists') || !taxonomy_exists('product_cat')) {
		return [];
	}

	$args = [
		'taxonomy' => 'product_cat',
		'hide_empty' => false,
		'parent' => $parent_term_id,
		'orderby' => 'meta_value_num',
		'order' => 'ASC',
		'meta_key' => 'order',
	];

	$terms = get_terms($args);
	if (is_wp_error($terms) || !is_array($terms)) {
		return [];
	}
	return array_values(array_filter($terms, static fn($t) => $t instanceof WP_Term));
}

define('BV_SHOP_CATEGORY_BAR_ASSET_CSS', 'assets/css/bv-shop-category-bar.css');
define('BV_SHOP_CATEGORY_BAR_ASSET_JS', 'assets/js/bv-shop-category-bar.js');

function bv_shop_category_bar_is_shop_context(): bool {
	// Kept for backwards-compatibility with older logic, but now we want the bar
	// on all front-end pages.
	return !is_admin();
}

// Note: Output buffering injection proved unreliable on the live environment.
// Instead, render at `wp_body_open` so it is always present, and rely on the
// existing JS (plus footer fallback) to move it under `.site-header`.

function bv_shop_category_bar_can_render(): bool {
	if (!bv_shop_category_bar_is_enabled()) {
		return false;
	}
	if (!is_admin()) {
		return true;
	}
	// admin-ajax.php runs in an "admin" context; allow rendering for our JSON endpoint.
	return function_exists('wp_doing_ajax') ? wp_doing_ajax() : (defined('DOING_AJAX') && DOING_AJAX);
}



add_action('wp_ajax_bv_shop_catbar', static function (): void {
	if (!bv_shop_category_bar_is_enabled()) {
		wp_send_json_error(['message' => 'disabled'], 403);
	}
	if (is_admin() && !wp_doing_ajax()) {
		wp_send_json_error(['message' => 'invalid_context'], 400);
	}
	$html = bv_shop_category_bar_render();
	if (!headers_sent()) {
		header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
		header('Pragma: no-cache');
		header('Expires: 0');
	}
	wp_send_json_success([
		'html' => $html,
		'version' => BV_SHOP_CATEGORY_BAR_VERSION,
	]);
});

add_action('wp_ajax_nopriv_bv_shop_catbar', static function (): void {
	if (!bv_shop_category_bar_is_enabled()) {
		wp_send_json_error(['message' => 'disabled'], 403);
	}
	$html = bv_shop_category_bar_render();
	if (!headers_sent()) {
		header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
		header('Pragma: no-cache');
		header('Expires: 0');
	}
	wp_send_json_success([
		'html' => $html,
		'version' => BV_SHOP_CATEGORY_BAR_VERSION,
	]);
});

add_action('wp_enqueue_scripts', static function (): void {
	if (!bv_shop_category_bar_is_enabled()) {
		return;
	}
	if (!bv_shop_category_bar_can_render()) {
		return;
	}

	$css_path = plugin_dir_path(__FILE__) . BV_SHOP_CATEGORY_BAR_ASSET_CSS;
	$js_path = plugin_dir_path(__FILE__) . BV_SHOP_CATEGORY_BAR_ASSET_JS;
	$css_ver = BV_SHOP_CATEGORY_BAR_VERSION;
	$js_ver = BV_SHOP_CATEGORY_BAR_VERSION;
	if (file_exists($css_path)) {
		$css_ver .= '-' . (string) filemtime($css_path);
	}
	if (file_exists($js_path)) {
		$js_ver .= '-' . (string) filemtime($js_path);
	}

	wp_enqueue_style(
		'bv-shop-category-bar',
		plugins_url(BV_SHOP_CATEGORY_BAR_ASSET_CSS, __FILE__),
		[],
		$css_ver
	);

	wp_enqueue_script(
		'bv-shop-category-bar',
		plugins_url(BV_SHOP_CATEGORY_BAR_ASSET_JS, __FILE__),
		[],
		$js_ver,
		false
	);

	wp_localize_script('bv-shop-category-bar', 'BVShopCatBar', [
		'ajaxUrl' => admin_url('admin-ajax.php'),
	]);
}, 20);

add_action('wp_footer', static function (): void {
	if (is_admin()) {
		return;
	}
	if (!bv_shop_category_bar_is_enabled()) {
		return;
	}

	// Move the bar under the header on themes that output it elsewhere.
	?>
	<script>
	(function(){
	  function place(){
	    var bar=document.getElementById('bv-shop-catbar');
	    if(!bar) return;
	    var header=document.querySelector('.site-header');
	    if(!header) return;
	    try{
	      var h = header.getBoundingClientRect().height;
	      if(h && h > 0){
	        document.documentElement.style.setProperty('--bv-site-header-h', h + 'px');
	      }
	    }catch(e){}
	    if(header.nextElementSibling!==bar){
	      header.insertAdjacentElement('afterend', bar);
	    }
	    try{
	      var pos=window.getComputedStyle(header).position;
	      if(pos==='fixed'){
	        bar.style.marginTop = header.getBoundingClientRect().height + 'px';
	      } else {
	        bar.style.marginTop = '';
	      }
	    }catch(e){}
	  }
	  try{ place(); }catch(e){}
	  if(document.readyState==='loading'){
	    document.addEventListener('DOMContentLoaded', place);
	  }
	  window.addEventListener('load', place);
	  window.addEventListener('resize', place);
	  setTimeout(place, 50);
	  setTimeout(place, 250);
	  setTimeout(place, 1000);
	})();
	</script>
	<?php
}, 20);

add_action('wp_head', static function (): void {
	if (is_admin()) {
		return;
	}
	if (!bv_shop_category_bar_is_enabled()) {
		return;
	}

	// Some performance/minify setups strip HTML comments. Use a meta tag too.
	echo "\n<meta name=\"bv-shop-category-bar\" content=\"" . esc_attr(BV_SHOP_CATEGORY_BAR_VERSION) . "\" />\n";
	echo "\n<!-- bv-shop-category-bar " . esc_html(BV_SHOP_CATEGORY_BAR_VERSION) . " (active) -->\n";
}, 999);

add_action('send_headers', static function (): void {
	if (is_admin()) {
		return;
	}
	if (!bv_shop_category_bar_is_enabled()) {
		return;
	}

	if (!function_exists('header') || headers_sent()) {
		return;
	}

	header('X-BV-Shop-Category-Bar: ' . BV_SHOP_CATEGORY_BAR_VERSION);
}, 20);

function bv_shop_category_bar_render(): string {
	if (!bv_shop_category_bar_can_render()) {
		return '';
	}

	$current_slug = '';
	if (function_exists('is_product_category') && is_product_category()) {
		$queried = get_queried_object();
		if ($queried instanceof WP_Term) {
			$current_slug = (string) $queried->slug;
		}
	}
	if ($current_slug === '' && isset($_SERVER['REQUEST_URI'])) {
		$path = (string) wp_parse_url((string) $_SERVER['REQUEST_URI'], PHP_URL_PATH);
		if (preg_match('#/product-category/([^/]+)/?#', $path, $m)) {
			$current_slug = sanitize_title((string) ($m[1] ?? ''));
		}
	}

	$current_term = null;
	if ($current_slug !== '') {
		$maybe = get_term_by('slug', (string) $current_slug, 'product_cat');
		if ($maybe instanceof WP_Term) {
			$current_term = $maybe;
		}
	}


	$raw = get_option(BV_SHOP_CATEGORY_BAR_OPTION_NAV_TEXT, '');
	$raw = is_string($raw) ? trim($raw) : '';
	$nav = $raw !== '' ? bv_shop_category_bar_parse_nav_text($raw) : [];

	$items = [];
	foreach ($nav as $nav_item) {
		$label = isset($nav_item['label']) ? (string) $nav_item['label'] : '';
		$target = isset($nav_item['target']) ? (string) $nav_item['target'] : '';
		if ($label === '' || $target === '') {
			continue;
		}

		$url = bv_shop_category_bar_resolve_link($target);
		if ($url === '') {
			continue;
		}

		$is_active = false;
		if ($current_term instanceof WP_Term && !preg_match('#^https?://#i', $target) && strpos($target, '/') !== 0) {
			$maybe_term = get_term_by('slug', sanitize_title($target), 'product_cat');
			if ($maybe_term instanceof WP_Term) {
				$is_active = ((int) $current_term->term_id === (int) $maybe_term->term_id)
					|| (function_exists('term_is_ancestor_of') && term_is_ancestor_of((int) $maybe_term->term_id, (int) $current_term->term_id, 'product_cat'));
			}
		}

		$children = [];
		$child_targets = isset($nav_item['children']) && is_array($nav_item['children']) ? $nav_item['children'] : [];
		if (!empty($child_targets)) {
			$children[] = [
				'label' => 'All ' . $label,
				'url' => (string) $url,
			];
			foreach ($child_targets as $child_target) {
				$child_target = (string) $child_target;
				$child_url = bv_shop_category_bar_resolve_link($child_target);
				if ($child_url === '') {
					continue;
				}
				$child_label = $child_target;
				if (!preg_match('#^https?://#i', $child_target) && strpos($child_target, '/') !== 0) {
					$maybe_child_term = get_term_by('slug', sanitize_title($child_target), 'product_cat');
					if ($maybe_child_term instanceof WP_Term) {
						$child_label = (string) $maybe_child_term->name;
					}
				}
				$children[] = [
					'label' => (string) $child_label,
					'url' => (string) $child_url,
				];
			}
		}

		$items[] = [
			'label' => (string) $label,
			'url' => (string) $url,
			'slug' => '',
			'active' => $is_active,
			'children' => $children,
			'has_menu' => !empty($children),
		];
	}

	if (empty($items)) {
		return '';
	}

	ob_start();
	?>
	<nav id="bv-shop-catbar" class="bv-shop-catbar" aria-label="Shop categories">
		<div class="bv-shop-catbar__inner">
			<?php foreach ($items as $item) : ?>
				<div class="bv-shop-catbar__item">
					<div class="bv-shop-catbar__pill<?php echo !empty($item['active']) ? ' is-active' : ''; ?>">
						<a class="bv-shop-catbar__link" href="<?php echo esc_url($item['url']); ?>">
							<?php echo esc_html($item['label']); ?>
						</a>
						<?php if (!empty($item['has_menu'])) : ?>
							<div class="bv-shop-catbar__menu" role="menu" aria-label="<?php echo esc_attr($item['label']); ?> menu">
								<?php foreach ($item['children'] as $child) : ?>
									<a role="menuitem" href="<?php echo esc_url($child['url']); ?>"><?php echo esc_html($child['label']); ?></a>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
			<?php if (!is_admin() && bv_shop_category_bar_user_can_manage_product_categories()) : ?>
				<div class="bv-shop-catbar__item bv-shop-catbar__item--edit">
					<div class="bv-shop-catbar__pill">
						<a class="bv-shop-catbar__link bv-shop-catbar__link--edit" href="<?php echo esc_url(admin_url('edit-tags.php?taxonomy=product_cat&post_type=product')); ?>">
							Edit bar
						</a>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</nav>
	<?php
	return (string) ob_get_clean();
}

function bv_shop_category_bar_output_once(): void {
	static $done = false;
	if ($done) {
		return;
	}
	if (!empty($GLOBALS['bv_shop_category_bar_rendered'])) {
		$done = true;
		return;
	}
	$done = true;

	if (!bv_shop_category_bar_can_render()) {
		return;
	}

	$markup = bv_shop_category_bar_render();
	if ($markup === '') {
		return;
	}

	$GLOBALS['bv_shop_category_bar_rendered'] = true;
	echo $markup;

		// Ensure the bar ends up directly under the sticky header even when external
		// scripts are delayed/optimized.
	if (!is_admin()) {
			echo "\n<script>(function(){try{var bar=document.getElementById('bv-shop-catbar');if(!bar)return;function place(){var h=document.querySelector('.site-header');if(!h)return false;if(h.nextElementSibling!==bar){h.insertAdjacentElement('afterend',bar);}return true;}if(place())return;var obs=new MutationObserver(function(){if(place()){try{obs.disconnect();}catch(e){}}});obs.observe(document.documentElement||document.body,{childList:true,subtree:true});setTimeout(function(){try{obs.disconnect();}catch(e){};place();},3000);}catch(e){}})();</script>\n";
	}
}

add_action('woocommerce_before_main_content', 'bv_shop_category_bar_output_once', 5);
add_action('woocommerce_before_shop_loop', 'bv_shop_category_bar_output_once', 5);
add_action('woocommerce_before_single_product', 'bv_shop_category_bar_output_once', 5);

// Fallback for themes that don't call WooCommerce hooks on non-shop pages.
add_action('wp_footer', 'bv_shop_category_bar_output_once', 1);



add_action('wp_footer', static function (): void {
	if (is_admin()) {
		return;
	}
	if (!bv_shop_category_bar_is_enabled()) {
		return;
	}

	// Another non-comment marker for debugging (hidden).
	echo "\n<div id=\"bv-shop-category-bar-active\" data-version=\"" . esc_attr(BV_SHOP_CATEGORY_BAR_VERSION) . "\" style=\"display:none!important\"></div>\n";
}, 999);

add_action('admin_notices', static function (): void {
	if (!bv_shop_category_bar_user_can_manage_product_categories()) {
		return;
	}

	$screen = function_exists('get_current_screen') ? get_current_screen() : null;
	$screen_id = is_object($screen) && isset($screen->id) ? (string) $screen->id : '';
	if (!in_array($screen_id, ['plugins', 'dashboard'], true)) {
		return;
	}

	$state = bv_shop_category_bar_is_enabled() ? 'enabled' : 'disabled';
	$url = admin_url('edit-tags.php?taxonomy=product_cat&post_type=product');
	echo '<div class="notice notice-info"><p><strong>BV Shop Category Bar</strong> is active (v' . esc_html(BV_SHOP_CATEGORY_BAR_VERSION) . ') — ' . esc_html($state) . '. <a href="' . esc_url($url) . '">Edit product categories</a></p></div>';
});
