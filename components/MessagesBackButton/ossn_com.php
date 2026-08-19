<?php
/**
 * Open Source Social Network
 *
 * @package   Dominik Lieger
 * @author    Dominik Lieger
 * @copyright Dominik Lieger
 * @license   GPL v2 https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @link      
 */



function MessagesBackButton_init(){
	ossn_extend_view('js/ossn.site', 'js/mbb');
	  ossn_extend_view('css/ossn.default', 'css/mbb');   


}
ossn_register_callback('ossn', 'init', 'MessagesBackButton_init');
?>
