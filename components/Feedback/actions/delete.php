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
 $guid = input('guid');
 $feedback = ossn_get_object($guid);
 if($feedback && $feedback->deleteObject()){
			ossn_trigger_message(ossn_print('feedback:deleted'));
			redirect(REF);
 } else {
			ossn_trigger_message(ossn_print('feedback:deleted:failed'), 'error');
			redirect(REF);	 
 }