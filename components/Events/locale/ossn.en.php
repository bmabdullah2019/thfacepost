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
$en = array(
	'event' => 'Events',
	'events' => 'Events',
	'event:wall:button' => 'Event',
    'event:fields:required:title:info' => 'Make sure you have entered all the fields.',
	'event:creation:succes' => 'Event has been successfully created',
	'event:creation:failed' => 'Event can not be created',
	'event:creation:succes:saved' => 'Event has been successfully saved',
	'event:save:failed' => 'Can not save the event',
	'event:edit' => 'Edit Event',
	'event:edited' => 'Event has been successfully edited',
	'event:edit:failed' => 'Can not edit the event',
	
	'event:decision:event:saved' => "You have successfully saved the decision",
	'event:created:by' => "<strong>Created By:</strong> %s",
	'event:going' => 'Going',
	'event:interested' => 'Interested',
	'event:nointerested' => 'Not Interested',
	
	'event:start:time' => 'Start Time',
	'event:end:time' => 'End Time',
	'event:price' => 'Price',
	'event:location' => 'Location',
	'event:country' => 'Country',
	'event:date' => 'Date',
	'event:price:any' => 'Price (if any)',
	'event:image' => 'Image',
	'event:allow:comments' => 'Allow comments & likes',

	'event:browse' => 'Browse',
	'event:all' => 'All Events',
	'event:mine' => 'My Events', 
	'event:add' => 'Add Event',
	'event:list' => 'Events List',
	
	'event:no:result' => 'No result found',
	'event:delete:failed' => 'Can not delete event',
	'event:deleted' => 'Event delete failed',

	'ossn:notifications:event:relation:going' => '%s is going to your event',
	'ossn:notifications:event:relation:interested' => '%s is interested in your event',
	'ossn:notifications:event:relation:nointerested' => '%s is not interested in your event',
	
	'events:comments:allowed:select' => '',
	'events:comments:allowed' => 'Allowed',
	'events:comments:notallowed' => 'Not Allowed',
	'event:wall:item:created' => 'Created the new event',
	'ossn:notifications:comments:entity:event:wall' => '%s commented on event',
	'ossn:notifications:like:entity:event:wall' => '%s liked your event',
	
	'event:title' => 'Event title',
	'event:description' => 'Event description',
	'event:delete:makesure' => 'Are you sure you want to delete this Event?',
	'event:isended' => 'Close the Event? (finished)',
	'event:finished:no' => 'No',
	'event:finished:yes' => 'Yes',
	'event:finished' => 'The event has been finished!',
	
	'event:nothing:to:show' => 'There is no item to show!',
	
	//cal
	'event:cal:close' => 'Close',
	'event:cal:month' => 'Month',
	'event:cal:list' => 'List',
	'event:cal:resources' => 'Resources',
	'event:cal:timeline' => 'Timeline',
	'event:cal:day' => 'Day',
	'event:cal:week' => 'Week',
	'event:cal:today' => 'Today',
	'event:cal:next' => 'Next',
	'event:cal:previous' => 'Previous',
	'event:calendar' => 'Calendar',
	'event:free' => 'Free',
);
ossn_register_languages('en', $en); 