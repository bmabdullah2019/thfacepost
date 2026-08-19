<?php
/**
 * Open Source Social Network
 *
 * @package   Marketplace
 * @author    Engr. Syed Arsalan Hussain Shah <arsalan@openteknik.com>
 * @copyright (C) OPENTEKNIK LLC
 * @license   OPENTEKNIK LLC, COMMERCIAL LICENSE
 * @link      https://www.openteknik.com/
 */
 $family = new Family;
 $friend_guid = input('friend_guid');
 $type = input('type');
 $privacy = input('privacy');
 
 if(empty($type) || empty($friend_guid) || empty($privacy)){
		ossn_trigger_message(ossn_print('family:relationship:all:fields'), 'error');
		redirect(REF);		 
 }
 if($family->addMember($friend_guid, $type, $privacy)){
		ossn_trigger_message(ossn_print('family:relationship:family:member:added'));
		redirect(REF);
 } else {
		ossn_trigger_message(ossn_print('family:relationship:family:member:add:failed'), 'error');
		redirect(REF);	 
 }