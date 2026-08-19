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
 $video = $params['video'];
 $entity = ossn_get_entities(array(
		'type' => 'object',
		'owner_guid' => $video->guid,
		'subtype' => 'file:video',
  ));
 $user = ossn_user_by_guid($video->owner_guid);
 if($video->container_type == 'businesspage' && function_exists('get_business_page')){
	 	$business = get_business_page($video->container_guid);
 }
 ?>
<div class="ossn-wall-item">
	<div class="row">
		<div class="meta">
        	<?php if($video->container_type != 'businesspage'){?>
				<img class="user-img" src="<?php echo $user->iconURL()->small; ?>" />
             <?php } else { ?>
				<img class="user-img" src="<?php echo $business->photoURL('small'); ?>" />            
             <?php } ?>
			<?php if(ossn_isLoggedin()){ ?>
			<?php $logged_user = ossn_loggedin_user(); ?>
			<div class="post-menu">
				<div class="dropdown">
					<a role="button" data-bs-toggle="dropdown" class="btn btn-link show" data-bs-target="#" aria-expanded="false">
					<i class="fa fa fa-ellipsis-h"></i>
					</a>
					<ul class="dropdown-menu dropdown-menu-end" role="menu" aria-labelledby="dropdownMenu" >
						<?php if(com_is_active('Report')){ ?>
						<li>
							<a class="dropdown-item post-control-report ossn-report-this" data-guid="<?php echo $video->guid;?>" data-type="video" href="javascript::void(0);"><?php echo ossn_print('report:this');?></a>
						</li>
						<?php } ?>
						<?php if($logged_user->guid == $video->owner_guid || $logged_user->canModerate()){ ?>
						<li>
							<a class="dropdown-item" href="<?php echo $video->getEditURL();?>"><?php echo ossn_print('video:com:edit');?></a>
						</li>
						<li>
							<a class="dropdown-item video-delete-btn" href="<?php echo $video->getDeleteURL();?>"><?php echo ossn_print('video:com:delete');?></a>
						</li>
						<?php } ?>
					</ul>
				</div>
			</div>
			<?php } ?>
			<div class="user">
				<?php
					if($video->container_type != 'businesspage'){
						echo ossn_plugin_view('output/user/url', array(
								'user' => $user,		
								'section' => 'wall',
						));
					} else {
						?>
                        <a class="owner-link" href="<?php echo $business->getURL(); ?>"> <?php echo $business->title; ?> </a>
                        <?php
					}
					?>
			</div>
			<div class="post-meta">
				<span class="time-created"><?php echo ossn_user_friendly_time($video->time_created); ?></span>
			</div>
		</div>
		<div class="post-contents">
			<strong><?php echo $video->title;?></strong>
			<video  playsinline preload="metadata" id="videojs-wall-item-<?php echo $video->guid;?>" class="margin-top-10 ossn-video-player video-js vjs-theme-sea"  poster="<?php echo $video->getCoverURL();?>" controls="controls" preload="none">
				<source type="video/mp4" src="<?php echo $video->getFileURL();?>" />
			</video>
<script>
$(document).ready(function () {
   var player = new Plyr('#videojs-wall-item-<?php echo $video->guid;?>', {
      ratio: '16:9',
      fullscreen: {
         iosNative: true
      },
      resetOnEnd: true,
      settings: ['loop'],
   });
   player.on('play', (event) => {
      $('video').not('#videojs-wall-item-<?php echo $video->guid;?>').trigger('pause');
   });
});
</script>                        
			<div class="ossn-video-description">
				<p><?php echo $video->description;?></p>
			</div>
		</div>
		<?php
			$vars['entity'] = $entity[0];
			$vars['full_view'] = false;
			if(isset($business)){
				$vars['businesspage'] = $business;
			}
			echo ossn_plugin_view('entity/comment/like/share/view', $vars);
			?>    
	</div>
</div>