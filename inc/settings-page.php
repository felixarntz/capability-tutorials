<?php
/**
 * Settings page functionality
 *
 * @package Capability_Tutorials
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Adds the tutorial settings page.
 *
 * @since 1.0.0
 */
function ct_add_settings_page() {
	$hook_suffix = add_submenu_page( 'edit.php?post_type=' . CT_POST_TYPE_SINGULAR, __( 'Tutorial Settings', 'capability-tutorials' ), __( 'Settings', 'capability-tutorials' ), 'manage_ct_options', 'ct_settings_page', 'ct_render_settings_page' );

	add_action( "load-{$hook_suffix}", 'ct_initialize_settings_page' );
}
add_action( 'admin_menu', 'ct_add_settings_page' );

/**
 * Initializes the tutorial settings page by adding settings fields.
 *
 * @since 1.0.0
 */
function ct_initialize_settings_page() {
	if ( current_user_can( 'manage_ct_option', 'ct_rewrite_slug' ) ) {
		add_settings_field( 'rewrite_slug', __( 'Rewrite Slug', 'capability-tutorials' ), 'ct_render_settings_page_rewrite_slug_field', CT_OPTION_GROUP, 'default', array(
			'label_for' => 'ct-rewrite-slug',
		) );
	}
	if ( current_user_can( 'manage_ct_option', 'ct_supports' ) ) {
		add_settings_field( 'supports', __( 'Supported Features', 'capability-tutorials' ), 'ct_render_settings_page_supports_field', CT_OPTION_GROUP, 'default' );
	}
	if ( current_user_can( 'manage_ct_option', 'ct_is_hierarchical' ) ) {
		add_settings_field( 'is_hierarchical', __( 'Hierarchical', 'capability-tutorials' ), 'ct_render_settings_page_is_hierarchical_field', CT_OPTION_GROUP, 'default' );
	}
	if ( current_user_can( 'manage_ct_option', 'ct_has_archive' ) ) {
		add_settings_field( 'has_archive', __( 'Archive', 'capability-tutorials' ), 'ct_render_settings_page_has_archive_field', CT_OPTION_GROUP, 'default' );
	}
}

/**
 * Renders the tutorial settings page.
 *
 * @since 1.0.0
 */
function ct_render_settings_page() {
	// Display settings errors.
	require ABSPATH . 'wp-admin/options-head.php';

	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Tutorial Settings', 'capability-tutorials' ); ?></h1>

		<form action="options.php" method="POST">
			<?php settings_fields( CT_OPTION_GROUP ); ?>

			<?php do_settings_sections( CT_OPTION_GROUP ); ?>

			<table class="form-table">
				<?php do_settings_fields( CT_OPTION_GROUP, 'default' ); ?>
			</table>

			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

/**
 * Renders content for the 'rewrite_slug' field.
 *
 * @since 1.0.0
 */
function ct_render_settings_page_rewrite_slug_field() {
	$value = ct_get_rewrite_slug();

	?>
	<input type="text" id="ct-rewrite-slug" name="ct_rewrite_slug" class="regular-text" value="<?php echo esc_attr( $value ); ?>" />
	<p class="description"><?php esc_html_e( 'Enter the URL slug to prefix tutorial slugs with.', 'capability-tutorials' ); ?></p>
	<?php
}

/**
 * Renders content for the 'supports' field.
 *
 * @since 1.0.0
 */
function ct_render_settings_page_supports_field() {
	$value    = ct_get_supports();
	$features = ct_get_available_post_type_features();

	?>
	<?php foreach ( $features as $slug => $label ) : ?>
		<div>
			<input type="checkbox" id="<?php echo esc_attr( 'ct-supports-' . $slug ); ?>" name="ct_supports[]" value="<?php echo esc_attr( $slug ); ?>"<?php echo in_array( $slug, $value, true ) ? ' checked="checked"' : ''; ?> />
			<label for="<?php echo esc_attr( 'ct-supports-' . $slug ); ?>"><?php echo esc_html( $label ); ?></label>
		</div>
	<?php endforeach; ?>
	<p class="description"><?php esc_html_e( 'Check the features tutorials should support.', 'capability-tutorials' ); ?></p>
	<?php
}

/**
 * Renders content for the 'is_hierarchical' field.
 *
 * @since 1.0.0
 */
function ct_render_settings_page_is_hierarchical_field() {
	$value = ct_is_hierarchical();

	?>
	<input type="checkbox" id="ct-is-hierarchical" name="ct_is_hierarchical" value="1"<?php checked( $value ); ?> />
	<label for="ct-is-hierarchical"><?php esc_html_e( 'Make hierarchical?', 'capability-tutorials' ); ?></label>
	<p class="description"><?php esc_html_e( 'Check this box to allow tutorials to use a hierarchical structure.', 'capability-tutorials' ); ?></p>
	<?php
}

/**
 * Renders content for the 'has_archive' field.
 *
 * @since 1.0.0
 */
function ct_render_settings_page_has_archive_field() {
	$value = ct_has_archive();

	?>
	<input type="checkbox" id="ct-has-archive" name="ct_has_archive" value="1"<?php checked( $value ); ?> />
	<label for="ct-has-archive"><?php esc_html_e( 'Enable archives?', 'capability-tutorials' ); ?></label>
	<p class="description"><?php esc_html_e( 'Check this box to enable tutorial archive pages.', 'capability-tutorials' ); ?></p>
	<?php
}
