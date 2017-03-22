<div>
<header class="entry-header">
        <?php the_title( sprintf('<h1 class="entry-title"><a href="%s">', esc_url( get_permalink() ) ),'</a></h1>' ); ?>
       
    </header>
  <div class="post">
        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <div class="entry">   
                <div class="feature_img"><?php the_post_thumbnail(); ?></div>
                <?php the_excerpt(); ?>
               <small>Posted on: <?php the_time('F j, Y'); ?> at <?php the_time('g:i a'); ?>, in <?php the_category(' '); ?></small>  
               
 
            </div>
 
    </div>
</div>
 
