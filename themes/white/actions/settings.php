<?php
/**
 * Open Source Social Network
 *
 * @package   (openteknik.com).ossn
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (C) OpenTeknik LLC
 * @license   Open Source Social Network License (OSSN LICENSE)  http://www.opensource-socialnetwork.org/licence
 * @link      https://www.opensource-socialnetwork.org/
 */
$custom_settings = multipurpose_theme_get_custom_logos_bgs_setting();

$logo_dir = ossn_route()->themes . "white/logos_backgrounds/";
//[B] Logo upload failed #2369
if(!is_dir($logo_dir)){
		mkdir($logo_dir, 0755, true);	
}

$site = new OssnFile();
$site->setFile('logo_site');
$site->setExtension(array(
		'jpg',
		'png',
		'jpeg',
		'jfif',
		'gif',
		'webp',
));

if(isset($site->file['tmp_name']) && $site->typeAllowed()) {
		$file = $site->file['tmp_name'];
		$size = filesize($file);
		if($size > 0) {
				if($size > 500000) {
						//500KB
						ossn_trigger_message(ossn_print('theme:white:file:large'), 'error');
						redirect(REF);
				}
				$contents  = file_get_contents($file);
				$path_info = pathinfo($site->file['name']);
				$filename  = md5($site->file['name'] . time() . 'logo_site') . '.' . $path_info['extension'];
				$tosave    = ossn_route()->themes . "white/logos_backgrounds/logo_site_{$filename}";

				if(strlen($contents) > 0 && file_put_contents($tosave, $contents)) {
						//delete old one
						if(isset($custom_settings['logo_site'])) {
								$tounlink = ossn_route()->themes . "white/logos_backgrounds/{$custom_settings['logo_site']}";
								unlink($tounlink);
						}
						multipurpose_theme_set_custom_logos_bgs_setting('logo_site', $filename);

						$cache = ossn_site_settings('cache');
						if($cache == false) {
								$done = true;
						} else {
								$done = 2;
						}
				} else {
						$done = false;
				}
		}
}

$admin = new OssnFile();
$admin->setFile('logo_admin');
$admin->setExtension(array(
		'jpg',
		'png',
		'jpeg',
		'jfif',
		'gif',
		'webp',
));
if(isset($admin->file['tmp_name']) && $admin->typeAllowed()) {
		$file = $admin->file['tmp_name'];
		$size = filesize($file);
		if($size > 0) {
				if($size > 500000) {
						//500KB
						ossn_trigger_message(ossn_print('theme:white:file:large'), 'error');
						redirect(REF);
				}
				$contents  = file_get_contents($file);
				$path_info = pathinfo($admin->file['name']);
				$filename  = md5($admin->file['name'] . time() . 'logo_admin') . '.' . $path_info['extension'];
				$tosave    = ossn_route()->themes . "white/logos_backgrounds/logo_admin_{$filename}";

				if(strlen($contents) > 0 && file_put_contents($tosave, $contents)) {
						//delete old one
						if(isset($custom_settings['logo_admin'])) {
								$tounlink = ossn_route()->themes . "white/logos_backgrounds/logo_admin_{$custom_settings['logo_admin']}";
								unlink($tounlink);
						}
						multipurpose_theme_set_custom_logos_bgs_setting('logo_admin', $filename);

						$cache = ossn_site_settings('cache');
						if($cache == false) {
								$done = true;
						} else {
								$done = 2;
						}
				} else {
						$done = false;
				}
		}
}


