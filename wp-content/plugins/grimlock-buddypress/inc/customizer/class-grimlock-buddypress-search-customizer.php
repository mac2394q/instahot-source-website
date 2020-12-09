<?php
/**
 * Grimlock_BuddyPress_Search_Customizer Class
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
 * The search page class for the Customizer.
 */
class Grimlock_BuddyPress_Search_Customizer {
	/**
	 * Setup class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_filter( 'grimlock_search_customizer_elements',              array( $this, 'add_elements'               ), 10, 1 );
		add_filter( 'grimlock_search_customizer_post_padding_elements', array( $this, 'add_post_padding_elements'  ), 10, 1 );
	}

	/**
	 * Add CSS selectors to the array of CSS selectors for the search elements.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $elements The array of CSS selectors for the search elements.
	 *
	 * @return array           The updated array of CSS selectors for the search elements.
	 */
	public function add_elements( $elements ) {
		return array_merge( $elements, array(
			'#buddypress:not(.youzer) .bboss_search_page ul.item-list li',
		) );
	}

	/**
	 * Add CSS selectors to the array of CSS selectors for the search elements padding.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $elements The array of CSS selectors for the search elements padding.
	 *
	 * @return array           The updated array of CSS selectors for the search elements padding.
	 */
	public function add_post_padding_elements( $elements ) {
		return array_merge( $elements, array(
			'#buddypress:not(.youzer) .bboss_search_page ul.item-list li',
		) );
	}

}

return new Grimlock_BuddyPress_Search_Customizer();
