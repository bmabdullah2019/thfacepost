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
?>
<div>
    <label><?php echo ossn_print('event:title');?></label>
    <input type="text" name="title" value="<?php echo $params['event']->title;?>" />
</div>
<div>
    <label><?php echo ossn_print('event:description');?></label>
    <textarea name="info"><?php echo ossn_restore_new_lines($params['event']->description);?></textarea>
</div>
<div class="time-picker-start">
    <label><?php echo ossn_print('event:start:time');?></label>
    <input type="text" data-format="HH:mm PP" name="start_time" class="add-on" value="<?php echo $params['event']->start_time;?>"/>
</div>
<div class="time-picker-end">
    <label><?php echo ossn_print('event:end:time');?></label>
    <input type="text" data-format="HH:mm PP" name="end_time" class="add-on" value="<?php echo $params['event']->end_time;?>" />
</div>
<div>
    <label><?php echo ossn_print('event:location');?></label>
    <input type="text" name="location" id="event-location-input" value="<?php echo $params['event']->location;?>" />
</div>
<div>
    <label><?php echo ossn_print('event:date');?></label>
    <input  class="add-on" id="event-date" name="date" type="text" value="<?php echo $params['event']->date;?>">
</div>

<div>
    <label><?php echo ossn_print('event:price:any');?></label>
    <input type="text" name="event_cost" value="<?php echo $params['event']->event_cost;?>" />
</div>
<div>
	<label><?php echo ossn_print('event:allow:comments');?></label>
    <?php
		echo ossn_plugin_view('input/dropdown', array(
					'name' => 'allowed_comments',
					'options' => ossn_events_comments_allowed(),
					'value' => $params['event']->allowed_comments_likes,
		));
	?>
</div>

<div>
	<label><?php echo ossn_print('event:isended');?></label>
    <?php
		echo ossn_plugin_view('input/dropdown', array(
					'name' => 'is_finished',
					'options' => array(
						'no' => ossn_print('event:finished:no'),
						'yes' => ossn_print('event:finished:yes'),
					),
					'value' => $params['event']->is_finished,
		));
	?>
</div>
<div>
    <label><?php echo ossn_print('event:image');?></label>
    <input type="file" name="picture" />
</div>
<div>
	<input type="hidden" name="guid" value="<?php echo $params['event']->guid;?>" />
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