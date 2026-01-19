<?php

if (!defined('ABSPATH')) {
	exit;
}

get_header();
?>

<main id="primary" class="site-main">
	<div class="container">
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
