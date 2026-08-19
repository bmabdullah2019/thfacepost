<?php
header("Content-Type: text/plain");
$guid = input('guid');
$object = get_business_page($guid);
if(!$object){
		echo 0;
		exit();
}
$cover = new \Ossn\Component\BusinessPage\Photo;
if($object && $object->owner_guid == ossn_loggedin_user()->guid && $cover->addPhoto($guid)){
		$object = get_business_page($guid);
		echo $object->photoURL();
} else {
		echo 0;	
}
exit;