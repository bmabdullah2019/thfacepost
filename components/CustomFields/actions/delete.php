<?php
/**
 * Open Source Social Network
 *
 * @package   (openteknik.com).ossn
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright 2014-2017 OpenTeknik LLC
 * @license   OPENTEKNIK LLC, COMMERCIAL LICENSE  https://www.openteknik.com/license/commercial-license-v1
 * @link      https://www.openteknik.com/
 */
 $guid = input('guid');
 $object = ossn_get_object($guid);
 
 if($object->deleteObject()){
			ossn_trigger_message(ossn_print('customfield:fields:deleted'));
			redirect(REF);			 
 } else {
			ossn_trigger_message(ossn_print('customfield:fields:delete:error'), 'error');
			redirect(REF);
}
