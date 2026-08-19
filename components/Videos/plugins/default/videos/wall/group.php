<?php
 $video = ossn_get_video($params['post']->item_guid);
 $entity = ossn_get_entities(array(
		'type' => 'object',
		'owner_guid' => $video->guid,
		'subtype' => 'file:video',
  ));
 $videolink = ossn_plugin_view('output/url', array(
		'href' => $video->getURL(),
		'text' => $video->title,
  ));
 

if(!isset($params['ismember'])){
    if ($group = ossn_get_group_by_guid($params['post']->owner_guid)) {
    	if ($group->isMember(NULL, ossn_loggedin_user()->guid)) {
      		$params['ismember'] = 1;
    	}
    }
}
//if user didn't exists not wall item #1110
if(!$params['user']){
		error_log("User didn't exists for wallpost with guid : {$params['post']->guid}");
		return;
}
?>
<div class="ossn-wall-item" id="activity-item-<?php echo $params['post']->guid; ?>">
	<div class="row">
		<div class="meta">
			<img class="user-img" src="<?php echo $params['user']->iconURL()->small; ?>" />
			<div class="post-menu">
				<div class="dropdown">
                 <?php
           			if (ossn_is_hook('wall', 'post:menu') && ossn_isLoggedIn()) {
                		$menu['post'] = $params['post'];
               			echo ossn_call_hook('wall', 'post:menu', $menu);
            			}
            		?>   
				</div>
			</div>
			<div class="user">
           <?php if ($params['user']->guid == $params['post']->poster_guid) { ?>
                <?php
				echo ossn_plugin_view('output/user/url', array(
						'user' => $params['user'],		
						'section' => 'wall',
				));
				?>
                <div class="ossn-wall-item-type"><?php echo ossn_print('video:com:wall:added', array($videolink));?></div>
            <?php } ?>
			</div>
			<div class="post-meta">
				<span class="time-created"><?php echo ossn_user_friendly_time($params['post']->time_created); ?></span>
			</div>
		</div>

       <div class="post-contents">
						<video id="videojs-wall-item-<?php echo $params['post']->guid;?>" class="ossn-video-player video-js vjs-theme-sea"  poster="<?php echo $video->getCoverURL();?>" controls="controls" preload="none">
							<source type="video/mp4" src="<?php echo $video->getFileURL();?>" />
						</video> 
<script>
$(document).ready(function () {
   var player = new Plyr('#videojs-wall-item-<?php echo $params['post']->guid;?>', {
      ratio: '16:9',
      fullscreen: {
         iosNative: true
      },
      resetOnEnd: true,
      settings: ['loop'],
   });
   player.on('play', (event) => {
      $('video').not('#videojs-wall-item-<?php echo $params['post']->guid;?>').trigger('pause');
   });
});
</script> 
						<div class="ossn-video-description">
							<p><?php echo $video->description;?></p>
						</div>
    	</div>
	<?php
		if($params['ismember'] == 1){
			$vars['entity'] = $entity[0];
			$vars['full_view'] = false;
			echo ossn_plugin_view('entity/comment/like/share/view', $vars);
		}
	?>    
	</div>
</div>