$admin = new OssnFile();
$admin->setFile('screenhome_dark');
$admin->setExtension(array(
		'jpg',
		'png',
		'jpeg',
		'jfif',
		'gif',
		'webp',
));
if(isset($admin->file['tmp_name']) && $admin->typeAllowed()) {
		$file = $admin->file['tmp_name'];
		$size = filesize($file);
		if($size > 0) {
				if($size > 500000) {
						//500KB
						ossn_trigger_message(ossn_print('theme:white:file:large'), 'error');
						redirect(REF);
				}
				$contents  = file_get_contents($file);
				$path_info = pathinfo($admin->file['name']);
				$filename  = md5($admin->file['name'] . time() . 'screenhome_dark') . '.' . $path_info['extension'];
				$tosave    = ossn_route()->themes . "white/logos_backgrounds/screenhome_dark_{$filename}";

				if(strlen($contents) > 0 && file_put_contents($tosave, $contents)) {
						//delete old one
						if(isset($custom_settings['logo_admin'])) {
								$tounlink = ossn_route()->themes . "white/logos_backgrounds/screenhome_dark_{$custom_settings['screenhome_dark']}";
								unlink($tounlink);
						}
						multipurpose_theme_set_custom_logos_bgs_setting('screenhome_dark', $filename);

						$cache = ossn_site_settings('cache');
						if($cache == false) {
								$done = true;
						} else {
								$done = 2;
						}
				} else {
						$done = false;
				}
		}
}

$admin = new OssnFile();
$admin->setFile('screenhome_lite');
$admin->setExtension(array(
		'jpg',
		'png',
		'jpeg',
		'jfif',
		'gif',
		'webp',
));
if(isset($admin->file['tmp_name']) && $admin->typeAllowed()) {
		$file = $admin->file['tmp_name'];
		$size = filesize($file);
		if($size > 0) {
				if($size > 500000) {
						//500KB
						ossn_trigger_message(ossn_print('theme:white:file:large'), 'error');
						redirect(REF);
				}
				$contents  = file_get_contents($file);
				$path_info = pathinfo($admin->file['name']);
				$filename  = md5($admin->file['name'] . time() . 'screenhome_lite') . '.' . $path_info['extension'];
				$tosave    = ossn_route()->themes . "white/logos_backgrounds/screenhome_lite_{$filename}";

				if(strlen($contents) > 0 && file_put_contents($tosave, $contents)) {
						//delete old one
						if(isset($custom_settings['logo_admin'])) {
								$tounlink = ossn_route()->themes . "white/logos_backgrounds/screenhome_lite_{$custom_settings['screenhome_lite']}";
								unlink($tounlink);
						}
						multipurpose_theme_set_custom_logos_bgs_setting('screenhome_lite', $filename);

						$cache = ossn_site_settings('cache');
						if($cache == false) {
								$done = true;
						} else {
								$done = 2;
						}
				} else {
						$done = false;
				}
		}
}

$com_white_theme_mode = input('com_white_theme_mode');
if(!empty($com_white_theme_mode)){
		$SiteSettings = new OssnSite();
		if($SiteSettings->setSetting('com_white_theme_mode', $com_white_theme_mode)){
				$done = true;	
		}
}
$com_white_theme_members_widget = input('com_white_theme_members_widget');
if(!empty($com_white_theme_members_widget)){
		$SiteSettings = new OssnSite();
		if($SiteSettings->setSetting('com_white_theme_members_widget', $com_white_theme_members_widget)){
				$done = true;	
		}
}
$com_white_theme_mode_field_signup = input('whitetheme_mode_field_signup');
if(!empty($com_white_theme_mode_field_signup)){
		$SiteSettings = new OssnSite();
		if($SiteSettings->setSetting('whitetheme_mode_field_signup', $com_white_theme_mode_field_signup)){
				$done = true;	
		}
}
if($done === true){
	ossn_trigger_message(ossn_print('theme:goblue:logo:changed'));
	redirect(REF);	
} elseif($done == 2){
	//redirect and flush cache
	ossn_trigger_message(ossn_print('theme:goblue:logo:changed'));	
	$action = ossn_add_tokens_to_url("action/admin/cache/flush");
	redirect($action);	
} else {
	ossn_trigger_message(ossn_print('theme:goblue:logo:failed'), 'error');
	redirect(REF);		
}
