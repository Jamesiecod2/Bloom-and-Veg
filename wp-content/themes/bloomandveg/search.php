<?php

if (!defined('ABSPATH')) {
	exit;
}

get_header();
?>

<main id="primary" class="site-main">
	<div class="container">
		<header class="page-header">
			<h1 class="page-title">
				<?php
				echo esc_html(sprintf(
					/* translators: %s: search query */
					__('Search results for: %s', 'bloomandveg'),
					get_search_query()
				));
				?>
			</h1>
		</header>

		<?php if (have_posts()) : ?>
			<?php while (have_posts()) : ?>
				<?php the_post(); ?>
				<?php get_template_part('template-parts/content/content', 'search'); ?>
			<?php endwhile; ?>

			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<?php get_template_part('template-parts/content/content', 'none'); ?>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
