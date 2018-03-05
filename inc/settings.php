<?php
/**
 * Setting definitions
 *
 * @package Capability_Tutorials
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the tutorial settings.
 *
 * @since 1.0.0
 */
function ct_register_settings() {
	register_setting( CT_OPTION_GROUP, 'ct_rewrite_slug', array(
		'type'              => 'string',
		'description'       => __( 'The URL slug to prefix tutorial slugs with.', 'capability-tutorials' ),
		'sanitize_callback' => 'ct_sanitize_rewrite_slug',
		'show_in_rest'      => true,
		'default'           => _x( 'tutorials', 'rewrite slug', 'capability-tutorials' ),
	) );

	register_setting( CT_OPTION_GROUP, 'ct_supports', array(
		'type'              => 'array',
		'description'       => __( 'List of core features that tutorials support.', 'capability-tutorials' ),
		'sanitize_callback' => 'ct_sanitize_supports',
		'show_in_rest'      => array(
			'schema' => array(
				'items' => array(
					'type' => 'string',
				),
			),
		),
		'default'           => array( 'title', 'editor', 'comments', 'author', 'thumbnail' ),
	) );

	register_setting( CT_OPTION_GROUP, 'ct_is_hierarchical', array(
		'type'              => 'boolean',
		'description'       => __( 'Whether tutorials have a hierarchical structure.', 'capability-tutorials' ),
		'sanitize_callback' => 'ct_sanitize_is_hierarchical',
		'show_in_rest'      => true,
		'default'           => false,
	) );

	register_setting( CT_OPTION_GROUP, 'ct_has_archive', array(
		'type'              => 'boolean',
		'description'       => __( 'Whether tutorials have archives.', 'capability-tutorials' ),
		'sanitize_callback' => 'ct_sanitize_has_archive',
		'show_in_rest'      => true,
		'default'           => false,
	) );
}
add_action( 'init', 'ct_register_settings', 1 );

/**
 * Gets the rewrite slug to use for the post type.
 *
 * @since 1.0.0
 *
 * @return string Rewrite slug.
 */
function ct_get_rewrite_slug() {
	return get_option( 'ct_rewrite_slug' );
}

/**
 * Gets the core features the post type supports.
 *
 * @since 1.0.0
 *
 * @return array Array of core features.
 */
function ct_get_supports() {
	return (array) get_option( 'ct_supports' );
}

/**
 * Determines whether the post type should be hierarchical.
 *
 * @since 1.0.0
 *
 * @return bool True if post type is hierarchical, false otherwise.
 */
function ct_is_hierarchical() {
	return (bool) get_option( 'ct_is_hierarchical' );
}

/**
 * Determines whether the post type should have archives.
 *
 * @since 1.0.0
 *
 * @return bool True if archives are enabled, false otherwise.
 */
function ct_has_archive() {
	return (bool) get_option( 'ct_has_archive' );
}

/**
 * Gets the rewrite slug to use for the category.
 *
 * @since 1.0.0
 *
 * @return string Rewrite slug.
 */
function ct_get_category_rewrite_slug() {
	return _x( 'tutorial-categories', 'rewrite slug', 'capability-tutorials' );
}

/**
 * Gets the rewrite slug to use for the tag.
 *
 * @since 1.0.0
 *
 * @return string Rewrite slug.
 */
function ct_get_tag_rewrite_slug() {
	return _x( 'tutorial-tags', 'rewrite slug', 'capability-tutorials' );
}

/**
 * Sanitizes and validates the post type rewrite slug.
 *
 * @since 1.0.0
 *
 * @param mixed $value Unsanitized value.
 * @return string Sanitized value, or previously stored value if invalid.
 */
