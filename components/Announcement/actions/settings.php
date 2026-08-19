<?php
/**
 * Open Source Social Network
 *
 * @packageOpen Source Social Network
 * @author    Open Social Website Core Team <info@softlab24.com>
 * @copyright (C) SOFTLAB24 LIMITED
 * @license    OSSN License  https://www.opensource-socialnetwork.org/licence
 * @link      http://www.opensource-socialnetwork.org/licence
 */
 $component = new OssnComponents;
 $announcement = input('announcement');
 $type = input('type');
 
 if(!$announcement){
		$announcement = false;	 
 }
 if(!$type){
		$type = 'danger';	 
 }
 $args = array(
			   'announcement' => $announcement,
			   'type' => $type,
			   );
 if($component->setSettings('Announcement', $args)){
		ossn_trigger_message(ossn_print('announcement:saved'));
		redirect(REF);
 } else {
		ossn_trigger_message(ossn_print('announcement:save:error'), 'error');
		redirect(REF);	 
 }