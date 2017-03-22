<?php

//////////////////////////////////////////////////////////////////
// Set Content Width
//////////////////////////////////////////////////////////////////

if ( ! isset( $content_width ) ) {
		$content_width = 1170;
	}



//////////////////////////////////////////////////////////////////
// Theme set up
//////////////////////////////////////////////////////////////////

if ( ! function_exists( 'kotha_theme_setup' ) ) :

function kotha_theme_setup() {

	// Localization support
	load_theme_textdomain( 'kotha', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Title Tag
	add_theme_support( 'title-tag' );

	// Register navigation menu
	register_nav_menus( 
		array(
			'main-menu' 		=> __( 'Primary Menu','kotha' )
		) );


	// Switch default core markup for search form, comment form, and comments to output valid HTML5.
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'kotha_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	// Post Formats
	add_theme_support( 'post-formats', array( 'gallery', 'video', 'audio' ) );

	// Post thumbnails
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'post-thumbnails', 1140, 600, TRUE );
	add_image_size( 'related-image', 590, 390, TRUE );
	add_image_size('xs-thumb', 60, 60, TRUE);

}
endif; // kotha_theme_setup

add_action( 'after_setup_theme', 'kotha_theme_setup' );



//////////////////////////////////////////////////////////////////
// Register widget
//////////////////////////////////////////////////////////////////

if ( function_exists('register_sidebar') ) {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'kotha' ),
		'id'            => 'blog-sidebar',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
	register_sidebar( array(
		'name'          => __( 'Footer', 'kotha' ),
		'id'            => 'footer-widget',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget col-md-3 footer-widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}



//////////////////////////////////////////////////////////////////
// Enqueue scripts and styles.
//////////////////////////////////////////////////////////////////

function kotha_all_scripts_and_css() {
	
	// CSS File
	wp_enqueue_style('bootstrap-css', get_template_directory_uri() . '/assets/css/bootstrap.min.css', array(), '3.3.6', 'all');
	wp_enqueue_style('font-awesome-css', get_template_directory_uri() . '/assets/css/font-awesome.min.css', array(), '4.4.0', 'all');
	wp_enqueue_style('slicknav-css', get_template_directory_uri() . '/assets/css/slicknav.css', array(), NULL);
	wp_enqueue_style( 'kotha-stylesheet', get_stylesheet_uri() );
	wp_enqueue_style('kotha-responsive', get_template_directory_uri() . '/assets/css/responsive.css', array(), NULL);

	// Google Fonts
	wp_enqueue_style( 'google-font-open-sans', '//fonts.googleapis.com/css?family=Open+Sans:400,300,700,600', array(), NULL );


	// JS Files
	wp_enqueue_script( 'jquery-bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array('jquery'), '3.3.6', TRUE );
	wp_enqueue_script( 'jquery-smoothscroll', get_template_directory_uri() . '/assets/js/smoothscroll.js', array('jquery'), '0.9.9', TRUE );
	wp_enqueue_script( 'jquery-slicknav', get_template_directory_uri() . '/assets/js/jquery.slicknav.js', array('jquery'), NULL, TRUE );
	wp_enqueue_script( 'jquery-fitvids', get_template_directory_uri() . '/assets/js/jquery.fitvids.js', array('jquery'), '1.1', TRUE );
	wp_enqueue_script( 'kotha-scripts', get_template_directory_uri() . '/assets/js/scripts.js', array('jquery'), NULL, TRUE );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'kotha_all_scripts_and_css' );



//////////////////////////////////////////////////////////////////
// COMMENTS LAYOUT
//////////////////////////////////////////////////////////////////

if(!function_exists('kotha_comment')):

	function kotha_comment($comment, $args, $depth)
	{
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :
			case 'pingback' :
			case 'trackback' :
			// Display trackbacks differently than normal comments.
		?>
		<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
			<p><?php _e('Pingback:', 'kotha'); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', 'kotha' ), '<span class="edit-link">', '</span>' ); ?></p>
		<?php
				break;
			default :
			
			global $post;
		?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
			<div id="comment-<?php comment_ID(); ?>" class="comment-body media">
				
					<div class="comment-avartar pull-left">
						<?php
							echo get_avatar( $comment, $args['avatar_size'] );
						?>
					</div>
					<div class="comment-context media-body">
						<div class="comment-head">
							<?php
								printf( '<h3 class="comment-author">%1$s</h3>',
									get_comment_author_link());
							?>
							<span class="comment-date"><?php echo get_comment_date() ?></span>
						</div>

						<?php if ( '0' == $comment->comment_approved ) : ?>
						<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.',
								'kotha' ); ?></p>
						<?php endif; ?>

						<div class="comment-content">
							<?php comment_text(); ?>
						</div>

						<?php edit_comment_link( __( 'Edit', 'kotha' ), '<span class="edit-link">', '</span>' ); ?>
						<span class="comment-reply">
							<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'kotha'
							), 'after' => '', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
						</span>

					</div>
				
			</div>
		<?php
			break;
		endswitch; 
	}

endif;



//////////////////////////////////////////////////////////////////
// Add Extra Fields In User Profile
//////////////////////////////////////////////////////////////////

function modify_user_contact_profile($profile_fields) {

	// Add new fields
	$profile_fields['facebook'] = __('Facebook URL', 'kotha');
	$profile_fields['twitter'] = __('Twitter URL', 'kotha');
	$profile_fields['gplus'] = __('Google+ URL', 'kotha');
	$profile_fields['linkedin'] = __('Linkedin URL', 'kotha');
	$profile_fields['tumblr'] = __('Tumblr URL', 'kotha');
	$profile_fields['pinterest'] = __('Pinterest URL', 'kotha');

	return $profile_fields;
}
add_filter('user_contactmethods', 'modify_user_contact_profile');



//////////////////////////////////////////////////////////////////
// Include files
//////////////////////////////////////////////////////////////////

// Theme Customizer
include('functions/customizer/customizer_settings.php');
include('functions/customizer/color_customize.php');


//Custom Widgets 
require_once get_template_directory()  . '/inc/widgets/blog-posts.php';
require_once get_template_directory()  . '/inc/widgets/social-icons.php';
require_once get_template_directory()  . '/inc/widgets/about_widget.php';

// Custom template tags for this theme.
require_once get_template_directory() . '/inc/template-tags.php';



