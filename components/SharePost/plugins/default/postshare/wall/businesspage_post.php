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
$object = ossn_get_object($params['post']->item_guid); 
$businesspage = get_business_page($object->owner_guid);

$contained_url = false;
if($object){
	$post = arrayObject((array)$object, 'OssnWall'); 
	$item = ossn_wallpost_to_item($post);
	
	if(preg_match('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $item['text'])){
			$contained_url = true;
	}
	$item = ossn_wall_view_template($item);
	$item = str_replace('activity-item-', 'activity-item-shared-', $item);
	$item = str_replace('ossn-wall-item-'.$params['post']->item_guid, 'ossn-wall-shared-item-'.$params['post']->guid, $item);
	$item = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $item);

	//remove comments initial
	$document = new DOMDocument('1.0', 'UTF-8');
	//https://ourcodeworld.com/articles/read/1483/how-to-keep-utf-8-special-characters-and-emojis-in-your-html-manipulated-by-phps-class-domdocument
	$document->loadHTML('<?xml encoding="UTF-8">'.$item, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
	$DomXPath = new DomXPath($document);
	$commentlists = $DomXPath->query("//*[contains(@class, 'comments-likes')]");
	if($commentlists){
		foreach($commentlists as $Item){
				$Item->parentNode->removeChild($Item);	
		}
		$item = utf8_decode($document->saveHTML());
	}	
}
ossn_trigger_callback('wall', 'load:item', $params);
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
            <?php
            } elseif($params['post']->type == 'user'){
				$owner = ossn_user_by_guid($params['post']->owner_guid);
				?>
                <a class="owner-link" href="<?php echo $params['user']->profileURL(); ?>"> <?php echo $params['user']->fullname; ?> </a> > 
                <a class="owner-link" href="<?php echo $owner->profileURL(); ?>"> <?php echo $owner->fullname; ?> </a>
            <?php } ?>
            <?php if($object->type == 'businesspage'){ ?>
                		<div class="ossn-wall-item-type"><?php echo ossn_print('post:shared:from:businesspage');?></div>
                        <a class="owner-link" href="<?php echo ossn_site_url("page/view/{$businesspage->guid}"); ?>"> <?php echo $businesspage->title; ?> </a>            

            <?php } ?>            
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
       <div class="post-contents post-share-wall-item">
                <?php
					if($object && $params['user']->guid != $object->owner_guid && $object->access == OSSN_FRIENDS && !$params['user']->isFriend($params['user']->guid, $object->owner_guid || $object && $object->access == OSSN_FRIENDS && !ossn_isLoggedin())){
							echo "<div class='post-share-unavailable'><i class='fa fa-exclamation-triangle'></i>".ossn_print('post:share:unavailable')."</div>";						
					} else {
						if($object){
							echo $item;
						} else {
							echo "<div class='post-share-unavailable'><i class='fa fa-exclamation-triangle'></i>".ossn_print('post:share:unavailable')."</div>";	
						}
					}
				?>
    	</div>
		<div class="comments-likes">
        	<?php if($params['post']->type == 'user' || ($is_group == true && $is_group_member == true)){ ?>
			<div class="menu-likes-comments-share">
				<?php echo ossn_view_menu('postextra', 'wall/menus/postextra');?>
			</div>
            <?php } ?>
         	<?php
      		  if (ossn_is_hook('post', 'likes')) {
          			  echo ossn_call_hook('post', 'likes', $params['post']);
        		}
      		  ?>           
			<div class="comments-list">
              <?php
          			  if (ossn_is_hook('post', 'comments')) {
                			$vars = array();
                			$vars['post'] =  $params['post'];
							if($is_group == true && $is_group_member == false){
								$vars['allow_comment'] = false;
							}
                			echo ossn_call_hook('post', 'comments', $vars);
           			   }
            		?>            				
			</div>
		</div>
        
         
	</div>
</div>
<?php
if($contained_url && isset($post->linkPreview) && !empty($post->linkPreview) && com_is_active('LinkPreview')) {
$item         = ossn_get_object($post->linkPreview);
$json_default = json_encode(array(
		'contents' => ossn_plugin_view('linkpreview/item_inner', array(
				'item' => $item,
				'guid' => $params['post']->guid
		))
));
if(isset($item->twitter_json) || !empty($item->twitter_json)) {
		$json = json_decode($item->twitter_json, true);
		if(isset($json['html'])) {
				$json = json_encode(array(
						'contents' => ossn_plugin_view('linkpreview/twitter_code', array(
								'html' => $json['html']
						))
				));
		} else {
				$json = $json_default;
		}
} else {
		$json = $json_default;
}
?>
<script>
		$(document).ready(function(){
					var $code = <?php echo $json;?>;
					$('#activity-item-shared-<?php echo $post->guid;?>').find('.post-contents').append($code['contents']);
		});
</script>
<?php
}