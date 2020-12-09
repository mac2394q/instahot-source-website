<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Grimlock_Author_Avatars
 *
 * @author  themoasaurus
 * @since   1.0.0
 * @package grimlock-author-avatars/inc
 */
class Grimlock_Author_Avatars {
	/**
	 * Setup class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		load_plugin_textdomain( 'grimlock-author-avatars', false, 'grimlock-author-avatars/languages' );

		require_once GRIMLOCK_AUTHOR_AVATARS_PLUGIN_DIR_PATH . 'inc/grimlock-author-avatars-template-functions.php';
		require_once GRIMLOCK_AUTHOR_AVATARS_PLUGIN_DIR_PATH . 'inc/grimlock-author-avatars-template-hooks.php';
		require_once GRIMLOCK_AUTHOR_AVATARS_PLUGIN_DIR_PATH . 'inc/component/class-grimlock-author-avatars-section-component.php';

		add_action( 'grimlock_author_avatars_section', array( $this, 'section' ), 10, 1 );

		require_once GRIMLOCK_AUTHOR_AVATARS_PLUGIN_DIR_PATH . 'inc/widget/class-grimlock-author-avatars-section-widget.php';
		require_once GRIMLOCK_AUTHOR_AVATARS_PLUGIN_DIR_PATH . 'inc/widget/fields/class-grimlock-author-avatars-section-widget-fields.php';

		add_action( 'widgets_init', array( $this, 'widgets_init' ), 10 );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10 );
	}

	/**
	 * Display the section with author avatars.
	 *
	 * @param array $args
	 * @since 1.0.0
	 */
	public function section( $args = array() ) {
		$component = new Grimlock_Author_Avatars_Section_Component( apply_filters( 'grimlock_author_avatars_section_args', $args ) );
		$component->render();
	}

	/**
	 * Register the custom widgets.
	 *
	 * @since 1.0.0
	 */
	public function widgets_init() {
		register_widget( 'Grimlock_Author_Avatars_Section_Widget' );
	}

	/**
	 * Enqueue scripts
	 *
	 * @since 1.0.5
	 */
	public function enqueue_scripts() {
		wp_enqueue_style( 'grimlock-author-avatars', GRIMLOCK_AUTHOR_AVATARS_PLUGIN_DIR_URL . 'assets/css/style.css', array(), GRIMLOCK_AUTHOR_AVATARS_VERSION );

		/*
		 * Load style-rtl.css instead of style.css for RTL compatibility
		 */
		wp_style_add_data( 'grimlock-author-avatars', 'rtl', 'replace' );
	}
}
