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
 $input = input('offline');
 if(empty($input)){
		ossn_trigger_message(ossn_print('siteoffline:save:error'), 'error');
		redirect(REF);		 
 }
 $args = array(
			   'offline' => $input,
			   );
 if($component->setSettings('SiteOffline', $args)){
		ossn_trigger_message(ossn_print('siteoffline:saved'));
		redirect(REF);
 } else {
		ossn_trigger_message(ossn_print('siteoffline:save:error'), 'error');
		redirect(REF);	 
 }