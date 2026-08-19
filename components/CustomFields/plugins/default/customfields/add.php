<div  class="customfields-form-admin">
<fieldset class="titleform">
	<legend class="titleform"><?php echo ossn_print('customfield:add'); ?></legend>
<?php
	echo ossn_view_form('customfields/add', array(
    		'action' => ossn_site_url() . 'action/customfield/add',
	));
?>		
</fieldset>
	<div style="margin-top:20px;">
		<?php echo ossn_print('customfields:note');?>
	</div>
</div>