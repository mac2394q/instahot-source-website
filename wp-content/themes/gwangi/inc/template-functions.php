<?php
/**
 * Gwangi template functions.
 *
 * @package gwangi
 */

if ( ! function_exists( 'gwangi_nav_menu_css_class' ) ) :
	/**
	 * Add CSS classes to default primary menu.
	 *
	 * @param array    $classes The CSS classes that are applied to the menu item's `<li>` element.
	 * @param WP_Post  $item    The current menu item.
	 * @param stdClass $args    An object of wp_nav_menu() arguments.
	 * @param int      $depth   Depth of menu item. Used for padding.
	 *
	 * @return array            The updated array of CSS classes applied to the menu item's `<li>` element.
	 */
	function gwangi_nav_menu_css_class( $classes, $item, $args, $depth ) {
		if ( 'primary' === $args->theme_location ) {
			$classes[] = 'list-inline-item';
		}
		return $classes;
	}
endif;

if ( ! function_exists( 'gwangi_header' ) ) :
	/**
	 * Prints HTML for the header.
	 *
	 * @since 1.0.0
	 */
	function gwangi_header() {
		?>
		<header id="header" class="site-header region">
			<div class="region__inner">
				<div class="region__container">

					<div class="main-navigation site-navigation navbar-expand-lg navbar--classic-center navbar--container-classic">
						<div class="navbar__container">
							<div class="navbar__header">
								<button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navigation-collapse" aria-controls="navigation-collapse" aria-expanded="false" aria-label="Toggle navigation">
									<span></span>
								</button><!-- .navbar-toggler -->
								<div class="navbar-brand">
									<div id="site_identity" class="site-branding">
										<h1 class="screen-reader-text"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
										<?php
										if ( function_exists( 'has_custom_logo' ) && function_exists( 'the_custom_logo' ) && has_custom_logo() ) :
											the_custom_logo();
										else : ?>
											<div id="site-title" class="site-title navbar-brand__title">
												<a class="site-title-link navbar-brand__title-link" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" target="_self"><?php bloginfo( 'name' ); ?></a>
											</div>
											<?php
											$description = get_bloginfo( 'description', 'display' );
											if ( $description || is_customize_preview() ) : ?>
												<small id="site-description" class="site-description navbar-brand__tagline"><?php echo esc_html( $description ); ?></small>
												<?php
											endif;
										endif; ?>
									</div><!-- .site-branding -->
								</div><!-- .navbar-brand -->
							</div><!-- .navbar-header -->
							<div class="collapse navbar-collapse" id="navigation-collapse">
								<?php
								if ( has_nav_menu( 'primary' ) ) :
									wp_nav_menu( array(
										'theme_location' => 'primary',
										'menu_id'        => 'primary-menu',
										'menu_class'     => 'nav navbar-nav navbar-nav--main-menu',
										'container'      => false,
									) );
								endif; ?>
								<ul class="nav navbar-nav navbar-nav--search ml-auto">
									<li class="menu-item">
										<div class="navbar-search navbar-search--animate">
											<?php get_search_form(); ?>
											<span class="search-icon"><i class="fa fa-search"></i></span>
										</div><!-- .navbar-search -->
									</li>
								</ul>
							</div><!-- .navbar-collapse -->
						</div><!-- .navbar__container -->
					</div><!-- .main-navigation-->

					<?php if ( ! is_singular( 'post' ) ) : ?>
						<div id="custom_header" class="custom_header region region--12-cols-center region--container-classic section" <?php gwangi_header_image_style(); ?>>
							<div class="region__inner">
								<div class="region__container">
									<div class="region__row">
										<div class="region__col region__col--2">
											<div class="section__header">
												<h2 class="section__title display-1">
													<?php
													if ( is_home() && is_front_page() ) :
														bloginfo( 'name' );
													elseif ( is_archive() ) :
														the_archive_title();
													elseif ( is_search() ) :
														/* translators: %s: The search query */
														printf( esc_html__( 'Search Results for: %s', 'gwangi' ), '<span>' . get_search_query() . '</span>' );
													elseif ( is_home() && ! is_front_page() || is_singular() ) :
														single_post_title();
													endif; ?>
												</h2>
											</div><!-- .section__header -->
										</div><!-- .region__col -->
									</div><!-- .region__row -->
								</div><!-- .region__container -->
							</div><!-- .region__inner -->
						</div><!-- #custom_header -->
					<?php endif; ?>

				</div><!-- .container -->
			</div><!-- .region__inner-->
		</header><!-- #header -->

		<div id="content" <?php gwangi_content_class(); ?> tabindex="-1">
			<div class="region__container">
				<div class="region__row">
		<?php
	}
