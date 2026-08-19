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
require_once SOCIAL_LOGIN . 'vendors/Google/vendor/autoload.php';
Firebase\JWT\JWT::$leeway = 60;

$key = social_login_apple_keyfile();
if($key === false) {
		ossn_trigger_message('Error Apple Key 404!', 'error');
		redirect();
}
$config = social_login_cred();
if(empty($config->apple->client_id) || empty($config->apple->team_id) || empty($config->apple->keyfile_id)) {
		ossn_trigger_message('Error 100!', 'error');
		redirect();
}
$provider = new League\OAuth2\Client\Provider\Apple(array(
		'clientId'    => $config->apple->client_id,
		'teamId'      => $config->apple->team_id,
		'keyFileId'   => $config->apple->keyfile_id,
		'keyFilePath' => $key,
		'redirectUri' => ossn_site_url('social_login/apple'),
));
$authUrl = $provider->getAuthorizationUrl(array(
		'state' => 'OPTIONAL_CUSTOM_CONFIGURED_STATE',
		'scope' => array(
				'name',
				'email',
		),
));
header('Location: ' . $authUrl);
exit();