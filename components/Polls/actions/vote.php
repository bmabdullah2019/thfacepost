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
 $option 	= input('option'); 
 $poll	 = ossn_poll_get($guid);
 $options = $poll->getOptions();
 if(!$poll){
	 		ossn_trigger_message(ossn_print('polls:failed:voted'), 'error');
			ossn_set_ajax_data(array(
								'status' => false,
								'option' => $options[$option],
			));			 
 }
 if($vote = $poll->addVote($option)){
	 		ossn_trigger_message(ossn_print('polls:success:voted'));
			ossn_set_ajax_data(array(
								'status' => true,
								'option' =>  ossn_plugin_view('polls/pages/result', array(
										'voted' => $vote,	
										'poll' => $poll,
								 )),
			));			
 } else {
	 		ossn_trigger_message(ossn_print('polls:failed:voted'), 'error');
			ossn_set_ajax_data(array(
								'status' => false,
								'option' =>  $options[$option],
			));		
 }