<?php
if(!isset($params['page']->username)){
	$params['page']->username = "";	
}
?>
<div>
	<label><?php echo ossn_print('username');?></label>
	<input type="text" name="username" value="<?php echo $params['page']->username;?>" />
</div>
<div class="mt-2">
	<input type="submit" value="<?php echo ossn_print('save');?>" class="btn btn-success btn-sm"  />
    <input type="hidden" name="guid" value="<?php echo $params['page']->guid;?>" />
</div>