endif;

if ( ! function_exists( 'gwangi_footer' ) ) :
	/**
	 * Prints footer in page.
	 */
	function gwangi_footer() {
		?>
					</div><!-- .region__row -->
			</div><!-- .region__container -->
		</div><!-- #content -->

		<div id="footer" class="site-footer region region--container-classic region--3-3-3-3-cols-left d-print-none">
			<div class="region__inner">
				<div class="region__container">
					<div class="region__row">
						<?php
						$sidebar_active = false;
						for ( $i = 1; $i <= 4; $i++ ) :
							if ( is_active_sidebar( "footer-{$i}" ) ) :
								$sidebar_active = true; ?>
								<div class="<?php echo esc_attr( "region__col region__col--{$i} widget-area" ); ?>">
									<?php dynamic_sidebar( "footer-{$i}" ); ?>
								</div><!-- .region__col -->
								<?php
							endif;
						endfor;

						if ( ! $sidebar_active ) : ?>
							<div class="site-info text-center w-100" role="contentinfo">
								<?php bloginfo( 'title' ); ?><span class="sep"> | </span><?php bloginfo( 'description' ); ?>
							</div><!-- .site-info -->
							<?php
						endif; ?>
					</div><!-- .region__row -->
				</div><!-- .region__container -->
			</div><!-- .region__inner -->
		</div><!-- #footer -->
		<?php
	}
endif;

if ( ! function_exists( 'gwangi_content_class' ) ) :
	/**
	 * Display the classes for the #site-content div.
	 *
	 * @since 1.0.0
	 */
	function gwangi_content_class() {
		$classes = array(
			'site-content',
			'region',
		);

		if ( is_page() ) {
			$page_template = get_page_template_slug( get_queried_object_id() );

			switch ( $page_template ) {
				case 'template-narrower-12-cols-left.php':
					$classes[] = 'region--12-cols-left';
					$classes[] = 'region--container-narrower';
					break;

				case 'template-narrow-12-cols-left.php':
					$classes[] = 'region--12-cols-left';
					$classes[] = 'region--container-narrow';
					break;

				case 'template-classic-12-cols-left.php':
				case 'template-minimal.php':
					$classes[] = 'region--12-cols-left';
					$classes[] = 'region--container-classic';
					break;

				case 'template-classic-9-3-cols-left.php':
					$classes[] = 'region--9-3-cols-left';
					$classes[] = 'region--container-classic';
					break;

				case 'template-classic-3-9-cols-left.php':
					$classes[] = 'region--3-9-cols-left';
					$classes[] = 'region--container-classic';
					break;

				default:
					$classes[] = 'region--9-3-cols-left';
					$classes[] = 'region--container-classic';
			}
		} elseif ( is_single() ) {
			$classes[] = 'region--12-cols-left';
			$classes[] = 'region--container-classic';
		} elseif ( is_404() ) {
			$classes[] = '';
		} else {
			$classes[] = 'region--9-3-cols-left';
			$classes[] = 'region--container-classic';
		}

		echo 'class="' . esc_attr( join( ' ', $classes ) ) . '"';
	}
endif;

if ( ! function_exists( 'gwangi_header_image_style' ) ) :
	/**
	 * Print the style attribute for the #custom_header div to display the header image.
	 *
	 * @since 1.0.0
	 */
	function gwangi_header_image_style() {
		if ( has_header_image() ) {
			echo 'style="background-image: url(' . esc_url_raw( get_header_image() ) . ');"';
		}
	}
endif;

