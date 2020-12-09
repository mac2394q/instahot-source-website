<?php
/**
 * Gwangi Grimlock Class
 *
 * @package  gwangi
 * @author   Themosaurus
 * @since    1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Gwangi_Grimlock' ) ) :
	/**
	 * The Gwangi Grimlock integration class
	 */
	class Gwangi_Grimlock {
		/**
		 * Setup class.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			add_action( 'init',                array( $this, 'add_excerpt_support_for_pages' ), 10    );
			add_action( 'widgets_init',        array( $this, 'widgets_init'                  ), 10    );
			add_filter( 'gwangi_widget_areas', array( $this, 'add_widget_areas'              ), 10, 1 );
			add_action( 'wp_enqueue_scripts',  array( $this, 'dequeue_scripts'               ), 20    );

			require get_template_directory() . '/inc/grimlock/component/class-gwangi-grimlock-post-component.php';

			global $grimlock;
			remove_action( 'grimlock_post',        array( $grimlock, 'post'        ), 10 );
			remove_action( 'grimlock_query_post',  array( $grimlock, 'query_post'  ), 10 );
			remove_action( 'grimlock_search_post', array( $grimlock, 'search_post' ), 10 );

			add_action(    'grimlock_post',        array( $this,     'post'        ), 10, 1 );
			add_action(    'grimlock_search_post', array( $this,     'search_post' ), 10, 1 );
			add_action(    'grimlock_query_post',  array( $this,     'query_post'  ), 10, 1 );

			require_once get_template_directory() . '/inc/grimlock/customizer/class-gwangi-grimlock-site-identity-customizer.php';
			require_once get_template_directory() . '/inc/grimlock/customizer/class-gwangi-grimlock-global-customizer.php';
			require_once get_template_directory() . '/inc/grimlock/customizer/class-gwangi-grimlock-typography-customizer.php';
			require_once get_template_directory() . '/inc/grimlock/customizer/class-gwangi-grimlock-navigation-customizer.php';
			require_once get_template_directory() . '/inc/grimlock/customizer/class-gwangi-grimlock-preheader-customizer.php';
			require_once get_template_directory() . '/inc/grimlock/customizer/class-gwangi-grimlock-custom-header-customizer.php';
			require_once get_template_directory() . '/inc/grimlock/customizer/class-gwangi-grimlock-prefooter-customizer.php';
			require_once get_template_directory() . '/inc/grimlock/customizer/class-gwangi-grimlock-footer-customizer.php';
			require_once get_template_directory() . '/inc/grimlock/customizer/class-gwangi-grimlock-control-customizer.php';
			require_once get_template_directory() . '/inc/grimlock/customizer/class-gwangi-grimlock-button-customizer.php';
			require_once get_template_directory() . '/inc/grimlock/customizer/class-gwangi-grimlock-back-to-top-button-customizer.php';
			require_once get_template_directory() . '/inc/grimlock/customizer/class-gwangi-grimlock-pagination-customizer.php';
			require_once get_template_directory() . '/inc/grimlock/customizer/class-gwangi-grimlock-table-customizer.php';
			require_once get_template_directory() . '/inc/grimlock/customizer/class-gwangi-grimlock-loader-customizer.php';
			require_once get_template_directory() . '/inc/grimlock/customizer/class-gwangi-grimlock-archive-customizer.php';
			require_once get_template_directory() . '/inc/grimlock/customizer/class-gwangi-grimlock-search-customizer.php';
			require_once get_template_directory() . '/inc/grimlock/customizer/class-gwangi-grimlock-single-customizer.php';
			require_once get_template_directory() . '/inc/grimlock/customizer/class-gwangi-grimlock-page-customizer.php';
			require_once get_template_directory() . '/inc/grimlock/customizer/class-gwangi-grimlock-404-customizer.php';

			add_filter( 'grimlock_color_field_palettes',                          array( $this, 'color_field_palettes'                                 ), 10, 1 );
			add_filter( 'grimlock_section_widget_defaults',                       array( $this, 'change_section_widget_defaults'                       ), 10, 1 );
			add_filter( 'grimlock_nav_menu_section_widget_defaults',              array( $this, 'change_nav_menu_section_widget_defaults'              ), 10, 1 );
			add_filter( 'grimlock_query_section_widget_post_thumbnail_size',      array( $this, 'change_query_section_widget_post_thumbnail_size'      ), 10, 2 );
			add_filter( 'grimlock_posts_section_widget_post_thumbnail_size',      array( $this, 'change_query_section_widget_post_thumbnail_size'      ), 10, 2 );
			add_filter( 'grimlock_term_query_section_widget_term_thumbnail_size', array( $this, 'change_term_query_section_widget_term_thumbnail_size' ), 10, 2 );
			add_filter( 'grimlock_query_section_widget_defaults',                 array( $this, 'change_query_section_widget_defaults'                 ), 10, 1 );

			add_filter( 'grimlock_section_widget_layout_field_args',                   array( $this, 'change_layout_field_args' ), 10, 1 );
			add_filter( 'grimlock_404_customizer_layout_field_args',                   array( $this, 'change_layout_field_args' ), 10, 1 );
			add_filter( 'grimlock_custom_header_customizer_layout_field_args',         array( $this, 'change_layout_field_args' ), 10, 2 );
			add_filter( 'grimlock_single_customizer_custom_header_layout_field_args',  array( $this, 'change_layout_field_args' ), 10, 2 );
			add_filter( 'grimlock_page_customizer_custom_header_layout_field_args',    array( $this, 'change_layout_field_args' ), 10, 2 );
			add_filter( 'grimlock_archive_customizer_custom_header_layout_field_args', array( $this, 'change_layout_field_args' ), 10, 2 );
			add_filter( 'grimlock_search_customizer_custom_header_layout_field_args',  array( $this, 'change_layout_field_args' ), 10, 2 );

			add_filter( 'grimlock_site_identity_args',                            array( $this, 'change_site_identity_args'                            ), 10, 1 );
			add_filter( 'grimlock_section_widget_background_image_size',          array( $this, 'change_section_widget_background_image_size'          ), 10, 1 );
			add_filter( 'grimlock_custom_header_displayed',                       array( $this, 'has_custom_header_displayed'                          ), 10, 1 );
			add_filter( 'grimlock_content_class',                                 array( $this, 'add_content_classes'                                  ), 10, 1 );
		}

		/**
		 * Remove stylesheets.
		 *
		 * @since 1.0.0
		 */
		public function dequeue_scripts() {
			wp_dequeue_style(    'gwangi-google-fonts' );
			wp_deregister_style( 'gwangi-google-fonts' );
			wp_dequeue_script(   'gwangi-navigation-search' );
			wp_dequeue_script(   'gwangi-grid' );
		}

		/**
		 * Define available colors in the palette of the color picker.
		 *
		 * @since 1.0.0
		 *
		 * @param  array $colors The color palette for the color picker.
		 *
		 * @return array         The updated color palette for the color picker.
		 */
		public function color_field_palettes( $colors ) {
			return array(
				GWANGI_BRAND_PRIMARY,
				GWANGI_BRAND_SECONDARY,
				GWANGI_BODY_COLOR,
				GWANGI_GRAY_DARK,
				GWANGI_GRAY_LIGHT,
				GWANGI_GRAY_LIGHTEST,
				GWANGI_BRAND_INFO,
				GWANGI_BRAND_SUCCESS,
			);
		}

		/**
		 * Change the default values for the section widget.
		 *
		 * @since 1.0.0
		 *
		 * @param  array $defaults The array of defaults values for the widget.
		 *
		 * @return array           The updated array of defaults values for the widget.
		 */
		public function change_section_widget_defaults( $defaults ) {
			$defaults['background_color'] = GWANGI_SECTION_WIDGET_BACKGROUND_COLOR;
			$defaults['padding_y']        = GWANGI_SECTION_PADDING_Y;
			return $defaults;
		}

		/**
		 * Change the default values for the nav menu section widget.
		 *
		 * @since 1.0.0
		 *
		 * @param  array $defaults The array of defaults values for the widget.
		 *
		 * @return array           The updated array of defaults values for the widget.
		 */
		public function change_nav_menu_section_widget_defaults( $defaults ) {
			$defaults['background_color'] = GWANGI_SECTION_WIDGET_BACKGROUND_COLOR;
			return $defaults;
		}

		/**
		 * Change the default values for the query section widget.
		 *
		 * @since 1.0.0
		 *
		 * @param  array $defaults The array of defaults values for the widget.
		 *
		 * @return array           The updated array of defaults values for the widget.
		 */
		public function change_query_section_widget_defaults( $defaults ) {
			$defaults['background_color'] = GWANGI_SECTION_WIDGET_BACKGROUND_COLOR;
			return $defaults;
		}

		/**
		 * Change default layouts.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args The array of layouts. Keys are filenames, values are translated names.
		 *
		 * @return array           The array of layouts.
		 */
		public function change_layout_field_args( $args ) {
			unset( $args['choices']['6-6-cols-left-modern'] );
			unset( $args['choices']['6-6-cols-left-reverse-modern'] );
			unset( $args['choices']['8-4-cols-left-modern'] );
			unset( $args['choices']['8-4-cols-left-reverse-modern'] );
			return $args;
		}


		/**
		 * Enables the Excerpt meta box in Page edit screen.
		 *
		 * @since 1.0.0
		 */
		public function add_excerpt_support_for_pages() {
			add_post_type_support( 'page', 'excerpt' );
		}

		/**
		 * Change props for the Site Identity component to display default logo.
		 *
		 * @since 1.0.0
		 *
		 * @param  array $args The array of props for the component.
		 *
		 * @return array       The filtered array of props for the component.
		 */
		public function change_site_identity_args( $args ) {
			$logo                = '<a href="' . esc_url( home_url( '/' ) ) . '" rel="home"><img src="' . esc_url( get_stylesheet_directory_uri() . '/assets/images/logo.png' ) . '" alt="logo" /></a>';
			$args['custom_logo'] = empty( $args['custom_logo'] ) ? $logo : $args['custom_logo'];
			return $args;
		}


		/**
		 * Override Grimlock Post component to modify its markups.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args The array of arguments for the post component.
		 */
		public function post( $args = array() ) {
			$post = new Gwangi_Grimlock_Post_Component( apply_filters( 'grimlock_post_args', (array) $args ) );
			$post->render();
		}

		/**
		 * Override Grimlock Post Search component to modify its markups.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args The array of arguments for the post component.
		 */
		public function search_post( $args = array() ) {
			$post = new Gwangi_Grimlock_Post_Component( apply_filters( 'grimlock_search_post_args', $args ) );
			$post->render();
		}

		/**
		 * Override Grimlock Query Post component to modify its markups.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args The array of arguments for the query post component.
		 */
		public function query_post( $args = array() ) {
			$post = new Gwangi_Grimlock_Post_Component( apply_filters( 'grimlock_query_post_args', $args ) );
			$post->render();
		}

		/**
		 * Register widget areas for the front page.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
		 * @since 1.0.0
		 */
		public function widgets_init() {
			$widget_areas = apply_filters( 'gwangi_grimlock_widget_areas', array(
				'homepage-widget-area-10' => array(
					'id'          => 'before-content-1',
					'name'        => esc_html__( 'Before Content', 'gwangi' ),
					'description' => esc_html__( 'The area before the content for all pages.', 'gwangi' ),
				),
				'homepage-widget-area-20' => array(
					'id'          => 'homepage-1',
					'name'        => esc_html__( 'Homepage', 'gwangi' ),
					'description' => esc_html__( 'The area replacing the content for the homepage template.', 'gwangi' ),
				),
				'homepage-widget-area-30' => array(
					'id'          => 'after-content-1',
					'name'        => esc_html__( 'After Content', 'gwangi' ),
					'description' => esc_html__( 'The area after the content for all pages.', 'gwangi' ),
				),
			) );

			ksort( $widget_areas );
			foreach ( $widget_areas as $widget_area ) {
				register_sidebar( wp_parse_args( $widget_area, array(
					'name'          => '',
					'id'            => '',
					'description'   => '',
					'before_widget' => '<section id="%1$s" class="widget %2$s">',
					'after_widget'  => '</section>',
					'before_title'  => '<span class="widget-title">',
					'after_title'   => '</span>',
				) ) );
			}
		}

		/**
		 * Add widget areas for the theme.
		 *
		 * @since 1.0.0
		 *
		 * @param  array $widget_areas The array of registred widget areas.
		 *
		 * @return array               The updated array of registred widget areas.
		 */
		public function add_widget_areas( $widget_areas ) {
			return array_merge( $widget_areas, array(
				'widget-area-01' => array(
					'id'          => 'preheader-1',
					'name'        => esc_html__( 'Pre Header 1', 'gwangi' ),
					'description' => esc_html__( 'The first area before the header of all pages.', 'gwangi' ),
				),
				'widget-area-02' => array(
					'id'          => 'preheader-2',
					'name'        => esc_html__( 'Pre Header 2', 'gwangi' ),
					'description' => esc_html__( 'The second area before the header of all pages.', 'gwangi' ),
				),
				'widget-area-03' => array(
					'id'          => 'preheader-3',
					'name'        => esc_html__( 'Pre Header 3', 'gwangi' ),
					'description' => esc_html__( 'The third area before the header of all pages.', 'gwangi' ),
				),
				'widget-area-04' => array(
					'id'          => 'preheader-4',
					'name'        => esc_html__( 'Pre Header 4', 'gwangi' ),
					'description' => esc_html__( 'The fourth area before the header of all pages.', 'gwangi' ),
				),
				'widget-area-11' => array(
					'id'          => 'sidebar-2',
					'name'        => esc_html__( 'Sidebar 2', 'gwangi' ),
					'description' => esc_html__( 'The right hand area for all pages.', 'gwangi' ),
				),
				'widget-area-12' => array(
					'id'          => 'prefooter-1',
					'name'        => esc_html__( 'Pre Footer 1', 'gwangi' ),
					'description' => esc_html__( 'The first area before the footer of all pages.', 'gwangi' ),
				),
				'widget-area-13' => array(
					'id'          => 'prefooter-2',
					'name'        => esc_html__( 'Pre Footer 2', 'gwangi' ),
					'description' => esc_html__( 'The second area before the footer of all pages.', 'gwangi' ),
				),
				'widget-area-14' => array(
					'id'          => 'prefooter-3',
					'name'        => esc_html__( 'Pre Footer 3', 'gwangi' ),
					'description' => esc_html__( 'The third area before the footer of all pages.', 'gwangi' ),
				),
				'widget-area-15' => array(
					'id'          => 'prefooter-4',
					'name'        => esc_html__( 'Pre Footer 4', 'gwangi' ),
					'description' => esc_html__( 'The fourth area before the footer of all pages.', 'gwangi' ),
				),
			) );
		}

		/**
		 * Add custom classes to content to modify layout.
		 *
		 * @since 1.0.0
		 *
		 * @param  array $classes The array of CSS classes for the content.
		 *
		 * @return array          The updated array of CSS classes for the content.
		 */
		public function add_content_classes( $classes ) {
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
						$classes[] = 'region--3-6-3-cols-left';
						$classes[] = 'region--container-classic';
				}
			}
			return $classes;
		}

		/**
		 * Change background image size for widget Grimlock section
		 *
		 * @since 1.0.0
		 *
		 * @return string The background image size.
		 */
		public function change_section_widget_background_image_size() {
			return 'full';
		}

		/**
		 * Check if the custom header is displayed or not.
		 *
		 * @since 1.1.9
		 *
		 * @param bool $default True if the custom header would be displayed, false otherwise.
		 *
		 * @return bool True if the custom header is displayed, false otherwise.
		 */
		public function has_custom_header_displayed( $default ) {
			return ! is_page_template( 'template-minimal.php' ) && ! is_page_template( 'template-homepage-minimal.php' ) && $default;
		}

		/**
		 * Change default post thumbnail sizes for the query section widget.
		 *
		 * @since 1.0.0
		 *
		 * @param string $size         The size for the post thumbnail.
		 * @param string $posts_layout The layout for the query.
		 *
		 * @return string              The updated size for the post thumbnail.
		 */
		public function change_query_section_widget_post_thumbnail_size( $size, $posts_layout ) {
			return "thumbnail-{$posts_layout}";
		}

		/**
		 * Change default term thumbnail sizes for the query section widget.
		 *
		 * @since 1.0.1
		 *
		 * @param string $size         The size for the term thumbnail.
		 * @param string $terms_layout The layout for the term query.
		 *
		 * @return string              The updated size for the term thumbnail.
		 */
		public function change_term_query_section_widget_term_thumbnail_size( $size, $terms_layout ) {
			return "thumbnail-{$terms_layout}";
		}
	}
endif;

return new Gwangi_Grimlock();
