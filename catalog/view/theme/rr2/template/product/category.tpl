<?php echo $header; ?>

<div id="content" class="container products-page category-page">
	<div class="row">
		<?php if(isset($image)):?>
			<div class="col-sm-6">
				<img src="<?php echo $image; ?>" alt="<?php echo isset($product['name']) ? $product['name'] : ''; ?>" class="img-responsive" />
			</div>
		<?php endif; ?>
		<div class="col-sm-6 text-right">
			<h3 class="red title"><?php echo $heading_title; ?></h3>
			<?php echo $description; ?>
		</div>
	</div>
	<div class="rugs_grid">
		<!--promo banner-->
		<!-- <div class="col-sm-4 rug promo-banner right">
			<?php if($banner['image']):?>
				<?php if(empty($banner['link'])): ?>
				<img src="<?php echo $banner['image'] ?>" />
				<?php else: ?>
				<a href="<?php echo $banner['link']; ?>">
					<img src="<?php echo $banner['image'] ?>" />
				</a>
				<?php endif; ?>
			<?php endif; ?>
		</div> -->
		<!--promo banner-->
		<?php if(!empty($products)):?>
		<?php foreach ($products as $product) { ?>
		<div class="col-sm-4 rug">
			<div class="rug_img">
				<a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a>
			</div>
                        <?php if($product['sale_flag'] == 1) { ?>
                        <div class="offer">
                            <div class="ribbon-offer"><p>Sale</p></div>
                        </div>
                        <?php } ?>
			<div class="rug_details">
				<div  class="rug_title"><a href="<?php echo $product['href']; ?>">
					<p><?php echo $product['name']; ?></p>

				</a>
					</div>
				<p class="price">Starting At <span>$<?php echo $product['price']; ?></span></p>
				<div class="view-btn text-center"><a href="<?php echo $product['href']; ?>">View Details</a></div>
			</div>
		</div>
		<?php } ?>
		<?php else: ?>
		<div class="col-sm-8 rug">No product found</div>
		<?php endif;?>
	</div>
	<div class="row clear">
		<div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
		<div class="col-sm-6 text-right"><?php echo $results; ?></div>
	</div>
</div>

<?php echo $footer; ?>
