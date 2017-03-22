<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

<!-- Gallery Post -->
    <?php if(has_post_format('gallery')) : ?>
    
        <?php $images = get_post_meta( $post->ID, '_format_gallery_images', true ); ?>
        
        <?php if($images) : ?>
        <div class="thumbnails">
            <div id="blog-gallery-slider" class="carousel slide" data-ride="carousel">
                <!-- Wrapper for slides -->
                <div class="carousel-inner">
                    <?php $image_no = 1; ?>
                    <?php foreach($images as $image) : ?>
                        <div class="item <?php if($image_no == 1){ echo 'active'; }; ?>">
                            <?php $the_image = wp_get_attachment_image_src( $image, 'post-thumbnails' ); ?> 
                            <?php $the_caption = get_post_field('post_excerpt', $image); ?>
                            <img src="<?php echo esc_url($the_image[0]); ?>" <?php if($the_caption) : ?>title="<?php
                            echo esc_attr($the_caption); ?>"<?php endif; ?> />
                        </div>
                        <?php $image_no++ ?>
                    <?php endforeach; ?>
                </div>
                <!-- Controls -->
                <a class="left carousel-control" href="#blog-gallery-slider" data-slide="prev">
                <i class="fa fa-angle-left"></i>
                </a>
                <a class="right carousel-control" href="#blog-gallery-slider" data-slide="next">
                <i class="fa fa-angle-right"></i>
                </a>
            </div>
        </div>
        <?php endif; ?>

<!-- Video Post -->
    <?php elseif(has_post_format('video')) : ?>
    
        <div class="thumbnails">
            <div class="entry-video">
            <?php $st_video = get_post_meta( $post->ID, '_format_video_embed', true ); ?>
            <?php if(wp_oembed_get( $st_video )) : ?>
                <?php echo wp_oembed_get($st_video); ?>
            <?php else : ?>
                <?php echo $st_video; ?>
            <?php endif; ?>
            </div>
        </div>
    
<!-- Audio Post -->
    <?php elseif(has_post_format('audio')) : ?>
    
        <div class="thumbnails">
            <div class="entry-audio">
            <?php $st_audio = get_post_meta( $post->ID, '_format_audio_embed', true ); ?>
            <?php if(wp_oembed_get( $st_audio )) : ?>
                <?php echo wp_oembed_get($st_audio); ?>
            <?php else : ?>
                <?php echo $st_audio; ?>
            <?php endif; ?>
            </div> <!--/.audio-content -->
        </div> <!--/.thumbnails -->

    <?php else : ?>
        
        <?php if(has_post_thumbnail()) : ?>
        <div class="thumbnails">
            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail('post-thumbnails', array('class' => 'post-thumbnail img-responsive')); ?></a>
        </div>
        <?php endif; ?>
        
    <?php endif; ?>

    <div class="padding-content text-center">
        <header class="entry-header">
            <?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>
        </header> <!--/.entry-header -->

        <?php if ( 'post' == get_post_type() ) : ?>
            <div class="entry-meta">
                <?php kotha_posted_on(); ?>
            </div><!-- .entry-meta -->
        <?php endif; ?>

        <div class="entry-content">
            <?php the_content(__('<span class="read-more-button">Keep Reading</span>', 'kotha')); ?>

            <?php
                wp_link_pages( array(
                    'before' => '<div class="page-links">' . __( 'Pages:', 'kotha' ),
                    'after'  => '</div>',
                ) );
            ?>
        </div><!-- .entry-content -->
    </div>

</article><!-- #post-## -->

