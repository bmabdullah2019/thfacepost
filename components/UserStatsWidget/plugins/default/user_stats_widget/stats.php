<?php
$user = ossn_loggedin_user();
?>
<div class="ossn-widget user-stats-widget">
	<div class="widget-contents">
    	<div class="ossn-loading"></div>
		<div class="user-stats-item" data-type="friends">
			<div class="counter">-</div>
			<div class="icon"><i class="fas fa-users"></i></div>
			<div class="title"> <?php echo ossn_print('userstats:friends');?></div>
		</div>
		<div class="user-stats-item" data-type="comments">
			<div class="counter">-</div>
			<div class="icon"><i class="far fa-comments"></i></div>
			<div class="title"> <?php echo ossn_print('userstats:comments');?></div>
		</div>
		<div class="user-stats-item" data-type="reactions">
			<div class="counter">-</div>
			<div class="icon"><i class="far fa-heart"></i></div>
			<div class="title"> <?php echo ossn_print('userstats:reactions');?></div>
		</div>
		<div class="user-stats-item" data-type="posts">
			<div class="counter">-</div>
			<div class="icon"><i class="fas fa-bars"></i></div>
			<div class="title"> <?php echo ossn_print('userstats:posts');?></div>
		</div>
	</div>
</div>
<script>
$(document).ready(function() {
	Ossn.PostRequest({
		url: Ossn.site_url + 'action/userstats/get',
		callback: function(callback) {
			//means json is correct
			$('.user-stats-widget .ossn-loading').remove();
				if (callback['friends'] != '') {
					$('.user-stats-item[data-type="friends"] .counter').html(callback['friends']);
				}
				if (callback['comments'] != '') {
					$('.user-stats-item[data-type="comments"] .counter').html(callback['comments']);
				}
				if (callback['reactions'] != '') {
					$('.user-stats-item[data-type="reactions"] .counter').html(callback['reactions']);
				}
				if (callback['posts'] != '') {
					$('.user-stats-item[data-type="posts"] .counter').html(callback['posts']);
			}
		}
	});
});
</script>