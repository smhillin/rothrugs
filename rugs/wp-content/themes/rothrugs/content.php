<?php
/**
 * Blog post details.
 */
?>

<div class="blog_bx">
    <div class="blog_title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
    <div class="date_cmnt"><?php echo date('F j, Y') ?> —  <?php echo comments_number(); ?></div>
    <div class="blog_img">
        <a href="<?php the_permalink(); ?>">
            <?php
            $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
            if( $image!='') {?>
            <img src="<?php echo $image[0] ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" />
            <?php } ?>
        </a>
    </div>
    <div class="blog_exerpt"><?php the_excerpt(); ?></div>
    <div class="blg_rdmore"><a href="<?php the_permalink(); ?>">READ MORE</a></div>
    <div class="share_dv">
        <div class="share_left">Share this post:</div>
        <div class="share_right">
            <ul>
                <li>
                    <a href="" class="ssk ssk-text ssk-pinterest ssk-lg" data-url="<?php echo get_the_permalink();?>">
                    <img src="<?php echo get_template_directory_uri();?>/images/shr1.png" />
                    </a>
                </li>
                <li>
                    <a href="https://instagram.com/rothrugs" target="_blank">
                    <img src="<?php echo get_template_directory_uri();?>/images/shr2.png" />
                    </a>
                </li>
                <li>
                    <a href="" class="ssk ssk-text ssk-facebook ssk-lg" data-url="<?php echo get_the_permalink();?>">
                    <img src="<?php echo get_template_directory_uri();?>/images/shr3.png" />
                    </a>
                </li>
                <li>
                    <a href="" class="ssk ssk-text ssk-twitter ssk-lg" data-url="<?php echo get_the_permalink();?>">
                    <img src="<?php echo get_template_directory_uri();?>/images/twitter.png" />
                    </a>
                </li>
            </ul>
        </div>
        <div class="clearfix"></div>
    </div>            
    <div class="blog_tag">| TAGGED: | <?php wp_tag_cloud(''); ?></div>
</div>