<?php


if ( ! is_active_sidebar(  'sidebar-2 ' ) ) {
	return;
}
?>

<div id= "supplementary ">
	<div id= "content-sidebar " class= "content-sidebar widget-area " role= "complementary ">
		<?php dynamic_sidebar(  'sidebar-2' ); ?>
	</div><!-- #content-sidebar -->
</div><!-- #supplementary -->