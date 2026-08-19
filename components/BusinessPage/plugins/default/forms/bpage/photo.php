<input type="file" name="photo" class="hidden d-none" onchange="Ossn.Clk('#upload-photo-page .upload');" />
<input type="hidden" name="guid" value="<?php echo $params['page']->guid;?>" />
<input type="submit" class="upload d-none" />
<div class="btn-action" id="bpage-upload-photo-btn">
	<?php echo ossn_print('bpage:change:photo');?>
</div>