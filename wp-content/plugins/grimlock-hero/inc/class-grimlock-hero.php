<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Grimlock_Hero
 *
 * @author  themosaurus
 * @since   1.0.0
 * @package grimlock-hero
 */
class Grimlock_Hero {
	/**
	 * Setup class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		load_plugin_textdomain( 'grimlock-hero', false, 'grimlock-hero/languages' );

		require_once GRIMLOCK_HERO_PLUGIN_DIR_PATH . 'inc/component/class-grimlock-hero-component.php';
		require_once GRIMLOCK_HERO_PLUGIN_DIR_PATH . 'inc/customizer/class-grimlock-hero-customizer.php';

		add_action( 'template_redirect', array( $this, 'change_custom_header' ), 10, 1 );
		add_filter( 'body_class',        array( $this, 'add_body_classes'     ), 10, 1 );
	}

	/**
	 * Change the Header by the Hero for the front page.
	 *
	 * @since 1.0.0
	 */
	public function change_custom_header() {
		if ( $this->has_hero_displayed() ) {
			global $grimlock;
			remove_action( 'grimlock_custom_header', array( $grimlock, 'custom_header' ), 10     );
			add_action(    'grimlock_custom_header', array( $this,     'custom_header' ), 10, 1 );
		}
	}

	/**
	 * Add custom classes to the HTML body tag.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $classes The array of classes for the HTML body tag.
	 *
	 * @return array          The updated array of classes for the HTML body tag.
	 */
	public function add_body_classes( $classes ) {
		if ( $this->has_hero_displayed() ) {
			$classes[] = 'grimlock--hero-displayed';
			$classes[] = 'grimlock--custom_header-displayed';
		}
		return $classes;
	}

	/**
	 * Check if the Hero is displayed or not.
	 *
	 * @since 1.0.4
	 *
	 * @return bool True if the Hero is displayed, false otherwise.
	 */
	public function has_hero_displayed() {
		return apply_filters( 'grimlock_hero_displayed', is_front_page() );
	}


	/**
	 * Display the Hero section in front page instead of the Custom Header.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args
	 */
	public function custom_header( $args ) {
		$args = apply_filters( 'grimlock_hero_args', wp_parse_args( $args, array(
			'id' => 'hero',
		) ) );
		$hero = new Grimlock_Hero_Component( $args );
		$hero->render();
	}
}