<?php echo $header; ?>
<div class="search-search"></div>
<div class="topheadersearch mobile-only">
    <ul>
        <li class="left-text"><?php echo $results_mobile;?></li>
        <li class="center-text">RUG FINDER</li>
        <li class="right-text"><a href="<?php echo $base; ?>index.php?route=product/search" class="clear-all-filters" title="Clear All filter">Clear All Filters</a></li>
    </ul>

</div>
<div id="content" class="container products-page">

    <ul id="filter" class="nav nav-justified">
        <li class="filter_item only-desktop hidden-xs filter_title">RUG FINDER</li>

        <li class="filter_item">
            <select class="rug-finder-multiselect" multiple="multiple" title="style" name="option_style_id">
                <?php foreach ($finder_filter['Style'] as $key => $value) {
                    if(in_array($value['filter_id'], $filter_style_id))
                        echo '<option value="'.$value['filter_id'].'" selected >'.$value['name'].' <!--('.$value['product_count'].')-->'.'</option>';
                    else
                        echo '<option  value="'.$value['filter_id'].'">'.$value['name'].' <!--('.$value['product_count'].')-->'.'</option>';
                }?>
            </select>
            <span class="mobile-only">
                <a class="clear-filter " href="<?php echo $base; ?>index.php?route=product/search&filter_color_id=<?php echo implode(",", $filter_color_id); ?>&filter_price_id=<?php echo implode(",",$filter_price_id); ?>&filter_size_id=<?php echo implode(",",$filter_size_id)?>&find=true">CLEAR FILTERS</a>
            </span>
        </li>
        <li class="filter_item">
            <select class="rug-finder-multiselect" id="option_color" multiple="multiple" title="color" name="filter_color">
                <?php foreach ($finder_filter['Color'] as $key => $value) {
                if(in_array($value['filter_id'], $filter_color_id))
                echo '<option data_name="'.$value['name'].'" value="'.$value['filter_id'].'" selected >'.$value['name'].' <!--('.$value['product_count'].')-->'.'</option>';
                else
                echo '<option data_name="'.$value['name'].'" value="'.$value['filter_id'].'">'.$value['name'].' <!--('.$value['product_count'].')-->'.'</option>';
                }?>
            </select>
            <span class="mobile-only">
                <a class="clear-filter" href="<?php echo $base; ?>index.php?route=product/search&filter_style_id=<?php echo implode(",",$filter_style_id); ?>&filter_price_id=<?php echo implode(",",$filter_price_id); ?>&filter_size_id=<?php echo implode(",",$filter_size_id)?>&find=true">CLEAR FILTERS</a>
            </span>
        </li>
        <li class="filter_item">
            <select class="rug-finder-multiselect" multiple="multiple" title="price" name="price">
                <?php foreach ($finder_filter['Price'] as  $finder_price) {
                $from = !empty($finder_price['from_value'])? (int) $finder_price['from_value']:0;
                $to = !empty($finder_price['to_value'])? (int) $finder_price['to_value']:0;
                if(in_array($finder_price['filter_id'], $filter_price_id))
                echo '<option data-price="'.$from .'-'.$to.'" value="'.$finder_price['filter_id'].'" selected>'.$finder_price['name'].' <!--('.$finder_price['product_count'].'])-->'.'</option>';
                else
                echo '<option data-price="'.$from .'-'.$to.'"  value="'.$finder_price['filter_id'].'">'.$finder_price['name'].' <!--('.$finder_price['product_count'].')-->'.'</option>';
                }?>
            </select>
            <span class="mobile-only">
                <a class="clear-filter " href="<?php echo $base; ?>index.php?route=product/search&filter_color_id=<?php echo implode(",",$filter_color_id); ?>&filter_style_id=<?php echo implode(",",$filter_style_id); ?>&filter_size_id=<?php echo implode(",",$filter_size_id)?>&find=true">CLEAR FILTERS</a>
            </span>
        </li>
        <li class="filter_item">

            <select class="rug-finder-multiselect" multiple="multiple" title="size" parrentOption ="true" name="option_size_id">
                <?php foreach ($finder_filter['Size']  as $key => $value) {
                if(in_array($value['filter_id'], $filter_size_id))
                echo '<option value="'.$value['filter_id'].'" selected>'.$value['name'].' <!--('.$value['product_count'].')-->'.'</option>';
                else
                echo '<option  value="'.$value['filter_id'].'">'.$value['name'].' <!--('.$value['product_count'].')-->'.'</option>';
                }?>
            </select>
            <span class="mobile-only">
                <a class="clear-filter" href="<?php echo $base; ?>index.php?route=product/search&filter_color_id=<?php echo implode(",",$filter_color_id); ?>&filter_style_id=<?php echo implode(",",$filter_style_id); ?>&filter_price_id=<?php echo implode(",",$filter_price_id); ?>&find=true">CLEAR FILTERS</a>
            </span>
        </li>
        <li class="filter_item"><a href="<?php echo $base; ?>index.php?route=product/search" class="clearSearch only-desktop" style="background: none;">Clear</a></li>
        <li class="filter_item hidden filter_search" id="button-search"><div>GO!</div></li>
    </ul>

    <div class="clearfix"></div>

    <div class="rugs_grid">
        <div class="only-desktop row">
            <div class="border-bottom">
                <div class="col-xs-12 col-sm-12 ">
                    <div class="col-xs-3">
                        <?php if(empty($banner['link'])): ?>
                        <!-- <img src="<?php echo $banner['image'] ?>" /> -->
                        <div class="promo-banner">
                            <p>Need Help? Call US!<br>(888) 776-6425</p>
                            <i class="fa fa-phone"></i>
                        </div>
                        <?php else: ?>
                        <a href="<?php echo $banner['link']; ?>">
                        <div class="promo-banner">
                            <p>Need Help? Call US!<br>(888) 776-6425</p>
                            <i class="fa fa-phone"></i>
                        </div></a>
                        <!-- <a href="<?php echo $banner['link']; ?>">
                            <img src="<?php echo $banner['image'] ?>" />
                        </a> -->
                        <?php endif; ?>
                    </div>
                    <div class="col-xs-3">
                        <span class="result-span"><?php echo $results_mobile_bot;?></span>
                    </div>
                    <div class="col-xs-3 text-left">
                        <select id="input-limit" class="form-control col-sm-3" onchange="location = this.value;">
                            <?php foreach ($limits as $limits) { ?>
                            <?php if ($limits['value'] == $limit) { ?>
                            <option data-limit="<?php echo $limits['value']; ?>" value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
                            <?php } else { ?>
                            <option data-limit="<?php echo $limits['value']; ?>" value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
                            <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-xs-3 text-left">
                        <select id="input-sort2" class="form-control col-sm-3" onchange="location = this.value;">
                            <?php foreach ($sorts as $sort) { ?>
                            <?php if ($sort['value'] == $sort . '-' . $order) { ?>
                            <option value="<?php echo $sort['href']; ?>" selected="selected"><?php echo $sort['text']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $sort['href']; ?>"><?php echo $sort['text']; ?></option>
                            <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>

        </div>
        <div class="mobile-only">
            <div class="border-bottom row">
                <div class="col-xs-7">
                    <span class="result-span"><?php echo $results_mobile_bot;?></span>
                </div>
                <div class="col-xs-5 text-right">
                    <select id="input-sort" class="form-control col-sm-3" onchange="location = this.value;">
                        <?php foreach ($sorts as $sort) { ?>
                        <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
                        <option value="<?php echo $sort['href']; ?>" selected="selected"><?php echo $sort['text']; ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $sort['href']; ?>"><?php echo $sort['text']; ?></option>
                        <?php } ?>
                        <?php } ?>
                    </select>
                </div>
            </div>

        </div>
        <?php if(!empty($quiz)):?>
        <div class="col-sm-12">
            <label class="btn_red">Check out your quiz results!</label>
        </div>
        <?php endif;?>
        <!--promo banner-->
        <!-- <div class="col-sm-4 rug promo-banner right">
            
        </div> -->
        <!--promo banner-->
        <?php if(!empty($products)):
        $counter = 0
        ?>
        <?php foreach ($products as $product)
        {
        $counter++;
        /*if($counter>10){
        $classresponsive = 'hidden-show-more';
        }else{
        $classresponsive = '';
        }*/
        ?>
        <div class="col-sm-4 col-xs-6 rug <?php echo $classresponsive;?>">
            <div class="rug_img">
                <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a>
            </div>
            <?php if($product['sale_flag'] == 1) { ?>
            <div class="offer">
                <div class="ribbon-offer"><p>Sale</p></div>
            </div>
            <?php } ?>
            <div class="rug_details">
                <div  class="rug_title">
                    <a href="<?php echo $product['href']; ?>">
                        <p><?php echo $product['name']; ?></p>
                    </a>
                </div>
                <p class="price">Starting at <span>$<?php echo $product['price']; ?> </span></p>
                <div class="view-btn text-center"><a href="<?php echo $product['href']; ?>">View Details</a></div>
            </div>
        </div>
        <?php } ?>
        <?php

        if($current_page < $total_page){ ?>
        <div class="col-xs-12 mobile-only"><a class="view-more" href="/index.php?<?php echo $viewMoreURL;?>" >VIEW MORE</a></div>
        <?php } ?>

        <?php else: ?>
        <div class="col-sm-8 rug">No product found</div>
        <?php endif;?>
    </div>
    <div class="row clear">
        <div class="col-sm-6 text-center"><?php echo $pagination; ?></div>
        <div class="col-sm-6 text-right"><?php echo $results; ?></div>
    </div>
</div>

<?php echo $footer; ?>
<script type="text/javascript"><!--

    /*$('.view-more').on('click', function() {
     $(this).fadeOut();
     $('.hidden-show-more').fadeIn();

     });*/

    $('#button-search').on('click', function () {
       url = '<?php echo $_SERVER["REQUEST_URI"];?>';
        // url = 'index.php?route=product/search';

        var filter_style_id = [];
        var filter_color_name = [];
        var filter_size_id = [];
        var filter_color_id = [];
        var filter_price_id = [];
        $('#content select[name=\'option_style_id\']').each(function () {
            if ($(this).val() !== null) {
                filter_style_id.push($(this).val());
            }

        });
        $('#content select[name=\'option_size_id\']').each(function () {
            if ($(this).val() !== null) {
                filter_size_id.push($(this).val());
            }
        });
        $('#content select[name=\'filter_color\']').each(function () {
            if ($('option:selected', this).attr('data_name') !== null) {
                $(this).children("option:selected").each(function () {
                    var str = $(this).attr('data_name');
                    filter_color_name.push(str.trim());
                    filter_color_id.push($(this).val());


                });
            }

        });
        $('#content select[name=\'price\']').each(function () {
            if ($(this).val() !== null) {
                filter_price_id.push($(this).val());
            }

        });
        if (filter_style_id != '') {
            url += '&filter_style_id=' + filter_style_id;
        }
        if (filter_color_name != '') {
            url += '&filter_color_name=' + filter_color_name;
        }
        if (filter_color_name != '') {
            url += '&filter_color_id=' + filter_color_id;
        }
        if (filter_size_id != '') {
            url += '&filter_size_id=' + filter_size_id;
        }
        if (filter_price_id != '') {
            url += '&filter_price_id=' + filter_price_id;
        }
        if (url != '') {
            url += '&find=true';
        }

        location = url;
    });

    $('.clear-all-filters').on('click', function () {
        $(".rug-finder-multiselect").multiselect('distroy');

    });

    $(document).on('change', 'input[type="checkbox"]', function () {
        $('.search-search').addClass('site-loader-search');

        $('<div class="loading-cover"></div>')
        .css('position', 'fixed')
        .css('width', $(window).width())
        .css('height', $(window).height())
        .css('z-index', 100)
        .css('opacity', '0.4')
        .css('filter', 'alpha(opacity=40)')
        .css('background-color', 'black')
        .css('top', 0)
        .appendTo('body');

        var filter_style_id = [];
        var filter_color_name = [];
        var filter_size_id = [];
        var filter_color_id = [];
        var filter_price_id = [];
        $('#content select[name=\'option_style_id\']').each(function () {
            if ($(this).val() !== null) {
                filter_style_id.push($(this).val());
            }
        });

        $('#content select[name=\'option_size_id\']').each(function () {
            if ($(this).val() !== null) {
                filter_size_id.push($(this).val());
            }
        });

        $('#content select[name=\'filter_color\']').each(function () {
            if ($('option:selected', this).attr('data_name') !== null) {
                $(this).children("option:selected").each(function () {
                    var str = $(this).attr('data_name');
                    filter_color_name.push(str.trim());
                    filter_color_id.push($(this).val());
                });
            }
        });

        $('#content select[name=\'price\']').each(function () {
            if ($(this).val() !== null) {
                filter_price_id.push($(this).val());
            }
        });

        var url = '<?php echo $base; ?>index.php?route=product/search<?php echo $vendor ? "&search=".$vendor : "" ?>&filter_style_id='+ filter_style_id+'&filter_color_name='+filter_color_name+'&filter_color_id='+filter_color_id+'&filter_size_id='+filter_size_id+'&filter_price_id='+filter_price_id+'&find=true';
        // console.log(url);
        setTimeout(function () {
            // $(".site-loader-search").css("opacity", "0.9");
            location = url;
        }, 3000);
    });

    $( window ).load(function() {
        $(".site-loader-search").css("display", "none");
    });
</script>