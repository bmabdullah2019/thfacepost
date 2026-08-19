<?php
/**
 * Open Source Social Network
 *
 * @packageOpen Source Social Network
 * @author    Open Social Website Core Team <info@informatikon.com>
 * @copyright 2014 iNFORMATIKON TECHNOLOGIES
 * @license   General Public Licence http://www.opensource-socialnetwork.org/licence
 * @link      http://www.opensource-socialnetwork.org/licence
 */
function video_background_index(){
	ossn_extend_view('js/opensource.socialnetwork', 'videobackground/js');
	ossn_extend_view('css/ossn.default', 'videobackground/css');
}
ossn_register_callback('ossn', 'init', 'video_background_index');