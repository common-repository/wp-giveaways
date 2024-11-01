<?php
/**
 * Custom Post Type
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   Custom Post Type for WP Giveaways
 * @author    Zoran C. <web@zoranc.co>
 * @license   GPL2
 * @link      http://zoranc.co
 * @copyright 2014 Zoran C.
 */
add_action( 'init', 'register_giveaway_cpt');
function register_giveaway_cpt() {
  register_post_type( 'giveaway',
       	array(
	       'labels' => array(
		     'name'               => __( 'Giveaways' ),
		     'menu_name'          => __( 'Giveaways' ),
		     'singular_name'      => __( 'Giveaway' ),
		     'add_new'            => __( 'Add New Giveaway' ),
		     'add_new_item'       => __( 'Add New Giveaway' ),	 
		     'edit'               => __( 'Edit' ),
		     'edit_item'          => __( 'Edit Giveaway' ),
		     'new_item'           => __( 'New Giveaway' ),
		     'view'               => __( 'View Giveaway' ),
		     'view_item'          => __( 'View Giveaway' ),
		     'search_items'       => __( 'Search Giveaways' ),
		     'not_found'          => __( 'No giveaways found' ),
		     'not_found_in_trash' => __( 'No giveaways found in Trash' ),
	          ),
   
	       'public'     	   => true,
	       'show_ui' 		   => true,
	       'show_in_menu' 	   => true,//$this->plugin_slug,
	       'show_in_admin_bar'   => true,
	       'show_in_nav_menus'   => false,
	       'publicly_queryable'  => false,
	       'exclude_from_search' => true,
	       'menu_position'       => 28,
	       'menu_icon'	   => 'dashicons-awards',
	       'hierarchical' 	   => false,
	       'query_var' 	   => true,
			     //  'rewrite' => array( 'slug' => 'giveaways', 'with_front' => false ),
	       //'taxonomies' => array( 'category'),
	       'can_export' 	   => true,
	       'supports'	 	   => array('title' )//'editor', 'excerpt', 'author','thumbnail','editor','custom-fields')
	  )
  );
}
?>
