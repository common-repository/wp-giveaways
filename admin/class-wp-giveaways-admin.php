<?php
/**
 * WP Giveaways.
 *
 * @package   WP Giveaways
 * @author    Zoran C. <web@zoranc.co>
 * @license   GPL2
 * @link      http://zoranc.co/wp-giveaways/
 * @copyright 2014 Zoran C.
 */

/**
 * Plugin class. This class is used to work with the
 * administrative side of the WordPress site.
 *
 * @package WP_Giveaways_Admin
 * @author  Zoran C. <web@zoranc.co>
 */
class WP_Giveaways_Admin {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;
	
	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;
	/**
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'wp-giveaways';
	/**
	 * Initialize the plugin by loading admin scripts & styles.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {
		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
		
		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );
		
		add_action( 'init', array( $this, 'wp_giveaways_custom_meta' ) );
		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_slug . '.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {
    		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return if no settings page is registered.
	 */
	public function enqueue_admin_styles() {
		global $pagenow;
		$screen = get_current_screen();
		if ( 'post.php' == $pagenow || 'post-new.php' == $pagenow ) {
		   if ( isset($_GET['post_type']) && $_GET['post_type'] == 'giveaway' || isset($_GET['post']) && get_post_type($_GET['post']) == 'giveaway' ) {
			wp_enqueue_style( $this->plugin_slug .'-cpt-style', plugins_url( 'assets/css/wp-giveaways-admin.css', __FILE__ ), array(), WP_Giveaways::VERSION );
		   }
		}
		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}
	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {
		global $pagenow;
		$screen = get_current_screen();
		if ( 'post.php' == $pagenow || 'post-new.php' == $pagenow ) {
		   if ( isset($_GET['post_type']) && $_GET['post_type'] == 'giveaway' || isset($_GET['post']) && get_post_type($_GET['post']) == 'giveaway' ) {
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( $this->plugin_slug . '-cpt-script', plugins_url( 'assets/js/wp-giveaways-admin.js', __FILE__ ), array( 'jquery' ), WP_Giveaways::VERSION );
		    }
		}
		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

	}
	/**
	 * Load metaboxes and Custom Fields
	 *
	 * @since    1.0.0
	 */
	public function wp_giveaways_custom_meta() {
		require_once(plugin_dir_path( __FILE__ ) . 'includes/custom-fields-giveaways.php' );
	}
	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {
	  add_submenu_page( 'edit.php?post_type=giveaway', 
				__( 'Addons', $this->plugin_slug ), 
				__( 'Addons', $this->plugin_slug ), 
				'manage_options', 
				$this->plugin_slug . '-addons',
				array( $this, 'display_plugin_addons_page') 
			);
	}
	/**
	 * Render the about page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_addons_page() {
		include_once( 'views/admin-addons.php' );
	}
	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {
		return $links; /*
		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'admin.php?page=' . $this->plugin_slug .'-settings') . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
			),
			$links
		);*/

	}
}
