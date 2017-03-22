<?php get_header(); ?>
 <div class="container">

    <div class="row">
        
        <div class="col-xs-12 body">
            <h3 class="title" id="MENU_title2">Blog</a></h3>
    <div id="blog">
        <?php if(have_posts()) : ?><?php while(have_posts()) : the_post(); ?>
         
        <div class="post">
        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
         
            <div class="entry">   
                <div class="feature_img"><?php the_post_thumbnail('medium'); ?></div>
                <?php the_content(); ?>
                
               
 
                <p class="postmetadata">
                <small> <?php edit_post_link(); ?></small>
               
                </p>
 
            </div>
        </div>
<?php endwhile; ?>
         
       
        
         
        <?php endif; ?>
        
    </div>
    </div>
    <div class=" sidebar col-xs-12 col-sm-4">
  <?php get_sidebar(); ?>
 </div>
</div>

</div>



 <?php wp_footer(); //join the top and bottom ?> 
<?php get_footer(); ?>