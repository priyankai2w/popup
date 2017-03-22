<?php

// Default options values
$sa_options = array(
	'footer_copyright' => '&copy; ' . date('Y') . ' ' . get_bloginfo('name'),
	'intro_text' => '',
	'featured_cat' => '',
	'layout_view' => 'fixed',
	'author_credits' => true
);

if ( is_admin() ) : // Load only if we are viewing an admin page

function sa_register_settings() {
	// Register settings and call sanitation functions
	register_setting( 'sa_theme_options', 'sa_options', 'sa_validate_options' );
}

add_action( 'admin_init', 'sa_register_settings' );



// Store layouts views in array
$sa_layouts = array(
	'fixed' => array(
		'value' => 'fixed',
		'label' => 'Fixed Layout'
	),
	'fluid' => array(
		'value' => 'fluid',
		'label' => 'Fluid Layout'
	),
);

function sa_theme_options() {
	// Add theme options page to the addmin menu
	add_theme_page( 'Theme Options', 'Theme Options', 'edit_theme_options', 'theme_options', 'sa_theme_options_page' );
}

add_action( 'admin_menu', 'sa_theme_options' );

// Function to generate options page
function sa_theme_options_page() {
	global $sa_options, $sa_categories, $sa_layouts;

	if ( ! isset( $_REQUEST['updated'] ) )
		$_REQUEST['updated'] = false; // This checks whether the form has just been submitted. 

?>


	<div class="wrap">

	<?php screen_icon(); echo "<h2>" . get_current_theme() . __( ' page' ) . "</h2>";
	// This shows the page's name and an icon if one has been provided ?>

	<?php if ( false !== $_REQUEST['updated'] ) : ?>
	<div class="updated fade"><p><strong><?php _e( 'Options saved' ); ?></strong></p></div>
	<?php endif; // If the form has just been submitted, this shows the notification ?>

	<form method="post" action="options.php">

	<?php $settings = get_option( 'sa_options', $sa_options ); ?>
	
	<?php settings_fields( 'sa_theme_options' );
	/* This function outputs some hidden fields required by the form,
	including a nonce, a unique number used to ensure the form has been submitted from the admin page
	and not somewhere else, very important for security */ ?>

	<h1>hello its working</h1>

	</select>
	</td>
	</tr>

	<tr valign="top"><th scope="row">Layout View</th>
	<td>
	<?php foreach( $sa_layouts as $layout ) : ?>
	<input type="radio" id="<?php echo $layout['value']; ?>" name="sa_options[layout_view]" value="<?php esc_attr_e( $layout['value'] ); ?>" <?php checked( $settings['layout_view'], $layout['value'] ); ?> />
	<label for="<?php echo $layout['value']; ?>"><?php echo $layout['label']; ?></label><br />
	<?php endforeach; ?>
	</td>
	</tr>

	

	</table>

	<p class="submit"><input type="submit" class="button-primary" value="Save Options" /></p>

	</form>

	</div>

	<?php
}

function sa_validate_options( $input ) {
	global $sa_options, $sa_categories, $sa_layouts;

	$settings = get_option( 'sa_options', $sa_options );
	
	// We strip all tags from the text field, to avoid vulnerablilties like XSS
	$input['footer_copyright'] = wp_filter_nohtml_kses( $input['footer_copyright'] );
	
	// We strip all tags from the text field, to avoid vulnerablilties like XSS
	$input['intro_text'] = wp_filter_post_kses( $input['intro_text'] );
	
	// We select the previous value of the field, to restore it in case an invalid entry has been given
	$prev = $settings['featured_cat'];
	// We verify if the given value exists in the categories array
	if ( !array_key_exists( $input['featured_cat'], $sa_categories ) )
		$input['featured_cat'] = $prev;
	
	// We select the previous value of the field, to restore it in case an invalid entry has been given
	$prev = $settings['layout_view'];
	// We verify if the given value exists in the layouts array
	if ( !array_key_exists( $input['layout_view'], $sa_layouts ) )
		$input['layout_view'] = $prev;
	
	// If the checkbox has not been checked, we void it
	if ( ! isset( $input['author_credits'] ) )
		$input['author_credits'] = null;
	// We verify if the input is a boolean value
	$input['author_credits'] = ( $input['author_credits'] == 1 ? 1 : 0 );
	
	return $input;
}

endif;  // EndIf is_admin()