<?php echo $header; ?>


<div class="container">
	
	<?php if($attention): ?>
	<div class="alert alert-info">
		<i class="fa fa-info-circle"></i> <?php echo $attention; ?>
		<button type="button" class="close" data-dismiss="alert">&times;</button>
	</div>
	<?php endif; ?>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
   <?php if ($success) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>

	<div id="content" class="<?php echo $class; ?>">
		<h2><?php echo $heading_title; ?></h2>
    	
        <div class="row shp_cart_main_in">
        	<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
	        	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
	            	<div class="shopcart_left">
	                	<!-- <h2>Shopping Cart</h2> -->
	                    <div class="shpcart_div_mn">
	                    <?php foreach($products as $key => $product) { ?>
	                    	<?php if($key == (count($products) -1) ) { ?>
	                        <div class="shpcrt_bx shpcrt_bx_end ">
	                        	<?php if($product['thumb']) { ?>
		                            <div class="shprdct_img">
			                            <a href="<?php echo $product['href']; ?>">
											<img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" />
										</a>
		                            </div>
	                            <?php } ?>
	                            <div class="shprdct_rght">
	                                <div class="shprdct_cnt">
	                                    <div class="shpcnt_tp">
	                                    	<div class="row">
	                                    		
	                                        <div class="name_product">Product: </div>
	                                        <div class="name_product1 spn_shp_prdct">
	                                        	<!-- <a href="<?php echo $product['href']; ?>"> -->
	                                        		<?php echo $product['name']; ?>
	                                        	<!-- </a> -->
	                                        	<?php if (!$product['stock']) { ?>
													<span class="text-danger">***</span>
												<?php } ?>
                                    		</div><br>
	                                    	</div>

	                                        Model: <span><?php echo $product['model']; ?></span>
	                                        <?php if ($product['option']) { ?>
												<?php foreach ($product['option'] as $option) { ?>
													<br />
													<?php echo $option['name']; ?>: <span><?php echo $option['value']; ?></span>
												<?php } ?>
											<?php } ?>
											<?php if ($product['reward']) { ?>
												<br />
												<span><?php echo $product['reward']; ?></span>
											<?php } ?>
											<?php if ($product['recurring']) { ?>
												<br />
												<span class="label label-info"><?php echo $text_recurring_item; ?></span> <span><?php echo $product['recurring']; ?></span>
											<?php } ?><br />
	                                        Unit Price: <span><?php echo $product['price']; ?></span>
	                                    </div>
	                                    <div class="shqnty_unt">
	                                        <div class="shqnty">
	                                            
	                                            <div class="qnty_dvx">
	                                            <span class="shqntxtx">Qty:</span>
	                                                <input type="text" class="shqnt1" name="quantity[<?php echo $product['key']; ?>]" value="<?php echo $product['quantity']; ?>">
	                                                <span class="input-group-btn">
														<button type="submit" data-toggle="tooltip" title="<?php echo $button_update; ?>" class="button-cart btn btn-primary glyphicon glyphicon-refresh"></button>
														<button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="button-cart btn btn-danger glyphicon glyphicon-remove" onclick="cart.remove('<?php echo $product['key']; ?>');"></button>
													</span>
	                                                <div class="clearfix"></div>
	                                            </div>
	                                        </div>
	                                        <div class="unt_ttl_tx">Unit X <?php echo $product['quantity']; ?> Total: <?php echo $product['total']; ?></div>
	                                        <div class="clearfix"></div>
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="clearfix"></div>
	                        </div>
	                        <?php }else{ ?>    
	                        <div class="shpcrt_bx">
	                        	<?php if($product['thumb']) { ?>
		                            <div class="shprdct_img">
			                            <a href="<?php echo $product['href']; ?>">
											<img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" />
										</a>
		                            </div>
	                            <?php } ?>
	                            <div class="shprdct_rght">
	                                <div class="shprdct_cnt">
	                                    <div class="shpcnt_tp">
	                                    	<div class="row">
	                                    		
	                                        <div class="name_product">Product: </div>
	                                        <div class="name_product1 spn_shp_prdct">
	                                        	<!-- <a href="<?php echo $product['href']; ?>"> -->
	                                        		<?php echo $product['name']; ?>
	                                        	<!-- </a> -->
	                                        	<?php if (!$product['stock']) { ?>
													<span class="text-danger">***</span>
												<?php } ?>
                                    		</div><br>
	                                    	</div>

	                                        Model: <span><?php echo $product['model']; ?></span>
	                                        <?php if ($product['option']) { ?>
												<?php foreach ($product['option'] as $option) { ?>
													<br />
													<?php echo $option['name']; ?>: <span><?php echo $option['value']; ?></span>
												<?php } ?>
											<?php } ?>
											<?php if ($product['reward']) { ?>
												<br />
												<span><?php echo $product['reward']; ?></span>
											<?php } ?>
											<?php if ($product['recurring']) { ?>
												<br />
												<span class="label label-info"><?php echo $text_recurring_item; ?></span> <span><?php echo $product['recurring']; ?></span>
											<?php } ?><br />
	                                        Unit Price: <span><?php echo $product['price']; ?></span>
	                                    </div>
	                                    <div class="shqnty_unt">
	                                        <div class="shqnty">
	                                            
	                                            <div class="qnty_dvx">
	                                            <span class="shqntxtx">Qty:</span>
	                                                <input type="text" class="shqnt1" name="quantity[<?php echo $product['key']; ?>]" value="<?php echo $product['quantity']; ?>">
	                                                <span class="input-group-btn">
														<button type="submit" data-toggle="tooltip" title="<?php echo $button_update; ?>" class="button-cart btn btn-primary glyphicon glyphicon-refresh"></button>
														<button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="button-cart btn btn-danger glyphicon glyphicon-remove" onclick="cart.remove('<?php echo $product['key']; ?>');"></button>
													</span>
	                                                <div class="clearfix"></div>
	                                            </div>
	                                        </div>
	                                        <div class="unt_ttl_tx">Unit X <?php echo $product['quantity']; ?> Total: <?php echo $product['total']; ?></div>
	                                        <div class="clearfix"></div>
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="clearfix"></div>
	                        </div>
	                    	<?php } ?>    
	                    <?php } ?>
	                    </div>
	                </div>
	            </form>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            	<div class="shopcart_right">
                	<div class="coupan_code_main">
                        <?php if($coupon || $voucher || $reward || $shipping) { ?>
						<div class="panel-group" id="accordion"><?php echo $coupon; ?><?php echo $voucher; ?><?php echo $reward; ?><?php echo $shipping; ?></div>
						<?php } ?>
                    </div>
                    <?php foreach ($totals as $total) { ?>
	                    <?php if($total['title'] !== 'Total') { ?>
		                    <div class="subtotal_dv">
		                    	<?php echo $total['title']; ?>:<span><?php echo $total['text']; ?></span>
		                    	<div class="clearfix"></div>
		                    </div>
	                    <?php }else{ ?>
		                    <div class="total_dvtx">
		                    	<?php echo $total['title']; ?>:<span><?php echo $total['text']; ?></span>
		                    	<div class="clearfix"></div>
		                    </div>
	                    <?php } ?>
					<?php } ?>
                    
                    <a href="<?php echo $checkout; ?>" class="btn btn-danger chkout"><?php echo $button_checkout; ?></a>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
            	<center><a href="./faq#return-policy" title="Return Policy">
					<img class="image-terms" src="catalog/view/theme/rr2/img/checkout.png" style="max-width:125%">
				</a></center>
            </div>
        </div>
    </div>
</div>
<?php echo $footer; ?> 
<link href='catalog/view/theme/rr2/roth/css/style_.css' type="text/css" rel="stylesheet"/>

<script type="text/javascript">

	var $w = $(window).scroll(function(){
		// console.log($w.scrollTop());
	    if ( $w.scrollTop() > 300 ) {   
	        
	        $('.shopcart_right').addClass('checkout');
	        console.log('sss');
	    } else {
	      	$('.shopcart_right').removeClass('checkout');
	    }
	});

	var numItems = $('.shpcrt_bx').length;
	for (var i = numItems; i >= 0; i--) {
		if (i == (numItems)) {
			$(this).addClass('aaaa');
			console.log(i);
		}
	}
</script>