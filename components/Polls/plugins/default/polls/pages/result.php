<?php
	$count = $params['poll']->countVotes();
?>
<div class="row">
<div class="col-md-12">
        <div class="poll-container ossn-polls-form-questions">
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
					$hvalue = $sort[$high];
					unset($sort[$high]);
					//all are same
					if(in_array($hvalue, $sort)){
							$high = false;	
					}
				}
                if($options){
					foreach($options as $key => $option){ 
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
				   <div class="row">
                  	<div class="col-md-12 col-sm-12 col-xs-12">
	                		   <div class="progress">
                               		<?php if(isset($votes[$key])){ ?>
                                     <div class="progress-bar polls-progress-bar-absol <?php echo $larger_voted;?>" role="progressbar" style="width: <?php echo $votes[$key];?>%;" aria-valuemax="100">&nbsp;</div>
 									 <div class="progress-bar progress-bar-no-votes polls-show-voters" <?php echo $voter_data_element;?> role="progressbar" style="width:100%;" aria-valuemax="100">
                      				   <span class="poll-check">
                                       		<?php if(isset($params['voted']) && $params['voted'] == $key){ ?>
	                                            <input type="checkbox" class="form-check-input" checked="checked" disabled="disabled" />
                                            <?php } else { ?>
                                            	<input type="checkbox" class="form-check-input" disabled="disabled" />
                                            <?php } ?>                                       
                                       </span>
                                       <span class="poll-label"><?php echo $option;?></span>
                                       <span class="poll-percent"><?php echo $votes[$key];?>%<?php echo $voter_caret;?></span>
                       			    </div>
                                    <?php } else { ?>
  									 <div class="progress-bar progress-bar-no-votes" role="progressbar" style="color:#000;width: 100%;" aria-valuemax="100">
                                       <span class="poll-check"><input type="checkbox" class="form-check-input" disabled="disabled" /></span>
                                       <span class="poll-label"><?php echo $option;?></span>
                                       <span class="poll-percent">0%<?php echo $voter_caret;?></span>
                       			    </div>                                   
                                    <?php } ?>
				   			</div>
                 		  </div>
                    </div> 
                    </div>     
                   <?php
					}
				}
				?>
            </div>
            <div class="poll-footer text-center">
                <div class="margin-top-10 text-left">
	                <?php
					if((ossn_isLoggedin() && $poll->owner_guid == ossn_loggedin_user()->guid) || ossn_isAdminLoggedin()){ ?>
                    <?php
					if(!isset($poll->is_ended) || $poll->is_ended == false){ ?>
                    <a href="<?php echo ossn_site_url("action/poll/end?guid={$poll->guid}", true);?>" class="btn btn-sm btn-primary"><?php echo ossn_print('polls:end');?></a>
                    <?php } ?>
                    
                    <?php if($poll->container_type == 'user' || $poll->container_type == 'businesspage'){ ?>
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