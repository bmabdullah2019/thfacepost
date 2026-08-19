<?php
$guid   = input('guid');
$object = ossn_get_object($guid);

if($object && $object->subtype == 'report'){
	$storage = ossn_get_userdata("object/{$guid}/report/");
	$storage_path = $storage . 'report.json';
	
	$file = file_get_contents($storage_path);
	$json = json_decode($file);
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode($json, JSON_PRETTY_PRINT);
	exit;
}
redirect(REF);