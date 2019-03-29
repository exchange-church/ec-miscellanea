<?php
/*
* Create a custom leadership taxonomy
*/ 
function create_leadership_taxonomy()
{
	// Set UI labels
	$labels = array(
		'name' => _x('Leadership Roles', 'taxonomy general name'),
		'singular_name' => _x('Leadership Role', 'taxonomy singular name'),
		'search_items' =>  __('Search Leadership Roles'),
		'all_items' => __('All Leadership Roles'),
		'edit_item' => __('Edit Leadership Role'),
		'view_item' => __('View Leadership Role'),
		'update_item' => __('Update Leadership Role'),
		'popular_items' => __('Popular Leadership Roles'),
		'add_new_item' => __('Add New Leadership Role'),
		'new_item_name' => __('New Leadership Role'),
		'separate_items_with_commas' => __('Separate leadership roles with commas'),
		'add_or_remove_items' => __('Add or remove leadership roles'),
		'choose_from_most_used' => __('Choose from most used leadership roles'),
		'not_found' => __('No leadership roles found.'),
		'back_to_items' => __('Back to leadership roles'),
		'menu_name' => __('Leadership Roles'),
	);
 
	// Register the custom taxonomy
	register_taxonomy('leadership_role', 'ec-leadership', array(
		'hierarchical' => false,
		'labels' => $labels,
		'show_ui' => true,
		'show_admin_column' => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var' => true,
		'rewrite' => array('slug' => 'leadership-role'),
	));
}
add_action('init', 'create_leadership_taxonomy', 0);

/*
* Create a custom Leadership post type
*/
function ec_leadership_post_type()
{
	// Set UI labels
	$labels = array(
		'name' => _x('Leadership', 'Post Type General Name', 'exchange-elementor'),
		'singular_name' => _x('Leadership Member', 'Post Type Singular Name', 'exchange-elementor'),
		'menu_name' => __('Leadership', 'exchange-elementor'),
		'all_items' => __('All Leadership', 'exchange-elementor'),
		'view_item' => __('View Leadership Team Member', 'exchange-elementor'),
		'view_items' => __('View Leadership', 'exchange-elementor'),
		'new_item' => __('New Leadership Team Member', 'exchange-elementor'),
		'add_new_item' => __('Add New Leadership Team Member', 'exchange-elementor'),
		'edit_item' => __('Edit Leadership Team Member', 'exchange-elementor'),
		'update_item' => __('Update Leadership Team Member', 'exchange-elementor'),
		'search_items' => __('Search Leadership Team', 'exchange-elementor'),
		'not_found' => __('No team member found.', 'exchange-elementor'),
		'not_found_in_trash' => __('No team member found in Trash.', 'exchange-elementor'),
		'archives' => __('All Leadership', 'exchange-elementor'),
		'featured_image' => __('Team Member Photo', 'exchange-elementor'),
		'set_featured_image' => __('Set team member photo', 'exchange-elementor'),
		'remove_featured_image' => __('Remove team member photo', 'exchange-elementor'),
		'use_featured_image' => __('Use as team member photo', 'exchange-elementor'),
		'item_published' => __('Team member info published.', 'exchange-elementor'),
		'item_published_privately' => __('Team member info published privately.', 'exchange-elementor'),
		'item_reverted_to_draft' => __('Team member info reverted to draft.', 'exchange-elementor'),
		'item_scheduled' => __('Team member info scheduled.', 'exchange-elementor'),
		'item_updated' => __('Team member info updated.', 'exchange-elementor'),
	);
		 
	// Set other custom post type options
	$args = array(
		'label' => __('leadership', 'exchange-elementor'),
		'description' => __('Church staff and leadership', 'exchange-elementor'),
		'labels' => $labels,
		'supports' => array('title', 'thumbnail', 'revisions',),
		'taxonomies' => array('leadership_role'),
		'public' => true,
		'has_archive' => true,
		'menu_position' => 20,
		'capability_type' => 'post', //page
		'rewrite' => array('slug' => 'leadership'),
		'menu_icon' => 'dashicons-groups',
	);
		 
	register_post_type( 'ec-leadership', $args ); 
}
add_action( 'init', 'ec_leadership_post_type', 0 );

/**
 * Define the metabox and field configurations for the Leadership post type
 */
function ec_leadership_metaboxes() {
	$prefix = 'ec-leadership_';

	/**
	 * Initiate the metabox
	 */
	$cmb = new_cmb2_box(array(
		'id'            => 'leadership_details_metabox',
		'title'         => __('Team Member Info', 'cmb2'),
		'object_types'  => array('ec-leadership',), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
	));

	/**
	 * Add fields to the metabox
	 */
	$cmb->add_field(array(
		'name' => __('Last Name', 'cmb2'),
		'desc' => __('Used for sorting', 'cmb2'),
		'id' => $prefix . 'last_name',
		'type' => 'text',
	));

	$cmb->add_field(array(
		'name' => __('Title/Role', 'cmb2'),
		'desc' => __('Title or role within Exchange', 'cmb2'),
		'id' => $prefix . 'job_title',
		'type' => 'text',
	));

	$cmb->add_field(array(
		'name' => __('Bio', 'cmb2'),
		'desc' => __('Biographical information to be displayed on leadership page', 'cmb2'),
		'id' => $prefix . 'bio',
		'type' => 'textarea',
	));

	$cmb->add_field(array(
		'name' => __('Hope for EC', 'cmb2'),
		'desc' => __('Hope for the future of Exchange Church', 'cmb2'),
		'id' => $prefix . 'hope',
		'type' => 'textarea',
	));

	$cmb->add_field(array(
		'name' => __('Email', 'cmb2'),
		'id' => $prefix . 'email',
		'type' => 'text_email',
	));

}
add_action( 'cmb2_admin_init', 'ec_leadership_metaboxes' );

/*
Sort leadership posts by last name
*/
function ec_leadership_sort_by_last_name( $query ) {
	if ( !is_admin() && $query->is_main_query() ) {
        if ( $query->query_vars['post_type'] == 'ec-leadership' ) {
			$query->set('meta_key', 'ec-leadership_last_name');
			$query->set('orderby', 'meta_value');
			$query->set('order', 'ASC');
		}
	}
}
add_action( 'pre_get_posts', 'ec_leadership_sort_by_last_name' );