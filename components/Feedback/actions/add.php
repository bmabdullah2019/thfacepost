<?php
/**
 * Open Source Social Network
 *
 * @package Open Source Social Network
 * @author    OSSN Core Team https://www.openteknik.com/contact
 * @copyright (C) OpenTeknik LLC
 * @license   OPENTEKNIK  LLC, COMMERCIAL LICENSE, COMMERCIAL LICENSE https://www.openteknik.com/license/commercial-license-v1
 * @link      https://www.opensource-socialnetwork.org/
 */
 $feedback = new Feedback;
 $text = input('feedback');
 $rate = input('rate');
 if($feedback->addFeedback($text, $rate)){
			ossn_trigger_message(ossn_print('feedback:added'));
			redirect('home');
 } else {
			ossn_trigger_message(ossn_print('feedback:add:failed'), 'error');
			redirect(REF);	 
 }