<?php
/**
 * Open Source Social Network
 *
 * @package   (openteknik.com).ossn
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (C) OpenTeknik LLC
 * @license   Open Source Social Network License (OSSN LICENSE)  http://www.opensource-socialnetwork.org/licence
 * @link      https://www.opensource-socialnetwork.org/
 */
if(isset($params['items'])) {
		foreach($params['items'] as $fields) {
				if(isset($fields['text'])) {
						foreach($fields['text'] as $item) {
								$guid = 0;
								if(isset($item['data-guid'])){
									$guid = $item['data-guid'];
									unset($item['data-guid']);									
								}
								if(!isset($item['params'])){
									$item['params'] = array();
								}
								$args                = array();
								$args['name']        = $item['name'];
								$args['value']		 = '';
								if(isset($item['value'])){
									$args['value']		 = $item['value'];
								}
								$args['placeholder'] = (empty($item['placeholder'])) ? ossn_print($item['name']) : $item['placeholder'];
								if(isset($item['class'])){
										$args['class']  = 'form-control '.$item['class'];	
								} else {
										$args['class']  = 'form-control ';											
								}	
								$vars                = array_merge($args, $item['params']);
								echo '<div class="customfield-item" data-guid="'.$guid.'">';
								echo "<div class='text'>";
								//[E]make the label arg assigned to any label of user/field #1646
								if(isset($item['label']) && !is_bool($item['label'])){
									echo "<label>".$item['label']."</label>";
								} elseif((isset($item['label']) && $item['label'] === true) || (isset($params['label']) && $params['label'] === true)){
									echo "<label>".ossn_print("{$item['name']}")."</label>";
								}
								echo ossn_plugin_view('input/text', $vars);
								echo "</div></div>";
								unset($guid);
						}
				}
				if(isset($fields['textarea'])) {
						foreach($fields['textarea'] as $item) {
								$guid = 0;
								if(isset($item['data-guid'])){
									$guid = $item['data-guid'];
									unset($item['data-guid']);									
								}						
								if(!isset($item['params'])){
									$item['params'] = array();
								}
								$args                = array();
								$args['name']        = $item['name'];
								$args['value']		 = '';
								if(isset($item['value'])){
									$args['value']		 = $item['value'];
								}								
								$args['placeholder'] = (empty($item['placeholder'])) ? ossn_print($item['name']) : $item['placeholder'];
								if(isset($item['class'])){
										$args['class']  = 'form-control '.$item['class'];	
								} else {
										$args['class']  = 'form-control ';											
								}	
								$vars                = array_merge($args, $item['params']);
								echo '<div class="customfield-item" data-guid="'.$guid.'">';
								echo "<div class='text'>";
								//[E]make the label arg assigned to any label of user/field #1646
								if(isset($item['label']) && !is_bool($item['label'])){
									echo "<label>".$item['label']."</label>";
								} elseif((isset($item['label']) && $item['label'] === true) || (isset($params['label']) && $params['label'] === true)){
									echo "<label>".ossn_print("{$item['name']}")."</label>";
								}
								echo ossn_plugin_view('input/textarea', $vars);
								echo "</div></div>";
								unset($guid);
						}
				}
				if(isset($fields['dropdown'])) {
						foreach($fields['dropdown'] as $item) {
								$guid = 0;
								if(isset($item['data-guid'])){
									$guid = $item['data-guid'];
									unset($item['data-guid']);									
								}							
								$vars         = array();
								$vars['name'] = $item['name'];
								$args         = array_merge($vars, $item);
								echo '<div class="customfield-item" data-guid="'.$guid.'">';
								echo "<div class='dropdown-block'>";
								//[E]make the label arg assigned to any label of user/field #1646
								if(isset($item['label']) && !is_bool($item['label'])){
									echo "<label>".$item['label']."</label>";
								} elseif((isset($item['label']) && $item['label'] === true) || (isset($params['label']) && $params['label'] === true)){
									echo "<label>".ossn_print("{$item['name']}")."</label>";
								}
								echo ossn_plugin_view('input/dropdown', $args);
								echo "</div></div>";
								unset($guid);
						}
				}						
				if(isset($fields['radio'])) {
						foreach($fields['radio'] as $item) {
								$guid = 0;
								if(isset($item['data-guid'])){
									$guid = $item['data-guid'];
									unset($item['data-guid']);									
								}							
								$vars         = array();
								$vars['name'] = $item['name'];
								$args         = array_merge($vars, $item);
								echo '<div class="customfield-item" data-guid="'.$guid.'">';
								echo "<div class='radio-block-container'>";
								//[E]make the label arg assigned to any label of user/field #1646
								if(isset($item['label']) && !is_bool($item['label'])){
									echo "<label>".$item['label']."</label>";
								} elseif((isset($item['label']) && $item['label'] === true) || (isset($params['label']) && $params['label'] === true)){
									echo "<label>".ossn_print("{$item['name']}")."</label>";
								}
								echo ossn_plugin_view('input/radio', $args);
								echo "</div></div>";
								unset($guid);
						}
				}	
				if(isset($fields['checkbox'])) {
						foreach ($fields['checkbox'] as $item) {
								echo '<div class="customfield-item" data-guid="'.$guid.'">';
								echo "<div class='checkbox-block-container'>";
								$vars         = array();
								$vars['name'] = $item['name'];
								$args         = array_merge($vars, $item);
								//[E]make the label arg assigned to any label of user/field #1646
								if(isset($item['label']) && !is_bool($item['label'])) {
										echo '<label>' . $item['label'] . '</label>';
								} elseif((isset($item['label']) && $item['label'] === true) || (isset($params['label']) && $params['label'] === true)) {
										echo '<label>' . ossn_print("{$item['name']}") . '</label>';
								}
								echo ossn_plugin_view('input/checkbox', $args);
								echo "</div></div>";
						}
				}				
		}
}