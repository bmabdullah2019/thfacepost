<fieldset class="titleform">
	<legend class="titleform"><?php echo ossn_print('customfield:aboutpage'); ?></legend>
<?php
	echo ossn_view_form('customfields/aboutpage', array(
    		'action' => ossn_site_url() . 'action/customfield/aboutpage',
	));
?>		
</fieldset>