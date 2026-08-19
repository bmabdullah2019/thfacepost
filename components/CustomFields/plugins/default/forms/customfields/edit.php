<?php
	$guid   = input('guid');
	$object = ossn_get_object($guid);
	$options = "";
	if(isset($object->field_options) && !empty($object->field_options)){
			$options = json_decode($object->field_options);
			$options = implode(', ', $options);
	}
?>
    <div class="row">
    	<div class="col-md-6">
	    	<input type="text" name="field_name" class="form-control" value="<?php echo $object->field_name;?>" placeholder="<?php echo ossn_print('customfield:name'); ?>" readonly="readonly"/>
        </div> 
    	<div class="col-md-6">
	    	<input type="text" name="field_placeholder" class="form-control" value="<?php echo $object->placeholder;?>" placeholder="<?php echo ossn_print('customfield:title'); ?>"/>                
        </div>        
    </div>
    <div class="row">
        <div class="col-md-12">
    	<input type="text" name="field_options" value="<?php echo $options;?>" class="form-control" placeholder="<?php echo ossn_print('customfield:options'); ?>"/>
        </div>       
    </div>
    <div class="row"> 
        <div class="col-md-6">
    		<label><?php echo ossn_print('customfield:type'); ?></label>
       	 	<?php 
			$fields = new CustomFields;
			echo ossn_plugin_view('input/dropdown', array(
					'name' => 'field_type',
					'options' => $fields->inputTypes(),
					'value' => $object->field_type,
					'readonly' => 'readonly',
					'class' => 'form-control',
			));
			?>       
         </div>
        <div class="col-md-6">
    		<label><?php echo ossn_print('customfield:showsignup'); ?></label>
       	 	<?php 
			$fields = new CustomFields;
			echo ossn_plugin_view('input/dropdown', array(
					'name' => 'show_on_signup',
					'class' => 'form-control',
					'options' => $fields->yesNoInput(),
					'value' => $object->show_on_signup,					
			));
			?>       
         </div>                         
    </div>
    <div class="row">
          <div class="col-md-6">
    		<label><?php echo ossn_print('customfield:fieldrequired'); ?></label>
       	 	<?php 
			$fields = new CustomFields;
			echo ossn_plugin_view('input/dropdown', array(
					'name' => 'is_required',
					'class' => 'form-control',
					'options' => $fields->yesNoInput(),
					'value' => $object->is_required,					
			));
			?>    
         </div>   
        <div class="col-md-6">
    		<label><?php echo ossn_print('customfield:showlabel'); ?></label>
       	 	<?php 
			$fields = new CustomFields;
			echo ossn_plugin_view('input/dropdown', array(
					'name' => 'show_label',
					'class' => 'form-control',
					'options' => $fields->yesNoInput(),
					'value' => $object->show_label,										
			));
			?>    
         </div>    
    </div>
    <div class="row">
          <div class="col-md-6">
    		<label><?php echo ossn_print('customfield:showonabout'); ?></label>
       	 	<?php 
			$fields = new CustomFields;
			echo ossn_plugin_view('input/dropdown', array(
					'name' => 'show_on_about',
					'class' => 'form-control',
					'options' => $fields->yesNoInput(),
					'value' => $object->show_on_about,															
			));
			?>    
         </div>   
    </div>    
	<div class="row">
    	<div class="col-md-12">
        <input type="hidden" name="guid" value="<?php echo $object->guid;?>" />
        <input type="submit" class="btn btn-success btn-sm" value="<?php echo ossn_print('save');?>" />   
        </div>
    </div><br />
    <?php echo ossn_plugin_view('customfields/menu');?>
	<div style="margin-top:20px;">
		<?php echo ossn_print('customfields:note');?>
	</div>
