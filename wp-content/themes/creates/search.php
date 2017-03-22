<?php get_header(); ?>
 
    <div id="blog">
        <?php if(have_posts()) : ?><?php while(have_posts()) : the_post(); ?>
         <?php get_template_part('content','search'); ?>
        
<?php endwhile; ?>
         
        <div class="navigation">
        <?php posts_nav_link(); ?>
        </div>
         
        <?php endif; ?>
    </div>
<?php get_sidebar(); ?>   
<?php get_footer(); ?>