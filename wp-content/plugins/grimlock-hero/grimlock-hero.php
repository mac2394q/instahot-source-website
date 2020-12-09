<?php
/*
 * Plugin name: Grimlock Hero
 * Plugin URI:  http://www.themosaurus.com
 * Description: Provides a new Grimlock Hero component extending features from Grimlock Section component to replace Custom Header in Front Page.
 * Author:      Themosaurus
 * Author URI:  http://www.themosaurus.com
 * Version:     1.0.8
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: grimlock-hero
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

define( 'GRIMLOCK_HERO_VERSION',         '1.0.8' );
define( 'GRIMLOCK_HERO_PLUGIN_FILE',     __FILE__ );
define( 'GRIMLOCK_HERO_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'GRIMLOCK_HERO_PLUGIN_DIR_URL',  plugin_dir_url( __FILE__ ) );

// Initialize update checker
require 'libs/plugin-update-checker/plugin-update-checker.php';
Puc_v4_Factory::buildUpdateChecker(
	'http://files.themosaurus.com/grimlock-hero/version.json',
	__FILE__,
	'grimlock-hero'
);

/**
 * Load plugin.
 */
function grimlock_hero_loaded() {
	require_once 'inc/class-grimlock-hero.php';

	global $grimlock_hero;
	$grimlock_hero = new Grimlock_Hero();

	do_action( 'grimlock_hero_loaded' );
}
add_action( 'grimlock_loaded', 'grimlock_hero_loaded' );
