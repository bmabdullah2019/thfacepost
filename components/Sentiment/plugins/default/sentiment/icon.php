<?php
/**
 * Open Source Social Network
 *
 * @package   (Informatikon.com).ossn
 * @author    OSSN Core Team <info@opensource-socialnetwork.org>
 * @copyright (C) OPENTEKNIK  LLC, COMMERCIAL LICENSE
 * @license   OPENTEKNIK  LLC, COMMERCIAL LICENSE, COMMERCIAL LICENSE https://www.openteknik.com/license/commercial-license-v1
 * @link      http://www.opensource-socialnetwork.org/licence
 */
 $component = new OssnComponents;
 $settings  = $component->getSettings('Sentiment'); 
 
 if(isset($settings->whocansee) && $settings->whocansee == 'admin'){
	 	if(!ossn_isAdminLoggedin()){
			return;
		}
 }
 if(isset($params['sentiment']) && !empty($params['sentiment'])){
		if($params['sentiment'] == 'positive'){
			$title = ossn_print('sentiment:positive');
			echo "<span class='sentiment {$params['class']}' title='{$title}'><i class='fa fa-circle sentiment-positive'></i></span>";
		}
		if($params['sentiment'] == 'neutral'){
			$title = ossn_print('sentiment:neutral');			
			echo "<span class='sentiment {$params['class']}' title='{$title}'><i class='fa fa-circle sentiment-neutral'></i></span>";
		}
		if($params['sentiment'] == 'negative'){
			$title = ossn_print('sentiment:negative');			
			echo "<span class='sentiment {$params['class']}' title='{$title}'><i class='fa fa-circle sentiment-negative'></i></span>";
		}		
 }