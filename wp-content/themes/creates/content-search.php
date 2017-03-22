    
        <div class="post">
        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <div class="entry">   
                <div class="feature_img"><?php the_post_thumbnail(); ?></div>
                <?php the_excerpt(); ?>
 
               
 
            </div>
 
            <div class="comments-template">
                <?php comments_template(); ?>
            </div>
    </div>