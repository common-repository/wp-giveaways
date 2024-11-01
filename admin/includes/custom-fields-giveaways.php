<?php
/**
 * Learning Managment System.
 *
 * @package   WP Giveaways Custom Fields and Metaboxes
 * @author    Zoran C. <web@zoranc.co>
 * @license   GPL2
 * @link      http://zoranc.co/wp-giveaways/
 * @copyright 2014 Zoran C.
 */
/**
 * Add Shortcode info to publish metabox
 *
 * @since   1.0.0
 *
 */
add_action( 'post_submitbox_misc_actions', 'giveaway_shortcode' );
function giveaway_shortcode() {
    global $post;
    if ('giveaway' === $post->post_type) {
      echo "<div id='wp-giveaway-shortcode'><span data-code='f313' class='dashicons dashicons-awards'></span>Shortcode: <span id='shortcode-text'>[giveaway id={$post->ID}]</span></div>";
    }
}
/**
 * Setup the Metabox for the custom fields
 *
 * @since   1.0.0
 *
 */
add_action( 'add_meta_boxes', 'meta_box_giveaway_setup' );
function meta_box_giveaway_setup() {
	//global $_wp_post_type_features;
	//unset($_wp_post_type_features['giveaway']['editor']);
	
	$meta_box_giveaway = custom_fields_giveaway_setup();
	foreach($meta_box_giveaway as $value) {
	  //add_meta_box($value['id'], $value['title'],  array(&$this, 'lms_format_box'), $post_type, $value['context'], $value['priority']);
	  $fields = $value['fields'];
	  add_meta_box($value['id'], $value['title'], 'giveaway_format_box', 'giveaway', $value['context'], $value['priority'], $fields);
	}
	// add back the editor
	/*add_meta_box(
		'description_sectionid',
		__('Winner Alert Template Email:', 'wp-giveaways' ),
		'giveaway_inner_custom_box',
		'giveaway', 'normal', 'low'
	);*/
}
/**
 * Set up the editor
 *
 * @since   1.0.0
 *
 * @return echoes editor html
 */
function giveaway_inner_custom_box( $post ) {
	echo '<div class="wp-editor-wrap">';
	wp_editor($post->post_content, 'content', array('dfw' => true, 'tinymce' => true, 'tabindex' => 1) );
	//$meta = get_post_meta($post->ID, 'giveaways-store-product-id', true);
	echo '</div>';
	}
/**
 * Setup the custom fields. Creates the course_outline field 
 * which stores the layout as an array.
 *
 * @since   1.0.0
 *
 */
