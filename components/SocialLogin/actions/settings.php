<?php
/**
 * Open Source Social Network
 *
 * @package Open Source Social Network
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (C) OPENTEKNIK  LLC, COMMERCIAL LICENSE
 * @license   OPENTEKNIK  LLC, COMMERCIAL LICENSE, COMMERCIAL LICENSE https://www.openteknik.com/license/commercial-license-v1
 * @link      http://www.opensource-socialnetwork.org/licence
 */
$component = new OssnComponents();

$fb_consumer_key    = input('fb_consumer_key');
$fb_consumer_secret = input('fb_consumer_secret');
$fb_enable          = input('fb_enable');

$tw_consumer_key    = input('tw_consumer_key');
$tw_consumer_secret = input('tw_consumer_secret');
$tw_enable          = input('tw_enable');

$google_client_id     = input('google_client_id');
$google_client_secret = input('google_client_secret');
$google_enable        = input('google_enable');

$apple_client_id  = input('apple_client_id');
$apple_team_id    = input('apple_team_id');
$apple_keyfile_id = input('apple_keyfile_id');
$apple_enable     = input('apple_enable');

$args = array(
		'fb_consumer_key'      => trim($fb_consumer_key),
		'fb_consumer_secret'   => trim($fb_consumer_secret),
		'fb_enable'            => trim($fb_enable),

		'tw_consumer_key'      => trim($tw_consumer_key),
		'tw_consumer_secret'   => trim($tw_consumer_secret),
		'tw_enable'            => trim($tw_enable),

		'google_client_id'     => trim($google_client_id),
		'google_client_secret' => trim($google_client_secret),
		'google_enable'        => trim($google_enable),

		'apple_client_id'      => trim($apple_client_id),
		'apple_team_id'        => trim($apple_team_id),
		'apple_keyfile_id'     => trim($apple_keyfile_id),
		'apple_enable'         => trim($apple_enable),
);
$file = $_FILES['apple_keyfile'];
if(isset($file['tmp_name']) && !empty($file['tmp_name'])) {
		$dir = ossn_get_userdata('social_login/');
		if(!is_dir($dir)) {
				mkdir($dir, 0755, true);
		}
		file_put_contents($dir . 'apple_key_file.p8', file_get_contents($file['tmp_name']));
}
if($component->setSettings('SocialLogin', $args)) {
		ossn_trigger_message(ossn_print('social:login:settings:saved'));
		redirect(REF);
} else {
		ossn_trigger_message(ossn_print('social:login:settings:save:error'), 'error');
		redirect(REF);
}