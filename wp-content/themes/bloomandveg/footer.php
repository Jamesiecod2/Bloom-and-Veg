<?php

if (!defined('ABSPATH')) {
	exit;
}

?>

<footer class="site-footer">
	<?php
	$visit_title = (string) get_theme_mod('bv_footer_visit_title', __('Visit Bloom & Veg', 'bloomandveg'));
	$visit_body = (string) get_theme_mod('bv_footer_visit_body', "The Bloom & Veg Farm Shop\nCentral Farm, Aveley Rd,\nUpminster RM14 2TW");
	$directions_label = (string) get_theme_mod('bv_footer_directions_label', __('Get Directions', 'bloomandveg'));
	$directions_url = (string) get_theme_mod('bv_footer_directions_url', '#');
	$hours_title = (string) get_theme_mod('bv_footer_hours_title', __('Opening Hours', 'bloomandveg'));
	$hours_body = (string) get_theme_mod('bv_footer_hours_body', "Monday – Saturday: 8am til 5pm\nSunday: 9am til 4pm");
	$phone = (string) get_theme_mod('bv_footer_phone', '');
	$email = (string) get_theme_mod('bv_footer_email', '');

	$zones_title = (string) get_theme_mod('bv_footer_zones_title', __('Delivery Zones', 'bloomandveg'));
	$zones_intro = (string) get_theme_mod('bv_footer_zones_intro', __('We deliver to homes in the following postal code areas:', 'bloomandveg'));
	$zones_list = (string) get_theme_mod('bv_footer_zones_list', '');
	$zones_bullets = (string) get_theme_mod('bv_footer_zones_bullets', "Morning + afternoon delivery slots\n£4.99 fixed delivery fee\nOrder by 10am for next day delivery\nMinimum spend of £25 applies");
	?>

	<div class="container bv-footer-top">
		<div class="bv-footer-grid">
			<section class="bv-footer-col" aria-label="<?php echo esc_attr($visit_title); ?>">
				<h3 class="bv-footer-title"><?php echo esc_html($visit_title); ?></h3>
				<div class="bv-footer-text">
					<?php echo wp_kses_post(nl2br(esc_html($visit_body))); ?>
				</div>

				<?php if (!empty($directions_url) && '#' !== $directions_url) : ?>
					<p class="bv-footer-actions">
						<a class="button" href="<?php echo esc_url($directions_url); ?>" target="_blank" rel="noopener noreferrer">
							<?php echo esc_html($directions_label); ?>
						</a>
					</p>
				<?php endif; ?>

				<h4 class="bv-footer-subtitle"><?php echo esc_html($hours_title); ?></h4>
				<div class="bv-footer-text">
					<?php echo wp_kses_post(nl2br(esc_html($hours_body))); ?>
				</div>

				<?php if (!empty($phone) || !empty($email)) : ?>
					<div class="bv-footer-contact">
						<?php if (!empty($phone)) : ?>
							<div><strong><?php esc_html_e('Phone:', 'bloomandveg'); ?></strong> <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9\+]/', '', $phone)); ?>"><?php echo esc_html($phone); ?></a></div>
						<?php endif; ?>
						<?php if (!empty($email)) : ?>
							<div><strong><?php esc_html_e('Email:', 'bloomandveg'); ?></strong> <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a></div>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</section>

			<section class="bv-footer-col" aria-label="<?php echo esc_attr($zones_title); ?>">
				<h3 class="bv-footer-title"><?php echo esc_html($zones_title); ?></h3>
				<p class="bv-footer-text"><?php echo esc_html($zones_intro); ?></p>

				<?php if (!empty($zones_list)) : ?>
					<p class="bv-footer-zones"><?php echo wp_kses_post(nl2br(esc_html($zones_list))); ?></p>
				<?php endif; ?>

				<?php
				$bullet_lines = preg_split('/\R+/', trim($zones_bullets));
				$bullet_lines = is_array($bullet_lines) ? array_values(array_filter(array_map('trim', $bullet_lines))) : [];
				?>
				<?php if (!empty($bullet_lines)) : ?>
					<ul class="bv-footer-bullets">
						<?php foreach ($bullet_lines as $line) : ?>
							<li><?php echo esc_html(ltrim($line, "-• \t")); ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</section>
		</div>
	</div>

	<div class="bv-footer-bottom">
		<div class="container bv-footer-bottom-inner">
			<p class="site-credit">
				<?php
				echo esc_html(sprintf(
					/* translators: %s: current year */
					__('© %s Bloom & Veg', 'bloomandveg'),
					(string) wp_date('Y')
				));
				?>
			</p>
		</div>
	</div>

	<button class="bv-back-to-top" type="button" aria-label="<?php esc_attr_e('Back to top', 'bloomandveg'); ?>" hidden>
		<span aria-hidden="true">↑</span>
	</button>
</footer>

<?php wp_footer(); ?>
</body>
</html>
