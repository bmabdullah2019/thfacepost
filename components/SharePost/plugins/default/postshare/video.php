<video id="videojs-wall-item-<?php echo $params['itemguid'];?>" class="ossn-video-player video-js vjs-theme-sea" poster="<?php echo $params['video']->getCoverURL();?>" controls="controls" preload="none">
	<source type="video/mp4" src="<?php echo $params['video']->getFileURL();?>"></source>
</video> 
<script>
$(document).ready(function () {
   var player = new Plyr('#videojs-wall-item-<?php echo $params['itemguid'];?>', {
      ratio: '16:9',
      fullscreen: {
         iosNative: true
      },
      resetOnEnd: true,
      settings: ['loop'],
   });
   player.on('play', (event) => {
      $('video').not('#videojs-wall-item-<?php echo $params['itemguid'];?>').trigger('pause');
   });
});
</script>  
<div class="ossn-video-description">
	<p><?php echo $params['video']->description;?></p>
</div>
<?php