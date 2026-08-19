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
 ?>
 <div>
 	<label><?php echo ossn_print('video:com:title');?></label>
    <input type="text" name="title" value="<?php echo $params['video']->title;?>" />
 </div>
  <div>
 	<label><?php echo ossn_print('video:com:description');?></label>
    <textarea name="description"><?php echo $params['video']->description;?></textarea>
 </div>
 <div>
 	<input type="hidden" name="guid" value="<?php echo $params['video']->guid;?>" />
 	<input type="submit" class="btn btn-success" value="<?php echo ossn_print('video:com:save');?>" />
 </div>