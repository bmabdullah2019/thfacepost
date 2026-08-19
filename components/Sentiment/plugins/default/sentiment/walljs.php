<?php
if(isset($params['post']->sentiment) && !empty($params['post']->sentiment)){
	$json = ossn_plugin_view('sentiment/icon', array(
				'sentiment' => $params['post']->sentiment,
				'text' => '-',
				'class' => 'time-created',
	));
	$json = json_encode(array(
				'contents' => $json,						  
	));
?>
<script>
	$(document).ready(function(){
			var $code = <?php echo $json;?>;
			$('#activity-item-<?php echo $params['post']->guid;?> .meta').find('.post-meta').append($code['contents']);
	});
</script>
<?php
}