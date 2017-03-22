<?php get_header(); ?>

	<div class="st-content">
		<div class="container">
			<div class="row">
				<div class="
				<?php if (get_theme_mod( 'kotha_page_layout' ) == 'full') { ?>
					call-md-12
				<?php } else { ?>
					col-md-8
				<?php }
				 ?>
				">
					<div id="primary" class="content-area">
						<main id="main" class="site-main" role="main">

							<?php while ( have_posts() ) : the_post(); ?>

								<?php get_template_part( 'content', 'page' ); ?>

								<?php if (!get_theme_mod('kotha_page_comments')) : ?>
									
									<?php
										// If comments are open or we have at least one comment, load up the comment template
										if ( comments_open() || get_comments_number() ) : ?>
											<div class="padding-content white-color">
												<?php comments_template(); ?>
											</div>
										<?php endif;
									?>
									
								<?php endif; ?>
							<?php endwhile; // end of the loop. ?>

						</main><!-- #main -->
					</div><!-- #primary -->
				</div> <!-- /col -->
				<!-- Blogsidebar -->
				<?php if (get_theme_mod( 'kotha_page_layout' ) == 'full') {
					
				} else {?>
					<div class="col-md-4 col-sm-5">
			            <div class="primary-sidebar widget-area" role="complementary">
			                <?php dynamic_sidebar('blog-sidebar'); ?>
			            </div>
			        </div>
				<?php }
				 ?>

			</div> <!-- /.row -->
		</div> <!-- /.container -->
	</div> <!-- /.st-content -->

<?php get_footer(); ?>
