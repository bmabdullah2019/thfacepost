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
set_time_limit(0);
$guid   = input('guid');
$object = ossn_get_object($guid);

if(!$object) {
		ossn_trigger_message(ossn_print('categories:delete:failed'), 'error');
		redirect(REF);
}
if($object->deleteObject()) {
		$entities = ossn_get_entities(array(
				'type' => 'user',
				'subtype' => 'category',
				'page_limit' => false
		));
		
		if($entities) {
				foreach($entities as $item) {
						$item->deleteEntity();
				}
		}
		ossn_trigger_message(ossn_print('categories:categry:deleted'));
		redirect("administrator/component/Categories");
} else {
		ossn_trigger_message(ossn_print('categories:delete:failed'), 'error');
		redirect(REF);
}