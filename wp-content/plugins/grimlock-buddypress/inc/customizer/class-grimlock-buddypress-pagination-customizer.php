<?php
/**
 * Grimlock_BuddyPress_Pagination_Customizer Class
 *
 * @author  Themosaurus
 * @since   1.0.0
 * @package grimlock
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The Grimlock BuddyPress Customizer style class.
 */
class Grimlock_BuddyPress_Pagination_Customizer {
	/**
	 * Setup class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_filter( 'grimlock_pagination_customizer_elements',       array( $this, 'add_elements'       ), 10, 1 );
		add_filter( 'grimlock_pagination_customizer_hover_elements', array( $this, 'add_hover_elements' ), 10, 1 );
	}

	/**
	 * @param $elements
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function add_elements( $elements ) {
		return array_merge( $elements, array(
			'#buddypress div.pagination div.pagination-links .page-numbers',
		) );
	}

	/**
	 * @param $elements
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function add_hover_elements( $elements ) {
		return array_merge( $elements, array(
			'#buddypress div.pagination div.pagination-links .page-numbers.current',
		) );
	}
}

return new Grimlock_BuddyPress_Pagination_Customizer();
