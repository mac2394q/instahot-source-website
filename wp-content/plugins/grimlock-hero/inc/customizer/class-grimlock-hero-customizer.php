<?php
/**
 * Grimlock_Hero_Customizer Class
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
 * The Grimlock Customizer class for the Hero section.
 */
class Grimlock_Hero_Customizer extends Grimlock_Region_Customizer {

	/**
	 * Setup class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->id      = 'hero';
		$this->section = 'grimlock_hero_customizer_section';
		$this->title   = esc_html__( 'Hero', 'grimlock-hero' );

		add_action( 'after_setup_theme',                                array( $this, 'add_customizer_fields'           ), 20    );
		add_action( 'customize_register',                               array( $this, 'add_partial' ) );

		add_filter( 'grimlock_customizer_controls_js_data',             array( $this, 'add_customizer_controls_js_data' ), 10, 1 );
		add_filter( 'grimlock_hero_args',                               array( $this, 'add_args'                        ), 10, 1 );
		add_filter( 'grimlock_navigation_customizer_padding_y_outputs', array( $this, 'add_padding_y_outputs'           ), 10, 1 );

		add_filter( 'kirki_grimlock_dynamic_css',                       array( $this, 'add_dynamic_css'                 ), 10, 1 );

		add_action( 'customize_controls_print_scripts',                 array( $this, 'add_scripts'                     ), 30, 1 );
	}

	/**
	 * Add arguments using theme mods to customize the region component.
	 *
	 * @param array $args The default arguments to render the region.
	 *
	 * @return array      The arguments to render the region.
	 */
	public function add_args( $args ) {
		$args                          = parent::add_args( $args );
		$args['displayed']             = $this->get_theme_mod( 'hero_displayed' );
		$args['background_image']      = $this->get_theme_mod( 'hero_background_image' );
		$args['full_screen_displayed'] = $this->get_theme_mod( 'hero_full_screen_displayed' );

		$args['title_displayed']       = '' !== $this->get_theme_mod( 'hero_title' );
		$args['title']                 = $this->get_theme_mod( 'hero_title' );
		$args['title_color']           = $this->get_theme_mod( 'hero_title_color' );

		$args['subtitle_displayed']    = '' !== $this->get_theme_mod( 'hero_subtitle' );
		$args['subtitle']              = $this->get_theme_mod( 'hero_subtitle' );
		$args['subtitle_color']        = $this->get_theme_mod( 'hero_subtitle_color' );

		$args['text_displayed']        = '' !== $this->get_theme_mod( 'hero_text' );
		$args['text']                  = $this->get_theme_mod( 'hero_text' );
		$args['color']                 = $this->get_theme_mod( 'hero_text_color' );

		$args['login_form_displayed']  = $this->get_theme_mod( 'hero_login_form_displayed' );
		$args['search_form_displayed'] = $this->get_theme_mod( 'hero_search_form_displayed' );

		$args['button_displayed']      = $this->get_theme_mod( 'hero_button_displayed' );
		$args['button_text']           = true == $args['button_displayed'] ? $this->get_theme_mod( 'hero_button_text' ) : '';
		$args['button_link']           = true == $args['button_displayed'] ? $this->get_theme_mod( 'hero_button_link' ) : '#';
		$args['button_target_blank']   = $this->get_theme_mod( 'hero_button_target_blank' );

		$args['text_wpautoped']        = $this->get_theme_mod( 'hero_text_wpautoped' );
		$args['thumbnail']             = $this->get_theme_mod( 'hero_thumbnail' );
		return $args;
	}

	/**
	 * @param $outputs
	 *
	 * @return mixed
	 */
	public function add_padding_y_outputs( $outputs ) {
		return array_merge( $outputs, array(
			array(
				'element'       => '.grimlock--navigation-inside-top .grimlock-hero > .region__inner > .region__container',
				'property'      => 'margin-top',
				'value_pattern' => 'calc($rem * 1.5)',
			),
			array(
				'element'       => '.grimlock--navigation-inside-top .grimlock-hero--full-screen-displayed > .region__inner > .region__container',
				'property'      => 'margin-bottom',
				'value_pattern' => 'calc($rem * 1.5)',
			),
			array(
				'element'       => '.grimlock--navigation-inside-bottom .grimlock-hero > .region__inner > .region__container',
				'property'      => 'margin-bottom',
				'value_pattern' => 'calc($rem * 2.5)',
				'media_query'   => '@media (min-width: 992px)',
			),
			array(
				'element'       => '.grimlock--navigation-inside-bottom .grimlock-hero > .region__inner > .region__container',
				'property'      => 'margin-top',
				'value_pattern' => 'calc($rem * 2.5)',
				'media_query'   => '@media (max-width: 992px)',
			),
		) );
	}

