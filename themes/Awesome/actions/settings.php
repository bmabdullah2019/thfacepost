<?php
$object = new OssnObject();
$search = $object->searchObject(array(
					'type' => 'site',
					'subtype' => 'awesome:theme',
		  ));
if($search && isset($search[0]->guid)){
		$object = $search[0];	
}

$object->description = input('description');
$object->title 		 = input('awesome_color');
$object->type		 = 'site';
$object->subtype 	 = 'awesome:theme';
$object->owner_guid	 = 1;
if($object->save()){	
		ossn_trigger_message(ossn_print('awesometheme:settings:saved'));
		redirect(REF);
} else {
		ossn_trigger_message(ossn_print('awesometheme:settings:error'), 'error');
		redirect(REF);	
}