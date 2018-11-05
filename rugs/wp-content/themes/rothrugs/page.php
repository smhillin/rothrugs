<?php
get_header();
the_post();
?>
<section class="inner_banner">
	<div class="container">
    	<div class="row">
        	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
              <div class="inner_title"><?php the_title();?><br><span>We're dedicated to providing diagnosis and treatment</span></div>
            </div>
        </div>
    </div>
</section>
<section class="about" style="border-bottom:none">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
       <div class="abt_left">
       	<?php the_content();?>
       </div>
      </div>      
    </div>
  </div>
</section>

<?php get_footer();?>
