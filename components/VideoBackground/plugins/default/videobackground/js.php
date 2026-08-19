$(document).ready(function(){
	$video = '<div class="video-wrap hidden-xs hidden-sm"> <video poster="'+Ossn.site_url+'components/VideoBackground/video/cover.jpg" playsinline="playsinline" autoplay="autoplay" muted="muted" loop="loop"> <source src="'+Ossn.site_url+'components/VideoBackground/video/video.mp4" type="video/mp4"> </video> </div>';
    $width = $(window).width();
	if($('.ossn-layout-startup').length && $width > 1000){
		$('.ossn-inner-page').prepend($video);
        $('.ossn-layout-startup').css('background', 'none');
		$.getScript(Ossn.site_url  + "components/VideoBackground/vendors/backgroundVideo.js", function(){
				$('.video-wrap video').backgroundVideo({
  						$videoWrap: $('.ossn-layout-startup'),
  						$outerWrap: $(window),
 		    			$window: $(window),									   
 						pauseVideoOnViewLoss: false
				});										
		});
	}
});