<?php
$wordpress_path = "/path/to/my/wordpress/install"localhost/templatecreation/wp-admin/plugins.php ;    
require_once( $wordpress_path . "/wp-load.php" ); //not sure if this line is needed
//activate_plugin() is here:
require_once(  $wordpress_path . "/wp-admin/includes/plugin.php");
$plugins = array("cforms",  "w3-total-cache",  "wordpress-seo");
foreach ($plugins as $plugin){
$plugin_path = $wordpress_path."wp-content/plugins/{$plugin}.php";
  activate_plugin($plugin_path);
}
?>