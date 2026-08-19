    <div class="row">
          <div class="col-md-12">
    		<label><?php echo ossn_print('customfield:showaboutpage'); ?></label>
       	 	<?php 
			$init = new OssnComponents;
			$list = $init->getSettings('CustomFields');
			$fields = new CustomFields;
			echo ossn_plugin_view('input/dropdown', array(
					'name' => 'show_about_page',
					'options' => $fields->yesNoInput(),
					'value' => $list->show_about_page,
			));
			?>    
         </div>   
    </div>
	<div class="row">
    	<div class="col-md-12">
        <input type="submit" class="btn btn-success btn-sm" value="<?php echo ossn_print('save');?>" />   
        </div>
    </div>    