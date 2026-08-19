<?php
/**
 * Open Source Social Network
 *
 * @package Open Source Social Network
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (C) OPENTEKNIK  LLC, COMMERCIAL LICENSE
 * @license   OPENTEKNIK  LLC, COMMERCIAL LICENSE, COMMERCIAL LICENSE https://www.openteknik.com/license/commercial-license-v1
 * @link      http://www.opensource-socialnetwork.org/licence
 */
 
 $events = new Events;
 $all = $events->getEvents(array(
			'entities_pairs' => array(
					array(
						'name' => 'container_type',
						'value' => 'group',
				 	),
					array(
						'name' => 'container_guid',
						'value' => $params['group']->guid,
				 	)					
			)									   
 ));
 $count = $events->getEvents(array(
		'count' => true,
		'entities_pairs' => array(
					array(
						'name' => 'container_type',
						'value' => 'group',
				 	),
					array(
						'name' => 'container_guid',
						'value' => $params['group']->guid,
				 	)					
		 )		
 ));
 if($all){
				foreach($all as $item){ 
					if(!$item instanceof Events){
						continue;
					}
?>
            <div class="row event-list-item">
           			<div class="col-md-4">
                                  <div class="image-event">
                				<img src="<?php echo $item->iconURL();?>" />
                			</div>
                    </div>
                    <div class="col-md-8">
                    	<div class="title"><span><?php echo $item->title;?></span></div>
                        <p><?php echo strl($item->description, 255);?></p>
                        <div class="options">
                        	<div class="metadata">
                            	<li><i class="fa fa-flag"></i><?php echo $item->country;?></li>
                                <li><i class="fa fa-map-marker"></i><?php echo $item->location;?></li>
                                <li><i class="fa fa-calendar-o"></i><?php echo date("F, d Y", strtotime($item->date));?></li>
                                <li><i class="fa fa-clock-o"></i><?php echo $item->start_time; ?> - <?php echo $item->end_time; ?></li>
                            </div>
                            <div class="margin-top-10">
                        	<a href="<?php echo $item->profileURL();?>" class="btn btn-info"><?php echo ossn_print("event:browse");?> <i class="fa fa-arrow-right"></i></a>
	                    	<?php if(ossn_isLoggedin() && $params['group']->owner_guid == ossn_loggedin_user()->guid || ossn_isAdminloggedin()){ ?>
										<a href="<?php echo ossn_site_url("action/event/delete?guid={$item->guid}", true);?>" class="btn btn-danger"><?php echo ossn_print("delete");?></a>    			                      
                	        <?php } ?>                               
                            </div>
                        </div>
                    </div>
            </div>
            <?php
				}	
				echo ossn_view_pagination($count);

 }