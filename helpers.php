<?php

if (!defined('ABSPATH')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}


define('WP_PGP_EMAIL_META_KEY','wp_pgp_email_key');


function get_user_key($user_id=false)
{
	return ($user_id)
				? \get_user_meta($user_id, WP_PGP_EMAIL_META_KEY, true)
				: false;
}

function set_user_key($user_id=false, $key=false)
{
	return ($user_id && $key)
				? \update_user_meta($user_id, WP_PGP_EMAIL_META_KEY, $key)
				: false;
}

?>
