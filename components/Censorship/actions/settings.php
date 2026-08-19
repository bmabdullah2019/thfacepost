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
 $input = input('words');
 $string = input('string');
 if(empty($input) || empty($string)){
		ossn_trigger_message(ossn_print('censorship:fields:error'), 'error');
		redirect(REF);		 
 }
 $args = array(
			   'words' => trim($input),
			   'stringval' => trim($string),
			   );
 if($component->setSettings('Censorship', $args)){
		ossn_trigger_message(ossn_print('censorship:saved'));
		redirect(REF);
 } else {
		ossn_trigger_message(ossn_print('censorship:save:error'), 'error');
		redirect(REF);	 
 }