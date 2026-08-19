<?php
/**
 * Open Source Social Network
 *
 * @package   Open Source Social Network
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (C) OpenTeknik LLC
 * @license   Open Source Social Network License (OSSN LICENSE)  http://www.opensource-socialnetwork.org/licence
 * @link      https://www.opensource-socialnetwork.org/
 */
 $guid 		= input('guid');
 $poll	 = ossn_poll_get($guid);
 if(!$poll || ($poll->owner_guid !== ossn_loggedin_user()->guid && $poll->container_type == 'user') && !ossn_isAdminLoggedin()){
	 		ossn_trigger_message(ossn_print('polls:failed:delete'), 'error');
			redirect($poll->getURL(false));	 
 }
 if($poll->container_type == 'group'){
 	if(function_exists('ossn_get_group_by_guid')){
			$group =  ossn_get_group_by_guid($poll->container_guid);
			if($group && ($group->owner_guid !== ossn_loggedin_user()->guid && $poll->owner_guid !== ossn_loggedin_user()->guid)){
	 			ossn_trigger_message(ossn_print('polls:error:created'), 'error');
				redirect(REF);				
			}
 	}	 
 }
 $poll->removeData();
 if($poll->deleteObject()){
	 		ossn_trigger_message(ossn_print('polls:success:delete'));
			redirect();	 
 } else {
	 		ossn_trigger_message(ossn_print('polls:failed:delete'), 'error');
			redirect($poll->getURL(false));	 
 }