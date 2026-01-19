<?php

if (!defined('ABSPATH')) {
	exit;
}

get_header();
?>

<main id="primary" class="site-main">
	<div class="container layout<?php echo is_front_page() ? ' bv-layout-single' : ''; ?>">
		<div class="content">
			<?php while (have_posts()) : ?>
				<?php the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php if (!is_front_page()) : ?>
						<header class="entry-header">
							<?php the_title('<h1 class="entry-title">', '</h1>'); ?>
						</header>
					<?php endif; ?>

					<div class="entry-content">
						<?php the_content(); ?>
					</div>
				</article>
			<?php endwhile; ?>
		</div>

		<?php if (!is_front_page()) : ?>
			<?php get_sidebar(); ?>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