if ( ! function_exists( 'gwangi_sidebar_right' ) ) :
	/**
	 * Prints right sidebar in page.
	 */
	function gwangi_sidebar_right() {
		if ( ! is_single() && is_active_sidebar( 'sidebar-1' ) ) : ?>
			<aside id="secondary-right" class="widget-area sidebar region__col region__col--3">
				<?php dynamic_sidebar( 'sidebar-1' ); ?>
			</aside><!-- #secondary-right -->
			<?php
		endif;
	}
endif;

if ( ! function_exists( 'gwangi_before_posts' ) ) :
	/**
	 * Prints markups to open the posts wrapper.
	 */
	function gwangi_before_posts() {
		?>
		<div id="posts" class="posts blog-posts posts--6-6-cols-classic posts--height-not-equalized">
		<?php
	}
endif;

if ( ! function_exists( 'gwangi_after_posts' ) ) :
	/**
	 * Prints markups to close the posts wrapper.
	 */
	function gwangi_after_posts() {
		?>
		</div><!-- #posts -->
		<?php
	}
endif;

if ( ! function_exists( 'gwangi_post' ) ) :
	/**
	 * Prints HTML for the post.
	 *
	 * @since 1.0.0
	 */
	function gwangi_post() {
		?>
		<div class="card">

			<?php if ( has_post_format( array( 'video', 'audio', 'image', 'gallery' ) ) ) : ?>
				<div class="post-media"><?php the_content(); ?></div>
			<?php elseif ( has_post_thumbnail() ) : ?>
				<?php
				gwangi_the_post_thumbnail( 'thumbnail-6-6-cols-classic', array(
					'class' => 'card-img wp-post-image',
				) ); ?>
			<?php endif; ?>

			<div class="card-body pt-3">
				<?php
				if ( 'post' === get_post_type() ) : ?>
					<div class="card-body-labels entry-labels">
						<?php
						gwangi_the_post_format();
						gwangi_the_sticky_mark(); ?>
					</div>
					<?php
				endif; ?>

				<header class="card-body-header entry-header clearfix">
					<?php
					if ( is_single() ) :
						the_title( '<h1 class="entry-title">', '</h1>' );
					else :
						the_title( '<h2 class="entry-title h4"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
					endif; ?>
					<div class="card-body-meta entry-meta">
						<?php gwangi_the_date(); ?> <?php gwangi_the_category_list(); ?> <?php gwangi_the_author(); ?>
					</div><!-- .entry-meta -->
				</header><!-- .entry-header -->

				<?php if ( has_post_format( array( 'link', 'quote' ) ) ) : ?>
					<div class="entry-content clearfix">
						<?php the_content(); ?>
						<a href="<?php the_permalink(); ?>" class="more-link"><?php esc_html_e( 'Continue reading', 'gwangi' ); ?></a>
					</div><!-- .entry-content -->
				<?php else : ?>
					<div class="entry-content clearfix">
						<?php the_excerpt(); ?>
					</div><!-- .entry-content -->
					<div class="entry-summary clearfix">
						<a href="<?php the_permalink(); ?>" class="more-link btn btn-link btn-sm"><?php esc_html_e( 'Continue reading', 'gwangi' ); ?></a>
					</div>
				<?php endif; ?>

			</div><!-- .card-body-->

			<?php if ( comments_open() || has_tag() ) : ?>
				<footer class="card-footer entry-footer clearfix">
					<div class="row">
						<div class="col">
							<?php gwangi_the_tag_list(); ?>
						</div>
						<div class="col-auto">
							<?php gwangi_comments_link(); ?>
						</div>
					</div>
				</footer><!-- .entry-footer -->
			<?php endif; ?>

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
		</div><!-- .card-->
		<?php
	}
endif;

if ( ! function_exists( 'gwangi_the_post_thumbnail' ) ) :
	/**
	 * Prints HTML for the post thumbnail.
	 *
	 * @since 1.0.0
	 *
	 * @param string $size The size for the post thumbnail.
	 * @param array  $attr The array of attributes for the post thumbnail.
	 */
	function gwangi_the_post_thumbnail( $size = 'large', $attr = array() ) {
		if ( has_post_thumbnail() ) : ?>
			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="post-thumbnail" rel="bookmark">
				<?php the_post_thumbnail( $size, $attr ); ?>
			</a>
			<?php
		endif;
	}
endif;

if ( ! function_exists( 'gwangi_search_post' ) ) :
	/**
	 * Prints HTML for the post.
	 *
	 * @since 1.0.0
	 */
	function gwangi_search_post() {
		?>
		<div class="card">

			<?php if ( has_post_format( array( 'video', 'audio', 'image', 'gallery' ) ) ) : ?>
				<div class="post-media"><?php the_content(); ?></div>
			<?php elseif ( has_post_thumbnail() ) : ?>
				<?php
				gwangi_the_post_thumbnail( 'thumbnail-6-6-cols-classic', array(
					'class' => 'card-img wp-post-image',
				) ); ?>
			<?php endif; ?>

			<div class="card-body pt-3">

				<header class="card-body-header entry-header clearfix">
					<?php
					if ( 'post' === get_post_type() ) : ?>
						<div class="card-body-labels entry-labels">
							<?php
							gwangi_the_post_format();
							gwangi_the_sticky_mark(); ?>
						</div>
						<?php
					endif; ?>
					<?php
					the_title( '<h2 class="entry-title h4"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );

					if ( 'post' === get_post_type() ) : ?>
						<div class="card-body-meta entry-meta">
							<?php
							gwangi_the_date(); ?> <?php gwangi_the_category_list(); ?> <?php gwangi_the_author(); ?>
						</div><!-- .entry-meta -->
						<?php
					endif; ?>
				</header><!-- .entry-header -->

				<?php if ( has_post_format( array( 'link', 'quote' ) ) ) : ?>
					<div class="entry-content clearfix">
						<?php the_content(); ?>
						<a href="<?php the_permalink(); ?>" class="more-link"><?php esc_html_e( 'Continue reading', 'gwangi' ); ?></a>
					</div><!-- .entry-content -->
				<?php else : ?>
					<div class="entry-content clearfix">
						<?php the_excerpt(); ?>
					</div><!-- .entry-content -->
					<div class="entry-summary clearfix">
						<a href="<?php the_permalink(); ?>" class="more-link btn btn-link btn-sm"><?php esc_html_e( 'Continue reading', 'gwangi' ); ?></a>
					</div>
				<?php endif; ?>

			</div><!-- .card-body -->

		<?php if ( comments_open() || has_tag() || ( 'post' === get_post_type() ) ) : ?>

			<footer class="card-footer entry-footer clearfix">
				<div class="row">
					<div class="col">
						<?php gwangi_the_tag_list(); ?>
					</div>
					<div class="col-auto">
						<?php gwangi_comments_link(); ?>
					</div>
				</div>
			</footer><!-- .entry-footer -->

		<?php endif; ?>

		</div><!-- .card -->
		<?php
	}
endif;

if ( ! function_exists( 'gwangi_single' ) ) :
	/**
	 * Prints HTML for the single post.
	 *
	 * @since 1.0.0
	 */
	function gwangi_single() {
		?>

		<?php
		$featured_img_url = get_the_post_thumbnail_url( get_the_ID(), 'full' ); ?>

		<header class="site-header region">
			<div class="region__inner">
				<div class="region__container">
					<div id="custom_header" class="custom_header custom_header--single region region--12-cols-center region--container-classic section" style="background-image: url('<?php echo esc_url('$featured_img_url'); ?>')">
						<div class="region__inner">
							<div class="region__container">
								<div class="region__row">
									<div class="region__col region__col--2">
										<div class="section__header entry-header">
											<?php gwangi_the_category_list(); ?>
											<h2 class="section__title display-1">
												<?php the_title(); ?>
											</h2>
											<h3 class="section__subtitle lead">
												<?php
												// Display post excerpt only when available.
												$_post = get_post();
												echo '' !== $_post->post_excerpt ? '<span class="excerpt d-block">' . esc_html( $_post->post_excerpt ) . '</span>' : '';

												do_action( 'gwangi_breadcrumb' );

												if ( 'post' === get_post_type() ) : ?>
													<span class="entry-meta d-block">
														<?php gwangi_the_date(); ?> <?php gwangi_the_author(); ?>
													</span><!-- .entry-meta -->
													<?php
												endif; ?>
											</h3><!-- .section__subtitle -->
										</div><!-- .section__header -->
									</div><!-- .region__col -->
								</div><!-- .region__row -->
							</div><!-- .region__container -->
						</div><!-- .region__inner -->
					</div><!-- #custom_header -->
				</div><!-- .region__container -->
			</div><!-- .region__inner -->
		</header>

		<div class="region__inner">
			<div class="region region--container-narrow">
				<div class="region__container">
					<div class="single-content">
						<div class="entry-content clearfix">
							<?php
							the_content();
							wp_link_pages( array(
								'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'gwangi' ) . '</span>',
								'after'       => '</div>',
								'link_before' => '<span>',
								'link_after'  => '</span>',
								'pagelink'    => '<span class="screen-reader-text sr-only">' . esc_html__( 'Page', 'gwangi' ) . ' </span>%',
								'separator'   => '<span class="screen-reader-text sr-only">, </span>',
							) );
							gwangi_the_author_biography(); ?>
						</div><!-- .entry-content -->
						<footer class="entry-footer clearfix">
							<?php
							gwangi_the_tag_list();

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
					</div>
				</div>
			</div>
		</div>
		<?php
	}
endif;

if ( ! function_exists( 'gwangi_page' ) ) :
	/**
	 * Prints HTML for the page.
	 *
	 * @since 1.0.0
	 */
	function gwangi_page() {
		?>
		<header class="entry-header clearfix">
			<?php
			do_action( 'gwangi_breadcrumb' );
			the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		</header><!-- .entry-header -->

		<div class="entry-content clearfix">
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

		<?php
		if ( get_edit_post_link() ) : ?>
			<footer class="entry-footer clearfix">
				<?php
				edit_post_link(
					sprintf(
						/* translators: %s: Name of current post */
						esc_html__( 'Edit %s', 'gwangi' ),
						the_title( '<span class="screen-reader-text sr-only">"', '"</span>', false )
					),
					'<span class="edit-link">',
					'</span>'
				); ?>
			</footer><!-- .entry-footer -->
			<?php
		endif;
	}
endif;

if ( ! function_exists( 'gwangi_404' ) ) :
	/**
	 * Prints HTML for the 404 page.
	 *
	 * @since 1.1.8
	 */
	function gwangi_404() {
		?>
		<div class="grimlock-404 error-404 not-found region grimlock-region grimlock-region--pt-0 grimlock-region--pb-0 region--4-8-cols-left region--container-fluid grimlock-section section">
			<div class="region__inner">
				<div class="region__container">
					<div class="region__row">
						<div class="region__col region__col--1">
							<div class="section__thumbnail">
								<img class="grimlock-section__thumbnail-img section__thumbnail-img img-fluid" src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/pages/page-404.jpg' ); ?>" alt="<?php esc_html_e( '404', 'gwangi' ); ?>" />
							</div><!-- .section__thumbnail -->
						</div><!-- .region__col -->
						<div class="region__col region__col--2">
							<h1 class="page-404-title"><?php esc_html_e( '404', 'gwangi' ); ?></h1>
							<h4 class="page-404-subtitle"><?php esc_html_e( 'Page not found.', 'gwangi' ); ?></h4>
							<p class="page-404-text"><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'gwangi' ); ?></p>
							<?php get_search_form(); ?>
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary btn-lg col-12 col-sm-auto"><?php esc_html_e( 'Go back to homepage', 'gwangi' ); ?></a>
						</div><!-- .region__col -->
					</div><!-- .region__row -->
				</div><!-- .region__container -->
			</div><!-- .region__inner -->
		</div><!-- .grimlock-section -->
		<?php
	}
endif;

if ( ! function_exists( 'gwangi_the_date' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function gwangi_the_date() {
		if ( 'post' === get_post_type() || 'attachment' === get_post_type() ) {
			$allowed_html = array(
				'time' => array(
					'class'    => true,
					'datetime' => true,
				),
			);

			$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

			if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
				$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
			}

			$time_string = sprintf( $time_string,
				esc_attr( get_the_date( 'c' ) ),
				esc_html( get_the_date() ),
				esc_attr( get_the_modified_date( 'c' ) ),
				esc_html( get_the_modified_date() )
			);

			printf(
				'<span class="posted-on"><span class="posted-on-label">' . esc_html__( 'Posted on', 'gwangi' ) . ' </span>%s</span>',
				'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . wp_kses( $time_string, $allowed_html ) . '</a>'
			);
		}
	}
endif;

if ( ! function_exists( 'gwangi_the_author' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function gwangi_the_author() {
		if ( 'post' === get_post_type() ) {
			printf(
				'<span class="byline author"><span class="byline-label">' . esc_html__( 'by', 'gwangi' ) . ' </span>%1$s %2$s</span>',
				'<span class="author-avatar"><span class="avatar-round-ratio medium"><a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . get_avatar( get_the_author_meta( 'ID' ), 52 ) . '</a></span></span>',
				'<span class="author-vcard vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
			);
		}
	}
endif;

if ( ! function_exists( 'gwangi_the_sticky_mark' ) ) :
	/**
	 * Prints HTML for "Featured" as Boostrap label when the post is sticky.
	 *
	 * @since 1.0.0
	 */
	function gwangi_the_sticky_mark() {
		if ( is_sticky() ) : ?>
			<span class="badge badge-primary post-sticky"><i class="fa fa-thumb-tack"></i> <span class="badge__name"><?php esc_html_e( 'Sticky', 'gwangi' ); ?></span></span>
			<?php
		endif;
	}
endif;

if ( ! function_exists( 'gwangi_get_more_link_text' ) ) :
	/**
	 * Get the HTML for the More link.
	 *
	 * @since 1.0.0
	 *
	 * @return string The more link text.
	 */
	function gwangi_get_more_link_text() {
		$allowed_html = array(
			'span' => array(
				'class' => array(),
			),
		);

		$more_link_text = sprintf(
			/* translators: 1: Name of current post, 2: Right arrow */
			wp_kses( __( 'Continue reading %1$s %2$s', 'gwangi' ), $allowed_html ),
			the_title( '<span class="screen-reader-text sr-only">"', '"</span>', false ),
			'<span class="meta-nav">&rarr;</span>'
		);

		return apply_filters( 'gwangi_more_link_text', $more_link_text );
	}
endif;

if ( ! function_exists( 'gwangi_the_category_list' ) ) :
	/**
	 * Prints HTML with meta information for the categories.
	 */
	function gwangi_the_category_list() {
		if ( 'post' === get_post_type() ) {

			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'gwangi' ) );
			if ( $categories_list && gwangi_categorized_blog() ) {
				// $categories_list doesn't need to be escaped here cause it comes from native WP get_the_category_list() function
				printf( '<span class="cat-links">%1$s</span>', $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
	}
endif;

if ( ! function_exists( 'gwangi_the_tag_list' ) ) :
	/**
	 * Prints HTML with meta information for the post tags.
	 */
	function gwangi_the_tag_list() {
		if ( 'post' === get_post_type() ) {
			$tags_list = get_the_tag_list( '', ' ' );
			if ( $tags_list ) {
				// $tags_list doesn't need to be escaped here cause it comes from native WP get_the_tag_list() function
				printf( '<span class="tags-links"><i class="fa fa-tags mr-1 va-m"></i> %1$s </span>', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
	}
endif;

if ( ! function_exists( 'gwangi_the_post_format' ) ) :
	/**
	 * Prints HTML for the post format as Boostrap label.
	 *
	 * @since 1.0.0
	 */
	function gwangi_the_post_format() {
		$post_format = get_post_format();
		if ( false !== $post_format ) :
			$post_format_link_title = sprintf(
				/* translators: %s: The post format name */
				esc_html__( 'View posts formatted as %s', 'gwangi' ),
				esc_attr( strtolower( get_post_format_string( $post_format ) ) )
			); ?>
			<a href="<?php echo esc_url( get_post_format_link( $post_format ) ); ?>" title="<?php echo esc_attr( $post_format_link_title ); ?>" class="badge badge-primary mr-2 post-format post-format--<?php echo esc_attr( $post_format ); ?>">
				<i class="fa fa-<?php echo esc_html( $post_format ); ?>"></i> <?php echo esc_html( get_post_format_string( $post_format ) ); ?>
			</a>
			<?php
		endif;
	}
endif;

if ( ! function_exists( 'gwangi_comments_link' ) ) :
	/**
	 * Prints HTML with meta information for the comments.
	 */
	function gwangi_comments_link() {
		$has_comments_link = ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() );
		if ( apply_filters( 'gwangi_has_comments_link', $has_comments_link ) ) {
			echo ' <span class="comments-link">';
			comments_popup_link( '0', '1', '%' );
			echo '</span>';
		}
	}
endif;

if ( ! function_exists( 'gwangi_the_author_biography' ) ) :
	/**
	 * Display the author biography.
	 */
	function gwangi_the_author_biography() {
		if ( '' !== get_the_author_meta( 'description' ) && 'post' === get_post_type() ) :
			$avatar_args = array(
				'class' => array( 'd-flex', 'align-self-start', 'mr-3' ),
			); ?>
			<div class="card p-3 mb-4">
				<div class="media author-info">
					<span class="avatar-round-ratio big">
						<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
							<?php echo get_avatar( get_the_author_meta( 'user_email' ), 140, '', '', $avatar_args ); ?>
						</a>
					</span>
					<div class="author-description media-body">
						<h4 class="author-title media-heading"><span class="author-heading"><?php esc_html_e( 'By', 'gwangi' ); ?></span> <?php echo get_the_author(); ?></h4>
						<div class="author-bio">
							<?php the_author_meta( 'description' ); ?>
							<div class="mt-1">
								<a class="btn btn-link" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
									<?php
									/* translators: %s: The author name */
									printf( esc_html__( 'View all posts by %s', 'gwangi' ), esc_html( get_the_author() ) ); ?>
								</a>
							</div>
						</div><!-- .author-bio -->
					</div><!-- .author-description -->
				</div><!-- .author-info -->
			</div>
			<?php
		endif;
	}
endif;

if ( ! function_exists( 'gwangi_comment' ) ) :
	/**
	 * Template for comments and pingbacks.
	 *
	 * @param object $comment The comment object.
	 * @param array  $args    The array of arguments for the comment link.
	 * @param int    $depth   The depth of comment replies.
	 */
	function gwangi_comment( $comment, $args, $depth ) {
		// @codingStandardsIgnoreLine
		$GLOBALS['comment'] = $comment;

		if ( 'pingback' === $comment->comment_type || 'trackback' === $comment->comment_type ) : ?>

			<li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>

				<div class="comment-body mb-2">
					<h5><?php esc_html_e( 'Pingback:', 'gwangi' ); ?></h5>
					<div><?php comment_author_link(); ?></div>
				</div><!-- .comment-body -->

			</li><!-- #-comment-## -->

			<?php
		else : ?>

		<li id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?>>

			<div id="div-comment-<?php comment_ID(); ?>" class="comment-main comment-main-<?php comment_ID(); ?> row m-0">

				<div class="col-12 col-sm-auto pl-0">
					<div class="comment-img text-left m-0">
						<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
							<span class="avatar-round-ratio medium"><?php echo 0 !== $args['avatar_size'] ? get_avatar( $comment, $args['avatar_size'] ) : ''; ?></span>
						</a>
					</div><!-- .comment-img -->
				</div><!-- .col -->

				<div class="col pr-0 pl-0">

					<div class="comment-body p-3 p-sm-4">

						<h5 class="comment-title media-heading">
							<span class="fn"><?php comment_author_link(); ?></span>
							<time datetime="<?php comment_time( 'c' ); ?>" class="comment-time small">
								<?php
								/* translators: 1: Date, 2: Time */
								printf( esc_html_x( '%1$s at %2$s', '1: date, 2: time', 'gwangi' ), esc_html( get_comment_date() ), esc_html( get_comment_time() ) ); ?>
							</time><!-- .comment-time -->
						</h5><!-- .media-heading -->

						<div class="comment-content">
							<?php comment_text(); ?>
						</div><!-- .comment-content -->

						<footer class="comment-meta">

							<?php if ( '0' === $comment->comment_approved ) : ?>
								<p class="alert alert-danger comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'gwangi' ); ?></p>
							<?php endif; ?>

							<ul class="nav nav-inline">

								<li class="nav-item">
									<?php
									$args = array_merge( $args, array(
										'add_below' => 'div-comment',
										'depth'     => $depth,
										'max_depth' => $args['max_depth'],
										'before'    => '<span class="reply">',
										'after'     => '</span>',
									) );
									comment_reply_link( $args ); ?>
								</li><!-- .nav-item -->

								<li class="nav-item">
									<?php edit_comment_link( esc_html__( 'Edit', 'gwangi' ), '<span class="edit-link">', '</span>' ); ?>
								</li><!-- .nav-item -->

							</ul><!-- .nav -->

						</footer><!-- .comment-meta -->

					</div><!-- .comment-body -->

				</div><!-- .col -->

			</div><!-- .comment-main -->

			<!-- "</li>" No closure tag, wp_list_comments "end-callback" do the job -->
			<?php
		endif;
	}
endif;

if ( ! function_exists( 'gwangi_get_the_archive_title' ) ) :
	/**
	 * Change the retrieved the archive title based on the queried object.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $title The archive title.
	 *
	 * @return string        The updated archive title.
	 */
	function gwangi_get_the_archive_title( $title ) {
		if ( is_category() ) {
			$title = single_cat_title( '', false );
		} elseif ( is_tag() ) {
			$title = single_tag_title( '', false );
		} elseif ( is_author() ) {
			$title = '<span class="vcard">' . get_the_author() . '</span>';
		} elseif ( is_year() ) {
			$title = get_the_date( esc_html__( 'Y', 'gwangi' ) );
		} elseif ( is_month() ) {
			$title = get_the_date( esc_html__( 'F Y', 'gwangi' ) );
		} elseif ( is_day() ) {
			$title = get_the_date( esc_html__( 'F j, Y', 'gwangi' ) );
		} elseif ( is_tax( 'post_format' ) ) {
			if ( is_tax( 'post_format', 'post-format-aside' ) ) {
				$title = esc_html__( 'Asides', 'gwangi' );
			} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
				$title = esc_html__( 'Galleries', 'gwangi' );
			} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
				$title = esc_html__( 'Images', 'gwangi' );
			} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
				$title = esc_html__( 'Videos', 'gwangi' );
			} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
				$title = esc_html__( 'Quotes', 'gwangi' );
			} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
				$title = esc_html__( 'Links', 'gwangi' );
			} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
				$title = esc_html__( 'Statuses', 'gwangi' );
			} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
				$title = esc_html__( 'Audio', 'gwangi' );
			} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
				$title = esc_html__( 'Chats', 'gwangi' );
			}
		} elseif ( is_post_type_archive() ) {
			$title = post_type_archive_title( '', false );
		} elseif ( is_tax() ) {
			$title = single_term_title( '', false );
		} else {
			$title = esc_html__( 'Archives', 'gwangi' );
		}

		return $title;
	}
