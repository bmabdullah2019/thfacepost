<div>
	<label><?php echo ossn_print('bpage:name');?></label>
	<input type="text" name="name" />
</div>
<div>
	<label><?php echo ossn_print('bpage:desc');?></label>
	<textarea maxlength="250" name="description"></textarea>
</div>
<div>
	<label><?php echo ossn_print('bpage:phone');?></label>
	<input type="text" name="phone" />
</div>
<div>
	<label><?php echo ossn_print('bpage:address');?></label>
	<input type="text" name="address" />
</div>
<div>
	<label><?php echo ossn_print('bpage:website');?></label>
	<input type="text" name="website" />
</div>
<div>
	<label><?php echo ossn_print('email');?></label>
	<input type="text" name="email" />
</div>
<div>
	<label><?php echo ossn_print('bpage:type');?></label>
	<?php
		echo ossn_plugin_view('input/dropdown', array(
				'name' => 'category',
				'options' => business_page_categories(),
		));
	?>
</div>
<div>
	<input type="submit" value="<?php echo ossn_print('save');?>" class="btn btn-primary btn-sm" />
</div>