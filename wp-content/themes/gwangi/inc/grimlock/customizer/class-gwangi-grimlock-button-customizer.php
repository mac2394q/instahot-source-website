<?php
/**
 * Gwangi_Grimlock_Button_Customizer Class
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
 * The button class for the Customizer.
 */
class Gwangi_Grimlock_Button_Customizer {
	/**
	 * Setup class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_filter( 'grimlock_button_customizer_defaults',                            array( $this, 'change_defaults'                         ), 10, 1 );
		add_filter( 'grimlock_button_customizer_elements',                            array( $this, 'add_elements'                            ), 10, 1 );
		add_filter( 'grimlock_button_customizer_primary_elements',                    array( $this, 'add_primary_elements'                    ), 10, 1 );
		add_filter( 'grimlock_button_customizer_secondary_elements',                  array( $this, 'add_secondary_elements'                  ), 10, 1 );
		add_filter( 'grimlock_button_customizer_primary_background_color_elements',   array( $this, 'add_primary_background_color_elements'   ), 10, 1 );
		add_filter( 'grimlock_button_customizer_primary_background_color_outputs',    array( $this, 'add_primary_background_color_outputs'    ), 10, 1 );
		add_filter( 'grimlock_button_customizer_secondary_background_color_elements', array( $this, 'add_secondary_background_color_elements' ), 10, 1 );
		add_filter( 'grimlock_button_customizer_primary_color_elements',              array( $this, 'add_primary_color_elements'              ), 10, 1 );
		add_filter( 'grimlock_button_customizer_primary_color_outputs',               array( $this, 'add_primary_color_outputs'               ), 10, 1 );
		add_filter( 'grimlock_button_customizer_border_radius_elements',              array( $this, 'add_border_radius_elements'              ), 10, 1 );
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
		$defaults['button_font']          = array(
			'font-family'    => GWANGI_FONT_FAMILY_BASE,
			'font-weight'    => GWANGI_BUTTON_FONT_VARIANT,
			'font-size'      => GWANGI_BUTTON_FONT_SIZE,
			'line-height'    => GWANGI_BUTTON_LINE_HEIGHT,
			'letter-spacing' => GWANGI_BUTTON_FONT_LETTER_SPACING,
			'subsets'        => array( 'latin-ext' ),
			'text-transform' => GWANGI_BUTTON_FONT_TEXT_TRANSFORM,
		);
		$defaults['button_border_radius'] = GWANGI_BUTTON_BORDER_RADIUS;
		$defaults['button_border_width']  = GWANGI_BUTTON_BORDER_WIDTH;
		$defaults['button_padding_y']     = GWANGI_BUTTON_PADDING_Y;
		$defaults['button_padding_x']     = GWANGI_BUTTON_PADDING_X;

		$defaults['button_primary_background_color']       = GWANGI_BUTTON_PRIMARY_BACKGROUND_COLOR;
		$defaults['button_primary_color']                  = GWANGI_BUTTON_PRIMARY_COLOR;
		$defaults['button_primary_border_color']           = GWANGI_BUTTON_PRIMARY_BORDER_COLOR;
		$defaults['button_primary_hover_background_color'] = GWANGI_BUTTON_PRIMARY_HOVER_BACKGROUND_COLOR;
		$defaults['button_primary_hover_color']            = GWANGI_BUTTON_PRIMARY_HOVER_COLOR;
		$defaults['button_primary_hover_border_color']     = GWANGI_BUTTON_PRIMARY_HOVER_BORDER_COLOR;

		$defaults['button_secondary_background_color']       = GWANGI_BUTTON_SECONDARY_BACKGROUND_COLOR;
		$defaults['button_secondary_color']                  = GWANGI_BUTTON_SECONDARY_COLOR;
		$defaults['button_secondary_border_color']           = GWANGI_BUTTON_SECONDARY_BORDER_COLOR;
		$defaults['button_secondary_hover_background_color'] = GWANGI_BUTTON_SECONDARY_HOVER_BACKGROUND_COLOR;
		$defaults['button_secondary_hover_color']            = GWANGI_BUTTON_SECONDARY_HOVER_COLOR;
		$defaults['button_secondary_hover_border_color']     = GWANGI_BUTTON_SECONDARY_HOVER_BORDER_COLOR;
		return $defaults;
	}

	/**
	 * Add CSS selectors to the array of CSS selectors for the button.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $elements The array of CSS selectors for the button.
	 *
	 * @return array           The updated array of CSS selectors for the button.
	 */
	public function add_elements( $elements ) {
		return array_merge( $elements, array(
			'.btn-selector',
			'#sb_instagram #sbi_load .sbi_load_btn',
			'#sb_instagram .sbi_follow_btn a',
			'.vertical-navbar .navbar-nav.navbar-nav--login .menu-item > a.btn',
			'.vertical-navbar .navbar-nav--login .menu-item .btn',
		) );
	}

