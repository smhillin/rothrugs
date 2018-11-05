<html lang="en">
    <head>
        <meta charset="UTF-8">
        <!--<meta name="viewport" content="width=device-width, initial-scale=1"> -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo !empty($title) ? $title : 'Rothrugs'; ?></title>
        <base href="<?php echo $base; ?>" />
        <?php if ($description) { ?>
        <meta name="description" content="<?php echo $description; ?>" />
        <?php } ?>
        <?php if ($keywords) { ?>
        <meta name="keywords" content= "<?php echo $keywords; ?>" />
        <?php } ?>
        <?php if ($icon) { ?>
        <link href="<?php echo $icon; ?>" rel="icon" />
        <?php } ?>
        <!-- <link href="catalog/view/theme/rr2/img/rothrugs_favicon.png" rel="shortcut icon" type="image/x-icon" /> -->

        <link async rel="stylesheet" type="text/css" href="catalog/view/theme/rr2/css/bootstrap.min.css">
        <link async rel="stylesheet" type="text/css" href="catalog/view/theme/rr2/css/jasny-bootstrap.min.css">
        <link async href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet" type="text/css">
        <link async rel="stylesheet" type="text/css" href="catalog/view/theme/rr2/css/bootstrap-multiselect.css">
        <link async rel="stylesheet" type="text/css" href="catalog/view/theme/rr2/css/slider.css">
        <link async rel="stylesheet" type="text/css" href="catalog/view/theme/rr2/css/animate.css">
        <link async rel="stylesheet" type="text/css" href="catalog/view/theme/rr2/css/font-awesome.min.css">
        <link async rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link async rel="stylesheet" type="text/css" href="catalog/view/theme/rr2/css/main.css">
        <meta name="p:domain_verify" content="85f60d8a8b0c42f7a2dbcb401b371114"/>

        <?php foreach ($styles as $style) { ?>
        <link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
        <?php } ?>

        <script  type="text/javascript" src="catalog/view/javascript/jquery-1.11.1.min.js"></script>
        <script  src="catalog/view/javascript/jquery-ui.js"></script>
        <?php foreach ($scripts as $script) { ?>
        <script src="<?php echo $script; ?>" type="text/javascript"></script>
        <?php } ?>
        <?php echo $google_analytics; ?>
        <script  type="text/javascript" src="catalog/view/javascript/bootstrap.min.js"></script>
        <script  type="text/javascript" src="catalog/view/javascript/jasny-bootstrap.min.js"></script>
        <script  type="text/javascript" src="catalog/view/javascript/skrollr.js"></script>
        <script  type="text/javascript" src="catalog/view/javascript/main.js"></script>
        <script  type="text/javascript" src="catalog/view/javascript/fadeSlideShow.js"></script>
        <script  type="text/javascript" src="catalog/view/javascript/bootstrap-multiselect.js"></script>
        <link href="catalog/view/theme/rr2/css/slick.css" rel="stylesheet">
        <link href="catalog/view/theme/rr2/css/slick-theme.css" rel="stylesheet">
        <link href="catalog/view/theme/rr2/css/product.css" rel="stylesheet">
        <script type="text/javascript"> setTimeout(function () {var a=document.createElement("script"); var b=document.getElementsByTagName("script")[0]; a.src=document.location.protocol+"//script.crazyegg.com/pages/scripts/0024/0370.js?"+Math.floor(new Date().getTime()/3600000); a.async=true;a.type="text/javascript";b.parentNode.insertBefore(a,b)}, 1
            );</script>

        <script type="text/javascript">var switchTo5x = true;</script>
        <script type="text/javascript" src="https://w.sharethis.com/button/buttons.js"></script>
        
        <script type="text/javascript">stLight.options({publisher: "0149ca55-2222-4e44-a788-b43e1763913d", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>
        <!-- begin olark code -->
        <script data-cfasync="false" type='text/javascript'>/*<![CDATA[*/window.olark||(function(c){var f=window,d=document,l=f.location.protocol=="https:"?"https:":"http:",z=c.name,r="load";var nt=function(){
        f[z]=function(){
        (a.s=a.s||[]).push(arguments)};var a=f[z]._={
        },q=c.methods.length;while(q--){(function(n){f[z][n]=function(){
        f[z]("call",n,arguments)}})(c.methods[q])}a.l=c.loader;a.i=nt;a.p={
        0:+new Date};a.P=function(u){
        a.p[u]=new Date-a.p[0]};function s(){
        a.P(r);f[z](r)}f.addEventListener?f.addEventListener(r,s,false):f.attachEvent("on"+r,s);var ld=function(){function p(hd){
        hd="head";return["<",hd,"></",hd,"><",i,' onl' + 'oad="var d=',g,";d.getElementsByTagName('head')[0].",j,"(d.",h,"('script')).",k,"='",l,"//",a.l,"'",'"',"></",i,">"].join("")}var i="body",m=d[i];if(!m){
        return setTimeout(ld,100)}a.P(1);var j="appendChild",h="createElement",k="src",n=d[h]("div"),v=n[j](d[h](z)),b=d[h]("iframe"),g="document",e="domain",o;n.style.display="none";m.insertBefore(n,m.firstChild).id=z;b.frameBorder="0";b.id=z+"-loader";if(/MSIE[ ]+6/.test(navigator.userAgent)){
        b.src="javascript:false"}b.allowTransparency="true";v[j](b);try{
        b.contentWindow[g].open()}catch(w){
        c[e]=d[e];o="javascript:var d="+g+".open();d.domain='"+d.domain+"';";b[k]=o+"void(0);"}try{
        var t=b.contentWindow[g];t.write(p());t.close()}catch(x){
        b[k]=o+'d.write("'+p().replace(/"/g,String.fromCharCode(92)+'"')+'");d.close();'}a.P(2)};ld()};nt()})({
        loader: "static.olark.com/jsclient/loader0.js",name:"olark",methods:["configure","extend","declare","identify"]});
        /* custom configuration goes here (www.olark.com/documentation) */
        olark.identify('5405-263-10-5485');/*]]>*/</script><noscript><a href="https://www.olark.com/site/5405-263-10-5485/contact" title="Contact us" target="_blank">Questions? Feedback?</a> powered by <a href="https://www.olark.com?welcome" title="Olark live chat software">Olark live chat software</a></noscript>
        <!-- end olark code -->
        
        <!-- Facebook Pixel Code -->
        <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function()
        {n.callMethod? n.callMethod.apply(n,arguments):n.queue.push(arguments)}
        ;
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window,document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '1377478055661050'); 
        fbq('track', 'PageView');
        </script>
        <noscript>
        <img height="1" width="1" 
        src="https://www.facebook.com/tr?id=1377478055661050&ev=PageView
        &noscript=1"/>
        </noscript>
        <!-- End Facebook Pixel Code -->
        
        <!-- Call Rail Tracking Code -->
        <script type="text/javascript" src="//cdn.callrail.com/companies/720628113/90954260becb5bf06785/12/swap.js"></script>
        <!-- End Call Rail Tracking Code -->

        <!-- Mailchimp Popup Code -->
        <style type="text/css">
            body{
                height: auto !important;

            }
            .mc-closeModal{
                position: initial !important;
                height: 22px !important;
            }
            .mc-layout__modalContent{
                border: 4px solid red;
                height: auto !important;
            }
            .mc-banner{
                display: none !important;
                top: 68 !important;
            }
            @media only screen and (max-width: 800px) {
			    .mc-modal {
			        margin-top: 36px;
			    }
			}
            .mc-modal{
                    top: 30% !important;
            }
        </style>    
        <script type="text/javascript" src="//s3.amazonaws.com/downloads.mailchimp.com/js/signup-forms/popup/embed.js" data-dojo-config="usePlainJson: true, isDebug: false"></script><script type="text/javascript">require(["mojo/signup-forms/Loader"], function(L) { L.start({"baseUrl":"mc.us8.list-manage.com","uuid":"2dcd04b37e3013a5e00f9b327","lid":"67b3a03874"}) })</script>

        <?php 
            if($announcement_bar_status && ($announcement_bar_bar_text_1 != '' || $announcement_bar_bar_text_2 != '' || $announcement_bar_bar_text_3 != '')){
            ?>
        <script type="text/javascript"> 
            $(window).scroll(function() {
                if ($(this).scrollTop() >99){  
                    //$('.top-header').addClass("nav-up");
                    //$('.header_bg').addClass("nav-up");
                    //$('.top-header').slideUp("400");
                }
                else{
                    //$('.top-header').removeClass("nav-up");
                    //$('.header_bg').removeClass("nav-up");
                    //$('.top-header').slideDown('400');
                }
            });
            $(function () {
                var $els = $('span[id^=annoucement]'),
                    i = 0,
                    len = $els.length;

                $els.slice(1).hide();

                setInterval(function () {
                    $els.eq(i).fadeOut('slow',function () {
                        i = (i + 1) % len
                        $els.eq(i).fadeIn('slow');
                    })
                }, 5000)
            });
        
        </script>
        <?php
            }
        ?> 
        <!-- Mailchimp Popup Code Ends -->

        <!-- Hotjar Tracking Code for https://rothrugs.com/ -->
		<script>
		(function(h,o,t,j,a,r){
		h.hj=h.hj||function()
		{(h.hj.q=h.hj.q||[]).push(arguments)}
		;
		h._hjSettings=
		{hjid:589637,hjsv:6}
		;
		a=o.getElementsByTagName('head')[0];
		r=o.createElement('script');r.async=1;
		r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
		a.appendChild(r);
		})(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
		</script>
		<!-- Hotjar Tracking Code for https://rothrugs.com/ -->

        <script type="text/javascript">
            $(document).ready(function() {
                $(".mc-banner").hide();
            });
        </script>
        
    </head>
    <body>
        <div class="site-loader"></div>
        <div class="navmenu navmenu-default navmenu-fixed-left offcanvas" style="">
            <ul class="nav navmenu-nav">
                <li>
                    <a class="menu-logo" href="<?php echo $base; ?>">
                        <img src="<?php echo $logo;?>" title="Rothrugs" alt="Rothrugs">
                    </a>
                </li>
                <li><a href="<?php echo $base; ?>katespick">KATE’S PICKS</a></li>
                <li><a href="<?php echo $base; ?>index.php?route=quiz">RUG QUIZ</a></li>
                <li><a href="<?php echo $base; ?>index.php?route=product/search">SHOP NOW</a></li>
                <li><a href="<?php echo $base; ?>index.php?route=information/contact">CONTACT US</a></li>
                <li><a href="<?php echo $base; ?>about_us">ABOUT US</a></li>
                <li><a href="<?php echo $base; ?>rugs" target="_blank">BLOG</a></li>
                
                <li >
                    <?php if (!$logged) : ?>
                    <a href="index.php?route=account/login"><i class="fa fa-user"></i> Sign in</a>
                    <?php else: ?>
                    <a href="index.php?route=account/logout"><i class="fa fa-sign-out"></i> Logout</a>
                    <?php endif; ?>
                </li>
            </ul>
        </div>


        <div class="header_bg">
        <?php 
            if($announcement_bar_status && ($announcement_bar_bar_text_1 != '' || $announcement_bar_bar_text_2 != '' || $announcement_bar_bar_text_3 != '')){
            ?>
            <div class="top-header">
                <?php 
                    if($announcement_bar_bar_text_1){
                    ?>
                    <span id="annoucement_1"><?php echo html_entity_decode($announcement_bar_bar_text_1); ?></span>
                    <?php
                    }
                    if($announcement_bar_bar_text_2){
                    ?>
                    <span id="annoucement_2"><?php echo html_entity_decode($announcement_bar_bar_text_2); ?></span>
                    <?php
                    }
                    if($announcement_bar_bar_text_3){
                    ?>
                    <span id="annoucement_3"><?php echo html_entity_decode($announcement_bar_bar_text_3); ?></span>
                    <?php
                    }
                ?>
            </div>
            <?php
            }
        ?>
            <div class="header_middle">
                <div class="container-fluid container main_head">
                    <div id="header">
                        <div class="row">
                            <div class="navbar " style="">
                                <button type="button" class="navbar-toggle" data-toggle="offcanvas" data-target=".navmenu" data-canvas="body">
                                    <div class="icon_item">
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                    </div>
                                    <div class="icon_text">
                                        <span class="text-menu">Menu</span>
                                    </div>
                                </button>
                            </div>
                            <div id="logo" class="col-sm-3">
                                <a href="<?php echo $base; ?>">
                                    <img src="<?php echo $logo;?>" title="Rothrugs" alt="Rothrugs">
                                </a>
                            </div>

                            <div class="menu col-sm-9 hidden-xs text-right">
                                <ul>
                                    <li><a class="a-katespick" href="<?php echo $base; ?>katespick">Kate's Picks</a></li>
                                    <li><a class="a-quiz" href="<?php echo $base; ?>index.php?route=quiz">Rug Quiz</a></li>
                                    <li><a class="a-finder" href="<?php echo $base; ?>index.php?route=product/search">Shop Now</a></li>
                                    <li><a href="<?php echo $base; ?>index.php?route=information/contact">Contact Us</a></li>
                                    <li><a target="_blank" href="<?php echo $base; ?>rugs">Blog</a></li>
                                    <li><a href="<?php echo $base; ?>about_us">About Us</a></li>
                                    <?php echo $search; ?>
                                    <li>
                                        <a class="a-cart" href="<?php echo $base; ?>index.php?route=checkout/cart">
                                            <i class="fa fa-shopping-basket"></i>
                                            <span>
                                                <?php if(!empty($cartCount)): ?>
                                                <?php
                                                echo $cartCount;
                                                ?>
                                                <?php else:
                                                echo 0;
                                                ?>
                                                <?php endif; ?>
                                            </span>
                                            <!-- <img src="<?php echo $base; ?>/catalog/view/theme/rr2/img/cart.png" alt="Cart"> -->
                                        </a>
                                    </li>
                                    

                                    <li class="menu-login">
                                        <?php if (!$logged) : ?>
                                        <a class="a-sign" href="index.php?route=account/login"><i class="fa fa-user"></i> Sign in</a>
                                        <?php else: ?>
                                        <a class="a-sign" href="index.php?route=account/logout"><i class="fa fa-sign-out"></i> Log out</a>
                                        <?php endif; ?>
                                    </li>
                                    <div class="minicart rem_keep" style="position:absolute;top:52px;right:22px;z-index: 9"></div>
                                </ul>
                            </div>
                            <div class="col-sm-4 header-cart-search mobile-only">
                                <ul>
                                    <?php echo $search; ?>
                                    <li>
                                        <a class="a-cart" href="<?php echo $base; ?>index.php?route=checkout/cart">
                                            <i class="fa fa-shopping-basket"></i>
                                            <span>
                                                <?php if(!empty($cartCount)): ?>
                                                <?php
                                                echo $cartCount;
                                                ?>

                                                <?php endif; ?>
                                            </span>
                                            
                                           <!--  <img src="<?php echo $base; ?>/catalog/view/theme/rr2/img/cart.png" alt="Cart"> -->
                                        </a>
                                    </li>
                                    

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="header_bottom">
                <ul>
                    <li>
                        <a href="<?php echo HTTP_SERVER.'faq?to=shipping' ?>">
                            <span class="icon_hdr shippng_icon">
                                <img src="catalog/view/theme/rr2/img/shipping_icon_blk.png">
                                <img src="catalog/view/theme/rr2/img/shipping_icon.png">
                            </span>
                            <span>Free Shipping</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo HTTP_SERVER.'faq?to=gprice' ?>">
                            <span class="icon_hdr price_icon">
                                <img src="catalog/view/theme/rr2/img/price_icon_blk.png">
                                <img src="catalog/view/theme/rr2/img/price_icon.png">
                            </span>
                            <span>Price Match</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo HTTP_SERVER.'faq?to=tax' ?>">
                            <span class="icon_hdr tax_icon">
                                <img src="catalog/view/theme/rr2/img/tax_icon_blk.png">    
                                <img src="catalog/view/theme/rr2/img/tax_icon.png">    
                            </span>
                            <span>Tax Free</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <script type="text/javascript">
            $(window).ready(function () {

                $(".a-search").click(function ($this) {
                    $(".search div").toggle();
                    if ($(window).width() < 768) {
                        $(".closebtn").fadeIn();
                    } else {
                        $(".closebtn").fadeOut();
                    }


                    return false;
                });
            });
        </script>