function custom_fields_giveaway_setup () {
  	$meta_box_giveaway['giveaway-history'] = array(
	  'id'       => 'giveaway-history',
	  'title'    => __( 'History:', 'wp-giveaways' ),
	  'context'  => 'advanced',
	  'priority' => 'low',
	  'fields'   => array(
		array(
		  'name'    => __('', 'wp-giveaways' ),
		  'id'      => 'Giveaway_History',
		  'type'    => 'history',
		  'default' => ''
		),
	  )
	);
	$meta_box_giveaway['giveaway-winner-email-template'] = array(
	  'id'       => 'giveaway-winner-email-template',
	  'title'    => __( 'Winner Alert Template Email:', 'wp-giveaways' ),
	  'context'  => 'advanced',
	  'priority' => 'default',
	  'fields'   => array(
		array(
		  'name'    => __('From:', 'wp-giveaways' ),
		  'id'      => 'Giveaway_Email_From',
		  'type'    => 'text',
		  'default' => ''
		),
		array(
		  'name'    => __('Subject:', 'wp-giveaways' ),
		  'id'      => 'Giveaway_Email_Subject',
		  'type'    => 'text',
		  'default' => 'Congratulations!'
		),
		array(
		  'name'    => __('', 'wp-giveaways' ),
		  'id'      => 'Giveaway_Winner_Email_Template',
		  'type'    => 'editor',
		  'default' => ''
		),
		array(
		  'name'    => __('Attach Files via attachment_id (comma separated):', 'wp-giveaways' ),
		  'id'      => 'Giveaway_Prize_File_Attachment',
		  'type'    => 'text',
		  'default' => ''
		)
	     )
	  );
	  $meta_box_giveaway['giveaway-countdown'] = array(
	  'id'       => 'giveaway-countdown',
	  'title'    => __( 'Giveaway Countdown', 'wp-giveaways' ),
	  'context'  => 'side',
	  'priority' => 'default',
	  'fields'   => array(
		array(
		  'name'    => __('Initial Giveaway(in days):', 'wp-giveaways' ),
		  'id'      => 'Giveaway_Initial_Time',
		  'type'    => 'text',
		  'default' => '14'
		),
		array(
		  'name'    => __('Recurring Giveaway?', 'wp-giveaways' ),
		  'id'      => 'Giveaway_Recurring',
		  'type'    => 'checkbox',
		  'default' => ''
		),
		array(
		  'name'    => __('Countdown in Days:', 'wp-giveaways' ),
		  'id'      => 'Giveaway_Time',
		  'type'    => 'text',
		  'default' => '14'
		),
	  ),
	);
	global $wp_roles; $roles = $wp_roles->get_names();
	$giveaway_pick_contestants_by = apply_filters('wp_giveaways_pick_contestants_by', array( 'role' => 'Role' ) );
	$custom_list = apply_filters('wp_giveaways_admin_custom_list', array());

	$meta_box_giveaway['giveaway-list'] = array(
	  'id'       => 'giveaway-list',
	  'title'    => __( 'Winner Pick', 'wp-giveaways' ),
	  'context'  => 'advanced',
	  'priority' => 'high',
	  'fields'   => array(
		array(
		  'name'    => __( '# of Winners:', 'wp-giveaways' ),
		  'id'      => 'giveaway_n_o_winners',
		  'type'    => 'text',
		  'default' => 1
		),
		array(
		  'name'    => __( 'Limit to subscriptions during countdown?', 'wp-giveaways' ),
		  'id'      => 'giveaway_limit_during_countdown',
		  'type'    => 'checkbox',
		  'default' => ''
		),
		array(
		  'name'    => __( 'Choose winner(s) from:', 'wp-giveaways' ),
		  'id'      => 'giveaway_pick_contestants_by',
		  'class'   => 'giveaway-by',
		  'type'    => 'select',
		  'default' => 'Role',
		  'options' => $giveaway_pick_contestants_by
		),
		array(
		  'name'    => __( 'Role:', 'wp-giveaways' ),
		  'id'      => 'giveaway_by_role',
		  'class'   => 'giveaway-list',
		  'type'    => 'select',
		  'default' => 'subscriber',
		  'options' => $roles
		),
		apply_filters('wp_giveaways_admin_custom_lists', array(
					  'name'    => __( 'Custom List:', 'wp-giveaways' ),
					  'id'      => 'giveaway_by_custom_list',
					  'class'   => 'giveaway-list',
					  'type'    => 'select',
					  'default' => '',
					  'options' => $custom_list
					)
		)

	  ),
	);
	return $meta_box_giveaway;
}
/**
 * Display custom fields html
 *
 * @since   1.0.0
 *
 * @return echoes field html
 */
