<?php

if (!defined('ABSPATH')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}


function wp_mail($to, $subject, $message, $headers, $attachments)
{
	if (false !== ($user = get_user_by('email', $to))) {
		if (false !== ($pgp_key = get_user_key($user->ID))) {
			require_once plugin_dir_path(__FILE__).'php-gpg/libs/GPG.php';

			try {
				$gpg = new GPG();
				$pub_key = new GPG_Public_Key($pgp_key);
				$message = $gpg->encrypt($pub_key, $message);
			} catch(\Exception $e) {
				// not quite sure what to do here....
			}
		}
	}

	return compact( 'to', 'subject', 'message', 'headers', 'attachments' );
}
add_filter('wp_mail', __NAMESPACE__.'\\wp_mail', 10, 3);

