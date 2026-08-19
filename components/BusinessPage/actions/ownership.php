<?php
$guid = input('guid');
$owner = input('friend');
$page = get_business_page($guid);
$user = ossn_user_by_guid($owner);

if(!$page || $page->owner_guid != ossn_loggedin_user()->guid && !ossn_loggedin_user()->canModerate()){
 	ossn_trigger_message(ossn_print('bpage:edit:failed'), 'error');
 	redirect(REF); 	
}

$page->owner_guid = $user->guid;
if($page->save()){
	ossn_trigger_message(ossn_print('bpage:edited'));
	redirect_external($page->getURL());
}
ossn_trigger_message(ossn_print('bpage:edit:failed'), 'error');
redirect(REF); 	