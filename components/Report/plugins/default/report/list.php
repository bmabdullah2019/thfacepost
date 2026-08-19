<?php
$report = new Report;
$list = $report->getAll();
$count = $report->getAll(array('count' => true));
?>
<div class="report-items">
	<div class="row">
    		<?php
			if($list){
					foreach($list as $item){
							$read = "";
							if(isset($item->is_read) && $item->is_read == true){
									$read = 'report-item-read';
							}
							$user = ossn_user_by_guid($item->reported_by);
							if(!$user){
									continue;
							}	
						?>
    		<div class="col-md-4">
    			<div class="report-item <?php echo $read;?>">
                	<p><strong><?php echo ossn_print('report:type');?></strong><span> <?php echo $item->container_type;?></span></p>
                	<p><strong><?php echo ossn_print('report:reason');?></strong><span> <?php echo $item->description;?></span></p>
                    <p><strong><?php echo ossn_print('report:reportedby');?></strong><span><a href="<?php echo $user->profileURL();?>"><?php echo $user->fullname;?></a></span></p>
                    <p><strong><?php echo ossn_print('report:time:created');?></strong><span><?php echo ossn_user_friendly_time($item->time_created);?></span></p>
                    <p>
                    	<?php if($item->container_type == 'message'){ ?>
                        	<a href="<?php echo ossn_site_url("action/report/data?guid={$item->guid}", true);?>" class="btn btn-default"><?php echo ossn_print('report:data');?></a>
                        <?php } ?>
                    	<?php if($item->container_type != 'message'){ ?>
                    	<a target="_blank" href="<?php echo ossn_site_url("action/report/view?guid={$item->guid}", true);?>" class="btn btn-primary"><?php echo ossn_print('report:view');?></a>					<?php } ?>
                        <a href="<?php echo ossn_site_url("action/report/read?guid={$item->guid}", true);?>" class="btn btn-warning"><?php echo ossn_print('report:read');?></a>
                        <a href="<?php echo ossn_site_url("action/report/delete?guid={$item->guid}", true);?>" class="btn btn-danger"><?php echo ossn_print('delete');?></a>
                        </p>
                </div>        
            </div>
            <?php }
			}
			echo ossn_plugin_view($count);
			?>
    </div>	
</div>