endif;

/**
 * List gwangi page templates.
 *
 * @since 1.0.0
 *
 * @param array $templates The array of page templates. Keys are filenames, values are translated names.
 *
 * @return array           The array of page templates.
 */
function gwangi_theme_page_templates( $templates ) {
	unset( $templates['template-classic-3-9-cols-left.php'] );
	unset( $templates['template-classic-9-3-cols-left.php'] );
	unset( $templates['template-homepage-minimal.php'] );
	unset( $templates['template-homepage.php'] );
	unset( $templates['template-minimal.php'] );
	unset( $templates['template-search.php'] );
	return $templates;
}

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function gwangi_categorized_blog() {
	$all_the_cool_cats = get_transient( 'gwangi_categories' );
	if ( false === $all_the_cool_cats ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'gwangi_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so gwangi_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so gwangi_categorized_blog should return false.
		return false;
	}
}

/**
 * Allow to inject code immediately following the opening <body> tag.
 *
 */
if ( ! function_exists( 'gwangi_body_open' ) ) {
	function gwangi_body_open() {
		do_action( 'wp_body_open' );
	}
}

/**
 * Flush out the transients used in gwangi_categorized_blog.
 */
function gwangi_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'gwangi_categories' );
}
add_action( 'edit_category', 'gwangi_category_transient_flusher' );
add_action( 'save_post',     'gwangi_category_transient_flusher' );
