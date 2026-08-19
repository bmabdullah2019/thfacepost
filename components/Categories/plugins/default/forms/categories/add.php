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
 	<label><?php echo ossn_print('category:title');?></label>
    <input type="text" name="title" />
 </div>
 <div>
 	<label><?php echo ossn_print('category:description');?></label>
    <textarea name="description"></textarea>
 </div>
 <div>
 	<input type="submit" class="btn btn-sm btn-success" value="<?php echo ossn_print('save');?>" />
 </div>