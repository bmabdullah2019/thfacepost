<?php
/**
 * Open Source Social Network
 *
 * @package   (openteknik.com).ossn
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright 2014-2017 OpenTeknik LLC
 * @license   OPENTEKNIK LLC, COMMERCIAL LICENSE  https://www.openteknik.com/license/commercial-license-v1
 * @link      https://www.openteknik.com/
 */
?>
<div>
	<label><?php echo ossn_print('polls:title');?></label>
    <input type="text" name="title" />
</div>
<div>
	<label><?php echo ossn_print('polls:show:voters');?></label>
    <p><?php echo ossn_print('polls:show:voters:note');?></p>
    <?php
		echo ossn_plugin_view('input/dropdown', array(
				'name' => 'show_voters',
				'options' => array(
					'yes' => ossn_print('poll:yes'),
					'no' => ossn_print('poll:no'),
				 ),
		));
	?>
</div>
<div class="polls-form-options">
	<label><?php echo ossn_print('polls:options');?> <a id="polls-add-option" class="badge bg-primary"><?php echo ossn_print('polls:add:option');?></a></label>
    <div class="margin-top-10">
	    <input type="text" name="poll_options_1" placeholder="<?php echo ossn_print("polls:option:title");?>" />
     </div>   
</div>
<?php if($params['container_type'] == 'group'){ ?>
<div>
	<label><?php echo ossn_print('polls:group');?></label>
    <input type="text" value="<?php echo $params['group']->title;?>" disabled="disabled" readonly="readonly"/>
<?php } ?>
<?php if($params['container_type'] == 'businesspage'){ ?>
	 <label><?php echo ossn_print('bpage');?></label>
     <input type="text" disabled="disabled" value="<?php echo $params['business']->title;?>" disabled="disabled" readonly="readonly" />     
<?php } ?>
<div>
	<input type="hidden" name="container_type" value="<?php echo $params['container_type'];?>" />
	<input type="hidden" name="container_guid" value="<?php echo $params['container_guid'];?>" />
    <input type="hidden" name="counter" value="1" id="polls-form-counter"/>
	<input type="submit" value="<?php echo ossn_print("polls:publish");?>" class="btn btn-success btn-sm" />
</div>
