<?php
/**
 * Gwangi_Grimlock_Footer_Customizer Class
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
 * The footer class for the Customizer.
 */
class Gwangi_Grimlock_Footer_Customizer {
	/**
	 * Setup class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_filter( 'grimlock_footer_customizer_defaults', array( $this, 'change_defaults' ), 10, 1 );
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
		$defaults['footer_background_image']    = GWANGI_FOOTER_BACKGROUND_IMAGE;
		$defaults['footer_layout']              = GWANGI_FOOTER_LAYOUT;
		$defaults['footer_container_layout']    = GWANGI_FOOTER_CONTAINER_LAYOUT;
		$defaults['footer_padding_y']           = GWANGI_FOOTER_PADDING_Y;
		$defaults['footer_mobile_displayed']    = GWANGI_FOOTER_MOBILE_DISPLAYED;
		$defaults['footer_background_color']    = GWANGI_FOOTER_BACKGROUND_COLOR;
		$defaults['footer_heading_color']       = GWANGI_FOOTER_HEADING_COLOR;
		$defaults['footer_color']               = GWANGI_FOOTER_COLOR;
		$defaults['footer_link_color']          = GWANGI_FOOTER_LINK_COLOR;
		$defaults['footer_link_hover_color']    = GWANGI_FOOTER_LINK_HOVER_COLOR;
		$defaults['footer_border_top_width']    = GWANGI_FOOTER_BORDER_TOP_WIDTH;
		$defaults['footer_border_top_color']    = GWANGI_FOOTER_BORDER_TOP_COLOR;
		$defaults['footer_border_bottom_width'] = GWANGI_FOOTER_BORDER_BOTTOM_WIDTH;
		$defaults['footer_border_bottom_color'] = GWANGI_FOOTER_BORDER_BOTTOM_COLOR;

		return $defaults;
	}
}

return new Gwangi_Grimlock_Footer_Customizer();
