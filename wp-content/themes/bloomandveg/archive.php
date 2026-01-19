<?php

if (!defined('ABSPATH')) {
	exit;
}

get_header();
?>

<main id="primary" class="site-main">
	<div class="container">
		<header class="page-header">
			<?php the_archive_title('<h1 class="page-title">', '</h1>'); ?>
			<?php the_archive_description('<div class="archive-description">', '</div>'); ?>
		</header>

		<?php if (have_posts()) : ?>
			<?php while (have_posts()) : ?>
				<?php the_post(); ?>
				<?php get_template_part('template-parts/content/content', get_post_type()); ?>
			<?php endwhile; ?>

			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<?php get_template_part('template-parts/content/content', 'none'); ?>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
