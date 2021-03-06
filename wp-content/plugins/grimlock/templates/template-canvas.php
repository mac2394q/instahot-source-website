<?php
/**
 * The template for displaying pages with no navigation, header or footer
 *
 * Template Name: Canvas
 *
 * This template can be overridden by copying it to yourtheme/grimlock/template-canvas.php.
 *
 * @package grimlock
 */

get_header(); ?>

	<div id="primary" class="content-area region__col">
		<main id="main" class="site-main">

			<?php
			/* Start the Loop */
			while ( have_posts() ) : the_post();

				get_template_part( 'template-parts/content', 'page' );

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // End of the loop.
			?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
