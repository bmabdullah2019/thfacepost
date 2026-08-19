<?php
if(isset($params['comment']['sentiment']) && !empty($params['comment']['sentiment'])){
	$json = ossn_plugin_view('sentiment/icon', array(
		'sentiment' => $params['comment']['sentiment'],
		'class' => 'time-created',
	));
	$json = json_encode(array(
				'contents' => $json,						  
	));	
?>
<script>
	$(document).ready(function(){
			var $code = <?php echo $json;?>;
			$('#comments-item-<?php echo $params['comment']['id'];?>').find('.comment-metadata').prepend($code['contents']);
	});
</script>
<?php
}
?>