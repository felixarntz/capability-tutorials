<?php
/**
 * Plugin initialization file
 *
 * @package Capability_Tutorials
 * @since 1.0.0
 *
 * @wordpress-plugin
 * Plugin Name: Capability Tutorials
 * Plugin URI:  https://github.com/felixarntz/capability-tutorials
 * Description: Demo plugin for how to use capabilities in WordPress, as part of the session "Capability-Driven Development".
 * Version:     1.0.0
 * Author:      Felix Arntz
 * Author URI:  https://leaves-and-love.net
 * License:     GNU General Public License v2 (or later)
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: capability-tutorials
 * Tags:        capability, tutorials, demo
 */

defined( 'ABSPATH' ) || exit;

define( 'CT_VERSION', '1.0.0' );
define( 'CT_POST_TYPE_SINGULAR', 'ct_tutorial' );
define( 'CT_POST_TYPE_PLURAL', 'ct_tutorials' );
define( 'CT_OPTION_GROUP', 'ct_settings' );

/**
 * Loads the plugin files.
 *
 * @since 1.0.0
 */
function ct_load_files() {
	$path = plugin_dir_path( __FILE__ );

	require_once $path . 'inc/post-type.php';
	require_once $path . 'inc/capabilities.php';
	require_once $path . 'inc/settings.php';
	require_once $path . 'inc/settings-page.php';
}

/**
 * Registers the post type and flushes rewrite rules. Used as activation hook.
 *
 * @since 1.0.0
 * @access private
 */
function ct_activate() {
	ct_register_post_type();

	flush_rewrite_rules();
}

ct_load_files();
register_activation_hook( __FILE__, 'ct_activate' );
