<div class="col-md-4 col-sm-4">
    <div class="blg_right">
    <div class="the_author">
        <img src="<?php echo get_template_directory_uri();?>/images/author.jpg" />
        <div class="author_txt">The  Authors: Kate Roth</div>
    </div>
    <div class="srch_div">
        <?php get_search_form(); ?>
    </div>
    <div class="follow_dv">
        <div class="flow_tx">FOLLOW US <br><span>TO LEARN MORE AND BE <br>THE FIRST TO SAVE!</span></div>
        <div class="flow_social">
            <ul>
                <li><a href="https://www.pinterest.com/rothrugs/" target="_blank"><img src="<?php echo get_template_directory_uri();?>/images/pin.png" /></a></li>
                <li><a href="https://instagram.com/rothrugs" target=""><img src="<?php echo get_template_directory_uri();?>/images/inst.png" /></a></li>
                <li><a href="https://www.facebook.com/RothRugs?ref=hl" target="_blank"><img src="<?php echo get_template_directory_uri();?>/images/fb.png" /></a></li>
                <li><a href="https://twitter.com/rothrugs" target="_blank"><img src="<?php echo get_template_directory_uri();?>/images/tw.png" /></a></li>                                
            </ul>
        </div>
    </div>
    <div class="newsletter_dv">
        <div class="news_tx">Subscribe by Email</div>
        <?php dynamic_sidebar('email-newsletter'); ?>
    </div>
    <div class="popular_tag">
        <div class="tag_tx">POPULAR TAGS</div>
        <div class="tag_list">
            <ul>
                <li><?php wp_tag_cloud(''); ?></li>
            </ul>
        </div>
    </div>
    </div>
    <div class="clearfix"></div>
</div>