<?php
/**
 * Open Source Social Network
 *
 * @package Open Source Social Network
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright 2014-2017 OpenTeknik LLC
 * @license   Open Source Social Network License (OSSN LICENSE)  http://www.opensource-socialnetwork.org/licence
 * @link      https://www.opensource-socialnetwork.org/
 */
?>
<input type="text" name="username" class="form-control" placeholder="<?php echo ossn_print('username'); ?>" />
<input type="password" name="password" class="form-control" placeholder="<?php echo ossn_print('password'); ?>" />
<input type="submit" value="<?php echo ossn_print("site:login");?>" class="btn <?php echo oa_theme_get_settings('btnoutline');?>" />  
<div>  
<?php 
if(com_is_active('RememberLogin')){ 
	echo "<span class='remember-me'>";
	echo ossn_plugin_view('rememberlogin/checkbox');
	echo "</span>";
}
?>
	<a class="reset-login-topbar" href="<?php echo ossn_site_url('resetlogin'); ?>"><?php echo ossn_print('reset:password'); ?></a>
</div>
<style>
.remember-me {
    display: inline-block;
    margin-right: 20px;	
}
</style>