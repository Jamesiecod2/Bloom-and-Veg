<?php

if (!defined('ABSPATH')) {
	exit;
}

if (!is_user_logged_in()) {
	return;
}

$user = wp_get_current_user();
$user_id = (int) $user->ID;

$first_name = $user->first_name;
$last_name = $user->last_name;
$email = (string) $user->user_email;
$phone = (string) get_user_meta($user_id, 'billing_phone', true);

$ship_first_name = (string) get_user_meta($user_id, 'shipping_first_name', true);
$ship_last_name = (string) get_user_meta($user_id, 'shipping_last_name', true);
$ship_address_1 = (string) get_user_meta($user_id, 'shipping_address_1', true);
$ship_address_2 = (string) get_user_meta($user_id, 'shipping_address_2', true);
$ship_city = (string) get_user_meta($user_id, 'shipping_city', true);
$ship_postcode = (string) get_user_meta($user_id, 'shipping_postcode', true);
$ship_country = (string) get_user_meta($user_id, 'shipping_country', true);
$ship_state = (string) get_user_meta($user_id, 'shipping_state', true);

$countries = [];
if (function_exists('WC') && WC()->countries) {
	$countries = WC()->countries->get_countries();
}

?>

<div class="bv-account-panel">
	<h2 class="bv-account-panel__title"><?php esc_html_e('Your profile', 'bloomandveg'); ?></h2>

	<form class="bv-account-form" method="post">
		<input type="hidden" name="bv_profile_action" value="update" />
		<?php wp_nonce_field('bv_profile_update', 'bv_profile_nonce'); ?>

		<p class="bv-field">
			<label for="bv-first-name"><?php esc_html_e('First name', 'bloomandveg'); ?></label>
			<input id="bv-first-name" type="text" name="first_name" value="<?php echo esc_attr($first_name); ?>" autocomplete="given-name" />
		</p>

		<p class="bv-field">
			<label for="bv-last-name"><?php esc_html_e('Last name', 'bloomandveg'); ?></label>
			<input id="bv-last-name" type="text" name="last_name" value="<?php echo esc_attr($last_name); ?>" autocomplete="family-name" />
		</p>

		<p class="bv-field">
			<label for="bv-email"><?php esc_html_e('Email', 'bloomandveg'); ?></label>
			<input id="bv-email" type="email" name="account_email" value="<?php echo esc_attr($email); ?>" autocomplete="email" required />
		</p>

		<p class="bv-field">
			<label for="bv-phone"><?php esc_html_e('Phone', 'bloomandveg'); ?></label>
			<input id="bv-phone" type="tel" name="billing_phone" value="<?php echo esc_attr($phone); ?>" autocomplete="tel" />
		</p>

		<hr />
		<h3><?php esc_html_e('Delivery address', 'bloomandveg'); ?></h3>

		<p class="bv-field">
			<label for="bv-ship-first"><?php esc_html_e('First name', 'bloomandveg'); ?></label>
			<input id="bv-ship-first" type="text" name="shipping_first_name" value="<?php echo esc_attr($ship_first_name); ?>" autocomplete="given-name" />
		</p>

		<p class="bv-field">
			<label for="bv-ship-last"><?php esc_html_e('Last name', 'bloomandveg'); ?></label>
			<input id="bv-ship-last" type="text" name="shipping_last_name" value="<?php echo esc_attr($ship_last_name); ?>" autocomplete="family-name" />
		</p>

		<p class="bv-field">
			<label for="bv-ship-address1"><?php esc_html_e('Address line 1', 'bloomandveg'); ?></label>
			<input id="bv-ship-address1" type="text" name="shipping_address_1" value="<?php echo esc_attr($ship_address_1); ?>" autocomplete="address-line1" />
		</p>

		<p class="bv-field">
			<label for="bv-ship-address2"><?php esc_html_e('Address line 2', 'bloomandveg'); ?></label>
			<input id="bv-ship-address2" type="text" name="shipping_address_2" value="<?php echo esc_attr($ship_address_2); ?>" autocomplete="address-line2" />
		</p>

		<p class="bv-field">
			<label for="bv-ship-city"><?php esc_html_e('Town / City', 'bloomandveg'); ?></label>
			<input id="bv-ship-city" type="text" name="shipping_city" value="<?php echo esc_attr($ship_city); ?>" autocomplete="address-level2" />
		</p>

		<p class="bv-field">
			<label for="bv-ship-postcode"><?php esc_html_e('Postcode', 'bloomandveg'); ?></label>
			<input id="bv-ship-postcode" type="text" name="shipping_postcode" value="<?php echo esc_attr($ship_postcode); ?>" autocomplete="postal-code" />
		</p>

		<p class="bv-field">
			<label for="bv-ship-country"><?php esc_html_e('Country', 'bloomandveg'); ?></label>
			<select id="bv-ship-country" name="shipping_country" autocomplete="country">
				<option value=""><?php esc_html_e('Select a country', 'bloomandveg'); ?></option>
				<?php foreach ($countries as $code => $name) : ?>
					<option value="<?php echo esc_attr($code); ?>" <?php selected($ship_country, $code); ?>><?php echo esc_html($name); ?></option>
				<?php endforeach; ?>
			</select>
		</p>

		<p class="bv-field">
			<label for="bv-ship-state"><?php esc_html_e('County / State', 'bloomandveg'); ?></label>
			<input id="bv-ship-state" type="text" name="shipping_state" value="<?php echo esc_attr($ship_state); ?>" autocomplete="address-level1" />
		</p>

		<p>
			<button type="submit" class="button button-primary"><?php esc_html_e('Save profile', 'bloomandveg'); ?></button>
		</p>
	</form>
</div>
