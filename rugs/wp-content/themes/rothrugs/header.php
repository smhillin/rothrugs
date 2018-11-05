<?php session_start();?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>
<?php wp_title( '|', true, 'right' ); ?>
</title>
<link rel="stylesheet" href="<?php echo bloginfo('template_url') ?>/css/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo bloginfo('template_url') ?>/style.css">
<link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/moble-style.css">
<link rel="stylesheet" href="<?php echo bloginfo('template_url') ?>/css/font-awesome.min.css">
<style type="text/css">
  .top-header {
    background: #f5b335;
    /* height: 40px; */
    height: auto;
    position: relative;
    top: 0;
    transition: top 0.2s ease-in-out;
    width: 100%;
    color: #fff;
    text-align: center;
    padding: 10px 15px;
    background-color: #00283A;
    font-size: 15px;
}
.search-mobile a{
    background: red;
    padding: 7px 14px;
    margin: 1px 13px;
    display: inline-block;
    text-align: center;
}
#search-all{
    width:80%;
    margin:auto;
}
#search form{
    margin: 2px 9px;
}
</style>
<script src="<?php echo bloginfo('template_url') ?>/js/jquery-1.10.2.js"></script>
<script src="<?php echo bloginfo('template_url') ?>/js/bootstrap.min.js"></script>

<?php
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, "https://rothrugs.com/index.php?route=api/announcement");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($curl);
curl_close($curl);
$res = json_decode($result);

$announcement_bar_status = isset($res->announcement_bar_status) ? $res->announcement_bar_status : '';
$announcement_bar_bar_text_1 =isset($res->announcement_bar_bar_text_1) ? $res->announcement_bar_bar_text_1 : '';
$announcement_bar_bar_text_2 =isset($res->announcement_bar_bar_text_2) ? $res->announcement_bar_bar_text_2 : '';
$announcement_bar_bar_text_3 =isset($res->announcement_bar_bar_text_3) ? '' : '';

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


<!-- Social Share Kit CSS -->
<?php /*?><link rel="stylesheet" href="<?php echo bloginfo('template_url') ?>/social/css/social-share-kit.css?v=1.0.6"><?php */?>

<!-- Font Awesome icons -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries for example page --><!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->

<!-- Share cards and meta -->
<meta property="og:site_name" content="Roth Rugs">
<meta property="og:image" content="https://www.rothrugs.com/img/avatar.png">

<script>
function showsear() {
	$('.search').toggle();
}

function check_small_search_form() {
	var para = document.getElementById('search-all').value;
	window.location="https://rothrugs.com/index.php?route=product/search&search="+para;
	return false;
}
</script>

<?php wp_head();?>

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
        </style>    
        <script type="text/javascript" src="//s3.amazonaws.com/downloads.mailchimp.com/js/signup-forms/popup/embed.js" data-dojo-config="usePlainJson: true, isDebug: false"></script><script type="text/javascript">require(["mojo/signup-forms/Loader"], function(L) { L.start({"baseUrl":"mc.us8.list-manage.com","uuid":"2dcd04b37e3013a5e00f9b327","lid":"67b3a03874"}) })</script> 
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
</head>
<body <?php //body_class();?>>

<script>
 (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
 (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
 m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
 })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
 ga('create', 'UA-76631944-1', 'auto');
 ga('send', 'pageview');
</script>

<div id="menu_popup">
  <h2>MENU <span style="cursor:pointer;margin-left:30px;"><img id="menuimgs" src="<?php echo get_template_directory_uri()?>/images/cross.png" /></span></h2>
  <div class="clearfix"></div>
  <div id="responsive-menu">
    <div class="menu-header-container">
      <?php wp_nav_menu( array( 'theme_location' => 'header' )); ?>
        <div class="search-mobile">
            <a onclick="showsear()"><i class="fa fa-search" aria-hidden="true"></i></a>
       </div>
        <div class="search">
            <div style="display: block;" id="search">
                <form method="get" onSubmit="return check_small_search_form()">
                    <input name="search" id="search-all" value="" class="form-control" placeholder="What are you looking for?" type="text">
                    <input value="" class="inp_srch" type="submit">
                </form>
            </div>                    
        </div>
    </div>
  </div>
</div>
<div id="page-content">
<div class="container-fluid">
<header>
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
  <div class="container">
    <div class="header1">
      <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
          <div class="logo_div">
            <?php // Logo
			$header_image = get_header_image();
			if ( ! empty( $header_image ) ) : ?>
            <a href="https://rothrugs.com/"> 
            <img src="<?php echo esc_url( $header_image ); ?>" class="header-image" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="" /> 
            </a>
            <?php endif; ?>
          </div>
          <div class="white_header">
            <div class="top_menus">
                <a style="cursor:pointer"><img id="menuimg" src="<?php echo get_template_directory_uri()?>/images/menu.png" /></a>
            </div>
          </div>
        </div>
        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
        	<div class="sign_in"><a href="https://rothrugs.com/index.php?route=account/login">Sign In</a></div>
            <div class="clearfix"></div>
            <div class="menu_crt">
                <div class="top_menu">
                    <?php wp_nav_menu( array( 'theme_location' => 'header' )); ?>
                </div>
                <div class="tp_cart">
                <a href="https://rothrugs.com/index.php?route=checkout/cart">
                	<i class="fa fa-shopping-cart" aria-hidden="true"></i>
                </a>
                </div>
                <div class="tp_search">
                <a onClick="showsear()">
               		<i class="fa fa-search" aria-hidden="true"></i>
                </a>
                </div>
                <div class="clearfix"></div>

                <div class="search">
                    <div style="display: block;" id="search">
                    	<form method="get" onSubmit="return check_small_search_form()">
                        <input name="search" id="search-all" value="" class="form-control" placeholder="What are you looking for?" type="text">
                        <input value="" class="inp_srch" type="submit">
                        </form>
                    </div>                    
                </div>

            </div>
        </div>
      </div>
    </div>
  </div>
</header>
<div class="desktoph"></div>