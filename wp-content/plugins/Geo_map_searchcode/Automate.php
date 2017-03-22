<?php
   /*
   Plugin Name: Geo Trade
   Plugin URI: http://app.scopeleads.com
   Description: Geo Trade is used to search the information to the google map and store the information into our database.
   Version: 1.0
   Author: Strideup Team
   Author URI: http://i2w.co.in
   */
/***creting a class***/
class Geo_Trade_plugin
{
    /**
     * Constructor will create the menu item
     */
    public function __construct()
    {
      
      add_action( 'admin_menu', array($this, 'GeoTrade_plugin' ));

      add_action( 'wp_enqueue_scripts',array( $this,'qsr_scripts'));

      add_shortcode('Geotrade', array( $this,'geotrade_func' ));

      add_action('wp_ajax_automate_action_pass', array( $this,'Automate_pass_callback'));

      add_action('wp_ajax_nopriv_automate_action_pass', array( $this,'Automate_pass_callback')); 
      
      register_activation_hook( __FILE__, array( $this,'create_dbtable' ));
    }
    public function GeoTrade_plugin()
    {
    add_options_page('Geo_Trade',
    'Geo_Trade',
    'manage_options',
    'my_setting_admin',
    'create_admin_page'
    );

    }
    public function qsr_scripts(){
    wp_register_script( 'file_mapcall',  plugins_url( '/js/file_mapcall.js', __FILE__ ), array('jquery'),'1.1', true ); 
    wp_enqueue_script( 'file_mapcall' );
    wp_register_script( 'geomap',  'https://maps.googleapis.com/maps/api/js?key=AIzaSyCYljgxnzRgHu8lVkXirCtAHYIVVtC610o&libraries=places&callback=initMap',array('jquery'), '1.1', true ); 
    wp_enqueue_script( 'geomap' );
    wp_localize_script( 'file_mapcall', 'auto_obj', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) ); 
     
     }

    public function Automate_pass_callback()
    { 

      $plcname = $_POST['placename'];
      // print_r($plcname);

      $countt = count($plcname);
      $str2 = substr($plcname, 1);
      print_r($str2);

      $ttt = explode(",",  $str2);
      $countt = count( $ttt);
      print_r($ttt);
      // print_r(array_values($plcname));
      // $result = substr_count( $_POST['placename'], ","); 
      // print_r( $result);
        for( $i=0; $i < count( $ttt); $i++ ) {
      // print_r($plcname[i])
      // $plcvar = $plcname[$i]; 
      // echo $plcvar;
        //' . $ttt[$i] . '
      $url = 'https://maps.googleapis.com/maps/api/place/details/json?placeid=' . $ttt[$i] . '&key=AIzaSyAXGL5xKgwsFm_uEZvn2sA70kGa7X1RUx0';
      // print_r($url);
      $response = wp_remote_get( esc_url_raw( $url ) );
      // print_r($response);
      /* Will result in $api_response being an array of data,
      parsed from the JSON response of the API listed above */
      $api_response = json_decode( wp_remote_retrieve_body( $response ), true );
      // $jsoncount = count($jsoncount);
      // echo $jsoncount;
      // print_r($api_response);
        if ($api_response['status'] == 'OK') {
       $formatted_addr = $api_response['result']['formatted_address'];
       $formatted_phone = $api_response['result']['formatted_phone_number'];
       $name = $api_response['result']['name'];
       $rating = $api_response['result']['rating'];
       $website = $api_response['result']['website'];
       // echo $name;
       // echo $formatted_addr;
       // echo $formatted_phone;
       // echo $website;
       // echo $rating;
       // $latitude = $api_response['result']['geometry']['location']['lat'];
       // $longitude = $api_response['result']['geometry']['location']['lng'];
       // echo $latitude;
       // echo $longitude;
      }

    //return $api_response;  
    //   $plcname = $_POST['placename'];
    //   $add = $_POST['address1'];
    //   $ratg = $_POST['rating1'];
    //   $phone = $_POST['phone1'];
    //   $site = $_POST['website1'];
    //   // print_r($add);
    //   // print_r($rat);
     
     $iwval = array( 'placename' =>  $name, 'address' => $formatted_addr,'phonenumber' => $formatted_phone, 'rating' => $rating,  'website' => $website);
     echo json_encode($iwval);
    //exit;
       }
  }

     /*** creating mysql database****/
     public function create_dbtable(){
    global $wpdb;
    $tablename = $wpdb->prefix . "wp_map_contacts";
    
    //if the table doesn't exist, create it
    if( $wpdb->get_var("SHOW TABLES LIKE '$tablename'") != $tablename ) {
   $sql = "CREATE TABLE $tablename(
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `Name` varchar(500) NOT NULL,
  `Address` varchar(500) NOT NULL,
  `Email` varchar(200) NOT NULL,
  `Phone` varchar(20) NOT NULL,
  `Rating` varchar(100) NOT NULL,
  `Website` varchar(500) NOT NULL,  
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
         }
}



    public function geotrade_func()
    {
      ?>
    <div id="anytrade">
    Enter Trade:
    </div>

    <div id="locationField">
    <input id="tradename" placeholder="Enter a Trade" type="text" />
    </div>

    <div id="findhotels">
    Find hotels in:
    </div>

    <div id="locationField">
      <input id="autocomplete" placeholder="Enter a city" type="text" />
    </div>

    <div id="submitfield">
      <button type="submit" id="submit" class="submit">Submit </button>
      <!-- <input type="submit" id="submit" value="Submit"> -->
     </div>

      <!-- <div id="demo"><span id="add"></span> -->
      <input type="text" id="add" name="add[]" value="">  

    <div id="controls">
      <select id="country">
        <option value="all">All</option>
        <option value="au">Australia</option>
        <option value="br">Brazil</option>
        <option value="ca">Canada</option>
        <option value="fr">France</option>
        <option value="de">Germany</option>
        <option value="mx">Mexico</option>
        <option value="nz">New Zealand</option>
        <option value="it">Italy</option>
        <option value="za">South Africa</option>
        <option value="es">Spain</option>
        <option value="pt">Portugal</option>
        <option value="us" selected>U.S.A.</option>
        <option value="uk">United Kingdom</option>
      </select>
    </div>

    <div id="map" style="height: 100%; position: absolute; width: 100%;"></div>

    <div id="listing">
      <table id="resultsTable">
        <tbody id="results"></tbody>
      </table>
    </div>

    <div style="display: none">
      <div id="info-content">
        <table>
          <tr id="iw-url-row" class="iw_table_row">
            <td id="iw-icon" class="iw_table_icon"></td>
            <td id="iw-url"></td>
          </tr>
          <tr id="iw-address-row" class="iw_table_row">
            <td class="iw_attribute_name">Address:</td>
            <td id="iw-address"></td>
          </tr>
          <tr id="iw-phone-row" class="iw_table_row">
            <td class="iw_attribute_name">Telephone:</td>
            <td id="iw-phone"></td>
          </tr>
          <tr id="iw-rating-row" class="iw_table_row">
            <td class="iw_attribute_name">Rating:</td>
            <td id="iw-rating"></td>
          </tr>
          <tr id="iw-website-row" class="iw_table_row">
            <td class="iw_attribute_name">Website:</td>
            <td id="iw-website"></td>
          </tr>
        </table>
      </div>
    </div>
      <?php
      if ( isset( $_POST['submit'] ) ){
      global $wpdb;
      $tablename=$wpdb->prefix.'wp_map_contacts';
      $data=array(
      // 'id' => , 
      'Name' => $name,
      // 'Email' => , 
      'Address' => $formatted_addr,
      'Phone' => $formatted_phone,
      'rating' => $rating,
      'Website' => $website
      );
       $success = $wpdb->insert( $tablename, $data);
       if($success){
        echo "inserted successfully";
       }
       else
       {
        echo "error occurs";
       }
      }
    ?>       
    <?php
    }
    }
    $geotradevar = new Geo_Trade_plugin();
    ?>