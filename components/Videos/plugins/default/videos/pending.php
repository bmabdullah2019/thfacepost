<div class="video-pending img-thumbnail">
	<div class="row">
		<div class="col-md-4">
			<img src="<?php echo ossn_site_url();?>components/Videos/images/encoding.gif" class="img-fluid img-responsive" />
		</div>
		<div class="col-md-8">
			<div class="video-conversion-title"><?php echo $params['video']->title;?></div>
			<?php if($params['video']->is_pending == 'yes'){ ?>
			<div class="alert alert-warning margin-top-10"><?php echo ossn_print('video:com:converion:cron:pending');?></div>
			<?php } ?>
			<?php if($params['video']->is_pending == 'conversion'){ ?>
			<div class="alert alert-success margin-top-10"><?php echo ossn_print('video:com:converion:cron:process');?></div>
			<?php } ?>
			<?php if($params['video']->is_pending == 'failed'){ ?>
			<div class="alert alert-danger margin-top-10"><?php echo ossn_print('video:com:converion:cron:failed');?></div>
			<?php } ?>
			<a href="<?php echo $params['video']->getDeleteURL();?>" class="btn btn-danger btn-sm"><?php echo ossn_print('video:com:delete');?></a>
			<div class="conversion margin-top-10">
				<label><?php echo ossn_print("video:com:converting"); ?></label>
				<div class="progress">
					<div class="progress-bar progress-bar-conversion bg-success" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
						<span>0%</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	setInterval(function() {
		Ossn.videoProgress(<?php echo $params['video']->guid;?>);
	}, 1000  * 10);
</script>