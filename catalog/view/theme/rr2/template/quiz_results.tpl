<!-- <!DOCTYPE html>
<html>
   <head>
      <title>RothRugs</title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">

   </head>
   <body> -->
   <?php echo $header; ?>
      <link rel="stylesheet" type="text/css" href="catalog/view/theme/rr2/css/theme.css">
      <link rel="stylesheet" type="text/css" href="catalog/view/theme/rr2/css/style.css">
      <link rel="stylesheet" type="text/css" href="catalog/view/theme/rr2/css/responsive.css">
      <style type="text/css">
         .top-header span p{
            color : white !important;
         }
         .top-header span p a{
            color: rgba(255, 37, 51, 0.59) !important;
         }
      </style>
<?php echo $content_top; ?>
   
      <div class="custom-container clearfix">
         <div class="banner clearfix">
            <img src="<?php echo $image; ?>" alt="">
            <div class="banner-in clearfix">
            <div class="banner-in-in clearfix">
               <h3>Your Rug Personality IS</h3>
                  <h1><?php echo $cat_name; ?></h1>
                  <p><?php echo $description; ?></p>
                  </div>
            </div>
             <div class="banner-circle">
             <div class="banner-circle-in">
               <h4>FREE </h4> 
               <h5> SHIPPING</h5>
               </div>
               </div>
               
         </div>

         <div class="all-products clearfix">
            <div class="products clearfix">
            <h2>Here are some rugs we think best match your style</h2>
               <div class="grid text-center clearfix">
               <?php foreach ($products as $product) { ?>
                  <div class="grid__item one-fifth medium--one-half">
                     <div class="product-image clearfix"><a href="<?php echo $product['href']; ?>" title="<?php echo $product['name']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>"> </a> </div>
                     <div class="product-description clearfix">
                        <h5> <?php echo $product['name']; ?></h5>
                        <h6> Starting at <span> <?php echo $product['price']; ?> </span></h6>
                        <a href="<?php echo $product['href']; ?>" title="">View Detail </a>
                     </div>
                  </div>
                  <?php } ?>
               </div>
            </div>

            <div class="shopby-room clearfix">
               <div class="title_cont">
                  <div class="mdr_brd_l"></div>
                  <h2 class="color">Shop by <span>room</span>  </h2>
               </div>
               
               <div class="grid text-center clearfix">
               <?php 
               if($landing_page == 'casual'){
               ?>
                  <div class="grid__item one-fifth medium--one-half">
                     <div class="product-image clearfix"><a href="https://rothrugs.com/index.php?route=product/search&filter_color_name=&filter_size_id=&filter_size_id=&filter_price_id=&filter_color_id=&filter_style_id=28,27&limit=100" title="Product"><img src="catalog/view/theme/rr2/images/casual/bedroom-rugs.jpg" alt=""> </a> </div>
                     <div class="product-description clearfix">
                        <h5>Bedroom Rugs</h5>
                     </div>
                  </div>
                  <div class="grid__item one-fifth medium--one-half">
                     <div class="product-image clearfix"><a href="https://rothrugs.com/index.php?route=product/search&filter_color_name=&filter_size_id=&filter_size_id=&filter_price_id=&filter_color_id=&filter_style_id=28,27&limit=100&limit=100&page=2" title="Product"><img src="catalog/view/theme/rr2/images/casual/dining-room-rugs.jpg" alt=""> </a> </div>
                     <div class="product-description clearfix">
                        <h5>Dining Room Rugs</h5>
                     </div>
                  </div>
                  <div class="grid__item one-fifth medium--one-half">
                     <div class="product-image clearfix"><a href="https://rothrugs.com/index.php?route=product/search&filter_color_name=&filter_size_id=&filter_size_id=&filter_price_id=&filter_color_id=&filter_style_id=28,27&page=2&limit=100&limit=100&page=3" title="Product"><img src="catalog/view/theme/rr2/images/casual/great-room-rugs.jpg" alt=""> </a> </div>
                     <div class="product-description clearfix">
                        <h5>Great Room Rugs</h5>
                     </div>
                  </div>
                  <div class="grid__item one-fifth medium--one-half">
                     <div class="product-image clearfix"><a href="https://rothrugs.com/index.php?route=product/search&filter_color_name=&filter_size_id=&filter_size_id=&filter_price_id=&filter_color_id=&filter_style_id=28,27&page=3&limit=100&limit=100&page=4" title="Product"><img src="catalog/view/theme/rr2/images/casual/kitchen-rugs.jpg" alt=""> </a> </div>
                     <div class="product-description clearfix">
                        <h5>Kitchen Rugs</h5>
                     </div>
                  </div>
                  <div class="grid__item one-fifth medium--one-half">
                     <div class="product-image clearfix"><a href="https://rothrugs.com/index.php?route=product/search&filter_color_name=&filter_size_id=&filter_size_id=&filter_price_id=&filter_color_id=&filter_style_id=28,27&page=4&limit=100&limit=100&page=5" title="Product"><img src="catalog/view/theme/rr2/images/casual/living-room-rugs.jpg" alt=""> </a> </div>
                     <div class="product-description clearfix">
                        <h5>Living Room Rugs</h5>
                     </div>
                  </div>
                  <?php   
                  }else if($landing_page == 'modern'){ ?>
                  <div class="grid__item one-fifth medium--one-half">
                     <div class="product-image clearfix"><a href="https://rothrugs.com/index.php?route=product/search&filter_color_name=&filter_size_id=&filter_size_id=&filter_price_id=&filter_color_id=&filter_style_id=34&limit=100" title="Product"><img src="catalog/view/theme/rr2/images/modern/bedroom_rug.jpg" alt=""> </a> </div>
                     <div class="product-description clearfix">
                        <h5>Bedroom Rugs</h5>
                     </div>
                  </div>
                  <div class="grid__item one-fifth medium--one-half">
                     <div class="product-image clearfix"><a href="https://rothrugs.com/index.php?route=product/search&filter_color_name=&filter_size_id=&filter_size_id=&filter_price_id=&filter_color_id=&filter_style_id=28,34&page=13&limit=100&limit=100&page=14" title="Product"><img src="catalog/view/theme/rr2/images/modern/kidroom_rug.jpg" alt=""> </a> </div>
                     <div class="product-description clearfix">
                        <h5>Kids Room Rugs</h5>
                     </div>
                  </div>
                  <div class="grid__item one-fifth medium--one-half">
                     <div class="product-image clearfix"><a href="https://rothrugs.com/index.php?route=product/search&filter_color_name=&filter_size_id=&filter_size_id=&filter_price_id=&filter_color_id=&filter_style_id=34&limit=100&limit=100&page=6" title="Product"><img src="catalog/view/theme/rr2/images/modern/living_room_rug.jpg" alt=""> </a> </div>
                     <div class="product-description clearfix">
                        <h5>Living Room Rugs</h5>
                     </div>
                  </div>
                  <div class="grid__item one-fifth medium--one-half">
                     <div class="product-image clearfix"><a href="https://rothrugs.com/index.php?route=product/search&filter_color_name=&filter_size_id=&filter_size_id=&filter_price_id=&filter_color_id=&filter_style_id=34&page=6&limit=100&limit=100&page=7" title="Product"><img src="catalog/view/theme/rr2/images/modern/dining_room_rug.jpg" alt=""> </a> </div>
                     <div class="product-description clearfix">
                        <h5>Dining Room Rugs</h5>
                     </div>
                  </div>
                  <div class="grid__item one-fifth medium--one-half">
                     <div class="product-image clearfix"><a href="https://rothrugs.com/index.php?route=product/search&filter_color_name=&filter_size_id=&filter_size_id=&filter_price_id=&filter_color_id=&filter_style_id=34&page=7&limit=100&limit=100&page=8" title="Product"><img src="catalog/view/theme/rr2/images/modern/kitchen_rug.jpg" alt=""> </a> </div>
                     <div class="product-description clearfix">
                        <h5>Kitchen Rugs</h5>
                     </div>
                  </div>
                <?php  }  elseif($landing_page == 'shag'){ ?>
                  <div class="grid__item one-fifth medium--one-half">
                     <div class="product-image clearfix"><a href="https://rothrugs.com/index.php?route=product/search&filter_style_id=32&limit=100" title="Product"><img src="catalog/view/theme/rr2/images/shag/denrugs.jpg" alt=""> </a> </div>
                     <div class="product-description clearfix">
                        <h5>Den Rugs</h5>
                     </div>
                  </div>
                  <div class="grid__item one-fifth medium--one-half">
                     <div class="product-image clearfix"><a href="https://rothrugs.com/index.php?route=product/search&filter_color_name=&filter_size_id=&filter_size_id=&filter_price_id=&filter_color_id=&filter_style_id=28,32&limit=100&limit=100&page=2" title="Product"><img src="catalog/view/theme/rr2/images/shag/frontroomrugs.jpg" alt=""> </a> </div>
                     <div class="product-description clearfix">
                        <h5>Front Room Rugs</h5>
                     </div>
                  </div>
                  <div class="grid__item one-fifth medium--one-half">
                     <div class="product-image clearfix"><a href="https://rothrugs.com/index.php?route=product/search&filter_color_name=&filter_size_id=&filter_size_id=&filter_price_id=&filter_color_id=&filter_style_id=28,32&page=11&limit=100&limit=100&page=12" title="Product"><img src="catalog/view/theme/rr2/images/shag/greatroomrugs.jpg" alt=""> </a> </div>
                     <div class="product-description clearfix">
                        <h5>Great Room Rugs</h5>
                     </div>
                  </div>
                  <div class="grid__item one-fifth medium--one-half">
                     <div class="product-image clearfix"><a href="https://rothrugs.com/index.php?route=product/search&filter_color_name=&filter_size_id=&filter_size_id=&filter_price_id=&filter_color_id=&filter_style_id=28,32&page=7&limit=100&limit=100&page=8" title="Product"><img src="catalog/view/theme/rr2/images/shag/kidsroom.jpg" alt=""> </a> </div>
                     <div class="product-description clearfix">
                        <h5>Kids Room Rugs</h5>
                     </div>
                  </div>
                  <div class="grid__item one-fifth medium--one-half">
                     <div class="product-image clearfix"><a href="https://rothrugs.com/index.php?route=product/search&filter_color_name=&filter_size_id=&filter_size_id=&filter_price_id=&filter_color_id=&filter_style_id=28,32&page=13&limit=100&limit=100&page=14" title="Product"><img src="catalog/view/theme/rr2/images/shag/kitchenrugs.jpg" alt=""> </a> </div>
                     <div class="product-description clearfix">
                        <h5>Kitchen Rugs</h5>
                     </div>
                  </div>
                  <?php } elseif($landing_page == 'traditional'){ ?>
                  <div class="grid__item one-fifth medium--one-half">
                     <div class="product-image clearfix"><a href="https://rothrugs.com/index.php?route=product/search&filter_style_id=34&limit=100" title="Product"><img src="catalog/view/theme/rr2/images/traditional/bedroom_rugs.jpg" alt=""> </a> </div>
                     <div class="product-description clearfix">
                        <h5>Bedroom Rugs</h5>
                     </div>
                  </div>
                  <div class="grid__item one-fifth medium--one-half">
                     <div class="product-image clearfix"><a href="https://rothrugs.com/index.php?route=product/search&filter_style_id=34&page=2&limit=100&limit=100&page=3" title="Product"><img src="catalog/view/theme/rr2/images/traditional/dining_room.jpg" alt=""> </a> </div>
                     <div class="product-description clearfix">
                        <h5>Dining Room Rugs</h5>
                     </div>
                  </div>
                  <div class="grid__item one-fifth medium--one-half">
                     <div class="product-image clearfix"><a href="https://rothrugs.com/index.php?route=product/search&filter_style_id=34&page=3&limit=100&limit=100&page=4" title="Product"><img src="catalog/view/theme/rr2/images/traditional/living_room.jpg" alt=""> </a> </div>
                     <div class="product-description clearfix">
                        <h5>Living Room Rugs</h5>
                     </div>
                  </div>
                  <div class="grid__item one-fifth medium--one-half">
                     <div class="product-image clearfix"><a href="https://rothrugs.com/index.php?route=product/search&filter_style_id=34&page=4&limit=100&limit=100&page=5" title="Product"><img src="catalog/view/theme/rr2/images/traditional/great_room.jpg" alt=""> </a> </div>
                     <div class="product-description clearfix">
                        <h5>Great Room Rugs</h5>
                     </div>
                  </div>
                  <div class="grid__item one-fifth medium--one-half">
                     <div class="product-image clearfix"><a href="https://rothrugs.com/index.php?route=product/search&filter_style_id=34&page=6&limit=100&limit=100&page=5" title="Product"><img src="catalog/view/theme/rr2/images/traditional/kitchen_rugs.jpg" alt=""> </a> </div>
                     <div class="product-description clearfix">
                        <h5>Kitchen Rugs</h5>
                     </div>
                  </div>
                  <?php  }elseif($landing_page == 'southwest') { ?>
                  <div class="grid__item one-fifth medium--one-half">
                     <div class="product-image clearfix"><a href="https://rothrugs.com/index.php?route=product/search&filter_style_id=33&find=true" title="Product"><img src="catalog/view/theme/rr2/images/southwest/bedroom-rugs1.jpg" alt=""> </a> </div>
                     <div class="product-description clearfix">
                        <h5>Bedroom Rugs</h5>
                     </div>
                  </div>
                  <div class="grid__item one-fifth medium--one-half">
                     <div class="product-image clearfix"><a href="https://rothrugs.com/index.php?route=product/search&filter_style_id=33&page=2" title="Product"><img src="catalog/view/theme/rr2/images/southwest/den-rugs.jpg" alt=""> </a> </div>
                     <div class="product-description clearfix">
                        <h5>Den Rugs</h5>
                     </div>
                  </div>
                  <div class="grid__item one-fifth medium--one-half">
                     <div class="product-image clearfix"><a href="https://rothrugs.com/index.php?route=product/search&filter_style_id=33&page=2&page=3" title="Product"><img src="catalog/view/theme/rr2/images/southwest/dining-room.jpg" alt=""> </a> </div>
                     <div class="product-description clearfix">
                        <h5>Dining Room Rugs</h5>
                     </div>
                  </div>
                  <div class="grid__item one-fifth medium--one-half">
                     <div class="product-image clearfix"><a href="https://rothrugs.com/index.php?route=product/search&filter_style_id=33&page=3&page=4" title="Product"><img src="catalog/view/theme/rr2/images/southwest/entryway.jpg" alt=""> </a> </div>
                     <div class="product-description clearfix">
                        <h5>Entryway Rugs</h5>
                     </div>
                  </div>
                  <div class="grid__item one-fifth medium--one-half">
                     <div class="product-image clearfix"><a href="https://rothrugs.com/index.php?route=product/search&filter_style_id=33&page=5&page=2" title="Product"><img src="catalog/view/theme/rr2/images/southwest/front-room.jpg" alt=""> </a> </div>
                     <div class="product-description clearfix">
                        <h5>Front Room Rugs</h5>
                     </div>
                  </div>
                  <?php } ?>
               </div>
            </div>

            <div class="shopby-style clearfix">
               

               <div class="title_cont">
                  <div class="mdr_brd_l"></div>
                  <h2 class="color">Shop by <span>Style</span>  </h2>
               </div>
               <div class="grid text-center clearfix">
                  <div class="grid__item one-fifth medium--one-half">
                     <div class="product-image clearfix"><a href="/rugs/casual/" title="Product"><img src="catalog/view/theme/rr2/images/shop_by_style/Casual/living_room.png" alt=""> </a> </div>
                     <div class="product-description clearfix">
                        <h5> Casual</h5>
                     </div>
                  </div>
                  <div class="grid__item one-fifth medium--one-half">
                     <div class="product-image clearfix"><a href="/rugs/outdoor/" title="Product"><img src="catalog/view/theme/rr2/images/shop_by_style/Outdoor/Outdoor-Dining.png" alt=""> </a> </div>
                     <div class="product-description clearfix">
                        <h5> Outdoor</h5>
                     </div>
                  </div>
                  <div class="grid__item one-fifth medium--one-half">
                     <div class="product-image clearfix"><a href="/rugs/southwest/" title="Product"><img src="catalog/view/theme/rr2/images/shop_by_style/Southwest/Nomad.png" alt=""> </a> </div>
                     <div class="product-description clearfix">
                        <h5> Southwest</h5>
                     </div>
                  </div>
                  <div class="grid__item one-fifth medium--one-half">
                     <div class="product-image clearfix"><a href="/rugs/traditional/" title="Product"><img src="catalog/view/theme/rr2/images/shop_by_style/Traditional/Bedroom-Rugs-4.png" alt=""> </a> </div>
                     <div class="product-description clearfix">
                        <h5>Traditional</h5>
                     </div>
                  </div>
                  <div class="grid__item one-fifth medium--one-half">
                     <div class="product-image clearfix"><a href="/rugs/modern/" title="Product"><img src="catalog/view/theme/rr2/images/shop_by_style/Modern/7000-78_2.png" alt=""> </a> </div>
                     <div class="product-description clearfix">
                        <h5> Modern</h5>
                     </div>
                  </div>
               </div>
            </div>
            <a href="#" title="Shop All Our Rugs" class="shop-all">Shop All Our Rugs</a>
         </div>

         <div class="shopby-style clearfix">
         <div class="title_cont">
                  <div class="mdr_brd_l"></div>
                  <h2 class="color">Kate's  <span>Picks</span>  </h2>
               </div>
         </div>
         <div class="cust clearfix">
            <div class="left">
               <h3 style="color:black"> <strong>Not your Style? </strong> Check out some of our curated collections by Kate Roth. Or contact us to work one on one with your very own rug concierge!</h3>
            </div>
            <div class="right" style="float: none;">
               <img src="catalog/view/theme/rr2/images/kate-img.png" alt="">
               <div class="name">
                  <h3><strong>Kate Roth</strong></h3>
                  <p style="font-size: 18px">VP of Roth Rugs and rug expert</p>
               </div>
            </div>
         </div>

         <div class="blogs clearfix">
            <div class="grid text-center clearfix">
            <?php foreach($kat_categories as $cat): ?>
               <div class="grid__item one-quarter">
                  <div class="blog-image clearfix"><a href="<?php echo $cat['href']; ?>" title="Product"><img src="<?php echo $cat['thumb']; ?>" alt=""> </a> </div>
                  <div class="blog-description clearfix">
                     <h4><?php echo $cat['name']; ?></h4>
                     <span> <?php echo $cat['limit_description']; ?> 
                     <a href="<?php echo $cat['href']; ?>" title="Learn More">Learn More </a></span>
                  </div>
               </div>
               <?php endforeach ?>
            </div>
         </div>
      </div>

<?php echo $footer; ?>