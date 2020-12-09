<?php
/**
 * Gwangi_Grimlock_bbPress_Archive_Forum_Customizer Class
 *
 * @author   Themosaurus
 * @since    1.0.0
 * @package grimlock
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The forum archive page class for the Customizer.
 */
class Gwangi_Grimlock_bbPress_Archive_Forum_Customizer {
	/**
	 * Setup class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_filter( 'grimlock_bbpress_archive_forum_customizer_defaults', array( $this, 'add_defaults' ), 10, 1 );
	}

	/**
	 * Add default values and control settings for the Customizer.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $defaults The array of default values for the Customizer controls.
	 *
	 * @return array           The updated array of default values for the Customizer controls.
	 */
	public function add_defaults( $defaults ) {
		$defaults['archive_forum_title']                          = GWANGI_ARCHIVE_FORUM_TITLE;
		$defaults['archive_forum_description']                    = GWANGI_ARCHIVE_FORUM_DESCRIPTION;
		$defaults['archive_forum_layout']                         = GWANGI_ARCHIVE_FORUM_LAYOUT;
		$defaults['archive_forum_custom_header_background_image'] = GWANGI_ARCHIVE_FORUM_CUSTOM_HEADER_BACKGROUND_IMAGE;
		$defaults['archive_forum_custom_header_padding_y']        = GWANGI_ARCHIVE_FORUM_CUSTOM_HEADER_PADDING_Y;
		$defaults['archive_forum_custom_header_layout']           = GWANGI_ARCHIVE_FORUM_CUSTOM_HEADER_LAYOUT;
		$defaults['archive_forum_custom_header_container_layout'] = GWANGI_ARCHIVE_FORUM_CUSTOM_HEADER_CONTAINER_LAYOUT;
		$defaults['archive_forum_content_padding_y']              = GWANGI_ARCHIVE_FORUM_CONTENT_PADDING_Y;
		$defaults['archive_forum_custom_header_displayed']        = GWANGI_ARCHIVE_FORUM_CUSTOM_HEADER_DISPLAYED;
		return $defaults;
	}
}

return new Gwangi_Grimlock_bbPress_Archive_Forum_Customizer();
