<?php $i =0;  ?>
<!-- <div class="bot_content"> -->
      <?php foreach($options as $o):  ?>
        <?php if($o['name'] == 'Size'): ?>
          <div class="sizes table-responsive" id="option[<?php echo $o['product_option_id']; ?>]">
            <table class="table table-bordered table-product">
                <tr>
                    <th>Shape</th>
                    <th>Size</th>
                    <th>MSRP</th>
                    <th>Roth Price</th>
                    <th>Quantity</th>
                </tr>
                <?php foreach($o['product_option_value'] as $size): ?>
                  <tr>
                      <td><img src="<?php echo $size['image_sub']; ?>" width="40px;"></td>
                      <td><?php echo $size['name']; ?></td>
                      <td><?php echo $size['msrp']; ?></td>
                      <td class="price-table"><?php echo $size['price']; ?></td>
                      <td>
                        <div class="quantity" id="<?php echo $o['product_option_id']; ?>" data-product-id="<?php echo $product_id ?>">
                          <!-- <select name="<?php echo $size['product_option_value_id']; ?>"  data-product-color-id="<?php echo $o['product_option_color_id']['option_id'] ?>" data-product-color-value-id="<?php echo $o['product_option_color_id']['value_id']  ?>" data-price="<?php echo $size['price']; ?>">
                            <?php for($i=0; $i <= $size['quantity']; $i++): ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php endfor; ?>
                          </select> -->
                          <input type="text" name="<?php echo $size['product_option_value_id']; ?>"  data-product-color-id="<?php echo $o['product_option_color_id']['option_id'] ?>" data-product-color-value-id="<?php echo $o['product_option_color_id']['value_id']  ?>" data-price="<?php echo $size['price']; ?>" value="" placeholder="" size="5">
                        </div>
                      </td>
                  </tr>
                <?php endforeach ?>

            </table>

          </div>
        <?php endif; ?>
      <?php endforeach ?>
      <!-- </div> -->
      	<div class="row">
	  <div class="total col-xs-12" id="total">
            TOTAL:  <span class="color-red"> $0.00</span>
        </div>
        <div class="text-right add_btn col-xs-12">
            <!-- <a href="#" class="btn btn-danger">ADD TO CART</a> -->
            <button id="button-cart" class="btn btn-danger" disabled>Please choose quantity</button>
        </div>
		</div>

<script>
                if ($(window).width() > 361) {

	$(".zoom").elevateZoom({ zoomWindowWidth:300, zoomWindowHeight:300, scrollZoom : true, containLensZoom: true, gallery:'thumbs', cursor: 'pointer', galleryActiveClass: "active"})
		
			}
				$(".zoom").bind("click", function(e) { var ez = $('.zoom').data('elevateZoom');
			$.fancybox(ez.getGalleryList());
			return false; });


</script>