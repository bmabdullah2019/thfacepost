<?php
/**
 * Open Source Social Network
 *
 * @package   Open Source Social Network
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright OpenTeknik LLC
 * @license   Open Source Social Network License (OSSN LICENSE)  http://www.opensource-socialnetwork.org/licence
 * @link      https://www.opensource-socialnetwork.org/
 */
 $site_key = ossn_site_settings('site_key');
 $site_key = hash('sha1', $site_key);
 $crontab = '*/30 * * * * wget -qO- '.ossn_site_url('stories/cron?key='.$site_key).' &> /dev/null';
?>
<div>
	<form>
    	<fieldset>
        	<div>
            	<p><?php echo ossn_print('stories:cron');?></p>
            	<input type="text" value="<?php echo $crontab;?>" disabled="disabled"/>
            </div>
        </fieldset>
    </form>
</div>