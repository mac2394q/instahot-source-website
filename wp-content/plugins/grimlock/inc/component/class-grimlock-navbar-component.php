<?php
/**
 * Grimlock_Navbar_Component Class
 *
 * @author  Themosaurus
 * @since   1.0.0
 * @package  grimlock
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Grimlock_Navbar_Component
 */
class Grimlock_Navbar_Component extends Grimlock_Component {
	/**
	 * Grimlock_Navbar_Component constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param array $props
	 */
	public function __construct( $props = array() ) {
		parent::__construct( wp_parse_args( $props, array(
			'search_form_displayed' => true,
			'layout'                => '',
			'container_layout'      => '',
			'class'                 => '',
		) ) );
	}

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
		$classes[] = 'grimlock-navbar';
		$classes[] = 'navbar';

		if ( ! empty( $this->props['layout'] ) ) {
			$classes[] = "navbar--{$this->props['layout']}";
		}

		if ( ! empty( $this->props['container_layout'] ) ) {
			$classes[] = "navbar--container-{$this->props['container_layout']}";
		}

		return array_unique( $classes );
	}

	/**
	 * Get inline border styles as property-values pairs for the component using props.
	 *
	 * @since 1.0.0
	 */
	public function get_border_style() {
		$styles = array();
		$styles['border-top-color']    = ! empty( $this->props['border_top_color'] ) ? esc_attr( $this->props['border_top_color'] ) : 'transparent';
		$styles['border-top-style']    = 'solid';
		$styles['border-top-width']    = ! empty( $this->props['border_top_width'] ) ? intval( $this->props['border_top_width'] ) . 'px' : 0;
		$styles['border-bottom-color'] = ! empty( $this->props['border_bottom_color'] ) ? esc_attr( $this->props['border_bottom_color'] ) : 'transparent';
		$styles['border-bottom-style'] = 'solid';
		$styles['border-bottom-width'] = ! empty( $this->props['border_bottom_width'] ) ? intval( $this->props['border_bottom_width'] ) . 'px' : 0;
		return $styles;
	}

	/**
	 * Output the navbar toggler.
	 *
	 * @since 1.0.0
	 */
	protected function render_toggler() {
		?>
        <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="<?php echo "#{$this->props['id']}-collapse"; ?>" aria-controls="<?php echo "{$this->props['id']}-collapse"; ?>" aria-expanded="false" aria-label="Toggle navigation">
            <span></span>
        </button>
		<?php
	}

	/**
	 * Output the navbar brand.
	 *
	 * @since 1.0.0
	 */
	protected function render_brand() {
		?>
        <div class="navbar-brand">
            <?php do_action( 'grimlock_site_identity' ); ?>
        </div><!-- .navbar-brand -->
		<?php
	}

	/**
	 * Output the navbar nav menu.
	 *
	 * @since 1.0.0
	 */
	protected function render_nav_menu() {
		do_action( 'grimlock_navbar_nav_menu', array(
			'container'  => false,
			'menu_class' => 'nav navbar-nav navbar-nav--main-menu',
		) );
	}

	/**
	 * Output the navbar collapsible search form.
	 *
	 * @since 1.0.0
	 */
	protected function render_search_form() {
		if ( true == $this->props['search_form_displayed'] ) : ?>
            <ul class="nav navbar-nav navbar-nav--search">
                <li class="menu-item">
                    <div class="navbar-search navbar-search--animate">
                        <?php get_search_form(); ?>
                        <span class="search-icon "><i class="fa fa-search"></i></span>
                    </div><!-- .navbar-search -->
                </li>
            </ul>
			<?php
		endif;
	}

	/**
	 * Display the current component with props data on page.
	 *
	 * @since 1.0.0
	 */
	public function render() {
		if ( $this->is_displayed() ) : ?>
			<<?php $this->render_el(); ?> <?php $this->render_id(); ?> <?php $this->render_class( 'navbar-expand-lg' ); ?> <?php $this->render_style(); ?> <?php $this->render_role(); ?>>
			<div class="navbar__container">
				<div class="navbar__header">
					<?php
					$this->render_toggler();
					$this->render_brand(); ?>
				</div><!-- .navbar__header -->
				<div class="collapse navbar-collapse" id="<?php echo "{$this->props['id']}-collapse"; ?>">
					<div class="navbar-collapse-content">
						<?php
						$this->render_nav_menu();
						$this->render_search_form(); ?>
					</div>
				</div><!-- .collapse -->
			</div><!-- .navbar__container -->
			</<?php $this->render_el(); ?>><!-- .navbar -->
		<?php endif;
	}
}
