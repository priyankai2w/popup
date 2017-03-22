<?php
/**
 * Plugin Name: Multistep Form
 * Plugin URI:  http://i2w.uk
 * Description: Show an article in a modal on the same page. 
 * Version:  1.0
 * Author: Stride Up Team
 * Author URI: http://www.i2w.uk
*/
/**
* To create a multistep forms for getting customer data
*/
class Multistep_Form
{  
  function __construct()
  {
    add_action( 'wp_enqueue_scripts', array( $this, 'strideup_enqueue_scripts' ) );
    add_action( 'activated_plugin', array( $this, 'strideup_save_error' ) );
    add_action( 'wp_ajax_strideup_multistep', array( $this, 'strideup_multistep_ajax_save_form' ) );
    add_action( 'wp_ajax_nopriv_strideup_multistep', array( $this, 'strideup_multistep_ajax_save_form' ) );
  }
  /*
   * Load jQuery datepicker.
   *
   */
  public function strideup_enqueue_scripts()
  {
    // bootstrap library files
    wp_enqueue_style( 'bootstrap-css', "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" );
    wp_enqueue_style( 'bootstrapValidator-css', "https://cdnjs.cloudflare.com/ajax/libs/bootstrap-validator/0.5.3/css/bootstrapValidator.css" );
    wp_enqueue_script( 'moment-js', "https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.0/moment.js" );
    wp_enqueue_script( 'bootstrap-js', "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js", array('jquery'), '1.0.0', true );
    wp_enqueue_script( 'bootstrapValidator-js', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-validator/0.5.3/js/bootstrapValidator.min.js', array('jquery'), '0.5.0', true  );
    wp_register_style( 'bootstrap-datetimepicker-min' , plugins_url( '/css/bootstrap-datetimepicker.min.css', __FILE__ ) );
    wp_enqueue_style('bootstrap-datetimepicker-min');
    wp_register_script( 'bootstrap-datetimepicker',  plugins_url( '/js/bootstrap-datetimepicker.js', __FILE__ ), array('jquery'),'1.0', true );
    wp_enqueue_script( 'bootstrap-datetimepicker' );        
    wp_register_script( 'multistepform',  plugins_url( '/js/multistepform.js', __FILE__ ), array('jquery'),'1.1', true ); 
    wp_enqueue_script( 'multistepform' ); 
    wp_localize_script( 'multistepform', 'ajax_object', array(
      'ajaxurl' => admin_url( 'admin-ajax.php' ),
      'nonce' => wp_create_nonce( 'ajax_object-nonce' )
    ) );
  }
  public function strideup_multistep_ajax_save_form()
  {
    header( "Content-Type: application/json" );
    $fname = $_POST['fname'];
    echo json_encode($fname);
    die();
  }
  public function strideup_multistep_activation_hook() {
      global $wpdb;
      $tablename_one = $wpdb->prefix . "package_details";
      $tablename_two = $wpdb->prefix . "multistepform";
      //if the table doesn't exist, create it
      if( $wpdb->get_var("SHOW TABLES LIKE '$tablename_one'") != $tablename_one ) {
          $sql_one = "CREATE TABLE $tablename_one(
                  `id` int(10) NOT NULL AUTO_INCREMENT,
                  `package_name` varchar(200) NOT NULL,
                  `currency_symbol` varchar(100) NOT NULL,
                  `package_price` int(10) NOT NULL,
                  `date_time` DATETIME,
                  PRIMARY KEY (id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

          require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
          dbDelta($sql_one);
      }
      if( $wpdb->get_var("SHOW TABLES LIKE '$tablename_two'") != $tablename_two ) {
          $sql_two = "CREATE TABLE $tablename_two(
                  `id` int(10) NOT NULL AUTO_INCREMENT,
                  `package_id` int(10) NOT NULL,
                  `first_name` varchar(100) NOT NULL,
                  `last_name` varchar(100) NOT NULL,
                  `email` varchar(150) NOT NULL,
                  `phone_number` varchar(25) NOT NULL,
                  `website_url` varchar(200) NOT NULL,
                  `business_address` varchar(500) NOT NULL,
                  `schedule_a_call`  DATETIME,
                  `update_time`  DATETIME,
                  PRIMARY KEY (id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

          require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
          dbDelta($sql_two);
      }
  }
  public function strideup_multistep_deactivation_hook() {
      error_log('plugin deactivated');
  }
  public function strideup_save_error() {
    update_option( 'plugin_error',  ob_get_contents() );
  }
  public function strideup_multstep_shortcode() {
    ?>
    <!-- Modal -->
    <div id="myModal" class="modal fade" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button class="close" type="button" data-dismiss="modal">×</button>
            <h4 id="myModalLabel" class="modal-title">Customer Details</h4>
          </div>
          <form method="post">
            <div class="modal-body">
              <!-- shortcode -->
              <div class="form-group row">
                <label for="firstname" class="col-sm-4 col-form-label">First Name</label>
                <div class="col-sm-8">
                  <input type="text" name="firstname" class="form-control col-sm-12" id="firstname" placeholder="Enter your First Name" required>
                </div>
              </div>
              <div class="form-group row">
                <label for="lastname" class="col-sm-4 col-form-label">Last Name</label>
                <div class="col-sm-8">
                  <input type="text" name="lastname" class="form-control col-sm-12" id="lastname" placeholder="Enter your Last Name" required>
                </div>
              </div>
              <div class="form-group row">
                <label for="inputemail" class="col-sm-4 col-form-label">Email</label>
                <div class="col-sm-8">
                  <input type="email" name="inputemail" class="form-control col-sm-12" id="inputemail" placeholder="Email" required>
                </div>
              </div>
              <div class="form-group row">
                <label for="phonenumber" class="col-sm-4 col-form-label">Phone Number</label>
                <div class="col-sm-8">
                  <input type="text" name="phonenumber" class="form-control col-sm-12" id="phonenumber" placeholder="Enter Phone Number" required="required">
                  <input type="hidden" name="packagename" id="packagename">
                  <input type="hidden" name="packageamt" id="packageamt">
                  <input type="hidden" name="pricesymbol" id="pricesymbol">
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
              <button type="submit" name="first_form" class="btn btn-primary">Save &amp; Continue</button>
               <!-- data-toggle="modal" data-target="#myModal2" data-dismiss="modal" -->
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- Modal2 -->
    <div id="myModal2" class="modal fade" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button class="close" type="button" data-dismiss="modal">×</button>
            <h4 id="myModalLabel2" class="modal-title">Customer Details</h4>
          </div>
          <form method="post">
            <div class="modal-body">
              <div class="form-group row">
                <label for="websiteurl" class="col-sm-4 col-form-label">Website URL</label>
                <div class="col-sm-8">
                  <input type="text" name="websiteurl" class="form-control col-sm-12" id="websiteurl" placeholder="Enter your Website URL" required>
                </div>
              </div>
              <div class="form-group row">
                <label for="businessaddress" class="col-sm-4 col-form-label">Business Address</label>
                <div class="col-sm-8">
                  <textarea name="businessaddress" class="form-control col-sm-12" id="businessaddress" rows="3" placeholder="Enter your Business Address" required></textarea>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-default" type="button" data-dismiss="modal" data-toggle="modal" data-target="#myModal">Previous</button>
              <button type="submit" name="second_form" class="btn btn-primary" data-toggle="modal" data-target="#myModal3" data-dismiss="modal">Save &amp; Close</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- Modal3 -->
    <div id="myModal3" class="modal fade" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button class="close" type="button" data-dismiss="modal">×</button>
            <h4 id="myModalLabel3" class="modal-title">Customer Details</h4>
          </div>
          <form method="post">
            <div class="modal-body">
              <!-- shortcode -->
              <div class="form-group row">
                <label for="scheduleacall" class="col-sm-4 col-form-label">Schedule a call</label>
                <div class="col-sm-8">
                  <input type="text" name="scheduleacall" class="form-control col-sm-12" id="scheduleacall" placeholder="Enter your Schedule a call" required>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-default" type="button" data-dismiss="modal" data-toggle="modal" data-target="#myModal2">Previous</button>
              <button type="submit" name="third_form" class="btn btn-primary" data-toggle="modal">Save &amp; Close</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  <?php
  }
}
if( class_exists( 'Multistep_Form' ) ) {
  $multistepform = new Multistep_Form();    
  add_shortcode( 'multistepform', array( $multistepform, 'strideup_multstep_shortcode' ) );
  //plugin activation hook for multistep form
  register_activation_hook(__FILE__, array($multistepform, 'strideup_multistep_activation_hook'));
  //plugin deactivation hook for multistep form plugin 
  register_activation_hook(__FILE__, array($multistepform, 'strideup_multistep_deactivation_hook'));
}
?>