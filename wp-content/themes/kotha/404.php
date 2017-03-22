<?php get_header(); ?>
<div class="st-content">
    <div class="container">
        <div class="row">
        <div class="col-sm-12">
			<div id="primary" class="content-area padding-content white-color">
				<main id="main" class="site-main" role="main">

					<section class="error-404 not-found text-center">
						<h1 class="404"><?php _e('404', 'kotha') ?></h1>
						<p class="lead"><?php _e('Sorry, we could not found the page you are looking for!', 'kotha') ?></p>

						<div class="row">
							<div class="col-sm-4 col-sm-offset-4">
								<?php get_search_form(); ?>
							</div>
						</div>
						
					</section><!-- .error-404 -->

				</main><!-- #main -->
			</div><!-- #primary -->
		</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>
