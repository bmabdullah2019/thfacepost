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
  $settings  = $component->getSettings('Sentiment');
 ?>
 <div>
 	<label><?php echo ossn_print('sentiment:apikey');?></label>
    <?php 
		echo ossn_plugin_view('input/text', array(
				'name' => 'apikey',
				'value' => $settings->apikey,
		));
	?>		
 </div>
 <div>
 	<label><?php echo ossn_print('sentiment:email');?></label>
    <?php 
		echo ossn_plugin_view('input/text', array(
				'name' => 'email',
				'value' => $settings->email,
		));
	?>		
 </div> 
 <div>
 	<label><?php echo ossn_print('sentiment:username');?></label>
    <?php 
		echo ossn_plugin_view('input/text', array(
				'name' => 'website',
				'value' => $settings->website,
		));
	?>		
 </div> 
 <div>
 	<label><?php echo ossn_print('sentiment:whocansee');?></label>
    <?php 
		echo ossn_plugin_view('input/dropdown', array(
				'name' => 'whocansee',
				'value' => $settings->whocansee,
				'options' => array(
							'admin' => ossn_print('sentiment:admin'),
							'all' => ossn_print('sentiment:all'),				   
				),
		));
	?>		
 </div>
 <div class="margin-top-10">
 	<input type="submit" class="btn btn-success" value="<?php echo ossn_print('save');?>" />
 </div>