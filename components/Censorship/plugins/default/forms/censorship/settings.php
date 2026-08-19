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
 $settings  = $component->getSettings('Censorship'); 
 ?>
 <div>
 	<label><?php echo ossn_print('censorship:add:words');?></label>
    <p><?php echo ossn_print('censorship:add:words:note');?></p>
    <?php 
		echo ossn_plugin_view('input/text', array(
				'name' => 'words',
				'value' => $settings->words,
		));
	?>		
 </div>
 <div>
 	<label><?php echo ossn_print('censorship:replace:string');?></label>
    <?php 
		echo ossn_plugin_view('input/text', array(
				'name' => 'string',
				'value' => $settings->stringval,
		));
	?>		
 </div> 
 <div class="margin-top-10">
 	<input type="submit" class="btn btn-success btn-sm" value="<?php echo ossn_print('save');?>" />
 </div>