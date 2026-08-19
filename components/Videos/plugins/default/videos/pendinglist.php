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
$videos = new Videos();
$owner  = ossn_loggedin_user()->guid;
$all    = $videos->getAll(array(
		'owner_guid'     => $owner,
		'entities_pairs' => array(
				array(
						'name'   => 'is_pending',
						'value'  => true,
						'wheres' => '([this].value = "yes" OR [this].value = "conversion" OR [this].value = "failed")',
				),
		),
));
$count = $videos->getAll(array(
		'count'          => true,
		'owner_guid'     => $owner,
		'entities_pairs' => array(
				array(
						'name'   => 'is_pending',
						'value'  => true,
						'wheres' => '([this].value = "yes" OR [this].value = "conversion" OR [this].value = "failed")',
				),
		),
));
if($all){
	echo "<div class='ossn-page-contents'>";
	foreach($all as $video){
		?>
        	<div class="row ossn-video-item">
            	<div class="col-md-4">
                       <a href="<?php echo $video->getURL();?>">
                    	<img src="<?php echo ossn_site_url('components/Videos/images/encoding.gif');?>" class="img-fluid img-responsive" />
                    </a>
                </div>
            	<div class="col-md-8">
            		<div class="video-meta-data">
                    	
                        <div class="video-title">
                        		<a href="<?php echo $video->getURL();?>"><?php echo $video->title;?></a>
                        </div>
                        <div class="description">
                        	<?php echo strl($video->description, 200); ?>
                        </div>
                        <div class="time-created"><?php echo ossn_user_friendly_time($video->time_created); ?></div>
                    </div>  
                    
                                      
                </div>
            </div>
        <?php	
	}
	echo ossn_view_pagination($count);
	echo "</div>";
 }