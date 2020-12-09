<?php
/**
 * Grimlock_Hero_Component Class
 *
 * @author  Themosaurus
 * @since   1.0.0
 * @package grimlock
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The component to display the Grimlock Hero.
 */
class Grimlock_Hero_Component extends Grimlock_Section_Component {
	/**
	 * Setup class.
	 *
	 * @since 1.0.0
	 *
	 * @param array $props
	 */
	public function __construct( $props = array() ) {
		parent::__construct( wp_parse_args( $props, array(
			'full_screen_displayed' => false,
			'login_form_displayed'  => false,
			'search_form_displayed' => false,
			'title_format'          => 'display-1',
		) ) );
	}

	/**
	 * Retrieve the classes for the component as an array.
	 *
	 * @since 1.0.0
	 *
	 * @param string|array $class One or more classes to add to the class list.
	 *
	 * @return       array        Array of classes.
	 */
	public function get_class( $class = '' ) {
		$classes = parent::get_class( $class );

		if ( ! empty( $this->props['full_screen_displayed'] ) ) {
			$classes[] = 'grimlock-hero--full-screen-displayed';
		}

		return array_unique( $classes );
	}

	/**
	 * Display the login form
	 *
	 * @since 1.0.3
	 */
	protected function render_login_form() {
		if ( ! empty( $this->props['login_form_displayed'] ) && ! is_user_logged_in() ) : ?>
			<div class="section__login_form">
				<?php wp_login_form(); ?>
			</div>
		<?php endif;
	}

	/**
	 * Display the search form
	 *
	 * @since 1.0.3
	 */
	protected function render_search_form() {
		if ( ! empty( $this->props['search_form_displayed'] ) ) : ?>
			<div class="section__search_form">
				<?php get_search_form(); ?>
			</div>
		<?php endif;
	}

	/**
	 * Display the current component header.
	 *
	 * @since 1.0.0
	 */
	protected function render_header() {
		if ( $this->has_header() ) : ?>
			<div class="section__header">
				<?php
				do_action( 'grimlock_hero_before_title', $this->props );
				$this->render_title( 'h1' );
				$this->render_subtitle( 'h2' );
				do_action( 'grimlock_hero_after_subtitle', $this->props );
				?>
			</div><!-- .section__header -->
		<?php endif;
	}

	/**
	 * Display the current component content.
	 *
	 * @since 1.0.0
	 */
	protected function render_content() {
		if ( $this->has_content() ) : ?>
			<div class="section__content">
				<?php $this->render_text(); ?>
				<?php $this->render_login_form(); ?>
				<?php $this->render_search_form(); ?>
				<?php do_action( 'grimlock_hero_after_content', $this->props ); ?>
			</div><!-- .section__content -->
		<?php endif;
	}

	/**
	 * Check whether the content has to be displayed.
	 *
	 * @since 2.0.0
	 *
	 * @return bool True when the component content, false otherwise.
	 */
	protected function has_content() {
		return ! empty( $this->props['text_displayed'] ) || ! empty( $this->props['search_form_displayed'] ) || ! empty( $this->props['login_form_displayed'] ) || has_action( 'grimlock_hero_after_content' );
	}

	/**
	 * Display the current component footer.
	 *
	 * @since 1.0.0
	 */
	protected function render_footer() {
		if ( $this->has_footer() ) : ?>
			<div class="grimlock-section__footer section__footer">
				<?php
				$this->render_button();
				do_action( 'grimlock_hero_after_button', $this->props );
				?>
			</div><!-- .section__footer -->
		<?php
		endif;
	}
}
