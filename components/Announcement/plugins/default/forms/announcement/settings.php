<?php
/**
 * Open Source Social Network
 *
 * @packageOpen Source Social Network
 * @author    Open Social Website Core Team <info@softlab24.com>
 * @copyright (C) SOFTLAB24 LIMITED
 * @license    OSSN License  https://www.opensource-socialnetwork.org/licence
 * @link      http://www.opensource-socialnetwork.org/licence
 */
$settings = $params['settings'];
if(!$settings){
	$settings = new stdClass();
	$settings->announcement = '';
}
if (!isset($settings->type)){
	$settings->type = "danger";
}
?>
<div>
	<label>
		<?php echo ossn_print('announcement:text'); ?>
	</label>
	<?php
	echo ossn_plugin_view('input/textarea', array(
		'name' => 'announcement',
		'class' => 'ossn-editor',
		'value' => html_entity_decode($settings->announcement),
	)
	);
	?>
</div>
<div class="mt-4">
	<label>
		<?php echo ossn_print('announcement:type'); ?>
	</label>
    <?php
		echo ossn_plugin_view('input/dropdown', array(
				'name' => 'type',
				'value' => $settings->type,
				'id' => 'announcement-type',
				'options' => array(
						'primary' => 'Primary',
						'secondary' => 'Secondary',
						'success' => 'Success',
						'danger' => 'Danger',
						'warning' => 'Warning',
						'info' => 'Info',
						'light' => 'Light',
						'dark' => 'Dark',
				 ),											  
		));
	?>
    <script>
		$(document).ready(function(){
					$('#announcement-type option').each(function(){
								$val = $(this).val();
								$(this).addClass("alert alert-"+$val);
					});						   
		});
	</script>
</div>
<div>
	<div class="margin-top-10">
		<input type="submit" class="btn btn-success" value="<?php echo ossn_print('save'); ?>" />
	</div>
</div>
