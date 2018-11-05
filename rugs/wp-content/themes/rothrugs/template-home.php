<?php
/**
* Template Name: Home Page 
*/
 
get_header();
the_post();
?>
<section class="inner_banner">
	<div class="container">
    	<div class="row">
        	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <div class="inner_title">Roth RUGS BLOG</div>
              <div class="inr_txt2">A resource for thoughtfully crafting an artful lifestyle and tips for desiging spaces <br>and enviroments using interior design.</div>
            </div>
        </div>
    </div>
</section>
<div class="blog_main">
  <div class="container Inspiration">
  	<div class="row">
  	<div class="col-md-8 col-sm-8">
		<?php
        $args =	array (
            'post_type'    => 'post',
            'post_status'  => 'publish',
            'order'		   => 'DESC',
            'posts_per_page'  => 4,
        );
        
        $wp_query = new WP_Query( $args );
        
        if ( $wp_query->have_posts() ) 
        {
            while ( $wp_query->have_posts() ) : $wp_query->the_post();
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
                        	<a href="http://instagram.com/rothrugs" target="_blank">
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
                            <img src="<?php echo get_template_directory_uri();?>/images/shr4.png" />
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="clearfix"></div>
            </div>            
            <div class="blog_tag">| TAGGED: | <?php wp_tag_cloud(''); ?></div>
        </div>
    <?php endwhile; } ?>
    </div>
    <?php get_sidebar();?>
    </div>
  </div>
</div>
<?php get_footer();?>