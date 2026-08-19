<?php
/**
 * Open Source Social Network
 *
 * @package   Open Source Social Network
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (C) OpenTeknik LLC
 * @license   Open Source Social Network License (OSSN LICENSE)  http://www.opensource-socialnetwork.org/licence
 * @link      https://www.opensource-socialnetwork.org/
 */
$en = array(
		'polls' => 'Polls',
		'polls:add' => 'Polls',
		'polls:poll' => 'Poll',
		'polls:add' => 'Add Poll',
		'polls:title' => 'Title for poll',
		'polls:option:title' => 'Poll option title',
		'polls:add:option' => 'Add Option',
		'polls:options' => 'Options',
		'polls:publish' => "Publish Poll",
		'polls:error:created' => 'Poll can not be created',
		'polls:success:created' => 'Poll has been successfully published on your wall',
		'polls:vote' => 'Vote',
		'polls:success:voted' => 'Your vote has been saved for this poll',
		'polls:failed:voted' => "Can not save the vote for this poll",
		'polls:wall:created' => 'created poll',
		'polls:failed:end' => "Can not end this poll",
		'polls:success:end' => 'Poll has been ended',
		'polls:end' => 'End',
		'polls:delete' => 'Delete',
		'polls:embed' => 'Embed',
		'polls:ended' => 'Poll has been ended',
		
		'ossn:notifications:comments:entity:poll_entity' => "%s commented on the poll",
		'ossn:notifications:like:entity:poll_entity' => '%s liked your poll',
		'polls:failed:delete' => 'Poll can not be deleted',
		'polls:success:delete' => 'Poll has been deleted',
		
		'polls:title' => 'Title',
		'polls:time' => 'Time created',
		'polls:status' => 'Status',
		'polls:status:ended' => 'Ended',
		'polls:status:opened' => 'Opened',
		'polls:all' => 'All Polls',
		'polls:my'  => 'My Polls',
		'polls:list' => 'Poll Lists',
		
		'polls:group' => 'Group',
		'polls:join:group' => 'You need to join group in order to view the polls',
		'polls:show:voters' => 'Show voters',
		'polls:voters' => 'Voters',
		'polls:show:voters:note' => 'Allow users to see voters',
		'poll:yes' => 'Yes',
		'poll:no' => 'No',
		'polls:error:atleast2' => 'Poll need at least 2 options!',
		'polls:error:duplicate:options' => 'Poll options need to be unique!', 
		
);
ossn_register_languages('en', $en);
