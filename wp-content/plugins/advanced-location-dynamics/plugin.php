<?php
/**
 * @wordpress-plugin
 * Plugin Name: 	Adtrak Advanced Location Dynamics
 * Plugin URI: 		
 * Description: 	An advanced version of Location Dynamics.
 * Version: 		0.9.14
 * Author: 			Adtrak
 * Author URI: 		https://adtrak.co.uk
 * License: 		GPL-2.0+
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:     advanced-ld
 */

# if this file is called directly, abort
if (! defined( 'WPINC' )) die;

if (! defined( 'ADTK_HOME_URL' ))
	define('ADTK_HOME_URL', 'http://plugins.adtrakdev.com/');

if (! class_exists('EDD_SL_Plugin_Updater')) {
    include (dirname( __FILE__ ) . '/updater.php');
}

// retrieve our license key from the DB (SET THIS)
$license_key = trim(get_option('adv-ld_license'));

// setup the updater
$edd_updater = new EDD_SL_Plugin_Updater(ADTK_HOME_URL, __FILE__, array(
    'version'       => '0.9.14',        // current version number
    'license'       => $license_key,    // license key (used get_option above to retrieve from DB)
    'item_name'     => 'Advanced Location Dynamics',    // name of this plugin
    'author'        => 'Adtrak'        // author of this plugin
));

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/vendor/getbilly/framework/bootstrap/autoload.php';

if (file_exists(WP_PLUGIN_DIR . '/.env.ald')) {
    $dotenv = Dotenv\Dotenv::createImmutable(WP_PLUGIN_DIR, '.env.ald');
    $dotenv->load();
}