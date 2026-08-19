    <div class="row">
    	<div class="col-md-6">
	    	<input type="text" name="field_name" class="form-control" placeholder="<?php echo ossn_print('customfield:name'); ?>"/>
        </div> 
    	<div class="col-md-6">
	    	<input type="text" name="field_placeholder" class="form-control" placeholder="<?php echo ossn_print('customfield:title'); ?>"/>                
        </div>        
    </div>
    <div class="row">
        <div class="col-md-12">
    	<input type="text" name="field_options" class="form-control" placeholder="<?php echo ossn_print('customfield:options'); ?>"/>
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
			));
			?>       
         </div>
        <div class="col-md-6">
    		<label><?php echo ossn_print('customfield:showsignup'); ?></label>
       	 	<?php 
			$fields = new CustomFields;
			echo ossn_plugin_view('input/dropdown', array(
					'name' => 'show_on_signup',
					'options' => $fields->yesNoInput(),
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
					'options' => $fields->yesNoInput(),
			));
			?>    
         </div>   
        <div class="col-md-6">
    		<label><?php echo ossn_print('customfield:showlabel'); ?></label>
       	 	<?php 
			$fields = new CustomFields;
			echo ossn_plugin_view('input/dropdown', array(
					'name' => 'show_label',
					'options' => $fields->yesNoInput(),
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
					'options' => $fields->yesNoInput(),
			));
			?>    
         </div>   
    </div>    
	<div class="row">
    	<div class="col-md-12">
        <input type="submit" class="btn btn-success btn-sm" value="<?php echo ossn_print('save');?>" />   
        </div>
    </div>