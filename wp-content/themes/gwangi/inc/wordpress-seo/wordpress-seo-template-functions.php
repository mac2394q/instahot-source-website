<?php
/**
 * Gwangi template functions for Yoast SEO.
 *
 * @package gwangi
 */

if ( ! function_exists( 'gwangi_yoast_breadcrumb' ) ) :
	/**
	 * Display the WordPress SEO breadcrumb before the content.
	 *
	 * @since 1.0.0
	 */
	function gwangi_yoast_breadcrumb() {
		yoast_breadcrumb( '<div class="breadcrumb yoast-breadcrumb">', '</div>' );
	}
endif;
