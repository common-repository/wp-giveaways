<?php
defined( 'ABSPATH' ) OR exit;
/**
 * @package   WP Giveaways
 * @author    Zoran C. <web@zoranc.co>
 * @license   GPL2
 * @link      http://zoranc.co/wp-giveaways/
 * @copyright 2014 Zoran C.
 *
 * @wordpress-plugin
 * Plugin Name:       WP Giveaways
 * Plugin URI:        http://zoranc.co/wp-giveaways/
 * Description:       Create a giveaway program. Select winners from specific user roles to encourage subscriptions to your blog.
 * Version:           1.0.1
 * Author:            Zoran C.
 * Author URI:        http://zoranc.co
 * Text Domain:       wp-giveaways
 * License:           GPL2
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/*----------------------------------------------------------------------------*
 * Components
 *----------------------------------------------------------------------------*/
require_once( plugin_dir_path( __FILE__ ) . 'includes/custom-post-type-giveaways.php' );
/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/
require_once( plugin_dir_path( __FILE__ ) . 'public/class-wp-giveaways.php' );
/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 *
 */
register_activation_hook( __FILE__, array( 'WP_Giveaways', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'WP_Giveaways', 'deactivate' ) );


		add_action( 'wp_giveaways_schedule_cron', array('WP_Giveaways', 'wp_giveaways_cron' ) );
/*
 * Initialize
 */
add_action( 'plugins_loaded', array( 'WP_Giveaways', 'get_instance' ) );
/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/
/*
 *
 * If you want to exclude Ajax within the dashboard, change the following
 * conditional to:
 *
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
 *   ...
 * }
 */
if ( is_admin() ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-wp-giveaways-admin.php' );
	add_action( 'plugins_loaded', array( 'WP_Giveaways_Admin', 'get_instance' ) );

do_action('wp_giveaways_loaded');
}