	/**
	 * Add CSS selectors to the array of CSS selectors for the secondary button.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $elements The array of CSS selectors for the secondary button.
	 *
	 * @return array           The updated array of CSS selectors for the secondary button.
	 */
	public function add_secondary_elements( $elements ) {
		return array_merge( $elements, array(
			'.grimlock-section .section__title ins[class*="decoration--block"].decoration--inverse',
			'.grimlock-section .section__subtitle ins[class*="decoration--block"].decoration--inverse',
		) );
	}

	/**
	 * Add CSS selectors to the array of CSS selectors for the secondary button background-color.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $elements The array of CSS selectors for the secondary button background-color.
	 *
	 * @return array           The updated array of CSS selectors for the secondary button background-color.
	 */
	public function add_secondary_background_color_elements( $elements ) {
		return array_merge( $elements, array(
			'.grimlock-section .section__title ins.decoration--secondary:after',
			'.grimlock-section .section__subtitle ins.decoration--secondary:after',
		) );
	}

	/**
	 * Add CSS selectors to the array of CSS selectors for the primary button.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $elements The array of CSS selectors for the primary button.
	 *
	 * @return array           The updated array of CSS selectors for the primary button.
	 */
	public function add_primary_elements( $elements ) {
		return array_merge( $elements, array(
			'.btn-primary-selector',
			'.ptp-pricing-table .ptp-col .ptp-item-container .ptp-plan',
			'.login-submit > #wp-submit',
			'.grimlock-section .section__title ins[class*="decoration--block"]',
			'.grimlock-section .section__subtitle ins[class*="decoration--block"]',
			'.vertical-navbar .navbar-nav.navbar-nav--login .menu-item > a.btn.btn-primary',
		) );
	}

	/**
	 * Add CSS selectors to the array of CSS selectors for the primary button color.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $elements The array of CSS selectors for the primary button color.
	 *
	 * @return array           The updated array of CSS selectors for the primary button color.
	 */
	public function add_primary_color_elements( $elements ) {
		return array_merge( $elements, array(
			'.ptp-pricing-table .ptp-col .ptp-item-container .ptp-plan',
			'.select2-container--default .select2-results__option--highlighted[aria-selected]',
			'.select2-container--default .select2-results__option[aria-selected=true]',
			'.datepicker table tr td.active.active',
			'.datepicker table tr td.active.disabled',
			'.datepicker table tr td.active.disabled.active',
			'.datepicker table tr td.active.disabled.disabled',
			'.datepicker table tr td.active.disabled:active',
			'.datepicker table tr td.active.disabled:hover',
			'.datepicker table tr td.active:active',
			'.datepicker table tr td.active:hover',
			'.datepicker table tr td.active:hover[disabled]',
			'.datepicker table tr td.active[disabled]',
		) );
	}

	/**
	 * Add CSS selectors to the array of CSS selectors for the button border radius.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $elements The array of CSS selectors for the button border radius.
	 *
	 * @return array           The updated array of CSS selectors for the button border radius.
	 */
	public function add_border_radius_elements( $elements ) {
		return array_merge( $elements, array(
			'.posts-filters .posts-filter .nav-link',
			'.modal .login-footer a',
			'.yza-form-actions > a, .yza-form-actions > button',
		) );
	}

	/**
	 * Add selectors and properties to the CSS rule-set for the primary button color.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $outputs The array of CSS selectors and properties for the primary button color.
	 *
	 * @return array          The updated array of CSS selectors for the primary button color.
	 */
	public function add_primary_color_outputs( $outputs ) {
		return array_merge( $outputs, array(
			array(
				'element'  => implode( ',', array(
					'.screen-reader-text:active',
					'.screen-reader-text:focus',
				) ),
				'property' => 'background-color',
			),
			array(
				'element'  => implode( ',', array(
					'.posts [id^="post-"].format-link .card-body a',
				) ),
				'property' => 'color',
				'suffix'   => '!important',
			),
		) );
	}

