<?php
/**
 * Open Source Social Network
 *
 * @package   (Informatikon.com).ossn
 * @author    OSSN Core Team <info@opensource-socialnetwork.org>
 * @copyright (C) OPENTEKNIK  LLC, COMMERCIAL LICENSE
 * @license   OPENTEKNIK  LLC, COMMERCIAL LICENSE, COMMERCIAL LICENSE https://www.openteknik.com/license/commercial-license-v1
 * @link      http://www.opensource-socialnetwork.org/licence
 */

$guid = input('guid');
$type = input('type');

// Input validation (optional but recommended)
if(!$guid || !$type) {
		ossn_trigger_message(ossn_print('event:decision:invalid:input'), 'error');
		redirect(REF);
}

$object = ossn_get_event($guid);

// Continue only if it's a valid relationship type and object exists
if(in_array("event:{$type}", ossn_events_relationship_default()) && $object) {
		// Initialize to avoid undefined variable notice
		$loop_decision = array();

		// Loop to check existing event relationships
		foreach (ossn_events_relationship_default() as $item) {
				$data = ossn_relation_exists_event(ossn_loggedin_user()->guid, $object->guid, $item);
				if($data) {
						$loop_decision[] = $data;
				}
		}

		$decision = $loop_decision;

		// If a relation exists and is of a different type, remove it
		if(!empty($decision) && isset($decision[0]) && $decision[0]->type !== "event:{$type}") {
				foreach ($decision as $item) {
						ossn_delete_relationship_by_id($item->relation_id);
				}
		}

		// If relation already exists and matches type, exit early
		if(!empty($decision) && isset($decision[0]) && $decision[0]->type === "event:{$type}") {
				ossn_trigger_message(ossn_print('event:decision:event:saved'));
				redirect(REF);
		}

		// Add new relationship
		if(ossn_add_relation(ossn_loggedin_user()->guid, $guid, "event:{$type}")) {
				if(class_exists('OssnNotifications')) {
						$notification = new OssnNotifications();
						$notification->add("event:relation:{$type}", ossn_loggedin_user()->guid, $object->guid, null, $object->owner_guid);
				}

				ossn_trigger_message(ossn_print('event:decision:event:saved'));
				redirect(REF);
		}
}