function ct_sanitize_rewrite_slug( $value ) {
	$old_value = ct_get_rewrite_slug();

	if ( ! current_user_can( 'manage_ct_option', 'ct_rewrite_slug' ) ) {
		return $old_value;
	}

	$value = sanitize_text_field( $value );

	if ( empty( $value ) ) {
		add_settings_error( 'ct_rewrite_slug', 'invalid_ct_rewrite_slug', __( 'The rewrite slug must not be empty.', 'capability-tutorials' ) );
		return $old_value;
	}

	if ( ! preg_match( '/^[\w0-9-_]+$/', $value ) ) {
		add_settings_error( 'ct_rewrite_slug', 'invalid_ct_rewrite_slug', __( 'The rewrite slug must not contain characters other than letters, numbers, underscores and hyphens.', 'capability-tutorials' ) );
		return $old_value;
	}

	// Ensure rewrite rules are regenerated when this changes.
	if ( $value !== $old_value ) {
		delete_option( 'rewrite_rules' );
	}

	return $value;
}

/**
 * Sanitizes and validates the core features the post type supports.
 *
 * @since 1.0.0
 *
 * @param mixed $value Unsanitized value.
 * @return array Sanitized value, or previously stored value if invalid.
 */
function ct_sanitize_supports( $value ) {
	$old_value = ct_get_supports();

	if ( ! current_user_can( 'manage_ct_option', 'ct_supports' ) ) {
		return $old_value;
	}

	if ( empty( $value ) ) {
		$value = array();
	} else {
		$value = (array) $value;
	}

	$features = ct_get_available_post_type_features();

	foreach ( $value as $slug ) {
		if ( ! isset( $features[ $slug ] ) ) {
			add_settings_error( 'ct_supports', 'invalid_ct_supports', sprintf( __( 'The feature &#8220;%s&#8221; is not a valid post type feature.', 'capability-tutorials' ), esc_html( $slug ) ) );
			return $old_value;
		}
	}

	return $value;
}

/**
 * Sanitizes whether the post type should be hierarchical.
 *
 * @since 1.0.0
 *
 * @param mixed $value Unsanitized value.
 * @return string Sanitized value, or previously stored value if invalid.
 */
function ct_sanitize_is_hierarchical( $value ) {
	$old_value = ct_is_hierarchical();

	if ( ! current_user_can( 'manage_ct_option', 'ct_is_hierarchical' ) ) {
		return $old_value;
	}

	$value = (bool) $value;

	// Ensure rewrite rules are regenerated when this changes.
	if ( $value !== $old_value ) {
		delete_option( 'rewrite_rules' );
	}

	return $value;
}

/**
 * Sanitizes whether the post type should have archives.
 *
 * @since 1.0.0
 *
 * @param mixed $value Unsanitized value.
 * @return string Sanitized value, or previously stored value if invalid.
 */
function ct_sanitize_has_archive( $value ) {
	$old_value = ct_has_archive();

	if ( ! current_user_can( 'manage_ct_option', 'ct_has_archive' ) ) {
		return $old_value;
	}

	$value = (bool) $value;

	// Ensure rewrite rules are regenerated when this changes.
	if ( $value !== $old_value ) {
		delete_option( 'rewrite_rules' );
	}

	return $value;
}

/**
 * Gets the available core features for post types.
 *
 * @since 1.0.0
 *
 * @return array Array of $slug => $label pairs.
 */
function ct_get_available_post_type_features() {
	return array(
		'title'           => __( 'Title', 'capability-tutorials' ),
		'editor'          => __( 'Editor', 'capability-tutorials' ),
		'comments'        => __( 'Comments', 'capability-tutorials' ),
		'revisions'       => __( 'Revisions', 'capability-tutorials' ),
		'trackbacks'      => __( 'Pingbacks', 'capability-tutorials' ),
		'author'          => __( 'Author', 'capability-tutorials' ),
		'excerpt'         => __( 'Excerpt', 'capability-tutorials' ),
		'page-attributes' => __( 'Page Attributes', 'capability-tutorials' ),
		'thumbnail'       => __( 'Featured Image', 'capability-tutorials' ),
		'custom-fields'   => __( 'Custom Fields', 'capability-tutorials' ),
		'post-formats'    => __( 'Post Formats', 'capability-tutorials' ),
	);
}
