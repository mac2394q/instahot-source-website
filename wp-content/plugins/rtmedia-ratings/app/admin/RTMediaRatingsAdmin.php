<?php
/**
 * Author: Ritesh <ritesh.patel@rtcamp.com>
 */

class RTMediaRatingsAdmin {

	/**
	 * Default value for General Enable Rating option.
	 *
	 * @var string
	 */
	public $default_general_enable_ratings = '1';

	/**
	 * Default value for General Enable Album Rating option.
	 *
	 * @var string
	 */
	public $default_general_enable_album_ratings = '0';

	/**
	 * Default value for General Enable Likes option.
	 *
	 * @var string
	 */
	public $default_general_enable_likes;

	public function __construct() {
		// settings default content
		add_filter( 'rtmedia_general_content_default_values', array( $this, 'add_admin_option_default_values' ), 10, 1 );

		// add new group in Other settings
		add_filter( 'rtmedia_display_content_groups', array( $this, 'general_content_add_rating_group' ), 10, 1 );

		// add on/off switch for rating and like
		add_filter( 'rtmedia_display_content_add_itmes', array( $this, 'render_ratings_admin_option' ), 10, 2 );

		// Save default values in database.
		$this->save_admin_option_default_values();
	}

	/**
	 * Sets default values for settings
	 * @param type $defaults
	 * @return int
	 */
	function add_admin_option_default_values( $defaults ) {
		/**
		 * Set default value for media likes $defaults['general_enableLikes'], it is being used to add compatibility with rtMedia 4.2.
		 *
		 * Issue id: GL-1.
		 *
		 * Remove the below condition and default value after rtMedia version 4.5.
		 */
		if ( ! defined( 'RTMEDIA_VERSION' ) || ( defined( 'RTMEDIA_VERSION' ) && RTMEDIA_VERSION < 4.3 ) ) {
			$this->default_general_enable_likes = '1';
			$defaults['general_enableLikes']    = '1';
		}

		$defaults['general_enableRatings']      = '1';
		$defaults['general_enableAlbumRatings'] = '0';
		return $defaults;
	}

	/**
	 * Save default values of settings.
	 */
	public function save_admin_option_default_values() {

		if ( function_exists( 'rtmedia_get_site_option' ) ) {
			$rtmedia_options = rtmedia_get_site_option( 'rtmedia-options' );

			if ( function_exists( 'rtmedia_update_site_option' ) ) {

				if ( ! isset( $rtmedia_options['general_enableRatings'] ) ) {
					$rtmedia_options['general_enableRatings'] = $this->default_general_enable_ratings;
				}

				if ( ! isset( $rtmedia_options['general_enableAlbumRatings'] ) ) {
					$rtmedia_options['general_enableAlbumRatings'] = $this->default_general_enable_album_ratings;
				}

				if ( ( ! empty( $this->default_general_enable_likes ) ) &&
					( ! isset( $rtmedia_options['general_enableLikes'] ) ||
						empty( $rtmedia_options['general_enableLikes'] ) ) ) {
					$rtmedia_options['general_enableLikes'] = $this->default_general_enable_likes;
				}

				rtmedia_update_site_option( 'rtmedia-options', $rtmedia_options );
			}
		}

	}

	/**
	 * Adds section to group similar setting options
	 * @param array $general_group	list of sections
	 * @return array $general_group
	 */
	public function general_content_add_rating_group( $general_group ) {
		/**
		 * Add group for media likes, it is being used to add compatibility with rtMedia 4.2.
		 *
		 * Issue id: GL-1.
		 *
		 * Remove the below condition and group title after rtMedia version 4.5.
		 */
		if ( ! defined( 'RTMEDIA_VERSION' ) || ( defined( 'RTMEDIA_VERSION' ) && RTMEDIA_VERSION < 4.3 ) ) {
			$general_group[11] = __( 'MEDIA LIKES', 'rtmedia' );
		}
		$general_group[12] = __( 'rating for media', 'rtmedia' );

		return $general_group;
	}

	/**
	 * Configure ratings admin option
	 * @param type $render_options
	 * @param type $options
	 * @return array
	 */
	function render_ratings_admin_option( $render_options, $options ) {

		// remove the below array after rtMedia 4.5 released.
		if ( ! defined( 'RTMEDIA_VERSION' ) || ( defined( 'RTMEDIA_VERSION' ) && RTMEDIA_VERSION < 4.3 ) ) {
			/* add on/off switch for media like */
			$render_options['general_enableLikes'] = array(
				'title'    => __( 'Enable likes for media', 'rtmedia' ),
				'callback' => array( 'RTMediaFormHandler', 'checkbox' ),
				'args' => array(
					'key' => 'general_enableLikes',
					'value' => $options['general_enableLikes'],
					'desc' => __( 'You may want to disable like feature if you had enabled rating feature.', 'rtmedia' ),
				),
				'group' => '11',
			);
		}

		/* add on/off switch for media rating */
		$render_options['general_enableRatings'] = array(
			'title'    => __( 'Enable 5 star rating for media', 'rtmedia' ),
			'callback' => array( 'RTMediaFormHandler', 'checkbox' ),
			'args' => array(
				'key' => 'general_enableRatings',
				'value' => $options['general_enableRatings'],
				'desc' => __( 'Allow user to rate media.', 'rtmedia' ),
			),
			'group' => '12',
		);
		$render_options['general_enableAlbumRatings'] = array(
			'title'    => __( 'Enable 5 star rating for albums', 'rtmedia' ),
			'callback' => array( 'RTMediaFormHandler', 'checkbox' ),
			'args' => array(
				'key' => 'general_enableAlbumRatings',
				'value' => $options['general_enableAlbumRatings'],
				'desc' => __( 'Allow user to rate albums.', 'rtmedia' ),
			),
			'group' => '12',
		);

		return $render_options;
	}
}
