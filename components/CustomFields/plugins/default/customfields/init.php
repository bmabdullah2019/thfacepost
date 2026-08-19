<div  class="customfields-form-admin">
	<fieldset class="titleform">
		<legend class="titleform"><?php echo ossn_print('admin:customfields'); ?></legend>
        <div class="row">
        	<div class="col-md-6 col-md-offset-5 offset-md-5">
            	    	<?php
							$coms = new OssnComponents;
							$all  = $coms->getActive();
							foreach($all as $allc){
								$alllist[] = $allc->com_id;	
							}
							$disable = array('aboutuser');
							if(!in_array($disable[0], $alllist) && !in_array($disable[1], $alllist) && !in_array($disable[2], $alllist)){
						?>	
                        <a href="<?php echo ossn_site_url("action/customfield/init", true);?>" class="btn btn-success"><?php echo ossn_print('customfields:init');?></a>  					<?php } else {
							echo ossn_print('customfield:disablecomponent');
                        } ?>
            </div>
        </div>
   </fieldset>
</div>    