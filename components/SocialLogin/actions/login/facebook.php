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
if((!isset($cc->facebook->consumer_key)  || isset($cc->facebook->consumer_key) && empty($cc->facebook->consumer_key)) || (!isset($cc->facebook->consumer_secret) || isset($cc->facebook->consumer_secret) && empty($cc->facebook->consumer_secret))){
		ossn_trigger_message("Error 100!", 'error');
		redirect();
}
$new = new Login;
$URL = $new->fbURL();
header("Location: {$URL}");
