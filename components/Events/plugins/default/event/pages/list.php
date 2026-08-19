<div class="events-list-modern">
        <?php
        if($params['list']){
            foreach($params['list'] as $item){ 
                if(!$item instanceof Events){ continue; }
        ?>
            <a href="<?php echo $item->profileURL(); ?>" class="event-list-card">
                <div class="event-card-image">
                    <img src="<?php echo $item->iconURL(); ?>" alt="Event Thumbnail" />
                </div>

                <div class="event-card-content">
                    <?php if(isset($item->is_finished) && $item->is_finished == 'yes'){ ?>
                        <span class="badge rounded-pill bg-danger text-white mb-2" style="width: fit-content; font-size: 10px;">
                            <?php echo ossn_print('event:finished'); ?>
                        </span>
                    <?php } ?>
                    
                    <h3><?php echo $item->title; ?></h3>
                    <p><?php echo strl($item->description, 180); ?></p>

                    <div class="event-card-meta">
                        <div class="meta-item">
                            <i class="fa fa-map-marker-alt" style="color:#3b82f6;"></i>
                            <span><?php echo $item->location; ?></span>
                        </div>
                        <div class="meta-item">
                            <i class="fa fa-calendar-day" style="color:#3b82f6;"></i>
                            <span><?php echo date("M d, Y", strtotime($item->date)); ?></span>
                        </div>
                        <div class="meta-item">
                            <i class="fa fa-clock" style="color:#3b82f6;"></i>
                            <span><?php echo $item->start_time; ?></span>
                        </div>
                        <div class="meta-item">
                            <i class="fa fa-ticket-alt" style="color:#10b981;"></i>
                            <span style="color:#10b981; font-weight:bold;">
                                <?php echo !empty($item->event_cost) ? $item->event_cost : ossn_print('event:free'); ?>
                            </span>
                        </div>
                    </div>

                    <div class="event-card-footer">
                        <span class="browse-link">
                            <?php echo ossn_print("event:browse"); ?> <i class="fa fa-chevron-right small"></i>
                        </span>
                    </div>
                </div>
            </a>
        <?php
            }
        }
		if(empty($params['list'])){ 
				echo "<div class='ossn-page-contents'>";
				echo ossn_print("event:no:result");
				echo "</div>";
		}		
		if(isset($params['count'])){
			echo ossn_view_pagination($params['count']); 
		}
        ?>
</div>