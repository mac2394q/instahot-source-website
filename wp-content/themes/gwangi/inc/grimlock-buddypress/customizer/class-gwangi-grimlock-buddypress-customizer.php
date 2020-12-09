<?php
/**
 * Gwangi_Grimlock_BuddyPress_Customizer Class
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
 * The Gwangi Customizer class for BuddyPress.
 */
class Gwangi_Grimlock_BuddyPress_Customizer {
	/**
	 * Setup class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_filter( 'grimlock_buddypress_customizer_defaults', array( $this, 'change_defaults' ), 10, 1 );
	}

	/**
	 * Change default values and control settings for the Customizer.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $defaults The array of default values for the Customizer controls.
	 *
	 * @return array           The updated array of default values for the Customizer controls.
	 */
	public function change_defaults( $defaults ) {
		$defaults['default_profile_cover_image'] = get_stylesheet_directory_uri() . '/assets/images/covers/member-cover.jpg';
		$defaults['default_group_cover_image']   = get_stylesheet_directory_uri() . '/assets/images/covers/group-cover.jpg';

		$defaults['friend_icons']                                  = 'heart';
		$defaults['member_actions_button_background_color']        = GWANGI_BUTTON_ACTION_BACKGROUND_COLOR;
		$defaults['friend_button_background_color']                = GWANGI_BUTTON_ACTION_LOVE_COLOR;
		$defaults['message_button_background_color']               = GWANGI_BUTTON_ACTION_MESSAGE_COLOR;
		$defaults['success_button_background_color']               = GWANGI_BUTTON_ACTION_SUCCESS_COLOR;
		$defaults['delete_button_background_color']                = GWANGI_BUTTON_ACTION_DANGER_COLOR;
		$defaults['miscellaneous_actions_button_background_color'] = GWANGI_BUTTON_ACTION_MISC_COLOR;

		$defaults['profile_header_background_color'] = GWANGI_BRAND_PRIMARY;

		return $defaults;
	}
}

return new Gwangi_Grimlock_BuddyPress_Customizer();
