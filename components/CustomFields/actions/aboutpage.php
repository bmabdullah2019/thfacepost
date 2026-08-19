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

$init = new OssnComponents;
$ini = array(
			'show_about_page' => input('show_about_page'),			 
);
if($init->setSettings('CustomFields', $ini)){
			ossn_trigger_message(ossn_print('customfield:fields:aboutsaved'));
			redirect(REF);			 
 } else {
			ossn_trigger_message(ossn_print('customfield:fields:aboutsave:error'), 'error');
			redirect(REF);
}