	/**
	 * Register default values, settings and custom controls for the Theme Customizer.
	 *
	 * @since 1.0.0
	 */
	public function add_customizer_fields() {
		$this->defaults = apply_filters( 'grimlock_hero_customizer_defaults', array(
			'hero_displayed'                        => true,
			'hero_mobile_displayed'                 => true,

			'hero_full_screen_displayed'            => false,
			'hero_padding_y'                        => 16, // %
			'hero_background_image'                 => '',
			'hero_background_color'                 => GRIMLOCK_SECTION_BACKGROUND_COLOR,
			'hero_background_gradient_displayed'    => false,
			'hero_background_gradient_first_color'  => 'rgba(0,0,0,0)',
			'hero_background_gradient_second_color' => 'rgba(0,0,0,.35)',
			'hero_background_gradient_direction'    => '0deg',
			'hero_background_gradient_position'     => '0', // %

			'hero_border_top_color'                 => GRIMLOCK_BORDER_COLOR,
			'hero_border_top_width'                 => 0, // px
			'hero_border_bottom_color'              => GRIMLOCK_BORDER_COLOR,
			'hero_border_bottom_width'              => 0, // px

			'hero_title'                            => esc_attr__( 'Title goes here...', 'grimlock-hero' ),
			'hero_title_font'                       => array(
				'font-family'                       => GRIMLOCK_FONT_FAMILY_SANS_SERIF,
				'variant'                           => 'regular',
				'font-size'                         => GRIMLOCK_DISPLAY_HEADING1_FONT_SIZE,
				'line-height'                       => GRIMLOCK_HEADINGS_LINE_HEIGHT,
				'letter-spacing'                    => GRIMLOCK_LETTER_SPACING,
				'subsets'                           => array( 'latin-ext' ),
				'text-transform'                    => 'none',
			),
			'hero_title_color'                      => GRIMLOCK_BODY_COLOR,
			'hero_title_displayed'                  => true,

			'hero_thumbnail'                        => '',
			'hero_subtitle'                         => esc_attr__( 'Subtitle goes here...', 'grimlock-hero' ),
			'hero_subtitle_font'                    => array(
				'font-family'                       => GRIMLOCK_FONT_FAMILY_SANS_SERIF,
				'variant'                           => '300',
				'font-size'                         => GRIMLOCK_HEADING1_FONT_SIZE,
				'line-height'                       => '1.5',
				'letter-spacing'                    => GRIMLOCK_LETTER_SPACING,
				'subsets'                           => array( 'latin-ext' ),
				'text-transform'                    => 'none',
			),
			'hero_subtitle_color'                   => GRIMLOCK_BODY_COLOR,
			'hero_subtitle_displayed'               => true,

			'hero_text'                             => esc_attr__( 'Text goes here...', 'grimlock-hero' ),
			'hero_text_font'                        => array(
				'font-size'                         => GRIMLOCK_FONT_SIZE,
				'line-height'                       => GRIMLOCK_LINE_HEIGHT,
				'letter-spacing'                    => GRIMLOCK_LETTER_SPACING,
				'text-transform'                    => 'none',
			),
			'hero_text_color'                       => GRIMLOCK_BODY_COLOR,
			'hero_text_displayed'                   => true,
			'hero_text_wpautoped'                   => true,

			'hero_login_form_displayed'             => false,
			'hero_search_form_displayed'            => false,

			'hero_button_text'                      => esc_attr__( 'Learn more', 'grimlock-hero' ),
			'hero_button_link'                      => '#',
			'hero_button_target_blank'              => false,
			'hero_button_color'                     => GRIMLOCK_BUTTON_PRIMARY_COLOR,
			'hero_button_background_color'          => GRIMLOCK_BUTTON_PRIMARY_BACKGROUND_COLOR,
			'hero_button_border_color'              => GRIMLOCK_BUTTON_PRIMARY_BORDER_COLOR,
			'hero_button_hover_color'               => GRIMLOCK_BUTTON_PRIMARY_HOVER_COLOR,
			'hero_button_hover_background_color'    => GRIMLOCK_BUTTON_PRIMARY_HOVER_BACKGROUND_COLOR,
			'hero_button_hover_border_color'        => GRIMLOCK_BUTTON_PRIMARY_HOVER_BORDER_COLOR,
			'hero_button_displayed'                 => true,

			'hero_layout'                           => '12-cols-center',
			'hero_container_layout'                 => 'classic',
		) );

		$this->add_section(                     array( 'priority' => 45  ) );

		// Add fields to the General tab.
		$this->add_thumbnail_field(             array( 'priority' => 10  ) );
		$this->add_divider_field(               array( 'priority' => 20  ) );
		$this->add_title_field(                 array( 'priority' => 20  ) );
		$this->add_divider_field(               array( 'priority' => 30  ) );
		$this->add_subtitle_field(              array( 'priority' => 30  ) );
		$this->add_divider_field(               array( 'priority' => 40  ) );
		$this->add_text_field(                  array( 'priority' => 40  ) );
		$this->add_text_wpautoped_field(        array( 'priority' => 50  ) );
		$this->add_divider_field(               array( 'priority' => 60  ) );
//		$this->add_login_form_displayed_field(  array( 'priority' => 60  ) ); TODO: uncomment when we find a solution to avoid seeing the same Hero after login with the Hero login form
		$this->add_search_form_displayed_field( array( 'priority' => 70  ) );
		$this->add_divider_field(               array( 'priority' => 80  ) );
		$this->add_button_text_field(           array( 'priority' => 80  ) );
		$this->add_button_link_field(           array( 'priority' => 90  ) );
		$this->add_button_target_blank_field(   array( 'priority' => 100  ) );
		$this->add_button_displayed_field(      array( 'priority' => 110  ) );

		// Add fields to the Layout tab.
		$this->add_layout_field(           array( 'priority' => 200 ) );
		$this->add_divider_field(          array( 'priority' => 210 ) );
		$this->add_container_layout_field( array( 'priority' => 210 ) );
		$this->add_divider_field(          array( 'priority' => 220 ) );
		$this->add_heading_field(          array(
			'label'    => esc_html__( 'Mobile Display', 'grimlock' ),
			'priority' => 220,
		) );

		$this->add_mobile_displayed_field( array( 'priority' => 220 ) );

		// Add fields to the Style tab.
		$this->add_background_image_field( array( 'priority' => 300 ) );
		$this->add_divider_field(          array( 'priority' => 310 ) );
		$this->add_padding_y_field(        array(
			'priority'        => 310,
			'description'     => esc_html__( 'Vertical padding can be manually set or the full screen mode can be activated instead.', 'grimlock-hero' ),
			'transport'       => 'refresh',
			'active_callback' => array(
				array(
					'setting'  => 'hero_full_screen_displayed',
					'operator' => '==',
					'value'    => false,
				),
			),
		) );

		$this->add_full_screen_displayed_field( array( 'priority' => 320 ) );
		$this->add_divider_field(               array( 'priority' => 330 ) );
		$this->add_background_color_field(      array(
			'priority'        => 330,
			'transport'       => 'refresh',
		) );

		$this->add_background_gradient_displayed_field(    array( 'priority' => 340 ) );
		$this->add_background_gradient_first_color_field(  array( 'priority' => 350 ) );
		$this->add_background_gradient_second_color_field( array( 'priority' => 360 ) );
		$this->add_background_gradient_direction_field(    array( 'priority' => 370 ) );
		$this->add_background_gradient_position_field(     array( 'priority' => 380 ) );
		$this->add_divider_field(                          array( 'priority' => 390 ) );
		$this->add_border_top_width_field(                 array( 'priority' => 390, 'transport' => 'refresh' ) );
		$this->add_border_top_color_field(                 array( 'priority' => 400 ) );
		$this->add_divider_field(                          array( 'priority' => 410 ) );
		$this->add_border_bottom_width_field(              array( 'priority' => 420, 'transport' => 'refresh' ) );
		$this->add_border_bottom_color_field(              array( 'priority' => 430 ) );
		$this->add_divider_field(                          array( 'priority' => 440 ) );
		$this->add_title_font_field(                       array( 'priority' => 450 ) );
		$this->add_divider_field(                          array( 'priority' => 460 ) );
		$this->add_title_color_field(                      array( 'priority' => 460 ) );
		$this->add_divider_field(                          array( 'priority' => 470 ) );
		$this->add_subtitle_font_field(                    array( 'priority' => 470 ) );
		$this->add_divider_field(                          array( 'priority' => 480 ) );
		$this->add_subtitle_color_field(                   array( 'priority' => 480 ) );
		$this->add_divider_field(                          array( 'priority' => 490 ) );
		$this->add_text_font_field(                        array( 'priority' => 490 ) );
		$this->add_divider_field(                          array( 'priority' => 500 ) );
		$this->add_text_color_field(                       array( 'priority' => 500 ) );
		$this->add_divider_field(                          array( 'priority' => 510 ) );
		$this->add_button_background_color_field(          array( 'priority' => 510 ) );
		$this->add_button_color_field(                     array( 'priority' => 520 ) );
		$this->add_button_border_color_field(              array( 'priority' => 530 ) );
		$this->add_divider_field(                          array( 'priority' => 540 ) );
		$this->add_button_hover_background_color_field(    array( 'priority' => 540 ) );
		$this->add_button_hover_color_field(               array( 'priority' => 550 ) );
		$this->add_button_hover_border_color_field(        array( 'priority' => 560 ) );
	}

