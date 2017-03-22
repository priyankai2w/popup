<?php
/*
Plugin Name: WP_List_Table Class Example
Plugin URI: http://sitepoint.com
Description: Demo on how WP_List_Table Class works
Version: 1.0
Author: Agbonghama Collins
Author URI:  http://w3guy.com
*/

define( 'SEO_AUTOMATION', plugin_dir_path( __FILE__ ) );
require_once( SEO_AUTOMATION . 'admin/seo-form-tabs.php' );

/* Creating custom table*/
//plugin activation hook for pagerank_average
register_activation_hook( __FILE__, 'strideup_website_details_seoautomation' );
//plugin deactivation hook for pagerank_average (NOT the same as plugin uninstall!)
register_deactivation_hook( __FILE__, 'strideup_website_details_seoautomation_deactivate' );

/*page rank average function */
function strideup_website_details_seoautomation() {
    global $wpdb;
    $tablename = $wpdb->prefix . "seoautomation";
    
    //if the table doesn't exist, create it
    if( $wpdb->get_var("SHOW TABLES LIKE '$tablename'") != $tablename ) {

        $sql = "CREATE TABLE $tablename(
				  `id` int(10) NOT NULL AUTO_INCREMENT,
				  `website_url` varchar(500) NOT NULL,
				  `web_username` varchar(300) NOT NULL,
				  `web_password` varchar(300) NOT NULL,
				  PRIMARY KEY (id)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}

/**
 * plugin deactivation
 */
function strideup_website_details_seoautomation_deactivate() {
    error_log('plugin deactivated');
}