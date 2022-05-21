<?php
ob_start();
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.fiverr.com/junaidzx90
 * @since             1.0.0
 * @package           Phone_Auth
 *
 * @wordpress-plugin
 * Plugin Name:       Phone Auth
 * Plugin URI:        https://www.fiverr.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Developer Junayed
 * Author URI:        https://www.fiverr.com/junaidzx90
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       phone-auth
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PHONE_AUTH_VERSION', '1.0.1' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-phone-auth-activator.php
 */
function activate_phone_auth() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-phone-auth-activator.php';
	Phone_Auth_Activator::activate();
}


add_action( "template_redirect", function(){
	if(is_page( 'app-home' ) && !is_admin()){
		if(is_user_logged_in(  )){
			if(current_user_can( 'driver' )){
				wp_safe_redirect( home_url( '/trent' ) );
				exit;
			}elseif(current_user_can('client')){
				wp_safe_redirect( home_url( '/profile' ) );
				exit;
			}
		}
	}
} );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-phone-auth-deactivator.php
 */
function deactivate_phone_auth() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-phone-auth-deactivator.php';
	Phone_Auth_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_phone_auth' );
register_deactivation_hook( __FILE__, 'deactivate_phone_auth' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-phone-auth.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_phone_auth() {

	$plugin = new Phone_Auth();
	$plugin->run();

}
run_phone_auth();
