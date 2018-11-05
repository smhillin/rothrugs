<div class="clearfix"></div>
<section id="best-seller">
    <div class="container best_seller common-cont">
        <div class="heading">
            <h2 class="line"><?php echo $heading_title; ?></h2>
        </div>
        <h3></h3>
        <div class="row">
          <?php foreach ($products as $product) { ?>
          <div class="col-sm-4 col-xs-6 rug">
            <div class="product-thumb transition">
                <div class="image rug_img"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
                <?php if($product['sale_flag'] == 1) { ?>
                    <div class="offer">
                        <div class="ribbon-offer"><p>Sale</p></div>
                    </div>
                <?php } ?>
                    <div class="rug_details">
                        <div class="rug_title">
                            <a href="#">
                                <p><?php echo $product['name']; ?></p>
                            </a>
                        </div>
                        <p class="price">Starting at <span>$<?php echo $product['min_price']; ?></span></p>
                        <div class="view-btn text-center"><a href="<?php echo $product['href']; ?>">View Details</a></div>
                    </div>
              <!--<div class="caption">
                <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
                <p><?php echo $product['description']; ?></p>
                <?php if ($product['rating']) { ?>
                <div class="rating">
                  <?php for ($i = 1; $i <= 5; $i++) { ?>
                  <?php if ($product['rating'] < $i) { ?>
                  <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
                  <?php } else { ?>
                  <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
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
              <div class="button-group">
                <button type="button" onclick="cart.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $button_cart; ?></span></button>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart"></i></button>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-exchange"></i></button>
              </div>-->
            </div>
          </div>
          <?php } ?>
        </div>
    </div>
</section>
