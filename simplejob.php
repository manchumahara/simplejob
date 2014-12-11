<?php
/*
Plugin Name: Codeboxr Simple Job Manager
Plugin URI: http://codeboxr
Description: Adds a simple job manager to your WordPress site.
Version: 1.0
Author: Codeboxr
Author URI: http://codeboxr.com
*/


/*
    Copyright 2011-2014  Codeboxr.com  (email : sabuj@codeboxr.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
    //error_reporting(E_ALL ^ E_NOTICE);
    //ini_set('display_errors', 1);
    ?>
    <?php
// avoid direct calls to this file where wp core files not present
/*
 * Use WordPress 2.6 Constants
 */
if (!defined('WP_CONTENT_DIR')) {
	define( 'WP_CONTENT_DIR', ABSPATH.'wp-content');
}
if (!defined('WP_CONTENT_URL')) {
	define('WP_CONTENT_URL', get_option('siteurl').'/wp-content');
}
if (!defined('WP_PLUGIN_DIR')) {
	define('WP_PLUGIN_DIR', WP_CONTENT_DIR.'/plugins');
}
if (!defined('WP_PLUGIN_URL')) {
	define('WP_PLUGIN_URL', WP_CONTENT_URL.'/plugins');
}

$base_name = plugin_basename('simplejob/jobs.php');
$base_page = 'admin.php?page='.$base_name;

$base_name_add = plugin_basename('simplejob/add.php');
$base_page_add = 'admin.php?page='.$base_name_add;

$base_name_app = plugin_basename('simplejob/applications.php');
$base_page_app = 'admin.php?page='.$base_name_app;

### Simplejob Table Name
global $wpdb;
$wpdb->simplejob = $wpdb->prefix.'simplejob';
$wpdb->simplejobapp = $wpdb->prefix.'simplejobapp';



// Define the complete directory path
define( 'SIMPLEJOB_DIR', dirname( __FILE__ ) );
// Jobman global functions
require_once( SIMPLEJOB_DIR . '/includes/helperfunctions.php' );
### Function: Simple Administration Menu
add_action('admin_menu', 'simplejob_menu');

function simplejob_menu() {    
    if (function_exists('add_menu_page')) {
            //add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
        add_menu_page(__('Job Manager', 'simplejob'), __('Simple Job', 'simplejob'), 'manage_jobs', 'simplejob/jobs.php', '');
    }
    if (function_exists('add_submenu_page')) {
            //add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function )
        add_submenu_page('simplejob/jobs.php', __('Job Manager', 'simplejob'), __('Jobs', 'simplejob'), 'manage_jobs', 'simplejob/jobs.php');
        add_submenu_page('simplejob/jobs.php', __('Setting', 'simplejob'), __('Setting', 'simplejob'), 'manage_jobs', 'simplejob/setting.php');
        add_submenu_page('simplejob/jobs.php', __('Add New Job', 'simplejob'), __('Add Job', 'simplejob'), 'manage_jobs', 'simplejob/add.php');
        add_submenu_page('simplejob/jobs.php', __('Application Manager', 'simplejob'), __('Applications', 'simplejob'), 'manage_jobs', 'simplejob/applications.php');
            //add_submenu_page('wp-downloadmanager/download-manager.php', __('Add File', 'wp-downloadmanager'), __('Add File', 'wp-downloadmanager'), 'manage_downloads', 'wp-downloadmanager/download-add.php');
            //add_submenu_page('wp-downloadmanager/download-manager.php', __('Download Options', 'wp-downloadmanager'), __('Download Options', 'wp-downloadmanager'), 'manage_downloads', 'wp-downloadmanager/download-options.php');
            //add_submenu_page('wp-downloadmanager/download-manager.php', __('Download Templates', 'wp-downloadmanager'), __('Download Templates', 'wp-downloadmanager'), 'manage_downloads', 'wp-downloadmanager/download-templates.php');
            //add_submenu_page('wp-downloadmanager/download-manager.php', __('Uninstall WP-DownloadManager', 'wp-downloadmanager'), __('Uninstall WP-DownloadManager', 'wp-downloadmanager'), 'manage_downloads', 'wp-downloadmanager/download-uninstall.php');
            //var_dump('admin_print_scripts-'.$page_hook_add);
            //add_action('admin_print_scripts-'.$page_hook, 'simplejob_admin_printjs');
            //add_action('admin_print_styles-' .$page_hook, 'simplejob_admin_printcss');
            //var_dump($page_hook);
    }      

}
add_action('init','simplejob_admin_printjs');
function simplejob_admin_printjs(){
    //if(is_admin()){
    wp_enqueue_script('jquery');
    wp_enqueue_script('jdpickerjs', WP_PLUGIN_URL . '/simplejob/js/datepicker/jquery.jdpicker.js', array( 'jquery' ), '1.0');
    wp_enqueue_script('jqueryvalidate', WP_PLUGIN_URL . '/simplejob/js/datepicker/jquery.validate.min.js', array( 'jquery' ), '1.0');
    wp_enqueue_style('jdpickercss', WP_PLUGIN_URL.'/simplejob/js/datepicker/jdpicker.css', '', '1.0');
    //}
}

