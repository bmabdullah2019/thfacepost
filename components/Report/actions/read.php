<?php
$guid   = input('guid');
$object = ossn_get_object($guid);

if($object){
	$object->data->is_read = true;
	$object->save();
}
redirect(REF);