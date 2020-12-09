<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package gwangi
 */

get_header(); ?>

	<div id="primary">
		<main id="main" class="site-main" role="main">

			<?php
			/**
			 * Functions hooked into gwangi_404 action
			 *
			 * @hooked gwangi_404          - 10
			 * @hooked gwangi_grimlock_404 - 10
			 */
			do_action( 'gwangi_404' ); ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
