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
$guid              = input('guid');
$type              = input('type');
$friend            = input('friend');
$group             = input('group');
$share_type		   = input('share_type');

$friend_group_guid = false;
$share_type_main = '';
switch($share_type){
		case 'object':
				$object = ossn_get_object($guid);
				if($object){
						$allowed = array('user:wall', 'group:wall', 'businesspage:wall', 'user:event', 'user:poll:item', 'user:video', 'user:file', 'user:marketplace:product');
						if(in_array($object->type.':'.$object->subtype, $allowed)){
								$share_type_main = $object->type.':'.$object->subtype;
						}
				}
			break;
}
if(!empty($friend)) {
		$friend_group_guid = $friend;
}
if(!empty($group)) {
		$friend_group_guid = $group;
}
if(empty($friend_group_guid) && ($type == 'friend' || $type == 'group')) {
		ossn_trigger_message(ossn_print('post:share:error'), 'error');
		redirect(REF);
}

if(!empty($share_type_main) && share_post($share_type_main, $type, $guid, $friend_group_guid)) {
		ossn_trigger_message(ossn_print('post:shared'));
		redirect(REF);
}
ossn_trigger_message(ossn_print('post:share:error'), 'error');
redirect(REF);