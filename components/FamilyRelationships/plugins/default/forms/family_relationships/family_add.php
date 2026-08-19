<?php
	 ossn_load_external_js('jquery.tokeninput'); 
?>
<div>
	<input type="text" name="friend_guid" id="familysearch-family-member"/>
</div>
<div>
	<?php 
		echo ossn_plugin_view('input/dropdown', array(
				'name' => 'type',
				'options' => family_relationship_types(),
		));
	?>
</div>
<div class="margin-top-10">
	<label><?php echo ossn_print('privacy');?></label>
	<?php 
		echo ossn_plugin_view('input/dropdown', array(
				'name' => 'privacy',
				'options' => array(
					OSSN_PUBLIC => ossn_print('public'),				   
					OSSN_FRIENDS => ossn_print('friends'),				   
				),
		));	
	?>	
</div><p><?php echo ossn_print('family:relationship:confirmation');?></p>
<input type="submit" value="<?php echo ossn_print('save');?>" class="btn btn-success btn-sm"/>