	/**
	 * Add tabs to the Customizer to group controls.
	 *
	 * @param  array $js_data The array of data for the Customizer controls.
	 *
	 * @return array          The filtered array of data for the Customizer controls.
	 */
	public function add_customizer_controls_js_data( $js_data ) {
		$js_data['tabs']['grimlock_hero_customizer_section'] = array(
			array(
				'label'    => esc_html__( 'General', 'grimlock-hero' ),
				'class'    => 'hero-general-tab',
				'controls' => array(
					'hero_thumbnail',
					"{$this->section}_divider_20",
					'hero_title',
					"{$this->section}_divider_30",
					'hero_subtitle',
					"{$this->section}_divider_40",
					'hero_text',
					'hero_text_wpautoped',
					"{$this->section}_divider_60",
					'hero_login_form_displayed',
					'hero_search_form_displayed',
					"{$this->section}_divider_80",
					'hero_button_text',
					'hero_button_link',
					'hero_button_target_blank',
					'hero_button_displayed',
				),
			),
			array(
				'label'    => esc_html__( 'Layout', 'grimlock-hero' ),
				'class'    => 'hero-layout-tab',
				'controls' => array(
					'hero_layout',
					"{$this->section}_divider_210",
					'hero_container_layout',
					"{$this->section}_divider_220",
					"{$this->section}_heading_220",
					'hero_mobile_displayed',
				),
			),
			array(
				'label'    => esc_html__( 'Style', 'grimlock-hero' ),
				'class'    => 'hero-style-tab',
				'controls' => array(
					'hero_background_image',
					"{$this->section}_divider_310",
					'hero_padding_y',
					'hero_full_screen_displayed',
					"{$this->section}_divider_330",
					'hero_background_color',
					'hero_background_gradient_displayed',
					'hero_background_gradient_first_color',
					'hero_background_gradient_second_color',
					'hero_background_gradient_direction',
					'hero_background_gradient_position',
					"{$this->section}_divider_390",
					'hero_border_top_color',
					'hero_border_top_width',
					"{$this->section}_divider_410",
					'hero_border_bottom_color',
					'hero_border_bottom_width',
					"{$this->section}_divider_440",
					'hero_title_font',
					"{$this->section}_divider_460",
					'hero_title_color',
					"{$this->section}_divider_470",
					'hero_subtitle_font',
					"{$this->section}_divider_480",
					'hero_subtitle_color',
					"{$this->section}_divider_490",
					'hero_text_font',
					"{$this->section}_divider_500",
					'hero_text_color',
					"{$this->section}_divider_510",
					'hero_button_background_color',
					'hero_button_color',
					'hero_button_border_color',
					"{$this->section}_divider_540",
					'hero_button_hover_background_color',
					'hero_button_hover_color',
					'hero_button_hover_border_color',
				),
			),
		);
		return $js_data;
	}

	/**
	 * Add a Kirki image field to set the featured image for the section in the Customizer.
	 *
	 * @param array $args
	 * @since 1.0.0
	 */
	protected function add_thumbnail_field( $args = array() ) {
		if ( class_exists( 'Kirki' ) ) {
			$args = wp_parse_args( $args, array(
				'type'     => 'image',
				'section'  => $this->section,
				'label'    => esc_html__( 'Featured Image', 'grimlock-hero' ),
				'settings' => 'hero_thumbnail',
				'default'  => $this->get_default( 'hero_thumbnail' ),
				'priority' => 10,
			) );

			Kirki::add_field( 'grimlock', apply_filters( 'grimlock_hero_customizer_thumbnail_field_args', $args ) );
		}
	}

	/**
	 * Add a Kirki text field to set the title in the Customizer.
	 *
	 * @param array $args
	 * @since 1.0.0
	 */
	protected function add_title_field( $args = array() ) {
		if ( class_exists( 'Kirki' ) ) {
			$args = wp_parse_args( $args, array(
				'type'              => 'text',
				'label'             => esc_html__( 'Title', 'grimlock-hero' ),
				'section'           => $this->section,
				'settings'          => 'hero_title',
				'default'           => $this->get_default( 'hero_title' ),
				'priority'          => 10,
				'sanitize_callback' => 'wp_kses_post',
			) );

			Kirki::add_field( 'grimlock', apply_filters( 'grimlock_hero_customizer_title_field_args', $args ) );
		}
	}

	/**
	 * Add a Kirki typography field to set the typography in the Customizer.
	 *
	 * @param array $args
	 * @since 1.0.0
	 */
	protected function add_title_font_field( $args = array() ) {
		if ( class_exists( 'Kirki') ) {
			$elements = apply_filters( 'grimlock_hero_customizer_title_font_elements', array(
				'.grimlock-hero .section__title',
			) );

			$outputs = apply_filters( 'grimlock_hero_customizer_title_font_outputs', array(
				array(
					'element'  => implode( ',', $elements ),
				),
			) );

			$args = wp_parse_args( $args, array(
				'type'     => 'typography',
				'settings' => 'hero_title_font',
				'label'    => esc_attr__( 'Title Typography', 'grimlock-hero' ),
				'section'  => $this->section,
				'default'  => $this->get_default( 'hero_title_font' ),
				'priority' => 10,
				'transport' => 'postMessage',
				'output'    => $outputs,
				'css_vars'  => array(
					array( '--grimlock-hero-title-font-family', '$', 'font-family' ),
					array( '--grimlock-hero-title-font-weight', '$', 'font-weight' ),
					array( '--grimlock-hero-title-font-size', '$', 'font-size' ),
					array( '--grimlock-hero-title-line-height', '$', 'line-height' ),
					array( '--grimlock-hero-title-letter-spacing', '$', 'letter-spacing' ),
					array( '--grimlock-hero-title-text-transform', '$', 'text-transform' ),
				),
				'js_vars'   => $this->to_js_vars( $outputs ),
			) );

			Kirki::add_field( 'grimlock', apply_filters( 'grimlock_hero_customizer_title_font_field_args', $args ) );
		}
	}

	/**
	 * Add a Kirki color field to set the color in the Customizer.
	 *
	 * @param array $args
	 * @since 1.0.0
	 */
	protected function add_title_color_field( $args = array() ) {
		if ( class_exists( 'Kirki' ) ) {
			$elements = apply_filters( 'grimlock_hero_customizer_title_color_elements', array(
				'.grimlock-hero .section__title',
			) );

			$outputs = apply_filters( 'grimlock_hero_customizer_title_color_outputs', array(
				array(
					'element'  => $elements,
					'property' => 'color',
					'suffix'   => '!important',
				),
			), $elements );

			$args = wp_parse_args( $args, array(
				'type'      => 'color',
				'label'     => esc_html__( 'Title Color', 'grimlock' ),
				'section'   => $this->section,
				'settings'  => 'hero_title_color',
				'default'   => $this->get_default( 'hero_title_color' ),
				'choices'   => array(
					'alpha'    => true,
					'palettes' => apply_filters( 'grimlock_color_field_palettes', array() ),
				),
				'priority'  => 10,
				'transport' => 'postMessage',
				'output'    => array(
					$this->get_css_var_output( 'hero_title_color' ),
				),
				'js_vars'   => $this->to_js_vars( $outputs ),
			) );

			Kirki::add_field( 'grimlock', apply_filters( 'grimlock_hero_customizer_title_color_field_args', $args ) );
		}
	}

