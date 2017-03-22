<?php get_header(); ?>
<p>hi this is service page</p>
 <!-- <h3 class="title" id="MENU_title1" ><a href="<?php //the_permalink(); ?>"><?php //the_title(); ?></a></h3> -->
		<?php 
	
	if( have_posts() ):
		
		while( have_posts() ): the_post(); ?>
				<h3><?php the_title(); ?></h3>
			<p><?php the_content(); ?></p>
			
		
			
			<hr>
		
		<?php endwhile;
		
	endif;
			
	?>

<?php get_footer(); ?>
