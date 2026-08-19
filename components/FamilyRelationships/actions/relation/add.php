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
 $relation = new Relationship;
 $friend_guid = input('friend_guid');
 $type = input('type');
 $privacy = input('privacy');
 $since = input('since');
 
 if(empty($type)){
		ossn_trigger_message(ossn_print('family:relationship:all:fields'), 'error');
		redirect(REF);		 
 }
 if($relation->addRelation($friend_guid, $type, $since, $privacy)){
		ossn_trigger_message(ossn_print('family:relationship:added'));
		redirect(REF);
 } else {
		ossn_trigger_message(ossn_print('family:relationship:family:member:add:failed'), 'error');
		redirect(REF);	 
 }