function giveaway_format_box($post, $fields) {
  $fields = $fields['args'];
  $nonce = wp_create_nonce(basename(__FILE__));
  // Use nonce for verification
  echo '<input type="hidden" name="giveaway_meta_box_nonce" value="' . $nonce . '" />';
  echo '<table class="form-table">';
  foreach ( $fields as $field ) {
	// get current post meta data
	$meta = get_post_meta($post->ID, $field['id'], true) ? get_post_meta($post->ID, $field['id'], true) : $field['default'];
	echo '<tr class="giveaway_tr">'.
	  '<th style="width:1%;"><label for="'. $field['id'] .'">'. $field['name']. '</label></th>'.
	  '<td class="giveaway_td">';

	switch ($field['type']) {
	  case 'text':
	  echo '<input type="text" name="'. $field['id']. '" id="'. $field['id'] .'" value="'. ($meta ? $meta : $field['default']) . '" size="30"  />';
	  break;
	  case 'textarea':
	  echo '<textarea name="'. $field['id']. '" id="'. $field['id']. '" cols="60" rows="4" style="width:97%">'. ($meta ? $meta : $field['default']) . '</textarea>';
	  break;
	  case 'select':
	  $options = '';
	  echo '<select name="'. $field['id'] . '" id="'. $field['id'] . '" class="' . $field['class'] . '">';
	  foreach ($field['options'] as $option_id => $option) {
		$options .= "<option value ='{$option_id}' ". ( $meta == $option_id ? ' selected="selected"' : '' ) . ">{$option}</option>";
	  }
	  echo apply_filters('wp_giveaway_settings_select', $options, $field);
	  echo '</select>';
	  break;
	  case 'radio':
	  foreach ($field['options'] as $option) {
		echo '<input type="radio" name="' . $field['id'] . '" value="' . $option['value'] . '"' . ( $meta == $option['value'] ? ' checked="checked"' : '' ) . ' />' . $option['name'];
	  }
	  break;
	  case 'checkbox':
	  echo '<input type="checkbox" name="' . $field['id'] . '" id="' . $field['id'] . '"' . ( $meta ? ' checked="checked"' : '' ) . ' />';
	  break;
	  case 'editor':
  	  echo '<div class="wp-editor-wrap">';
	  wp_editor($meta, $field['id'], array('dfw' => true, 'tabindex' => 1) );
	  //$meta = get_post_meta($post->ID, 'giveaways-store-product-id', true);
	  echo '</div>';
	  break;
	  case 'history':
	  //delete_post_meta($post->ID, 'Giveaway_History');
	  if($meta) {
		foreach( $meta as $event ) {
		  echo "<div><p>";
		  echo  gmdate("l, F j, Y", $event['time']) . " - Winners:</p> "; 
		  foreach ($event['winners'] as $winner) {
			echo "<p>{$winner}</p>"; 
		  }
		  echo "</div>";
		}
	  } else {
		echo __('No Draws occured.', 'wp-giveaways');
	  }
	  break;
	}
  }
  echo '</table>';
}
add_action('manage_giveaway_posts_custom_column', 'manage_giveaway_custom_column',10,2);
function manage_giveaway_custom_column($column_key,$post_id) {
  global $pagenow;
  $post = get_post($post_id);
  if ('giveaway' === $post->post_type && is_admin() && 'edit.php' === $pagenow)  {
  	echo ( get_post_meta($post_id,$column_key,true) ) ? number_format(get_post_meta($post_id,$column_key,true)) : "Undefined";
  }
}
// Save data from meta box
add_action('save_post', 'giveaway_save_data' );
function giveaway_save_data($post_id) {
  //global $meta_box;
  global $post;
  //Verify nonce
  if (!isset($_POST['giveaway_meta_box_nonce']) || !wp_verify_nonce($_POST['giveaway_meta_box_nonce'], basename(__FILE__))) {
	return $post_id;
  }
  //Check autosave
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
	return $post_id;
  }
  //Check permissions
  if ('page' == $_POST['post_type']) {
	if (!current_user_can('edit_page', $post_id)) {
	  return $post_id;
	}
  } elseif (!current_user_can('edit_post', $post_id)) {
	return $post_id;
  } elseif( 'giveaway' === $_POST['post_type']) {
	  $custom_fields_giveaway = custom_fields_giveaway_setup();
	  
	  foreach ($custom_fields_giveaway as $metabox) {
	    foreach ($metabox['fields'] as $field) {
		@$old = get_post_meta($post_id, $field['id'], true);
		@$new = $_POST[$field['id']];
		if ($field['id'] === 'Giveaway_History') {
		      // do nothing
		} else if ($new && $new != $old) {
		  update_post_meta($post_id, $field['id'], $new);
		} elseif ('' == $new && $old) {
		  delete_post_meta($post_id, $field['id'], $old);
		}
	    }
	  }
  }
}
//add_action('manage_giveaways_posts_columns',  'manage_giveaways_posts_columns');
function manage_giveaway_posts_columns($post_columns) {
  $post_columns = array(
	'cb' => $post_columns['cb'],
	'date' => 'Date'
  );
  return $post_columns;
}
add_filter( 'manage_edit-giveaways_sortable_columns', 'giveaways_column_register_sortable' );
function giveaway_column_register_sortable( $post_columns ) {
  $post_columns = array(
	'title' => 'title',
	'date' => 'Date'
  );
  return $post_columns;
}

//add_filter( 'request', 'sort_views_column' );
function wpga_sort_views_column( $vars ) {
    if ( isset( $vars['orderby'] ) ) {
        $vars = array_merge( $vars, array()
            //'meta_key' => 'reward_amount',
            //'orderby' => 'meta_value_num')
        );
    }
    return $vars;
}
?>
