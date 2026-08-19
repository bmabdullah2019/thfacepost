<div>
	<label><?php echo ossn_print('post:share:type');?></label>
    <?php echo ossn_plugin_view('input/dropdown', array(
				'name' => 'type',
				'id' => 'sharepost-select-type',
				'placeholder' => ossn_print('post:share:type'),
				'options' => share_post_types(),
	)); ?>
</div>
<div id="sharepost-friends">
    <?php 
	$friends = ossn_loggedin_user()->getFriends();
	if($friends){
			$users = array();
			foreach($friends as $user){
				$users[$user->guid] = $user->fullname;	
			}
	}
	echo ossn_plugin_view('input/dropdown', array(
				'name' => 'friend',
				'placeholder' => ossn_print('post:share:selectfriend'),
				'options' => $users,
	)); ?>    
</div>
<div id="sharepost-groups">
    <?php 
	$groups_user = ossn_get_user_groups(ossn_loggedin_user());
	if($groups_user){
			$groups = array();
			foreach($groups_user as $group){
				$groups[$group->guid] = $group->title;	
			}
	}
	echo ossn_plugin_view('input/dropdown', array(
				'name' => 'group',
				'placeholder' => ossn_print('post:share:selectgroup'),
				'options' => $groups,
	)); ?>    
</div>
<input type="hidden" name="guid" value="<?php echo $params['guid'];?>" />
<input type="hidden" name="share_type" value="<?php echo $params['type'];?>" />
<input type="submit" class="hidden" id="sharepost-cb" value="<?php echo ossn_print('save');?>" />
