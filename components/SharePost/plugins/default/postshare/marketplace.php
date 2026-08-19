<?php
$product = $params['product'];
?>
<div class="marketplace-view-list-photos ossn-photos-wall">
            	<?php
					$photos = $product->getPhotos();
					if($photos){
						foreach($photos as $photo){
							$url = $product->photoURL($photo, false);
							$full = $product->photoURL($photo, true);
							echo "<a href='{$product->getURL()}'><img class='img-thumbnail ossn-photos-wall-item ossn-photo-wall-item-small' src='{$url}' /></a>";	
						}
					}
				?>
            </div>
            <div class="marketplace-metadata">
            		<div class="name"><?php echo $product->title?></div>
            		<div class="price"><?php echo ossn_print('marketplace:currenct:input', array($product->price, $product->currency));?></div>
                 <div class="marketplace-desc-list">
                       		<li><i class="fa fa-bars"></i><span><?php echo $product->getCategory()->title;?></span></li>                       
                       		<li><i class="fa fa-map-marker"></i><span><?php echo $product->location;?></span></li>                       
                       		<li>
                            	<i class="fa fa-archive"></i>
                            	<span><?php if($product->product_type == 'new'){ echo ossn_print('marketplace:type:new'); }  else { echo ossn_print('marketplace:type:used'); }?></span>
                            </li>
                  </div>         
            </div>
            
            <div class="marketplace-view-product">
            		<a href="<?php echo $product->getURL();?>" class="btn btn-sm btn-primary"><?php echo ossn_print('marketplace:browse');?></a>
            </div>