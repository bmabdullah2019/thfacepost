<div class="alert alert-warning">
	<p>Please Flush cache from dashboard after changing color</p>
</div>    
<fieldset class="titleform">
	<legend class="titleform">Select Color</legend>
 <?php
  $settings = oa_theme_object();
  $settings = $settings[0];
   $vals = array(
			'primary', 'warning', 'info', 'success', 'danger', 'black',			
	);
  foreach($vals as $color){
	 $img = "<span style='padding:3px;' class='label-{$color}'></span>";
	 $options[$color] = $img.' '.ossn_print("awesometheme:{$color}"); 
  }
 ?>
 <div>
    <?php 
		echo ossn_plugin_view('input/radio', array(
				'name' => 'awesome_color',
				'value' => $settings->title,
				'options' => $options,
		));
	?>		
 </div> 
 <div>
 	<label><strong>Front page text</strong></label>
    <textarea name="description" id="awesome-editor"><?php echo html_entity_decode($settings->description, ENT_QUOTES, 'UTF-8');?></textarea>
 </div>
 <input type="submit" class="btn btn-success btn-sm" />
</fieldset>