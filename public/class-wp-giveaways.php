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
 * public-facing side of the WordPress site.
 *
 * @package WP_Giveaways
 * @author  Zoran C. <web@zoranc.co>
 */
class WP_Giveaways {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '1.0.1';
	
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
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {
    // Load plugin text domain
    add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
    // Set up the custom post types

    // Activate plugin when new blog is added
    add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );
	  
		// Load public-facing style sheet and JavaScript.
		//add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		//add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_shortcode( 'giveaway' , array( $this, 'wp_giveaway_shortcode' ) );
	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
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
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 */
	public static function activate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide  ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();
				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_activate();
				}

				restore_current_blog();

			} else {
				self::single_activate();
			}

		} else {
			self::single_activate();
		}

	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Deactivate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog id
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_deactivate();

				}

				restore_current_blog();

			} else {
				self::single_deactivate();
			}

		} else {
			self::single_deactivate();
		}

	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @since    1.0.0
	 *
	 * @param    int    $blog_id    ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {

		if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();

	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since    1.0.0
	 *
	 * @return   array|false    The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {

		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );

	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since    1.0.0
	 */
	private static function single_activate() {
	  wp_schedule_event( strtotime('+1 min', time()) ,'twicedaily', 'wp_giveaways_schedule_cron' );
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 */
	private static function single_deactivate() {
		wp_clear_scheduled_hook( 'wp_giveaways_schedule_cron' );
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {
		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );

	}
	/**
	 * Execute giveaway hourly cron
	 *
	 * @since    1.0.0
	 */
	public static function wp_giveaways_cron() {
		$giveaway_ids = self::get_posts_by_type('giveaway');
		if ( !empty($giveaway_ids) ) {
			foreach ($giveaway_ids as $giveaway_id) {
				self::wp_giveaways_check_giveaway($giveaway_id);
			}
		}
	}
	
	/**
	 * Check giveaway schedule and see if a winner is due to be picked and emailed
	 *
	 * @since    1.0.0
	 */
	public static function wp_giveaways_check_giveaway($giveaway_id) {
		$current_time = time();
		$giveaway_settings = get_post_meta($giveaway_id);

		$scheduled_options = self::wp_giveaways_get_scheduled_options($giveaway_id);
    $scheduled_time = strtotime($scheduled_options['scheduled_time']);
		if ( !$scheduled_time ) {
			do_action('wp_giveaways_expired_check', $giveaway_id, $current_time);
		} else if ($scheduled_time < $current_time) {
			self::execute_draw($giveaway_id, $giveaway_settings, $scheduled_options['limit_since'], $current_time);
		}
	}
	
	/**
	 * Check giveaway schedule and limitations on eligible contestants
	 *
	 * @since    1.0.0
	 * @return array with scheduled time and limitation
	 */
	public static function wp_giveaways_get_scheduled_options($giveaway_id) {
		$giveaway = get_post($giveaway_id);
		$giveaway_settings = get_post_meta($giveaway_id);
		$giveaway_history = get_post_meta($giveaway_id, 'Giveaway_History', true);
		
		if ( empty($giveaway_history) ) {
			$publish_time = get_the_time('U', $giveaway->ID);
			$initial_delay = $giveaway_settings['Giveaway_Initial_Time'][0];
			$time = strtotime("+{$initial_delay} days", $publish_time);
			$scheduled_time = gmdate("l, F j, Y", $time);
			$limit_since = isset($giveaway_settings['giveaway_limit_during_countdown']) && $giveaway_settings['giveaway_limit_during_countdown'][0] == 'on' ? $publish_time : 0;
		} else if ( isset($giveaway_settings['Giveaway_Recurring'][0]) && $giveaway_settings['Giveaway_Recurring'][0] === 'on' ) {
			$previous = end($giveaway_history);
			$previous_run = $previous['time'];
			$recurring_delay = $giveaway_settings['Giveaway_Time'][0];
			$time = strtotime("+{$recurring_delay} days", $previous_run);
			$scheduled_time = gmdate("l, F j, Y", $time);
			$limit_since = isset($giveaway_settings['giveaway_limit_during_countdown']) && $giveaway_settings['giveaway_limit_during_countdown'][0] == 'on' ? $previous_run : 0;
			$limit_since = apply_filters( 'wp_giveaways_limit_since', $limit_since, $giveaway_id );

		} else {
			$scheduled_time = __("Giveaway Expired!", 'wp-giveaways');
			$limit_since = 0;
		}
		$scheduled_options = array('scheduled_time' => $scheduled_time, 'limit_since' => $limit_since);
		return apply_filters('wp_giveaways_scheduled_options', $scheduled_options, $giveaway_id);
	}
	
	/**
	 * Run the draw for a given giveaway id.
	 *
	 * @since    1.0.0
	 * @return array of eligeble contestant email addresses
	 */
	public static function execute_draw($giveaway_id, $giveaway_settings, $limit_since, $current_time) {
		$contestant_pool = self::wp_giveaway_generate_pool($giveaway_settings, $limit_since);
		
		if ( !empty($contestant_pool) ) {
			$winners = self::wp_giveaway_pick_winners($contestant_pool, $giveaway_settings['giveaway_n_o_winners'][0]);
			self::wp_giveaway_alert_winners($giveaway_id, $winners);
		} else {
			$winners = __('There were not enough eligible contestants!', 'wp-giveaways');
		}

		$giveaway_history = get_post_meta($giveaway_id, 'Giveaway_History', true);
		$giveaway_history[] = array( 'time' => $current_time, 'winners' => $winners);
		update_post_meta($giveaway_id, 'Giveaway_History', $giveaway_history);
		
		self::wp_giveaway_admin_alert($giveaway_id, $winners);
	}
	
	/**
	 * Generate the contestant pool(array) to draw from.
	 *
	 * @since    1.0.0
	 * @return array of eligeble contestant email addresses
	 */
	public static function wp_giveaway_generate_pool($giveaway_settings, $limit_since) {
		$contestant_pool = array();
		if ( 'role' === $giveaway_settings['giveaway_pick_contestants_by'][0] ) {
			$giveaway_pool_object = get_users( array('role' => $giveaway_settings['giveaway_by_role'][0]) );
			if (!empty($giveaway_pool_object)) {
				foreach ($giveaway_pool_object as $contestant) {
					if (strtotime($contestant->user_registered) >= $limit_since) {
						$contestant_pool[] = $contestant->user_email;
					}
				}
			}
		}
		return apply_filters( 'wp_giveaways_contestant_pool', $contestant_pool, $giveaway_settings, $limit_since );
	}
	
	/**
	 * Pick random winners.
	 *
	 * @since    1.0.0
	 * @return array of winning email addresses
	 */
	public static function wp_giveaway_pick_winners($contestant_pool, $no_of_contestants) {
		$winners = array();
		shuffle($contestant_pool);
		$no_of_winners = count($contestant_pool) < $no_of_contestants ? count($contestant_pool) : $no_of_contestants;
		for ($i = 0; $i < $no_of_winners; $i++) {
			$winners[] = $contestant_pool[$i];
		}
		return apply_filters ('wp_giveaways_picked_winners', $winners);
	}
	
	/**
	 * Alert winners.
	 *
	 * @since    1.0.0
	 * @return none
	 */
	public static function wp_giveaway_alert_winners($giveaway_id, $winners) {
		$subject = get_post_meta( $giveaway_id, 'Giveaway_Email_Subject' , true );
		$message = get_post_meta( $giveaway_id, 'Giveaway_Winner_Email_Template' , true );
		$from = get_post_meta( $giveaway_id, 'Giveaway_Email_From' , true );
		$headers = "From: {$from}";
		$attachment_ids = explode( ',' , get_post_meta($giveaway_id, 'Giveaway_Prize_File_Attachment' , true) );
		$attachments = array();
		foreach ($attachment_ids as $attachment_id) {
			 $attachments[] = get_attached_file( $attachment_id );
		}
		if( isset($attachments[0]) && $attachments[0] == '' ) {
			$attachments = '';
		}
		add_filter( 'wp_mail_content_type', array('WP_Giveaways', 'set_html_content_type') );
		foreach ( $winners as $winner ) {
			wp_mail( $winner, $subject, $message, $headers, $attachments );
		}
		remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
	}
	
	/**
	 * Send email alert
	 *
	 * @since    1.0.0
	 * @return string
	 */
	public static function wp_giveaway_admin_alert($giveaway_id, $winners) {
		$giveaway_history = get_post_meta($giveaway_id, 'Giveaway_History', true);
		$last_run = end($giveaway_history);
		$timestamp = $last_run['time'];
		$time = gmdate("l, F j, Y", $timestamp);
		$winners = implode (', ' , $winners );
		$title = get_the_title($giveaway_id);

		$to = apply_filters( 'wp_giveaways_alert_admin_to' , get_option('admin_email'), $giveaway_id );
		$subject = apply_filters( 'wp_giveaways_alert_admin_subject' , __('A draw occured', 'wp-giveaways'), $giveaway_id);
		$message = apply_filters( 'wp_giveaways_alert_admin_message' , sprintf(__("A draw occured on %s for %s. Winner(s): %s" , 'wp-giveaways'), $time, $title, $winners), $giveaway_id);

		wp_mail( $to, $subject, $message );

	}
	
	/**
	 * Set content type for wp_mail
	 *
	 * @since    1.0.0
	 * @return string
	 */
	public static function set_html_content_type() {
		return 'text/html';
	}
	
	/**
	 * Register and enqueue public-facing style sheet.
	 * @TODO Include only on needed pages
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_slug . 'public-styles', plugins_url( 'assets/css/wp-giveaways-public.css', __FILE__ ), array(), self::VERSION );
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 * @TODO Include only on needed pages
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'assets/js/wp-giveaways-public.js', __FILE__ ), array( 'jquery' ), self::VERSION );
	}
	
 	/**
	 * Return the shortcode content.
	 *
	 * @since    1.0.0
	 *
	 * @return    Content HTML.
	 */
	public function wp_giveaway_shortcode($atts) {
		$args = shortcode_atts(array(
			  'id'       => false
		    	), $atts, 'giveaway'
		);
		$giveaway_id = (int)$args['id'];
		$current_time = time();

		$scheduled_options = self::wp_giveaways_get_scheduled_options($giveaway_id);
		$scheduled_time = $scheduled_options['scheduled_time'];
		$html  = "<div id=giveaway-countdown-{$giveaway_id}>";
		$html .= apply_filters( 'wp_giveaways_shortcode_notice', __("Next sweepstakes draw: ", $this->plugin_slug), $giveaway_id);
		$html .= "<div date='{$scheduled_time}'>";
		$html .= $scheduled_time;
		$html .= "</div>";
		$html .= "</div>";
		return apply_filters('wp_giveaways_shortcode_html', $html , $giveaway_id);
	}
	
	/*
	 * Fetch all posts by the selected type
	 *
	 * @since    1.0.0
	 * @param    string $post_type
	 * @return   integer $id.
	 */
	public static function get_posts_by_type ( $post_type ) {
	  	$args=array(
			'post_type' => $post_type,
			//'post_status' => 'publish',
			'posts_per_page' => -1/*,
			'orderby' => 'title',
			'order' => 'ASC'*/
		);
		$custom_post_ids = array();
		$custom_post_query = new WP_Query($args);
		if( $custom_post_query->have_posts() ) {
			while ($custom_post_query->have_posts()) : $custom_post_query->the_post();
		  		$current_ID = get_the_ID();
		  		$custom_post_ids[] = $current_ID;
			endwhile;
		}
		return $custom_post_ids;
	}
}
