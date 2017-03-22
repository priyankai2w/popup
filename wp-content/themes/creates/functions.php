<?php
 
 function craft_script_enqueue() {
    //css
    wp_enqueue_style('bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css', array(), '3.3.4', 'all');
    
    wp_enqueue_script('bootstrapjs', get_template_directory_uri() . '/js/bootstrap.min.js', array(), '3.3.4', true);
    wp_enqueue_style( 'customstyle', get_template_directory_uri() .'/css/craft_css.css', array(), '1.0.0', 'all');
  wp_enqueue_script( 'customjs', get_template_directory_uri() .'/js/craft_js.js', array(), '1.0.0', true );
    
}
add_action( 'wp_enqueue_scripts', 'craft_script_enqueue'); 


//logo
function m1_customize_register( $wp_customize ) {
    $wp_customize->add_setting( 'm1_logo' ); // Add setting for logo uploader
         
    // Add control for logo uploader (actual uploader)
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'm1_logo', array(
        'label'    => __( 'Upload Logo (replaces text)', 'm1' ),
        'section'  => 'title_tagline',
        'settings' => 'm1_logo',
    ) ) );
}
add_action( 'customize_register', 'm1_customize_register' );

 
//Register area for custom menu
function register_my_menu() {
    add_theme_support('menus');
    
    
    register_nav_menu('secondary', 'Footer Navigation');
    register_nav_menu( 'primary-menu', __( 'Primary Menu' ) );
}

//Add support for WordPress 3.0's custom menus
add_action( 'init', 'register_my_menu' );

/**
 * Extended Walker class for use with the
 * Twitter Bootstrap toolkit Dropdown menus in Wordpress.
 * Edited to support n-levels submenu.
 * @author johnmegahan https://gist.github.com/1597994, Emanuele 'Tex' Tessore https://gist.github.com/3765640
 * @license CC BY 4.0 https://creativecommons.org/licenses/by/4.0/
 */
class BootstrapNavMenuWalker extends Walker_Nav_Menu {
    function start_lvl( &$output, $depth ) {
        $indent = str_repeat( "\t", $depth );
        $submenu = ($depth > 0) ? ' sub-menu' : '';
        $output    .= "\n$indent<ul class=\"dropdown-menu$submenu depth_$depth\">\n";
    }
    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
        $li_attributes = '';
        $class_names = $value = '';
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        
        // managing divider: add divider class to an element to get a divider before it.
        $divider_class_position = array_search('divider', $classes);
        if($divider_class_position !== false){
            $output .= "<li class=\"divider\"></li>\n";
            unset($classes[$divider_class_position]);
        }
        
        $classes[] = ($args->has_children) ? 'dropdown' : '';
        $classes[] = ($item->current || $item->current_item_ancestor) ? 'active' : '';
        $classes[] = 'menu-item-' . $item->ID;
        if($depth && $args->has_children){
            $classes[] = 'dropdown-submenu';
        }
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
        $class_names = ' class="' . esc_attr( $class_names ) . '"';
        $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
        $id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';
        $output .= $indent . '<li' . $id . $value . $class_names . $li_attributes . '>';
        $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
        $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
        $attributes .= ($args->has_children)        ? ' class="dropdown-toggle" data-toggle="dropdown"' : '';
        $item_output = $args->before;
        $item_output .= '<a'. $attributes .'>';
        $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
        $item_output .= ($depth == 0 && $args->has_children) ? ' <b class="caret"></b></a>' : '</a>';
        $item_output .= $args->after;
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
    
