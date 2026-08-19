<?php
/**
 * Open Source Social Network
 *
 * @package Open Source Social Network
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (C) OPENTEKNIK  LLC, COMMERCIAL LICENSE
 * @license   OPENTEKNIK  LLC, COMMERCIAL LICENSE, COMMERCIAL LICENSE https://www.openteknik.com/license/commercial-license-v1
 * @link      http://www.opensource-socialnetwork.org/licence
 */
define('EVENTS', ossn_route()->com . 'Events/');
define('EVENTS_SORT_BY_DATE', true); //set false if you wanted to sort events as in the order they created

require_once EVENTS . 'classes/Events.php';
require_once EVENTS . 'lib/events.php';
