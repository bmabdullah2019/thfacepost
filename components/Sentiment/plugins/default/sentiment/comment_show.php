<?php 
if(isset($params['comment']['sentiment'])){
	$code = json_encode(array(
				'contents' => ossn_plugin_view('sentiment/icon', array(
								'sentiment' => $params['comment']['sentiment'],
								'class' => 'time-created',
				)),						  
	));	
?>
<script>
$(document).ready(function(){
		var code = <?php echo $code;?>;
		$('#comments-item-<?php echo $params['comment']['id'];?> .comment-metadata').prepend(code['contents']);					   
});
</script>
<?php } ?>