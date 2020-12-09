<?php
/**
 * Gwangi_Grimlock_BuddyPress Class
 *
 * @package  gwangi
 * @author   Themosaurus
 * @since    1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The Gwangi Grimlock BuddyPress integration class.
 */
class Gwangi_Grimlock_BuddyPress {
	/**
	 * Setup class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		global $gwangi_grimlock_buddypress_customizer;
		$gwangi_grimlock_buddypress_customizer = require_once get_template_directory() . '/inc/grimlock-buddypress/customizer/class-gwangi-grimlock-buddypress-customizer.php';
	}
}

return new Gwangi_Grimlock_BuddyPress();