	/**
	 * Add a Kirki text field to set the subtitle in the Customizer.
	 *
	 * @param array $args
	 * @since 1.0.0
	 */
	protected function add_subtitle_field( $args = array() ) {
		if ( class_exists( 'Kirki' ) ) {
			$args = wp_parse_args( $args, array(
				'type'              => 'text',
				'label'             => esc_html__( 'Subtitle', 'grimlock-hero' ),
				'section'           => $this->section,
				'settings'          => 'hero_subtitle',
				'default'           => $this->get_default( 'hero_subtitle' ),
				'priority'          => 10,
				'sanitize_callback' => 'wp_kses_post',
			) );

			Kirki::add_field( 'grimlock', apply_filters( 'grimlock_hero_customizer_subtitle_field_args', $args ) );
		}
	}

	/**
	 * Add a Kirki typography field to set the typography in the Customizer.
	 *
	 * @param array $args
	 * @since 1.0.0
	 */
	protected function add_subtitle_font_field( $args = array() ) {
		if ( class_exists( 'Kirki') ) {
			$elements = apply_filters( 'grimlock_hero_customizer_subtitle_font_elements', array(
				'.grimlock-hero .section__subtitle',
			) );

			$outputs = apply_filters( 'grimlock_hero_customizer_subtitle_font_outputs', array(
				array(
					'element'  => implode( ',', $elements ),
				),
			) );

			$args = wp_parse_args( $args, array(
				'type'      => 'typography',
				'settings'  => 'hero_subtitle_font',
				'label'     => esc_attr__( 'Subtitle Typography', 'grimlock-hero' ),
				'section'   => $this->section,
				'default'   => $this->get_default( 'hero_subtitle_font' ),
				'priority'  => 10,
				'transport' => 'postMessage',
				'output'    => $outputs,
				'css_vars'  => array(
					array( '--grimlock-hero-subtitle-font-family', '$', 'font-family' ),
					array( '--grimlock-hero-subtitle-font-weight', '$', 'font-weight' ),
					array( '--grimlock-hero-subtitle-font-size', '$', 'font-size' ),
					array( '--grimlock-hero-subtitle-line-height', '$', 'line-height' ),
					array( '--grimlock-hero-subtitle-letter-spacing', '$', 'letter-spacing' ),
					array( '--grimlock-hero-subtitle-text-transform', '$', 'text-transform' ),
				),
				'js_vars'   => $this->to_js_vars( $outputs ),
			) );

			Kirki::add_field( 'grimlock', apply_filters( 'grimlock_hero_customizer_subtitle_font_field_args', $args ) );
		}
	}

	/**
	 * Add a Kirki color field to set the color in the Customizer.
	 *
	 * @param array $args
	 * @since 1.0.0
	 */
	protected function add_subtitle_color_field( $args = array() ) {
		if ( class_exists( 'Kirki' ) ) {
			$elements = apply_filters( 'grimlock_hero_customizer_subtitle_color_elements', array(
				'.grimlock-hero .section__subtitle',
			) );

			$outputs = apply_filters( 'grimlock_hero_customizer_subtitle_color_outputs', array(
				array(
					'element'  => $elements,
					'property' => 'color',
					'suffix'   => '!important',
				),
			), $elements );

			$args = wp_parse_args( $args, array(
				'type'      => 'color',
				'label'     => esc_html__( 'Subtitle Color', 'grimlock' ),
				'section'   => $this->section,
				'settings'  => 'hero_subtitle_color',
				'default'   => $this->get_default( 'hero_subtitle_color' ),
				'choices'   => array(
					'alpha'    => true,
					'palettes' => apply_filters( 'grimlock_color_field_palettes', array() ),
				),
				'priority'  => 10,
				'transport' => 'postMessage',
				'output'    => array(
					$this->get_css_var_output( 'hero_subtitle_color' ),
				),
				'js_vars'   => $this->to_js_vars( $outputs ),
			) );

			Kirki::add_field( 'grimlock', apply_filters( 'grimlock_hero_customizer_subtitle_color_field_args', $args ) );
		}
	}

	/**
	 * Add a Kirki typography field to set the typography in the Customizer.
	 *
	 * @param array $args
	 * @since 1.0.0
	 */
	protected function add_text_font_field( $args = array() ) {
		if ( class_exists( 'Kirki') ) {
			$elements = apply_filters( 'grimlock_hero_customizer_text_font_elements', array(
				'.grimlock-hero .section__text',
			) );

			$outputs = apply_filters( 'grimlock_hero_customizer_text_font_outputs', array(
				array(
					'element'  => implode( ',', $elements ),
				),
			) );

			$args = wp_parse_args( $args, array(
				'type'      => 'typography',
				'settings'  => 'hero_text_font',
				'label'     => esc_attr__( 'Text Typography', 'grimlock-hero' ),
				'section'   => $this->section,
				'default'   => $this->get_default( 'hero_text_font' ),
				'priority'  => 10,
				'transport' => 'postMessage',
				'output'    => $outputs,
				'css_vars'  => array(
					array( '--grimlock-hero-text-font-size', '$', 'font-size' ),
					array( '--grimlock-hero-text-line-height', '$', 'line-height' ),
					array( '--grimlock-hero-text-letter-spacing', '$', 'letter-spacing' ),
					array( '--grimlock-hero-text-text-transform', '$', 'text-transform' ),
				),
				'js_vars'   => $this->to_js_vars( $outputs ),
			) );

			Kirki::add_field( 'grimlock', apply_filters( 'grimlock_hero_customizer_text_font_field_args', $args ) );
		}
	}

	/**
	 * Add a Kirki color field to set the color in the Customizer.
	 *
	 * @param array $args
	 * @since 1.0.0
	 */
	protected function add_text_color_field( $args = array() ) {
		if ( class_exists( 'Kirki' ) ) {
			$elements = apply_filters( 'grimlock_hero_customizer_text_color_elements', array(
				'.grimlock-hero .section__text',
			) );

			$outputs = apply_filters( 'grimlock_hero_customizer_text_color_outputs', array(
				array(
					'element'  => $elements,
					'property' => 'color',
					'suffix'   => '!important',
				),
			), $elements );

			$args = wp_parse_args( $args, array(
				'type'      => 'color',
				'label'     => esc_html__( 'Text Color', 'grimlock' ),
				'section'   => $this->section,
				'settings'  => 'hero_text_color',
				'default'   => $this->get_default( 'hero_text_color' ),
				'choices'   => array(
					'alpha'    => true,
					'palettes' => apply_filters( 'grimlock_color_field_palettes', array() ),
				),
				'priority'  => 10,
				'transport' => 'postMessage',
				'output'    => array(
					$this->get_css_var_output( 'hero_text_color' ),
				),
				'js_vars'   => $this->to_js_vars( $outputs ),
			) );

			Kirki::add_field( 'grimlock', apply_filters( 'grimlock_hero_customizer_text_color_field_args', $args ) );
		}
	}

