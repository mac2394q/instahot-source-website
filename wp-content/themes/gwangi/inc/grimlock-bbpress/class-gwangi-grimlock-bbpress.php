<?php
/**
 * Gwangi_Grimlock_bbPress Class
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
 * The Gwangi Grimlock bbPress integration class
 */
class Gwangi_Grimlock_bbPress {
	/**
	 * Setup class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		require_once get_template_directory() . '/inc/grimlock-bbpress/customizer/class-gwangi-grimlock-bbpress-archive-forum-customizer.php';
	}
}

return new Gwangi_Grimlock_bbPress();
