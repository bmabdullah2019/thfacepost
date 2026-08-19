<?php 
if(isset($params['post']->sentiment)){
	$code = json_encode(array(
				'contents' => ossn_plugin_view('sentiment/icon', array(
								'sentiment' => $params['post']->sentiment,
								'class' => 'time-created',
				)),						  
	));	
?>
<script>
		$(document).ready(function(){
					var $code = <?php echo $code;?>;
					$('#activity-item-<?php echo $params['post']->guid;?>').find('.post-meta').append($code['contents']);
		});
</script>
<?php
}