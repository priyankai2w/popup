<?php get_header(); ?>
 <div class="container">
        
            <div class="row">
                
                <div class="col-xs-12 body">
        <h3 class="title" id="MENU_title" ><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
    <div id="blog">
        <?php if(have_posts()) : ?><?php while(have_posts()) : the_post(); ?>
         
        <div class="post">
 
            <div class="entry">   
                <div class="feature_img"><?php the_post_thumbnail(); ?></div>
                <?php the_content(); ?>
 
                <p class="postmetadata">
               
                
                </p>
 
            </div>
 
            
    </div>
 
<?php endwhile; ?>
    

   
<?php endif; ?>
</div>

 </div></div>
 <div class="sidebar col-xs-12 col-sm-4">
   <?php get_sidebar(); ?>
 </div>

</div>
 

<!-- <h2>Error 404 - Not Found</h2> -->
 <?php wp_footer(); ?>
<?php get_footer(); ?>
