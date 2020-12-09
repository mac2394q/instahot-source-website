<?php
/**
 * Gwangi BP Profile Search Class
 *
 * @package  gwangi
 * @author   Themosaurus
 * @since    1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Gwangi_BP_Profile_Search' ) ) :
	/**
	 * The Gwangi BP Profile Search integration class
	 */
	class Gwangi_BP_Profile_Search {
		/**
		 * Setup class.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			if ( class_exists( 'Grimlock_Hero' ) ) {
				require_once get_template_directory() . '/inc/bp-profile-search/customizer/class-gwangi-bp-profile-search-grimlock-hero-customizer.php';
			}
		}
	}
endif;

return new Gwangi_BP_Profile_Search();
