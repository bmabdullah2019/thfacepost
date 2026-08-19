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
 $guid = input('guid');
 $title = input('title');
 $description  = input('description');
 
 $video = ossn_get_video($guid);
 if(!$video){
	 ossn_trigger_message(ossn_print('video:com:invalid'), 'error');
	 redirect(REF);	 
 }
 
 $user = ossn_loggedin_user();
 if($video->owner_guid == $user->guid || $user->canModerate()){
	 if($video->videoEdit($title, $description)){
	 	ossn_trigger_message(ossn_print('video:com:saved'));
	 	
		$url = $video->getURL();
		header("Location: {$url}");
		exit;
	 } 
 } 
 ossn_trigger_message(ossn_print('video:com:save:failed'), 'error');
 redirect(REF);	 
