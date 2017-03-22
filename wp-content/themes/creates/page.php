<?php /*
Template Name: Full Width
*/get_header(); ?>

<div class="container">

    <div class="row">
        
        <div class="col-xs-12 body">
            <h3 class="title" id="MENU_title1" ><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
<div id="blog">
<?php if(have_posts()) : ?><?php while(have_posts()) : the_post(); ?>
 
<div class="post">



    <div class="entry">
      <div class="feature_img"><?php the_post_thumbnail(); //col-sm-9 col-sm-3?></div>
    <?php the_content(); ?>
    
     <p>
    
   
   </p>
        
    </div>
   

</div>
 


<?php endwhile; ?>

<?php endif; ?>
</div>
</div>
<div class=" sidebar col-xs-12 ">
  <?php get_sidebar(); ?>
 </div>
</div>

</div>


<?php get_footer(); ?>
 <?php wp_footer(); ?> 