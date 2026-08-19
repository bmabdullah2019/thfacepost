<?php
$guid = input('guid');
$object = get_business_page($guid);
if(!$object){
		echo 0;
		exit();
}
$like = new \Ossn\Component\BusinessPage\Likes;
if($like->Unlike($guid, ossn_loggedin_user()->guid)){
		echo 1;
} else {
		echo 0;	
}