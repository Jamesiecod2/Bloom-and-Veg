<?php

if (!defined('ABSPATH')) {
	exit;
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('entry'); ?>>
	<header class="entry-header">
		<?php the_title('<h1 class="entry-title">', '</h1>'); ?>
		<div class="entry-meta">
			<span class="posted-on"><?php echo esc_html(get_the_date()); ?></span>
		</div>
	</header>

	<div class="entry-content">
		<?php the_content(); ?>
	</div>
</article>
