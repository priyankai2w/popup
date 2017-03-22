<?php get_header(); ?>
<p>hi this is sample page</p>
		<?php 
	
	if( have_posts() ):
		
		while( have_posts() ): the_post(); ?>
			<h1><?php the_title(); ?></h1>
			<p><?php the_content(); ?></p>
			
	
			
			<hr>
		
		<?php endwhile;
		
	endif;
			
	?>

<?php get_footer(); ?>

