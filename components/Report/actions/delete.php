<?php
$guid   = input('guid');
$object = ossn_get_object($guid);

if($object && $object->deleteObject()){
	ossn_trigger_message(ossn_print('report:deleted'));
	redirect(REF);
}
ossn_trigger_message(ossn_print('report:delete:error'), 'error');
redirect(REF);