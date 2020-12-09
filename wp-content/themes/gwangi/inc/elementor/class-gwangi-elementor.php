<?php
/**
 * Gwangi Grimlock Class
 *
 * @package  gwangi
 * @author   Themosaurus
 * @since    1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Gwangi_Elementor' ) ) :
	/**
	 * The Gwangi Grimlock integration class
	 */
	class Gwangi_Elementor {
		/**
		 * Setup class.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			add_filter( 'gwangi_scroll_to_content_button_displayed', array( $this, 'scroll_to_content_button_displayed' ), 10,  1 );
		}

		/**
		 * Add conditions for the scroll to content display.
		 *
		 * @since 1.0.0
		 *
		 * @param  bool $default True when the scroll to content button has to be displayed, false otherwise.
		 *
		 * @return bool          True when the scroll to content button has to be displayed, false otherwise.
		 */
		public function scroll_to_content_button_displayed( $default ) {
			return $default || ( is_page_template( 'elementor_header_footer' ) && is_front_page() );
		}
	}
endif;

return new Gwangi_Elementor();
