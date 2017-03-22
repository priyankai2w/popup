<?php
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
function my_theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_register_script( 'datetimer', get_stylesheet_directory_uri() . '/js/datetimer.js', array('jquery'),'1.0',true );
    wp_enqueue_script('datetimer');
    
}
function ibenic_add_style_and_script() {
	wp_enqueue_style( 'ibenic-bootstrap-css', "https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" );
	wp_enqueue_script( 'ibenic-bootstrap-js', "https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js", array('jquery'), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'ibenic_add_style_and_script' );
?>
