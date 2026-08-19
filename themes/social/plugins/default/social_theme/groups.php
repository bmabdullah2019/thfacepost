<?php
$groups  = new OssnGroup;
$data    = $groups->searchObject(array(
		'limit' => 5,
		'type' => 'user',
		'subtype' => 'ossngroup',
		'order_by' => 'o.guid DESC',
));
if($data){
	foreach($data as $item){
		?>
          <li><a href="<?php echo ossn_site_url("group/{$item->guid}");?>"><i class="fa fa-users"></i><?php echo $item->title;?></a></li>
        <?php	
	}
}
