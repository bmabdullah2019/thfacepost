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

define('REMEMBER_LOGIN', ossn_route()->com . 'RememberLogin/');
define('REMEMBER_LOGIN_ADMIN_ALLOWED', true);

require_once(REMEMBER_LOGIN . 'lib/rememberlogin.php');