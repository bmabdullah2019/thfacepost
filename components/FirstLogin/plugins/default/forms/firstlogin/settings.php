<div>
	<label><?php echo ossn_print('first:login:url');?></label>
    <input type="text" name="pattern" value="<?php echo first_login_settings();?>" placeholder='<?php echo ossn_print('first:login:placeholder');?>' />
</div>
<div>
	<p><?php echo ossn_print('first:login:info');?></p>
</div>
<div>
	<input type="submit" value="<?php echo ossn_print('save');?>" class="btn btn-success btn-sm"/>
</div>