<?php
/**
 * Gwangi_Grimlock_RevSlider Class
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
 * The Gwangi Grimlock RevSlider integration class.
 */
class Gwangi_Grimlock_RevSlider {
	/**
	 * Setup class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		require_once get_template_directory() . '/inc/grimlock-revslider/customizer/class-gwangi-grimlock-revslider-global-customizer.php';
	}
}

return new Gwangi_Grimlock_RevSlider();
