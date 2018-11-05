
<?php echo $header; ?>

<div class="home_sections">
    <script type="text/javascript">
        $(document).ready(function() {
            $('.find_block a').hover(function() {
                $('.menu a.' + $(this).attr('class')).toggleClass('active');
            });
        });
    </script>

    <?php echo $content_top; ?>
  
    <section id="right-rugs" class="bg-gray">
        <div class="container common-cont">
            <div class="heading">
                <h2>find the right rugs for you</h2>
            </div>
            <div class="row">
                <div class="col-md-4 rightrug">
                    <a href="<?php echo $base; ?>katespick">
                        <div class="left-sec">
                            <img src="catalog/view/theme/rr2/img/rr1.png">
                            <img src="catalog/view/theme/rr2/img/rr1-red.png">
                        </div>
                        <div class="right-sec">
                            <h3>
                                Kate's Picks
                            </h3>
                            <p>Need inspiration? Our VP and rug expert, Kate, shares her faves here!</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-4 rightrug">
                    <a href="<?php echo $base; ?>index.php?route=product/search">
                        <div class="left-sec" style="position: relative;top: -5px;">
                            <img src="catalog/view/theme/rr2/img/rr2.png">
                            <img src="catalog/view/theme/rr2/img/rr2-red.png">
                        </div>
                        <div class="right-sec">
                            <h3>
                                Rug Finder
                            </h3>
                            <p>Use our advanced search feature to narrow down your rug search.</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-4 rightrug">
                    <a href="<?php echo $base; ?>index.php?route=quiz">
                        <div class="left-sec">
                            <img src="catalog/view/theme/rr2/img/rr3.png">
                            <img src="catalog/view/theme/rr2/img/rr3-red.png">
                        </div>
                        <div class="right-sec">
                            <h3>
                                Rug Quiz
                            </h3>
                            <p>Try out our fun rug quiz to find your rug personality!</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <section id="shop-style">
        <div class="container common-cont">
            <div class="heading">
                <h2 class="line">Shop By Style</h2>
            </div>
            <div class="gallery">
                <div class="gallery_col wow fadeInDown">
                    <a class="gallery-link" href="<?php echo $base; ?>rugs/traditional/">
                        <img src="catalog/view/theme/rr2/img/style_traditional.jpg">
                        <div class="gallery_title">
                            <h2>
                                Traditional
                            </h2>
                        </div>
                    </a>
                </div>
                <div class="gallery_col wow fadeInDown">
                    <a class="gallery-link" href="<?php echo $base; ?>rugs/casual/">
                        <img src="catalog/view/theme/rr2/img/style_casual.jpg">
                        <div class="gallery_title">
                            <h2>
                                Casual
                            </h2>
                        </div>
                    </a>
                </div><div class="gallery_col wow fadeInDown">
                    <a class="gallery-link" href="<?php echo $base; ?>rugs/modern/">
                        <img src="catalog/view/theme/rr2/img/style_morden.jpg">
                        <div class="gallery_title">
                            <h2>
                                Modern
                            </h2>
                        </div>
                    </a>
                </div>
                <div class="gallery_col wow fadeInDown">
                    <a class="gallery-link" href="<?php echo $base; ?>rugs/shag/">
                        <img src="catalog/view/theme/rr2/img/style_shag.jpg">
                        <div class="gallery_title">
                            <h2>
                                Shag
                            </h2>
                        </div>
                    </a>
                </div>
                <div class="gallery_col wow fadeInDown">
                    <a class="gallery-link" href="<?php echo $base; ?>rugs/outdoor/">
                        <img src="catalog/view/theme/rr2/img/style_outdoor.jpg">
                        <div class="gallery_title">
                            <h2>
                                Outdoor
                            </h2>
                        </div>
                    </a>
                </div>
                <div class="gallery_col wow fadeInDown">
                    <a class="gallery-link" href="<?php echo $base; ?>rugs/southwest/">
                        <img src="catalog/view/theme/rr2/img/style_southwest.jpg">
                        <div class="gallery_title">
                            <h2>
                                Southwest
                            </h2>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <section id="kate-pics" class="bg-gray">
        <div class="container common-cont">
            <div class="heading">
                <h2 class="line">Kate's Picks</h2>
            </div>
            <div class="">
                <?php foreach($top_category as $category){ ?>
                <a href="<?php echo $category['href']; ?>" class="kate-item">
                    <img src="<?php echo $category['thumb']; ?>" alt="<?php echo $category['name']; ?>">
                    <figcaption>
                        <h2><?php echo $category['name']; ?></h2>
                    </figcaption>	
                </a>
                <?php } ?>
            </div>
        </div>
    </section>
    <section id="insta-field">
        <div class="container common-cont">
            <div class="heading">
                <h2 class="insta"><i class="fa fa-instagram"></i> #rothrugs</h2>
            </div>
            <div class="insta_field" id="instafeed">
            </div>
            <div class="social-sec">
                <h2>Follow & Share</h2>
                <ul class="list-inline">
                    <li><a href="https://www.facebook.com/RothRugs?ref=hl"><i class="fa fa-facebook"></i></a></li>
                    <li><a href="https://www.pinterest.com/rothrugs/"><i class="fa fa-pinterest"></i></a></li>	
                    <li><a href="https://twitter.com/rothrugs"><i class="fa fa-twitter"></i></a></li>	
                    <li><a href="https://instagram.com/rothrugs"><i class="fa fa-instagram"></i></a></li>	
                </ul>
            </div>
        </div>
    </section>
    <section id="featured" class="bg-gray">
        <div class="container featured_brand common-cont">
            <div class="heading">
                <h2 class="line">Our Featured Brands</h2>
            </div>
            <div class="section_content">
                <div class="brands row">
                    <div class="brand">
                        <img src="catalog/view/theme/rr2/img/brand8.png">
                    </div>
                    <div class="brand">
                        <img src="catalog/view/theme/rr2/img/brand2.png">
                    </div>
                    <div class="brand">
                        <img src="catalog/view/theme/rr2/img/brandleen.png">
                    </div>
                    <div class="brand">
                        <img src="catalog/view/theme/rr2/img/Momeni.png">
                    </div>
                    <div class="brand">
                        <img src="catalog/view/theme/rr2/img/Jaipur-Living-Primary-medium_grey.png">
                    </div>
                    <div class="brand">
                        <img src="catalog/view/theme/rr2/img/brand9.png">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php echo $content_bottom; ?>
    <div class="clearfix"></div>
</div>
<?php echo $footer; ?>