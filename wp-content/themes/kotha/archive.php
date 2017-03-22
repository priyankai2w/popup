<?php get_header(); ?>

	<div class="st-content">
		<div class="container">
			<div class="row">
				<div class="
				<?php if (get_theme_mod( 'kotha_home_layout' ) == 'full') { ?>
					call-md-12
				<?php } else { ?>
					col-md-8
				<?php }
				 ?>
				">
					<div id="primary" class="content-area">
						<main id="main" class="site-main" role="main">

						<?php if ( have_posts() ) : ?>

							<header class="search-header">
								<?php
									kotha_archive_title( '<div class="search-title">', '</div>' );
									the_archive_description( '<div class="taxonomy-description">', '</div>' );
								?>
							</header><!-- .page-header -->

							<?php while ( have_posts() ) : the_post(); ?>

								<?php
									/* Include the Post-Format-specific template for the content.
									 * If you want to override this in a child theme, then include a file
									 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
									 */
									get_template_part( 'content', 'post' );
								?>

							<?php endwhile; ?>

							<?php
							 // Posts Pagination
							if (get_theme_mod('kotha_blog_pagination') == 'navigation') {
								kotha_posts_navigation();
							} else {
								kotha_posts_pagination();
							} ?>

						<?php else : ?>

							<?php get_template_part( 'content', 'none' ); ?>

						<?php endif; ?>

						</main><!-- #main -->
					</div><!-- #primary -->

				</div> <!-- /col -->
				<!-- Blogsidebar -->			
				<?php get_sidebar(); ?>
			</div> <!-- /.row -->
		</div> <!-- /.container -->
	</div> <!-- /.st-content -->

<?php get_footer(); ?>
