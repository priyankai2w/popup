<?php
/*
Plugin Name: Image Geo Tagger Tool
Plugin URI: http://www.geoimgr.com
Description: Image Geo Tagger Tool Plugin is used to set the GPS latitude and Longitude into an image.
Version: 1.0
Author: Strideup Team
Author URI: http://i2w.co.in
*/
add_action( 'wp_enqueue_scripts', 'q_scripts', 9 );
function q_scripts(){
  wp_register_script( 'geomap',  'https://maps.googleapis.com/maps/api/js?key=AIzaSyBBrzBiXsGSF2Qbb7Xw_Yda2a7C2jm8hvM&ext=.js',array('jquery'),'1.1', true ); 
    wp_enqueue_script( 'geomap' );
    wp_register_script( 'fileupload',  plugins_url( '/js/file_upload.js', __FILE__ ), array('jquery'),'1.1', true ); 
    wp_enqueue_script( 'fileupload' );
    wp_enqueue_style( 'style', plugins_url( '/css/style.css', __FILE__ ),'geoimgr/css/style.css');
    wp_localize_script( 'fileupload', 'geoimg', array(
      'ajaxurl' => admin_url( 'admin-ajax.php' ),
      'nonce' => wp_create_nonce( 'geoimg-nonce' )
    ) );

}
add_action('admin_menu','Geoimg_plugin');
function Geoimg_plugin(){
  add_options_page('GeoImgr',
    'GeoImgr',
    'manage_options',
    'my_setting_admin',

    'create_admin_page'
    );
}
function zipFilesAndDownload($file_names,$archive_file_name,$file_path)
{
  $zip = new ZipArchive();
  //create the file and throw the error if unsuccessful
  if ($zip->open($archive_file_name, ZIPARCHIVE::CREATE )!==TRUE) {
      exit("cannot open <$archive_file_name>\n");
  }
  //add each files of $file_name array to archive
  foreach($file_names as $files)
  {
      $zip->addFile($file_path.$files,$files);
  }
  $zip->close();
  //then send the headers to foce download the zip file
  header("Content-type: application/zip"); 
  header("Content-Disposition: attachment; filename=$archive_file_name"); 
  header("Pragma: no-cache"); 
  header("Expires: 0"); 
}
function wpse_141088_upload_dir( $pathdata ) {
        if ( empty( $pathdata['subdir'] ) ) {
            $pathdata['path']   = $pathdata['path'] . '/mycus/' ;
            $pathdata['url']    = $pathdata['url']. '/mycus/' ;
            $pathdata['subdir'] = '/mycus/' ;
        } else {
            $subdir             = '/mycus/' ;
            $pathdata['path']   = str_replace( $pathdata['subdir'], $subdir, $pathdata['path'] );
            $pathdata['url']    = str_replace( $pathdata['subdir'], $subdir, $pathdata['url'] );
            $pathdata['subdir'] = str_replace( $pathdata['subdir'], $subdir, $pathdata['subdir'] );
        }
        return $pathdata;
}
add_action('wp_ajax_geoimg_action_pass', 'itwowgeoimg_pass_callback');
add_action('wp_ajax_nopriv_geoimg_action_pass', 'itwowgeoimg_pass_callback');
function itwowgeoimg_pass_callback()
{
  if ( ! function_exists( 'wp_handle_upload' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/file.php' );
    require_once( ABSPATH . 'wp-admin/includes/media.php' );
  }
  $urlfirst = get_site_url();
  $array = array();
  $status = array();
  $file_names = array();
  $paths_send = array();
if(isset($_POST['lat']) && isset($_POST['lng'])){
  $total = count($_FILES);
  $latvalue = $_POST['lat'];
  $lngvalue = $_POST['lng'];
  $titleval = $_POST['title'];
  $subtitle = $_POST['subtitle'];
  $comment = $_POST['comment'];
  include_once(plugin_dir_path( __FILE__ ) . 'pel-0.9.1/PelJpeg.php');
  for( $i=0; $i < $total; $i++ ) {
      $uploadedfile = $_FILES['file_'.$i.''];
      $upload_overrides = array( 'test_form' => false );
      $img_types = $uploadedfile['type'];
      $imgtype = substr($img_types, strrpos($img_types, '/') + 1);
    if( $imgtype == 'jpeg' || $imgtype == 'jpg' || $imgtype == 'png' ){
      add_filter( 'upload_dir', 'wpse_141088_upload_dir' );
      $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
      remove_filter( 'upload_dir', 'wpse_141088_upload_dir' );
      if ( $movefile && ! isset( $movefile['error'] ) ) {
        $paths = $movefile['file'];
        $path_url = $movefile['url'];
        $filename = substr($paths, strrpos($paths, '/') + 1);                 
        if( $imgtype == 'png' )
          {
            $input_file = $paths;
            $variable = substr($filename, 0, strpos($filename, "."));
            $output_path = substr($paths, 0, strrpos($paths, '/'));
            $output_file = $output_path . $variable. '.jpg';
            $output_url_str = substr($path_url, 0, strrpos($path_url, '/'));
            $output_url = $output_url_str . $variable. '.jpg';
            $input = imagecreatefrompng($input_file);
            list($width, $height) = getimagesize($input_file);
            $output = imagecreatetruecolor($width, $height);
            $white = imagecolorallocate($output,  255, 255, 255);
            imagefilledrectangle($output, 0, 0, $width, $height, $white);
            imagecopy($output, $input, 0, 0, 0, 0, $width, $height);
            imagejpeg($output, $output_file);
            $file_names[] = substr($output_file, strrpos($output_file, '/') + 1);
            $outputfirst = substr($paths, 0, strrpos($paths, '/'));
            $movefile['file'] = $output_file;
            $movefile['url'] = $output_url;
            $movefile['type'] = 'image/jpg';           
            $paths = $movefile['file'];
          }
          else{
            $file_names[] = substr($paths, strrpos($paths, '/') + 1);
          }
         
        $pelJpeg = new PelJpeg($paths);
        $pelExif = $pelJpeg->getExif();
        if ($pelExif == null) {
            $pelExif = new PelExif();
            $pelJpeg->setExif($pelExif);
        }
        $pelTiff = $pelExif->getTiff();
        if ($pelTiff == null) {
            $pelTiff = new PelTiff();
            $pelExif->setTiff($pelTiff);
        }
        $pelIfd0 = $pelTiff->getIfd();
        if ($pelIfd0 == null) {
            $pelIfd0 = new PelIfd(PelIfd::IFD0);
            $pelTiff->setIfd($pelIfd0);
        }        
        if( $pelExif != null )          
        $pelIfd0->addEntry(new PelEntryAscii(PelTag::IMAGE_DESCRIPTION, $titleval));
        $pelIfd0->addEntry(new PelEntryWindowsString(PelTag::XP_SUBJECT, $subtitle));
        $pelIfd0->addEntry(new PelEntryWindowsString(PelTag::XP_COMMENT,$comment));
        $pelSubIfdGps = new PelIfd(PelIfd::GPS);
        $pelIfd0->addSubIfd($pelSubIfdGps);
        setGeolocation($pelSubIfdGps, $latvalue, $lngvalue);
        $pelJpeg->saveFile(plugin_dir_path( __FILE__ ).'/'.$filename);
        $ss = realpath(plugin_dir_path( __FILE__ ).'/'.$filename);
        if (copy($ss,  $paths)) {
          unlink(plugin_dir_path( __FILE__ ).'/'.$filename);
          if( $imgtype == 'png' )
          {          
          unlink($input_file);
          }
          $array[] = $movefile;
          $status[] = array(status => "Success");           
        //Download Files path
        $paths_send[] = $movefile['file'];
        $paths_file = $movefile['file'];
        $zip_filename_file = substr($paths_file, 0, strrpos( $paths_file, '/') + 1);
        $file_path=$zip_filename_file;
        //Archive name
        $archive_file_name=$zip_filename_file.uniqid().'.zip';
        //cal the function
        }
      }
    }
    else{
      $status[] = array('status' => "Invalid Image Format");
    }
  }
  zipFilesAndDownload($file_names,$archive_file_name,$file_path);
  for( $j = 0; $j < count($paths_send); $j++ ) {
    if (file_exists($paths_send[$j])) 
    {
      if(unlink($paths_send[$j]))
      {
          // echo "Successfully deleted " . $paths_send[$j];
      } 
      else 
      {
          // echo "Problem deleting " . $paths_send[$j];
      }
    }
    else{
      // echo 'not exits';
    }
   }
  $urlhalf = strstr($archive_file_name, 'wp-content');
  $url = $urlfirst.'/'.$urlhalf;
  $urlsend = array( 'url' => $url );
  $gpsval = array( 'latitude' => $latvalue, 'langitude' => $lngvalue );
  $merge = array_merge($urlsend, $status, $gpsval);
  echo json_encode($merge);
  exit;
} 
}
function setGeolocation(
        $pelSubIfdGps, $latitudeDegreeDecimal, $longitudeDegreeDecimal) {
    $latitudeRef = ($latitudeDegreeDecimal >= 0) ? 'N' : 'S';
    $latitudeDegreeMinuteSecond
            = degreeDecimalToDegreeMinuteSecond(abs($latitudeDegreeDecimal));
    $longitudeRef= ($longitudeDegreeDecimal >= 0) ? 'E' : 'W';
    $longitudeDegreeMinuteSecond
            = degreeDecimalToDegreeMinuteSecond(abs($longitudeDegreeDecimal));
    $pelSubIfdGps->addEntry(new PelEntryAscii(
            PelTag::GPS_LATITUDE_REF, $latitudeRef));
    $pelSubIfdGps->addEntry(new PelEntryRational(
            PelTag::GPS_LATITUDE, 
            array($latitudeDegreeMinuteSecond['degree'], 1), 
            array($latitudeDegreeMinuteSecond['minute'], 1), 
            array(round($latitudeDegreeMinuteSecond['second'] * 1000), 1000)));
    $pelSubIfdGps->addEntry(new PelEntryAscii(
            PelTag::GPS_LONGITUDE_REF, $longitudeRef));
    $pelSubIfdGps->addEntry(new PelEntryRational(
            PelTag::GPS_LONGITUDE, 
            array($longitudeDegreeMinuteSecond['degree'], 1), 
            array($longitudeDegreeMinuteSecond['minute'], 1), 
            array(round($longitudeDegreeMinuteSecond['second'] * 1000), 1000)));
}
function degreeDecimalToDegreeMinuteSecond($degreeDecimal) {
    $degree = floor($degreeDecimal);
    $remainder = $degreeDecimal - $degree;
    $minute = floor($remainder * 60);
    $remainder = ($remainder * 60) - $minute;
    $second = $remainder * 60;
    return array('degree' => $degree, 'minute' => $minute, 'second' => $second);
}
function strideup_delete_all_post_revisions(){
  $uploads = wp_upload_dir();
  $conc = $uploads['path'];
  $urlget = substr($conc, 0, strpos($conc, "uploads/"));
  $urls = $urlget.'uploads/mycus/';
  $paths = $urls;
  array_map('unlink', glob($paths."*"));
}
// delete_post_revisions will be call when the Cron is executed
add_action( 'geomap_fetch_call_daily', 'strideup_delete_all_post_revisions' );
function cron_minute($schedules) 
{
  // Adds once every minute to the existing schedules.
  $schedules['daily'] = array(
        'interval'  => 86400,
        'display'   => __( 'daily', 'textdomain' )
  );
  return $schedules;
}
add_filter( 'cron_schedules', 'cron_minute' );     
function my_index_activation() {
  if ( !wp_next_scheduled( 'geomap_fetch_call_daily' ) ) {
    wp_schedule_event( current_time( 'timestamp' ), 'daily', 'geomap_fetch_call_daily');
  }
}
add_action('init', 'my_index_activation', 10);
function geoimag_func()
{
?>
 <html>
        <body>
        <script>var ajax_url = "<?php echo admin_url('admin-ajax.php'); ?>"</script> 

        <form id="form1" method="post" enctype="multipart/form-data" action="">
        <?php wp_nonce_field('ajax_file_nonce', 'security'); ?>
  <p>&nbsp;</p>
  <p>
    <label for="upload_img"></label>
  </p>
  <table width="872" height="292" border="1">
    <tr>
      <td colspan="2"><label for="image_up"></label>
        <div align="left"><label id="move">Upload Images</label>
          <input type="file" name="image_up[]" id="image_up" class = "files-data form-control" multiple/>
        </div></td>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4">
        <div id="progress-div">
          <div id="progress-bar">
          </div>
        </div>
      </td>
    </tr>
    <tr>
      <td width="231" rowspan="10">
        <div id="display_img"></div>
        <div id="numdisp">
          <span id="plussign">+</span>
          <span id="spannum">0</span>
          <span>image</span>
        </div>
      <td width="107">&nbsp;</td>
      <td width="240"><label></label>
        &nbsp; <div align="center">Latitude</div></td>
      <td width="266"><label></label>
        &nbsp;
        <div align="center">Longitude</div></td>
    </tr>
    <tr>
      <td><label>
        <div align="center">Marker</div>
      </label></td>
      <td><label for="mark_lati"></label>
        <div align="center">
          <input type="text" name="Lat" id="lat" size="40"/>
      </div></td>
      <td><label for="mark_long"></label>
        <div align="center">
          <input type="text" name="Lng" id="lng" size="40"/>
      </div></td>
    </tr>
    <tr>
      <td><div align="center">Photo</div></td>
      <td><label for="mark_photo"></label>
        <div align="center">
          <input type="text" name="mark_photo" id="mark_photo" size="40"/>
      </div></td>
      <td><label for="photo_long"></label>
        <div align="center">
          <input type="text" name="photo_long" id="photo_long" size="40" />
      </div></td>
    </tr>
    <tr>
    <td><div align="center">Enter Address</div></td>
      <td colspan="2"><input id="place" type="text" placeholder="Address" value="America" size="98" /></td>
    </tr>
    <tr>
    <td><div align="center">Title</div></td>
      <td colspan="2"><input id="title" type="text" placeholder="Title" value=" " size="98" /></td>
    </tr>
        <tr>
    <td><div align="center">Subtitle</div></td>
      <td colspan="2"><input id="subtitle" type="text" placeholder="Subtitle" value=" " size="98" /></td>
    </tr>
     <tr>
    <td><div align="center">5 Star Rating</div></td>
      <td colspan="2"><input type="checkbox" name="rating_1" id="rating_1">
       <label for="chk_rating">
         <input type="checkbox" name="rating_2" id="rating_2">
         <input type="checkbox" name="rarting_3" id="rarting_3">
         <input type="checkbox" name="rating_4" id="rating_4">
         <input type="checkbox" name="rating_5" id="rating_5">
       </label></td>
    </tr>
     <tr>
    <td><div align="center">Comments</div></td>
      <td colspan="2"><label for="txt_area"></label>
       <textarea name="txt_area" id="txt_area" cols="45" rows="5"></textarea></td>
    </tr>
    
    <tr>
      <td colspan="3"><div align="center">
        <input type="submit" name="tag_btn" id="tag_btn" value="Tag Photos" />
      </div></td>
    </tr>
    <tr>
      <td colspan="3"><div align="center">
        <a href="#" id="download_btn">Download photos</a></div></td>
    </tr>
  </table> 
</form>
<div id="map-canvas">
</div>
</body>
</html>
<?php
}
add_shortcode( 'Geoimg', 'geoimag_func' );
?>