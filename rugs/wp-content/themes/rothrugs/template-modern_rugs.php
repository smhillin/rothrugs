<?php
/**
* Template Name: Modern Rugs Page 
*/
 
get_header();
the_post();
?>
<section class="modrn_rug_title">
    <div class="container">
    	<div class="row">
        	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            	<div class="mdr_brd_l"></div>
             	<h2>MODERN RUGS</h2>
            </div>
        </div>
    </div>
</section>
<section class="neutral_moderin">
	<div class="container">
    	<div class="row">
        	<div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
            	<div class="neutral_essential">
                    <a href="<?php the_field('neutral_essentials_link'); ?>">
                        <img src="<?php the_field('neutral_essentials_image'); ?>" alt="" title="" />
                        <div class="neutral_cntnt"><span>Neutral Essentials</span><br><span class="vw">View all  neutral rugs ></span></div>
                    </a>
                </div>
            </div>
            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
            	<div class="modern_area_rugs">
                    <a href="<?php the_field('modern_area_rugs_link'); ?>">
                        <img src="<?php the_field('modern_area_rugs_image'); ?>" alt="" title="" />
                        <div class="mdrn_ar_rg_tx">
                            <div class="mdrn_ar_rg_tx1">MODERN</div>
                            <div class="mdrn_ar_rg_tx2">Area Rugs</div>
                            <div class="mdrn_ar_rg_tx3"><p>View area rugs ></p></div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="row rugs_dvx">
        	<?php 
			$cc=1;
			while ( have_rows('rug_type')) { the_row(); 
			?>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="bedroom_rugs">
                    <a href="<?php the_sub_field('link'); ?>">
                        <img src="<?php the_sub_field('image'); ?>" alt="" title="" />
                        <div class="rug_tpbx"><?php the_sub_field('title'); ?><br><p><?php the_sub_field('link_text'); ?></p></div>
                    </a>
                </div>
            </div>
            <?php if($cc==4) { ?>   
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="shop_by">
                        <h2><?php the_field('shop_by_room_title'); ?></h2>
                        <p><?php the_field('shop_by_room_content'); ?></p>
                    </div>
                </div>
            <?php } ?>
            <?php $cc++; } ?>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="why_modern_rugs">
                    <?php the_field('picking_content'); ?>
                    <?php if(get_field('learn_about_link')) { ?>
                    <div class="learn_abt"><a href="<?php the_field('learn_about_link'); ?>">TALK TO AN EXPERT</a></div>
                    <?php }?>
                </div>
            </div>
        </div>
        <div class="row rugs_dvx">
        	<?php 
			$cc=1;
			while ( have_rows('color_type')) { the_row(); 			
			?>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="bedroom_rugs">
                    <a href="<?php the_sub_field('link'); ?>">
                        <img src="<?php the_sub_field('image'); ?>" alt="" title="" />
                        <div class="rug_tpbx"><?php the_sub_field('title'); ?><br><p><?php the_sub_field('link_text'); ?></p></div>
                    </a>
                </div>
            </div>
            <?php if($cc==4) { ?>            
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="shop_by">
                        <h2><?php the_field('shop_by_color_text'); ?></h2>
                        <p><?php the_field('shop_by_color_content'); ?></p>
                    </div>
                </div>
            <?php } ?>
            <?php $cc++; } ?>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="why_modern_rugs">
                    <?php the_field('what_are_modern_rugs_content'); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="why_modern_rugs">
                    <?php the_field('still_looking_content'); ?>
                    <?php if(get_field('try_the_rug_link')) { ?>
                    <div class="learn_abt"><a href="<?php the_field('try_the_rug_link'); ?>">TRY THE RUG QUIZ</a></div>
            		<?php } ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="why_modern_rugs why_modern_rugs1">
                	<?php the_field('kates_picks_content'); ?>
                    <?php if(get_field('venture_into_link')) { ?>
                    <div class="learn_abt"><a href="<?php the_field('venture_into_link'); ?>">VENTURE INTO KATE’S PICKS</a></div>
            		<?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php get_footer();?>