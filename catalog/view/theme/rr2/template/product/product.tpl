<?php echo $header; ?>
<?php
/*$URL         = "https://api.shortpixel.com/v2/reducer.php";
$APIKey     =  "VK4VG5YO1IUNyx20S2eA" ;
$Lossy       = "1";
$Resize     = "1";
$ImagesList[] = $thumb;

if($images) {
	foreach ($images as $image) {
		if(!empty($image['thumb'])){
			$ImagesList[] = $image['thumb'];
		}
	}
}

$ImagesList = array_map ('utf8_encode',  $ImagesList);
$Data       = json_encode(array(
     "plugin_version" => "104.236.36.204 1" ,
    "key" =>  $APIKey,
    "lossy"  => $Lossy,
     "urllist" => $ImagesList
));
$POSTArray  = array(
     'http' => array(
         'method' => 'POST' ,
        'header' => "Content-Type: application/json\r\n" . "Accept: application/json\r\n" . "Content-Length: " . strlen($Data),
        'content' => $Data //http_build_query($Data),
    )
);
$Context    = stream_context_create($POSTArray);
$Result     = file_get_contents($URL, false, $Context);
echo '<pre>';
print_r($Result);
echo '</pre>';*/
?>

<link href='catalog/view/theme/rr2/css/jquery-ui.css' type="text/css" rel="stylesheet"/>
<div class="container product-page">
    <div class="row">
        <div id="content">
            <div class="col-sm-12 mobile-only">
                <ul class="breadcrumb">
                    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                    <?php } ?>
                </ul>
            </div>
            <div class="col-sm-6 product_main">
                <ul class="breadcrumb">
                    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                    <?php } ?>
                </ul>
                <div class="row">
                    <div class="col-xs-12 product-name">
                        <h2><?php echo $heading_title; ?></h2>
                    </div>
                </div>
                <div class="row">
                    <div class="sku col-xs-6 sku-name">
                        Sku #<?php echo $heading_sku; ?>
                    </div>
                    <div class="col-xs-6 top mobile-only">
                        Starting at <span  class="color-red"><?php echo $priceto ?></span>
                    </div>
                </div>


                <!-- color start-->
                <div class="mobile-only row">
                    <div class="color col-xs-12">
                        <h3>COLOR</h3>
                    </div>

                    <div class="product_details col-xs-12">
                        <div class="list_carousel responsive" id="casorel_color_id_mobile">
                            <div class="begin-carousel">
                                <ul class="colors" id="casorel_color_mobile">
                                    <?php if(!empty($product_same_color)): ?>
                                    <?php foreach($product_same_color as $item):?>

                                    <li class="color <?php echo ($item['product_id'] == $product_id)?'active':''?>"
                                        data-product-option-id="option[<?php echo isset($item['product_option_id'])?$item['product_option_id']:''?>]"
                                        data-value="<?php echo $item['product_id'] ?>"
                                        data-product-option-value-id="<?php echo isset($item['product_option_value_id'])?$item['product_option_value_id']:''?>"
                                        >
                                        <a  class="colorbox"  ><img src="<?php echo $item['images'] ?>"></a></li>
                                    <?php
                                    endforeach;?>
                                    <?php else: ?>
                                    <p>No item found</p>
                                    <?php endif;?>
                                </ul>
                                <input type="" id="product_was_choose" value="">
                                <input type="hidden" id="color_option_choose">

                            </div>
                            <a id="prev_carousel_mobile" class="prev  glyphicon glyphicon-chevron-left" href="#" title="prev"></a>
                            <a id="next_carousel_mobile" class="next  glyphicon glyphicon-chevron-right" href="#" title="next"></a>
                            <script type="text/javascript" language="javascript">
                                jQuery(document).ready(function ($) {
                                    $('#casorel_color_mobile').carouFredSel({
                                        auto: false,
                                        responsive: true,
                                        circular: false,
                                        infinite: false,
                                        prev: '#prev_carousel_mobile',
                                        next: '#next_carousel_mobile',
                                        swipe: {
                                            onTouch: true
                                        },
                                        items: {
                                            height: 60,
                                            visible: {
                                                min: 1,
                                                max: 5
                                            }
                                        },
                                        scroll: {
                                            direction: 'left', //  The direction of the transition.
                                            duration: 500, //  The duration of the transition.
                                        }
                                    });
                                });
                            </script>
                        </div>
                    </div>
                </div>
                <!-- color end-->
                <div class="product_descr">
                    <div id="product-id" class="hidden" data-productid="<?php echo $product_id; ?>">
                    </div>
                    <div class="pics">
                        <?php if ($thumb) { ?>
                        <div id="mainPic">
                            <img class='zoom' data-zoom-image="<?php echo $popup; ?>" style="height: 500px;"  src="<?php echo $thumb; ?>" />
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
                </div>
                <div class="left-product only-desktop" >
                    <div class="tab-product" style="margin-top: 11px;">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#description" aria-controls="description" role="tab" data-toggle="tab">Product Description</a>
                            </li>
                            <li role="presentation">
                                <a href="#reviews" aria-controls="reviews" role="tab" data-toggle="tab">Customer Reviews</a>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="description">
                                <?php echo $description; ?>
                            </div>
                            <div role="tabpanel" class="tab-pane " id="reviews">
                                <div class="pull-left">
                                    <a href="#" id="write-review" class="btn btn-grey">Write a review</a>
                                </div>
                                <div class="box-show-review">
                                    <p style="font-size: 17px">
                                        <strong><?php echo $heading_title; ?></strong><br>
                                        Overall rating
                                    </p>
                                    <div class="over-review">
                                        <div class="out-review">
                                            <h3>0.0</h3>
                                            <span>Out of 5.0</span>
                                        </div>
                                        <ul class="num-review">
                                            <li>
                                                <label>Quality</label>
                                                <div class="star-review">
                                                    <span></span>
                                                    <span></span>
                                                    <span></span>
                                                    <span></span>
                                                    <span></span>
                                                </div>
                                            </li>
                                            <li>
                                                <label>Color</label>
                                                <div class="star-review">
                                                    <span></span>
                                                    <span></span>
                                                    <span></span>
                                                    <span></span>
                                                    <span></span>
                                                </div>
                                            </li>
                                            <li>
                                                <label>Value</label>
                                                <div class="star-review">
                                                    <span></span>
                                                    <span></span>
                                                    <span></span>
                                                    <span></span>
                                                    <span></span>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="clear"></div>

                                <div class="form-comment">
                                    <div id="review"></div>
                                    <div id="form-write-review" style="display:none;">
                                        <form class="form-horizontal">
                                            <h4>Product Review</h4>
                                            <div class="row form-group">
                                                <label class="col-sm-4"><span class="require">*</span>Review title :</label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="title" id="title" class="form-control" placeholder="Example:  Best rug ever!">
                                                </div>
                                            </div>

                                            <div class="row form-group">
                                                <label class="col-sm-4">
                                                    <span class="require">*</span>Quality rating<br>
                                                    <span class="require">*</span>Color rating<br>
                                                    <span class="require">*</span>Value rating</label>
                                                <div class="col-sm-8">
                                                    <!-- <div class="rate-comment">
                                                        <ul class="num-review">
                                                            <li>
                                                                <div class="star-review">
                                                                    <input type="radio" name="quality" value="1" />
                                                                    <input type="radio" name="quality" value="2" />
                                                                    <input type="radio" name="quality" value="3" />
                                                                    <input type="radio" name="quality" value="4" />
                                                                    <input type="radio" name="quality" value="5" />
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="star-review">
                                                                    <input type="radio" name="color" value="1" />
                                                                    <input type="radio" name="color" value="2" />
                                                                    <input type="radio" name="color" value="3" />
                                                                    <input type="radio" name="color" value="4" />
                                                                    <input type="radio" name="color" value="5" />
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="star-review">
                                                                    <input type="radio" name="value" value="1" />
                                                                    <input type="radio" name="value" value="2" />
                                                                    <input type="radio" name="value" value="3" />
                                                                    <input type="radio" name="value" value="4" />
                                                                    <input type="radio" name="value" value="5" />
                                                                </div>
                                                            </li>
                                                        </ul>
                                                        <div class="check-rate">
                                                            Would you recommend this product?<br>
                                                            <label class="radio-inline">
                                                                <input name="recommend" type="radio" value="1"> Yes
                                                            </label>
                                                            <label class="radio-inline">
                                                                <input name="recommend" type="radio" value="0"> No
                                                            </label>
                                                        </div>
                                                    </div> -->
                                                    <div class="rate-comment">
                                                        <div class="rate-form">
                                                            <div class="rate-input">
                                                                <input class="star v-quality" type="radio" name="rating-1" value="1"/>
                                                                <input class="star v-quality" type="radio" name="rating-1" value="2"/>
                                                                <input class="star v-quality" type="radio" name="rating-1" value="3"/>
                                                                <input class="star v-quality" type="radio" name="rating-1" value="4"/>
                                                                <input class="star v-quality" type="radio" name="rating-1" value="5"/>
                                                            </div>
                                                            <div class="rate-input ">
                                                                <input class="star v-color" type="radio" name="rating-2" value="1"/>
                                                                <input class="star v-color" type="radio" name="rating-2" value="2"/>
                                                                <input class="star v-color" type="radio" name="rating-2" value="3"/>
                                                                <input class="star v-color" type="radio" name="rating-2" value="4"/>
                                                                <input class="star v-color" type="radio" name="rating-2" value="5"/>
                                                            </div>
                                                            <div class="rate-input ">
                                                                <input class="star v-value" type="radio" name="rating-3" value="1"/>
                                                                <input class="star v-value" type="radio" name="rating-3" value="2"/>
                                                                <input class="star v-value" type="radio" name="rating-3" value="3"/>
                                                                <input class="star v-value" type="radio" name="rating-3" value="4"/>
                                                                <input class="star v-value" type="radio" name="rating-3" value="5"/>
                                                            </div>
                                                            <input type="hidden" name="quality" id="v-quality" value="">
                                                            <input type="hidden" name="color" id="v-color" value="">
                                                            <input type="hidden" name="value" id="v-value" value="">
                                                        </div>
                                                        <div class="check-rate">
                                                            Would you recommend this product?<br>
                                                            <label class="radio-inline">
                                                                <input type="radio"> Yes
                                                            </label>
                                                            <label class="radio-inline">
                                                                <input type="radio"> No
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <h4>Your Info</h4>
                                            <div class="row form-group">
                                                <label class="col-sm-4">
                                                    <span class="require">*</span>Review Summary :<br>
                                                    <span class="text-small">Product features and performance</span>
                                                </label>
                                                <div class="col-sm-8">
                                                    <textarea rows="5" name="summary" id="summary" class="form-control" placeholder="Example: the texture of the rug is soft and plusy as well as strong and durable. I often find myself laying on the floor!"></textarea>
                                                </div>
                                            </div>

                                            <div class="row form-group">
                                                <label class="col-sm-4"><span class="require">*</span>Your Nickname :</label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="nickname" id="nickname" class="form-control" placeholder="Please do not use your own name use letters and numbers only">
                                                </div>
                                            </div>

                                            <div class="row form-group">
                                                <label class="col-sm-4">Your Location :</label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="location" id="location" class="form-control" placeholder="Austin, TX">
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="pull-right" style="margin-right: 7px;">
                                                    <a type="button" role="<?php echo $product_id ?>" id="button-review" data-loading-text="Submit..." class="btn btn-danger">Submit</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div id="show-review"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-sm-6 quality ">
                <div class="row shipping-product only-desktop">
                    <div class="col-xs-4"><a href="<?php echo HTTP_SERVER.'faq?to=shipping' ?>"><img src="catalog/view/theme/rr2/img/ship1.jpg"></a></div>
                    <div class="col-xs-4"><a href="<?php echo HTTP_SERVER.'faq?to=gprice' ?>"><img src="catalog/view/theme/rr2/img/ship2.jpg"></a></div>
                    <div class="col-xs-4"><a href="<?php echo HTTP_SERVER.'faq?to=tax' ?>"><img src="catalog/view/theme/rr2/img/ship3.jpg"></a></div>
                </div>
                <div class="top-detail">
                    <div class="row">
                        <div class="over-review col-xs-6">
                            <div class="out-review">
                                <h3>0.0</h3>
                                <span>Out of 5.0</span>
                            </div>
                            <ul class="num-review">
                                <li>
                                    <label>Quality</label>
                                    <div class="star-review">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </div>
                                </li>
                                <li>
                                    <label>Color</label>
                                    <div class="star-review">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </div>
                                </li>
                                <li>
                                    <label>Value</label>
                                    <div class="star-review">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </div>
                                </li>
                            </ul>
                            <div class="clear"></div>
                        </div>

                        <div class="action-pro col-xs-6">
                            <div class="star-at">
                                Starting at <span  class="color-red"><?php echo $priceto ?></span>
                            </div>
                            <ul class="list-ac">
                                <li><a href="javascript:void:;" onclick="window.print();"><i class="fa fa-file-o"></i> Print</a></li>
                                <li><a href="#emailPopup" id="sendProductViaMail"><i class="fa fa-envelope-o"></i> Email</a></li>
                                <li><a href="javascript:void(0):;" id="shareNow"><i class="fa fa-share "></i> Share</a></li>

                                <li><a href="javascript:void(0)" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product_id; ?>');"><i class="fa fa-heart"></i> Add to wishlist</a></li>
                                <li></li>

                                <!-- <button type="button" data-toggle="tooltip" class="btn btn-default" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product_id; ?>');"><i class="fa fa-heart"></i></button> -->
                            </ul>
                            <div id="shareButtons" style="display: none">
                                <span class='st_sharethis_large' displayText='ShareThis'></span>
                                <span class='st_facebook_large' displayText='Facebook'></span>
                                <span class='st_twitter_large' displayText='Tweet'></span>
                                <span class='st_linkedin_large' displayText='LinkedIn'></span>
                                <span class='st_pinterest_large' displayText='Pinterest'></span>
                                <span class='st_email_large' displayText='Email'></span>
                            </div>
                            <div style="display: none">
                                <form class="form-horizontal" id="emailPopup" method="post" action="">
                                    <div class="col-md-12">
                                        <h4><?php echo $heading_title; ?></h4>
                                        <div class="form-group">
                                            <input type="text" id="your_name" name="your_name" class="form-control" placeholder="Your Name"/>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="sending_person_name" name="sending_person_name" placeholder="Sending Person Name"/>
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" type="email" id="sending_email_to" name="sending_email_to" placeholder="Sending Email to "/>
                                        </div>
                                        <div class="form-group">
                                            <textarea rows="8" id="sending_message" name="sending_message" class="form-control" style="resize: none;">
Product Name :- <?php echo $heading_title ; ?>
                                            </textarea>
                                        </div>

                                        <div class="form-group">
                                            <input type="submit" value="Email" class="btn btn-default" />
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="color only-desktop">
                    <h3>COLOR</h3>
                </div>
                <div class="product_details only-desktop">
                    <div class="list_carousel responsive" id="casorel_color_id">
                        <div class="begin-carousel">
                            <ul class="colors" id="casorel_color">
                                <?php if(!empty($product_same_color)): ?>
                                <?php foreach($product_same_color as $item):?>

                                <li class="color <?php echo ($item['product_id'] == $product_id)?'active':''?>"
                                    data-product-option-id="option[<?php echo isset($item['product_option_id'])?$item['product_option_id']:''?>]"
                                    data-value="<?php echo $item['product_id'] ?>"
                                    data-product-option-value-id="<?php echo isset($item['product_option_value_id'])?$item['product_option_value_id']:''?>"
                                    >
                                    <a  class="colorbox"  ><img src="<?php echo $item['images'] ?>"></a></li>
                                <?php
                                endforeach;?>
                                <?php else: ?>
                                <p>No item found</p>
                                <?php endif;?>
                            </ul>
                            <input type="" id="product_was_choose" value="">
                            <input type="hidden" id="color_option_choose">

                        </div>
                        <a id="prev_carousel" class="prev  glyphicon glyphicon-chevron-left" href="#" title="prev"></a>
                        <a id="next_carousel" class="next  glyphicon glyphicon-chevron-right" href="#" title="next"></a>
                        <script type="text/javascript" language="javascript">
                            jQuery(document).ready(function ($) {
                                $('#casorel_color').carouFredSel({
                                    auto: false,
                                    responsive: true,
                                    width: '100%',
                                    circular: true,
                                    infinite: true,
                                    prev: '#prev_carousel',
                                    next: '#next_carousel',
                                    swipe: {
                                        onTouch: true
                                    },
                                    items: {
                                        height: 60,
                                        visible: {
                                            min: 1,
                                            max: 5
                                        }
                                    },
                                    scroll: {
                                        direction: 'left', //  The direction of the transition.
                                        duration: 500, //  The duration of the transition.
                                    }
                                });
                            });
                        </script>
                    </div>
                </div>
                <div class="Choose_shape mobile-only">
                    <h4>Choose Shape</h4>
                </div>
                <div class="bot_content">
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
                            <tbody class="text-center">
                                <?php foreach($o['product_option_value'] as $size): ?>
                                <tr class="tr-sizes">
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
                                            <input type="number" name="<?php echo $size['product_option_value_id']; ?>"  data-product-color-id="<?php echo $o['product_option_color_id']['option_id'] ?>" data-product-color-value-id="<?php echo $o['product_option_color_id']['value_id']  ?>" data-price="<?php echo $size['price']; ?>" value="" placeholder="" size="5">
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach ?>
                            <tbody>
                        </table>

                    </div>
                    <?php endif; ?>
                    <?php endforeach ?>
                    <div class="row">
                        <div class="total col-xs-12" id="total">
                            TOTAL:  <span class="color-red"> $0.00</span>
                        </div>
                        <div class="text-right add_btn col-xs-12">
                            <!-- <a href="#" class="btn btn-danger">ADD TO CART</a> -->
                            <button id="button-cart" class="btn btn-danger" disabled>Please choose quantity</button>
                        </div>
                    </div>

                </div>


                <div class="dont-forget">
                    <h3>DON’T FORGET THE RUG PAD!</h3>
                    A rug without a pad is like peanut butter without jelly!<br>
                    <!-- <a id="viewoption" href="/index.php?route=product/search&filter_style_id=47&find=true">VIEW RUG PAD OPTIONS</a>-->
                    <a href="./rugs/rug-pads/">VIEW RUG PAD OPTIONS</a>
                    <div id="showoption" style="display:none;">
                        <div class="relate-product">
                            <ul class="row">
                                <?php if ($rothpad_products): ?>
                                <?php foreach ($rothpad_products as $key => $re_product): ?>
                                <li class="col-xs-4 col-sm-4">
                                    <a href="<?php echo $re_product['href'] ?>" title="<?php echo $re_product['name'] ?>">
                                        <img src="<?php echo $re_product['thumb'] ?>">
                                        <?php echo $re_product['name'] ?><br>
                                        <?php echo $re_product['price'] ?>
                                        <p class="color-red"><a href="<?php echo $re_product['href'] ?>" title="<?php echo $re_product['name'] ?>">View  Item</a></p>
                                    </a>
                                </li>
                                <?php endforeach ?>
                                <?php endif ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div id="accordion" class="mobile-only">
                    <h3>PRODUCT DESCRIPTION</h3>
                    <div>
                        <?php echo $description; ?>
                    </div>
                    <h3>CUSTOMER REVIEW</h3>
                    <div>

                        <div class="box-show-review-mobile">
                            <p style="font-size: 17px">
                                <strong><?php echo $heading_title; ?></strong><br>
                                Overall rating
                            </p>
                            <div class="over-review-mobile">
                                <div class="out-review">
                                    <h3>4.9</h3>
                                    <span>Out of 5.0</span>
                                </div>
                                <ul class="num-review">
                                    <li>
                                        <label>Quality</label>
                                        <div class="star-review">
                                            <span class="pull-star"></span>
                                            <span class="pull-star"></span>
                                            <span class="pull-star"></span>
                                            <span class="pull-star"></span>
                                            <span></span>
                                        </div>
                                    </li>
                                    <li>
                                        <label>Color</label>
                                        <div class="star-review">
                                            <span class="pull-star"></span>
                                            <span class="pull-star"></span>
                                            <span class="pull-star"></span>
                                            <span class="pull-star"></span>
                                            <span class="pull-star"></span>
                                        </div>
                                    </li>
                                    <li>
                                        <label>Value</label>
                                        <div class="star-review">
                                            <span class="pull-star"></span>
                                            <span class="pull-star"></span>
                                            <span class="pull-star"></span>
                                            <span class="pull-star"></span>
                                            <span class="pull-star"></span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="pull-left">
                            <a href="#" id="write-review-mobile" class="btn btn-grey">WRITE A REVIEW</a>
                        </div>
                        <div class="clear"></div>

                        <div class="form-comment">
                            <div id="review_mobile"></div>
                            <div id="form-write-review-mobile" style="display:none;">
                                <form class="form-horizontal">
                                    <h4>Product Review</h4>
                                    <div class="row form-group">
                                        <label class="col-sm-4"><span class="require">*</span>Review title :</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="title_mobile" id="title" class="form-control" placeholder="Example:  Best rug ever!">
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col-sm-8">


                                            <label> <span class="require">*</span>Quality rating</label><br>
                                            <div class="rate-input">
                                                <input class="star v-quality_mobile" type="radio" name="rating-1" value="1"/>
                                                <input class="star v-quality_mobile" type="radio" name="rating-1" value="2"/>
                                                <input class="star v-quality_mobile" type="radio" name="rating-1" value="3"/>
                                                <input class="star v-quality_mobile" type="radio" name="rating-1" value="4"/>
                                                <input class="star v-quality_mobile" type="radio" name="rating-1" value="5"/>
                                            </div>
                                            <label><span class="require">*</span>Color rating</label><br>
                                            <div class="rate-input ">
                                                <input class="star v-color_mobile" type="radio" name="rating-2" value="1"/>
                                                <input class="star v-color_mobile" type="radio" name="rating-2" value="2"/>
                                                <input class="star v-color_mobile" type="radio" name="rating-2" value="3"/>
                                                <input class="star v-color_mobile" type="radio" name="rating-2" value="4"/>
                                                <input class="star v-color_mobile" type="radio" name="rating-2" value="5"/>
                                            </div>
                                            <label><span class="require">*</span>Value rating</label><br>
                                            <div class="rate-input ">
                                                <input class="star v-value_mobile" type="radio" name="rating-3" value="1"/>
                                                <input class="star v-value_mobile" type="radio" name="rating-3" value="2"/>
                                                <input class="star v-value_mobile" type="radio" name="rating-3" value="3"/>
                                                <input class="star v-value_mobile" type="radio" name="rating-3" value="4"/>
                                                <input class="star v-value_mobile" type="radio" name="rating-3" value="5"/>
                                            </div>
                                            <input type="hidden" name="quality_mobile" id="v-quality_mobile" value="">
                                            <input type="hidden" name="color_mobile" id="v-color_mobile" value="">
                                            <input type="hidden" name="value_mobile" id="v-value_mobile" value="">
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="rate-comment">

                                                <div class="check-rate">
                                                    Would you recommend this product?<br>
                                                    <label class="radio-inline">
                                                        <input type="radio"> Yes
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio"> No
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <h4>Your Info</h4>
                                    <div class="row form-group">
                                        <label class="col-sm-4">
                                            <span class="require">*</span>Review Summary :<br>
                                            <span class="text-small">Product features and performance</span>
                                        </label>
                                        <div class="col-sm-8">
                                            <textarea rows="5" name="summary_mobile" id="summary" class="form-control" placeholder="Example: the texture of the rug is soft and plusy as well as strong and durable. I often find myself laying on the floor!"></textarea>
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <label class="col-sm-4"><span class="require">*</span>Your Nickname :</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="nickname_mobile" id="nickname" class="form-control" placeholder="Please do not use your own name use letters and numbers only">
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <label class="col-sm-4">Your Location :</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="location_mobile" id="location" class="form-control" placeholder="Austin, TX">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="pull-right" style="margin-right: 7px;">
                                            <a type="button" role="<?php echo $product_id ?>" id="button-review-mobile" data-loading-text="Submit..." class="btn btn-danger">Submit</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div id="show-review-mobile"></div>
                        </div>
                    </div>

                </div>
                <?php if (isset($related_products)): ?>
                    <div class="relate-product">
                        <h3>Related Options</h3>
                        <ul class="row">
                            <?php if ($related_products): ?>
                            <?php foreach ($related_products as $key => $re_product): ?>
                            <li class="col-xs-4 col-sm-4">
                                <?php 
                                    $RothRug = strpos($re_product['name'], 'vvvvv');
                                ?>
                                <a href="<?php echo !empty($RothRug) ? 'rugs/?page_id=208/' : $re_product['href'] ?>" title="<?php echo $re_product['name'] ?>">
                                    <img src="<?php echo $re_product['thumb'] ?>">
                                    <p class="related-name"><?php echo $re_product['name'] ?></p>
                                    <p class="related-name"> Starting at <span  class="color-red"><?php echo $priceto ?></span></p>
                                    <p class="color-red "><a href="<?php echo $re_product['href'] ?>" title="<?php echo $re_product['name'] ?>">View  Item</a></p>
                                </a>
                            </li>
                            <?php endforeach ?>
                            <?php endif ?>

                        </ul>
                    </div>
                <?php endif ?>
            </div>

            <?php echo $column_right; ?>
            <input id="model_filter" value="<?php echo $model?>" type="hidden">
            <input id="product_id_filter" value="<?php echo $product_id ?>" type="hidden">
            <script>
                if ($(window).width() > 361) {
                    $(".zoom").elevateZoom({zoomWindowWidth: 300, zoomWindowHeight: 300, scrollZoom: true, containLensZoom: true, gallery: 'thumbs', cursor: 'pointer', galleryActiveClass: "active"})


                }
				 $(".zoom").bind("click", function (e) {
                        var ez = $('.zoom').data('elevateZoom');
                        $.fancybox(ez.getGalleryList());
                        return false;
                    });
            </script>


        </div>


    </div>
</div>
<script type="text/javascript">
    
    // $( window ).load(function() {
    //     $('#thumbs').click(function(event) {
    //         return false;
    //     });
    // });
    $('#thumbs').click(function(event) {
            return false;
        });
</script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.0/jquery.validate.js"></script>
<script type="text/javascript" src="catalog/view/javascript/product.js"></script>
<script type="text/javascript"><!--
$(window).load(function(){

    $(document).on('click', '#thumbs', function(event) {
        return false;
    });

    $('#show-review').delegate('.pagination a', 'click', function (e) {
        e.preventDefault();

        $('#show-review').fadeOut('slow');

        $('#show-review').load(this.href);

        $('#show-review').fadeIn('slow');
    });
    $("#accordion").accordion();

    $('#show-review').load('index.php?route=product/product/getreview&product_id=<?php echo $product_id; ?>');
    $('.over-review').load('index.php?route=product/product/gettotalreview&product_id=<?php echo $product_id; ?>');
    $(document).on('click', '#button-review', function (event) {
        event.preventDefault();
        var product_id = $(this).attr('role');
        $.ajax({
            url: 'index.php?route=product/product/writereview&product_id=' + product_id,
            type: 'post',
            dataType: 'json',
            data: 'title=' + encodeURIComponent($('input[name=\'title\']').val()) +
                    '&summary=' + encodeURIComponent($('textarea[name=\'summary\']').val()) +
                    '&quality=' + encodeURIComponent($('input[name=\'quality\']').val() ? $('input[name=\'quality\']').val() : '') +
                    '&color=' + encodeURIComponent($('input[name=\'color\']').val() ? $('input[name=\'color\']').val() : '') +
                    '&value=' + encodeURIComponent($('input[name=\'value\']').val() ? $('input[name=\'value\']').val() : '') +
                    '&location=' + encodeURIComponent($('input[name=\'location\']').val()) +
                    '&nickname=' + encodeURIComponent($('input[name=\'nickname\']').val()),
            beforeSend: function () {
                $('#button-review').button('loading');
            },
            complete: function () {
                $('#button-review').button('reset');
            },
            success: function (json) {
                $('.alert-success, .alert-danger').remove();

                if (json['error']) {
                    $('#review').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
                }

                if (json['success']) {
                    $('#review').after('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

                    $('input[name=\'title\']').val('');
                    $('input[name=\'location\']').val('');
                    $('input[name=\'nickname\']').val('');
                    $('textarea[name=\'summary\']').val('');
                    $('input[name=\'quality\']').val('');
                    $('input[name=\'color\']').val('');
                    $('input[name=\'value\']').val('');
                    $('#show-review').load('index.php?route=product/product/getreview&product_id=' + product_id);
                    $('.over-review').load('index.php?route=product/product/gettotalreview&product_id=' + product_id);
                    $('#form-write-review').toggle();
                    $('.alert-success').toggle(5000);
                }
            }
        });
    });
    $(document).on('click', '.like', function (event) {
        event.preventDefault();
        var review_id = $(this).attr('role');
        $.ajax({
            url: 'index.php?route=product/product/likereview',
            type: 'POST',
            dataType: 'html',
            data: {review_id: review_id, type:1},
        })
                .done(function (msg) {
                    if (msg != 0) {
                        $('.like_' + review_id).html(msg);
                    }
                });
    });
    /*********************/
    $('#show-review-mobile').delegate('.pagination a', 'click', function (e) {
        e.preventDefault();

        $('#show-review-mobile').fadeOut('slow');

        $('#show-review-mobile').load(this.href);

        $('#show-review-mobile').fadeIn('slow');
    });

    $('#show-review-mobile').load('index.php?route=product/product/getreview&product_id=<?php echo $product_id; ?>');
    $('.over-review-mobile').load('index.php?route=product/product/gettotalreview&product_id=<?php echo $product_id; ?>');
    $(document).on('click', '#button-review-mobile', function (event) {
        event.preventDefault();
        var product_id = $(this).attr('role');
        $.ajax({
            url: 'index.php?route=product/product/writereview&product_id=' + product_id,
            type: 'post',
            dataType: 'json',
            data: 'title=' + encodeURIComponent($('input[name=\'title_mobile\']').val()) +
                    '&summary=' + encodeURIComponent($('textarea[name=\'summary_mobile\']').val()) +
                    '&quality=' + encodeURIComponent($('input[name=\'quality_mobile\']').val() ? $('input[name=\'quality_mobile\']').val() : '') +
                    '&color=' + encodeURIComponent($('input[name=\'color_mobile\']').val() ? $('input[name=\'color_mobile\']').val() : '') +
                    '&value=' + encodeURIComponent($('input[name=\'value_mobile\']').val() ? $('input[name=\'value_mobile\']').val() : '') +
                    '&location=' + encodeURIComponent($('input[name=\'location_mobile\']').val()) +
                    '&nickname=' + encodeURIComponent($('input[name=\'nickname_mobile\']').val()),
            beforeSend: function () {
                $('#button-review-mobile').button('loading');
            },
            complete: function () {
                $('#button-review-mobile').button('reset');
            },
            success: function (json) {
                $('.alert-success, .alert-danger').remove();

                if (json['error']) {
                    $('#review_mobile').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
                }

                if (json['success']) {
                    $('#review_mobile').after('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

                    $('input[name=\'title_mobile\']').val('');
                    $('input[name=\'location_mobile\']').val('');
                    $('input[name=\'nickname_mobile\']').val('');
                    $('textarea[name=\'summary_mobile\']').val('');
                    $('input[name=\'quality_mobile\']').val('');
                    $('input[name=\'color_mobile\']').val('');
                    $('input[name=\'value_mobile\']').val('');
                    $('#show-review-mobile').load('index.php?route=product/product/getreview&product_id=' + product_id);
                    $('.over-review-mobile').load('index.php?route=product/product/gettotalreview&product_id=' + product_id);
                    $('#form-write-review-mobile').toggle();
                    $('.alert-success').toggle(5000);
                }
            }
        });
    });
    /********************/

    $(document).on('click', '.dislike', function (event) {
        event.preventDefault();
        var review_id = $(this).attr('role');
        $.ajax({
            url: 'index.php?route=product/product/likereview',
            type: 'POST',
            dataType: 'html',
            data: {review_id: review_id, type:0},
        })
                .done(function (msg) {
                    if (msg != 0) {
                        $('.dislike_' + review_id).html(msg);
                    }
                });
    });

    //$('.nav-tabs li a:first').css('display', 'none');
    $('.quantity select').css('display', '');
    $('.quantity .btn-group').css('display', 'none');

    $(document).on('click', '#write-review', function (event) {
        event.preventDefault();
        $('#form-write-review').toggle();
    });
    /*** new amend ments**/
    $(document).on('click', '#write-review-mobile', function (event) {
        event.preventDefault();
        $('#form-write-review-mobile').toggle();
    });

});
</script>

<script src='catalog/view/javascript/jquery.rating.js' type="text/javascript" language="javascript"></script>
<script src="catalog/view/javascript/common.js" type="text/javascript"></script>
<link href='catalog/view/theme/rr2/css/jquery.rating.css' type="text/css" rel="stylesheet"/>
<script type="text/javascript">
    $('.v-quality').rating({
        callback: function (value, link) {
            // alert(value);
            $('#v-quality').val(value);
        }
    });
    $('.v-color').rating({
        callback: function (value, link) {
            // alert(value);
            $('#v-color').val(value);
        }
    });
    $('.v-value').rating({
        callback: function (value, link) {
            // alert(value);
            $('#v-value').val(value);
        }
    });


// new amendments
    $('.v-quality_mobile').rating({
        callback: function (value, link) {
            // alert(value);
            $('#v-quality_mobile').val(value);
        }
    });
    $('.v-color_mobile').rating({
        callback: function (value, link) {
            // alert(value);
            $('#v-color_mobile').val(value);
        }
    });
    $('.v-value_mobile').rating({
        callback: function (value, link) {
            // alert(value);
            $('#v-value_mobile').val(value);
        }
    });
    // new amendments ends
    $(document).on('click', '#viewoption', function (event) {
        $('#showoption').toggle();
        event.preventDefault();
    });

    $(document).ready(function () {
        $('.zoomContainer').css('height', '500px');
    });



    $(document).ready(function () {
        $("#sendProductViaMail").fancybox({
            'scrolling': 'no',
            'titleShow': false,
            'onClosed': function () {
                $("#emailPopup").hide();
            }
        });
    });



    $("#emailPopup").validate({
        rules: {
            your_name: {
                required: true
            },
            sending_person_name: {
                required: true
            },
            sending_email_to: {
                required: true
            },
            sending_message: {
                required: true
            }
        },
        submitHandler: function () {
            var yourname = $("#your_name").val();
            var sendingpersonname = $("#sending_person_name").val();
            var sendingemailto = $("#sending_email_to").val();
            var sendingmessage = $("#sending_message").val();

            $.ajax({
                url: 'index.php?route=product/product/sendmailtoother&yourname=' + yourname,
                type: 'post',
                dataType: 'json',
                data: 'personname=' + sendingpersonname + '&personemail=' + sendingemailto + '&sendingmessage=' + sendingmessage,
                beforeSend: function () {
                    $('#button-review-mobile').button('loading');
                },
                complete: function () {
                    $('#button-review-mobile').button('reset');
                },
                success: function (json) {

                }
            });
        }
    });

    $(document).on("click", "#shareNow", function () {
        $("#shareButtons").slideToggle("slow");
    });
</script>

<?php echo $footer; ?>