<?php
/**
 * Open Source Social Network
 *
 * @package   Kernel
 * @author    OpenTeknik LLC <info@openteknik.com>
 * @copyright 2020 OpenTeknik LLC
 * @license   OPENTEKNIK LLC, COMMERCIAL LICENSE v1.0
 * @link      https://www.openteknik.com/
 */
if(!extension_loaded('ionCube Loader')) {
		echo "PHP ionCube extension is disabled.  Please ask your hosting provider to enable Latest PHP ionCube extension for your hosting account. You may contact us by filling form <a href='https://www.openteknik.com/contact' target='_blank'>Support Contact</a>";
		exit(199);
}
define('KERNEL', ossn_route()->com . 'Kernel/');
ossn_register_class(array(
		'OssnSystem' => KERNEL . 'classes/OssnSystem.php',
		'Kernel'  => KERNEL . 'classes/Kernel.php',
));
require_once(KERNEL . 'lib/init.php');