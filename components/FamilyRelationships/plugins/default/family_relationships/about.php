<?php
/**
 * Open Source Social Network
 *
 * @package   (openteknik.com).ossn
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright 2014-2017 OPENTEKNIK LLC
 * @license   OPENTEKNIK LLC, COMMERCIAL LICENSE  https://www.openteknik.com/license/commercial-license-v1
 * @link      https://www.openteknik.com/
 */
if(!isset($params['user']->guid)){
	return;	
}
$family = new Family;
$userguid = $params['user']->guid;
$relationship = new Relationship;
$list = $relationship->getStatus($params['user']->guid);
?>
<div class="family-relationships-container">
	<div class="family-relationships-title"><?php echo ossn_print('family:relationships');?></div>
    <div class="family-members-list relationship-status">
	    <?php
			if($list && $list[0]->pending == false){
				$member = $list[0];
				if($member->owner_guid == $userguid){
							$privacy = $member->privacy_from;
							$user = ossn_user_by_guid($member->member_guid);
				} else {
							$privacy = $member->privacy_to;
							$user = ossn_user_by_guid($member->owner_guid);	
				}
				if(((int)$privacy == OSSN_FRIENDS && ossn_isLoggedin() && $params['user']->isFriend(ossn_loggedin_user()->guid, $userguid)) || (int)$privacy == OSSN_PUBLIC|| (ossn_isLoggedin() && ossn_loggedin_user()->guid == $userguid)){
					if($member->member_guid == false){
					?>
				  <div class="item">
                            	<div class="user-details">
                                	<span>
                                    <?php echo $member->getType();?>
                               		 </span>
                                </div>
                     </div>                      
                  	  <?php
					} else {
						if($user){ ?>
                    <div class="item">
                    	<div class="row">
                        	<div class="col-md-1 col-xs-3">
                            	<div class="user-icon">
                                	<img src="<?php echo $user->iconURL()->small;?>" class="image-responsive" />
                                </div>
                            </div>	
                            <div class="col-md-11 col-xs-9">
                            	<div class="user-details">                                                           
                                	<a href="<?php echo $user->profileURL();?>"><?php echo $user->fullname;?></a>
                                    <div class="type">
                                    	<?php echo $member->getType();?>
                                        <?php if(!empty($member->since)){ ?>
                                        <span class="time-created"><?php echo $member->since;?></span>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                     </div>  						
						<?php 
						}				
					}//else
				} //privacy
			}
		?>
    </div>
    
	<div class="family-relationships-title margin-top-10"><?php echo ossn_print('family:relationship:family:members');?></div>
    <div class="family-members-list">
	    <?php
			$family = new Family;
			$list = $family->getMembers($userguid);
			if($list){
				foreach($list as $member){
						$member->user_profile = $params['user'];
						if($member->owner_guid == $userguid){
							$privacy = $member->privacy_from;
							$user = ossn_user_by_guid($member->member_guid);
						} else {
							$privacy = $member->privacy_to;
							$user = ossn_user_by_guid($member->owner_guid);	
						}
						if(!$user || $member->pending == true){
							continue;	
						}
						if((int)$privacy == OSSN_FRIENDS && !ossn_isLoggedin()){
								continue;	
						}
						if((int)$privacy == OSSN_FRIENDS && ossn_isLoggedin() && !$params['user']->isFriend(ossn_loggedin_user()->guid, $userguid)){
								continue;
						}
						
					?>
                    <div class="item">
                    	<div class="row">
                        	<div class="col-md-1 col-xs-3">
                            	<div class="user-icon">
                                	<img src="<?php echo $user->iconURL()->small;?>" class="image-responsive" />
                                </div>
                            </div>	
                            <div class="col-md-11 col-xs-9">
                            	<div class="user-details">                                                            
                                	<a href="<?php echo $user->profileURL();?>"><?php echo $user->fullname;?></a>
                                    <div class="type">
                                    	<?php echo $member->getType();?>
                                    </div>
                                </div>
                            </div>
                        </div>
                     </div>   
                    <?php		
				}
			}
		?>
    </div>
</div>