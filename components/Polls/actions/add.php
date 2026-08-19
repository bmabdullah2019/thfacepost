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
$container_guid = input('container_guid');
$container_type = input('container_type');
$show_voters    = input('show_voters');

$title   = input('title');
$counter = input('counter');

if($counter < 2){
		ossn_trigger_message(ossn_print('polls:error:atleast2'), 'error');
		redirect(REF);		
}
foreach (range(1, $counter) as $item) {
		$options[] = input("poll_options_{$item}");
}
if(count($options) !== count(array_unique($options))){
		ossn_trigger_message(ossn_print('polls:error:duplicate:options'), 'error');
		redirect(REF);			
}
if(($container_type == 'user' && $container_guid != ossn_loggedin_user()->guid) || !in_array($container_type, polls_container_types())) {
		ossn_trigger_message(ossn_print('polls:error:created'), 'error');
		redirect(REF);
}
if($container_type == 'group' && function_exists('ossn_get_group_by_guid')) {
		$group = ossn_get_group_by_guid($conatiner_guid);
		if($group && !$group->isMember($group->guid, ossn_loggedin_user()->guid)) {
				ossn_trigger_message(ossn_print('polls:error:created'), 'error');
				redirect(REF);
		}
}
if($container_type == 'businesspage') {
		$business_page = get_business_page($container_guid);
		if(!$business_page || ($business_page && $business_page->owner_guid != ossn_loggedin_user()->guid)){
			ossn_trigger_message(ossn_print("polls:error:created"), 'error');
			redirect(REF);				
		}
}
$poll              = new Softlab24\Ossn\Component\Polls();
$poll->show_voters = $show_voters;
if($guid = $poll->addPoll($title, $container_guid, $container_type, $options)) {
		ossn_trigger_message(ossn_print('polls:success:created'));

		$urlize = OssnTranslit::urlize($title);
		redirect("polls/view/{$guid}/{$urlize}");
} else {
		ossn_trigger_message(ossn_print('polls:error:created'), 'error');
		redirect(REF);
}