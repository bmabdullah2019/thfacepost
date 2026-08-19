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
 $com         = new OssnComponents;
 $comsettings = $com->getSettings('Videos'); 
 if(!$comsettings){
		$comsettings = new stdClass(); 
 }
 if(!isset($comsettings->ffmpeg_maxtime)){
		$comsettings->ffmpeg_maxtime = 30;
 }
 ?>
 <div id="video-errors" class="alert alert-danger hidden"></div>
 <div>
 	<label><?php echo ossn_print('video:com:title');?></label>
    <input type="text" name="title" id="video_title" />
 </div>
  <div>
 	<label><?php echo ossn_print('video:com:description');?></label>
    <textarea name="description" id="video_description"></textarea>
 </div>
  <div>
 	<label><?php echo ossn_print('video:com:file');?></label>
     <ul>
	     <li><?php echo ossn_print('video:com:maxfilesize', array(ossn_video_max_size()));?></li>
    	 <li><?php echo ossn_print('video:com:formats');?></li>
         <li><?php echo ossn_print('video:com:uploadtime', array($comsettings->ffmpeg_maxtime));?></li>
     </ul>    
    <input type="file" name="video" id="video_file" />
 </div> 
<?php if($params['container_type'] == 'group'){ ?>
<div class="margin-top-10">
	<label><?php echo ossn_print('video:group');?></label>
    <input type="text" value="<?php echo $params['group']->title;?>" disabled="disabled" readonly="readonly"/>
<div> 
<?php } ?>
<?php if($params['container_type'] == 'businesspage'){ ?>
<div class="margin-top-10">
	 <label><?php echo ossn_print('bpage');?></label>
     <input type="text" disabled="disabled" value="<?php echo $params['business']->title;?>" disabled="disabled" readonly="readonly" />     
<div> 
<?php } ?>
 <div class="video-submit margin-top-10">
	<input type="hidden" name="container_type" value="<?php echo $params['container_type'];?>" id="container_type" />
	<input type="hidden" name="container_guid" value="<?php echo $params['container_guid'];?>" id="container_guid" />
 	<a type="submit" class="btn btn-success btn-sm" id="video-add-button"><?php echo ossn_print('video:com:save');?></a>
 </div>
<div class="video-upload margin-top-20">
	<label><?php echo ossn_print("video:com:uploading"); ?></label>
	<div class="progress">
    	<div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
        	<span>0%</span>
    	</div>
	</div> 
</div>