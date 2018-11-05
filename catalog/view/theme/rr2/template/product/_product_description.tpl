
		<div id="product-id" class="hidden" data-productid="<?php echo $product_id; ?>"></div>
		<div class="pics">
			<?php if ($thumb) { ?>
				<div id="mainPic">
					<img class='zoom' style="height: 500px;" data-zoom-image="<?php echo $popup; ?>" src="<?php echo $thumb; ?>" />
				</div>
			<?php } ?>
			<?php if($images) { ?>
				<?php
							 						$array_data =array();

              //$images = array_unique($images);
             ?>
				<div id="thumbs">
					<?php foreach ($images as $image) { 
									 if(!in_array(trim($image['popup']),$array_data)){
							 $array_data[] = trim($image['popup']); 
						?>
					<?php  if(!empty($image['popup'])): ?>
						<a href="#" data-image="<?php echo $image['popup']; ?>" data-zoom-image="<?php echo $image['popup']; ?>">
                            <img  src="<?php echo $image['thumb']; ?>" data-full="<?php echo $image['popup']; ?>" />
                        </a>
					<!-- <a href="#" data-image="<?php echo $image['popup']; ?>" data-zoom-image="<?php echo $image['popup']; ?>">
					<img  src="<?php echo $image['thumb']; ?>" data-full="<?php echo $image['popup']; ?>" /> -->
					<?php else : ?>
						<?php  if(!empty($image['thumb'])): ?>
						<a href="#">
							<img  src="<?php echo $image['thumb']; ?>">
						</a>
						<?php endif;?>
					<?php endif;?>

					<?php } } ?>
				</div>
			<?php } ?>
		</div>
		<!-- <div class="text">
			<h3>Description</h3>
			<h3><?php echo $heading_title; ?></h3>
			<?php echo $description; ?>
		</div> -->