    /**
	 * Add a Kirki radio-image field to set the layout in the Customizer.
	 *
	 * @param array $args
	 * @since 1.0.0
	 */
	protected function add_layout_field( $args = array() ) {
		if ( class_exists( 'Kirki' ) ) {
			$args = wp_parse_args( $args, array(
				'type'      => 'radio-image',
				'section'   => $this->section,
				'label'     => esc_html__( 'Layout', 'grimlock-hero' ),
				'settings'  => 'hero_layout',
				'default'   => $this->get_default( 'hero_layout' ),
				'priority'  => 10,
				'choices'   => array(
					'12-cols-left'                 => GRIMLOCK_HERO_PLUGIN_DIR_URL . 'assets/images/hero-12-cols-left.png',
					'12-cols-center'               => GRIMLOCK_HERO_PLUGIN_DIR_URL . 'assets/images/hero-12-cols-center.png',
					'12-cols-right'                => GRIMLOCK_HERO_PLUGIN_DIR_URL . 'assets/images/hero-12-cols-right.png',
					'6-6-cols-left'                => GRIMLOCK_HERO_PLUGIN_DIR_URL . 'assets/images/hero-6-6-cols-left.png',
					'6-6-cols-left-reverse'        => GRIMLOCK_HERO_PLUGIN_DIR_URL . 'assets/images/hero-6-6-cols-left-reverse.png',
					'6-6-cols-left-modern'         => GRIMLOCK_HERO_PLUGIN_DIR_URL . 'assets/images/hero-6-6-cols-left-modern.png',
					'6-6-cols-left-reverse-modern' => GRIMLOCK_HERO_PLUGIN_DIR_URL . 'assets/images/hero-6-6-cols-left-reverse-modern.png',
				),
			) );

			Kirki::add_field( 'grimlock', apply_filters( 'grimlock_hero_customizer_layout_field_args', $args ) );
		}
	}

	/**
	 * Add a Kirki checkbox field to activate the full screen mode in the Customizer.
	 *
	 * @param array $args
	 * @since 1.0.0
	 */
	protected function add_full_screen_displayed_field( $args = array() ) {
		if ( class_exists( 'Kirki') ) {
			$args = wp_parse_args( $args, array(
				'type'     => 'checkbox',
				'section'  => $this->section,
				'label'    => esc_html__( 'Activate full screen mode', 'grimlock-hero' ),
				'settings' => 'hero_full_screen_displayed',
				'default'  => $this->get_default( 'hero_full_screen_displayed' ),
				'priority' => 10,
			) );

			Kirki::add_field( 'grimlock', apply_filters( 'grimlock_hero_customizer_full_screen_displayed_field_args', $args ) );
		}
	}

	/**
	 * Add a Kirki textarea field to set the text in the Customizer.
	 *
	 * @param array $args
	 * @since 1.0.0
	 */
	protected function add_text_field( $args = array() ) {
		if ( class_exists( 'Kirki' ) ) {
			$args = wp_parse_args( $args, array(
				'type'              => 'textarea',
				'label'             => esc_html__( 'Text', 'grimlock-hero' ),
				'section'           => $this->section,
				'settings'          => 'hero_text',
				'default'           => $this->get_default( 'hero_text' ),
				'sanitize_callback' => 'wp_kses_post',
				'priority'          => 10,
			) );

			Kirki::add_field( 'grimlock', apply_filters( 'grimlock_hero_customizer_text_field_args', $args ) );
		}
	}

	/**
	 * Add a Kirki checkbox field to set the display for the text paragraphs in the Customizer.
	 *
	 * @param array $args
	 * @since 1.0.0
	 */
	protected function add_text_wpautoped_field( $args = array() ) {
		if ( class_exists( 'Kirki') ) {
			$args = wp_parse_args( $args, array(
				'type'     => 'checkbox',
				'section'  => $this->section,
				'label'    => esc_html__( 'Automatically add paragraphs' ),
				'settings' => 'hero_text_wpautoped',
				'default'  => $this->get_default( 'hero_text_wpautoped' ),
				'priority' => 10,
			) );

			Kirki::add_field( 'grimlock', apply_filters( 'grimlock_hero_customizer_text_wpautoped_field_args', $args ) );
		}
	}

	/**
	 * Add a Kirki checkbox field to set the display for the search form in the Customizer.
	 *
	 * @param array $args
	 * @since 1.0.3
	 */
	protected function add_search_form_displayed_field( $args = array() ) {
		if ( class_exists( 'Kirki') ) {
			$args = wp_parse_args( $args, array(
				'type'     => 'checkbox',
				'section'  => $this->section,
				'label'    => esc_html__( 'Display search form', 'grimlock-hero' ),
				'settings' => 'hero_search_form_displayed',
				'default'  => $this->get_default( 'hero_search_form_displayed' ),
				'priority' => 10,
			) );

			Kirki::add_field( 'grimlock', apply_filters( 'grimlock_hero_customizer_search_form_displayed_field_args', $args ) );
		}
	}

	/**
	 * Add a Kirki checkbox field to set the display for the login form in the Customizer.
	 *
	 * @param array $args
	 * @since 1.0.3
	 */
	protected function add_login_form_displayed_field( $args = array() ) {
		if ( class_exists( 'Kirki') ) {
			$args = wp_parse_args( $args, array(
				'type'        => 'checkbox',
				'section'     => $this->section,
				'label'       => esc_html__( 'Display login form', 'grimlock-hero' ),
				'description' => esc_html__( 'The form is only visible for logged out users. Therefore it will not show in the preview.', 'grimlock-hero' ),
				'settings'    => 'hero_login_form_displayed',
				'default'     => $this->get_default( 'hero_login_form_displayed' ),
				'priority'    => 10,
			) );

			Kirki::add_field( 'grimlock', apply_filters( 'grimlock_hero_customizer_login_form_displayed_field_args', $args ) );
		}
	}

	/**
	 * Add a Kirki text field to set the button label in the Customizer.
	 *
	 * @param array $args
	 * @since 1.0.0
	 */
	protected function add_button_text_field( $args = array() ) {
		if ( class_exists( 'Kirki' ) ) {
			$elements = apply_filters( 'grimlock_hero_customizer_button_text_elements', array(
				'.grimlock-hero .section__btn',
				'.grimlock-hero .section__content [type="submit"]',
				'.grimlock-hero .section__content [type="button"]',
				'.grimlock-hero .section__content #wp-submit',
			) );

			$args = wp_parse_args( $args, array(
				'type'              => 'text',
				'label'             => esc_html__( 'Button Text', 'grimlock-hero' ),
				'section'           => $this->section,
				'settings'          => 'hero_button_text',
				'default'           => $this->get_default( 'hero_button_text' ),
				'priority'          => 10,
				'sanitize_callback' => 'wp_kses_post',
				'transport'         => 'postMessage',
				'js_vars'           => array(
					array(
						'function'  => 'html',
						'element'   => implode( ',', $elements ),
					),
				),
			) );

			Kirki::add_field( 'grimlock', apply_filters( 'grimlock_hero_customizer_button_text_field_args', $args ) );
		}
	}

	/**
	 * Add a Kirki text field to set the button link in the Customizer.
	 *
	 * @param array $args
	 * @since 1.0.0
	 */
	protected function add_button_link_field( $args = array() ) {
		if ( class_exists( 'Kirki' ) ) {
			$args = wp_parse_args( $args, array(
				'type'     => 'text',
				'label'    => esc_html__( 'Button Link', 'grimlock-hero' ),
				'section'  => $this->section,
				'settings' => 'hero_button_link',
				'default'  => $this->get_default( 'hero_button_link' ),
				'priority' => 10,
			) );

			Kirki::add_field( 'grimlock', apply_filters( 'grimlock_hero_customizer_button_link_field_args', $args ) );
		}
	}

