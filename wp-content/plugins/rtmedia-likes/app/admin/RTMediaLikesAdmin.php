<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RTMediaLikesAdmin
 *
 * @author sanket
 */
class RTMediaLikesAdmin {

	public function __construct() {
		// settings default content
		add_filter( 'rtmedia_general_content_default_values', array( $this, 'rtmedia_general_content_add_default_value' ), 10, 1 );

		// remove this  filter and function after rtMedia 4.5 released.
		if ( ! defined( 'RTMEDIA_VERSION' ) || ( defined( 'RTMEDIA_VERSION' ) && RTMEDIA_VERSION < 4.3 ) ) {
			// add new group in Other settings
			add_filter( 'rtmedia_display_content_groups', array( $this, 'general_content_add_likes_group' ), 10, 1 );
		}

		// add on/off switch for user likes page
		add_filter( 'rtmedia_display_content_add_itmes', array( $this, 'rtmedia_general_content_add_user_likes_option' ), 10, 2 );

		// add on/off switch for user likes commnet
		add_filter( 'rtmedia_display_content_add_itmes', array( $this, 'rtmedia_general_content_add_user_likes_option_for_comment' ), 10, 2 );
	}

	/**
	 * Sets default values for settings
	 * @param array $defaults
	 * @return array $defaults
	 */
	public function rtmedia_general_content_add_default_value( $defaults ) {
		$defaults['general_enable_user_likes'] = 0;
		$defaults['general_enable_user_likes_comment'] = 1;

		return $defaults;
	}

	/**
	* Adds section to group similar setting options
	* @param array $general_group    list of sections
	* @return array $general_group
	*/
	public function general_content_add_likes_group( $general_group ) {
		$general_group[11] = __( 'MEDIA LIKES', 'rtmedia' );

		return $general_group;
	}

	/**
	 * Configuring User like page option
	 * @param array $render_options	List of option to configure
	 * @param array $options		List of settings value
	 * @return array $render_options
	 */
	public function rtmedia_general_content_add_user_likes_option( $render_options, $options ) {
		$render_options['general_enable_user_likes'] = array(
			'title' => __( 'Likes page in Media tab ', 'rtmedia' ),
			'callback' => array( 'RTMediaFormHandler', 'checkbox' ),
			'args' => array(
				'key' => 'general_enable_user_likes',
				'value' => $options['general_enable_user_likes'],
				'desc' => __( 'Add a separate "Likes" page under the "Media" tab in every user\'s profile that will display any media that the user has liked.', 'rtmedia' ),
			),
			'group' => 11,
		);

		return $render_options;
	}

	/**
	 * Configuring User like page option
	 * @param array $render_options	List of option to configure
	 * @param array $options		List of settings value
	 * @return array $render_options
	 */
	public function rtmedia_general_content_add_user_likes_option_for_comment( $render_options, $options ) {
		$render_options['general_enable_user_likes_comment'] = array(
			'title' => __( 'Likes in media comments', 'rtmedia' ),
			'callback' => array( 'RTMediaFormHandler', 'checkbox' ),
			'args' => array(
				'key' => 'general_enable_user_likes_comment',
				'value' => $options['general_enable_user_likes_comment'],
				'desc' => __( 'Add a "Like" button under every media comment in lightbox & single page.', 'rtmedia' ),
			),
			'group' => 11,
		);

		return $render_options;
	}
}
