<?php

if (!defined('ABSPATH')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}


function show_user_profile($user)
{
	if (false === ($pgp_key = get_user_key($user->ID))) {
		$key_type = 'n/a';
	} else {
		require_once plugin_dir_path(__FILE__).'php-gpg/libs/GPG.php';
		$key_types = array(
			PK_TYPE_ELGAMAL => 'ElGamal',
			PK_TYPE_RSA => 'RSA',
			PK_TYPE_UNKNOWN => '???'
		);

		try {
			$pub_key = new \GPG_Public_Key($pgp_key);
			$key_type = $key_types[$pub_key->GetKeyType()];
		} catch(\Exception $e) {
			$key_type = __('INVALID','wp-gpg-email');
		}
	}
?>
	<h3><?php _e('Email Encryption', 'wp-pgp-email') ?></h3>
	<table class="form-table">
		<tbody>
			<tr>
				<th>
					<label for="pgp"><?php _e('PGP Public Key','wp-pgp-email') ?></label>
				</th>
				<td>
					<textarea name="pgp" id="pgp" rows="5" cols="30"><?php echo $pgp_key ?></textarea>
					<br>
					<span class="description"><?php _e('KeyType:','wp-pgp-email') ?> <?php echo $key_type ?></span>
				</td>
			</tr>
		</tbody>
	</table>
<?php
}
add_action('show_user_profile', __NAMESPACE__.'\\show_user_profile');
add_action('edit_user_profile', __NAMESPACE__.'\\show_user_profile');


function personal_options_update($user_id)
{
	return (\current_user_can('edit_user', $user_id))
				? set_user_key($user_id, $_POST['pgp'])
				: false;
}
add_action('personal_options_update', __NAMESPACE__.'\\personal_options_update');
add_action('edit_user_profile_update', __NAMESPACE__.'\\personal_options_update');

