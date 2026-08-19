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

$entity = ossn_get_entity($params['post']->item_guid);
$event = ossn_get_event($entity->owner_guid);
if(!$event instanceof Events){
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
           <?php if ($params['user']->guid == $params['post']->owner_guid) { ?>
                <?php
				echo ossn_plugin_view('output/user/url', array(
						'user' => $params['user'],		
						'section' => 'wall',
				));
				?>
                <div class="ossn-wall-item-type"><?php echo ossn_print('event:wall:item:created');?></div>
            <?php
            }?>
			</div>
			<div class="post-meta">
				<span class="time-created"><?php echo ossn_user_friendly_time($params['post']->time_created); ?></span>
			</div>
		</div>

       <div class="post-contents event-wall-item">
                <?php
					echo ossn_plugin_view('event/pages/list', array(
						'list' => array(
									$event,				
						),												
					));
				?>
    	</div>
	<?php
		if($event->allowed_comments_likes){
			$vars['entity'] = ossn_get_entity($entity->guid);
			$vars['full_view'] = false;
			echo ossn_plugin_view('entity/comment/like/share/view', $vars);
		}
	?>    
	</div>
</div>
