<?php
/**
 * Taxonomies registration
 *
 * @package Capability_Tutorials
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the tutorial category taxonomy.
 *
 * @since 1.0.0
 *
 * @return bool True if the taxonomy was registered, false otherwise.
 */
function ct_register_category() {
	if ( taxonomy_exists( CT_CATEGORY_SINGULAR ) ) {
		return false;
	}

	$args = array(
		'public'            => true,
		'show_in_rest'      => true,
		'rest_base'         => CT_CATEGORY_PLURAL,
		'show_admin_column' => true,
		// Under 'capabilities' you can specify the custom capabilities that should be used instead of the regular term capabilities.
		'capabilities'      => array(
			'manage_terms' => 'manage_' . CT_CATEGORY_PLURAL,
			'edit_terms'   => 'edit_' . CT_CATEGORY_PLURAL,
			'delete_terms' => 'delete_' . CT_CATEGORY_PLURAL,
			'assign_terms' => 'assign_' . CT_CATEGORY_PLURAL,
		),
		'hierarchical'      => true,
		'rewrite'           => array(
			'slug'         => ct_get_category_rewrite_slug(),
			'with_front'   => false,
			'hierarchical' => true,
		),
	);

	$taxonomy = register_taxonomy( CT_CATEGORY_SINGULAR, CT_POST_TYPE_SINGULAR, $args );

	if ( ! $taxonomy || is_wp_error( $taxonomy ) ) {
		return false;
	}

	return true;
}
add_action( 'init', 'ct_register_category' );

/**
 * Registers the tutorial tag taxonomy.
 *
 * @since 1.0.0
 *
 * @return bool True if the taxonomy was registered, false otherwise.
 */
function ct_register_tag() {
	if ( taxonomy_exists( CT_TAG_SINGULAR ) ) {
		return false;
	}

	$args = array(
		'public'            => true,
		'show_in_rest'      => true,
		'rest_base'         => CT_TAG_PLURAL,
		'show_admin_column' => true,
		// Under 'capabilities' you can specify the custom capabilities that should be used instead of the regular term capabilities.
		'capabilities'      => array(
			'manage_terms' => 'manage_' . CT_TAG_PLURAL,
			'edit_terms'   => 'edit_' . CT_TAG_PLURAL,
			'delete_terms' => 'delete_' . CT_TAG_PLURAL,
			'assign_terms' => 'assign_' . CT_TAG_PLURAL,
		),
		'hierarchical'      => false,
		'rewrite'           => array(
			'slug'         => ct_get_tag_rewrite_slug(),
			'with_front'   => false,
			'hierarchical' => false,
		),
	);

	$taxonomy = register_taxonomy( CT_TAG_SINGULAR, CT_POST_TYPE_SINGULAR, $args );

	if ( ! $taxonomy || is_wp_error( $taxonomy ) ) {
		return false;
	}

	return true;
}
add_action( 'init', 'ct_register_tag' );
