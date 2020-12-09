<?php
/**
 * Gwangi_Grimlock_Navigation_Customizer Class
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
class Gwangi_Grimlock_Navigation_Customizer {
	/**
	 * Setup class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_filter( 'grimlock_navigation_customizer_defaults',                                   array( $this, 'change_defaults'                                ), 10, 1 );
		add_filter( 'grimlock_navigation_customizer_menu_item_color_outputs',                    array( $this, 'add_menu_item_color_outputs'                    ), 10, 1 );
		add_filter( 'grimlock_navigation_customizer_background_color_outputs',                   array( $this, 'add_background_color_outputs'                   ), 10, 1 );
		add_filter( 'grimlock_navigation_customizer_stick_to_top_background_color_elements',     array( $this, 'remove_stick_to_top_background_color_elements'  ), 10, 1 );
		add_filter( 'grimlock_navigation_customizer_border_bottom_width_outputs',                array( $this, 'add_border_bottom_width_outputs'                ), 10, 1 );
		add_filter( 'grimlock_navigation_customizer_border_bottom_color_outputs',                array( $this, 'add_border_bottom_color_outputs'                ), 10, 1 );
		add_filter( 'grimlock_navigation_customizer_menu_item_active_color_elements',            array( $this, 'add_menu_item_active_color_elements'            ), 10, 1 );
		add_filter( 'grimlock_navigation_customizer_menu_item_active_color_outputs',             array( $this, 'add_menu_item_active_color_outputs'             ), 10, 1 );
		add_filter( 'grimlock_navigation_customizer_menu_item_active_background_color_elements', array( $this, 'add_menu_item_active_background_color_elements' ), 10, 1 );
		add_filter( 'grimlock_navigation_customizer_sub_menu_item_background_color_outputs',     array( $this, 'add_sub_menu_item_background_color_outputs'     ), 10, 1 );
		add_filter( 'grimlock_navigation_customizer_sub_menu_item_color_outputs',                array( $this, 'add_sub_menu_item_color_outputs'                ), 10, 1 );
		add_filter( 'grimlock_navigation_customizer_font_elements',                              array( $this, 'add_font_elements'                              ), 10, 1 );
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
		$defaults['navigation_font'] = array(
			'font-family'    => GWANGI_NAVIGATION_FONT_FAMILY,
			'font-weight'    => GWANGI_NAVIGATION_FONT_WEIGHT,
			'font-size'      => GWANGI_NAVIGATION_FONT_SIZE,
			'line-height'    => GWANGI_NAVIGATION_LINE_HEIGHT,
			'letter-spacing' => GWANGI_NAVIGATION_LETTER_SPACING,
			'subsets'        => array( 'latin-ext' ),
			'text-transform' => GWANGI_NAVIGATION_TEXT_TRANSFORM,
		);

		$defaults['navigation_background_color']                  = GWANGI_NAVIGATION_BACKGROUND;
		$defaults['navigation_menu_item_color']                   = GWANGI_NAVIGATION_ITEM_COLOR;
		$defaults['navigation_menu_item_active_background_color'] = GWANGI_NAVIGATION_ITEM_ACTIVE_BACKGROUND_COLOR;
		$defaults['navigation_menu_item_active_color']            = GWANGI_NAVIGATION_ITEM_COLOR_ACTIVE;
		$defaults['navigation_sub_menu_item_color']               = GWANGI_NAVIGATION_SUB_MENU_ITEM_COLOR;
		$defaults['navigation_sub_menu_item_background_color']    = GWANGI_NAVIGATION_SUB_MENU_ITEM_BACKGROUND_COLOR;

		$defaults['navigation_border_bottom_width'] = GWANGI_NAVIGATION_BORDER_BOTTOM_WIDTH;
		$defaults['navigation_border_bottom_top']   = GWANGI_NAVIGATION_BORDER_TOP_WIDTH;
		$defaults['navigation_border_bottom_color'] = GWANGI_NAVIGATION_BORDER_COLOR;

		$defaults['navigation_layout']                        = GWANGI_NAVIGATION_LAYOUT;
		$defaults['navigation_position']                      = GWANGI_NAVIGATION_POSITION;
		$defaults['navigation_container_layout']              = GWANGI_NAVIGATION_CONTAINER_LAYOUT;
		$defaults['navigation_padding_y']                     = GWANGI_NAVIGATION_PADDING_Y;
		$defaults['navigation_stick_to_top']                  = GWANGI_NAVIGATION_STICK_TO_TOP;
		$defaults['navigation_stick_to_top_background_color'] = GWANGI_NAVIGATION_STICK_TO_TOP_BACKGROUND;
		$defaults['navigation_mobile_background_color']       = GWANGI_NAVIGATION_MOBILE_BACKGROUND;

		$defaults['navigation_search_form_displayed']               = GWANGI_NAVIGATION_SEARCH_FORM_DISPLAYED;
		$defaults['navigation_search_form_color']                   = GWANGI_NAVIGATION_SEARCH_FORM_COLOR;
		$defaults['navigation_search_form_placeholder_color']       = GWANGI_NAVIGATION_SEARCH_FORM_PLACEHOLDER_COLOR;
		$defaults['navigation_search_form_background_color']        = GWANGI_NAVIGATION_SEARCH_FORM_BACKGROUND_COLOR;
		$defaults['navigation_search_form_active_background_color'] = GWANGI_NAVIGATION_SEARCH_FORM_ACTIVE_BACKGROUND_COLOR;
		$defaults['navigation_search_form_active_color']            = GWANGI_NAVIGATION_SEARCH_FORM_ACTIVE_COLOR;

		return $defaults;
	}

	/**
	 * Add selectors and properties to the CSS rule-set for the navigation bottom border.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $outputs The array of CSS selectors and properties for the navigation bottom border.
	 *
	 * @return array          The updated array of CSS selectors for the navigation bottom border.
	 */
	public function add_border_bottom_width_outputs( $outputs ) {
		return array_merge( $outputs, array(
			array(
				'element'  => implode( ',', array(
					'.main-navigation .navbar-nav.navbar-nav--main-menu > .menu-item > a:before',
				) ),
				'property' => 'bottom',
				'prefix'   => '-',
				'units'    => 'px',
			),
			array(
				'element'  => implode( ',', array(
					'.main-navigation .navbar-nav.navbar-nav--woocommerce + .nav',
				) ),
				'property' => 'border-left-width',
				'units'    => 'px',
				'suffix'   => '!important',
			),
		) );
	}

	/**
	 * Add selectors and properties to the CSS rule-set for the navigation bottom border.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $outputs The array of CSS selectors and properties for the navigation bottom border.
	 *
	 * @return array          The updated array of CSS selectors for the navigation bottom border.
	 */
	public function add_border_bottom_color_outputs( $outputs ) {
		return array_merge( $outputs, array(
			array(
				'element'  => implode( ',', array(
					'.main-navigation .navbar-nav.navbar-nav--woocommerce + .nav',
				) ),
				'property' => 'border-left-color',
				'suffix'   => '!important',
			),
		) );
	}

	/**
	 * Remove CSS selectors from the array of CSS selectors for the sticky navigation background color.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $elements The array of CSS selectors for the sticky navigation background color.
	 *
	 * @return array           The updated array of CSS selectors for the sticky navigation background color.
	 */
	public function remove_stick_to_top_background_color_elements( $elements ) {
		$key = array_search( 'body:not(.grimlock--custom_header-displayed) .grimlock-navigation', $elements, true );

		if ( false !== $key ) {
			unset( $elements[ $key ] );
		}
		return $elements;
	}

	/**
	 * Add CSS selectors to the array of CSS selectors for the menu item active color.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $elements The array of CSS selectors for the menu item active color.
	 *
	 * @return array           The updated array of CSS selectors for the menu item active color.
	 */
	public function add_menu_item_active_color_elements( $elements ) {
		return array_merge( $elements, array(
			'.navbar-search.navbar-search--animate:not(.navbar-search--open) .search-icon:hover',
			'.navbar-search.navbar-search--animate:not(.navbar-search--open) .search-icon:active',
		) );
	}

	/**
	 * Add CSS selectors to the array of CSS selectors for the menu item active color.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $elements The array of CSS selectors for the menu item active color.
	 *
	 * @return array           The updated array of CSS selectors for the menu item active color.
	 */
	public function add_menu_item_active_background_color_elements( $elements ) {
		return array_merge( $elements, array(
			'.navbar-search.navbar-search--animate:not(.navbar-search--open) .search-icon:hover',
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
	public function add_background_color_outputs( $outputs ) {
		return array_merge( $outputs, array(
			array(
				'element'  => implode( ',', array(
					// Grimlock Login
					'#navigation .nav.navbar-nav.navbar-nav--login li.menu-item.menu-item--login .btn',
					'#navigation .nav.navbar-nav.navbar-nav--login li.menu-item.menu-item--register .btn',
				) ),
				'property' => 'background-color',
			),
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
	public function add_menu_item_color_outputs( $outputs ) {
		return array_merge( $outputs, array(
			array(
				'element'  => implode( ',', array(
					// Grimlock Login
					'#navigation .nav.navbar-nav.navbar-nav--login li.menu-item.menu-item--login .btn',
				) ),
				'property' => 'color',
			),
			array(
				'element'  => implode( ',', array(
					// Grimlock Login
					'#navigation .nav.navbar-nav.navbar-nav--login li.menu-item.menu-item--login .btn:hover',
				) ),
				'property' => 'border-color',
			),
			array(
				'element'  => implode( ',', array(
					// Grimlock Login
					'#navigation .nav.navbar-nav.navbar-nav--login li.menu-item.menu-item--login .btn',
				) ),
				'property' => 'border-color',
				'suffix'   => '33',
			),
			array(
				'element'  => implode( ',', array(
					// Grimlock Login
					'#navigation .nav.navbar-nav.navbar-nav--login li.menu-item.menu-item--login .btn:hover',
				) ),
				'property' => 'background-color',
				'suffix'   => '0d',
			),
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
	public function add_menu_item_active_color_outputs( $outputs ) {
		return array_merge( $outputs, array(
			array(
				'element'  => implode( ',', array(
					'.main-navigation .navbar-nav.navbar-nav--main-menu > .menu-item > a:before',
				) ),
				'property' => 'background-color',
			),
			array(
				'element'  => implode( ',', array(
					// Grimlock Login
					'#navigation .nav.navbar-nav.navbar-nav--login li.menu-item.menu-item--register .btn',
				) ),
				'property' => 'color',
			),
			array(
				'element'  => implode( ',', array(
					// Grimlock Login
					'#navigation .nav.navbar-nav.navbar-nav--login li.menu-item.menu-item--register .btn:hover',
				) ),
				'property' => 'border-color',
			),
			array(
				'element'  => implode( ',', array(
					// Grimlock Login
					'#navigation .nav.navbar-nav.navbar-nav--login li.menu-item.menu-item--register .btn',
				) ),
				'property' => 'border-color',
				'suffix'   => '59',
			),
			array(
				'element'  => implode( ',', array(
					// Grimlock Login
					'#navigation .nav.navbar-nav.navbar-nav--login li.menu-item.menu-item--register .btn',
				) ),
				'property' => 'background-color',
				'suffix'   => '1c',
			),
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
					'.dropdown-classic .dropdown-menu',
					'.dropdown-classic .dropdown-menu a',
				) ),
				'property' => 'background-color',
				'suffix'   => '!important',
			),
			array(
				'element'  => implode( ',', array(
					'.dropdown-classic .dropdown-menu a:hover',
				) ),
				'property' => 'color',
				'suffix'   => '!important',
			),
			array(
				'element'       => implode( ',', array(
					'.off-center-image:before',
				) ),
				'property'      => 'background-image',
				'value_pattern' => 'linear-gradient(-260deg, $ 15%, rgba(255,255,255,0) 100%)',
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
					'.dropdown-classic .dropdown-menu',
					'.dropdown-classic .dropdown-menu a',
				) ),
				'property' => 'color',
				'suffix'   => '!important',
			),
			array(
				'element'  => implode( ',', array(
					'.dropdown-classic .dropdown-menu a:hover',
				) ),
				'property' => 'background-color',
				'suffix'   => '!important',
			),
			array(
				'element'  => implode( ',', array(
					'.main-navigation .navbar-nav > .menu-item.mega-menu:not(.mega-menu--image-grid) > ul.sub-menu ul.sub-menu ul.sub-menu',
				) ),
				'property' => 'border-color',
				'suffix'   => '0f !important',
			),
		) );
	}


	/**
	 * Add CSS selectors to the array of CSS selectors for the navigation font.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $elements The array of CSS selectors for the navigation font.
	 *
	 * @return array           The updated array of CSS selectors for the navigation font.
	 */
	public function add_font_elements( $elements ) {
		return array_merge( $elements, array(
			'.navbar-nav--hamburger-menu .menu-item .btn',
			'.navbar-nav--login .menu-item .btn',
		) );
	}
}

return new Gwangi_Grimlock_Navigation_Customizer();