	/**
	 * Add a Kirki checkbox field in the Customizer to set whether the button link should open in a new page.
	 *
	 * @param array $args
	 * @since 1.0.0
	 */
	protected function add_button_target_blank_field( $args = array() ) {
		if ( class_exists( 'Kirki') ) {
			$args = wp_parse_args( $args, array(
				'type'     => 'checkbox',
				'section'  => $this->section,
				'label'    => esc_html__( 'Open link in a new page', 'grimlock-hero' ),
				'settings' => 'hero_button_target_blank',
				'default'  => $this->get_default( 'hero_button_target_blank' ),
				'priority' => 10,
			) );

			Kirki::add_field( 'grimlock', apply_filters( 'grimlock_hero_customizer_button_target_blank_field_args', $args ) );
		}
	}

	/**
	 * Add a Kirki checkbox field to set the button display in the Customizer.
	 *
	 * @param array $args
	 * @since 1.0.0
	 */
	protected function add_button_displayed_field( $args = array() ) {
		if ( class_exists( 'Kirki') ) {
			$args = wp_parse_args( $args, array(
				'type'     => 'checkbox',
				'section'  => $this->section,
				'label'    => esc_html__( 'Display button', 'grimlock-hero' ),
				'settings' => 'hero_button_displayed',
				'default'  => $this->get_default( 'hero_button_displayed' ),
				'priority' => 10,
			) );

			Kirki::add_field( 'grimlock', apply_filters( 'grimlock_hero_customizer_button_displayed_field_args', $args ) );
		}
	}

	/**
	 * Add a Kirki color field to set the button_background color in the Customizer.
	 *
	 * @param array $args
	 * @since 1.0.0
	 */
	protected function add_button_background_color_field( $args = array() ) {
		if ( class_exists( 'Kirki' ) ) {
			$elements = apply_filters( 'grimlock_hero_customizer_button_background_color_elements', array(
				'.grimlock-hero .section__btn',
				'.grimlock-hero .section__content [type="submit"]',
				'.grimlock-hero .section__content [type="button"]',
				'.grimlock-hero .section__content #wp-submit',
			) );

			$outputs = apply_filters( 'grimlock_hero_customizer_button_background_color_outputs', array(
				$this->get_css_var_output( 'hero_button_background_color' ),
				array(
					'element'  => implode( ',', $elements ),
					'property' => 'background-color',
				),
			) );

			$args = wp_parse_args( $args, array(
				'type'      => 'color',
				'label'     => esc_html__( 'Button Background Color', 'grimlock-hero' ),
				'section'   => $this->section,
				'settings'  => 'hero_button_background_color',
				'default'   => $this->get_default( 'hero_button_background_color' ),
				'choices'   => array(
					'alpha'    => true,
					'palettes' => apply_filters( 'grimlock_color_field_palettes', array() ),
				),
				'priority'  => 10,
				'transport' => 'postMessage',
				'output'    => $outputs,
				'js_vars'   => $this->to_js_vars( $outputs ),
			) );

			Kirki::add_field( 'grimlock', apply_filters( 'grimlock_hero_customizer_button_background_color_field_args', $args ) );
		}
	}

	/**
	 * Add a Kirki color field to set the button color in the Customizer.
	 *
	 * @param array $args
	 * @since 1.0.0
	 */
	protected function add_button_color_field( $args = array() ) {
		if ( class_exists( 'Kirki' ) ) {
			$elements = apply_filters( 'grimlock_hero_customizer_button_color_elements', array(
				'.grimlock-hero .section__btn',
				'.grimlock-hero .section__content [type="submit"]',
				'.grimlock-hero .section__content [type="button"]',
				'.grimlock-hero .section__content #wp-submit',
			) );

			$outputs = apply_filters( 'grimlock_hero_customizer_button_color_outputs', array(
				$this->get_css_var_output( 'hero_button_color' ),
				array(
					'element'  => implode( ',', $elements ),
					'property' => 'color',
				),
			) );

			$args = wp_parse_args( $args, array(
				'type'      => 'color',
				'label'     => esc_html__( 'Button Color', 'grimlock-hero' ),
				'section'   => $this->section,
				'settings'  => 'hero_button_color',
				'default'   => $this->get_default( 'hero_button_color' ),
				'choices'   => array(
					'alpha'    => true,
					'palettes' => apply_filters( 'grimlock_color_field_palettes', array() ),
				),
				'priority'  => 10,
				'transport' => 'postMessage',
				'output'    => $outputs,
				'js_vars'   => $this->to_js_vars( $outputs ),
			) );

			Kirki::add_field( 'grimlock', apply_filters( 'grimlock_hero_customizer_button_color_field_args', $args ) );
		}
	}

	/**
	 * Add a Kirki color field to set the button_border color in the Customizer.
	 *
	 * @param array $args
	 * @since 1.0.0
	 */
	protected function add_button_border_color_field( $args = array() ) {
		if ( class_exists( 'Kirki' ) ) {
			$elements = apply_filters( 'grimlock_hero_customizer_button_color_elements', array(
				'.grimlock-hero .section__btn',
				'.grimlock-hero .section__content [type="submit"]',
				'.grimlock-hero .section__content [type="button"]',
				'.grimlock-hero .section__content #wp-submit',
			) );

			$outputs = apply_filters( 'grimlock_hero_customizer_button_color_outputs', array(
				$this->get_css_var_output( 'hero_button_border_color' ),
				array(
					'element'  => implode( ',', $elements ),
					'property' => 'border-color',
				),
			) );

			$args = wp_parse_args( $args, array(
				'type'      => 'color',
				'label'     => esc_html__( 'Button Border Color', 'grimlock-hero' ),
				'section'   => $this->section,
				'settings'  => 'hero_button_border_color',
				'default'   => $this->get_default( 'hero_button_border_color' ),
				'choices'   => array(
					'alpha'    => true,
					'palettes' => apply_filters( 'grimlock_color_field_palettes', array() ),
				),
				'priority'  => 10,
				'transport' => 'postMessage',
				'output'    => $outputs,
				'js_vars'   => $this->to_js_vars( $outputs ),
			) );

			Kirki::add_field( 'grimlock', apply_filters( 'grimlock_hero_customizer_button_border_color_field_args', $args ) );
		}
	}

	/**
	 * Add a Kirki color field to set the button_hover_background color in the Customizer.
	 *
	 * @param array $args
	 * @since 1.0.0
	 */
	protected function add_button_hover_background_color_field( $args = array() ) {
		if ( class_exists( 'Kirki' ) ) {
			$elements = apply_filters( 'grimlock_hero_customizer_button_hover_background_color_elements', array(
				'.grimlock-hero .section__btn:hover',
				'.grimlock-hero .section__btn:focus',
				'.grimlock-hero .section__content [type="submit"]:hover',
				'.grimlock-hero .section__content [type="submit"]:focus',
				'.grimlock-hero .section__content [type="button"]:hover',
				'.grimlock-hero .section__content #wp-submit',
				'.grimlock-hero .section__content [type="button"]:focus',
				'.grimlock-hero .section__content #wp-submit',
			) );

			$outputs = apply_filters( 'grimlock_hero_customizer_button_hover_background_color_outputs', array(
				$this->get_css_var_output( 'hero_button_hover_background_color' ),
				array(
					'element'  => implode( ',', $elements ),
					'property' => 'background-color',
				),
			) );

			$args = wp_parse_args( $args, array(
				'type'      => 'color',
				'label'     => esc_html__( 'Button Background Color on Hover', 'grimlock-hero' ),
				'section'   => $this->section,
				'settings'  => 'hero_button_hover_background_color',
				'default'   => $this->get_default( 'hero_button_hover_background_color' ),
				'choices'   => array(
					'alpha'    => true,
					'palettes' => apply_filters( 'grimlock_color_field_palettes', array() ),
				),
				'priority'  => 10,
				'transport' => 'postMessage',
				'output'    => $outputs,
				'js_vars'   => $this->to_js_vars( $outputs ),
			) );

			Kirki::add_field( 'grimlock', apply_filters( 'grimlock_hero_customizer_button_hover_background_color_field_args', $args ) );
		}
	}

