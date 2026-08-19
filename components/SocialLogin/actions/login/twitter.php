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
$cc = social_login_cred();
if((!isset($cc->twitter->consumer_key)  || isset($cc->twitter->consumer_key) && empty($cc->twitter->consumer_key)) || (!isset($cc->twitter->consumer_secret) || isset($cc->twitter->consumer_secret) && empty($cc->twitter->consumer_secret))){
		ossn_trigger_message("Error 100!", 'error');
		redirect();
}
$new = new Login;
$URL = $new->twitterURL();
if(!$URL){
	ossn_trigger_message('social:login:account:create:error', 'error');
	redirect();	
}
header("Location: {$URL}");
