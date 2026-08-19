<?php
set_time_limit(0);
$version = 'v3.0';
$site    = new OssnSite;
$setting = $site->getSettings('com_event_upgrade');

if(!isset($setting->value) || isset($setting->value) && $setting->value != $version) {
		define('EVENTS', ossn_route()->com . 'Events/');
		define('EVENTS_SORT_BY_DATE', true);
		require_once(EVENTS . 'classes/Events.php');
		$events = new Events;
		$all    = $events->getEvents(array(
				'page_limit' => false
		));
		if($all) {
				foreach($all as $event) {
						$event->data->container_type = 'user';
						$event->data->container_guid = $event->owner_guid;
						$event->save();
						
						error_log("Event upgraded from 2.x to 3.x | {$event->guid}");
				}
		}
		$site->setSetting('com_event_upgrade', $version);
} else {
	error_log("Event component | nothing to do.");		
}