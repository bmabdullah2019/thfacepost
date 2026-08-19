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
define('__OPENTEKNIK__', ossn_route()->com . 'OpenTeknik/');
require_once(__OPENTEKNIK__ . 'classes/OPENTEKNIK_API.php');
function openteknik_init(){
	ossn_add_hook('required', 'components', 'premium_disable_crtical_components');	
}
function premium_disable_crtical_components($hook, $type, $return, $params){
			$return[] = 'Kernel';
			$return[] = 'OpenTeknik';
			return $return;
}
ossn_register_system_sdk('PremiumAPIBase', 'premium_base_api_init_80');
ossn_register_callback('ossn', 'init', 'openteknik_init');