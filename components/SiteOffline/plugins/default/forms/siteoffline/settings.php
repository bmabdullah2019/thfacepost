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
  $settings  = $component->getSettings('SiteOffline');
 ?>
 <div>
 	<label><?php echo ossn_print('siteoffline');?></label>
    <?php 
		echo ossn_plugin_view('input/radio', array(
				'name' => 'offline',
				'value' => $settings->offline,
				'options' => array(
							'online' => ossn_print('siteoffline:online'),
							'offline' => ossn_print('siteoffline:offline'),				   
				),
		));
	?>		
 </div>
 <div class="margin-top-10">
 	<input type="submit" class="btn btn-success btn-sm" value="<?php echo ossn_print('save');?>" />
 </div>