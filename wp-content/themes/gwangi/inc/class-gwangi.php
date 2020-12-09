<?php
/**
 * Gwangi Class
 *
 * @author   Themosaurus
 * @since    1.0.0
 * @package  gwangi
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Gwangi' ) ) :
	/**
	 * The main Gwangi class
	 */
	class Gwangi {
		/**
		 * Setup class.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			// Priority 0 to make it available to lower priority callbacks.
			add_action( 'after_setup_theme',       array( $this, 'content_width'        ), 0     );
			add_action( 'after_setup_theme',       array( $this, 'setup'                ), 10    );
			add_filter( 'image_size_names_choose', array( $this, 'add_image_size_names' ), 20, 1 );
			add_action( 'widgets_init',            array( $this, 'widgets_init'         ), 10    );
			add_action( 'wp_enqueue_scripts',      array( $this, 'wp_enqueue_scripts'   ), 10    );
			add_filter( 'body_class',              array( $this, 'add_body_classes'     ), 10    );
			add_action( 'wp_head',                 array( $this, 'pingback_header'      ), 10    );
		}

		/**
		 * Sets up theme defaults and registers support for various WordPress features.
		 *
		 * Note that this function is hooked into the after_setup_theme hook, which
		 * runs before the init hook. The init hook is too late for some features, such
		 * as indicating support for post thumbnails.
		 *
		 * @since 1.0.0
		 */
		public function setup() {
			/*
			 * Make theme available for translation.
			 * Translations can be filed in the /languages/ directory.
			 * If you're building a theme based on gwangi, use a find and replace
			 * to change 'gwangi' to the name of your theme in all the template files.
			 */
			load_theme_textdomain( 'gwangi', get_template_directory() . '/languages' );

			// Add default posts and comments RSS feed links to head.
			add_theme_support( 'automatic-feed-links' );

			/*
			 * Let WordPress manage the document title.
			 * By adding theme support, we declare that this theme does not use a
			 * hard-coded <title> tag in the document head, and expect WordPress to
			 * provide it for us.
			 */
			add_theme_support( 'title-tag' );

			/*
			 * Enable support for Post Thumbnails on posts and pages.
			 *
			 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
			 */
			add_theme_support( 'post-thumbnails' );

			// This theme uses wp_nav_menu() in one location.
			register_nav_menus( apply_filters( 'gwangi_nav_menus', array(
				'primary' => esc_html__( 'Primary', 'gwangi' ),
			) ) );

			/*
			 * Switch default core markup for search form, comment form, and comments
			 * to output valid HTML5.
			 */
			add_theme_support( 'html5', array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			) );

			/*
			 * Enable support for Post Formats.
			 *
			 * @link https://developer.wordpress.org/themes/functionality/post-formats/
			 */
			add_theme_support( 'post-formats', apply_filters( 'gwangi_post_formats_args', array(
				'aside',
				'image',
				'gallery',
				'quote',
				'link',
				'video',
				'audio',
				'status',
				'chat',
			) ) );

			// Set up the WordPress core custom background feature.
			add_theme_support( 'custom-background', apply_filters( 'gwangi_custom_background_args', array(
				'default-color'      => GWANGI_BODY_BACKGROUND,
				'default-image'      => GWANGI_BACKGROUND_IMAGE,
				'default-repeat'     => GWANGI_BACKGROUND_IMAGE_REPEAT,
				'default-position-x' => GWANGI_BACKGROUND_IMAGE_POSITION_X,
				'default-position-y' => GWANGI_BACKGROUND_IMAGE_POSITION_Y,
				'default-attachment' => GWANGI_BACKGROUND_IMAGE_ATTACHMENT,
				'default-size'       => GWANGI_BACKGROUND_IMAGE_SIZE,
			) ) );

			/**
			 * Set up the WordPress core custom header feature.
			 *
			 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
			 */
			add_theme_support( 'custom-header', apply_filters( 'gwangi_custom_header_args', array(
				'default-image' => '',
				'header-text'   => false,
				'width'         => 1850,
				'height'        => 550,
				'flex-width'    => true,
				'flex-height'   => true,
			) ) );

			/*
			 * Enable support for custom logo.
			 */
			add_theme_support( 'custom-logo', apply_filters( 'gwangi_custom_logo_args', array(
				'width'       => 350,
				'height'      => 350,
				'flex-height' => true,
			) ) );

			/*
			 * Enable support for WooCommerce plugin
			 * See https://docs.woothemes.com/document/declare-woocommerce-support-in-third-party-theme/
			 */
			add_theme_support( 'woocommerce' );

			// Indicate widget sidebars can use selective refresh in the Customizer.
			add_theme_support( 'customize-selective-refresh-widgets' );

			/*
			 * Enable support for Gutenberg features
			 * See https://wordpress.org/gutenberg/handbook/extensibility/theme-support/
			 */

			// Enable wide alignment for block
			add_theme_support( 'align-wide' );

			// Enable default block style
			add_theme_support( 'wp-block-styles' );

			// Responsive embedded content
			add_theme_support( 'responsive-embeds' );

			// Change default block color palettes
			add_theme_support( 'editor-color-palette', array(
				array(
					'name'  => esc_html__( 'Primary', 'gwangi' ),
					'slug'  => 'primary',
					'color' => GWANGI_BRAND_PRIMARY,
				),
				array(
					'name'  => esc_html__( 'Secondary', 'gwangi' ),
					'slug'  => 'secondary',
					'color' => GWANGI_BRAND_SECONDARY,
				),
				array(
					'name'  => esc_html__( 'Gray Dark', 'gwangi' ),
					'slug'  => 'gray-color',
					'color' => GWANGI_GRAY_DARK,
				),
				array(
					'name'  => esc_html__( 'Gray', 'gwangi' ),
					'slug'  => 'gray',
					'color' => GWANGI_GRAY,
				),
				array(
					'name'  => esc_html__( 'Gray Light', 'gwangi' ),
					'slug'  => 'gray-color',
					'color' => GWANGI_GRAY_LIGHT,
				),
				array(
					'name'  => esc_html__( 'Gray Lighter', 'gwangi' ),
					'slug'  => 'gray-color',
					'color' => GWANGI_GRAY_LIGHTER,
				),
				array(
					'name'  => esc_html__( 'Gray Lightest', 'gwangi' ),
					'slug'  => 'gray-color',
					'color' => GWANGI_GRAY_LIGHTEST,
				),
				array(
					'name'  => esc_html__( 'Info', 'gwangi' ),
					'slug'  => 'info',
					'color' => GWANGI_BRAND_INFO,
				),
				array(
					'name'  => esc_html__( 'Danger', 'gwangi' ),
					'slug'  => 'danger',
					'color' => GWANGI_BRAND_DANGER,
				),
				array(
					'name'  => esc_html__( 'Success', 'gwangi' ),
					'slug'  => 'success',
					'color' => GWANGI_BRAND_SUCCESS,
				),
				array(
					'name'  => esc_html__( 'Warning', 'gwangi' ),
					'slug'  => 'warning',
					'color' => GWANGI_BRAND_WARNING,
				),

			) );

			// Change default block font sizes palettes
			add_theme_support( 'editor-font-sizes', array(
				array(
					'name'      => esc_html__( 'Small', 'gwangi' ),
					'shortName' => esc_html__( 'S', 'gwangi' ),
					'size'      => 12,
					'slug'      => 'small',
				),
				array(
					'name'      => esc_html__( 'Regular', 'gwangi' ),
					'shortName' => esc_html__( 'M', 'gwangi' ),
					'size'      => 16,
					'slug'      => 'regular',
				),
				array(
					'name'      => esc_html__( 'Large', 'gwangi' ),
					'shortName' => esc_html__( 'L', 'gwangi' ),
					'size'      => 32,
					'slug'      => 'large',
				),
				array(
					'name'      => esc_html__( 'Larger', 'gwangi' ),
					'shortName' => esc_html__( 'XL', 'gwangi' ),
					'size'      => 38,
					'slug'      => 'larger',
				),
				array(
					'name'      => esc_html__( 'Largest', 'gwangi' ),
					'shortName' => esc_html__( 'XXL', 'gwangi' ),
					'size'      => 51,
					'slug'      => 'largest',
				),
			) );

			/**
			 * Add image sizes for post thumbnails.
			 */
			$image_sizes = apply_filters( 'gwangi_image_sizes', array(
				'thumbnail-12-cols-classic'      => array(
					'width'  => 1280,
					'height' => 600,
					'crop'   => true,
				),
				'thumbnail-6-6-cols-classic'     => array(
					'width'  => 700,
					'height' => 400,
					'crop'   => true,
				),
				'thumbnail-4-4-4-cols-classic'   => array(
					'width'  => 700,
					'height' => 500,
					'crop'   => true,
				),
				'thumbnail-3-3-3-3-cols-classic' => array(
					'width'  => 550,
					'height' => 390,
					'crop'   => true,
				),
				'thumbnail-12-cols-lateral'      => array(
					'width'  => 700,
					'height' => 520,
					'crop'   => true,
				),
				'thumbnail-6-6-cols-lateral'     => array(
					'width'  => 550,
					'height' => 650,
					'crop'   => true,
				),
			) );

			foreach ( $image_sizes as $name => $image_size ) {
				add_image_size( $name, $image_size['width'], $image_size['height'], $image_size['crop'] );
			}
		}

		/**
		 * Define names for the custom image sizes.
		 *
		 * @param array $sizes The array of custom sizes.
		 *
		 * @return array The array of custom sizes.
		 * @since 1.0.0
		 */
		public function add_image_size_names( $sizes ) {
			return array_merge( $sizes, array(
				'thumbnail-12-cols-classic'      => esc_html__( 'Thumbnail: One Column', 'gwangi' ),
				'thumbnail-6-6-cols-classic'     => esc_html__( 'Thumbnail: Two Columns', 'gwangi' ),
				'thumbnail-4-4-4-cols-classic'   => esc_html__( 'Thumbnail: Three Columns', 'gwangi' ),
				'thumbnail-3-3-3-3-cols-classic' => esc_html__( 'Thumbnail: Four Columns', 'gwangi' ),
				'thumbnail-12-cols-lateral'      => esc_html__( 'Thumbnail: One Column Lateral', 'gwangi' ),
				'thumbnail-6-6-cols-lateral'     => esc_html__( 'Thumbnail: Two Columns Lateral', 'gwangi' ),
			) );
		}


		/**
		 * Set the content width in pixels, based on the theme's design and stylesheet.
		 *
		 * @global int $content_width
		 * @since 1.0.0
		 */
		public function content_width() {
			$GLOBALS['content_width'] = apply_filters( 'gwangi_content_width', 1140 );
		}

		/**
		 * Register widget areas.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
		 * @since 1.0.0
		 */
		public function widgets_init() {
			$widget_areas = apply_filters( 'gwangi_widget_areas', array(
				'widget-area-10' => array(
					'id'          => 'sidebar-1',
					'name'        => esc_html__( 'Sidebar 1', 'gwangi' ),
					'description' => esc_html__( 'The left hand area for all pages.', 'gwangi' ),
				),
				'widget-area-20' => array(
					'id'          => 'footer-1',
					'name'        => esc_html__( 'Footer 1', 'gwangi' ),
					'description' => esc_html__( 'The first area for the footer of all pages.', 'gwangi' ),
				),
				'widget-area-21' => array(
					'id'          => 'footer-2',
					'name'        => esc_html__( 'Footer 2', 'gwangi' ),
					'description' => esc_html__( 'The second area for the footer of all pages.', 'gwangi' ),
				),
				'widget-area-22' => array(
					'id'          => 'footer-3',
					'name'        => esc_html__( 'Footer 3', 'gwangi' ),
					'description' => esc_html__( 'The third area for the footer of all pages.', 'gwangi' ),
				),
				'widget-area-23' => array(
					'id'          => 'footer-4',
					'name'        => esc_html__( 'Footer 4', 'gwangi' ),
					'description' => esc_html__( 'The fourth area for the footer of all pages.', 'gwangi' ),
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
					'before_title'  => '<h2 class="widget-title">',
					'after_title'   => '</h2>',
				) ) );
			}
		}

		/**
		 * Adds custom classes to the array of body classes.
		 *
		 * @param array $classes Classes for the body element.
		 * @since 1.0.0
		 *
		 * @return array
		 */
		public function add_body_classes( $classes ) {
			// Adds a class of group-blog to blogs with more than 1 published author.
			if ( is_multi_author() ) {
				$classes[] = 'group-blog';
			}

			// Adds a class of hfeed to non-singular pages.
			if ( ! is_singular() ) {
				$classes[] = 'hfeed';
			}

			// Adds a class of mobile to mobile devices pages.
			// @codingStandardsIgnoreLine
			if ( ! apply_filters( 'gwangi_is_mobile', wp_is_mobile() ) ) {
				$classes[] = 'mobile';
			}

			return $classes;
		}

		/**
		 * Get the URL for the Google Fonts to enqueue.
		 *
		 * @since 1.0.0
		 *
		 * @return string The URL for the Google Fonts.
		 */
		public function get_google_fonts_url() {
			$fonts_url = '';

			/*
			Translators: If there are characters in your language that are not
			supported by Lato, translate this to 'off'. Do not translate
			into your own language.
			*/
			$lato = esc_html_x( 'on', 'Lato font: on or off', 'gwangi' );

			if ( 'off' !== $lato ) {
				$font_families = array( 'Lato:300,400,400i,700,700i,900' );

				$query_args = array(
					'family' => rawurlencode( implode( '|', $font_families ) ),
					'subset' => rawurlencode( 'latin,latin-ext' ),
				);

				$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
			}

			return esc_url_raw( $fonts_url );
		}

		/**
		 * Enqueue scripts and stylesheets.
		 *
		 * @since 1.0.0
		 */
		public function wp_enqueue_scripts() {
			/**
			 * Enqueue styles.
			 */
			wp_enqueue_style( 'gwangi-google-fonts', $this->get_google_fonts_url(),               array(), GWANGI_VERSION );
			wp_enqueue_style( 'gwangi-style',        get_template_directory_uri() . '/style.css', array(), GWANGI_VERSION );

			/*
			 * Load style-rtl.css instead of style.css for RTL compatibility
			 */
			wp_style_add_data( 'gwangi-style', 'rtl', 'replace' );

			/**
			 * Enqueue scripts.
			 */
			wp_enqueue_script( 'gwangi-skip-link-focus-fix', get_template_directory_uri() . '/assets/js/skip-link-focus-fix.js', array(), '20130115', true );

			if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
				wp_enqueue_script( 'comment-reply' );
			}

			wp_enqueue_script( 'modernizr',                get_template_directory_uri() . '/assets/js/libs/modernizr.js',       array(), '3.6.0', false );
			wp_enqueue_script( 'popper',                   get_template_directory_uri() . '/assets/js/vendor/popper.min.js',    array(), '1.14.3', true );
			wp_enqueue_script( 'bootstrap',                get_template_directory_uri() . '/assets/js/vendor/bootstrap.min.js', array( 'jquery', 'popper' ), '4.1.3', true );
			wp_enqueue_script( 'gwangi',                   get_template_directory_uri() . '/assets/js/main.js',                 array( 'bootstrap' ), GWANGI_VERSION, true );
			wp_enqueue_script( 'gwangi-navigation-search', get_template_directory_uri() . '/assets/js/navigation-search.js',    array( 'jquery' ), GWANGI_VERSION, true );

			wp_enqueue_script( 'jquery-masonry' );
			wp_enqueue_script( 'gwangi-grid',              get_template_directory_uri() . '/assets/js/grid.js',                 array( 'jquery', 'jquery-masonry' ), GWANGI_VERSION, true );
		}

		/**
		 * Add a pingback url auto-discovery header for singularly identifiable articles.
		 *
		 * @since 1.0.0
		 */
		public function pingback_header() {
			if ( is_singular() && pings_open() ) {
				echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
			}
		}
	}
endif;

return new Gwangi();