function simplejob_admin_printcss(){
    wp_enqueue_style('jdpickercss', WP_PLUGIN_URL.'/simplejob/js/datepicker/jdpicker.css', '', '1.0');
}
### Function: Setup Simple job
add_action('activate_simplejob/simplejob.php', 'setup_simplejob');
function setup_simplejob() {
	global $wpdb, $blog_id;
	//simplejob_textdomain();
        //var_dump("yes");
	$charset_collate = '';
	if($wpdb->supports_collation()) {
		if(!empty($wpdb->charset)) {
			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		}
		if(!empty($wpdb->collate)) {
			$charset_collate .= " COLLATE $wpdb->collate";
		}
	}

	// Create simple jobs Table
    if($wpdb->get_var("SHOW TABLES LIKE '$wpdb->simplejob'") != $wpdb->simplejob){
        $create_table = "CREATE TABLE $wpdb->simplejob (".
         "jobid int(10) NOT NULL auto_increment,".							
         "title varchar(255) NOT NULL default '',".
         "salary int(10) NOT NULL default '0',".
         "location varchar(50) NOT NULL default '',".
         "startdate varchar(20) NOT NULL default '',".
         "enddate varchar(20) NOT NULL default '',".
         "des text NOT NULL default '',".
         "dstartdate varchar(20) NOT NULL default '',".
         "denddate varchar(20) NOT NULL default '',".
         "appmail varchar(255) NOT NULL default '',".
         "highlight tinyint(1) NOT NULL default '0',".
         "published tinyint(1) NOT NULL default '0',".                                                        
         "PRIMARY KEY (jobid)) $charset_collate;";
            //maybe_create_table($wpdb->downloads, $create_table);
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
dbDelta($create_table);

}


        // Create WP-Downloadshop Table
if($wpdb->get_var("SHOW TABLES LIKE '$wpdb->simplejobapp'") != $wpdb->simplejobapp){
    $create_table2 = "CREATE TABLE $wpdb->simplejobapp (".
        "id BIGINT( 20 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,".
        "fullname VARCHAR( 255 ) NOT NULL default '',".
        "jobids VARCHAR( 255 ) NOT NULL default '',".
        "email VARCHAR( 255 ) NOT NULL default '',".
        "des text NOT NULL default '',".
        "status tinyint(1) NOT NULL default '0',".
        "hash VARCHAR( 255 ) NOT NULL default '',".
        "applydate DATETIME NULL) $charset_collate;";

            //maybe_create_table($wpdb->downloadshop, $create_table2);
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
dbDelta($create_table2);
}


	// Create simplejob Folder
if (function_exists('is_site_admin')) {
  if(!is_dir(WP_CONTENT_DIR.'/blogs.dir/'.$blog_id.'/simplejob/')) {
     mkdir(WP_CONTENT_DIR.'/blogs.dir/'.$blog_id.'/simplejob/', 0777, true);
 }
} else {
  if(!is_dir(WP_CONTENT_DIR.'/simplejob/')) {
     mkdir(WP_CONTENT_DIR.'/simplejob/', 0777, true);
 }
}

	// Set 'manage_jobs' Capabilities To Administrator
$role = get_role('administrator');
if(!$role->has_cap('manage_jobs')) {
  $role->add_cap('manage_jobs');
}
}


### Function: Add Favourite Actions >= WordPress 2.7
add_filter('favorite_actions', 'simplejob_favorite_actions');
function simplejob_favorite_actions($favorite_actions) {
 $favorite_actions['admin.php?page=simplejob/add.php'] = array(__('Add New Job', 'simplejob'), 'manage_jobs');
 return $favorite_actions;
}


add_shortcode('showjobs', 'simplejob_showjobs_frontend');
add_shortcode('showjobform', 'simplejob_showjobs_frontend_form');


?>
