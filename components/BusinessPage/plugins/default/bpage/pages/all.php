<?php
/**
 * Open Source Social Network
 *
 * @package   (openteknik.com).ossn
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (C) OpenTeknik LLC
 * @license   OPENTEKNIK  LLC, COMMERCIAL LICENSE, COMMERCIAL LICENSE https://www.openteknik.com/license/commercial-license-v1
 * @link      https://www.opensource-socialnetwork.org/
 */
$page = new \Ossn\Component\BusinessPage\Page;
$list = $page->getPages();
$count = $page->getPages(array(
		'count' => true,							  
));
?>
<div class="business-page ossn-page-contents">
	<?php
	if($list){
			foreach($list as $item){ ?>
			    <div class="row list-item">
    					<div class="col-md-3">
                        	<img src="<?php echo $item->photoURL();?>" />
                        </div>
                        <div class="col-md-9">
                        	<a href="<?php echo $item->getURL();?>"><?php echo $item->title;?></a>
                            <p><?php echo $item->description;?></p>
                        </div>
   				 </div>
     <?php	
			}
			echo ossn_view_pagination($count);
	}
	?>
</div>