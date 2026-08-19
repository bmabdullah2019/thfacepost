<?php
	$count = $params['poll']->countVotes();
?>
<div class="row">
<div class="col-md-12">
        <div class="poll-container">
            <div class="poll-title">
                <i class="fa fa-line-chart"></i> 
				<?php echo $params['poll']->title;?> 
                <?php if($count > 0){ ?> <span class="poll-votes-total">(<i class="fas fa-users"></i> <?php echo $count; ?>) </span><?php } ?>
            </div>
            <div class="poll-body">
            	<?php
				$poll = $params['poll'];
				$options = $poll->getOptions();
				$votes = $poll->getVotes();
				$high  = false;
				if($votes){
					$sort = $votes;
					arsort($sort);
					$high = array_key_first($sort);	
				}				
                if($options){
					foreach($options as $key => $option){ 
						$width = 0;
						if($votes && isset($votes[$key])){
							$width = $votes[$key];
						}
						$larger_voted = "";
						if($high && $high == $key){
							$larger_voted = "poll-progress-highlight";	
						}			
						$voter_caret = "";
						$voter_data_element = "";
						if((isset($poll->show_voters) && $poll->show_voters == 'yes') || (ossn_isLoggedin() && ossn_loggedin_user()->guid == $poll->owner_guid)){
							$voter_caret = " >";
							$voter_data_element = " data-guid='{$poll->guid}' data-option='{$key}'";
						}
				?>
               	 <div class="ossn-polls-item">
	                		   <div class="progress">
                                     <div class="progress-bar polls-progress-bar-absol <?php echo $larger_voted;?>" role="progressbar" style="width: <?php echo $width;?>%;" aria-valuemax="100">&nbsp;</div>
 									 <div class="progress-bar progress-bar-no-votes polls-show-voters" <?php echo $voter_data_element;?> role="progressbar" style="width:100%;" aria-valuemax="100">
                      				   <span class="poll-check">
                                       		<?php if(isset($poll->is_ended) && $poll->is_ended == true){ ?>
                                            <input type="checkbox" value="<?php echo $key;?>" class="form-check-input" disabled="disabled" />
                                            <?php } else {?>
                                            <input type="checkbox" value="<?php echo $key;?>" class="form-check-input" />
                                            <?php } ?>
                                       </span>                                     
                      				   <span class="poll-label"><?php echo $option;?></span>
                                       <span class="poll-percent"><?php echo $width;?>%<?php echo $voter_caret;?></span>
                       			    </div>
				   			</div>
                    </div>   
                   <?php
					}
				}
				?>
            </div>
            <div class="poll-footer text-center">
            	<input type="hidden" name="guid" value="<?php echo $params['poll']->guid;?>" />
                <input type="hidden" name="option" id="poll-option-<?php echo $params['poll']->guid;?>" />
                <?php 
				if(isset($params['poll']->is_ended) && $params['poll']->is_ended == false){ ?>
                <input type="submit" class="btn btn-primary btn-block btn-sm" id="poll-submit-button" value="<?php echo ossn_print('polls:vote');?>"/>
                <?php } ?>
                <div class="ossn-poll-loading-submit">
                	<div class="ossn-loading"></div>
                </div>
    <div class="margin-top-10 text-left">
	                <?php
					if((ossn_isLoggedin() && $poll->owner_guid == ossn_loggedin_user()->guid) || ossn_isAdminLoggedin()){ ?>
                    <?php
					if(!isset($poll->is_ended) || $poll->is_ended == false){ ?>
                    <a href="<?php echo ossn_site_url("action/poll/end?guid={$poll->guid}", true);?>" class="btn btn-sm btn-primary"><?php echo ossn_print('polls:end');?></a>
                    <?php } ?>
                    
                    <?php if($poll->container_type == 'user'){ ?>
        	        <a href="<?php echo ossn_site_url("action/poll/delete?guid={$poll->guid}", true);?>" class="btn btn-sm btn-danger"><?php echo ossn_print('polls:delete');?></a>
                    <?php } ?>
                    
                    <?php } ?>
                    <?php
					if(ossn_isLoggedin() && $poll->container_type == 'group'){
								if(function_exists('ossn_get_group_by_guid')){
										$group =  ossn_get_group_by_guid($poll->container_guid);
										if($group && $group->owner_guid == ossn_loggedin_user()->guid){
	 													?>
           	       										 <a href="<?php echo ossn_site_url("action/poll/delete?guid={$poll->guid}", true);?>" class="btn btn-sm btn-danger"><?php echo ossn_print('polls:delete');?></a>
                                                        <?php
										}	
								}
					}
					?>                    
                </div>
                <?php if(isset($poll->is_ended) && $poll->is_ended == true){ ?>
                		<div class="alert alert-warning margin-top-10"><?php echo ossn_print('polls:ended');?></div>     
                <?php } ?>                
            </div>
        </div>
</div>
</div>