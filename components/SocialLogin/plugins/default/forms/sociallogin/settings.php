<?php
/**
 * Open Source Social Network
 *
 * @package Open Source Social Network
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright 2014 iNFORMATIKON TECHNOLOGIES
 * @license   General Public Licence http://www.opensource-socialnetwork.org/licence
 * @link      http://www.opensource-socialnetwork.org/licence
 */
 $settings = social_login_cred();
?>

<fieldset class="fieldset">
	<legend><?php echo ossn_print('social:login:facebook');?></legend>
    <label><?php echo ossn_print('social:login:app:key');?></label>
    <input type="text" name="fb_consumer_key" value="<?php echo $settings->facebook->consumer_key;?>" />
    <label><?php echo ossn_print('social:login:app:secret');?></label>
    <input type="text" name="fb_consumer_secret"  value="<?php echo $settings->facebook->consumer_secret;?>" />
    <label><?php echo ossn_print('social:login:facebook:url');?></label>
    <input type="text" readonly value="<?php echo ossn_site_url('social_login/facebook');?>" />
    <div>
    	<label><?php echo ossn_print('social:login:button');?></label>
    	<?php echo ossn_plugin_view('input/dropdown', array(
					'name' => 'fb_enable',
					'value' => $settings->facebook->button,
					'options' => array(
						'yes' => ossn_print('social:login:enabled'),
						'no' => ossn_print('social:login:disabled'),
					),
		)); 
		?>
    </div>
    <input type="submit" value="<?php echo ossn_print('save');?>" class="btn btn-success btn-sm"/>
</fieldset>

<fieldset class="fieldset">
	<legend><?php echo ossn_print('social:login:twitter');?></legend>
    <label><?php echo ossn_print('social:login:consumer:key');?></label>
    <input type="text" name="tw_consumer_key" value="<?php echo $settings->twitter->consumer_key;?>" />
    <label><?php echo ossn_print('social:login:constumer:secret');?></label>
    <input type="text" name="tw_consumer_secret"  value="<?php echo $settings->twitter->consumer_secret;?>" />
    <label><?php echo ossn_print('social:login:twitter:callback');?></label>
    <input type="text" readonly value="<?php echo ossn_site_url('social_login/twitter');?>" />
    <div>
    	<label><?php echo ossn_print('social:login:button');?></label>
    	<?php echo ossn_plugin_view('input/dropdown', array(
					'name' => 'tw_enable',
					'value' => $settings->twitter->button,
					'options' => array(
						'yes' => ossn_print('social:login:enabled'),
						'no' => ossn_print('social:login:disabled'),
					),
		)); 
		?>
    </div>    
    <input type="submit" value="<?php echo ossn_print('save');?>" class="btn btn-success btn-sm"/>
</fieldset>

<fieldset class="fieldset">
	<legend><?php echo ossn_print('social:login:google');?></legend>
    <label><?php echo ossn_print('social:login:oauth:id');?></label>
    <input type="text" name="google_client_id" value="<?php echo $settings->google->client_id;?>" />
    <label><?php echo ossn_print('social:login:oauth:secret');?></label>
    <input type="text" name="google_client_secret"  value="<?php echo $settings->google->client_secret;?>" />
    <label><?php echo ossn_print('social:login:facebook:url');?></label>
    <input type="text" readonly value="<?php echo ossn_site_url('social_login/google');?>" />
    <div>
    	<label><?php echo ossn_print('social:login:button');?></label>
    	<?php echo ossn_plugin_view('input/dropdown', array(
					'name' => 'google_enable',
					'value' => $settings->google->button,
					'options' => array(
						'yes' => ossn_print('social:login:enabled'),
						'no' => ossn_print('social:login:disabled'),
					),
		)); 
		?>
    </div>        
    <input type="submit" value="<?php echo ossn_print('save');?>" class="btn btn-success btn-sm"/>
</fieldset>

<fieldset class="fieldset">
	<legend><?php echo ossn_print('social:login:apple');?></legend>
    <label><?php echo ossn_print('social:login:apple:clientid:service');?></label>
    <input type="text" name="apple_client_id" value="<?php echo $settings->apple->client_id ;?>" />
    <label><?php echo ossn_print('social:login:apple:teamid');?></label>
    <input type="text" name="apple_team_id"  value="<?php echo $settings->apple->team_id;?>" />
    <label><?php echo ossn_print('social:login:appple:keyfileid');?></label>
    <input type="text" name="apple_keyfile_id"  value="<?php echo $settings->apple->keyfile_id;?>" />
    <label><?php echo ossn_print('social:login:appple:keyfile');?></label>
    <input type="file" name="apple_keyfile"  />
    <?php if($key = social_login_apple_keyfile()){ ?>
    	<i class="badge bg-success"><?php echo ossn_print('social:login:appple:keyfilefound');?></i>
    <?php } ?>
    <div style="margin-top:10px;">
    <label><?php echo ossn_print('social:login:facebook:url');?></label>
    <input type="text" readonly value="<?php echo ossn_site_url('social_login/apple');?>" />
    </div>
    <div>
    	<label><?php echo ossn_print('social:login:button');?></label>
    	<?php echo ossn_plugin_view('input/dropdown', array(
					'name' => 'apple_enable',
					'value' => $settings->apple->button,
					'options' => array(
						'yes' => ossn_print('social:login:enabled'),
						'no' => ossn_print('social:login:disabled'),
					),
		)); 
		?>
    </div>     
    <input type="submit" value="<?php echo ossn_print('save');?>" class="btn btn-success btn-sm"/>
</fieldset>