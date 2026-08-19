<?php
/**
 * Open Source Social Network
 *
 * @package   Open Source Social Network
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (C) OpenTeknik LLC
 * @license   Open Source Social Network License (OSSN LICENSE)  http://www.opensource-socialnetwork.org/licence
 * @link      https://www.opensource-socialnetwork.org/
 */
define('OSSN_ALLOW_SYSTEM_START', TRUE);
require_once(dirname(dirname(dirname(__FILE__))).'/system/start.php');
if(php_sapi_name() == "cli" || (isset($_SERVER['argv']) && $_SERVER['argv'] > 0)) {
		$video = new Videos;
		$video->cron();
}
