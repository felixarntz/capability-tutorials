<?php
/**
 * Post type registration
 *
 * @package Capability_Tutorials
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the tutorial post type.
 *
 * @since 1.0.0
 *
 * @return bool True if post type was registered, false otherwise.
 */
function ct_register_post_type() {
	if ( post_type_exists( CT_POST_TYPE_SINGULAR ) ) {
		return false;
	}

	$args = array(
		'labels'          => array(
			'name'                  => _x( 'Tutorials', 'post type general name', 'capability-tutorials' ),
			'singular_name'         => _x( 'Tutorial', 'post type singular name', 'capability-tutorials' ),
			'add_new'               => _x( 'Add New', 'tutorial', 'capability-tutorials' ),
			'add_new_item'          => __( 'Add New Tutorial', 'capability-tutorials' ),
			'edit_item'             => __( 'Edit Tutorial', 'capability-tutorials' ),
			'new_item'              => __( 'New Tutorial', 'capability-tutorials' ),
			'view_item'             => __( 'View Tutorial', 'capability-tutorials' ),
			'view_items'            => __( 'View Tutorials', 'capability-tutorials' ),
			'search_items'          => __( 'Search Tutorials', 'capability-tutorials' ),
			'not_found'             => __( 'No tutorials found.', 'capability-tutorials' ),
			'not_found_in_trash'    => __( 'No tutorials found in Trash.', 'capability-tutorials' ),
			'parent_item_colon'     => __( 'Parent Tutorial:', 'capability-tutorials' ),
			'all_items'             => __( 'All Tutorials', 'capability-tutorials' ),
			'archives'              => __( 'Tutorial Archives', 'capability-tutorials' ),
			'attributes'            => __( 'Tutorial Attributes', 'capability-tutorials' ),
			'insert_into_item'      => __( 'Insert into tutorial', 'capability-tutorials' ),
			'uploaded_to_this_item' => __( 'Uploaded to this tutorial', 'capability-tutorials' ),
			'filter_items_list'     => __( 'Filter tutorials list', 'capability-tutorials' ),
			'items_list_navigation' => __( 'Tutorials list navigation', 'capability-tutorials' ),
			'items_list'            => __( 'Tutorials list', 'capability-tutorials' ),
		),
		'public'          => true,
		'show_in_rest'    => true,
		'rest_base'       => CT_POST_TYPE_PLURAL,
		'menu_position'   => 21,
		'menu_icon'       => 'dashicons-welcome-learn-more',
		// If 'map_meta_cap' is true, WordPress will take care of mapping the meta capabilities, otherwise you need to do it.
		'map_meta_cap'    => true,
		// Specifying a singular and plural slug for 'capability_type' will have WordPress automatically create custom capabilities
		// for you. However, there will be no custom 'read' capability, and the 'create_posts' capability will be the same as the
		// 'edit_posts' one.
		'capability_type' => array( CT_POST_TYPE_SINGULAR, CT_POST_TYPE_PLURAL ),
		// Under 'capabilities' you can specify the custom capabilities that should be used instead of the regular post capabilities.
		// This is the most explicit way of handling post type capabilities, and it is the only way to have a custom 'read' capability
		// and a custom 'create_posts' capability.
		'capabilities'    => array(
			// The following are primitive capabilities.
			'edit_posts'             => 'edit_' . CT_POST_TYPE_PLURAL,
			'edit_others_posts'      => 'edit_others_' . CT_POST_TYPE_PLURAL,
			'publish_posts'          => 'publish_' . CT_POST_TYPE_PLURAL,
			'read_private_posts'     => 'read_private_' . CT_POST_TYPE_PLURAL,
			'create_posts'           => 'create_' . CT_POST_TYPE_PLURAL, // If we didn't specify this, it would be the same as the value of 'edit_posts'.
			// The following are primitive capabilities that are only required if 'map_meta_cap' is true (see above), but should be provided in either case.
			'edit_private_posts'     => 'edit_private_' . CT_POST_TYPE_PLURAL,
			'edit_published_posts'   => 'edit_published_' . CT_POST_TYPE_PLURAL,
			'delete_posts'           => 'delete_' . CT_POST_TYPE_PLURAL,
			'delete_others_posts'    => 'delete_others_' . CT_POST_TYPE_PLURAL,
			'delete_private_posts'   => 'delete_private_' . CT_POST_TYPE_PLURAL,
			'delete_published_posts' => 'delete_published_' . CT_POST_TYPE_PLURAL,
			'read'                   => 'read_' . CT_POST_TYPE_PLURAL, // If we didn't specify this, it would be 'read'.
			// The following are meta capabilities. Note that there is no meta capability for publishing a post.
			'edit_post'              => 'edit_' . CT_POST_TYPE_SINGULAR,
			'delete_post'            => 'delete_' . CT_POST_TYPE_SINGULAR,
			'read_post'              => 'read_' . CT_POST_TYPE_SINGULAR,
		),
		'supports'        => ct_get_supports(),
		'has_archive'     => ct_has_archive(),
		'rewrite'         => array(
			'slug'       => ct_get_rewrite_slug(),
			'with_front' => false,
		),
	);

	$post_type = register_post_type( CT_POST_TYPE_SINGULAR, $args );

	if ( ! $post_type || is_wp_error( $post_type ) ) {
		return false;
	}

	return true;
}
add_action( 'init', 'ct_register_post_type' );
