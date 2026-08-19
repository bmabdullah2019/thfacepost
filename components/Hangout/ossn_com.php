<?php
/**
 * Open Source Social Network
 *
 * @package Open Source Social Network
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright 2014-2017 OpenTeknik LLC
 * @license   Open Source Social Network License (OSSN LICENSE)  http://www.opensource-socialnetwork.org/licence
 * @link      https://www.opensource-socialnetwork.org/
 */
function hangout_init() {
	if(ossn_isLoggedin()){
		ossn_extend_view('ossn/site/head', 'js/hangout');
	}
}
ossn_register_callback('ossn', 'init', 'hangout_init');
