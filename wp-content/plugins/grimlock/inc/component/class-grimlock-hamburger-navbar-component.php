<?php
/**
 * Grimlock_Hamburger_Navbar_Component Class
 *
 * @author  Themosaurus
 * @since   1.0.0
 * @package  grimlock
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Grimlock_Hamburger_Navbar_Component
 */
class Grimlock_Hamburger_Navbar_Component extends Grimlock_Navbar_Component {
	/**
	 * Retrieve the classes for the component as an array.
	 *
	 * @since 1.0.0
	 *
	 * @param string|array $class One or more classes to add to the class list.
	 * @return array Array of classes.
	 */
	public function get_class( $class = '' ) {
		$classes   = parent::get_class( $class );
		$classes[] = 'grimlock-navbar--hamburger';
		$classes[] = 'hamburger-navbar';
		return array_unique( $classes );
	}

	/**
	 * Output the navbar toggler.
	 *
	 * @since 1.0.0
	 */
	protected function render_toggler() {
		?>
        <button id="navbar-toggler" class="navbar-toggler navbar-toggler-right collapsed" type="button" data-toggle="collapse" data-target="<?php echo "#{$this->props['id']}-collapse"; ?>">
            <span></span>
        </button>
		<?php
	}

	/**
	 * Output the navbar nav menu.
	 *
	 * @since 1.0.0
	 */
	protected function render_nav_menu() {
		do_action( 'grimlock_hamburger_navbar_nav_menu', array(
			'container'  => false,
			'menu_class' => 'hamburger-navbar-nav nav navbar-nav navbar-nav--hamburger-menu',
		) );
	}

	/**
	 * Render the current component with props data on page.
	 *
	 * @since 1.0.0
	 */
	public function render() {
		if ( $this->is_displayed() ) : ?>
			<<?php $this->render_el(); ?> <?php $this->render_id(); ?> <?php $this->render_class( 'navbar-full' ); ?> <?php $this->render_style(); ?> <?php $this->render_role(); ?>>
			<div class="navbar__container">
				<div class="navbar__header">
					<?php
					$this->render_toggler();
					$this->render_brand(); ?>
				</div><!-- .navbar__header -->
				<div class="hamburger-navbar-nav-menu-container d-none">
					<?php $this->render_nav_menu(); ?>
				</div><!-- .collapse -->
			</div><!-- .navbar__container -->
			</<?php $this->render_el(); ?>><!-- .hamburger-navbar -->
		<?php endif;
	}
}
