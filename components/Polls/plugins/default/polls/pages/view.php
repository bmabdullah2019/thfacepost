<?php
$poll = $params['poll'];
if($poll->container_type == 'group'){
		if(function_exists('ossn_get_group_by_guid')){
			$group =  ossn_get_group_by_guid($poll->container_guid);
			if(ossn_isLoggedin()){
				if($group && $group->membership == OSSN_PRIVATE && !$group->isMember($group->guid, ossn_loggedin_user()->guid)){
		 				ossn_trigger_message(ossn_print('polls:join:group'), 'error');
						redirect("group/{$group->guid}");				
				}
			} else {
					if($group && $group->membership == OSSN_PRIVATE){
			 				ossn_trigger_message(ossn_print('polls:join:group'), 'error');
							redirect("group/{$group->guid}");		
					}
			}
		}
}
?>
<div class="ossn-poll-main">
	<div class="ossn-poll-item-main-<?php echo $params['poll']->guid;?>">
		<?php 
			echo ossn_plugin_view('polls/pages/view_main', $params); 
			$entity = ossn_get_entity($params['poll']->poll_entity);
		?>
	</div>
<?php	
	$vars['entity'] = $entity;
	$vars['full_view'] = true;
	if($poll->container_type == 'businesspage'){
		$vars['businesspage'] = get_business_page($params['poll']->container_guid);	
	}
	echo ossn_plugin_view('entity/comment/like/share/view', $vars);
?>
</div>
<script>Ossn.poll(<?php echo $params['poll']->guid;?>);</script>
