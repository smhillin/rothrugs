<div id="footer">
  <div class="container">
    <div class="footerblock_mian clearfix">
      <div class="footer_block firstclass">
        <div class="footer-head">
          <img src="catalog/view/theme/rr2/img/logo.png">
        </div>
        <div class="about_footer">
          <p>We want to change the way you shop for rugs online. Through our curated selection, personalized concierge, and 150% price match guarantee, we promise to bring you the best rug shopping experience on the internet.</p>
        </div>
      </div>
      <div class="footer_block secondclass">
        <div class="footer-head">
          <h4>General Links</h4>
        </div>
        <ul>
            <li><a href="<?php echo $base; ?>index.php?route=information/sitemap">Sitemap</a></li>
            <li><a href="<?php echo $base; ?>index.php?route=quiz">Rug Quiz</a></li>
            <li><a href="https://rothrugs.wordpress.com/" target="_blank">Blog</a></li>
            <li><a href="<?php echo $base; ?>index.php?route=product/search">Rug Finder</a></li>
            <li><a href="<?php echo $base; ?>about_us">About Us</a></li>
            <li><a href="<?php echo $base; ?>index.php?route=information/contact">Contact Us</a></li>
            <li><a href="<?php echo $base; ?>katespick">Kate's Picks</a></li>
            <li><a href="<?php echo $base; ?>faq">FAQs</a></li>
            <li><!--<a href="<?php echo $base; ?>membership">Membership</a>--></li>
            <li><a href="<?php echo $base; ?>terms-conditions">Terms & Conditions</a></li>            
        </ul>
      </div>
      <div class="footer_block thirdclass">
        <div class="footer-head">
          <h4>Products</h4>
        </div>
        <ul>
            <li><a href="<?php echo $base; ?>rugs/casual/">Casual Rugs</a></li>
            <li><a href="<?php echo $base; ?>rugs/modern/">Modern Rugs</a></li>
            <li><a href="<?php echo $base; ?>rugs/outdoor/">Outdoor Rugs</a></li>
            <li><a href="<?php echo $base; ?>rugs/shag/">Shag Rugs</a></li>
            <li><a href="<?php echo $base; ?>rugs/southwest/">Southwestern Rugs</a></li>
            <li><a href="<?php echo $base; ?>rugs/rug-pads/">Rug Pads</a></li>
            <li><a href="<?php echo $base; ?>rugs/traditional/">Traditional Rugs</a></li>
            <li></li>
        <ul>
      </div>
      <div class="footer_block forthclass">
        <div class="footer-head">
          <h4>Need Help?</h4>
        </div>
        <div class="">
          <div class="">
            <div class="ftr_contact_box">
              <a href="tel:8887766425"><img src="catalog/view/theme/rr2/img/phone_icon.png"> (888) 776 - 6425</a>
              <ul class="list-inline">
                <li><a href="https://instagram.com/rothrugs" target="_blank" rel="nofollow"><i class="fa fa-instagram"></i></a></li>
                <li><a href="https://www.facebook.com/RothRugs?ref=hl" target="_blank" rel="nofollow"><i class="fa fa-facebook"></i></a></li>
                <li><a href="https://www.pinterest.com/rothrugs/" target="_blank" rel="nofollow"><i class="fa fa-pinterest"></i></a></li>
                <li><a href="https://twitter.com/rothrugs" target="_blank" rel="nofollow"><i class="fa fa-twitter"></i></a></li>
              </ul>
            </div>
          </div>

          <div class="corporate_logo">
            <div class="logo_sec"> 
              <a href="https://www.bbb.org/pittsburgh/business-reviews/carpet-and-rug-dealers-new/rothrugs-com-in-pittsburgh-pa-71012499/#bbbonlineclick" target="_blank" rel="nofollow"><img src="https://seal-westernpennsylvania.bbb.org/seals/blue-seal-160-82-bbb-71012499.png" style="border: 0;" alt="Rothrugs.com BBB Business Review" /></a>
            </div>
            <div class="logo_sec">
              <!-- (c) 2005, 2017. Authorize.Net is a registered trademark of CyberSource Corporation --> 
              <div class="AuthorizeNetSeal" style="margin:0 auto">
                <script type="text/javascript" language="javascript">
                  var ANS_customer_id="e8f8adfd-d16a-4477-bd0a-3a9244bdcfa1";
                </script> 
                <script type="text/javascript" language="javascript" src="//verify.authorize.net/anetseal/seal.js" ></script> 
                <a rel="nofollow" style="font: 300 8px Lato; color: #fff;" href="https://www.authorize.net/" id="AuthorizeNetText" target="_blank">Credit Card Processing</a>
              </div>
            </div>
            <div class="logo_sec paypalseal">
              <!-- Paypal Advertising Banner Code -->
              <script type="text/javascript" data-pp-payerid="MCJTQG986Y7WC" data-pp-placementtype="120x90"> (function (d, t) {
              "use strict";
              var s = d.getElementsByTagName(t)[0], n = d.createElement(t);
              n.src = "//www.paypalobjects.com/upstream/bizcomponents/js/merchant.js";
              s.parentNode.insertBefore(n, s);
              }(document, "script"));
              </script>
              <!-- Paypal Advertising Banner Code ends-->
            </div>
          </div>
        </div>
      </div>
    </div>
      
  </div>
  <div class="copy text-center">
    <p>Copyright © <?php echo date("Y"); ?> RothRugs.com, All Rights Reserved</p>
  </div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$(".AuthorizeNetSeal").find('a').attr('rel','nofollow');
    checkContainer();
	});

   $(document).ready(function() {
      setTimeout(function() {
        if($(".mc-modal").attr('style') == undefined){
          console.log($(".mc-modal").attr('style'));
          $("#PopupSignupForm_0").css('display','none');
        }
      }, 4000);
    });
    function checkContainer () {
        if($('.paypalseal > span > div > a').is(':visible')){ //if the container is visible on the page
          console.log($('.paypalseal > span > div > a').attr('href'));
          $('.paypalseal > span > div > a').attr('rel','nofollow');
        } else {
          console.log("not in");
          setTimeout(checkContainer, 50); //wait 50 ms, then try again
        }
     }
</script>
<!-- Google Code for Remarketing Tag -->
<!--
Remarketing tags may not be associated with personally identifiable information or placed on pages related to sensitive categories. See more information and instructions on how to setup the tag on: http://google.com/ads/remarketingsetup
-->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 990455000;
var google_custom_params = window.google_tag_params;
var google_remarketing_only = true;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/990455000/?guid=ON&script=0"/>
</div>
</noscript>
</body>