<div  class="customfields-form-admin">
<fieldset class="titleform">
	<legend class="titleform"><?php echo ossn_print('customfield:edit'); ?></legend>
<?php
	echo ossn_view_form('customfields/edit', array(
    		'action' => ossn_site_url() . 'action/customfield/edit',
	));
?>		
</fieldset>
</div>