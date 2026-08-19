<div class="ossn-shared-wrapper">
	<div class="row">
		<div class="meta">
			<img class="user-icon-small user-img" src="<?php echo $params['user']->iconURL()->small;?>">
			<div class="user">
				<?php
				echo ossn_plugin_view('output/user/url', array(
						'user' => $params['user'],		
						'section' => 'wall',
				));
				?>
			</div>
			<div class="post-meta">
				<span class="time-created ossn-wall-post-time" title="<?php echo date('d/m/Y', $params['time_created']);?>">
					<?php echo ossn_user_friendly_time($params['time_created']); ?>
                 </span>
			</div>            
			<div class="post-contents">
				<?php echo $params['contents'];?>
			</div>
		</div>
	</div>
</div>