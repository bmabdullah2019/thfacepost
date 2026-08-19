<?php
/**
 * Open Source Social Network
 *
 * @package   (openteknik.com).ossn
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (C) OpenTeknik LLC
 * @license   OPENTEKNIK LLC, COMMERCIAL LICENSE v1.0 https://www.openteknik.com/license/commercial-license-v1
 * @link      https://www.openteknik.com
 */
 $title = input('title');
 $Stories = new Stories;
 if($Stories->addStory($title)){
		ossn_trigger_message(ossn_print('stories:added')); 
		redirect(REF);
 } else {
		ossn_trigger_message(ossn_print('stories:add:failed'), 'error'); 
		redirect(REF);	 
 }