<?php
/**
 * Open Source Social Network
 *
 * @package   (openteknik.com).ossn
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright 2014-2017 OpenTeknik LLC
 * @license   OPENTEKNIK  LLC, COMMERCIAL LICENSE, COMMERCIAL LICENSE https://www.openteknik.com/license/commercial-license-v1
 * @link      https://www.opensource-socialnetwork.org/
 */

if(ossn_isLoggedin()) {
		redirect('home');
}
$username = input('username');
$password = input('password');

if(empty($username) || empty($password)) {
		ossn_trigger_message(ossn_print('login:error'));
		redirect();
}
$user = ossn_user_by_username($username);

//check if username is email
if(strpos($username, '@') !== false) {
		$user     = ossn_user_by_email($username);
		$username = $user->username;
}

if($user && !$user->isUserVALIDATED()) {
		$user->resendValidationEmail();
		ossn_trigger_message(ossn_print('ossn:user:validation:resend'), 'error');
		redirect(REF);
}
$vars = array(
		'user' => $user
);
ossn_trigger_callback('user', 'before:login', $vars);

$login           = new OssnUser;
$login->username = $username;
$login->password = $password;
if($login->Login()) {
	if (isset($_POST['rememberlogin']) && isset($_COOKIE['rl_bfp'])) {
		// Checkbox is selected
		// store mail in a cookie named "rl_uma" that expires after 1 year
		$data = rembember_me_data();
		if (!ossn_loggedin_user()->isAdmin()) {
			setcookie('rl_uma', ossn_string_encrypt($data, $_COOKIE['rl_bfp'] . ossn_site_settings('site_key')), time() + (86400 * 30 * 12), "/");  // 86400 = 1 day
		}
	}
	//One uneeded redirection when login #516
	ossn_trigger_callback('login', 'success', $vars);
	redirect('home');
} else {
	redirect('login?error=1');
}
