<?php
/**
 * Open Source Social Network
 *
 * @package Open Source Social Network
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright 2014-2017 OpenTeknik LLC
 * @license   Open Source Social Network License (OSSN LICENSE)  http://www.opensource-socialnetwork.org/licence
 * @link      https://www.opensource-socialnetwork.org/
 */
 	$feedback = new Feedback;
	$list = $feedback->getFeedbacks();
	$count = $feedback->getFeedbacks(array(
			'count' => true,									  
	));
 ?>
 <table class="table table-striped">
  <tr>
    <th scope="col"><?php echo ossn_print('feedback:from');?></th>
    <th scope="col"><?php echo ossn_print('feedback:message');?></th>
    <th scope="col"><?php echo ossn_print('feedback:rate');?></th>
    <th scope="col"><?php echo ossn_print('feedback:delete');?></th>
  </tr>
  <?php if($list){ 
  		foreach($list as $item){
				$user = ossn_user_by_guid($item->owner_guid);
				if(!$user){
					continue;	
				}
  ?>
  <tr>
    <td><a href="<?php echo $user->profileURL();?>"><?php echo $user->fullname;?></a></td>
    <td><?php echo $item->description;?></td>
    <td><?php echo $item->stars;?></td>
    <td><a class="badge bg-danger" href="<?php echo ossn_site_url("action/feedback/delete?guid={$item->guid}", true);?>"><?php echo ossn_print('feedback:delete');?></a></td>
  </tr>
  <?php
		}
  }
  ?>
</table>
<?php
echo ossn_view_pagination($count);
