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
			'sort' => input('data'),			 
);
if($init->setSettings('CustomFields', $ini)){
		echo 1;		
} else {
		echo 0;
}
exit;