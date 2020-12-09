<?php
/**
 * Gwangi_SportsPress Class
 *
 * @author   Themosaurus
 * @since    1.0.0
 * @package  gwangi
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Gwangi_SportsPress
 *
 * @author  themosaurus
 * @since   1.0.0
 * @package gwangi/inc/sportspress
 */
class Gwangi_SportsPress {
	/**
	 * Setup class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'setup' ), 10 );
	}

	/**
	 * SportsPress setup function.
	 *
	 * @since 1.0.0
	 */
	public function setup() {
		// Add theme support for SportsPress.
		add_theme_support( 'sportspress' );
	}
}

return new Gwangi_SportsPress();
