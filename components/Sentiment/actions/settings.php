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

 $component = new OssnComponents;
 $input = input('whocansee');
 $email = input('email');
 $apikey = input('apikey');
 $website = input('website');
 
 if(empty($input) || empty($apikey) || empty($website) || empty($email)){
		ossn_trigger_message(ossn_print('sentiment:save:error'), 'error');
		redirect(REF);		 
 }
 $args = array(
			   'whocansee' => $input,
			   'apikey' => $apikey,
			   'website' => $website,
			   'email' => $email,
			   );
 if($component->setSettings('Sentiment', $args)){
		ossn_trigger_message(ossn_print('sentiment:saved'));
		redirect(REF);
 } else {
		ossn_trigger_message(ossn_print('sentiment:save:error'), 'error');
		redirect(REF);	 
 }