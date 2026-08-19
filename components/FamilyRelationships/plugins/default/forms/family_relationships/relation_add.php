<?php
	 ossn_load_external_js('jquery.tokeninput'); 
?>
<div>
	<?php 
		echo ossn_plugin_view('input/dropdown', array(
				'name' => 'type',
				'class' => 'relation-status-type',
				'options' => relationship_types(),
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
</div>
<div class="relation-status-inputs-level2">
<div class="margin-top-10">
	<input type="text" name="friend_guid" id="relation-family-member" />
</div>
<div>
	<input type="text" name="since" id="relation-status-date" placeholder="<?php echo ossn_print('family:relationship:since');?>"/>
</div>
<p><?php echo ossn_print('family:relationship:confirmation');?></p>
</div>
<input type="submit" value="<?php echo ossn_print('save');?>" class="btn btn-success btn-sm"/>