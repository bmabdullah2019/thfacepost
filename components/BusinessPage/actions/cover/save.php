<?php
/**
 * Open Source Social Network
 *
 * @package   (openteknik.com).ossn
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (C) OpenTeknik LLC
 * @license   OPENTEKNIK  LLC, COMMERCIAL LICENSE, COMMERCIAL LICENSE https://www.openteknik.com/license/commercial-license-v1
 * @link      https://www.opensource-socialnetwork.org/
 */
$guid = input('guid');
$object = get_business_page($guid);
if(!$object){
		echo 0;
		exit();
}
$object->data->cover_top = input('top');
$object->data->cover_left = input('left');
if($object && $object->owner_guid == ossn_loggedin_user()->guid && $object->save()){
		echo 1;
} else {
		echo 0;	
}