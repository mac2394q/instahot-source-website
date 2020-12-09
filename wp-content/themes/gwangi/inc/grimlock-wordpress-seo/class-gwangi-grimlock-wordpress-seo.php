<?php
/**
 * Gwangi_Grimlock_WordPress_SEO Class
 *
 * @package  gwangi
 * @author   Themosaurus
 * @since    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * The Gwangi Grimlock WordPress SEO integration class
 */
class Gwangi_Grimlock_WordPress_SEO {
	/**
	 * Setup class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		require_once get_template_directory() . '/inc/grimlock-wordpress-seo/customizer/class-gwangi-grimlock-wordpress-seo-breadcrumb-customizer.php';
	}
}

return new Gwangi_Grimlock_WordPress_SEO();
