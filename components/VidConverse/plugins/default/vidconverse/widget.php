<div class="ossn-widget"> 
	<div class="widget-heading">Video Call</div>
    <div class="widget-contents vidconverse-widget">
    		<div class="button">
             <?php
			 if($params['user']->isOnline(10)){ ?>				
	            <a href="javascript:void(0);" class="vidconverse-button-widget vidconverse-button-widget-disabled-" data-guid="<?php echo $params['user']->guid;?>" data-name="<?php echo $params['user']->first_name;?>">
    	        		<span><i class="fa fa-video-camera"></i>Setup video call</span>
        	    </a>
                <?php } else {?>
	            <a href="javascript:void(0);" class="vidconverse-button-widget vidconverse-button-widget-disabled"  data-guid="<?php echo $params['user']->guid;?>" data-name="<?php echo $params['user']->first_name;?>">
    	        		<span><i class="fa fa-video-camera"></i>Setup video call</span>
        	    </a>                
                <?php } ?> 
                <div class="ossn-loading"></div>
             </div>   
             <p class="encrypted"><i class="fa fa-lock"></i>Peer-to-peer encrypted.</p>
             <?php if(!$params['user']->isOnline(10)){ ?>
            <p class="offline"><?php echo $params['user']->first_name;?> is offline, can not setup a call.</p>
            <?php } else { ?>
            <p class="offline" style="display:none;"><?php echo $params['user']->first_name;?> is offline, can not setup a call.</p>            
            <?php } ?>
    </div>
</div>    