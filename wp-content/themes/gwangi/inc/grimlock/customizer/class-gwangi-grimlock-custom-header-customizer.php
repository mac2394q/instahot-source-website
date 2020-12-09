<?php
/**
 * Gwangi_Grimlock_Custom_Header_Customizer Class
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
 * The custom header class for the Customizer.
 */
class Gwangi_Grimlock_Custom_Header_Customizer extends Grimlock_Custom_Header_Customizer {
	/**
	 * Setup class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct();
		add_filter( 'grimlock_custom_header_customizer_defaults', array( $this, 'change_defaults'  ), 10, 1 );
		add_filter( 'body_class',                                 array( $this, 'add_body_classes' ), 10, 1 );

		add_filter( 'grimlock_custom_header_customizer_border_bottom_width_field_args', array( $this, 'change_border_bottom_fields_args' ), 10, 1 );
		add_filter( 'grimlock_custom_header_customizer_border_bottom_color_field_args', array( $this, 'change_border_bottom_fields_args' ), 10, 1 );
	}

	/**
	 * Register default values, settings and custom controls for the Theme Customizer.
	 *
	 * @since 1.0.0
	 */
	public function add_customizer_fields() {
		parent::add_customizer_fields();
		$this->defaults = apply_filters( 'gwangi_grimlock_custom_header_customizer_defaults', array_merge( $this->defaults, array(
			'custom_header_bevel_displayed' => true,
		) ) );

		// @codingStandardsIgnoreStart

		// Allow associative array to be declared in a single line.
		$this->add_bevel_displayed_field( array( 'priority' => 311 ) );
		// @codingStandardsIgnoreEnd
	}

	/**
	 * Add tabs to the Customizer to group controls.
	 *
	 * @param  array $js_data The array of data for the Customizer controls.
	 *
	 * @return array          The filtered array of data for the Customizer controls.
	 */
	public function add_customizer_controls_js_data( $js_data ) {
		$js_data['tabs'][ $this->section ][2]['controls'][] = 'custom_header_bevel_displayed';
		return $js_data;
	}

	/**
	 * Add a Kirki checkbox field to set the custom header bevel display in the Customizer.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args The array of arguments for the Kirki field.
	 */
	protected function add_bevel_displayed_field( $args = array() ) {
		if ( class_exists( 'Kirki' ) ) {
			$args = wp_parse_args( $args, array(
				'type'     => 'checkbox',
				'section'  => $this->section,
				'label'    => esc_html__( 'Display Bottom Bevel', 'gwangi' ),
				'settings' => 'custom_header_bevel_displayed',
				'default'  => $this->get_default( 'custom_header_bevel_displayed' ),
				'priority' => 10,
			) );

			Kirki::add_field( 'grimlock', apply_filters( 'gwangi_grimlock_custom_header_customizer_bevel_displayed_field_args', $args ) );
		}
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
		$defaults['custom_header_padding_y']        = GWANGI_HEADER_PADDING_Y;
		$defaults['custom_header_background_color'] = GWANGI_CUSTOM_HEADER_BACKGROUND_COLOR;

		$defaults['custom_header_title_displayed'] = true;
		$defaults['custom_header_title_format']    = 'display-1';
		$defaults['custom_header_title_color']     = '#fff';

		$defaults['custom_header_subtitle_displayed'] = true;
		$defaults['custom_header_subtitle_format']    = 'lead';
		$defaults['custom_header_subtitle_color']     = '#fff';

		$defaults['custom_header_link_color']       = '#fff';
		$defaults['custom_header_link_hover_color'] = 'rgba(255,255,255,0.8)';

		$defaults['custom_header_layout']           = GWANGI_CUSTOM_HEADER_LAYOUT;
		$defaults['custom_header_container_layout'] = GWANGI_CUSTOM_HEADER_CONTAINER_LAYOUT;
		$defaults['custom_header_mobile_displayed'] = true;
		return $defaults;
	}

	/**
	 * Add custom classes to body to modify layout.
	 *
	 * @since 1.0.0
	 * @param $classes
	 *
	 * @return string
	 */
	public function add_body_classes( $classes ) {
		if ( ! empty( $this->get_theme_mod( 'custom_header_bevel_displayed' ) ) ) {
			$classes[] = 'grimlock--custom_header-bevel-displayed';
		}
		return $classes;
	}

	/**
	 * Change border bottom fields args
	 *
	 * @param array $args The Kirki field args
	 *
	 * @return array The modified Kirki field args
	 */
	public function change_border_bottom_fields_args( $args ) {
		$args['active_callback'] = array(
			array(
				'setting'  => 'custom_header_bevel_displayed',
				'operator' => '==',
				'value'    => false,
			),
		);
		return $args;
	}
}

return new Gwangi_Grimlock_Custom_Header_Customizer();