	/**
	 * Add a Kirki color field to set the button hover color in the Customizer.
	 *
	 * @param array $args
	 * @since 1.0.0
	 */
	protected function add_button_hover_color_field( $args = array() ) {
		if ( class_exists( 'Kirki' ) ) {
			$elements = apply_filters( 'grimlock_hero_customizer_button_hover_color_elements', array(
				'.grimlock-hero .section__btn:hover',
				'.grimlock-hero .section__btn:focus',
				'.grimlock-hero .section__content [type="submit"]:hover',
				'.grimlock-hero .section__content [type="submit"]:focus',
				'.grimlock-hero .section__content [type="button"]:hover',
				'.grimlock-hero .section__content #wp-submit',
				'.grimlock-hero .section__content [type="button"]:focus',
				'.grimlock-hero .section__content #wp-submit',
			) );

			$outputs = apply_filters( 'grimlock_hero_customizer_button_hover_color_outputs', array(
				$this->get_css_var_output( 'hero_button_hover_color' ),
				array(
					'element'  => implode( ',', $elements ),
					'property' => 'color',
				),
			) );

			$args = wp_parse_args( $args, array(
				'type'      => 'color',
				'label'     => esc_html__( 'Button Color on Hover', 'grimlock-hero' ),
				'section'   => $this->section,
				'settings'  => 'hero_button_hover_color',
				'default'   => $this->get_default( 'hero_button_hover_color' ),
				'choices'   => array(
					'alpha'    => true,
					'palettes' => apply_filters( 'grimlock_color_field_palettes', array() ),
				),
				'priority'  => 10,
				'transport' => 'postMessage',
				'output'    => $outputs,
				'js_vars'   => $this->to_js_vars( $outputs ),
			) );

			Kirki::add_field( 'grimlock', apply_filters( 'grimlock_hero_customizer_button_hover_color_field_args', $args ) );
		}
	}

	/**
	 * Add a Kirki color field to set the button_hover_border color in the Customizer.
	 *
	 * @param array $args
	 * @since 1.0.0
	 */
	protected function add_button_hover_border_color_field( $args = array() ) {
		if ( class_exists( 'Kirki' ) ) {
			$elements = apply_filters( 'grimlock_hero_customizer_button_hover_border_color_elements', array(
				'.grimlock-hero .section__btn:hover',
				'.grimlock-hero .section__btn:focus',
				'.grimlock-hero .section__content [type="submit"]:hover',
				'.grimlock-hero .section__content [type="submit"]:focus',
				'.grimlock-hero .section__content [type="button"]:hover',
				'.grimlock-hero .section__content #wp-submit',
				'.grimlock-hero .section__content [type="button"]:focus',
				'.grimlock-hero .section__content #wp-submit',
			) );

			$outputs = apply_filters( 'grimlock_hero_customizer_button_hover_border_color_outputs', array(
				$this->get_css_var_output( 'hero_button_hover_border_color' ),
				array(
					'element'  => implode( ',', $elements ),
					'property' => 'border-color',
				),
			) );

			$args = wp_parse_args( $args, array(
				'type'      => 'color',
				'label'     => esc_html__( 'Button Border Color on Hover', 'grimlock-hero' ),
				'section'   => $this->section,
				'settings'  => 'hero_button_hover_border_color',
				'default'   => $this->get_default( 'hero_button_hover_border_color' ),
				'choices'   => array(
					'alpha'    => true,
					'palettes' => apply_filters( 'grimlock_color_field_palettes', array() ),
				),
				'priority'  => 10,
				'transport' => 'postMessage',
				'output'    => $outputs,
				'js_vars'   => $this->to_js_vars( $outputs ),
			) );

			Kirki::add_field( 'grimlock', apply_filters( 'grimlock_hero_customizer_button_hover_border_color_field_args', $args ) );
		}
	}

	/**
	 * Add a Kirki slider control to set the button size in the Customizer.
	 *
	 * @param array $args
	 * @since 1.0.0
	 */
	protected function add_button_size_field( $args = array() ) {
		if ( class_exists( 'Kirki' ) ) {
			$args = wp_parse_args( $args, array(
				'type'     => 'select',
				'section'  => $this->section,
				'label'    => esc_html__( 'Button Size', 'grimlock-hero' ),
				'settings' => 'hero_button_size',
				'default'  => $this->get_default( 'hero_button_size' ),
				'priority' => 10,
				'choices'  => array(
					'btn-sm' => esc_attr__( 'Small', 'grimlock-hero' ),
					' '      => esc_attr__( 'Regular', 'grimlock-hero' ),
					'btn-lg' => esc_attr__( 'Large', 'grimlock-hero' ),
				),
			) );

			Kirki::add_field( 'grimlock', apply_filters( 'grimlock_hero_customizer_button_size_field_args', $args ) );
		}
	}


	/**
	 * Add a Kirki checkbox field to activate the use of a gradient as background color for the Hero.
	 *
	 * @param array $args
	 * @since 1.0.0
	 */
	protected function add_background_gradient_displayed_field( $args = array() ) {
		if ( class_exists( 'Kirki') ) {
			$args = wp_parse_args( $args, array(
				'type'     => 'checkbox',
				'section'  => $this->section,
				'label'    => esc_html__( 'Add gradient to background color', 'grimlock-hero' ),
				'settings' => 'hero_background_gradient_displayed',
				'default'  => $this->get_default( 'hero_background_gradient_displayed' ),
				'priority' => 10,
			) );

			Kirki::add_field( 'grimlock', apply_filters( 'grimlock_hero_customizer_background_gradient_displayed_field_args', $args ) );
		}
	}

	/**
	 * Add a Kirki color field to set the first color of the background gradient in the Customizer.
	 *
	 * @param array $args
	 * @since 1.0.0
	 */
	protected function add_background_gradient_first_color_field( $args = array() ) {
		if ( class_exists( 'Kirki' ) ) {
			$args = wp_parse_args( $args, array(
				'type'      => 'color',
				'label'     => esc_html__( 'Background Gradient First Color', 'grimlock-hero' ),
				'section'   => $this->section,
				'settings'  => 'hero_background_gradient_first_color',
				'default'   => $this->get_default( 'hero_background_gradient_first_color' ),
				'choices'   => array(
					'alpha'    => true,
					'palettes' => apply_filters( 'grimlock_color_field_palettes', array() ),
				),
				'priority'  => 10,
				'transport' => 'refresh',
				'active_callback' => array(
					array(
						'setting'  => 'hero_background_gradient_displayed',
						'operator' => '==',
						'value'    => true,
					),
				),
			) );

			Kirki::add_field( 'grimlock', apply_filters( 'grimlock_hero_customizer_background_gradient_first_color_field_args', $args ) );
		}
	}

