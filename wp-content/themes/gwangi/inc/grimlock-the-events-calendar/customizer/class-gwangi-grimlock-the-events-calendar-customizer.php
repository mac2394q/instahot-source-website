<?php
/**
 * Gwangi_Grimlock_The_Events_Calendar_Customizer Class
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
 * The Customizer class for The Events Calendar.
 */
class Gwangi_Grimlock_The_Events_Calendar_Customizer {
	/**
	 * Setup class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_filter( 'grimlock_the_events_calendar_customizer_defaults', array( $this, 'change_defaults'             ), 10, 1 );
		add_filter( 'grimlock_custom_header_displayed',                 array( $this, 'has_custom_header_displayed' ), 10, 1 );
	}

	/**
	 * Change the default values and control settings for the Customizer.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $defaults The array of default values for the Customizer controls.
	 *
	 * @return array          The updated array of default values for the Customizer controls.
	 */
	public function change_defaults( $defaults ) {
		$defaults['the_events_calendar_title']                          = GWANGI_THE_EVENTS_CALENDAR_TITLE;
		$defaults['the_events_calendar_description']                    = GWANGI_THE_EVENTS_CALENDAR_DESCRIPTION;
		$defaults['the_events_calendar_custom_header_displayed']        = GWANGI_THE_EVENTS_CALENDAR_CUSTOM_HEADER_DISPLAYED;
		$defaults['the_events_calendar_custom_header_layout']           = GWANGI_THE_EVENTS_CALENDAR_CUSTOM_HEADER_LAYOUT;
		$defaults['the_events_calendar_custom_header_container_layout'] = GWANGI_THE_EVENTS_CALENDAR_CUSTOM_HEADER_CONTAINER_LAYOUT;
		$defaults['the_events_calendar_custom_header_background_image'] = GWANGI_THE_EVENTS_CALENDAR_CUSTOM_HEADER_BACKGROUND_IMAGE;
		$defaults['the_events_calendar_custom_header_padding_y']        = GWANGI_THE_EVENTS_CALENDAR_CUSTOM_HEADER_PADDING_Y;
		$defaults['the_events_calendar_content_padding_y']              = GWANGI_THE_EVENTS_CALENDAR_CONTENT_PADDING_Y;
		return $defaults;
	}

	/**
	 * Check if the custom header is displayed or not.
	 *
	 * @since 1.1.9
	 *
	 * @param  bool $default True if the custom header would be displayed, false otherwise.
	 *
	 * @return bool True if the custom header is displayed, false otherwise.
	 */
	public function has_custom_header_displayed( $default ) {
		return ! $this->is_template() && $default;
	}

	/**
	 * Check if the current template is the expected template.
	 *
	 * @since 1.1.9
	 *
	 * @return bool True when the template is the expected template, false otherwise.
	 */
	protected function is_template() {
		return is_singular( 'tribe_organizer' ) || is_singular( 'tribe_venue' ) || is_singular( 'tribe_events' );
	}
}

return new Gwangi_Grimlock_The_Events_Calendar_Customizer();
