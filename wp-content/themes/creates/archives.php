<?php get_header(); ?>
 
    <div id="blog">
        <?php if(have_posts()) : ?><?php while(have_posts()) : the_post(); ?>
      
         <small>Posted on: <?php the_time('F j, Y'); ?> at <?php the_time('g:i a'); ?>, in <?php the_category(' '); ?></small>  
         <?php get_template_part('content','archives'); ?>

<?php endwhile; ?>
     <div class="col-xs-12 text-center">
        <?php the_posts_navigation(); ?>
        </div>    
        <div class="navigation">
        <?php posts_nav_link(); ?>
        </div>
        
        <?php endif; wp_reset_query();?>
        <div class="col-xs-12 col-sm-4">
    <?php get_sidebar(); ?>
</div>
         

    </div>
 
<?php get_footer(); ?>