	/**
	 * Add a Kirki color field to set the second color of the background gradient in the Customizer.
	 *
	 * @param array $args
	 * @since 1.0.0
	 */
	protected function add_background_gradient_second_color_field( $args = array() ) {
		if ( class_exists( 'Kirki' ) ) {
			$args = wp_parse_args( $args, array(
				'type'      => 'color',
				'label'     => esc_html__( 'Background Gradient Second Color', 'grimlock-hero' ),
				'section'   => $this->section,
				'settings'  => 'hero_background_gradient_second_color',
				'default'   => $this->get_default( 'hero_background_gradient_second_color' ),
				'choices'   => array(
					'alpha'    => true,
					'palettes' => apply_filters( 'grimlock_color_field_palettes', array() ),
				),
				'priority'  => 10,
				'transport' => 'refresh',
				'active_callback' => array(
					array(
						'setting'  => 'hero_background_gradient_displayed',
						'operator' => '==',
						'value'    => true,
					),
				),
			) );

			Kirki::add_field( 'grimlock', apply_filters( 'grimlock_hero_customizer_background_gradient_second_color_field_args', $args ) );
		}
	}


	/**
	 * Add a Kirki radio-image field to set the layout in the Customizer.
	 *
	 * @param array $args
	 * @since 1.0.0
	 */
	protected function add_background_gradient_direction_field( $args = array() ) {
		if ( class_exists( 'Kirki' ) ) {
			$args = wp_parse_args( $args, array(
				'type'     => 'radio-image',
				'section'  => $this->section,
				'label'    => esc_html__( 'Background Gradient Direction', 'grimlock' ),
				'settings' => 'hero_background_gradient_direction',
				'default'  => $this->get_default( 'hero_background_gradient_direction' ),
				'priority' => 10,
				'choices'  => array(
					'315deg' => GRIMLOCK_PLUGIN_DIR_URL . 'assets/images/gradient-direction-315.png',
					'0deg'   => GRIMLOCK_PLUGIN_DIR_URL . 'assets/images/gradient-direction-0.png',
					'45deg'  => GRIMLOCK_PLUGIN_DIR_URL . 'assets/images/gradient-direction-45.png',
					'270deg' => GRIMLOCK_PLUGIN_DIR_URL . 'assets/images/gradient-direction-270.png',
					'360deg' => GRIMLOCK_PLUGIN_DIR_URL . 'assets/images/gradient-direction-360.png',
					'90deg'  => GRIMLOCK_PLUGIN_DIR_URL . 'assets/images/gradient-direction-90.png',
					'225deg' => GRIMLOCK_PLUGIN_DIR_URL . 'assets/images/gradient-direction-225.png',
					'180deg' => GRIMLOCK_PLUGIN_DIR_URL . 'assets/images/gradient-direction-180.png',
					'135deg' => GRIMLOCK_PLUGIN_DIR_URL . 'assets/images/gradient-direction-135.png',
				),
				'transport' => 'refresh',
				'active_callback' => array(
					array(
						'setting'  => 'hero_background_gradient_displayed',
						'operator' => '==',
						'value'    => true,
					),
				),
			) );

			Kirki::add_field( 'grimlock', apply_filters( 'grimlock_hero_customizer_background_gradient_direction_field_args', $args ) );
		}
	}

	/**
	 * Add a Kirki slider field to set the background gradient position in the Customizer.
	 *
	 * @param array $args
	 * @since 1.0.0
	 */
	protected function add_background_gradient_position_field( $args = array() ) {
		if ( class_exists( 'Kirki' ) ) {
			$args = wp_parse_args( $args, array(
				'type'      => 'slider',
				'section'   => $this->section,
				'label'     => esc_attr__( 'Background Gradient Position', 'grimlock-hero' ),
				'settings'  => 'hero_background_gradient_position',
				'default'   => $this->get_default( 'hero_background_gradient_position' ),
				'choices'   => array(
					'min'   => -100,
					'max'   => 100,
					'step'  => 1,
				),
				'priority'  => 10,
				'transport' => 'refresh',
				'active_callback' => array(
					array(
						'setting'  => 'hero_background_gradient_displayed',
						'operator' => '==',
						'value'    => true,
					),
				),
			) );

			Kirki::add_field( 'grimlock', apply_filters( 'grimlock_hero_customizer_background_gradient_position_field_args', $args ) );
		}
	}

	/**
	 *
	 *
	 * @param WP_Customize_Manager $wp_customize
	 */
	public function add_partial( $wp_customize ) {
		$wp_customize->selective_refresh->add_partial( 'hero_partial', array(
				'selector' => '#hero .region__col--2',
				'settings' => array( 'hero_title' ),
			)
		);
	}

	/**
	 * Add custom styles based on theme mods.
	 *
	 * @param string $styles The styles printed by Kirki
	 *
	 * @since 1.0.0
	 */
	public function add_dynamic_css( $styles ) {
		$background_gradient_displayed = $this->get_theme_mod( 'hero_background_gradient_displayed' );
		$hero_font                     = $this->get_theme_mod( 'hero_title_font' );
		$hero_subtitle_font            = $this->get_theme_mod( 'hero_subtitle_font' );

		$styles .= "
		@media screen and (max-width: 992px) {
			.grimlock-hero .section__title { font-size: calc( ({$hero_font['font-size']}) / 1.3); }
			.grimlock-hero .section__subtitle { font-size: calc( ({$hero_subtitle_font['font-size']}) / 1.2); }
		}
		@media screen and (max-width: 576px) {
			.grimlock-hero .section__title { font-size: calc( ({$hero_font['font-size']}) / 1.85); }
			.grimlock-hero .section__subtitle { font-size: calc( ({$hero_subtitle_font['font-size']}) / 1.2); }
		}";

		if ( !empty( $background_gradient_displayed ) ) {
			$background_gradient_first_color  = $this->get_theme_mod( 'hero_background_gradient_first_color' );
			$background_gradient_second_color = $this->get_theme_mod( 'hero_background_gradient_second_color' );
			$background_gradient_direction    = $this->get_theme_mod( 'hero_background_gradient_direction' );
			$background_gradient_position     = $this->get_theme_mod( 'hero_background_gradient_position' );

			$styles .= "
			.grimlock-hero > .region__inner {
				background: linear-gradient({$background_gradient_direction}, {$background_gradient_second_color} {$background_gradient_position}%, {$background_gradient_first_color} 100%)
			}";

		}

		return $styles;
	}

	/**
	 * Add scripts to improve user experience in the customizer
	 */
	public function add_scripts() {
		?>
		<script type="text/javascript">
            jQuery( document ).ready( function( $ ) {
                wp.customize.section( '<?php echo esc_js( $this->section ); ?>', function( section ) {
                    section.expanded.bind( function( isExpanded ) {
                        var previewUrl = '<?php echo esc_js( trailingslashit( home_url() ) ); ?>';
                        if ( isExpanded && wp.customize.previewer.previewUrl.get() !== previewUrl ) {
                            wp.customize.previewer.previewUrl.set( previewUrl );
                        }
                    } );
                } );
            } );
		</script>
		<?php
	}
}

return new Grimlock_Hero_Customizer();