	/**
	 * Add CSS selectors to the array of CSS selectors for the primary button background color.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $elements The array of CSS selectors for the primary button background color.
	 *
	 * @return array           The updated array of CSS selectors for the primary button background color.
	 */
	public function add_primary_background_color_elements( $elements ) {
		return array_merge( $elements, array(
			'.btn.btn-link:hover:after',
			'.btn.btn-link:active:after',
			'.btn.btn-link.active:after',
			'.btn.btn-link:focus:after',
			'.grimlock-nav-menu-section .menu .menu-item.primary i',
			'.select2-container--default .select2-results__option[aria-selected=true]',
			'.grimlock--custom_header-displayed:not(.grimlock--hero-displayed) .site-header > .region__inner:after',
			'.main-navigation .navbar-nav.navbar-nav--buddypress.logged-out .menu-item--profile:hover:after',
			'.grimlock-hero.region--12-cols-center-boxed .section__content:before',
			'.grimlock-section .section__title ins:after',
			'.grimlock-section .section__subtitle ins:after',
			'.mejs-controls .mejs-time-rail .mejs-time-current',
			'.grimlock-section:not(.grimlock-hero):not(.grimlock-custom_header).section--full-viewport',
			'#custom_header',
			'.custom-checkbox .custom-control-input:checked ~ .custom-control-label::before',
			'.posts [id^="post-"].format-link .card-body',
			'.grimlock .parallax-mirror',
		) );
	}

	/**
	 * Add selectors and properties to the CSS rule-set for the primary button background color.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $outputs The array of CSS selectors and properties for the primary button background color.
	 *
	 * @return array          The updated array of CSS selectors for the primary button background color.
	 */
	public function add_primary_background_color_outputs( $outputs ) {
		return array_merge( $outputs, array(
			array(
				'element'  => implode( ',', array(
					// Gutenberg
					'.wp-block-button.is-style-outline .wp-block-button__link:not(.has-background)',
					'.wp-block-button.is-style-outline .wp-block-button__link:not(.has-background):active',
					'.wp-block-button.is-style-outline .wp-block-button__link:not(.has-background):focus',
					'.wp-block-button.is-style-outline .wp-block-button__link:not(.has-background):hover',
					'.wp-block-pullquote',
				) ),
				'property' => 'border-color',
				'suffix'   => '!important',
			),
			array(
				'element'  => implode( ',', array(
					'.card .author img:hover',
					'.blog-posts .sticky .card',
					'.archive-posts .sticky .card',
					'.custom-control.custom-checkbox:hover .custom-control-label:before',
				) ),
				'property' => 'border-color',
			),
			array(
				'element'  => implode( ',', array(
					'.btn.btn-link:hover',
					'.btn.btn-link:active',
					'.btn.btn-link:focus',
					'.screen-reader-text:active',
					'.screen-reader-text:focus',
					'.grimlock-section:not(.grimlock-hero):not(.grimlock-custom_header) .section__title ins:after',
					'.grimlock-section:not(.grimlock-hero):not(.grimlock-custom_header) .section__subtitle ins:after',
					'.grimlock-section .section__title ins.decoration--brush:before',
					'.grimlock-section .section__subtitle ins.decoration--brush:before',

					// Gutenberg
					'.wp-block-button.is-style-outline .wp-block-button__link:not(.has-background)',
					'.wp-block-button.is-style-outline .wp-block-button__link:not(.has-background):active',
					'.wp-block-button.is-style-outline .wp-block-button__link:not(.has-background):focus',
					'.wp-block-button.is-style-outline .wp-block-button__link:not(.has-background):hover',
				) ),
				'property' => 'color',
			),
			array(
				'element'  => implode( ',', array(
					'svg.morph',
				) ),
				'property' => 'fill',
				'suffix'   => '!important',
			),
			array(
				'element'       => implode( ',', array(
					'.blog-posts .sticky .card',
					'.archive-posts .sticky .card',
				) ),
				'property'      => 'box-shadow',
				'value_pattern' => '0 0 0 2px $',
			),
			array(
				'element'  => implode( ',', array(
					'.select2-container--default .select2-results__option--highlighted[aria-selected]',
				) ),
				'property' => 'background-color',
				'suffix'   => '!important',
			),
		) );
	}




}

return new Gwangi_Grimlock_Button_Customizer();
