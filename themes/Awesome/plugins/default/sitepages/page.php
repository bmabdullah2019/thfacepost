<?php
/**
 * Open Source Social Network
 *
 * @package   (openteknik.com).ossn
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright 2014-2017 OpenTeknik LLC
 * @license   Open Source Social Network License (OSSN LICENSE)  http://www.opensource-socialnetwork.org/licence
 * @link      https://www.opensource-socialnetwork.org/
 */
if (!isset($params['title'])) {
    $params['title'] = '';
}
if (!isset($params['contents'])) {
    $params['contents'] = '';
}
?>
 <div class="oa-width-full ossn-site-pages-inner ossn-page-contents">
        <div class="ossn-site-pages-title">
            <?php echo $params['title']; ?>
        </div>
        <div class="ossn-site-pages-body">
            <?php echo $params['contents']; ?>
        </div>
    </div>
