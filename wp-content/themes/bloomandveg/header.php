<?php

if (!defined('ABSPATH')) {
	exit;
}

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#primary">
	<?php esc_html_e('Skip to content', 'bloomandveg'); ?>
</a>

<header class="site-header">
	<div class="container header-inner">
		<div class="site-branding">
			<?php if (has_custom_logo()) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<a class="site-title" href="<?php echo esc_url(home_url('/')); ?>">
					<?php bloginfo('name'); ?>
				</a>
			<?php endif; ?>
			<?php $tagline = get_bloginfo('description', 'display'); ?>
			<?php if (!empty($tagline)) : ?>
				<p class="site-tagline"><?php echo esc_html($tagline); ?></p>
			<?php endif; ?>
		</div>

		<div class="header-nav">
			<nav class="primary-nav" aria-label="<?php esc_attr_e('Primary', 'bloomandveg'); ?>">
				<?php
				wp_nav_menu([
					'theme_location' => 'primary',
					'menu_id' => 'primary-menu',
					'container' => false,
				]);
				?>
			</nav>
		</div>

		<div class="header-actions">
			<?php
			$account_url = '';
			if (function_exists('wc_get_page_permalink')) {
				$account_url = wc_get_page_permalink('myaccount');
				if (is_user_logged_in() && function_exists('wc_get_account_endpoint_url')) {
					$account_url = wc_get_account_endpoint_url('profile');
				}
			}
			if (empty($account_url)) {
				$account_url = is_user_logged_in() ? get_edit_user_link() : wp_login_url(home_url('/'));
			}
			?>

			<form class="bv-header-search-form" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
				<label class="screen-reader-text" for="bv-header-search-input">
					<?php esc_html_e('Search for:', 'bloomandveg'); ?>
				</label>
				<input id="bv-header-search-input" type="search" name="s" placeholder="<?php esc_attr_e('Search', 'bloomandveg'); ?>" />
				<button class="bv-icon-pill bv-search-submit" type="submit" aria-label="<?php esc_attr_e('Search', 'bloomandveg'); ?>">
					<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
						<circle cx="11" cy="11" r="7" />
						<path d="M21 21l-4.3-4.3" />
					</svg>
				</button>
			</form>

			<a class="bv-icon-pill bv-account-link" href="<?php echo esc_url($account_url); ?>" aria-label="<?php esc_attr_e('My account', 'bloomandveg'); ?>">
				<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
					<path d="M20 21a8 8 0 0 0-16 0" />
					<circle cx="12" cy="7" r="4" />
				</svg>
			</a>

			<?php get_template_part('template-parts/partials/mini-cart'); ?>

			<button class="menu-toggle bv-icon-pill" type="button" aria-controls="primary-menu" aria-expanded="false" aria-label="<?php esc_attr_e('Menu', 'bloomandveg'); ?>">
				<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
					<path d="M4 6h16" />
					<path d="M4 12h16" />
					<path d="M4 18h16" />
				</svg>
			</button>
		</div>
	</div>
</header>
