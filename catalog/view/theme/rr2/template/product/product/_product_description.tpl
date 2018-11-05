
		<div id="product-id" class="hidden" data-productid="<?php echo $product_id; ?>"></div>
		<div class="pics">
			<?php if ($thumb) { ?>
				<div id="mainPic">
					<img class='zoom' data-zoom-image="<?php echo $popup; ?>" src="<?php echo $thumb; ?>" />
				</div>
			<?php } ?>
			<?php if($images) { ?>
				<div id="thumbs">
					<?php foreach ($images as $image) { ?>
					<?php  if(!empty($image['popup'])): ?>
					<a href="#" data-image="<?php echo $image['popup']; ?>" data-zoom-image="<?php echo $image['popup']; ?>">
					<img  src="<?php echo $image['thumb']; ?>" data-full="<?php echo $image['popup']; ?>" />
					<?php else : ?>
					<a href="#">
					<img  src="<?php echo $image['thumb']; ?>">
					<?php endif;?>
						
					</a>
					<?php } ?>
				</div>
			<?php } ?>
		</div>
		<div class="text">
			<h3>Description</h3>
			<h3><?php echo $heading_title; ?></h3>
			<?php echo $description; ?>
		</div>