    function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {
        //v($element);
        if ( !$element )
            return;
        $id_field = $this->db_fields['id'];
        //display this element
        if ( is_array( $args[0] ) )
            $args[0]['has_children'] = ! empty( $children_elements[$element->$id_field] );
        else if ( is_object( $args[0] ) )
            $args[0]->has_children = ! empty( $children_elements[$element->$id_field] );
        $cb_args = array_merge( array(&$output, $element, $depth), $args);
        call_user_func_array(array(&$this, 'start_el'), $cb_args);
        $id = $element->$id_field;
        // descend only when the depth is right and there are childrens for this element
        if ( ($max_depth == 0 || $max_depth > $depth+1 ) && isset( $children_elements[$id]) ) {
            foreach( $children_elements[ $id ] as $child ){
                if ( !isset($newlevel) ) {
                    $newlevel = true;
                    //start the child delimiter
                    $cb_args = array_merge( array(&$output, $depth), $args);
                    call_user_func_array(array(&$this, 'start_lvl'), $cb_args);
                }
                $this->display_element( $child, $children_elements, $max_depth, $depth + 1, $args, $output );
            }
            unset( $children_elements[ $id ] );
        }
        if ( isset($newlevel) && $newlevel ){
            //end the child delimiter
            $cb_args = array_merge( array(&$output, $depth), $args);
            call_user_func_array(array(&$this, 'end_lvl'), $cb_args);
        }
        //end this element
        $cb_args = array_merge( array(&$output, $element, $depth), $args);
        call_user_func_array(array(&$this, 'end_el'), $cb_args);
    }
}


// Enable post thumbnails
add_theme_support('post-thumbnails');
set_post_thumbnail_size(520, 250, true);
//Enable post and comments RSS feed links to head
add_theme_support( 'automatic-feed-links' );
//Enable multisite feature (WordPress 3.0)
define('WP_ALLOW_MULTISITE', true); 

