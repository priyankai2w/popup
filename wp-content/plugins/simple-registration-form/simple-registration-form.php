<?php
/**
 * Plugin Name: Simple Registration Form
 * Plugin URI: https://aftabhusain.wordpress.com/
 * Description: This plugin allows users to put simple registration form on page , post or template using shortcode 
 * Version: 1.0.1
 * Author: Aftab Husain
 * Author URI: https://aftabhusain.wordpress.com/
 * Author Email: amu02.aftab@gmail.com
 * License: GPLv2
 */

//define('WP_DEBUG',true);
define('SRF_REGISTRATION_INCLUDE_URL', plugin_dir_url(__FILE__).'includes/');
ob_start();


//add front end css and js
function srf_slider_trigger(){
	wp_enqueue_style('srf_caro_css_and_js', SRF_REGISTRATION_INCLUDE_URL."front-style.css"); 
    wp_register_script('srf_caro_css_and_js', SRF_REGISTRATION_INCLUDE_URL."font-script.js" );
	wp_enqueue_script('srf_caro_css_and_js');
}
add_action('wp_footer','srf_slider_trigger');

// function to registration Shortcode
function srf_registration_shortcode( $atts ) {
    global $wpdb, $user_ID; 
	$firstname='';
	$lastname='';
	$username='';
	$email='';
	
	//if looged in rediret to home page
	if ( is_user_logged_in() ) { 
	    wp_redirect( get_option('home') );// redirect to home page
		exit;
	}

	if(sanitize_text_field( $_POST['com_submit']) != ''){

		$firstname=sanitize_text_field( $_REQUEST['com_firstname'] );
		$lastname=sanitize_text_field( $_REQUEST['com_lastname']);
		$username = sanitize_text_field(  $_REQUEST['com_username'] );
		$email = sanitize_text_field(  $_REQUEST['com_email']  );
		$password = $wpdb->escape( sanitize_text_field( $_REQUEST['com_password']));
		$status = wp_create_user($username,$password,$email);
	    $succress ='';
		$error_msg='';
	   
		if (is_wp_error($status))  {
		     $error_msg = __('Username or Email already registered. Please try another one.',''); 
		} 
		else{
			$user_id=$status;
			update_user_meta( $user_id,'first_name', $firstname);
			update_user_meta( $user_id,'last_name', $lastname);
			
			$succress= __('Your are register successfully for this site.',''); 
			
		}  
	}
?>
	<div class="alar-registration-form">
		<div class="alar-registration-heading">
		<?php _e("Registration Form",'');?>
		</div>
		<?php if($error_msg!='') { ?><div class="error"><?php echo $error_msg; ?></div><?php }  ?>
		<?php if($succress!='') { ?><div class="success"><?php echo $succress; ?></div><?php }  ?>
		
		<form  name="form" id="registration"  method="post">
			<div class="ftxt">
			 <label><?php _e("First Name :",'');?></label> 
			 <input id="com_firstname" name="com_firstname" type="text" class="input" required value=<?php echo $firstname; ?> > 
			</div>
			<div class="ftxt">
			 <label><?php _e("Last name :",'');?></label>  
			 <input id="com_lastname" name="com_lastname" type="text" class="input" required value=<?php echo $lastname; ?> >
			</div>
			<div class="ftxt">
			 <label><?php _e("Username :",'');?></label> 
			 <input id="com_username" name="com_username" type="text" class="input" required value=<?php echo $username; ?> >
			</div>
			<div class="ftxt">
			<label><?php _e("E-mail :",'');?> </label>
			 <input id="com_email" name="com_email" type="email" class="input" required value=<?php echo $email; ?> >
			</div>
			<div class="ftxt">
			<label><?php _e("Password :",'');?></label>
			 <input id="password1" name="com_password" type="password" required class="input" />
			</div>
			<div class="ftxt">
			<label><?php _e("Confirm Password : ",'');?></label>
			 <input id="password2" name="c_password" type="password" class="input" />
			</div>
			<div class="fbtn"><input type="submit" name='com_submit' class="button"  value="Register"/> </div>
		</form>
	</div>
<?php	
}

//add registration shortcoode
add_shortcode( 'simple-registration-form', 'srf_registration_shortcode' );
	
?>
