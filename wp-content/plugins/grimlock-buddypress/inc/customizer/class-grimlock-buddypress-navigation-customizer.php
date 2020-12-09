<?php
/**
 * Grimlock_BuddyPress_Navigation_Customizer Class
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
 * The navigation class for the Customizer.
 */
class Grimlock_BuddyPress_Navigation_Customizer {
	/**
	 * Setup class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_filter( 'grimlock_navigation_customizer_stick_to_top_background_color_elements', array( $this, 'add_stick_to_top_background_color_elements'    ), 10, 1 );
		add_filter( 'grimlock_navigation_customizer_sub_menu_item_background_color_outputs', array( $this, 'add_sub_menu_item_background_color_outputs'    ), 10, 1 );
		add_filter( 'grimlock_navigation_customizer_sub_menu_item_color_outputs',            array( $this, 'add_sub_menu_item_color_outputs'               ), 10, 1 );
	}

	/**
	 * Add CSS selectors to the array of CSS selectors for the sticky navigation background color.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $elements The array of CSS selectors for the sticky navigation background color.
	 *
	 * @return array           The updated array of CSS selectors for the sticky navigation background color.
	 */
	public function add_stick_to_top_background_color_elements( $elements ) {
		return array_merge( $elements, array(
			'body:not(.grimlock--custom_header-displayed):not(.bp-user):not(.group-home):not(.group-admin) .grimlock-navigation:not(.vertical-navbar)',
			'body.bp-user[class*="yz-"][class*="-scheme"]:not(.grimlock--custom_header-displayed):not(.group-home):not(.group-admin) .grimlock-navigation:not(.vertical-navbar)',
			'body.activity.bp-user.activity-permalink .grimlock-navigation:not(.vertical-navbar)',
			'body.single-item.groups[class*="yz-"][class*="-scheme"]:not(.grimlock--custom_header-displayed) .grimlock-navigation:not(.vertical-navbar)',
		) );
	}

	/**
	 * Add selectors and properties to the CSS rule-set for the sub-menu item background color.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $outputs The array of CSS selectors and properties for the sub-menu item background color.
	 *
	 * @return array          The updated array of CSS selectors for the sub-menu item background color.
	 */
	public function add_sub_menu_item_background_color_outputs( $outputs ) {
		return array_merge( $outputs, array(
			array(
				'element'  => implode( ',', array(
					'.bb-global-search-ac.ui-autocomplete',
				) ),
				'property' => 'background-color',
				'suffix'   => '!important',
			),
		) );
	}

	/**
	 * Add selectors and properties to the CSS rule-set for the sub-menu item color.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $outputs The array of CSS selectors and properties for the sub-menu item color.
	 *
	 * @return array          The updated array of CSS selectors for the sub-menu item color.
	 */
	public function add_sub_menu_item_color_outputs( $outputs ) {
		return array_merge( $outputs, array(
			array(
				'element'  => implode( ',', array(
					'.bb-global-search-ac.ui-autocomplete',
					'.bb-global-search-ac.ui-autocomplete a',
				) ),
				'property' => 'color',
				'suffix'   => '!important',
			),
		) );
	}
}

return new Grimlock_BuddyPress_Navigation_Customizer();
