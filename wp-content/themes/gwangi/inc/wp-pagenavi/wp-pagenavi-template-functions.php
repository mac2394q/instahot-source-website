<?php
/**
 * Gwangi template functions for WP PageNavi.
 *
 * @package gwangi
 */

if ( ! function_exists( 'gwangi_wp_pagenavi' ) ) :
	/**
	 * Display posts navigation using native feature or WP PageNavi plugin.
	 *
	 * @since 1.0.0
	 */
	function gwangi_wp_pagenavi() {
		if ( function_exists( 'wp_pagenavi' ) ) {
			wp_pagenavi();
		}
	}
endif;
