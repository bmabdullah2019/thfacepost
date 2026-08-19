<?php
/**
 * Open Source Social Network
 *
 * @package   (Informatikon.com).ossn
 * @author    OSSN Core Team <info@opensource-socialnetwork.org>
 * @copyright (C) OPENTEKNIK  LLC, COMMERCIAL LICENSE
 * @license   OPENTEKNIK  LLC, COMMERCIAL LICENSE, COMMERCIAL LICENSE https://www.openteknik.com/license/commercial-license-v1
 * @link      http://www.opensource-socialnetwork.org/licence
 */
$is_group = false;
$is_group_member = false; 
if($params['post']->type == 'group'){
	   $group = ossn_get_group_by_guid($params['post']->owner_guid);
	   $is_group = true;
  	   if($group){	
    		if ($group->isMember(NULL, ossn_loggedin_user()->guid)){
				$is_group_member = true;
    		}
	}	
}
$event = ossn_get_event($params['post']->item_guid); 
if(!$event instanceof Events){
   return;
}
?>
<div class="ossn-wall-item ossn-wall-item-<?php echo $params['post']->guid; ?>" id="activity-item-<?php echo $params['post']->guid; ?>">
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
           <?php
		   if ($params['user']->guid == $params['post']->owner_guid || $params['post']->type == 'group') { ?>
                <?php
				echo ossn_plugin_view('output/user/url', array(
						'user' => $params['user'],		
						'section' => 'wall',
				));
				?>
                <div class="ossn-wall-item-type"><?php echo ossn_print('post:shared:event:title');?></div>
            <?php
            } elseif($params['post']->type == 'user'){
				$owner = ossn_user_by_guid($params['post']->owner_guid);
				?>
                <?php
				echo ossn_plugin_view('output/user/url', array(
						'user' => $params['user'],	
						'class' => 'owner-link',
						'section' => 'wall',
				));
				?>  > 
                <?php
				echo ossn_plugin_view('output/user/url', array(
						'user' => $owner,	
						'class' => 'owner-link',
						'section' => 'wall',
				));
				?>  
            <?php } ?>
            <?php if($object->type == 'group'){ 
						$group = ossn_get_group_by_guid($object->owner_guid);
					?>
                <a class="owner-link" href="<?php echo ossn_group_url($group->guid);?>"> <?php echo $group->title; ?> </a>            
            <?php } ?>
			</div>
			<div class="post-meta">
				<span class="time-created ossn-wall-post-time" onclick="Ossn.redirect('<?php echo("post/view/{$params['post']->guid}");?>');"><?php echo ossn_user_friendly_time($params['post']->time_created); ?></span>
			</div>
		</div>
       <div class="post-contents post-share-wall-item">
				<?php 
				$contents = ossn_plugin_view('event/pages/list', array(
						'list' => array(
									$event,				
						),												
				));
				echo ossn_plugin_view('postshare/wrapper', array(
						'contents' => $contents,
						'time_created' => $event->time_created,
						'user' => ossn_user_by_guid($event->owner_guid),
				));
				?>                
    	</div>
		<!-- reactions comment -->
		<div class="comments-likes">
			<div class="menu-likes-comments-share">
				<?php echo ossn_view_menu('postextra', 'wall/menus/postextra');?>
			</div>
         	<?php
      		  if (ossn_is_hook('post', 'likes')) {
          			  echo ossn_call_hook('post', 'likes', $params['post']);
        		}
      		  ?>           
			<div class="comments-list">
              <?php
          			  if (ossn_is_hook('post', 'comments')){
                			$vars = array();
                			$vars['post'] =  $params['post'];						  
                			echo ossn_call_hook('post', 'comments', $vars);
           			   }
            		?>            				
			</div>
		</div>
<!-- reaction comments ./ -->       
	</div>
</div>