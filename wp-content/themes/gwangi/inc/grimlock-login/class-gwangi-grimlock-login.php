<?php
/**
 * Gwangi_Grimlock_Login Class
 *
 * @package  gwangi
 * @author   Themosaurus
 * @since    1.1.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The Gwangi Grimlock Login integration class.
 */
class Gwangi_Grimlock_Login {
	/**
	 * Setup class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_filter( 'grimlock_login_custom_logo_url', array( $this, 'add_custom_logo_url'             ),    10, 1 );
	}

	/**
	 * Add a custom logo URL when empty.
	 *
	 * @since 1.1.9
	 *
	 * @param  string $url The URL for the custom logo.
	 *
	 * @return string      The updated URL for the custom logo.
	 */
	public function add_custom_logo_url( $url ) {
		return empty( $url ) ? get_stylesheet_directory_uri() . '/assets/images/logo.png' : $url;
	}
}

return new Gwangi_Grimlock_Login();
