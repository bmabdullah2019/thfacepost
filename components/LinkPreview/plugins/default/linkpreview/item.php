<?php
/**
 * Open Source Social Network
 *
 * @package   (openteknik.com).ossn
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright 2014-2017 OpenTeknik LLC
 * @license   OPENTEKNIK LLC, COMMERCIAL LICENSE  https://www.openteknik.com/license/commercial-license-v1
 * @link      https://www.openteknik.com/
 */
if(empty($params['post']->linkPreview) || !isset($params['post']->linkPreview)) {
		return;
}
$item         = ossn_get_object($params['post']->linkPreview);
$json_default = json_encode(array(
		'contents' => ossn_plugin_view('linkpreview/item_inner', array(
				'item' => $item,
				'guid' => $params['post']->guid
		))
));
$item = ossn_get_object($params['post']->linkPreview);

// Default behavior
$json_default = json_encode([
    'contents' => ossn_plugin_view('linkpreview/item_inner', [
        'item' => $item,
        'guid' => $params['post']->guid
    ])
]);

$json = $json_default; // Initialize with default

// TikTok JSON override (takes precedence)
if (!empty($item->tiktok_json)) {
    $tiktok = json_decode($item->tiktok_json, true);
    if (!empty($tiktok['html'])) {
        $json = json_encode([
            'contents' => ossn_plugin_view('linkpreview/twitter_code', [
                'html' => $tiktok['html']
            ])
        ]);
    }
}

// Twitter JSON override (takes precedence over TikTok if both exist)
if (!empty($item->twitter_json)) {
    $twitter = json_decode($item->twitter_json, true);
    if (!empty($twitter['html'])) {
        $json = json_encode([
            'contents' => ossn_plugin_view('linkpreview/twitter_code', [
                'html' => $twitter['html']
            ])
        ]);
    }
}
?>
<script>
		$(document).ready(function(){
					var $code = <?php echo $json;?>;
					$('#activity-item-<?php echo $params['post']->guid;?>').find('.post-contents').append($code['contents']);
		});
</script>