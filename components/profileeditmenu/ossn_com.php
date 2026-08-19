<?php
/**
 * Open Source Social Network
 *
 * @package   Dominik Lieger
 * @author    Dominik Lieger
 * @license   GPL v2 https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @link      https://www.example.com
 */


function profileeditmenu_init() {
    if (ossn_isLoggedin()) {
        $user = ossn_loggedin_user();
        $username = $user->username;

        ossn_extend_view('js/opensource.socialnetwork', 'profileeditmenu/js');
		        ossn_extend_view('css/opensource.socialnetwork', 'profileeditmenu/css');

    }
}

ossn_register_callback('ossn', 'init', 'profileeditmenu_init');
