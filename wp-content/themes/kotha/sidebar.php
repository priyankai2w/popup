
    <?php if (get_theme_mod( 'kotha_home_layout' ) == 'full') {
        
    } else { ?>
        <div class="col-md-4">
            <div class="primary-sidebar widget-area" role="complementary">
                <?php dynamic_sidebar('blog-sidebar'); ?>
            </div>
        </div>
    <?php }
     ?>