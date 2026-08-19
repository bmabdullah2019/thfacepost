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
$family = new Family;
$reuqests = $family->getRequstsTo(); 

$relationship = new Relationship;
$list = $relationship->getStatus();
$requests_relation = $relationship->getRequstsTo();
?>
<div class="family-relationships-container">
	<div class="family-relationships-title"><?php echo ossn_print('family:relationships');?></div>
    <?php if(!$list){ ?>
    <a class="add-relationship-member" href="javascript:void(0);"><i class="fa fa-plus-circle"></i><?php echo ossn_print('family:relationship:status:add');?></a>
    <div class="relation-member-add-form">
    	<?php
			 	echo ossn_view_form('family_relationships/relation_add', array(
       				 'action' => ossn_site_url() . 'action/family/relation/add',
    			));
		?>
    </div>    
    <?php
	}
	?>
    <div class="family-members-list relationship-status">
	    <?php
			$userguid = ossn_loggedin_user()->guid;
			if($list){
				$member = $list[0];
				if($member->member_guid == false){
						if($member->owner_guid == $userguid){
							$privacy = $member->privacy_from;
						} else {
							$privacy = $member->privacy_to;
						}					
					?>
				  <div class="item">
                            	<div class="user-details">
                                <span>
                                    <?php echo $member->getType();?>
                                </span>
                                    <div class="edit ossn-form">
											<?php 
												echo ossn_plugin_view('input/dropdown', array(
													'class' => 'family-relation-privacy',
													'data-guid' => $member->guid,
													'value' => (int)$privacy,
													'options' => array(
														OSSN_PUBLIC  => ossn_print('public'),				   
														OSSN_FRIENDS => ossn_print('friends'),				   
													),
												));	
											?>	
                                    	<a href="<?php echo ossn_site_url("action/family/relation/cancel?guid={$member->guid}", true);?>" class="family-delete-btn badge bg-danger text-white ossn-make-sure"><i class="fa fa-times"></i></a>
                                    </div>                                                              
                                    <div class="type">
                                        <?php if($member->pending == true){ ?>
												<span class="pending">(<?php echo ossn_print('family:relationship:request:pending');?>)</span>
										<?php } ?>
                                    </div>
                                </div>
                     </div>                      
                    <?php
				} else {
						if($member->owner_guid == $userguid){
							$privacy = $member->privacy_from;
							$user = ossn_user_by_guid($member->member_guid);
						} else {
							$privacy = $member->privacy_to;
							$user = ossn_user_by_guid($member->owner_guid);	
						}
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
                                    <div class="edit ossn-form">
											<?php 
												echo ossn_plugin_view('input/dropdown', array(
													'class' => 'family-relation-privacy',
													'data-guid' => $member->guid,
													'value' => (int)$privacy,
													'options' => array(
														OSSN_PUBLIC  =>  ossn_print('public'),				   
														OSSN_FRIENDS =>  ossn_print('friends'),				   
													),
												));	
											?>	
                                    	<a href="<?php echo ossn_site_url("action/family/relation/cancel?guid={$member->guid}", true);?>" class="family-delete-btn badge bg-danger text-white ossn-make-sure"><i class="fa fa-times"></i></a>
                                    </div>                                                              
                                	<a href="<?php echo $user->profileURL();?>"><?php echo $user->fullname;?></a>
                                    <div class="type">
                                    	<?php echo $member->getType();?>
                                        <?php if(!empty($member->since)){ ?>
                                        <span class="time-created"><?php echo $member->since;?></span>
                                        <?php } ?>
                                        <?php if($member->pending == true){ ?>
												<span class="pending">(<?php echo ossn_print('family:relationship:request:pending');?>)</span>
										<?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                     </div>  						
						<?php 
						}
					?>
					
				
				<?php }
			}
		?>
    </div>
    
	<div class="family-relationships-title margin-top-10"><?php echo ossn_print('family:relationship:family:members');?></div>
    <a class="add-family-member" href="javascript:void(0);"><i class="fa fa-plus-circle"></i><?php echo ossn_print('family:relationship:add:family:member');?></a>
    <div class="family-member-add-form">
    	<?php
			 	echo ossn_view_form('family_relationships/family_add', array(
       				 'action' => ossn_site_url() . 'action/family/member/add',
    			));
		?>
    </div>
    <div class="family-members-list">
	    <?php
			$family = new Family;
			$list = $family->getMembers();
			$userguid = ossn_loggedin_user()->guid;
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
						if(!$user){
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
                                    <div class="edit ossn-form">
											<?php 
												echo ossn_plugin_view('input/dropdown', array(
													'class' => 'family-members-edit',
													'data-guid' => $member->guid,
													'value' => (int)$privacy,
													'options' => array(
														OSSN_PUBLIC  =>  ossn_print('public'),				   
														OSSN_FRIENDS => ossn_print('friends'),				   
													),
												));	
											?>	
                                    	<a href="<?php echo ossn_site_url("action/family/member/cancel?guid={$member->guid}", true);?>" class="family-delete-btn badge bg-danger text-white ossn-make-sure"><i class="fa fa-times"></i></a>
                                    </div>                                                              
                                	<a href="<?php echo $user->profileURL();?>"><?php echo $user->fullname;?></a>
                                    <div class="type">
                                    	<?php echo $member->getType();?>
                                        <?php if($member->pending == true){ ?>
												<span class="pending">(<?php echo ossn_print('family:relationship:request:pending');?>)</span>
										<?php } ?>
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
    <?php if($reuqests){ ?>
	<div class="family-relationships-title"><?php echo ossn_print('family:relationship:members:requests');?></div>
    <div class="family-relationships-requests">
        <table class="table table-striped">
        <?php
		if($reuqests){
				foreach($reuqests as $request){
						$request->user_profile = $params['user'];
						$user = ossn_user_by_guid($request->owner_guid);
						if(!$user){
							continue;	
						}
				?>
			  <tr class="family-member-request-item" id="family-member-row-<?php echo $request->guid;?>">
					 <td><a class="family-member-request-name" target="_blank" href="<?php echo $user->profileURL();?>"><?php echo $user->fullname;?></a></td>
					 <td><?php echo $request->getType();?></td>
					 <td>
                     	<?php
							echo ossn_plugin_view('input/dropdown', array(
								'name' => 'privacy_to',
								'class' => 'family-member-privacy',
								'options' => array(
									OSSN_PUBLIC => ossn_print('public'),				   
									OSSN_FRIENDS => ossn_print('friends'),				   
								),
							));	
						?>
                     </td>
                     <td class="buttons">
                     	<a class="family-member-request-btn family-member-accept badge bg-success" data-guid="<?php echo $request->guid;?>" href="javascript:void(0);"><i class="fa fa-check"></i></a>
                     	<a class="family-member-request-btn badge bg-danger ossn-make-sure" data-guid="<?php echo $request->guid;?>" href="<?php echo ossn_site_url("action/family/member/cancel?guid={$request->guid}", true);?>"><i class="fa fa-times"></i></a>
                        <div class="ossn-loading"></div>
                     </td>
			  </tr>
  <?php
				}
		}
		?>
</table>

    </div>
    <?php } ?>
    
    <?php if($requests_relation){
		?>
	<div class="family-relationships-title"><?php echo ossn_print('family:relationship:request');?></div>
    <div class="family-relationships-requests">
        <table class="table table-striped">
        <?php
				foreach($requests_relation as $request){
						$user = ossn_user_by_guid($request->owner_guid);
						if(!$user){
							continue;	
						}
				?>
			  <tr class="family-member-request-item" id="family-member-row-<?php echo $request->guid;?>">
					 <td><a class="family-member-request-name" target="_blank" href="<?php echo $user->profileURL();?>"><?php echo $user->fullname;?></a></td>
					 <td><?php echo $request->getType();?></td>
					 <td>
                     	<?php
							echo ossn_plugin_view('input/dropdown', array(
								'name' => 'privacy_to',
								'class' => 'family-member-privacy',
								'options' => array(
									OSSN_PUBLIC => ossn_print('public'),				   
									OSSN_FRIENDS => ossn_print('friends'),				   
								),
							));	
						?>
                     </td>
                     <td class="buttons">
                     	<a class="family-member-request-btn family-relation-accept badge bg-success" data-guid="<?php echo $request->guid;?>" href="javascript:void(0);"><i class="fa fa-check"></i></a>
                     	<a class="family-member-request-btn badge bg-danger ossn-make-sure" data-guid="<?php echo $request->guid;?>" href="<?php echo ossn_site_url("action/family/relation/cancel?guid={$request->guid}", true);?>"><i class="fa fa-times"></i></a>
                        <div class="ossn-loading"></div>
                     </td>
			  </tr>
  <?php
		}
		?>
</table>

    </div>
    <?php } ?>    
</div>