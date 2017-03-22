<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php if(!get_theme_mod('kotha_preloader') ): ?>
    <div id="st-preloader">
        <div id="pre-status">
            <div class="preload-placeholder"></div>
        </div>
    </div>
<?php endif; ?>
<!-- /Preloader -->

<header id="header">
	<div class="container">

		<?php if(get_theme_mod('kotha_logo')): ?>
			<div class="main-logo pull-left">
				<a href="<?php echo esc_url(site_url()); ?>"><img src="<?php echo esc_url(get_theme_mod('kotha_logo'));
					?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>"></a>
			</div><!-- /Logo -->
		<?php else: ?>
			<div class="text-logo pull-left">
				<a href="<?php echo esc_url(home_url()); ?>"><?php echo esc_attr(get_bloginfo('name')); ?></a>
				<p><?php echo esc_attr(get_bloginfo('description')); ?></p>
			</div><!-- /Logo -->
		<?php endif; ?>

		<div id="navigation-wrapper" class="pull-right">
			<?php wp_nav_menu( array('container' => false, 'theme_location' => 'main-menu', 'menu_class' => 'menu')); ?>
		</div>
		<div class="menu-mobile"></div>
	</div>
</header>



