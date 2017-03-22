<?php /*
Template Name: page with left sidebar
*/get_header(); ?>

<div class="container">

    <div class="row">
        
        <div class=" col-xs-9 col-sm-12">
            <h3 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
<div id="blog">
<?php if(have_posts()) : ?><?php while(have_posts()) : the_post(); ?>
 
<div class="post">



    <div class="entry">
      <div class="feature_img"><?php the_post_thumbnail(); //col-sm-9 col-sm-3?></div>
    <?php the_content(); ?>
    
     <p>
    <small>Posted on: <?php the_time('F j, Y'); ?> at <?php the_time('g:i a'); ?></small>
    <small><?php the_category(' '); ?> || <?php edit_post_link(); ?></small>
   </p>
        
    </div>
   

</div>
 


<?php endwhile; ?>

<?php endif; ?>
</div>
</div>
<div class=" col-xs-3 col-sm-12 sidebar  ">
  <?php get_sidebar(); ?>
 </div>
</div>

</div>

 <?php wp_footer(); ?> 
<?php get_footer(); ?>