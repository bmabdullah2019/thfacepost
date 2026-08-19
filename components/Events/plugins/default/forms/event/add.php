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
 $container = $params['container'];
 $type  = $params['type'];
?>
<?php if($type == 'businesspage'){ ?>
	 <label><?php echo ossn_print('bpage');?></label>
     <input type="text" value="<?php echo $container->title;?>" disabled="disabled" readonly="readonly" />     
<?php } ?>
<?php if($type == 'group'){ ?>
	 <label><?php echo ossn_print('group:name');?></label>
     <input type="text" value="<?php echo $container->title;?>" disabled="disabled" readonly="readonly" />     
<?php } ?>
<div>
    <label class="event-required-label"><?php echo ossn_print('event:title');?></label>
    <input type="text" name="title" required/>
</div>
<div>
    <label class="event-required-label"><?php echo ossn_print('event:description');?></label>
    <textarea name="info" required></textarea>
</div>
<div class="time-picker-start">
    <label class="event-required-label"><?php echo ossn_print('event:start:time');?></label>
    <input type="text" data-format="HH:mm PP" name="start_time" class="add-on" required />
</div>
<div class="time-picker-end">
    <label class="event-required-label"><?php echo ossn_print('event:end:time');?></label>
    <input type="text" data-format="HH:mm PP" name="end_time" class="add-on" required />
</div>
<div>
    <label class="event-required-label"><?php echo ossn_print('event:location');?></label>
    <input type="text" name="location" id="event-location-input" required/>
</div>
<div>
    <label class="event-required-label"><?php echo ossn_print('event:date');?></label>
    <input class="add-on" id="event-date" name="date" type="text" required readonly="readonly">
</div>

<div>
    <label><?php echo ossn_print('event:price:any');?></label>
    <input type="text" name="event_cost" />
</div>
<div>
	<label><?php echo ossn_print('event:allow:comments');?></label>
    <?php
		echo ossn_plugin_view('input/dropdown', array(
					'name' => 'allowed_comments',
					'options' => ossn_events_comments_allowed(),
		));
	?>
</div>
<div>
    <label class="event-required-label"><?php echo ossn_print('event:image');?></label>
    <input type="file" name="picture" required />
</div>
<div>
	<input type="hidden" name="container_guid" value="<?php echo input('container_guid');?>" />
	<input type="hidden" name="container_type" value="<?php echo input('container_type');?>" />
    <input type="submit" value="<?php echo ossn_print('save');?>" class="btn btn-success btn-sm" />
</div>
<script type="text/javascript">
    $(function() {
        $('.time-picker-start').datetimepicker({
            pickDate: false,
            pick12HourFormat: true,
            pickSeconds: false,
        });
        $('.time-picker-end').datetimepicker({
            pickDate: false,
            pick12HourFormat: true,
            pickSeconds: false,
        });
		
		//Event date is not translated 
		//Use JQ UI instead
		var cYear = (new Date).getFullYear();
		var alldays = Ossn.Print('datepicker:days');
		var shortdays = alldays.split(",");
		var allmonths = Ossn.Print('datepicker:months');
		var shortmonths = allmonths.split(",");

		var datepick_args = {
			changeMonth: true,
			changeYear: true,
			dateFormat: 'mm/dd/yy',
		    minDate: 0,
            maxDate: "+12m"
		};

		if (Ossn.isLangString('datepicker:days')) {
			datepick_args['dayNamesMin'] = shortdays;
		}
		if (Ossn.isLangString('datepicker:months')) {
			datepick_args['monthNamesShort'] = shortmonths;
		}
		$("#event-date").datepicker(datepick_args);
		$('.ui-datepicker').addClass('notranslate');		
    });
</script>
<style>
.event-required-label:after {
  content:"*";
  margin-left:5px;
  color:red;	
}
</style>