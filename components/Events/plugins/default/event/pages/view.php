<?php
/**
 * Open Source Social Network
 *
 * @package Open Source Social Network
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (C) OPENTEKNIK  LLC, COMMERCIAL LICENSE
 * @license   OPENTEKNIK  LLC, COMMERCIAL LICENSE, COMMERCIAL LICENSE https://www.openteknik.com/license/commercial-license-v1
 * @link      http://www.opensource-socialnetwork.org/licence
 */
$owner          = ossn_user_by_guid($params['event']->owner_guid);
$default_status = false;
$loop_decision  = array();
foreach (ossn_events_relationship_default() as $item) {
		$data = ossn_relation_exists(ossn_loggedin_user()->guid, $params['event']->guid, $item);
		if(isset($data)) {
				$loop_decision[$item] = $data;
				if($data) {
						$default_status = $item;
				}
		}
}
$decision   = $loop_decision;
$interested = ossn_get_relationships(array(
		'to'    => $params['event']->guid,
		'type'  => 'event:interested',
		'count' => true,
));
$nointerested = ossn_get_relationships(array(
		'to'    => $params['event']->guid,
		'type'  => 'event:nointerested',
		'count' => true,
));
$going = ossn_get_relationships(array(
		'to'    => $params['event']->guid,
		'type'  => 'event:going',
		'count' => true,
));
$comment_wall = ossn_get_entities(array(
		'type'       => 'object',
		'subtype'    => 'event:wall',
		'owner_guid' => $params['event']->guid,
));
$comments = $comment_wall[0];
$url      = ossn_plugin_view('output/url', array(
		'href' => $owner->profileURL(),
		'text' => $owner->fullname,
));
?>

<div class="ossn-page-contents">
    <div class="event-details-card">
        <div class="title-section">
                <?php if(isset($params['event']->is_finished) && $params['event']->is_finished == 'yes'){ ?>
                    <span class="badge rounded-pill bg-danger text-white mb-1" style="font-size: 10px;"><?php echo ossn_print('event:finished'); ?></span>
                <?php } ?>
                <h2 class="mb-2"><?php echo $params['event']->title;?></h2>
                <small style="color:#888;"><i class="fa fa-user"></i> <?php echo ossn_print('event:created:by', [$url]);?></small>
        </div>        
        <div class="event-action-bar">
            <div class="interaction-area">
                <div class="btns-group">
                    <?php if(ossn_isLoggedin() && (isset($params['event']->is_finished) && $params['event']->is_finished != 'yes' || !isset($params['event']->is_finished)) ){
                        // GOING
                        if($default_status == 'event:going') {
                            echo '<button class="btn btn-selected-active" disabled><i class="fa fa-check"></i> '.ossn_print('event:going').'</button>';
                        } else { ?>
                            <a href="<?php echo ossn_site_url("action/event/decision?guid={$params['event']->guid}&type=going", true);?>" class="btn btn-primary"><?php echo ossn_print('event:going');?></a>
                        <?php }

                        // INTERESTED
                        if($default_status == 'event:interested') {
                            echo '<button class="btn btn-selected-active" disabled><i class="fa fa-star"></i> '.ossn_print('event:interested').'</button>';
                        } else { ?>
                            <a href="<?php echo ossn_site_url("action/event/decision?guid={$params['event']->guid}&type=interested", true);?>" class="btn btn-info"><?php echo ossn_print('event:interested');?></a>
                        <?php }

                        // NOT INTERESTED
                        if($default_status == 'event:nointerested') {
                            echo '<button class="btn btn-selected-active" disabled><i class="fa fa-times"></i> '.ossn_print('event:nointerested').'</button>';
                        } else { ?>
                            <a href="<?php echo ossn_site_url("action/event/decision?guid={$params['event']->guid}&type=nointerested", true);?>" class="btn btn-warning"><?php echo ossn_print('event:nointerested');?></a>
                        <?php }
                    } ?>
                </div>

                <?php if($params['event']->owner_guid == ossn_loggedin_user()->guid || (ossn_isLoggedin() && ossn_loggedin_user()->canModerate())){ ?>
                    <div class="admin-controls">
                        <a href="<?php echo ossn_site_url("event/edit/{$params['event']->guid}");?>" style="background:#10b981;" title="Edit"><i class="fa fa-pencil-alt"></i></a>
                        <a href="<?php echo ossn_site_url("action/event/delete?guid={$params['event']->guid}", true);?>" class="ossn-make-sure" style="background:#f43f5e;" title="Delete"><i class="fa fa-trash"></i></a>
                    </div>
                <?php } ?>
            </div>
        </div>

        <div class="event-body-content">
            <div class="event-upper-split">
                <div class="event-image-container">
                    <div class="date-badge-fixed">
                        <span class="m"><?php echo date("M", strtotime($params['event']->date));?></span>
                        <span class="d"><?php echo date("d", strtotime($params['event']->date));?></span>
                    </div>
                    <div class="event-fixed-image">
                        <a data-fancybox="gallery" href="<?php echo $params['event']->iconURL();?>">
                            <img src="<?php echo $params['event']->iconURL();?>" />
                        </a>
                    </div>
                </div>

                <div class="meta-info-panel">
                    <div class="info-item">
                        <i class="fa fa-calendar-alt"></i>
                        <div>
                            <span class="info-label"><?php echo ossn_print('event:date');?></span>
                            <span class="info-val"><?php echo date("l, F d, Y", strtotime($params['event']->date)); ?></span>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fa fa-clock"></i>
                        <div>
                            <span class="info-label"><?php echo ossn_print('event:price');?></span>
                            <span class="info-val"><?php echo $params['event']->start_time; ?> - <?php echo $params['event']->end_time; ?></span>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fa fa-map-marker-alt"></i>
                        <div>
                            <span class="info-label"><?php echo ossn_print('event:location');?></span>
                            <span class="info-val"><?php echo $params['event']->location; ?></span>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fa fa-tag"></i>
                        <div>
                            <span class="info-label"><?php echo ossn_print("event:price");?></span>
                            <span class="info-val" style="color:#10b981;"><?php echo !empty($params['event']->event_cost) ? $params['event']->event_cost : ossn_print('event:free'); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="event-description-section">
                <div class="description-header"><?php echo ossn_print('event:description');?></div>
                <div class="event-description-text">
                    <?php echo nl2br($params['event']->description);?>
                </div>
            </div>
        </div>

        <div class="event-stats-footer">
            <div class="stat-pill event-relation" data-guid="<?php echo $params['event']->guid;?>" data-type="1">
                <span class="stat-num"><?php echo $going;?></span>
                <span class="stat-txt"><?php echo ossn_print('event:going');?></span>
            </div>
            <div class="stat-pill event-relation" data-guid="<?php echo $params['event']->guid;?>" data-type="2">
                <span class="stat-num"><?php echo $interested;?></span>
                <span class="stat-txt"><?php echo ossn_print('event:interested');?></span>
            </div>
            <div class="stat-pill event-relation" data-guid="<?php echo $params['event']->guid;?>" data-type="3">
                <span class="stat-num"><?php echo $nointerested;?></span>
                <span class="stat-txt"><?php echo ossn_print('event:nointerested');?></span>
            </div>
        </div>
    </div>

    <div class="event-footer-comments">
        <?php
        if($params['event']->allowed_comments_likes){
            $vars['entity'] = ossn_get_entity($comments->guid);
            echo ossn_plugin_view('entity/comment/like/share/view', $vars);
        }
        ?>
    </div>
</div>