<?php
/**
 * The template for displaying image attachment.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#attachment
 *
 * @package gwangi
 */

get_header(); ?>

<div id="primary" class="content-area region__col region__col--2">
	<main id="main" class="site-main">
		<?php
		/* Start the Loop */
		while ( have_posts() ) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<nav id="image-navigation" class="navigation image-navigation">
					<div class="nav-links">
						<ul class="pager">
							<li class="nav-previous pager-prev"><?php previous_image_link( false, esc_html__( 'Previous Image', 'gwangi' ) ); ?></li>
							<li class="nav-next pager-next"><?php next_image_link( false, esc_html__( 'Next Image', 'gwangi' ) ); ?></li>
						</ul><!-- .pager -->
					</div><!-- .nav-links -->
				</nav><!-- .image-navigation -->

				<header class="entry-header">
					<?php
					do_action( 'gwangi_breadcrumb' );
					the_title( '<h1 class="entry-title">', '</h1>' ); ?>

					<div class="entry-meta h6">
						<?php gwangi_the_date(); ?>
						<span class="sep"> - </span>
						<?php
						$metadata = wp_get_attachment_metadata();
						if ( $metadata ) :
							printf( '<span class="full-size-link"><span class="screen-reader-text sr-only">%1$s </span><a target="_blank" href="%2$s">%3$s &times; %4$s</a></span>',
								esc_html_x( 'Full size', 'Used before full size attachment link.', 'gwangi' ),
								esc_url( wp_get_attachment_url() ),
								absint( $metadata['width'] ),
								absint( $metadata['height'] )
							);
						endif; ?>
					</div><!-- entry-meta -->
				</header><!-- .entry-header -->

				<div class="entry-content">

					<div class="entry-attachment">
						<figure class="figure w-100">
							<?php
							/**
							 * Filter the default gwangi image attachment size.
							 *
							 * @since 1.0.0
							 *
							 * @param string $image_size Image size. Default 'large'.
							 */
							$image_size = apply_filters( 'gwangi_attachment_size', 'large' );

							echo wp_get_attachment_image( get_the_ID(), 'large', false, array(
								'class' => 'img-fluid figure-img w-100',
							) ); ?>

							<figcaption class="figure-caption">
								<?php the_excerpt(); ?>
							</figcaption>
						</figure>
					</div><!-- .entry-attachment -->

					<?php
					the_content();
					wp_link_pages( array(
						'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'gwangi' ) . '</span>',
						'after'       => '</div>',
						'link_before' => '<span>',
						'link_after'  => '</span>',
						'pagelink'    => '<span class="screen-reader-text sr-only">' . esc_html__( 'Page', 'gwangi' ) . ' </span>%',
						'separator'   => '<span class="screen-reader-text sr-only">, </span>',
					) ); ?>
				</div><!-- .entry-content -->

				<footer class="entry-footer">
					<?php
					if ( get_edit_post_link() ) :
						edit_post_link(
							sprintf(
								/* translators: %s: Name of current post */
								esc_html__( 'Edit %s', 'gwangi' ),
								the_title( '<span class="screen-reader-text sr-only">"', '"</span>', false )
							),
							'<span class="edit-link">',
							'</span>'
						);
					endif; ?>
				</footer><!-- .entry-footer -->
			</article><!-- #post-## -->

			<?php
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
