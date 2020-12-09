<?php
/*
 * Plugin name: Grimlock Animate
 * Plugin URI:  http://www.themosaurus.com
 * Description: This plugin enables animations and effects for components such as section components..
 * Author:      Themosaurus
 * Author URI:  http://www.themosaurus.com
 * Version:     1.1.2
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: grimlock-animate
 * Domain Path: /languages
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'GRIMLOCK_ANIMATE_VERSION',         '1.1.2' );
define( 'GRIMLOCK_ANIMATE_PLUGIN_FILE',     __FILE__ );
define( 'GRIMLOCK_ANIMATE_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'GRIMLOCK_ANIMATE_PLUGIN_DIR_URL',  plugin_dir_url( __FILE__ ) );

// Initialize update checker
require 'libs/plugin-update-checker/plugin-update-checker.php';
Puc_v4_Factory::buildUpdateChecker(
	'http://files.themosaurus.com/grimlock-animate/version.json',
	__FILE__,
	'grimlock-animate'
);

/**
 * Load plugin.
 *
 * @since 1.0.0
 */
function grimlock_animate_loaded() {
	require_once 'inc/class-grimlock-animate.php';

	global $grimlock_animate;
	$grimlock_animate = new Grimlock_Animate();

	do_action( 'grimlock_animate_loaded' );
}
add_action( 'grimlock_loaded', 'grimlock_animate_loaded' );

/**
 * Add support for Grimlock Hero.
 *
 * @since 1.0.3
 */
function grimlock_animate_hero_loaded() {
	require_once 'inc/hero/class-grimlock-animate-hero.php';

	global $grimlock_animate_hero;
	$grimlock_animate_hero = new Grimlock_Animate_Hero();

	do_action( 'grimlock_animate_hero_loaded' );
}
add_action( 'grimlock_hero_loaded', 'grimlock_animate_hero_loaded' );

/**
 * Add support for Grimlock Gallery.
 *
 * @since 1.1.0
 */
function grimlock_animate_gallery_loaded() {
	require_once 'inc/gallery/class-grimlock-animate-gallery.php';

	global $grimlock_animate_gallery;
	$grimlock_animate_gallery = new Grimlock_Animate_Gallery();

	do_action( 'grimlock_animate_gallery_loaded' );
}
add_action( 'grimlock_gallery_loaded', 'grimlock_animate_gallery_loaded' );

/**
 * Add support for Grimlock for The Events Calendar.
 *
 * @since 1.1.0
 */
function grimlock_animate_the_events_calendar_loaded() {
	require_once 'inc/the-events-calendar/class-grimlock-animate-the-events-calendar.php';

	global $grimlock_animate_the_events_calendar;
	$grimlock_animate_the_events_calendar = new Grimlock_Animate_The_Events_Calendar();

	do_action( 'grimlock_animate_the_events_calendar_loaded' );
}
add_action( 'grimlock_the_events_calendar_loaded', 'grimlock_animate_the_events_calendar_loaded' );

/**
 * Add support for Grimlock for WooCommerce Subscriptions.
 *
 * @since 1.1.0
 */
function grimlock_animate_woocommerce_subscriptions_loaded() {
	require_once 'inc/woocommerce-subscriptions/class-grimlock-animate-woocommerce-subscriptions.php';

	global $grimlock_animate_woocommerce_subscriptions;
	$grimlock_animate_woocommerce_subscriptions = new Grimlock_Animate_WooCommerce_Subscriptions();

	do_action( 'grimlock_animate_woocommerce_subscriptions_loaded' );
}
add_action( 'grimlock_woocommerce_subscriptions_loaded', 'grimlock_animate_woocommerce_subscriptions_loaded' );

/**
 * Add support for Grimlock for Author Avatars List.
 *
 * @since 1.1.0
 */
function grimlock_animate_author_avatars_loaded() {
	require_once 'inc/author-avatars/class-grimlock-animate-author-avatars.php';

	global $grimlock_animate_author_avatars;
	$grimlock_animate_author_avatars = new Grimlock_Animate_Author_Avatars();

	do_action( 'grimlock_animate_author_avatars_loaded' );
}
add_action( 'grimlock_author_avatars_loaded', 'grimlock_animate_author_avatars_loaded' );

/**
 * Add support for Grimlock for BuddyPress.
 *
 * @since 1.1.0
 */
function grimlock_animate_buddypress_loaded() {
	require_once 'inc/buddypress/class-grimlock-animate-buddypress.php';

	global $grimlock_animate_buddypress;
	$grimlock_animate_buddypress = new Grimlock_Animate_BuddyPress();

	do_action( 'grimlock_animate_buddypress_loaded' );
}
add_action( 'grimlock_buddypress_loaded', 'grimlock_animate_buddypress_loaded' );

/**
 * Add support for Grimlock for Features by WooThemes.
 *
 * @since 1.1.0
 */
function grimlock_animate_features_by_woothemes_loaded() {
	require_once 'inc/features-by-woothemes/class-grimlock-animate-features-by-woothemes.php';

	global $grimlock_animate_features_by_woothemes;
	$grimlock_animate_features_by_woothemes = new Grimlock_Animate_Features_By_WooThemes();

	do_action( 'grimlock_animate_features_by_woothemes_loaded' );
}
add_action( 'grimlock_features_by_woothemes_loaded', 'grimlock_animate_features_by_woothemes_loaded' );

/**
 * Add support for Grimlock for Features by WooThemes.
 *
 * @since 1.1.0
 */
function grimlock_animate_testimonials_by_woothemes_loaded() {
	require_once 'inc/testimonials-by-woothemes/class-grimlock-animate-testimonials-by-woothemes.php';

	global $grimlock_animate_testimonials_by_woothemes;
	$grimlock_animate_testimonials_by_woothemes = new Grimlock_Animate_Testimonials_By_WooThemes();

	do_action( 'grimlock_animate_testimonials_by_woothemes_loaded' );
}
add_action( 'grimlock_testimonials_by_woothemes_loaded', 'grimlock_animate_testimonials_by_woothemes_loaded' );

/**
 * Add support for Grimlock Modal.
 *
 * @since 1.1.0
 */
function grimlock_animate_modal_loaded() {
	require_once 'inc/modal/class-grimlock-animate-modal.php';

	global $grimlock_animate_modal;
	$grimlock_animate_modal = new Grimlock_Animate_Modal();

	do_action( 'grimlock_animate_modal_loaded' );
}
add_action( 'grimlock_modal_loaded', 'grimlock_animate_modal_loaded' );

/**
 * Add support for Grimlock Video.
 *
 * @since 1.1.0
 */
function grimlock_animate_video_loaded() {
	require_once 'inc/video/class-grimlock-animate-video.php';

	global $grimlock_animate_video;
	$grimlock_animate_video = new Grimlock_Animate_Video();

	do_action( 'grimlock_animate_video_loaded' );
}
add_action( 'grimlock_video_loaded', 'grimlock_animate_video_loaded' );
