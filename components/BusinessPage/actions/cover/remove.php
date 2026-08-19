<?php
header("Content-Type: text/plain");
$guid = input('guid');
$page = get_business_page($guid);
if(!$page){
		echo 0;
		exit();
}
if($page && ($page->owner_guid == ossn_loggedin_user()->guid || ossn_loggedin_user()->canModerate()) && $page->deleteCover()){
	ossn_trigger_message(ossn_print('photo:deleted:success'), 'success');
} else {
	ossn_trigger_message(ossn_print('photo:delete:error'), 'error');	
}
redirect(REF);