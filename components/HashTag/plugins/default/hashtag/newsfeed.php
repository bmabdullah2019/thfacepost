<?php
$search = $params['trending'];
if($search){
		foreach($search as $item){
			?>
          	<a class="hashtag-trending-link" href="<?php echo ossn_site_url("hashtag/{$item->hashtag}");?>">
           	<div class="hashtag-trending-container">
            		<div class="d-inline-block icon-container">
                    	<div class="icon">
	                    	<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512"><path d="M384 160c-17.7 0-32-14.3-32-32s14.3-32 32-32H544c17.7 0 32 14.3 32 32V288c0 17.7-14.3 32-32 32s-32-14.3-32-32V205.3L342.6 374.6c-12.5 12.5-32.8 12.5-45.3 0L192 269.3 54.6 406.6c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3l160-160c12.5-12.5 32.8-12.5 45.3 0L320 306.7 466.7 160H384z"/></svg>
                            </div>
                    </div>
					<div class="d-inline-block">
                             <div class="hashtag-trending-title">#<?php echo $item->hashtag;?></div>
                   			 <div class="hashtag-trending-count"><?php echo ossn_print('hashtag:counter', array($item->total));?></div>
                    </div>
            
            </div>
            </a>
            <?php			
		}
}
?>
