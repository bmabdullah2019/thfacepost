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
	<label><?php echo ossn_print('mobilelogin:username'); ?></label>
    <input type="text" name="username" />
</div>

<div>
	<label><?php echo ossn_print('password'); ?></label>
    <input type="password" name="password" />
</div>
<div>
	<?php echo ossn_fetch_extend_views('forms/login2/before/submit'); ?>
<div>
<div>
    <input type="submit" value="<?php echo ossn_print('site:login');?>" class="btn btn-primary"/>
    <a href="<?php echo ossn_site_url('resetlogin'); ?>"><?php echo ossn_print('reset:login'); ?> </a>
</div>
