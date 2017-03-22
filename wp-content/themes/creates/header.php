<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php bloginfo('name');?><?php wp_title ( '|', true,'right' ); ?></title>
 
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
 
<?php
    /* 
     *  Add this to support sites with sites with threaded comments enabled.
     */
    if ( is_singular() && get_option( 'thread_comments' ) )
        wp_enqueue_script( 'comment-reply' );
 
    wp_head();
     
    wp_get_archives('type=monthly&format=link');
?>
</head>
<body>
 	
<div class="container wrapper">
		
			<div class="row">
				
				<div class="col-xs-12">
					
					<nav class="navbar navbar-default head_div">
					  <div class="container-fluid">
					    <!-- Brand and toggle get grouped for better mobile display -->
					    <div class="navbar-header">
					      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					        <span class="sr-only">Toggle navigation</span>
					        <span class="icon-bar"></span>
					        <span class="icon-bar"></span>
					        <span class="icon-bar"></span>
					      </button>
					      <a class="navbar-brand" href="#"><p id="theme_logo"> <span id="logo_text">Jame's</span> Craft's<?php if ( get_theme_mod( 'm1_logo' ) ) : ?>
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" id="site-logo" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
 
        <img src="<?php echo get_theme_mod( 'm1_logo' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
 
    </a>
 
    <?php else : ?>
               
    <hgroup>
        <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
        <p class="site-description"><?php bloginfo( 'description' ); ?></p>
    </hgroup>
               
<?php endif; ?></a></p></a>
					    </div>

						<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

							<?php 
								wp_nav_menu(array(
									'theme_location' => 'primary',
									'depth'		 => 0,
									'container' => false,
									'menu_class' => 'nav navbar-nav navbar-right',
									'walker'	 => new BootstrapNavMenuWalker()
									)
								);
							?>
						</div>
					  </div><!-- /.container-fluid -->
					</nav>
	

				</div>
				
			</div>
			
			
    <img src="<?php header_image(); ?>" height="<?php echo get_custom_header()->height; ?>" width="<?php echo get_custom_header()->width; ?>" alt="" />

