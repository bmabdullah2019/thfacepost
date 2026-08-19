<?php
	$div = "col-md-11";
	if(isset($params['group'])){
		$div = "";	
	}
?>
<div class="<?php echo $div;?>">
	<div class="ossn-page-contents">
 		<div id="eventCal"><div class="oloading-c"><div style="margin: 0 auto;" class="ossn-loading"></div></div></div>   
    </div>
</div>
<script type="text/javascript">
	<?php if(!isset($params['group'])){ ?>
		ossn_event_calendar(0);
	<?php } else { ?>
		ossn_event_calendar(<?php echo $params['group']->guid;?>);
	<?php } ?>
</script>