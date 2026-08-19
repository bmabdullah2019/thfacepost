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
 if(!$poll){
	 		ossn_trigger_message(ossn_print('polls:failed:end'), 'error');
			redirect($poll->getURL(false));	 
 }
 $poll->data->is_ended = true;
 if($poll->save()){
	 		ossn_trigger_message(ossn_print('polls:success:end'));
			redirect($poll->getURL(false));	 
 } else {
	 		ossn_trigger_message(ossn_print('polls:failed:end'), 'error');
			redirect($poll->getURL(false));	 
 }