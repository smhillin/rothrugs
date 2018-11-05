<!--<div id="cart" class="btn-group btn-block">-->
  <div class="pull-right mini_sub_div">
    <?php if ($products || $vouchers) { ?>
    <div>
      <table class="table table-striped">
        <?php foreach ($products as $product) { ?>
        <tr>
          <td class="text-center"><?php if ($product['thumb']) { ?>
            <a href="<?php echo $product['href']; ?>"><img style="width:50px" src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-thumbnail" /></a>
            <?php } ?></td>
          <td class="text-left"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
            <?php if ($product['option']) { ?>
            <?php foreach ($product['option'] as $option) { ?>
            <br />
            - <small><?php echo $option['name']; ?> <?php echo $option['value']; ?></small>
            <?php } ?>
            <?php } ?>
            <?php if ($product['recurring']) { ?>
            <br />
            - <small><?php echo $text_recurring; ?> <?php echo $product['recurring']; ?></small>
            <?php } ?></td>
          <td class="text-right">x <?php echo $product['quantity']; ?></td>
          <td class="text-right"><?php echo $product['total']; ?></td>
          <!--<td class="text-center"><button type="button" onclick="cart.remove('<?php echo $product['key']; ?>');" title="<?php echo $button_remove; ?>" class="btn btn-danger btn-xs"><i class="fa fa-times"></i></button></td>-->
        </tr>
        <?php } ?>
        <?php foreach ($vouchers as $voucher) { ?>
        <tr>
          <td class="text-center"></td>
          <td class="text-left"><?php echo $voucher['description']; ?></td>
          <td class="text-right">x&nbsp;1</td>
          <td class="text-right"><?php echo $voucher['amount']; ?></td>
          <!--<td class="text-center text-danger"><button type="button" onclick="voucher.remove('<?php echo $voucher['key']; ?>');" title="<?php echo $button_remove; ?>" class="btn btn-danger btn-xs"><i class="fa fa-times"></i></button></td>-->
        </tr>
        <?php } ?>
      </table>
    </div>
    <div>
      <div>
        <table class="table table-striped">
          <?php foreach ($totals as $total) { ?>
          <tr>
            <td class="text-right" style="width:75%"><strong><?php echo $total['title']; ?>:</strong></td>
            <td class="text-right"><?php echo $total['text']; ?></td>
          </tr>
          <?php } ?>
        </table>
        <p class="text-center"><a class="btn btn-danger" id="close_mini"><strong>Continue Shopping</strong></a>&nbsp;&nbsp;&nbsp;<a class="btn btn-danger" href="<?php echo $cart; ?>"><strong><i class="fa fa-shopping-cart"></i> <?php echo $text_cart; ?></strong></a>&nbsp;&nbsp;&nbsp;<a class="btn btn-danger" href="<?php echo $checkout; ?>"><strong><i class="fa fa-share"></i> <?php echo $text_checkout; ?></strong></a></p>
      </div>
    </div>
    <?php } else { ?>
    <div>
      <p class="text-center"><?php echo $text_empty; ?></p>
    </div>
    <?php } ?>
  </div>
<script>
    $(document).ready(function(){
        $('#close_mini').click(function(){
            $('.minicart .pull-right').remove();
        });
        $(".minicart").hover(
            function () {
              $(this).removeClass("rem_keep");
            },
            function () {
              $(this).addClass("rem_keep");
             setTimeout(function(){$('.minicart.rem_keep').animate({top: '-1500px'}) } ,2000);
            }
        );
        
    });
</script>
<!--</div>-->
