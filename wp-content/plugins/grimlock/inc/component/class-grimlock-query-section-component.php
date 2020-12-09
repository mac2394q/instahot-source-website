<?php

/**
 * Class Grimlock_Query_Section_Component
 *
 * @author  themosaurus
 * @since   1.0.0
 * @package grimlock-query/inc/components
 */
class Grimlock_Query_Section_Component extends Grimlock_Section_Component {
	/**
	 * Setup class.
	 *
	 * @param array $props
	 * @since 1.0.0
	 */
	public function __construct( $props = array() ) {
		parent::__construct( wp_parse_args( $props, array(
			'post_thumbnail_size' => 'large',
			'posts_layout'        => '12-cols-classic',
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
		$classes[] = 'grimlock-query-section';
		return array_unique( $classes );
	}

	/**
	 * Retrieve the classes for the query posts as an array.
	 *
	 * @since 1.0.0
	 *
	 * @param string|array $class One or more classes to add to the class list.
	 * @return array Array of classes.
	 */
	public function get_posts_class( $class = '' ) {
		$classes   = $this->parse_array( $class );
		$classes[] = 'grimlock-query-section__posts';
		$classes[] = 'posts';
		$classes[] = 'posts--height-equalized';
		$classes[] = "posts--{$this->props['posts_layout']}";

		if ( isset( $this->props['query'] ) && $this->props['query'] instanceof WP_Query ) {
			$classes[] = "posts--per-page-{$this->props['query']->get( 'posts_per_page' )}";
			$classes[] = "posts--type-{$this->props['query']->get( 'post_type' )}";
		}

		return array_unique( $classes );
	}

	/**
	 * Display the classes for the query posts.
	 *
	 * @since 1.0.0
	 *
	 * @param string|array $class One or more classes to add to the class list.
	 */
	public function render_posts_class( $class = '' ) {
		$classes = $this->get_posts_class( $class );
		$this->output_class( $classes );
	}

	/**
	 * Display the current component content.
	 *
	 * @since 1.0.0
	 */
	protected function render_content() {
		?>
		<div class="section__content">
			<?php
			$has_query = isset( $this->props['query'] ) && $this->props['query'] instanceof WP_Query;
			if ( $has_query && $this->props['query']->have_posts() ) : ?>
				<div <?php $this->render_posts_class(); ?>>
					<?php
					while ( $this->props['query']->have_posts() ) : $this->props['query']->the_post(); ?>
						<article id="post-<?php echo esc_attr( uniqid() ); ?>" <?php post_class(); ?>>
							<?php
							$post_type = get_post_type();

							if ( has_action( "grimlock_query_{$post_type}" ) ) :
								do_action( "grimlock_query_{$post_type}", $this->props );
							else :
								do_action( 'grimlock_query_post', $this->props );
							endif; ?>
						</article><!-- #post-## -->
					<?php
					endwhile;
					wp_reset_postdata(); ?>
				</div><!-- .grimlock-query-section__posts.posts -->
			<?php
			endif; ?>
		</div><!-- .section__content -->
		<?php
	}
}