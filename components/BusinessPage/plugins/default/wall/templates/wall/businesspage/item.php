<?php
/**
 * Open Source Social Network
 *
 * @package   (openteknik.com).ossn
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (C) OpenTeknik LLC
 * @license   OPENTEKNIK  LLC, COMMERCIAL LICENSE, COMMERCIAL LICENSE https://www.openteknik.com/license/commercial-license-v1
 * @link      https://www.opensource-socialnetwork.org/
 */
$params['page'] = get_business_page($params['post']->owner_guid);
$image = $params['image'];
?>
<!-- wall item -->
<div class="ossn-wall-item" id="activity-item-<?php echo $params['post']->guid; ?>">
	<div class="row">
		<div class="meta">
			<img class="user-img" src="<?php echo $params['page']->photoURL('small'); ?>" width="50" height="50"/>
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
            <a class="owner-link" href="<?php echo $params['page']->getURL(); ?>"> <?php echo $params['page']->title; ?></a>
			</div>
			<div class="post-meta">
				<span class="time-created ossn-wall-post-time" onclick="Ossn.redirect('<?php echo("post/view/{$params['post']->guid}");?>');"><?php echo ossn_user_friendly_time($params['post']->time_created); ?></span>
                <span class="time-created"><?php echo $params['location']; ?></span>
                <?php
					echo ossn_plugin_view('privacy/icon/view', array(
							'privacy' => 2,
							'text' => '-',
							'class' => 'time-created',
					));
				?>                
			</div>
		</div>
		<div class="post-contents">
			<p><?php echo $params['text']; ?></p>
             <?php
            if (!empty($image)) {
                ?>
                <div class="ossn-wall-image-container"><img src="<?php echo $image; ?>"/></div>
            <?php } ?>
         
		</div>
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
	</div>
</div>
<!-- ./ wall item -->
