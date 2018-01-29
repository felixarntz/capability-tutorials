<?php
/**
 * Capability handling
 *
 * @package Capability_Tutorials
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Grants primitive plugin capabilities based on primitive core capabilities.
 *
 * To customize handling of the plugin capabilities, unhook this function and either
 * add them as primitive capabilities to a role or provide your own hook function.
 *
 * @since 1.0.0
 *
 * @param array $allcaps Array of all the user's capabilities.
 * @return array Array of all the user's capabilities, including the plugin capabilities.
 */
function ct_maybe_grant_capabilities( $allcaps ) {
	$post_type_capabilities = array(
		'edit_' . CT_POST_TYPE_PLURAL,
		'edit_others_' . CT_POST_TYPE_PLURAL,
		'publish_' . CT_POST_TYPE_PLURAL,
		'read_private_' . CT_POST_TYPE_PLURAL,
		'create_' . CT_POST_TYPE_PLURAL,
		'edit_private_' . CT_POST_TYPE_PLURAL,
		'edit_published_' . CT_POST_TYPE_PLURAL,
		'delete_' . CT_POST_TYPE_PLURAL,
		'delete_others_' . CT_POST_TYPE_PLURAL,
		'delete_private_' . CT_POST_TYPE_PLURAL,
		'delete_published_' . CT_POST_TYPE_PLURAL,
		'read_' . CT_POST_TYPE_PLURAL,
	);

	// Allow all the above capabilities depending on the user having the equivalent 'post' capabilities.
	foreach ( $post_type_capabilities as $post_type_capability ) {
		if ( 'read_' . CT_POST_TYPE_PLURAL === $post_type_capability ) {
			// Core capability is not called 'read_posts', but simply 'read'.
			$core_capability = 'read';
		} elseif ( 'create_' . CT_POST_TYPE_PLURAL === $post_type_capability ) {
			// Core capability is not called 'create_posts', but 'edit_posts'.
			$core_capability = 'edit_posts';
		} else {
			// Simply replace our plural slug with 'posts' to get the respective core capability.
			$core_capability = str_replace( CT_POST_TYPE_PLURAL, 'posts', $post_type_capability );
		}

		if ( isset( $allcaps[ $core_capability ] ) ) {
			$allcaps[ $post_type_capability ] = $allcaps[ $core_capability ];
		}
	}

	// Allow managing plugin options depending on the user having the 'manage_options' capability.
	if ( isset( $allcaps['manage_options'] ) ) {
		$allcaps['manage_ct_options'] = $allcaps['manage_options'];
	}

	return $allcaps;
}
add_filter( 'user_has_cap', 'ct_maybe_grant_capabilities' );

/**
 * Maps meta capabilities to their primitive capabilities.
 *
 * @since 1.0.0
 *
 * @param array  $caps    Actual primitive capabilities required, as processed by core. Contains only the
 *                        value of $cap if not processed further.
 * @param string $cap     Meta capability that should be resolved to primitive capabilities.
 * @param int    $user_id User ID for which the capability is checked.
 * @param array  $args    Additional arguments passed when checking the capability. Typically an object ID.
 * @return array Actual primitive capabilities required.
 */
function ct_map_meta_capabilities( $caps, $cap, $user_id, $args ) {
	switch ( $cap ) {
		// The following post type meta capabilities only need to be handled if you set 'map_meta_cap' to
		// false during post type registration, or if you want to check additional things.
		case 'edit_' . CT_POST_TYPE_SINGULAR:
		case 'delete_' . CT_POST_TYPE_SINGULAR:
		case 'read_' . CT_POST_TYPE_SINGULAR:
			break;
		// Maps the meta capability for a single option to the primitive capability for options.
		case 'manage_ct_option':
			$caps = array( 'manage_ct_options' );
			break;
	}

	return $caps;
}
add_filter( 'map_meta_cap', 'ct_map_meta_capabilities', 10, 4 );
