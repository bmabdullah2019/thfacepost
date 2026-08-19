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
require_once(SOCIAL_LOGIN . 'vendors/Google/vendor/autoload.php');
use League\OAuth2\Client\Provider\Google;
$config = social_login_cred();
if(empty($config->google->client_id)  || empty($config->google->client_secret)){
		ossn_trigger_message("Error 100!", 'error');
		redirect();
}
$provider = new Google([
	'clientId'     => $config->google->client_id,
	'clientSecret' => $config->google->client_secret,
	'redirectUri'  => ossn_site_url('social_login/google'),
    'accessType'   => 'offline',
]);
$authUrl = $provider->getAuthorizationUrl();
//$_SESSION['oauth2state'] = $provider->getState();
header('Location: ' . $authUrl);
exit;
