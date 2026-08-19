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
 
 $component = new OssnComponents;
 $settings  = $component->getSettings('AccessCode'); 
 echo "<label>".ossn_print('accesscode:register:code')."</label>";
 echo ossn_plugin_view('input/text', array(
			'name' => 'access_code',
			'placeholder' => ossn_print('accesscode'),
			'value' => $settings->accesscode,
 ));
 echo "<input type='submit' class='btn btn-success btn-sm' />";
 