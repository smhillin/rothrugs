<?php
/**
* Template Name: Common Page 
*/
 
get_header();
the_post();
?>
<section class="modrn_rug_title">
    <div class="container">
    	<div class="row">
        	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            	<div class="mdr_brd_l"></div>
             	<h2><?php the_title();?></h2>
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
                        <?php if(get_the_ID()==229) {?>
                        <div class="shagrugs">SHAG RUGS<br><p>View shag area rugs ></p></div>
                        <?php }?>
                        <?php if(get_the_ID()==94) {?>
                        <div class="casual">Casual <br><span>AREA RUGS</span><br><p>View area rugs ></p></div>
                        <?php }?>
                        <?php if(get_the_ID()==92) {?>
                        <div class="outdoor">OUTDOOR <br><span>Space Rugs</span><br><p>View area rugs ></p></div>
                        <?php }?>
                        <?php if(get_the_ID()==90) {?>
                        <div class="southwest">SOUTHWEST <br><span>inspired rugs</span><br><p>View area rugs ></p></div>
                        <?php }?>
                    </a>
                </div>
            </div>
            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
            	<div class="modern_area_rugs">
                    <a href="<?php the_field('modern_area_rugs_link'); ?>">
                        <img src="<?php the_field('modern_area_rugs_image'); ?>" alt="" title="" />
                        <?php if(get_the_ID()==229) {?>
                        <div class="shagrighttop">
                            <div class="shagright">SHAG</div>
                            <div class="shagright1">ESSENTIALS</div>
                            <div class="shagright2"><p>View deep pile area rugs ></p></div>
                        </div>
                        <?php }?>
                        <?php if(get_the_ID()==94) {?>
                        <div class="casualright">
                            <div class="casualright1">Neutral</div>
                            <div class="casualright2">ESSENTIALS</div>
                            <div class="casualright3"><p>View neutral color area rugs ></p></div>
                        </div>
                        <?php }?>
                        <?php if(get_the_ID()==92) {?>
                        <div class="outdoorright">
                            <div class="outdoorright1">OUTDOOR</div>
                            <div class="outdoorright2">ESSENTIALS</div>
                            <div class="outdoorright3"><p>View outdoor area rugs ></p></div>
                        </div>
                        <?php }?>
                        <?php if(get_the_ID()==90) {?>
                        <div class="southwestright">
                            <div class="southwestright1">SOUTHWEST</div>
                            <div class="southwestright2">ESSENTIALS</div>
                            <div class="southwestright3"><p>View our essentials ></p></div>
                        </div>
                        <?php }?>
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
                <a href="<?php the_sub_field('link'); ?>">
                    <div class="bedroom_rugs">
                        <img src="<?php the_sub_field('image'); ?>" alt="" title="" />
                        <div class="rug_tpbx"><?php the_sub_field('title'); ?><br><p><?php the_sub_field('link_text'); ?></p></div>
                    </div>
                </a>
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
                <a href="<?php the_sub_field('link'); ?>">
                    <div class="bedroom_rugs">
                        <img src="<?php the_sub_field('image'); ?>" alt="" title="" />
                        <div class="rug_tpbx"><?php the_sub_field('title'); ?><br><p><?php the_sub_field('link_text'); ?></p></div>
                    </div>
                </a>
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