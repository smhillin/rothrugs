<?php echo $header; ?>

<div id="content" class="container products-page category-page">
	<div class="row">
		<div class="col-sm-6">
			<img src="<?php echo $image; ?>" alt="<?php echo $product['name']; ?>" class="img-responsive" />
		</div>
		<div class="col-sm-6 text-right">
			<h3 class="red title"><?php echo $heading_title; ?></h3>
			<?php echo $description; ?>
		</div>
	</div>
	<div class="rugs_grid">
		<!--promo banner-->
		<div class="col-sm-4 rug promo-banner right">
			<?php if($banner['image']):?>
				<?php if(empty($banner['link'])): ?>
				<img src="<?php echo $banner['image'] ?>" />
				<?php else: ?>
				<a href="<?php echo $banner['link']; ?>">
					<img src="<?php echo $banner['image'] ?>" />
				</a>
				<?php endif; ?>
			<?php endif; ?>
		</div>
		<!--promo banner-->
		<?php foreach ($products as $product) { ?>  
		<div class="col-sm-4 rug">
			<div class="rug_img">
				<img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" />
			</div>
			<div class="rug_details">
				<p><?php echo $product['name']; ?></p>
				<p class="price">FROM <span><?php echo $product['lowest-price']; ?> USD</p>
				<div class="view-btn text-right"><a href="<?php echo $product['href']; ?>">View Details</a></div></a>
			</div>
		</div>
		<?php } ?>
	</div>
	<div class="row clear">
		<div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
		<div class="col-sm-6 text-right"><?php echo $results; ?></div>
	</div>
</div>

<?php echo $footer; ?>
