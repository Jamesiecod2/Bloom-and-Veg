<?php

if (!defined('ABSPATH')) {
	exit;
}
?>

<section class="no-results not-found">
	<header class="page-header">
		<h1 class="page-title"><?php esc_html_e('Nothing found', 'bloomandveg'); ?></h1>
	</header>

	<div class="page-content">
		<?php if (is_search()) : ?>
			<p><?php esc_html_e('Try a different search.', 'bloomandveg'); ?></p>
			<?php get_search_form(); ?>
		<?php else : ?>
			<p><?php esc_html_e('It looks like nothing was found at this location.', 'bloomandveg'); ?></p>
		<?php endif; ?>
	</div>
</section>