add_theme_support('custom-background');
add_theme_support('custom-header');
add_theme_support('post-thumbnails');
add_theme_support('html5',array('search_form'));
/*add_theme_support( 'custom-logo' );
add_theme_support( 'custom-logo', array(
    'height'      => 100,
    'width'       => 400,
    'flex-height' => true,
    'flex-width'  => true,
    'header-text' => array( 'site-title', 'site-description' ),
) );*/


 function craft_widget_setup() {
	
	register_sidebar(
		array(	
			'name'	=> 'Sidebar',
			'id'	=> 'sidebar-1',
			'class'	=> 'custom',
			'description' => 'Standard Sidebar',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h1 class="widget-title">',
			'after_title'   => '</h1>',
		)
	);
    register_sidebar( array(
        'name'          => __( 'Content Sidebar', 'new crafts' ),
        'id'            => 'sidebar-2',
        'description'   => __( 'Additional sidebar that appears on the right.', 'new crafts' ),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h1 class="widget-title">',
        'after_title'   => '</h1>',
    ) );
register_sidebar( array(
        'name'          => __( 'Footer Widget Area', 'new crafts' ),
        'id'            => 'sidebar-3',
        'description'   => __( 'Appears in the footer section of the site.', 'new crafts' ),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
    }
	add_action('widgets_init','craft_widget_setup');



function add_theme_menu_item()
{
    add_menu_page("Theme Panel", "Theme Panel", "manage_options", "theme-panel", "settings_page", null, 99);
}

add_action("admin_menu", "add_theme_menu_item");
function settings_page()
{
    ?>
        <div class="wrap">
        <h1>Theme Panel</h1>
        <form method="post" action="options.php">
            <?php
                settings_fields("section");
                do_settings_sections("theme-options");      
                submit_button(); 
            ?>          
        </form>
        </div>
    <?php
}


require_once ( get_stylesheet_directory() . '/themeoptions.php' );

function sa_layout_view() {
    global $sa_options;
    $settings = get_option( 'sa_options', $sa_options );
    if( $settings['layout_view'] == 'fluid' ) : ?>

    <?php endif;
}

add_action( 'wp_head', 'sa_layout_view' );




function my_fullwidth_homepage( $class ) {
    // Make the front-page have a full-width layout
    if ( is_front_page() ) {
        $class = 'full-width';
    }
    // Return correct class
    return $class;
}
add_filter( 'wpex_post_layout_class', 'my_fullwidth_homepage', 20 );





/*
	function run_activate_plugin( $plugin ) {
    $current = get_option( 'active_plugins' );
    $plugin = plugin_basename( trim( $plugin ) );

    if ( !in_array( $plugin, $current ) ) {
        $current[] = $plugin;
        sort( $current );
        do_action( 'activate_plugin', trim( $plugin ) );
        update_option( 'active_plugins', $current );
        do_action( 'activate_' . trim( $plugin ) );
        do_action( 'activated_plugin', trim( $plugin) );
    }

    return null;
}
run_activate_plugin( 'plugin/bb-plugin/fl-builder.php' );  //important*/




/*function do_something() {
//This code is only run when the user switches to the theme
    echo "HAPPY NEW YEAR";
}
add_action( 'after_setup_theme', 'do_something' );*/
 


/*function optin_theme_options() {
    // Add theme options page to the addmin menu
    add_theme_page( 'Install Plugins', 'Install Plugins', 'edit_theme_options', 'Install Plugins', 'register_required_plugins' );
}

add_action( 'admin_menu', 'optin_theme_options' );*/
 
//add_action(  'tgmpa_register',  'register_required_plugins' );

require_once dirname( __FILE__ ) . '/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'crafts_newtheme_register_required_plugins' );


function crafts_newtheme_register_required_plugins() {
    /*
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array(

        // This is an example of how to include a plugin bundled with a theme.
        array(
            'name'               => 'Customizer Export/Import', // The plugin name.
            'slug'               => 'customizer-export-import', // The plugin slug (typically the folder name).
            'source'             => get_template_directory_uri() . '/plugins/customizer-export-import.zip', // The plugin source.
            'required'           => true, // If false, the plugin is only 'recommended' instead of required.
            'version'            => '0.4', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
            'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
            'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
            'external_url'       => '', // If set, overrides default API URL and points to an external URL.
            'is_callable'        => '', // If set, this callable will be be checked for availability to determine if a plugin is active.
        ),
       /* array(
                'name'                  => 'Visual Composer',
                'slug'                  => 'js_composer',
                'source'                => get_stylesheet_directory()  .'/plugins/js_composer.zip',
                'required'              => true,
                'version'               => '4.12.1',
            ),*/
        array(
                'name'                  => 'Beaver Builder Plugin (Standard Version)',
                'slug'                  => 'bb-plugin',
                'required'              => true,
                'source'                => get_template_directory_uri()  .'/plugins/bb-plugin.zip',
                'version'               => '1.9.2',
            ),

        );

    
    $config = array(
        'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => '',                      // Default absolute path to bundled plugins.
        'menu'         => 'tgmpa-install-plugins', // Menu slug.
        'parent_slug'  => 'themes.php',            // Parent menu slug.
        'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
        'has_notices'  => true,                    // Show admin notices or not.
        'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => false,                   // Automatically activate plugins after installation or not.
        'message'      => '',                      // Message to output right before the plugins table.

        
    );

    tgmpa( $plugins, $config );
}

$exports = array( 'posts', 'comments', 'users' );

$exportdir = TEMPLATEPATH . '/export';

if ( ! is_dir( $exportdir ) ) {
    $mkdir = wp_mkdir_p( $exportdir );
    if ( false == $mkdir || ! is_dir( $exportdir ) )
        throw new Exception( 'Cannot create export directory. Aborting.' );
}

// empty the export dir else MySQL throws errors
$files = glob( $exportdir . '/*' );
if ( ! empty( $files ) ) {
    foreach( $files as $file )
        unlink( $file );
}

foreach ( $exports as $export ) {

    if ( ! isset( $tables[$export] ) )
        continue;

    if ( ! empty( $tables[$export] ) ) {
        foreach ( $tables[$export] as $table ) {

            $outfile =  sprintf( '%s/%s_dump.sql', $exportdir, $table );
            $sql = "SELECT * FROM {$wpdb->$table} INTO OUTFILE '%s'";
            $res = $wpdb->query( $wpdb->prepare( $sql, $outfile ) );

            if ( is_wp_error( $res ) )
                echo "<p>Cannot export {$table} into {$outfile}</p>";
        }
    }
}
global $wpdb;

$exportdir = TEMPLATEPATH . '/export';

$files = glob( $exportdir . '/*_dump.sql' );

foreach ( $files as $file ) {

    preg_match( '#/([^/]+)_dump.sql$#is', $file, $match );

    if ( ! isset( $match[1] ) )
        continue;

    $sql = "LOAD DATA LOCAL INFILE '%s' INTO TABLE {$wpdb->$match[1]};";

    $res = $wpdb->query( $wpdb->prepare( $sql, $file ) );

    if ( is_wp_error( $res ) )
        echo "<p>Cannot import data from file {$file} into table {$wpdb->$match[1]}</p>";
}

?>
