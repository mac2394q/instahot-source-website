<?php
/**
 * Gwangi Jetpack Class
 *
 * @link https://jetpack.com/
 *
 * @package  gwangi
 * @author   Themosaurus
 * @since    1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Gwangi_Jetpack' ) ) :
	/**
	 * The Gwangi Jetpack integration class
	 */
	class Gwangi_Jetpack {
		/**
		 * Setup class.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			add_action( 'after_setup_theme',                 array( $this, 'setup'                          ), 10    );
			add_filter( 'infinite_scroll_archive_supported', array( $this, 'jp_infinite_scroll_support'     ), 10    );
			add_filter( 'comments_open',                     array( $this, 'jp_remove_attachment_comments'  ), 10, 2 );

		}

		/**
		 * Jetpack setup function.
		 *
		 * @since 1.0.0
		 *
		 * @link https://jetpack.me/support/infinite-scroll/
		 * @link https://jetpack.me/support/responsive-videos/
		 */
		public function setup() {

			// Add theme support for Infinite Scroll.
			add_theme_support( 'infinite-scroll', array(
				'container' => 'main',
				'render'    => array( $this, 'infinite_scroll_render' ),
				'footer'    => 'page',
			) );

			// Add theme support for Responsive Videos.
			add_theme_support( 'jetpack-responsive-videos' );
		}

		/**
		 * Init Jetpack infinite scroll only in some cases.
		 *
		 * @since 2.0.0
		 *
		 * @link https://jetpack.me/support/infinite-scroll/
		 */
		public function jp_infinite_scroll_support() {
			$supported = current_theme_supports('infinite-scroll') && ( is_home() || is_category() || is_tag() || is_author() );
			return $supported;
		}


		/**
		 * Custom render function for Infinite Scroll.
		 *
		 * @since 1.0.0
		 */
		public function infinite_scroll_render() {
			while ( have_posts() ) {
				the_post();
				get_template_part( 'template-parts/content', get_post_format() );
			}
		}

		/**
		 * Remove Comment box on Jetpack Carousel.
		 *
		 * @since 2.0.0
		 */
		function jp_remove_attachment_comments( $open, $post_id ) {
			$post = get_post( $post_id );
			if ( 'attachment' == $post->post_type ) {
				return false;
			}
			return $open;
		}

	}
endif;

return new Gwangi_Jetpack();
