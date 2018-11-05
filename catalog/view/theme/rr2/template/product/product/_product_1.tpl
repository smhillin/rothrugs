
	<div class="col-sm-6 product_descr">
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
	</div>
	<div class="col-sm-6 product_details">
		<h4>COLOR</h4>
		  <div class="list_carousel responsive" id="casorel_color_id">
		  <ul class="colors" id="casorel_color">
			  <?php if(!empty($product_same_color)): ?>
		
			
				<?php foreach($product_same_color as $item):?>
				<li class="color" data-value="<?php echo $item['product_id'] ?>">
				<a  class="colorbox"  ><img src="<?php echo $item['images'] ?>"></a></li>
				<?php 
				endforeach;?>
				<?php else: ?>
				<p>No item found</p>
				<?php endif;?>
		  </ul>
		<a id="prev_carousel" class="prev  glyphicon glyphicon-chevron-left" href="#" title="prev"></a>
		<a id="next_carousel" class="next  glyphicon glyphicon-chevron-right" href="#" title="next"></a>
      </div>
	<script type="text/javascript" language="javascript">
jQuery(document).ready(function($){
	$('#casorel_color').carouFredSel({
		auto: false,
		responsive: true,
		width: '100%',
		circular: false,
		infinite: false,
		prev: '#prev_carousel',
		next: '#next_carousel',
		swipe: {
		onTouch : true
		},
		items: {
			width: 71,
			height: 71,
			visible: {
			min: 1,
			max: 4
			}
		},
		scroll: {
			direction : 'left',    //  The direction of the transition.
			duration  : 500   //  The duration of the transition.
		}
	});
});
</script>
		<?php foreach($options as $o): ?>
		
		
		<?php if($o['name'] == 'Shape'): ?>
		<h4>SHAPE</h4>
		<div class="shapes option-<?php echo $o['product_option_id']; ?>" id="option[<?php echo $o['product_option_id']; ?>]">
			
			<?php foreach($o['product_option_value'] as $shape): ?>
			<div class="shape" data-value="<?php echo $shape['product_option_value_id']; ?>">
				<img data-value="<?php  echo $shape['option_value_id']?>" src="<?php echo $shape['image'] ?>">
			</div>
			<?php endforeach;?>
		</div>
		<?php elseif($o['name'] == 'Size'): ?>
			<div class="sizes table-responsive" id="option[<?php echo $o['product_option_id']; ?>]">
				<table class="table">
					<thead>
						<tr>
							<th>SIZE</th>
							<th>MSRP</th>
							<th>ROTH PRICE</th>
							<th>QUANTITY</th>
						</tr>
					<thead>
					<tbody class="text-center">
						<?php foreach($o['product_option_value'] as $size): ?>
							<tr>
								<td><?php echo $size['name']; ?></td>
								<td><?php echo $size['msrp']; ?></td>
								<td class="red"><?php echo $size['price']; ?></td>
								<td>	
									<div class="quantity">
										<select name="<?php echo $size['product_option_value_id']; ?>" data-price="<?php echo $size['price']; ?>">
											<?php for($i=0; $i <= $size['quantity']; $i++): ?>
											<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
											<?php endfor; ?>
										</select>
									</div>
								</td>
							</tr>
						<?php endforeach; ?>
					<tbody>
				</table>
			</div><!-- .sizes -->
		<?php endif; ?>
		<?php endforeach; ?>

		<div id="total">
			TOTAL: <span>$0.00</span>
		</div>
		<div class="add_btn pull-left">
			<button id="button-cart" disabled>Please choose quantity</button>
		</div>
		<div class="clearfix"></div>
		<div class="info">
			<div class="info_blocks">
				<a href="<?php echo $faq_shiping ?>"><img src="catalog/view/theme/rr2/img/shipping.png" /></a>
				<a href="<?php echo $faq_price ?>"><img src="catalog/view/theme/rr2/img/price.png" /></a>
				<a href="<?php echo $faq_tax ?>"><img src="catalog/view/theme/rr2/img/tax.png" /></a>
			</div>
			<div class="clearfix"></div>
			<div class="tip">
				<h3>DON’T FORGET THE RUG PAD!</h3>
				<p>A rug without a pad is like peanut butter without jelly!<br />
				<a href="#">View Options</a></p>
			</div>
		</div>
		  <?php if ($products) { ?>
      <h3><?php echo $text_related; ?></h3>
		  <div class="row">
        <?php $i = 0; ?>
        <?php foreach ($products as $product) { ?>
        <?php if ($column_left && $column_right) { ?>
        <?php $class = 'col-lg-6 col-md-6 col-sm-12 col-xs-12'; ?>
        <?php } elseif ($column_left || $column_right) { ?>
        <?php $class = 'col-lg-4 col-md-4 col-sm-6 col-xs-12'; ?>
        <?php } else { ?>
        <?php $class = 'col-lg-3 col-md-3 col-sm-6 col-xs-12'; ?>
        <?php } ?>
        <div class="<?php echo $class; ?>">
          <div class="product-thumb transition">
            <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
            <div class="caption">
              <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
              <?php if ($product['rating']) { ?>
              <div class="rating">
                <?php for ($i = 1; $i <= 5; $i++) { ?>
                <?php if ($product['rating'] < $i) { ?>
                <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                <?php } else { ?>
                <span class="fa fa-stack"><i class="fa fa-star fa-stack-1x"></i><i class="fa fa-star-o fa-stack-1x"></i></span>
                <?php } ?>
                <?php } ?>
              </div>
              <?php } ?>
              <?php if ($product['price']) { ?>
              <p class="price">
                <?php if (!$product['special']) { ?>
                <?php echo $product['price']; ?>
                <?php } else { ?>
                <span class="price-new"><?php echo $product['special']; ?></span> <span class="price-old"><?php echo $product['price']; ?></span>
                <?php } ?>
                <?php if ($product['tax']) { ?>
                <span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
                <?php } ?>
              </p>
              <?php } ?>
            </div>
          </div>
        </div>
        <?php if (($column_left && $column_right) && ($i % 2 == 0)) { ?>
        <div class="clearfix visible-md visible-sm"></div>
        <?php } elseif (($column_left || $column_right) && ($i % 3 == 0)) { ?>
        <div class="clearfix visible-md"></div>
        <?php } elseif ($i % 4 == 0) { ?>
        <div class="clearfix visible-md"></div>
        <?php } ?>
        <?php $i++; ?>
        <?php } ?>
      </div>
	  <?php } ?>
		  <?php echo $column_right; ?>
	</div>
	<input id="model_filter" value="<?php echo $model?>" type="hidden">
<input id="product_id_filter" value="<?php echo $product_id ?>" type="hidden">
<script>
	$(".zoom").elevateZoom({ zoomWindowWidth:300, zoomWindowHeight:300, scrollZoom : true, containLensZoom: true, gallery:'thumbs', cursor: 'pointer', galleryActiveClass: "active"}) 
			$(".zoom").bind("click", function(e) { var ez = $('.zoom').data('elevateZoom');
			$.fancybox(ez.getGalleryList()); 
			return false; });
	
	
</script>






