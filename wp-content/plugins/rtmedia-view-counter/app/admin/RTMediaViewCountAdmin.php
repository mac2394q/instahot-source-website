<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RTMediaViewCountAdmin
 *
 * @author sanket
 */
class RTMediaViewCountAdmin {

	public function __construct() {
		// default setting content
		add_filter( 'rtmedia_general_content_default_values', array( $this, 'rtmedia_general_content_add_default_value' ), 10, 1 );
		// add options to display tab
		add_filter( 'rtmedia_display_content_add_itmes', array( $this, 'rtmedia_general_content_single_view_options' ), 10, 2 );
	}

	/**
	 * Sets default values for settings
	 * @param type $defaults
	 * @return array $defaults
	 */
	public function rtmedia_general_content_add_default_value( $defaults ) {
		$defaults['general_viewcount'] = 1;

		return $defaults;
	}

	/**
	 * Configure view counts admin option
	 * @param type $render_options
	 * @param type $options
	 * @return array
	 */
	public function rtmedia_general_content_single_view_options( $render_options, $options ) {
		$render_options['general_viewcount'] = array(
			'title' => __( 'Enable view count', 'rtmedia' ),
			'callback' => array( 'RTMediaFormHandler', 'checkbox' ),
			'args' => array(
				'key' => 'general_viewcount',
				'value' => $options['general_viewcount'],
				'desc' => __( 'You may want to show total views of the media to users.', 'rtmedia' ),
			),
			'group' => '10',
		);

		return $render_options;
	}

}
