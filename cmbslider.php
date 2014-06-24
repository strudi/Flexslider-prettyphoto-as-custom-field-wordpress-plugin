<?php
/**
 * The WordPress Plugin Boilerplate.
 *
 * A foundation off of which to build well-documented WordPress plugins that
 * also follow WordPress Coding Standards and PHP best practices.
 *
 * @package   cmbSlider
 * @author    Primoz Krkovic <primoz@primas.si>
 * @license   GPL-2.0+
 * @link      medonet.si
 * @copyright 2014 Primoz Krkovic
 *
 * @wordpress-plugin
 * Plugin Name:       cmbSlider
 * Plugin URI:       medonet.si
 * Description:       Custom slider field for cmb
 * Version:           1.0.0
 * Author:       Primoz Krkovic
 * Author URI:       medonet.si
 * Text Domain:       cmbslider
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/<owner>/<repo>
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

/*
 * @TODO:
 *
 * - replace `class-cmbslider.php` with the name of the plugin's class file
 *
 */

define('CMB_SLIDER_PLUGIN_DIR', plugin_dir_path( __FILE__ ));

if( ! class_exists( 'CMB_Slider_Template_Loader' ) ) {
	require plugin_dir_path( __FILE__ ) . 'includes/class-cmbslider-template-loader.php';
}

require_once( plugin_dir_path( __FILE__ ) . 'public/class-cmbslider.php' );


/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 *
 * @TODO:
 *
 * - replace cmbSlider with the name of the class defined in
 *   `class-cmbslider.php`
 */
register_activation_hook( __FILE__, array( 'cmbSlider', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'cmbSlider', 'deactivate' ) );

/*
 * @TODO:
 *
 * - replace cmbSlider with the name of the class defined in
 *   `class-cmbslider.php`
 */
add_action( 'plugins_loaded', array( 'cmbSlider', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

/*
 * @TODO:
 *
 * - replace `class-cmbslider-admin.php` with the name of the plugin's admin file
 * - replace cmbSlider_Admin with the name of the class defined in
 *   `class-cmbslider-admin.php`
 *
 * If you want to include Ajax within the dashboard, change the following
 * conditional to:
 *
 * if ( is_admin() ) {
 *   ...
 * }
 *
 * The code below is intended to to give the lightest footprint possible.
 */
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-cmbslider-admin.php' );
	add_action( 'plugins_loaded', array( 'cmbSlider_Admin', 'get_instance' ) );

}
