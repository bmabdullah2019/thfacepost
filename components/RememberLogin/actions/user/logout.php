<?php
/**
 * Open Source Social Network
 *
 * @package   (openteknik.com).ossn
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright 2014-2017 OpenTeknik LLC
 * @license   OPENTEKNIK  LLC, COMMERCIAL LICENSE, COMMERCIAL LICENSE https://www.openteknik.com/license/commercial-license-v1
 * @link      https://www.opensource-socialnetwork.org/
 */

if (isset($_COOKIE['rl_ht'])) {
    unset($_COOKIE['rl_ht']);
	setcookie('rl_ht', '', time() - 3600, '/', '', null, true);
}
if (isset($_COOKIE['rl_t'])) {
	unset($_COOKIE['rl_t']);
	setcookie('rl_t', '', time() - 3600, '/', '', null, true);
}
ossn_logout();
redirect();	
