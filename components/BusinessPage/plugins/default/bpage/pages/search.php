<?php
/**
 * Open Source Social Network
 *
 * @package   (openteknik.com).ossn
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (C) OpenTeknik LLC
 * @license   Open Source Social Network License (OSSN LICENSE)  http://www.opensource-socialnetwork.org/licence
 * @link      https://www.opensource-socialnetwork.org/
 */
$list = $params['pages'];
?>
<div class="business-page">
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
	}
	?>
</div>