<?php
$guid = input('guid');
$page = get_business_page($guid);

if(!$page || $page->owner_guid != ossn_loggedin_user()->guid && !ossn_loggedin_user()->canModerate()){
 	ossn_trigger_message(ossn_print('bpage:edit:failed'), 'error');
 	redirect(REF); 	
}
$username = input('username');
if(strlen($username) < 5){
		ossn_trigger_message(ossn_print('username:error'), 'error');
		redirect(REF);	 
}
if(!preg_match('/^[A-Za-z][A-Za-z0-9]*$/', $username)){
		ossn_trigger_message(ossn_print('username:error'), 'error');
		redirect(REF);	 	 
}
if(get_business_by_username($username)){
		ossn_trigger_message(ossn_print('username:inuse'), 'error');
		redirect(REF);	
}
$page->data->username = trim(strtolower($username));
if($page->save()){
	ossn_trigger_message(ossn_print('bpage:edited'));
	redirect(REF);
}
ossn_trigger_message(ossn_print('bpage:edit:failed'), 'error');
redirect(REF); 	