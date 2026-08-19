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
$en = array(
	'videos' => 'Videos',
	'video:com' => 'Videos',
	'video:com:fields:requred' => 'Please fill all fields',
	'video:com:title' => 'Title',
	'video:com:description' => 'Description',
	'video:com:file' => 'Choose File',
	'video:com:save' => 'Save',
	'video:com:add' => 'Add video',
	'video:com:uploaded' => 'Video has been saved',
	'video:com:upload:failed' => 'Video upload failed', 
	'video:com:invalid' => 'Invalid Video',
	'video:com:deleted' => 'Video has been deleted',
	'video:com:delete:fail' => 'Video delete failed',
	'video:com:delete' => 'Delete',
	'video:com:edit' => 'Edit',
	'video:com:saved' => 'Video has been saved',
	'video:com:save:failed' => 'Failed to save the video',
	'video:com:wall:added' => 'Created a new video %s',
	'video:com:all' => 'All site videos',
	'video:com:mine' => 'My videos',
	'video:com:add' => 'Add video',
	'video:com:users:videos' => "%s's videos",
	
	'video:com:ffmpeg:path' => 'FFMPEG tool path',
	'video:com:ffmpeg:path:note' => 'If you are on linux server, ffmpeg tools usually resides in /usr/bin/ directory. Simply enter the directory where ffmpeg binary exists.',
	'video:ffmpeg:input:empty' => 'Please fill all fields',
	'video:ffmpeg:path:saved' => 'Path successfully saved',
	'video:ffmpeg:path:save:error' => 'Can not save the path, seems internal error.',
	
	'video:com:ffmpeg:found' => 'FFMPEG Binary found at %s',
	'video:com:ffmpeg:notfound' => 'FFMPEG Binary not found',
	
	'video:com:ffmpeg:status' => 'FFMPEG Status',
	'video:com:mp4:files' => 'Your video format is not supported',
	
	'video:com:upload:conversion:failed' => 'Video conversion failed',
	'video:com:uploading' => 'Uploading:',
	'video:com:converting' => 'Conversion:',
	'video:group' => 'Group',
	'video:com:upgrade:note' => 'Please upgrade your video component by clicking the button below',
	'ossn:notifications:comments:entity:file:video' => '%s commented on the video',
	'ossn:notifications:like:entity:file:video' => '%s liked your video',	
	
	'video:com:ffmpeg:compression:value' => 'Compression value (Default 35)',
	'video:com:ffmpeg:compress:text' => 'Value range from 1-50 lower value means best quality video size. We suggest you keep default to make less overhead in system memory.',
	'video:com:ffmpeg:time:value' => 'Maximum video time (Default 30s)',
	'video:com:ffmpeg:time:text' => 'Maximum time limit of video in seconds minimum 10 seconds. We suggest you keep it as small as possible. More time of video causes more RAM usage and makes the site slow. We suggest you not to make more than 120 seconds',
	'video:com:maxfilesize' => 'Maximum size %s MB',
	'video:com:formats' => 'Allow formats (.mov, .mp4)',
	'video:com:uploadtime' => 'Video will be shorten to utmost %s seconds',
	'video:com:converion:cron:failed' => 'The conversion of this video has been failed!',
	'video:com:converion:cron:process' => 'This video is being converted!',
	'video:com:converion:cron:pending' => 'This video is pending in queue for conversion! You can not edit/delete video while in queue for conversion.',
	'video:com:pending' => 'Pending',
	'video:com:cronjob' => 'Conversion CRON Job',
	'video:com:cronjob:text' => 'CRON job is required to convert the videos in backend. You can ask your hosting provider to add the cron job in your hosting account. You can replace /usr/bin/php with actual path for php binaries.',
);
ossn_register_languages('en', $en);
