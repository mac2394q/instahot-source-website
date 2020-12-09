<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class Grimlock_Custom_Header_Component
 *
 * @author  themosaurus
 * @since   1.0.0
 * @package grimlock/inc/components
 */
class Grimlock_Custom_Header_Component extends Grimlock_Section_Component {
	/**
	 * Setup class.
	 *
	 * @param array $props
	 * @since 1.0.0
	 */
	public function __construct( $props = array() ) {
		parent::__construct( wp_parse_args( $props, array(
			'before_title'   => '',
			'after_subtitle' => '',
		) ) );
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
				do_action( 'grimlock_custom_header_before_title', $this->props );
				$this->render_title( 'h1' );
				$this->render_subtitle( 'h2' );
				do_action( 'grimlock_custom_header_after_subtitle', $this->props );
				?>
			</div><!-- .section__header -->
		<?php endif;
	}

	/**
	 * Display the current component with props data on page.
	 *
	 * @since 1.0.0
	 */
	public function render() {
		if ( $this->is_displayed() ) : ?>
            <<?php $this->render_el(); ?> <?php $this->render_id(); ?> <?php $this->render_class(); ?> <?php $this->render_style(); ?> <?php $this->render_role(); ?>>
            <div class="region__inner" <?php $this->render_inner_style(); ?>>
                <div class="region__container">
                    <div class="region__row">
                        <div class="region__col region__col--1">
							<?php $this->render_thumbnail(); ?>
                        </div><!-- .region__col -->
                        <div class="region__col region__col--2">
							<?php $this->render_header(); ?>
                        </div><!-- .region__col -->
                    </div><!-- .region__row -->
                </div><!-- .region__container -->
            </div><!-- .region__inner -->
            </<?php $this->render_el(); ?>><!-- .grimlock-section -->
		<?php endif;
	}
}
