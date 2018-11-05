<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>

<section class="inner_banner">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="inner_title">Roth RUGS BLOG</div>
        <div class="inr_txt2">A resource for thoughtfully crafting an artful lifestyle and tips for desiging spaces <br>
          and enviroments using interior design.</div>
      </div>
    </div>
  </div>
</section>
<div class="blog_main">
  <div class="container Inspiration">
    <div class="row">
      <div class="col-md-8 col-sm-8">
        <?php if ( have_posts() ) : ?>
        <div class="blg_title"> Search <?php echo get_search_query() ?> </div>
        <div class="clearfix">&nbsp;</div>
        <?php //jifftech_content_nav( 'nav-above' ); ?>
        <?php /* Start the Loop */ ?>
        <?php while ( have_posts() ) : the_post(); ?>
        <?php get_template_part( 'content', get_post_format() ); ?>
        <?php endwhile; ?>
        <?php //jifftech_content_nav( 'nav-below' ); ?>
        <?php else : ?>
        <article id="post-0" class="post no-results not-found">
          <h1 class="entry-title">
            <?php _e( 'Nothing Found', 'jifftech' ); ?>
          </h1>
          <div class="entry-content">
            <p>
              <?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'jifftech' ); ?>
            </p>
            <?php //get_search_form(); ?>
          </div>
        </article>
        <?php endif; ?>
      </div>
      <?php get_sidebar();?>
    </div>
  </div>
</div>
<?php get_footer();?>