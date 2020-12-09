<?php
/**
 * Gwangi_Grimlock_LearnDash Class
 *
 * @package gwangi
 * @author  Themosaurus
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The Gwangi Grimlock for LearnDash integration class.
 */
class Gwangi_Grimlock_LearnDash {
	/**
	 * Setup class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		require_once get_template_directory() . '/inc/grimlock-learndash/customizer/class-gwangi-grimlock-learndash-customizer.php';
	}
}

return new Gwangi_Grimlock_LearnDash();
