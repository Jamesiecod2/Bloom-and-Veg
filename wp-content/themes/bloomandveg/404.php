<?php

if (!defined('ABSPATH')) {
	exit;
}

get_header();
?>

<main id="primary" class="site-main">
	<div class="container">
		<section class="error-404 not-found">
			<header class="page-header">
				<h1 class="page-title"><?php esc_html_e('Page not found', 'bloomandveg'); ?></h1>
			</header>

			<div class="page-content">
				<p><?php esc_html_e("Sorry, we couldn't find that page.", 'bloomandveg'); ?></p>
				<?php get_search_form(); ?>
			</div>
		</section>
	</div>
</main>

<?php
get_footer();
