<?php
$users  = new OssnUser;
$data          = $users->searchUsers(array(
		'limit' => 12,
		'page_limit' => false,
		'order_by' => 'u.guid DESC',
));
if($data){
	foreach($data as $item){
		?>
          <li><img src="<?php echo $item->iconURL()->small;?>" /></li>
        <?php	
	}
}
