<?php
$poll = $params['poll'];
$entity = $params['entity'];
$businesspage = get_business_page($params['post']->owner_guid);

//if user didn't exists not wall item #1110
if(!$params['user']){
		error_log("User didn't exists for wallpost with guid : {$params['post']->guid}");
		return;
}
?>
<div class="ossn-wall-item" id="activity-item-<?php echo $params['post']->guid; ?>">
	<div class="row">
		<div class="meta">
			<img class="user-img" src="<?php echo $businesspage->photoURL('small'); ?>" />
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
				<a class="owner-link" href="<?php echo $businesspage->getURL(); ?>"> <?php echo $businesspage->title; ?> </a>
                <div class="ossn-wall-item-type"><?php echo ossn_print('polls:wall:created');?><a class="ms-1" href="<?php echo $params['poll']->getURL();?>"><?php echo $params['poll']->title;?></a></div>
            <?php
            } 
			?>
			</div>
			<div class="post-meta">
				<span class="time-created" onclick="Ossn.redirect('<?php echo("post/view/{$params['post']->guid}");?>');"><?php echo ossn_user_friendly_time($params['post']->time_created); ?></span>
				<?php
					echo ossn_plugin_view('privacy/icon/view', array(
							'privacy' => OSSN_PUBLIC,
							'text' => '-',
							'class' => 'time-created',
					));
				?>                      
			</div>
		</div>

       <div class="post-contents">
			<div class="ossn-poll-item-main-<?php echo $poll->guid;?>">
				<?php echo ossn_plugin_view('polls/pages/view_main', array(
						'poll' => $poll,														   
				));
				?>
			</div>
			<script>Ossn.poll(<?php echo $poll->guid;?>);</script>
    	</div>
	<?php
			$vars['entity'] = $entity;
			$vars['full_view'] = false;
			$vars['businesspage'] = $businesspage;
			echo ossn_plugin_view('entity/comment/like/share/view', $vars);
	?>    
	</div>
</div>