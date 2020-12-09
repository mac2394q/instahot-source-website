<?php
/**
 * Gwangi_Merlin Class
 *
 * @author  Themosaurus
 * @since   1.0.0
 * @package gwangi
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Gwangi_Merlin' ) ) :
	/**
	 * The Gwangi Merlin Activation class
	 */
	class Gwangi_Merlin {

		/**
		 * Setup class.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			if ( is_admin() && class_exists( 'TGM_Plugin_Activation' ) && isset( $_GET['demo'] ) ) {
				add_filter( 'gwangi_tgm_plugin_activation_register_plugins', array( $this, 'add_tgmpa_demo_plugins'     ) );
				add_filter( 'import_start',                                  array( $this, 'change_permalink_structure' ) );

				// Init BP xProfile Location field type during demo import
				if ( function_exists( 'pp_loc_init' ) ) {
					add_filter( 'xprofile_field_types', function( $types ) {
						$types[] = 'location';
						return array_unique( $types );
					}, 10, 1 );
				}
			}

			$theme = wp_get_theme();

			add_filter( 'merlin_import_files', array( $this, 'merlin_import_files' ) );
			add_action( 'merlin_after_all_import', array( $this, 'merlin_after_import_setup' ) );
			add_filter( "{$theme->template}_merlin_steps", array( $this, 'change_merlin_steps' ) );

			// Prevent WooCommerce from messing with the plugins installation step
			add_filter( 'woocommerce_prevent_automatic_wizard_redirect', '__return_true' );

			// Prevent Elementor from messing with the plugins installation step
			add_action( 'init', array( $this, 'prevent_elementor_redirect' ) );

			$this->init();
		}

		/**
		 * Get the demo setups arguments that will be sent to Merlin WP
		 *
		 * @return array
		 */
		private function get_demo_setups_args() {
			return apply_filters( 'gwangi_demo_setups_args', array(
				'classic'   => array(
					'import_file_name'           => 'Gwangi Classic Demo',
					'import_file_url'            => 'https://files.themosaurus.com/gwangi/demos/classic/demo-content.xml',
					'import_widget_file_url'     => 'https://files.themosaurus.com/gwangi/demos/classic/widgets.wie',
					'import_customizer_file_url' => 'https://files.themosaurus.com/gwangi/demos/classic/customizer.dat',
					'import_file_screenshot'     => get_template_directory_uri() . '/assets/images/screenshots/classic.png',
					'import_notice'              => esc_html__( 'Visit doc.themosaurus.com to get the full documentation for the theme', 'gwangi' ),
					'preview_url'                => 'http://classic.gwangi-theme.com/',
					'tgmpa'                      => array(
						array(
							'name'     => 'Author Avatars List',
							'slug'     => 'author-avatars',
							'required' => false,
						),
						array(
							'name'     => 'BuddyPress',
							'slug'     => 'buddypress',
							'required' => true,
						),
						array(
							'name'     => 'bbPress',
							'slug'     => 'bbpress',
							'required' => false,
						),
						array(
							'name'     => 'BP Better Messages',
							'slug'     => 'bp-better-messages',
							'required' => false,
						),
						array(
							'name'     => 'BP Profile Search',
							'slug'     => 'bp-profile-search',
							'required' => false,
						),
						array(
							'name'         => 'BuddyPress Follow',
							'source'       => 'https://github.com/r-a-y/buddypress-followers/archive/master.zip',
							'required'     => false,
							'external_url' => 'https://github.com/r-a-y/buddypress-followers',
						),
						array(
							'name'     => 'JetPack',
							'slug'     => 'jetpack',
							'required' => false,
						),
						array(
							'name'     => 'Menu Image',
							'slug'     => 'menu-image',
							'required' => false,
						),
						array(
							'name'     => 'Paid Memberships Pro',
							'slug'     => 'paid-memberships-pro',
							'required' => false,
						),
						array(
							'name'     => 'Paid Memberships Pro - BuddyPress Add On',
							'slug'     => 'pmpro-buddypress',
							'required' => false,
						),
						array(
							'name'     => 'rtMedia for WordPress, BuddyPress and bbPress',
							'slug'     => 'buddypress-media',
							'required' => false,
						),
						array(
							'name'     => 'Social Articles',
							'slug'     => 'social-articles',
							'required' => false,
						),
						array(
							'name'     => 'Verified Member for BuddyPress',
							'slug'     => 'bp-verified-member',
							'required' => false,
						),
						array(
							'name'         => 'Grimlock for BuddyPress',
							'slug'         => 'grimlock-buddypress',
							'source'       => 'http://files.themosaurus.com/grimlock-buddypress/grimlock-buddypress.zip',
							'required'     => true,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock Hero',
							'slug'         => 'grimlock-hero',
							'source'       => 'http://files.themosaurus.com/grimlock-hero/grimlock-hero.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock Isotope',
							'slug'         => 'grimlock-isotope',
							'source'       => 'http://files.themosaurus.com/grimlock-isotope/grimlock-isotope.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock for Author Avatars List',
							'slug'         => 'grimlock-author-avatars',
							'source'       => 'http://files.themosaurus.com/grimlock-author-avatars/grimlock-author-avatars.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock for bbPress',
							'slug'         => 'grimlock-bbpress',
							'source'       => 'http://files.themosaurus.com/grimlock-bbpress/grimlock-bbpress.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock for Jetpack',
							'slug'         => 'grimlock-jetpack',
							'source'       => 'http://files.themosaurus.com/grimlock-jetpack/grimlock-jetpack.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock for Paid Memberships Pro',
							'slug'         => 'grimlock-paid-memberships-pro',
							'source'       => 'http://files.themosaurus.com/grimlock-paid-memberships-pro/grimlock-paid-memberships-pro.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
					),
				),
				'community' => array(
					'import_file_name'           => 'Gwangi Community Demo',
					'import_file_url'            => 'https://files.themosaurus.com/gwangi/demos/community/demo-content.xml',
					'import_widget_file_url'     => 'https://files.themosaurus.com/gwangi/demos/community/widgets.wie',
					'import_customizer_file_url' => 'https://files.themosaurus.com/gwangi/demos/community/customizer.dat',
					'import_file_screenshot'     => get_template_directory_uri() . '/assets/images/screenshots/community.png',
					'import_notice'              => esc_html__( 'Visit doc.themosaurus.com to get the full documentation for the theme', 'gwangi' ),
					'preview_url'                => 'http://community.gwangi-theme.com/',
					'tgmpa'                      => array(
						array(
							'name'     => 'Author Avatars List',
							'slug'     => 'author-avatars',
							'required' => false,
						),
						array(
							'name'     => 'BuddyPress',
							'slug'     => 'buddypress',
							'required' => true,
						),
						array(
							'name'     => 'bbPress',
							'slug'     => 'bbpress',
							'required' => false,
						),
						array(
							'name'     => 'BP Better Messages',
							'slug'     => 'bp-better-messages',
							'required' => false,
						),
						array(
							'name'     => 'BP Profile Search',
							'slug'     => 'bp-profile-search',
							'required' => false,
						),
						array(
							'name'         => 'BuddyPress Follow',
							'source'       => 'https://github.com/r-a-y/buddypress-followers/archive/master.zip',
							'required'     => false,
							'external_url' => 'https://github.com/r-a-y/buddypress-followers',
						),
						array(
							'name'     => 'JetPack',
							'slug'     => 'jetpack',
							'required' => false,
						),
						array(
							'name'     => 'Menu Image',
							'slug'     => 'menu-image',
							'required' => false,
						),
						array(
							'name'     => 'Paid Memberships Pro',
							'slug'     => 'paid-memberships-pro',
							'required' => false,
						),
						array(
							'name'     => 'Paid Memberships Pro - BuddyPress Add On',
							'slug'     => 'pmpro-buddypress',
							'required' => false,
						),
						array(
							'name'     => 'rtMedia for WordPress, BuddyPress and bbPress',
							'slug'     => 'buddypress-media',
							'required' => false,
						),
						array(
							'name'     => 'Social Articles',
							'slug'     => 'social-articles',
							'required' => false,
						),
						array(
							'name'     => 'Verified Member for BuddyPress',
							'slug'     => 'bp-verified-member',
							'required' => false,
						),
						array(
							'name'         => 'Grimlock for BuddyPress',
							'slug'         => 'grimlock-buddypress',
							'source'       => 'http://files.themosaurus.com/grimlock-buddypress/grimlock-buddypress.zip',
							'required'     => true,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock Hero',
							'slug'         => 'grimlock-hero',
							'source'       => 'http://files.themosaurus.com/grimlock-hero/grimlock-hero.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock Isotope',
							'slug'         => 'grimlock-isotope',
							'source'       => 'http://files.themosaurus.com/grimlock-isotope/grimlock-isotope.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock for Author Avatars List',
							'slug'         => 'grimlock-author-avatars',
							'source'       => 'http://files.themosaurus.com/grimlock-author-avatars/grimlock-author-avatars.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock for bbPress',
							'slug'         => 'grimlock-bbpress',
							'source'       => 'http://files.themosaurus.com/grimlock-bbpress/grimlock-bbpress.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock for Jetpack',
							'slug'         => 'grimlock-jetpack',
							'source'       => 'http://files.themosaurus.com/grimlock-jetpack/grimlock-jetpack.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock for Paid Memberships Pro',
							'slug'         => 'grimlock-paid-memberships-pro',
							'source'       => 'http://files.themosaurus.com/grimlock-paid-memberships-pro/grimlock-paid-memberships-pro.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
					),
				),
				'dating'    => array(
					'import_file_name'           => 'Gwangi Dating Demo',
					/* translators: 1: Opening <a> tag 2: Closing <a> tag 3: Opening <strong> tag 4: Closing <strong> tag */
					'import_file_warning'        => '<strong>' . esc_html__( 'Heads up!', 'gwangi' ) . '</strong><p>' . sprintf( esc_html__( 'This demo uses the %1$sBP Maps for Members%2$s plugin to add the members map. This is a paid plugin and it is not included with Gwangi. If you want to use the members map, we recommend that you manually install it %3$safter%4$s importing this demo.', 'gwangi' ), '<a target="_blank" href="https://www.philopress.com/products/bp-maps-for-members/">', '</a>', '<strong>', '</strong>' ) . '</p>',
					'import_file_url'            => 'https://files.themosaurus.com/gwangi/demos/dating/demo-content.xml',
					'import_widget_file_url'     => 'https://files.themosaurus.com/gwangi/demos/dating/widgets.wie',
					'import_customizer_file_url' => 'https://files.themosaurus.com/gwangi/demos/dating/customizer.dat',
					'import_file_screenshot'     => get_template_directory_uri() . '/assets/images/screenshots/dating.png',
					'import_notice'              => esc_html__( 'Visit doc.themosaurus.com to get the full documentation for the theme', 'gwangi' ),
					'import_finished_warning'    => '<strong>' . esc_html__( 'Heads up!', 'gwangi' ) . '</strong><p>' . sprintf( esc_html__( 'To complete this demo setup, don\'t forget to install the %1$sBP Maps for Members%2$s plugin if you want to use the members map.', 'gwangi' ), '<a href="https://www.philopress.com/products/bp-maps-for-members/" target="_blank">', '</a>' ) . '</p>',
					'preview_url'                => 'http://dating.gwangi-theme.com/',
					'tgmpa'                      => array(
						array(
							'name'     => 'Author Avatars List',
							'slug'     => 'author-avatars',
							'required' => false,
						),
						array(
							'name'     => 'BuddyPress',
							'slug'     => 'buddypress',
							'required' => true,
						),
						array(
							'name'     => 'bbPress',
							'slug'     => 'bbpress',
							'required' => false,
						),
						array(
							'name'     => 'BP Better Messages',
							'slug'     => 'bp-better-messages',
							'required' => false,
						),
						array(
							'name'     => 'BP Profile Search',
							'slug'     => 'bp-profile-search',
							'required' => false,
						),
						array(
							'name'     => 'BP xProfile Location',
							'slug'     => 'bp-xprofile-location',
							'required' => false,
						),
						array(
							'name'         => 'BuddyPress Follow',
							'source'       => 'https://github.com/r-a-y/buddypress-followers/archive/master.zip',
							'required'     => false,
							'external_url' => 'https://github.com/r-a-y/buddypress-followers',
						),
						array(
							'name'     => 'JetPack',
							'slug'     => 'jetpack',
							'required' => false,
						),
						array(
							'name'     => 'Match me for BuddyPress',
							'slug'     => 'match-me-for-buddypress',
							'required' => false,
						),
						array(
							'name'     => 'Menu Image',
							'slug'     => 'menu-image',
							'required' => false,
						),
						array(
							'name'     => 'Mutual Buddies',
							'slug'     => 'mailchimp-for-wp',
							'required' => false,
						),
						array(
							'name'     => 'Paid Memberships Pro',
							'slug'     => 'paid-memberships-pro',
							'required' => false,
						),
						array(
							'name'     => 'Paid Memberships Pro - BuddyPress Add On',
							'slug'     => 'pmpro-buddypress',
							'required' => false,
						),
						array(
							'name'     => 'rtMedia for WordPress, BuddyPress and bbPress',
							'slug'     => 'buddypress-media',
							'required' => false,
						),
						array(
							'name'     => 'Social Articles',
							'slug'     => 'social-articles',
							'required' => false,
						),
						array(
							'name'     => 'Verified Member for BuddyPress',
							'slug'     => 'bp-verified-member',
							'required' => false,
						),
						array(
							'name'         => 'Grimlock for BuddyPress',
							'slug'         => 'grimlock-buddypress',
							'source'       => 'http://files.themosaurus.com/grimlock-buddypress/grimlock-buddypress.zip',
							'required'     => true,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock Hero',
							'slug'         => 'grimlock-hero',
							'source'       => 'http://files.themosaurus.com/grimlock-hero/grimlock-hero.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock Isotope',
							'slug'         => 'grimlock-isotope',
							'source'       => 'http://files.themosaurus.com/grimlock-isotope/grimlock-isotope.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock Video',
							'slug'         => 'grimlock-video',
							'source'       => 'http://files.themosaurus.com/grimlock-video/grimlock-video.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock for Author Avatars List',
							'slug'         => 'grimlock-author-avatars',
							'source'       => 'http://files.themosaurus.com/grimlock-author-avatars/grimlock-author-avatars.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock for bbPress',
							'slug'         => 'grimlock-bbpress',
							'source'       => 'http://files.themosaurus.com/grimlock-bbpress/grimlock-bbpress.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock for Jetpack',
							'slug'         => 'grimlock-jetpack',
							'source'       => 'http://files.themosaurus.com/grimlock-jetpack/grimlock-jetpack.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock for Paid Memberships Pro',
							'slug'         => 'grimlock-paid-memberships-pro',
							'source'       => 'http://files.themosaurus.com/grimlock-paid-memberships-pro/grimlock-paid-memberships-pro.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
					),
				),
				'shop'      => array(
					'import_file_name'           => 'Gwangi Shop Demo',
					'import_file_url'            => 'https://files.themosaurus.com/gwangi/demos/shop/demo-content.xml',
					'import_widget_file_url'     => 'https://files.themosaurus.com/gwangi/demos/shop/widgets.wie',
					'import_customizer_file_url' => 'https://files.themosaurus.com/gwangi/demos/shop/customizer.dat',
					'import_file_screenshot'     => get_template_directory_uri() . '/assets/images/screenshots/shop.png',
					'import_notice'              => esc_html__( 'Visit doc.themosaurus.com to get the full documentation for the theme', 'gwangi' ),
					'preview_url'                => 'http://shop.gwangi-theme.com/',
					'tgmpa'                      => array(
						array(
							'name'     => 'Menu Image',
							'slug'     => 'menu-image',
							'required' => false,
						),
						array(
							'name'     => 'WC Secondary Product Thumbnail',
							'slug'     => 'wc-secondary-product-thumbnail',
							'required' => false,
						),
						array(
							'name'     => 'WooCommerce',
							'slug'     => 'woocommerce',
							'required' => false,
						),
						array(
							'name'         => 'WooCommerce Quantity Increment',
							'slug'         => 'woocommerce-quantity-increment',
							'source'       => 'https://github.com/woocommerce/WooCommerce-Quantity-Increment/archive/master.zip',
							'required'     => false,
							'external_url' => 'https://github.com/woocommerce/WooCommerce-Quantity-Increment/',
						),
						array(
							'name'     => 'YITH Infinite Scrolling',
							'slug'     => 'yith-infinite-scrolling',
							'required' => false,
						),
						array(
							'name'     => 'YITH WooCommerce Ajax Search',
							'slug'     => 'yith-woocommerce-ajax-search',
							'required' => false,
						),
						array(
							'name'     => 'YITH WooCommerce Quick View',
							'slug'     => 'yith-woocommerce-quick-view',
							'required' => false,
						),
						array(
							'name'     => 'YITH WooCommerce Wishlist',
							'slug'     => 'yith-woocommerce-wishlist',
							'required' => false,
						),
						array(
							'name'         => 'Grimlock for WooCommerce',
							'slug'         => 'grimlock-woocommerce',
							'source'       => 'http://files.themosaurus.com/grimlock-woocommerce/grimlock-woocommerce.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock Hero',
							'slug'         => 'grimlock-hero',
							'source'       => 'http://files.themosaurus.com/grimlock-hero/grimlock-hero.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock Isotope',
							'slug'         => 'grimlock-isotope',
							'source'       => 'http://files.themosaurus.com/grimlock-isotope/grimlock-isotope.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
					),
				),
				'youzer'    => array(
					'import_file_name'           => 'Gwangi Youzer Demo',
					/* translators: 1: Opening <a> tag 2: Closing <a> tag 3: Opening <strong> tag 4: Closing <strong> tag */
					'import_file_warning'        => '<strong>' . esc_html__( 'Heads up!', 'gwangi' ) . '</strong><p>' . sprintf( esc_html__( 'This demo is based on the %1$sYouzer%2$s plugin. This is a paid plugin and it is not included with Gwangi. We recommend that you manually install it %3$safter%4$s importing this demo.', 'gwangi' ), '<a target="_blank" href="https://youzer.kainelabs.com/">', '</a>', '<strong>', '</strong>' ) . '</p>',
					'import_file_url'            => 'https://files.themosaurus.com/gwangi/demos/youzer/demo-content.xml',
					'import_widget_file_url'     => 'https://files.themosaurus.com/gwangi/demos/youzer/widgets.wie',
					'import_customizer_file_url' => 'https://files.themosaurus.com/gwangi/demos/youzer/customizer.dat',
					'import_file_screenshot'     => get_template_directory_uri() . '/assets/images/screenshots/youzer.png',
					'import_notice'              => esc_html__( 'Visit doc.themosaurus.com to get the full documentation for the theme', 'gwangi' ),
					'import_finished_warning'    => '<strong>' . esc_html__( 'Heads up!', 'gwangi' ) . '</strong><p>' . sprintf( esc_html__( 'To complete this demo setup, don\'t forget to install the %1$sYouzer%2$s plugin.', 'gwangi' ), '<a href="https://youzer.kainelabs.com/" target="_blank">', '</a>' ) . '</p>',
					'preview_url'                => 'http://youzer.gwangi-theme.com/',
					'tgmpa'                      => array(
						array(
							'name'     => 'Author Avatars List',
							'slug'     => 'author-avatars',
							'required' => false,
						),
						array(
							'name'     => 'BuddyPress',
							'slug'     => 'buddypress',
							'required' => true,
						),
						array(
							'name'     => 'bbPress',
							'slug'     => 'bbpress',
							'required' => false,
						),
						array(
							'name'     => 'BP Better Messages',
							'slug'     => 'bp-better-messages',
							'required' => false,
						),
						array(
							'name'     => 'BP Profile Search',
							'slug'     => 'bp-profile-search',
							'required' => false,
						),
						array(
							'name'     => 'JetPack',
							'slug'     => 'jetpack',
							'required' => false,
						),
						array(
							'name'     => 'Menu Image',
							'slug'     => 'menu-image',
							'required' => false,
						),
						array(
							'name'     => 'Paid Memberships Pro',
							'slug'     => 'paid-memberships-pro',
							'required' => false,
						),
						array(
							'name'     => 'Paid Memberships Pro - BuddyPress Add On',
							'slug'     => 'pmpro-buddypress',
							'required' => false,
						),
						array(
							'name'     => 'rtMedia for WordPress, BuddyPress and bbPress',
							'slug'     => 'buddypress-media',
							'required' => false,
						),
						array(
							'name'     => 'Social Articles',
							'slug'     => 'social-articles',
							'required' => false,
						),
						array(
							'name'         => 'Grimlock for BuddyPress',
							'slug'         => 'grimlock-buddypress',
							'source'       => 'http://files.themosaurus.com/grimlock-buddypress/grimlock-buddypress.zip',
							'required'     => true,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock Hero',
							'slug'         => 'grimlock-hero',
							'source'       => 'http://files.themosaurus.com/grimlock-hero/grimlock-hero.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock Isotope',
							'slug'         => 'grimlock-isotope',
							'source'       => 'http://files.themosaurus.com/grimlock-isotope/grimlock-isotope.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock for Author Avatars List',
							'slug'         => 'grimlock-author-avatars',
							'source'       => 'http://files.themosaurus.com/grimlock-author-avatars/grimlock-author-avatars.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock for bbPress',
							'slug'         => 'grimlock-bbpress',
							'source'       => 'http://files.themosaurus.com/grimlock-bbpress/grimlock-bbpress.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock for Jetpack',
							'slug'         => 'grimlock-jetpack',
							'source'       => 'http://files.themosaurus.com/grimlock-jetpack/grimlock-jetpack.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock for Paid Memberships Pro',
							'slug'         => 'grimlock-paid-memberships-pro',
							'source'       => 'http://files.themosaurus.com/grimlock-paid-memberships-pro/grimlock-paid-memberships-pro.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
					),
				),
				'spiritual' => array(
					'import_file_name'           => 'Gwangi Spiritual Demo',
					'import_file_url'            => 'https://files.themosaurus.com/gwangi/demos/spiritual/demo-content.xml',
					'import_widget_file_url'     => 'https://files.themosaurus.com/gwangi/demos/spiritual/widgets.wie',
					'import_customizer_file_url' => 'https://files.themosaurus.com/gwangi/demos/spiritual/customizer.dat',
					'import_file_screenshot'     => get_template_directory_uri() . '/assets/images/screenshots/spiritual.png',
					'import_notice'              => esc_html__( 'Visit doc.themosaurus.com to get the full documentation for the theme', 'gwangi' ),
					'preview_url'                => 'http://spiritual.gwangi-theme.com/',
					'tgmpa'                      => array(
						array(
							'name'     => 'Author Avatars List',
							'slug'     => 'author-avatars',
							'required' => false,
						),
						array(
							'name'     => 'BuddyPress',
							'slug'     => 'buddypress',
							'required' => true,
						),
						array(
							'name'     => 'bbPress',
							'slug'     => 'bbpress',
							'required' => false,
						),
						array(
							'name'     => 'BP Better Messages',
							'slug'     => 'bp-better-messages',
							'required' => false,
						),
						array(
							'name'     => 'BP Profile Search',
							'slug'     => 'bp-profile-search',
							'required' => false,
						),
						array(
							'name'         => 'BuddyPress Follow',
							'source'       => 'https://github.com/r-a-y/buddypress-followers/archive/master.zip',
							'required'     => false,
							'external_url' => 'https://github.com/r-a-y/buddypress-followers',
						),
						array(
							'name'     => 'BuddyPress Global Search',
							'slug'     => 'buddypress-global-search',
							'required' => false,
						),
						array(
							'name'     => 'JetPack',
							'slug'     => 'jetpack',
							'required' => false,
						),
						array(
							'name'     => 'Menu Image',
							'slug'     => 'menu-image',
							'required' => false,
						),
						array(
							'name'     => 'Paid Memberships Pro',
							'slug'     => 'paid-memberships-pro',
							'required' => false,
						),
						array(
							'name'     => 'Paid Memberships Pro - BuddyPress Add On',
							'slug'     => 'pmpro-buddypress',
							'required' => false,
						),
						array(
							'name'     => 'rtMedia for WordPress, BuddyPress and bbPress',
							'slug'     => 'buddypress-media',
							'required' => false,
						),
						array(
							'name'     => 'Social Articles',
							'slug'     => 'social-articles',
							'required' => false,
						),
						array(
							'name'     => 'Verified Member for BuddyPress',
							'slug'     => 'bp-verified-member',
							'required' => false,
						),
						array(
							'name'         => 'Grimlock for BuddyPress',
							'slug'         => 'grimlock-buddypress',
							'source'       => 'http://files.themosaurus.com/grimlock-buddypress/grimlock-buddypress.zip',
							'required'     => true,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock Hero',
							'slug'         => 'grimlock-hero',
							'source'       => 'http://files.themosaurus.com/grimlock-hero/grimlock-hero.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock Isotope',
							'slug'         => 'grimlock-isotope',
							'source'       => 'http://files.themosaurus.com/grimlock-isotope/grimlock-isotope.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock for Author Avatars List',
							'slug'         => 'grimlock-author-avatars',
							'source'       => 'http://files.themosaurus.com/grimlock-author-avatars/grimlock-author-avatars.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock for bbPress',
							'slug'         => 'grimlock-bbpress',
							'source'       => 'http://files.themosaurus.com/grimlock-bbpress/grimlock-bbpress.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock for Jetpack',
							'slug'         => 'grimlock-jetpack',
							'source'       => 'http://files.themosaurus.com/grimlock-jetpack/grimlock-jetpack.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock for Paid Memberships Pro',
							'slug'         => 'grimlock-paid-memberships-pro',
							'source'       => 'http://files.themosaurus.com/grimlock-paid-memberships-pro/grimlock-paid-memberships-pro.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
					),
				),
				'mentor'    => array(
					'import_file_name'           => 'Gwangi Mentor Demo',
					'import_file_url'            => 'https://files.themosaurus.com/gwangi/demos/mentor/demo-content.xml',
					'import_widget_file_url'     => 'https://files.themosaurus.com/gwangi/demos/mentor/widgets.wie',
					'import_customizer_file_url' => 'https://files.themosaurus.com/gwangi/demos/mentor/customizer.dat',
					'import_file_screenshot'     => get_template_directory_uri() . '/assets/images/screenshots/mentor.png',
					'import_notice'              => esc_html__( 'Visit doc.themosaurus.com to get the full documentation for the theme', 'gwangi' ),
					'preview_url'                => 'http://mentor.gwangi-theme.com/',
					'tgmpa'                      => array(
						array(
							'name'     => 'Author Avatars List',
							'slug'     => 'author-avatars',
							'required' => false,
						),
						array(
							'name'     => 'BuddyPress',
							'slug'     => 'buddypress',
							'required' => true,
						),
						array(
							'name'     => 'bbPress',
							'slug'     => 'bbpress',
							'required' => false,
						),
						array(
							'name'     => 'BP Better Messages',
							'slug'     => 'bp-better-messages',
							'required' => false,
						),
						array(
							'name'     => 'BP Profile Search',
							'slug'     => 'bp-profile-search',
							'required' => false,
						),
						array(
							'name'         => 'BuddyPress Follow',
							'source'       => 'https://github.com/r-a-y/buddypress-followers/archive/master.zip',
							'required'     => false,
							'external_url' => 'https://github.com/r-a-y/buddypress-followers',
						),
						array(
							'name'     => 'BuddyPress Member Reviews',
							'slug'     => 'bp-user-profile-reviews',
							'required' => false,
						),
						array(
							'name'     => 'JetPack',
							'slug'     => 'jetpack',
							'required' => false,
						),
						array(
							'name'     => 'Menu Image',
							'slug'     => 'menu-image',
							'required' => false,
						),
						array(
							'name'     => 'Paid Memberships Pro',
							'slug'     => 'paid-memberships-pro',
							'required' => false,
						),
						array(
							'name'     => 'Paid Memberships Pro - BuddyPress Add On',
							'slug'     => 'pmpro-buddypress',
							'required' => false,
						),
						array(
							'name'     => 'rtMedia for WordPress, BuddyPress and bbPress',
							'slug'     => 'buddypress-media',
							'required' => false,
						),
						array(
							'name'     => 'Social Articles',
							'slug'     => 'social-articles',
							'required' => false,
						),
						array(
							'name'     => 'Verified Member for BuddyPress',
							'slug'     => 'bp-verified-member',
							'required' => false,
						),
						array(
							'name'         => 'Grimlock for BuddyPress',
							'slug'         => 'grimlock-buddypress',
							'source'       => 'http://files.themosaurus.com/grimlock-buddypress/grimlock-buddypress.zip',
							'required'     => true,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock Hero',
							'slug'         => 'grimlock-hero',
							'source'       => 'http://files.themosaurus.com/grimlock-hero/grimlock-hero.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock Isotope',
							'slug'         => 'grimlock-isotope',
							'source'       => 'http://files.themosaurus.com/grimlock-isotope/grimlock-isotope.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock for Author Avatars List',
							'slug'         => 'grimlock-author-avatars',
							'source'       => 'http://files.themosaurus.com/grimlock-author-avatars/grimlock-author-avatars.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock for bbPress',
							'slug'         => 'grimlock-bbpress',
							'source'       => 'http://files.themosaurus.com/grimlock-bbpress/grimlock-bbpress.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock for Jetpack',
							'slug'         => 'grimlock-jetpack',
							'source'       => 'http://files.themosaurus.com/grimlock-jetpack/grimlock-jetpack.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock for Paid Memberships Pro',
							'slug'         => 'grimlock-paid-memberships-pro',
							'source'       => 'http://files.themosaurus.com/grimlock-paid-memberships-pro/grimlock-paid-memberships-pro.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
					),
				),
				'nightlife' => array(
					'import_file_name'           => 'Gwangi Nightlife Demo',
					'import_file_url'            => 'https://files.themosaurus.com/gwangi/demos/nightlife/demo-content.xml',
					'import_widget_file_url'     => 'https://files.themosaurus.com/gwangi/demos/nightlife/widgets.wie',
					'import_customizer_file_url' => 'https://files.themosaurus.com/gwangi/demos/nightlife/customizer.dat',
					'import_file_screenshot'     => get_template_directory_uri() . '/assets/images/screenshots/nightlife.png',
					'import_notice'              => esc_html__( 'Visit doc.themosaurus.com to get the full documentation for the theme', 'gwangi' ),
					'preview_url'                => 'http://nightlife.gwangi-theme.com/',
					'tgmpa'                      => array(
						array(
							'name'     => 'Author Avatars List',
							'slug'     => 'author-avatars',
							'required' => false,
						),
						array(
							'name'     => 'BuddyPress',
							'slug'     => 'buddypress',
							'required' => true,
						),
						array(
							'name'     => 'bbPress',
							'slug'     => 'bbpress',
							'required' => false,
						),
						array(
							'name'     => 'BP Better Messages',
							'slug'     => 'bp-better-messages',
							'required' => false,
						),
						array(
							'name'     => 'BP Profile Search',
							'slug'     => 'bp-profile-search',
							'required' => false,
						),
						array(
							'name'         => 'BuddyPress Follow',
							'source'       => 'https://github.com/r-a-y/buddypress-followers/archive/master.zip',
							'required'     => false,
							'external_url' => 'https://github.com/r-a-y/buddypress-followers',
						),
						array(
							'name'     => 'BuddyPress Global Search',
							'slug'     => 'buddypress-global-search',
							'required' => false,
						),
						array(
							'name'     => 'JetPack',
							'slug'     => 'jetpack',
							'required' => false,
						),
						array(
							'name'     => 'Menu Image',
							'slug'     => 'menu-image',
							'required' => false,
						),
						array(
							'name'     => 'Paid Memberships Pro',
							'slug'     => 'paid-memberships-pro',
							'required' => false,
						),
						array(
							'name'     => 'Paid Memberships Pro - BuddyPress Add On',
							'slug'     => 'pmpro-buddypress',
							'required' => false,
						),
						array(
							'name'     => 'rtMedia for WordPress, BuddyPress and bbPress',
							'slug'     => 'buddypress-media',
							'required' => false,
						),
						array(
							'name'     => 'Social Articles',
							'slug'     => 'social-articles',
							'required' => false,
						),
						array(
							'name'     => 'The Events Calendar',
							'slug'     => 'the-events-calendar',
							'required' => false,
						),
						array(
							'name'     => 'Verified Member for BuddyPress',
							'slug'     => 'bp-verified-member',
							'required' => false,
						),
						array(
							'name'         => 'Grimlock for BuddyPress',
							'slug'         => 'grimlock-buddypress',
							'source'       => 'http://files.themosaurus.com/grimlock-buddypress/grimlock-buddypress.zip',
							'required'     => true,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock Hero',
							'slug'         => 'grimlock-hero',
							'source'       => 'http://files.themosaurus.com/grimlock-hero/grimlock-hero.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock Isotope',
							'slug'         => 'grimlock-isotope',
							'source'       => 'http://files.themosaurus.com/grimlock-isotope/grimlock-isotope.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock for Author Avatars List',
							'slug'         => 'grimlock-author-avatars',
							'source'       => 'http://files.themosaurus.com/grimlock-author-avatars/grimlock-author-avatars.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock for The Events Calendar',
							'slug'         => 'grimlock-the-events-calendar',
							'source'       => 'http://files.themosaurus.com/grimlock-the-events-calendar/grimlock-the-events-calendar.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock for bbPress',
							'slug'         => 'grimlock-bbpress',
							'source'       => 'http://files.themosaurus.com/grimlock-bbpress/grimlock-bbpress.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock for Jetpack',
							'slug'         => 'grimlock-jetpack',
							'source'       => 'http://files.themosaurus.com/grimlock-jetpack/grimlock-jetpack.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock for Paid Memberships Pro',
							'slug'         => 'grimlock-paid-memberships-pro',
							'source'       => 'http://files.themosaurus.com/grimlock-paid-memberships-pro/grimlock-paid-memberships-pro.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
					),
				),
				'learn'     => array(
					'import_file_name'           => 'Gwangi Learn Demo',
					/* translators: 1: Opening <a> tag 2: Closing <a> tag 3: Opening <strong> tag 4: Closing <strong> tag */
					'import_file_warning'        => '<strong>' . esc_html__( 'Heads up!', 'gwangi' ) . '</strong><p>' . sprintf( esc_html__( 'This demo is based on the %1$sLearnDash%2$s plugin. This is a paid plugin and it is not included with Gwangi. We recommend that you manually install it %3$sbefore%4$s importing this demo.', 'gwangi' ), '<a target="_blank" href="https://www.learndash.com/">', '</a>', '<strong>', '</strong>' ) . '</p>',
					'import_file_url'            => 'https://files.themosaurus.com/gwangi/demos/learn/demo-content.xml',
					'import_widget_file_url'     => 'https://files.themosaurus.com/gwangi/demos/learn/widgets.wie',
					'import_customizer_file_url' => 'https://files.themosaurus.com/gwangi/demos/learn/customizer.dat',
					'import_file_screenshot'     => get_template_directory_uri() . '/assets/images/screenshots/learn.png',
					'import_notice'              => esc_html__( 'Visit doc.themosaurus.com to get the full documentation for the theme', 'gwangi' ),
					'import_finished_warning'    => '<strong>' . esc_html__( 'Heads up!', 'gwangi' ) . '</strong><p>' . sprintf( esc_html__( 'To complete this demo setup, don\'t forget to install the %1$sLearnDash%2$s plugin if you haven\'t already.', 'gwangi' ), '<a href="https://www.learndash.com/" target="_blank">', '</a>' ) . '</p>',
					'preview_url'                => 'http://learn.gwangi-theme.com/',
					'tgmpa'                      => array(
						array(
							'name'     => 'Author Avatars List',
							'slug'     => 'author-avatars',
							'required' => false,
						),
						array(
							'name'     => 'BuddyPress',
							'slug'     => 'buddypress',
							'required' => true,
						),
						array(
							'name'     => 'bbPress',
							'slug'     => 'bbpress',
							'required' => false,
						),
						array(
							'name'     => 'BP Better Messages',
							'slug'     => 'bp-better-messages',
							'required' => false,
						),
						array(
							'name'     => 'BP Profile Search',
							'slug'     => 'bp-profile-search',
							'required' => false,
						),
						array(
							'name'     => 'BuddyPress for LearnDash',
							'slug'     => 'buddypress-learndash',
							'required' => false,
						),
						array(
							'name'     => 'BuddyPress Global Search',
							'slug'     => 'buddypress-global-search',
							'required' => false,
						),
						array(
							'name'     => 'JetPack',
							'slug'     => 'jetpack',
							'required' => false,
						),
						array(
							'name'     => 'Paid Memberships Pro',
							'slug'     => 'paid-memberships-pro',
							'required' => false,
						),
						array(
							'name'     => 'Paid Memberships Pro - BuddyPress Add On',
							'slug'     => 'pmpro-buddypress',
							'required' => false,
						),
						array(
							'name'     => 'rtMedia for WordPress, BuddyPress and bbPress',
							'slug'     => 'buddypress-media',
							'required' => false,
						),
						array(
							'name'     => 'Social Articles',
							'slug'     => 'social-articles',
							'required' => false,
						),
						array(
							'name'     => 'Verified Member for BuddyPress',
							'slug'     => 'bp-verified-member',
							'required' => false,
						),
						array(
							'name'         => 'Grimlock for BuddyPress',
							'slug'         => 'grimlock-buddypress',
							'source'       => 'http://files.themosaurus.com/grimlock-buddypress/grimlock-buddypress.zip',
							'required'     => true,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock Hero',
							'slug'         => 'grimlock-hero',
							'source'       => 'http://files.themosaurus.com/grimlock-hero/grimlock-hero.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock Isotope',
							'slug'         => 'grimlock-isotope',
							'source'       => 'http://files.themosaurus.com/grimlock-isotope/grimlock-isotope.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock for Author Avatars List',
							'slug'         => 'grimlock-author-avatars',
							'source'       => 'http://files.themosaurus.com/grimlock-author-avatars/grimlock-author-avatars.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock for bbPress',
							'slug'         => 'grimlock-bbpress',
							'source'       => 'http://files.themosaurus.com/grimlock-bbpress/grimlock-bbpress.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock for Jetpack',
							'slug'         => 'grimlock-jetpack',
							'source'       => 'http://files.themosaurus.com/grimlock-jetpack/grimlock-jetpack.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
						array(
							'name'         => 'Grimlock for Paid Memberships Pro',
							'slug'         => 'grimlock-paid-memberships-pro',
							'source'       => 'http://files.themosaurus.com/grimlock-paid-memberships-pro/grimlock-paid-memberships-pro.zip',
							'required'     => false,
							'external_url' => 'https://www.themosaurus.com/',
						),
					),
				),
			) );
		}

		/**
		 * Dynamically add plugins to TGMPA depending on the selected demo during import
		 *
		 * @param $plugins
		 *
		 * @return array
		 */
		public function add_tgmpa_demo_plugins( $plugins ) {
			$demo_setups = array_values( $this->get_demo_setups_args() ); // Turn into non-associative to make sure we have the same indexes as Merlin's import_files property
			if ( ! empty( $demo_setups[ $_GET['demo'] ] ) ) {
				if ( ! empty( $demo_setups[ $_GET['demo'] ]['tgmpa'] ) ) {
					$plugins = array_merge( $plugins, $demo_setups[ $_GET['demo'] ]['tgmpa'] );
				}

				if ( ! empty( $_GET['use-elementor'] ) ) {
					$plugins[] = array(
						'name'     => 'Elementor',
						'slug'     => 'elementor',
						'required' => true,
					);
					$plugins[] = array(
						'name'     => 'Grimlock for Elementor',
						'slug'     => 'grimlock-elementor',
						'required' => true,
					);
				}
			}

			return $plugins;
		}

		public function change_permalink_structure() {
			// Update the permalink structure
			update_option( 'permalink_structure', '/%postname%/' );
			flush_rewrite_rules();
		}

		/**
		 * Setup demo import files list for Merlin
		 *
		 * @param $args
		 *
		 * @return array
		 */
		public function merlin_import_files( $args ) {
			return array_merge( $args, $this->get_demo_setups_args() );
		}

		/**
		 * Process adjustments after demo import
		 */
		public function merlin_after_import_setup() {
			// Process demo specific adjustments
			if ( isset( $_GET['demo'] ) ) {
				if ( ! empty( $_GET['use-elementor'] ) ) {
					$this->setup_elementor();
				}

				switch ( $_GET['demo'] ) {
					case 0: // Classic demo
						$this->after_import_classic();
						break;
					case 1: // Community demo
						$this->after_import_community();
						break;
					case 2: // Dating demo
						$this->after_import_dating();
						break;
					case 3: // Shop demo
						$this->after_import_shop();
						break;
					case 4: // Youzer demo
						$this->after_import_youzer();
						break;
					case 5: // Spiritual demo
						$this->after_import_spiritual();
						break;
					case 6: // Mentor demo
						$this->after_import_mentor();
						break;
					case 7: // Nightlife demo
						$this->after_import_nightlife();
						break;
					case 8: // Learn demo
						$this->after_import_learn();
						break;
				}
			}

			flush_rewrite_rules();
		}

		/**
		 * After import logic for the Classic demo
		 */
		public function after_import_classic() {
			$primary_menu           = get_term_by( 'name', 'Primary', 'nav_menu' );
			$logged_in_menu         = get_term_by( 'name', 'User - Logged In', 'nav_menu' );
			$logged_out_menu        = get_term_by( 'name', 'User - Logged Out', 'nav_menu' );
			$homepage_features_menu = get_term_by( 'name', 'Features - Homepage', 'nav_menu' );
			$interests_menu         = get_term_by( 'name', 'Interests', 'nav_menu' );
			$prefooter_1_menu       = get_term_by( 'name', 'Pre Footer 1', 'nav_menu' );
			$prefooter_3_menu       = get_term_by( 'name', 'Pre Footer 3', 'nav_menu' );
			$social_menu            = get_term_by( 'name', 'Social', 'nav_menu' );

			$logged_in_menu_items         = wp_get_nav_menu_items( $logged_in_menu );
			$primary_menu_items           = wp_get_nav_menu_items( $primary_menu );
			$homepage_features_menu_items = wp_get_nav_menu_items( $homepage_features_menu );
			$interests_menu_items         = wp_get_nav_menu_items( $interests_menu );
			$prefooter_1_menu_items       = wp_get_nav_menu_items( $prefooter_1_menu );
			$prefooter_3_menu_items       = wp_get_nav_menu_items( $prefooter_3_menu );
			$social_menu_items            = wp_get_nav_menu_items( $social_menu );

			// Assign menus to their locations.
			set_theme_mod(
				'nav_menu_locations', array(
					'primary'         => $primary_menu->term_id,
					'user_logged_in'  => $logged_in_menu->term_id,
					'user_logged_out' => $logged_out_menu->term_id,
				)
			);

			// Adjust Features menu items
			foreach ( $homepage_features_menu_items as $menu_item ) {
				switch ( $menu_item->title ) {
					case 'Talk to Real People':
						$menu_item->title = '<span class="icon-wrapper"><i class="gwangi-icon gwangi-love-on"></i><span class="h5 d-block">Talk to Real People</span></span>';
						break;
					case 'Smooth & Simple Features':
						$menu_item->title = '<span class="icon-wrapper"><i class="gwangi-icon gwangi-smile"></i><span class="h5 d-block">Smooth & Simple Features</span></span>';
						break;
					case 'Your Data are Safe':
						$menu_item->title = '<span class="icon-wrapper"><i class="fa fa-lock"></i><span class="h5 d-block">Your Data are Safe</span></span>';
						break;
				}

				$this->update_menu_item( $homepage_features_menu->term_id, $menu_item );
			}

			// Adjust Social menu items
			foreach ( $social_menu_items as $menu_item ) {
				switch ( $menu_item->url ) {
					case 'https://www.instagram.com/':
						$menu_item->title = '<i class="fa fa-instagram"></i>';
						break;
					case 'https://twitter.com/':
						$menu_item->title = '<i class="fa fa-twitter"></i>';
						break;
					case 'https://www.facebook.com/':
						$menu_item->title = '<i class="fa fa-facebook-official"></i>';
						break;
					case 'https://www.youtube.com/':
						$menu_item->title = '<i class="fa fa-youtube-play"></i>';
						break;
					case 'https://www.snapchat.com/':
						$menu_item->title = '<i class="fa fa-snapchat-square"></i>';
						break;
				}

				$this->update_menu_item( $social_menu->term_id, $menu_item );
			}

			if ( class_exists( 'BuddyPress' ) ) {
				// Enable registrations
				update_option( 'users_can_register', 1 );

				// Activate BP components
				$this->activate_bp_components();

				// Adjust Primary menu items
				$this->convert_relative_menu_items_urls( $primary_menu, $primary_menu_items );

				// Adjust User - Logged In menu items
				$this->convert_relative_menu_items_urls( $logged_in_menu, $logged_in_menu_items );

				// Generate profile fields in the "Base" tab
				$this->generate_base_xprofile_fields();

				// Get ids of component pages (directories pages, register page, etc...)
				$directory_pages = bp_get_option( 'bp-pages' );

				// Get Sidebar Left and Right directory pages
				$directory_sidebar_left  = get_page_by_title( 'Members Sidebar Left' );
				$directory_sidebar_right = get_page_by_title( 'Members Sidebar Right' );

				// Fix members directory search forms
				$directory_search_fields = array(
					'field_' . xprofile_get_field_id_from_name( 'Name' ),
					'field_' . xprofile_get_field_id_from_name( 'Passion' ),
					'field_' . xprofile_get_field_id_from_name( 'Birthdate' ),
					'field_' . xprofile_get_field_id_from_name( 'Gender' ),
					'field_' . xprofile_get_field_id_from_name( 'Country' ),
					'field_' . xprofile_get_field_id_from_name( 'City' ),
					'field_any',
				);
				if ( ! empty( $directory_pages['members'] ) ) {
					$this->fix_bps_form( 'Search directory', $directory_search_fields, $directory_pages['members'] );
				}
				if ( ! empty( $directory_sidebar_left ) ) {
					$this->fix_bps_form( 'Search Directory Sidebar Left', $directory_search_fields, $directory_sidebar_left->ID );
				}
				if ( ! empty( $directory_sidebar_right ) ) {
					$this->fix_bps_form( 'Search Directory Sidebar Right', $directory_search_fields, $directory_sidebar_right->ID );
				}

				// Adjust Interests menu items
				$directory_search_form = get_page_by_title( 'Search directory', 'OBJECT', 'bps_form' );
				if ( ! empty( $directory_search_form ) ) {
					foreach ( $interests_menu_items as $menu_item ) {
						$menu_item->url = esc_url( add_query_arg( array(
							'field_' . xprofile_get_field_id_from_name( 'Passion' ) => urlencode( str_replace( ' ', '+', $menu_item->title ) ),
							'bps_form' => $directory_search_form->ID,
						), get_permalink( $directory_pages['members'] ) ) );

						$this->update_menu_item( $interests_menu->term_id, $menu_item );
					}
				}
			}

			// Assign PMPro pages
			$this->assign_pmpro_pages();

			// Activate Jetpack modules
			if ( class_exists( 'Jetpack' ) ) {
				Jetpack::activate_module( 'tiled-gallery' );
				Jetpack::activate_module( 'infinite-scroll' );
				Jetpack::activate_module( 'carousel' );
			}
		}

		/**
		 * After import logic for the Community demo
		 */
		public function after_import_community() {
			$primary_menu           = get_term_by( 'name', 'Primary', 'nav_menu' );
			$logged_in_menu         = get_term_by( 'name', 'User - Logged In', 'nav_menu' );
			$logged_out_menu        = get_term_by( 'name', 'User - Logged Out', 'nav_menu' );
			$homepage_features_menu = get_term_by( 'name', 'Features - Homepage', 'nav_menu' );
			$cities_menu            = get_term_by( 'name', 'Cities', 'nav_menu' );
			$prefooter_1_menu       = get_term_by( 'name', 'Pre Footer 1', 'nav_menu' );
			$prefooter_3_menu       = get_term_by( 'name', 'Pre Footer 3', 'nav_menu' );
			$social_menu            = get_term_by( 'name', 'Social', 'nav_menu' );

			$logged_in_menu_items         = wp_get_nav_menu_items( $logged_in_menu );
			$primary_menu_items           = wp_get_nav_menu_items( $primary_menu );
			$homepage_features_menu_items = wp_get_nav_menu_items( $homepage_features_menu );
			$cities_menu_items            = wp_get_nav_menu_items( $cities_menu );
			$prefooter_1_menu_items       = wp_get_nav_menu_items( $prefooter_1_menu );
			$prefooter_3_menu_items       = wp_get_nav_menu_items( $prefooter_3_menu );
			$social_menu_items            = wp_get_nav_menu_items( $social_menu );

			// Assign menus to their locations.
			set_theme_mod(
				'nav_menu_locations', array(
					'primary'         => $primary_menu->term_id,
					'user_logged_in'  => $logged_in_menu->term_id,
					'user_logged_out' => $logged_out_menu->term_id,
				)
			);

			// Adjust Features menu items
			foreach ( $homepage_features_menu_items as $menu_item ) {
				switch ( $menu_item->title ) {
					case 'Talk to Real People':
						$menu_item->title = '<span class="icon-wrapper"><i class="gwangi-icon gwangi-love-on"></i><span class="h5 d-block">Talk to Real People</span></span>';
						break;
					case 'Smooth & Simple Features':
						$menu_item->title = '<span class="icon-wrapper"><i class="gwangi-icon gwangi-smile"></i><span class="h5 d-block">Smooth & Simple Features</span></span>';
						break;
					case 'Your Data are Safe':
						$menu_item->title = '<span class="icon-wrapper"><i class="fa fa-lock"></i><span class="h5 d-block">Your Data are Safe</span></span>';
						break;
				}

				$this->update_menu_item( $homepage_features_menu->term_id, $menu_item );
			}

			// Adjust Prefooter 3 menu items
			foreach ( $prefooter_3_menu_items as $menu_item ) {
				switch ( $menu_item->url ) {
					case 'https://www.instagram.com/':
						$menu_item->title = '<i class="fa fa-instagram"></i> gwangi_community';
						break;
					case 'https://twitter.com/':
						$menu_item->title = '<i class="fa fa-twitter"></i> gwangi';
						break;
					case 'https://www.facebook.com/':
						$menu_item->title = '<i class="fa fa-facebook"></i> gwangi';
						break;
					case 'https://www.youtube.com/':
						$menu_item->title = '<i class="fa fa-youtube-play"></i> gwangi_tv';
						break;
					case 'https://www.snapchat.com/':
						$menu_item->title = '<i class="fa fa-snapchat-ghost"></i> gwangi_community';
						break;
				}

				$this->update_menu_item( $prefooter_3_menu->term_id, $menu_item );
			}

			// Adjust Social menu items
			foreach ( $social_menu_items as $menu_item ) {
				switch ( $menu_item->url ) {
					case 'https://www.instagram.com/':
						$menu_item->title = '<span class="icon-wrapper"><i class="fa fa-instagram"></i><span class="h5 d-block">@gwangi_community</span></span>';
						break;
					case 'https://twitter.com/':
						$menu_item->title = '<span class="icon-wrapper"><i class="fa fa-twitter"></i><span class="h5 d-block">@gwangi_community</span></span>';
						break;
					case 'https://www.facebook.com/':
						$menu_item->title = '<span class="icon-wrapper"><i class="fa fa-facebook"></i><span class="h5 d-block">@gwangi</span></span>';
						break;
					case 'https://www.youtube.com/':
						$menu_item->title = '<span class="icon-wrapper"><i class="fa fa-youtube-play"></i><span class="h5 d-block">@gwangi_tv</span></span>';
						break;
					case 'https://www.snapchat.com/':
						$menu_item->title = '<span class="icon-wrapper"><i class="fa fa-snapchat-ghost"></i><span class="h5 d-block">@gwangi_community</span></span>';
						break;
				}

				$this->update_menu_item( $social_menu->term_id, $menu_item );
			}

			if ( class_exists( 'BuddyPress' ) ) {
				// Enable registrations
				update_option( 'users_can_register', 1 );

				// Activate BP components
				$this->activate_bp_components();

				// Adjust Primary menu items
				$this->convert_relative_menu_items_urls( $primary_menu, $primary_menu_items );

				// Adjust User - Logged In menu items
				$this->convert_relative_menu_items_urls( $logged_in_menu, $logged_in_menu_items );

				// Adjust Prefooter 1 menu items
				$this->convert_relative_menu_items_urls( $prefooter_1_menu, $prefooter_1_menu_items );

				// Generate profile fields in the "Base" tab
				$this->generate_base_xprofile_fields();

				// Get ids of component pages (directories pages, register page, etc...)
				$directory_pages = bp_get_option( 'bp-pages' );

				// Get Sidebar Left and Right directory pages
				$directory_sidebar_left  = get_page_by_title( 'Members Sidebar Left' );
				$directory_sidebar_right = get_page_by_title( 'Members Sidebar Right' );

				// Fix members directory search forms
				$directory_search_fields = array(
					'field_' . xprofile_get_field_id_from_name( 'Name' ),
					'field_' . xprofile_get_field_id_from_name( 'Passion' ),
					'field_' . xprofile_get_field_id_from_name( 'Birthdate' ),
					'field_' . xprofile_get_field_id_from_name( 'Gender' ),
					'field_' . xprofile_get_field_id_from_name( 'Country' ),
					'field_' . xprofile_get_field_id_from_name( 'City' ),
					'field_any',
				);
				if ( ! empty( $directory_pages['members'] ) ) {
					$this->fix_bps_form( 'Search directory', $directory_search_fields, $directory_pages['members'] );
				}
				if ( ! empty( $directory_sidebar_left ) ) {
					$this->fix_bps_form( 'Search Directory Sidebar Left', $directory_search_fields, $directory_sidebar_left->ID );
				}
				if ( ! empty( $directory_sidebar_right ) ) {
					$this->fix_bps_form( 'Search Directory Sidebar Right', $directory_search_fields, $directory_sidebar_right->ID );
				}

				// Fix home search form
				$this->fix_bps_form( 'Search home', array(
					'field_' . xprofile_get_field_id_from_name( 'Name' ),
					'field_' . xprofile_get_field_id_from_name( 'Birthdate' ),
					'field_' . xprofile_get_field_id_from_name( 'Passion' ),
				), $directory_pages['members'] );

				// Adjust Cities menu items
				$directory_search_form = get_page_by_title( 'Search directory', 'OBJECT', 'bps_form' );
				if ( ! empty( $directory_search_form ) ) {
					foreach ( $cities_menu_items as $menu_item ) {
						$menu_item->url = esc_url( add_query_arg( array(
							'field_' . xprofile_get_field_id_from_name( 'City' ) => urlencode( str_replace( ' ', '+', $menu_item->title ) ),
							'bps_form' => $directory_search_form->ID,
						), get_permalink( $directory_pages['members'] ) ) );

						$this->update_menu_item( $cities_menu->term_id, $menu_item );
					}
				}
			}

			// Assign PMPro pages
			$this->assign_pmpro_pages();

			// Activate Jetpack modules
			if ( class_exists( 'Jetpack' ) ) {
				Jetpack::activate_module( 'tiled-gallery' );
				Jetpack::activate_module( 'infinite-scroll' );
				Jetpack::activate_module( 'carousel' );
			}
		}

		/**
		 * After import logic for the Nightlife demo
		 */
		public function after_import_nightlife() {
			$primary_menu           = get_term_by( 'name', 'Primary', 'nav_menu' );
			$logged_in_menu         = get_term_by( 'name', 'User - Logged In', 'nav_menu' );
			$logged_out_menu        = get_term_by( 'name', 'User - Logged Out', 'nav_menu' );
			$homepage_features_menu = get_term_by( 'name', 'Features - Homepage', 'nav_menu' );
			$cities_menu            = get_term_by( 'name', 'Cities', 'nav_menu' );
			$prefooter_1_menu       = get_term_by( 'name', 'Pre Footer 1', 'nav_menu' );
			$prefooter_3_menu       = get_term_by( 'name', 'Pre Footer 3', 'nav_menu' );
			$social_menu            = get_term_by( 'name', 'Social', 'nav_menu' );

			$logged_in_menu_items         = wp_get_nav_menu_items( $logged_in_menu );
			$primary_menu_items           = wp_get_nav_menu_items( $primary_menu );
			$homepage_features_menu_items = wp_get_nav_menu_items( $homepage_features_menu );
			$cities_menu_items            = wp_get_nav_menu_items( $cities_menu );
			$prefooter_1_menu_items       = wp_get_nav_menu_items( $prefooter_1_menu );
			$prefooter_3_menu_items       = wp_get_nav_menu_items( $prefooter_3_menu );
			$social_menu_items            = wp_get_nav_menu_items( $social_menu );

			// Assign menus to their locations.
			set_theme_mod(
				'nav_menu_locations', array(
					'primary'         => $primary_menu->term_id,
					'user_logged_in'  => $logged_in_menu->term_id,
					'user_logged_out' => $logged_out_menu->term_id,
				)
			);

			// Adjust Features menu items
			foreach ( $homepage_features_menu_items as $menu_item ) {
				switch ( $menu_item->title ) {
					case 'Talk to Real People':
						$menu_item->title = '<span class="icon-wrapper"><i class="gwangi-icon gwangi-love-on"></i><span class="h5 d-block">Talk to Real People</span></span>';
						break;
					case 'Smooth & Simple Features':
						$menu_item->title = '<span class="icon-wrapper"><i class="gwangi-icon gwangi-smile"></i><span class="h5 d-block">Smooth & Simple Features</span></span>';
						break;
					case 'Your Data are Safe':
						$menu_item->title = '<span class="icon-wrapper"><i class="fa fa-lock"></i><span class="h5 d-block">Your Data are Safe</span></span>';
						break;
				}

				$this->update_menu_item( $homepage_features_menu->term_id, $menu_item );
			}

			// Adjust Prefooter 3 menu items
			foreach ( $prefooter_3_menu_items as $menu_item ) {
				switch ( $menu_item->url ) {
					case 'https://www.instagram.com/':
						$menu_item->title = '<i class="fa fa-instagram"></i> gwangi_community';
						break;
					case 'https://twitter.com/':
						$menu_item->title = '<i class="fa fa-twitter"></i> gwangi';
						break;
					case 'https://www.facebook.com/':
						$menu_item->title = '<i class="fa fa-facebook"></i> gwangi';
						break;
					case 'https://www.youtube.com/':
						$menu_item->title = '<i class="fa fa-youtube-play"></i> gwangi_tv';
						break;
					case 'https://www.snapchat.com/':
						$menu_item->title = '<i class="fa fa-snapchat-ghost"></i> gwangi_community';
						break;
				}

				$this->update_menu_item( $prefooter_3_menu->term_id, $menu_item );
			}

			// Adjust Social menu items
			foreach ( $social_menu_items as $menu_item ) {
				switch ( $menu_item->url ) {
					case 'https://www.instagram.com/':
						$menu_item->title = '<span class="icon-wrapper"><i class="fa fa-instagram"></i><span class="h5 d-block">@gwangi_community</span></span>';
						break;
					case 'https://twitter.com/':
						$menu_item->title = '<span class="icon-wrapper"><i class="fa fa-twitter"></i><span class="h5 d-block">@gwangi_community</span></span>';
						break;
					case 'https://www.facebook.com/':
						$menu_item->title = '<span class="icon-wrapper"><i class="fa fa-facebook"></i><span class="h5 d-block">@gwangi</span></span>';
						break;
					case 'https://www.youtube.com/':
						$menu_item->title = '<span class="icon-wrapper"><i class="fa fa-youtube-play"></i><span class="h5 d-block">@gwangi_tv</span></span>';
						break;
					case 'https://www.snapchat.com/':
						$menu_item->title = '<span class="icon-wrapper"><i class="fa fa-snapchat-ghost"></i><span class="h5 d-block">@gwangi_community</span></span>';
						break;
				}

				$this->update_menu_item( $social_menu->term_id, $menu_item );
			}

			if ( class_exists( 'BuddyPress' ) ) {
				// Enable registrations
				update_option( 'users_can_register', 1 );

				// Activate BP components
				$this->activate_bp_components();

				// Adjust Primary menu items
				$this->convert_relative_menu_items_urls( $primary_menu, $primary_menu_items );

				// Adjust User - Logged In menu items
				$this->convert_relative_menu_items_urls( $logged_in_menu, $logged_in_menu_items );

				// Adjust Prefooter 1 menu items
				$this->convert_relative_menu_items_urls( $prefooter_1_menu, $prefooter_1_menu_items );

				// Generate profile fields in the "Base" tab
				$this->generate_base_xprofile_fields();

				// Get ids of component pages (directories pages, register page, etc...)
				$directory_pages = bp_get_option( 'bp-pages' );

				// Get Sidebar Left and Right directory pages
				$directory_sidebar_left  = get_page_by_title( 'Members Sidebar Left' );
				$directory_sidebar_right = get_page_by_title( 'Members Sidebar Right' );

				// Fix members directory search forms
				$directory_search_fields = array(
					'field_' . xprofile_get_field_id_from_name( 'Name' ),
					'field_' . xprofile_get_field_id_from_name( 'Passion' ),
					'field_' . xprofile_get_field_id_from_name( 'Birthdate' ),
					'field_' . xprofile_get_field_id_from_name( 'Gender' ),
					'field_' . xprofile_get_field_id_from_name( 'Country' ),
					'field_' . xprofile_get_field_id_from_name( 'City' ),
					'field_any',
				);
				if ( ! empty( $directory_pages['members'] ) ) {
					$this->fix_bps_form( 'Search directory', $directory_search_fields, $directory_pages['members'] );
				}
				if ( ! empty( $directory_sidebar_left ) ) {
					$this->fix_bps_form( 'Search Directory Sidebar Left', $directory_search_fields, $directory_sidebar_left->ID );
				}
				if ( ! empty( $directory_sidebar_right ) ) {
					$this->fix_bps_form( 'Search Directory Sidebar Right', $directory_search_fields, $directory_sidebar_right->ID );
				}

				// Fix home search form
				$this->fix_bps_form( 'Search home', array(
					'field_' . xprofile_get_field_id_from_name( 'Name' ),
					'field_' . xprofile_get_field_id_from_name( 'Birthdate' ),
					'field_' . xprofile_get_field_id_from_name( 'Passion' ),
				), $directory_pages['members'] );

				// Adjust Cities menu items
				$directory_search_form = get_page_by_title( 'Search directory', 'OBJECT', 'bps_form' );
				if ( ! empty( $directory_search_form ) ) {
					foreach ( $cities_menu_items as $menu_item ) {
						$menu_item->url = esc_url( add_query_arg( array(
							'field_' . xprofile_get_field_id_from_name( 'City' ) => urlencode( str_replace( ' ', '+', $menu_item->title ) ),
							'bps_form' => $directory_search_form->ID,
						), get_permalink( $directory_pages['members'] ) ) );

						$this->update_menu_item( $cities_menu->term_id, $menu_item );
					}
				}
			}

			if ( function_exists( 'tribe' ) ) {
				tribe_update_option( 'postsPerPage', '9' );
				tribe_update_option( 'stylesheetOption', 'full' );
				tribe_update_option( 'tribeEventsTemplate', 'template-classic-12-cols-left.php' );
				tribe_update_option( 'viewOption', 'list' );
				tribe_update_option( 'views_v2_enabled', false );
			}

			// Assign PMPro pages
			$this->assign_pmpro_pages();

			// Activate Jetpack modules
			if ( class_exists( 'Jetpack' ) ) {
				Jetpack::activate_module( 'tiled-gallery' );
				Jetpack::activate_module( 'infinite-scroll' );
				Jetpack::activate_module( 'carousel' );
			}
		}

		/**
		 * After import logic for the Learn demo
		 */
		public function after_import_learn() {
			$primary_menu           = get_term_by( 'name', 'Primary', 'nav_menu' );
			$logged_in_menu         = get_term_by( 'name', 'User - Logged In', 'nav_menu' );
			$logged_out_menu        = get_term_by( 'name', 'User - Logged Out', 'nav_menu' );
			$homepage_features_menu = get_term_by( 'name', 'Features - Homepage', 'nav_menu' );
			$cities_menu            = get_term_by( 'name', 'Cities', 'nav_menu' );
			$prefooter_1_menu       = get_term_by( 'name', 'Pre Footer 1', 'nav_menu' );
			$prefooter_3_menu       = get_term_by( 'name', 'Pre Footer 3', 'nav_menu' );
			$social_menu            = get_term_by( 'name', 'Social', 'nav_menu' );

			$logged_in_menu_items         = wp_get_nav_menu_items( $logged_in_menu );
			$primary_menu_items           = wp_get_nav_menu_items( $primary_menu );
			$homepage_features_menu_items = wp_get_nav_menu_items( $homepage_features_menu );
			$cities_menu_items            = wp_get_nav_menu_items( $cities_menu );
			$prefooter_1_menu_items       = wp_get_nav_menu_items( $prefooter_1_menu );
			$prefooter_3_menu_items       = wp_get_nav_menu_items( $prefooter_3_menu );
			$social_menu_items            = wp_get_nav_menu_items( $social_menu );

			// Assign menus to their locations.
			set_theme_mod(
				'nav_menu_locations', array(
					'primary'         => $primary_menu->term_id,
					'user_logged_in'  => $logged_in_menu->term_id,
					'user_logged_out' => $logged_out_menu->term_id,
				)
			);

			// Adjust Features menu items
			foreach ( $homepage_features_menu_items as $menu_item ) {
				switch ( $menu_item->title ) {
					case 'Talk to Real People':
						$menu_item->title = '<span class="icon-wrapper"><i class="gwangi-icon gwangi-love-on"></i><span class="h5 d-block">Talk to Real People</span></span>';
						break;
					case 'Smooth & Simple Features':
						$menu_item->title = '<span class="icon-wrapper"><i class="gwangi-icon gwangi-smile"></i><span class="h5 d-block">Smooth & Simple Features</span></span>';
						break;
					case 'Your Data are Safe':
						$menu_item->title = '<span class="icon-wrapper"><i class="fa fa-lock"></i><span class="h5 d-block">Your Data are Safe</span></span>';
						break;
				}

				$this->update_menu_item( $homepage_features_menu->term_id, $menu_item );
			}

			// Adjust Prefooter 3 menu items
			foreach ( $prefooter_3_menu_items as $menu_item ) {
				switch ( $menu_item->url ) {
					case 'https://www.instagram.com/':
						$menu_item->title = '<i class="fa fa-instagram"></i> gwangi_community';
						break;
					case 'https://twitter.com/':
						$menu_item->title = '<i class="fa fa-twitter"></i> gwangi';
						break;
					case 'https://www.facebook.com/':
						$menu_item->title = '<i class="fa fa-facebook"></i> gwangi';
						break;
					case 'https://www.youtube.com/':
						$menu_item->title = '<i class="fa fa-youtube-play"></i> gwangi_tv';
						break;
					case 'https://www.snapchat.com/':
						$menu_item->title = '<i class="fa fa-snapchat-ghost"></i> gwangi_community';
						break;
				}

				$this->update_menu_item( $prefooter_3_menu->term_id, $menu_item );
			}

			// Adjust Social menu items
			foreach ( $social_menu_items as $menu_item ) {
				switch ( $menu_item->url ) {
					case 'https://www.instagram.com/':
						$menu_item->title = '<span class="icon-wrapper"><i class="fa fa-instagram"></i><span class="h5 d-block">@gwangi_community</span></span>';
						break;
					case 'https://twitter.com/':
						$menu_item->title = '<span class="icon-wrapper"><i class="fa fa-twitter"></i><span class="h5 d-block">@gwangi_community</span></span>';
						break;
					case 'https://www.facebook.com/':
						$menu_item->title = '<span class="icon-wrapper"><i class="fa fa-facebook"></i><span class="h5 d-block">@gwangi</span></span>';
						break;
					case 'https://www.youtube.com/':
						$menu_item->title = '<span class="icon-wrapper"><i class="fa fa-youtube-play"></i><span class="h5 d-block">@gwangi_tv</span></span>';
						break;
					case 'https://www.snapchat.com/':
						$menu_item->title = '<span class="icon-wrapper"><i class="fa fa-snapchat-ghost"></i><span class="h5 d-block">@gwangi_community</span></span>';
						break;
				}

				$this->update_menu_item( $social_menu->term_id, $menu_item );
			}

			if ( class_exists( 'BuddyPress' ) ) {
				// Enable registrations
				update_option( 'users_can_register', 1 );

				// Activate BP components
				$this->activate_bp_components();

				// Adjust Primary menu items
				$this->convert_relative_menu_items_urls( $primary_menu, $primary_menu_items );

				// Adjust User - Logged In menu items
				$this->convert_relative_menu_items_urls( $logged_in_menu, $logged_in_menu_items );

				// Adjust Prefooter 1 menu items
				$this->convert_relative_menu_items_urls( $prefooter_1_menu, $prefooter_1_menu_items );

				// Generate profile fields in the "Base" tab
				$this->generate_base_xprofile_fields();

				// Get ids of component pages (directories pages, register page, etc...)
				$directory_pages = bp_get_option( 'bp-pages' );

				// Get Sidebar Left and Right directory pages
				$directory_sidebar_left  = get_page_by_title( 'Members Sidebar Left' );
				$directory_sidebar_right = get_page_by_title( 'Members Sidebar Right' );

				// Fix members directory search forms
				$directory_search_fields = array(
					'field_' . xprofile_get_field_id_from_name( 'Name' ),
					'field_' . xprofile_get_field_id_from_name( 'Passion' ),
					'field_' . xprofile_get_field_id_from_name( 'Birthdate' ),
					'field_' . xprofile_get_field_id_from_name( 'Gender' ),
					'field_' . xprofile_get_field_id_from_name( 'Country' ),
					'field_' . xprofile_get_field_id_from_name( 'City' ),
					'field_any',
				);
				if ( ! empty( $directory_pages['members'] ) ) {
					$this->fix_bps_form( 'Search directory', $directory_search_fields, $directory_pages['members'] );
				}
				if ( ! empty( $directory_sidebar_left ) ) {
					$this->fix_bps_form( 'Search Directory Sidebar Left', $directory_search_fields, $directory_sidebar_left->ID );
				}
				if ( ! empty( $directory_sidebar_right ) ) {
					$this->fix_bps_form( 'Search Directory Sidebar Right', $directory_search_fields, $directory_sidebar_right->ID );
				}

				// Adjust Cities menu items
				$directory_search_form = get_page_by_title( 'Search directory', 'OBJECT', 'bps_form' );
				if ( ! empty( $directory_search_form ) ) {
					foreach ( $cities_menu_items as $menu_item ) {
						$menu_item->url = esc_url( add_query_arg( array(
							'field_' . xprofile_get_field_id_from_name( 'City' ) => urlencode( str_replace( ' ', '+', $menu_item->title ) ),
							'bps_form' => $directory_search_form->ID,
						), get_permalink( $directory_pages['members'] ) ) );

						$this->update_menu_item( $cities_menu->term_id, $menu_item );
					}
				}
			}

			// Assign PMPro pages
			$this->assign_pmpro_pages();

			// Change BuddyPress for LearnDash option
			$bp_lms_options = get_site_option( 'buddypress_learndash_plugin_options', array() );
			$bp_lms_options['courses_visibility'] = 'on';
			update_site_option( 'buddypress_learndash_plugin_options', $bp_lms_options );

			// Activate Jetpack modules
			if ( class_exists( 'Jetpack' ) ) {
				Jetpack::activate_module( 'tiled-gallery' );
				Jetpack::activate_module( 'infinite-scroll' );
				Jetpack::activate_module( 'carousel' );
			}
		}

		/**
		 * After import logic for the Spiritual demo
		 */
		public function after_import_spiritual() {
			$primary_menu           = get_term_by( 'name', 'Primary', 'nav_menu' );
			$logged_in_menu         = get_term_by( 'name', 'User - Logged In', 'nav_menu' );
			$logged_out_menu        = get_term_by( 'name', 'User - Logged Out', 'nav_menu' );
			$homepage_features_menu = get_term_by( 'name', 'Features - Homepage', 'nav_menu' );
			$cities_menu            = get_term_by( 'name', 'Cities', 'nav_menu' );
			$prefooter_1_menu       = get_term_by( 'name', 'Pre Footer 1', 'nav_menu' );
			$prefooter_3_menu       = get_term_by( 'name', 'Pre Footer 3', 'nav_menu' );
			$social_menu            = get_term_by( 'name', 'Social', 'nav_menu' );

			$logged_in_menu_items         = wp_get_nav_menu_items( $logged_in_menu );
			$primary_menu_items           = wp_get_nav_menu_items( $primary_menu );
			$homepage_features_menu_items = wp_get_nav_menu_items( $homepage_features_menu );
			$cities_menu_items            = wp_get_nav_menu_items( $cities_menu );
			$prefooter_1_menu_items       = wp_get_nav_menu_items( $prefooter_1_menu );
			$prefooter_3_menu_items       = wp_get_nav_menu_items( $prefooter_3_menu );
			$social_menu_items            = wp_get_nav_menu_items( $social_menu );

			// Assign menus to their locations.
			set_theme_mod(
				'nav_menu_locations', array(
					'primary'         => $primary_menu->term_id,
					'user_logged_in'  => $logged_in_menu->term_id,
					'user_logged_out' => $logged_out_menu->term_id,
				)
			);

			// Adjust Features menu items
			foreach ( $homepage_features_menu_items as $menu_item ) {
				switch ( $menu_item->title ) {
					case 'Talk to Real People':
						$menu_item->title = '<span class="icon-wrapper"><i class="gwangi-icon gwangi-love-on"></i><span class="h5 d-block">Talk to Real People</span></span>';
						break;
					case 'Smooth & Simple Features':
						$menu_item->title = '<span class="icon-wrapper"><i class="gwangi-icon gwangi-smile"></i><span class="h5 d-block">Smooth & Simple Features</span></span>';
						break;
					case 'Your Data are Safe':
						$menu_item->title = '<span class="icon-wrapper"><i class="fa fa-lock"></i><span class="h5 d-block">Your Data are Safe</span></span>';
						break;
				}

				$this->update_menu_item( $homepage_features_menu->term_id, $menu_item );
			}

			// Adjust Prefooter 3 menu items
			foreach ( $prefooter_3_menu_items as $menu_item ) {
				switch ( $menu_item->url ) {
					case 'https://www.instagram.com/':
						$menu_item->title = '<i class="fa fa-instagram"></i> gwangi_community';
						break;
					case 'https://twitter.com/':
						$menu_item->title = '<i class="fa fa-twitter"></i> gwangi';
						break;
					case 'https://www.facebook.com/':
						$menu_item->title = '<i class="fa fa-facebook"></i> gwangi';
						break;
					case 'https://www.youtube.com/':
						$menu_item->title = '<i class="fa fa-youtube-play"></i> gwangi_tv';
						break;
					case 'https://www.snapchat.com/':
						$menu_item->title = '<i class="fa fa-snapchat-ghost"></i> gwangi_community';
						break;
				}

				$this->update_menu_item( $prefooter_3_menu->term_id, $menu_item );
			}

			// Adjust Social menu items
			foreach ( $social_menu_items as $menu_item ) {
				switch ( $menu_item->url ) {
					case 'https://www.instagram.com/':
						$menu_item->title = '<span class="icon-wrapper"><i class="fa fa-instagram"></i><span class="h5 d-block">@gwangi_community</span></span>';
						break;
					case 'https://twitter.com/':
						$menu_item->title = '<span class="icon-wrapper"><i class="fa fa-twitter"></i><span class="h5 d-block">@gwangi_community</span></span>';
						break;
					case 'https://www.facebook.com/':
						$menu_item->title = '<span class="icon-wrapper"><i class="fa fa-facebook"></i><span class="h5 d-block">@gwangi</span></span>';
						break;
					case 'https://www.youtube.com/':
						$menu_item->title = '<span class="icon-wrapper"><i class="fa fa-youtube-play"></i><span class="h5 d-block">@gwangi_tv</span></span>';
						break;
					case 'https://www.snapchat.com/':
						$menu_item->title = '<span class="icon-wrapper"><i class="fa fa-snapchat-ghost"></i><span class="h5 d-block">@gwangi_community</span></span>';
						break;
				}

				$this->update_menu_item( $social_menu->term_id, $menu_item );
			}

			if ( class_exists( 'BuddyPress' ) ) {
				// Enable registrations
				update_option( 'users_can_register', 1 );

				// Activate BP components
				$this->activate_bp_components();

				// Adjust Primary menu items
				$this->convert_relative_menu_items_urls( $primary_menu, $primary_menu_items );

				// Adjust User - Logged In menu items
				$this->convert_relative_menu_items_urls( $logged_in_menu, $logged_in_menu_items );

				// Adjust Prefooter 1 menu items
				$this->convert_relative_menu_items_urls( $prefooter_1_menu, $prefooter_1_menu_items );

				// Generate profile fields in the "Base" tab
				$this->generate_base_xprofile_fields();

				// Get ids of component pages (directories pages, register page, etc...)
				$directory_pages = bp_get_option( 'bp-pages' );

				// Get Sidebar Left and Right directory pages
				$directory_sidebar_left  = get_page_by_title( 'Members Sidebar Left' );
				$directory_sidebar_right = get_page_by_title( 'Members Sidebar Right' );

				// Fix members directory search forms
				$directory_search_fields = array(
					'field_' . xprofile_get_field_id_from_name( 'Name' ),
					'field_' . xprofile_get_field_id_from_name( 'Passion' ),
					'field_' . xprofile_get_field_id_from_name( 'Birthdate' ),
					'field_' . xprofile_get_field_id_from_name( 'Gender' ),
					'field_' . xprofile_get_field_id_from_name( 'Country' ),
					'field_' . xprofile_get_field_id_from_name( 'City' ),
					'field_any',
				);
				if ( ! empty( $directory_pages['members'] ) ) {
					$this->fix_bps_form( 'Search directory', $directory_search_fields, $directory_pages['members'] );
				}
				if ( ! empty( $directory_sidebar_left ) ) {
					$this->fix_bps_form( 'Search Directory Sidebar Left', $directory_search_fields, $directory_sidebar_left->ID );
				}
				if ( ! empty( $directory_sidebar_right ) ) {
					$this->fix_bps_form( 'Search Directory Sidebar Right', $directory_search_fields, $directory_sidebar_right->ID );
				}

				// Fix home search form
				$this->fix_bps_form( 'Search home', array(
					'field_' . xprofile_get_field_id_from_name( 'Name' ),
					'field_' . xprofile_get_field_id_from_name( 'Birthdate' ),
					'field_' . xprofile_get_field_id_from_name( 'Passion' ),
				), $directory_pages['members'] );

				// Adjust Cities menu items
				$directory_search_form = get_page_by_title( 'Search directory', 'OBJECT', 'bps_form' );
				if ( ! empty( $directory_search_form ) ) {
					foreach ( $cities_menu_items as $menu_item ) {
						$menu_item->url = esc_url( add_query_arg( array(
							'field_' . xprofile_get_field_id_from_name( 'City' ) => urlencode( str_replace( ' ', '+', $menu_item->title ) ),
							'bps_form' => $directory_search_form->ID,
						), get_permalink( $directory_pages['members'] ) ) );

						$this->update_menu_item( $cities_menu->term_id, $menu_item );
					}
				}
			}

			// Assign PMPro pages
			$this->assign_pmpro_pages();

			// Activate Jetpack modules
			if ( class_exists( 'Jetpack' ) ) {
				Jetpack::activate_module( 'tiled-gallery' );
				Jetpack::activate_module( 'infinite-scroll' );
				Jetpack::activate_module( 'carousel' );
			}
		}

		/**
		 * After import logic for the Mentor demo
		 */
		public function after_import_mentor() {
			$primary_menu           = get_term_by( 'name', 'Primary', 'nav_menu' );
			$logged_in_menu         = get_term_by( 'name', 'User - Logged In', 'nav_menu' );
			$logged_out_menu        = get_term_by( 'name', 'User - Logged Out', 'nav_menu' );
			$homepage_features_menu = get_term_by( 'name', 'Features - Homepage', 'nav_menu' );
			$prefooter_1_menu       = get_term_by( 'name', 'Pre Footer 1', 'nav_menu' );
			$social_menu            = get_term_by( 'name', 'Social', 'nav_menu' );

			$logged_in_menu_items         = wp_get_nav_menu_items( $logged_in_menu );
			$primary_menu_items           = wp_get_nav_menu_items( $primary_menu );
			$homepage_features_menu_items = wp_get_nav_menu_items( $homepage_features_menu );
			$prefooter_1_menu_items       = wp_get_nav_menu_items( $prefooter_1_menu );
			$social_menu_items            = wp_get_nav_menu_items( $social_menu );

			// Assign menus to their locations.
			set_theme_mod(
				'nav_menu_locations', array(
					'primary'         => $primary_menu->term_id,
					'user_logged_in'  => $logged_in_menu->term_id,
					'user_logged_out' => $logged_out_menu->term_id,
				)
			);

			// Adjust Features menu items
			foreach ( $homepage_features_menu_items as $menu_item ) {
				switch ( $menu_item->title ) {
					case 'Modern':
						$menu_item->title = '<span class="icon-wrapper"><i class="fa fa-rocket"></i><span class="h5 d-block">Modern</span></span>';
						break;
					case 'Centralized':
						$menu_item->title = '<span class="icon-wrapper"><i class="fa fa-circle-o-notch"></i><span class="h5 d-block">Centralized</span></span>';
						break;
					case 'Scalable':
						$menu_item->title = '<span class="icon-wrapper"><i class="fa fa-signal"></i><span class="h5 d-block">Scalable</span></span>';
						break;
				}

				$this->update_menu_item( $homepage_features_menu->term_id, $menu_item );
			}

			// Adjust Social menu items
			foreach ( $social_menu_items as $menu_item ) {
				switch ( $menu_item->url ) {
					case 'https://www.instagram.com/':
						$menu_item->title = '<i class="fa fa-instagram"></i>';
						break;
					case 'https://twitter.com/':
						$menu_item->title = '<i class="fa fa-twitter"></i>';
						break;
					case 'https://www.facebook.com/':
						$menu_item->title = '<i class="fa fa-facebook"></i>';
						break;
					case 'https://www.youtube.com/':
						$menu_item->title = '<i class="fa fa-youtube-play"></i>';
						break;
					case 'https://www.snapchat.com/':
						$menu_item->title = '<i class="fa fa-snapchat-ghost"></i>';
						break;
				}

				$this->update_menu_item( $social_menu->term_id, $menu_item );
			}

			if ( class_exists( 'BuddyPress' ) ) {
				// Enable registrations
				update_option( 'users_can_register', 1 );

				// Activate BP components
				$this->activate_bp_components();

				// Adjust Primary menu items
				$this->convert_relative_menu_items_urls( $primary_menu, $primary_menu_items );

				// Adjust User - Logged In menu items
				$this->convert_relative_menu_items_urls( $logged_in_menu, $logged_in_menu_items );

				// Adjust Prefooter 1 menu items
				$this->convert_relative_menu_items_urls( $prefooter_1_menu, $prefooter_1_menu_items );

				// Generate profile fields in the "Base" tab
				$this->generate_base_xprofile_fields();

				// Add "Education" field in "Base" tab
				$this->generate_xprofile_field( 'Education', 'selectbox', 1, false, array(
					'High school',
					'2-year college',
					'University',
					'Post grad',
				) );

				// Get ids of component pages (directories pages, register page, etc...)
				$directory_pages = bp_get_option( 'bp-pages' );

				// Get Sidebar Left and Right directory pages
				$directory_sidebar_left  = get_page_by_title( 'Members Sidebar Left' );
				$directory_sidebar_right = get_page_by_title( 'Members Sidebar Right' );

				// Fix members directory search forms
				$directory_search_fields = array(
					'field_' . xprofile_get_field_id_from_name( 'Name' ),
					'field_' . xprofile_get_field_id_from_name( 'Passion' ),
					'field_' . xprofile_get_field_id_from_name( 'Birthdate' ),
					'field_' . xprofile_get_field_id_from_name( 'Gender' ),
					'field_' . xprofile_get_field_id_from_name( 'Country' ),
					'field_' . xprofile_get_field_id_from_name( 'City' ),
					'field_any',
				);
				if ( ! empty( $directory_pages['members'] ) ) {
					$this->fix_bps_form( 'Search directory', $directory_search_fields, $directory_pages['members'] );
				}
				if ( ! empty( $directory_sidebar_left ) ) {
					$this->fix_bps_form( 'Search Directory Sidebar Left', $directory_search_fields, $directory_sidebar_left->ID );
				}
				if ( ! empty( $directory_sidebar_right ) ) {
					$this->fix_bps_form( 'Search Directory Sidebar Right', $directory_search_fields, $directory_sidebar_right->ID );
				}

				// Fix home search form
				$this->fix_bps_form( 'Search home', array(
					'field_' . xprofile_get_field_id_from_name( 'Name' ),
					'field_' . xprofile_get_field_id_from_name( 'Passion' ),
					'field_' . xprofile_get_field_id_from_name( 'Education' ),
					'field_' . xprofile_get_field_id_from_name( 'Birthdate' ),
				), $directory_pages['members'] );
			}

			// Assign PMPro pages
			$this->assign_pmpro_pages();

			// Activate Jetpack modules
			if ( class_exists( 'Jetpack' ) ) {
				Jetpack::activate_module( 'tiled-gallery' );
				Jetpack::activate_module( 'infinite-scroll' );
				Jetpack::activate_module( 'carousel' );
			}
		}

		/**
		 * After import logic for the Youzer demo
		 */
		public function after_import_youzer() {
			$primary_menu           = get_term_by( 'name', 'Primary', 'nav_menu' );
			$logged_in_menu         = get_term_by( 'name', 'User - Logged In', 'nav_menu' );
			$logged_out_menu        = get_term_by( 'name', 'User - Logged Out', 'nav_menu' );
			$homepage_features_menu = get_term_by( 'name', 'Features - Homepage', 'nav_menu' );
			$cities_menu            = get_term_by( 'name', 'Cities', 'nav_menu' );
			$prefooter_1_menu       = get_term_by( 'name', 'Pre Footer 1', 'nav_menu' );
			$prefooter_3_menu       = get_term_by( 'name', 'Pre Footer 3', 'nav_menu' );

			$logged_in_menu_items         = wp_get_nav_menu_items( $logged_in_menu );
			$primary_menu_items           = wp_get_nav_menu_items( $primary_menu );
			$homepage_features_menu_items = wp_get_nav_menu_items( $homepage_features_menu );
			$cities_menu_items            = wp_get_nav_menu_items( $cities_menu );
			$prefooter_1_menu_items       = wp_get_nav_menu_items( $prefooter_1_menu );
			$prefooter_3_menu_items       = wp_get_nav_menu_items( $prefooter_3_menu );

			// Assign menus to their locations.
			set_theme_mod(
				'nav_menu_locations', array(
					'primary'         => $primary_menu->term_id,
					'user_logged_in'  => $logged_in_menu->term_id,
					'user_logged_out' => $logged_out_menu->term_id,
				)
			);

			// Adjust Features menu items
			foreach ( $homepage_features_menu_items as $menu_item ) {
				switch ( $menu_item->title ) {
					case 'Talk to Real People':
						$menu_item->title = '<span class="icon-wrapper"><i class="gwangi-icon gwangi-love-on"></i><span class="h5 d-block">Talk to Real People</span></span>';
						break;
					case 'Smooth & Simple Features':
						$menu_item->title = '<span class="icon-wrapper"><i class="gwangi-icon gwangi-smile"></i><span class="h5 d-block">Smooth & Simple Features</span></span>';
						break;
					case 'Your Data are Safe':
						$menu_item->title = '<span class="icon-wrapper"><i class="fa fa-lock"></i><span class="h5 d-block">Your Data are Safe</span></span>';
						break;
				}

				$this->update_menu_item( $homepage_features_menu->term_id, $menu_item );
			}

			// Adjust Prefooter 3 menu items
			foreach ( $prefooter_3_menu_items as $menu_item ) {
				switch ( $menu_item->url ) {
					case 'https://www.instagram.com/':
						$menu_item->title = '<i class="fa fa-instagram"></i> gwangi_community';
						break;
					case 'https://twitter.com/':
						$menu_item->title = '<i class="fa fa-twitter"></i> gwangi';
						break;
					case 'https://www.facebook.com/':
						$menu_item->title = '<i class="fa fa-facebook"></i> gwangi';
						break;
					case 'https://www.youtube.com/':
						$menu_item->title = '<i class="fa fa-youtube-play"></i> gwangi_tv';
						break;
					case 'https://www.snapchat.com/':
						$menu_item->title = '<i class="fa fa-snapchat-ghost"></i> gwangi_community';
						break;
				}

				$this->update_menu_item( $prefooter_3_menu->term_id, $menu_item );
			}

			if ( class_exists( 'BuddyPress' ) ) {
				// Enable registrations
				update_option( 'users_can_register', 1 );

				// Activate BP components
				$this->activate_bp_components();

				// Adjust Primary menu items
				$this->convert_relative_menu_items_urls( $primary_menu, $primary_menu_items );

				// Adjust User - Logged In menu items
				$this->convert_relative_menu_items_urls( $logged_in_menu, $logged_in_menu_items );

				// Adjust Prefooter 1 menu items
				$this->convert_relative_menu_items_urls( $prefooter_1_menu, $prefooter_1_menu_items );

				// Generate profile fields in the "Base" tab
				$this->generate_base_xprofile_fields();

				// Get ids of component pages (directories pages, register page, etc...)
				$directory_pages = bp_get_option( 'bp-pages' );

				// Fix members directory search forms
				$directory_search_fields = array(
					'field_' . xprofile_get_field_id_from_name( 'Name' ),
					'field_' . xprofile_get_field_id_from_name( 'Passion' ),
					'field_' . xprofile_get_field_id_from_name( 'Birthdate' ),
					'field_' . xprofile_get_field_id_from_name( 'Gender' ),
					'field_' . xprofile_get_field_id_from_name( 'Country' ),
					'field_' . xprofile_get_field_id_from_name( 'City' ),
					'field_any',
				);
				if ( ! empty( $directory_pages['members'] ) ) {
					$this->fix_bps_form( 'Search directory', $directory_search_fields, $directory_pages['members'] );
				}

				// Adjust Cities menu items
				$directory_search_form = get_page_by_title( 'Search directory', 'OBJECT', 'bps_form' );
				if ( ! empty( $directory_search_form ) ) {
					foreach ( $cities_menu_items as $menu_item ) {
						$menu_item->url = esc_url( add_query_arg( array(
							'field_' . xprofile_get_field_id_from_name( 'City' ) => urlencode( str_replace( ' ', '+', $menu_item->title ) ),
							'bps_form' => $directory_search_form->ID,
						), get_permalink( $directory_pages['members'] ) ) );

						$this->update_menu_item( $cities_menu->term_id, $menu_item );
					}
				}
			}

			// Assign PMPro pages
			$this->assign_pmpro_pages();

			// Activate Jetpack modules
			if ( class_exists( 'Jetpack' ) ) {
				Jetpack::activate_module( 'tiled-gallery' );
				Jetpack::activate_module( 'infinite-scroll' );
				Jetpack::activate_module( 'carousel' );
			}
		}

		/**
		 * After import logic for the Dating demo
		 */
		public function after_import_dating() {
			// Assign front page.
			$front_page = get_page_by_title( 'Home' );
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $front_page->ID );

			$primary_menu           = get_term_by( 'name', 'Primary', 'nav_menu' );
			$logged_in_menu         = get_term_by( 'name', 'User - Logged In', 'nav_menu' );
			$logged_out_menu        = get_term_by( 'name', 'User - Logged Out', 'nav_menu' );
			$homepage_features_menu = get_term_by( 'name', 'Features - Homepage', 'nav_menu' );
			$interests_menu         = get_term_by( 'name', 'Interests', 'nav_menu' );
			$prefooter_1_menu       = get_term_by( 'name', 'Pre Footer 1', 'nav_menu' );
			$prefooter_3_menu       = get_term_by( 'name', 'Pre Footer 3', 'nav_menu' );

			$logged_in_menu_items         = wp_get_nav_menu_items( $logged_in_menu );
			$primary_menu_items           = wp_get_nav_menu_items( $primary_menu );
			$homepage_features_menu_items = wp_get_nav_menu_items( $homepage_features_menu );
			$interests_menu_items         = wp_get_nav_menu_items( $interests_menu );
			$prefooter_1_menu_items       = wp_get_nav_menu_items( $prefooter_1_menu );
			$prefooter_3_menu_items       = wp_get_nav_menu_items( $prefooter_3_menu );

			// Assign menus to their locations.
			set_theme_mod(
				'nav_menu_locations', array(
					'primary'         => $primary_menu->term_id,
					'user_logged_in'  => $logged_in_menu->term_id,
					'user_logged_out' => $logged_out_menu->term_id,
				)
			);

			// Adjust Features menu items
			foreach ( $homepage_features_menu_items as $menu_item ) {
				switch ( $menu_item->title ) {
					case 'Talk to Real People':
						$menu_item->title = '<span class="icon-wrapper"><i class="gwangi-icon gwangi-love-on"></i><span class="h5 d-block">Talk to Real People</span></span>';
						break;
					case 'Smooth & Simple Features':
						$menu_item->title = '<span class="icon-wrapper"><i class="gwangi-icon gwangi-smile"></i><span class="h5 d-block">Smooth & Simple Features</span></span>';
						break;
					case 'Your Data are Safe':
						$menu_item->title = '<span class="icon-wrapper"><i class="fa fa-lock"></i><span class="h5 d-block">Your Data are Safe</span></span>';
						break;
				}

				$this->update_menu_item( $homepage_features_menu->term_id, $menu_item );
			}

			// Adjust Prefooter 3 menu items
			foreach ( $prefooter_3_menu_items as $menu_item ) {
				switch ( $menu_item->url ) {
					case 'https://www.instagram.com/':
						$menu_item->title = '<i class="fa fa-instagram"></i> gwangi_community';
						break;
					case 'https://twitter.com/':
						$menu_item->title = '<i class="fa fa-twitter"></i> gwangi';
						break;
					case 'https://www.facebook.com/':
						$menu_item->title = '<i class="fa fa-facebook"></i> gwangi';
						break;
					case 'https://www.youtube.com/':
						$menu_item->title = '<i class="fa fa-youtube-play"></i> gwangi_tv';
						break;
					case 'https://www.snapchat.com/':
						$menu_item->title = '<i class="fa fa-snapchat-ghost"></i> gwangi_community';
						break;
				}

				$this->update_menu_item( $prefooter_3_menu->term_id, $menu_item );
			}

			if ( class_exists( 'BuddyPress' ) ) {
				// Enable registrations
				update_option( 'users_can_register', 1 );

				// Activate BP components
				$this->activate_bp_components();

				// Adjust Primary menu items
				$this->convert_relative_menu_items_urls( $primary_menu, $primary_menu_items );

				// Adjust User - Logged In menu items
				$this->convert_relative_menu_items_urls( $logged_in_menu, $logged_in_menu_items );

				// Adjust Prefooter 1 menu items
				$this->convert_relative_menu_items_urls( $prefooter_1_menu, $prefooter_1_menu_items );

				// Generate profile fields in the "Base" tab
				$this->generate_base_xprofile_fields( true );

				// Generate profile fields in the "Availability" tab
				$this->generate_availability_profile_fields();

				// Generate profile fields in the "Looks" tab
				$this->generate_looks_profile_fields();

				// Generate profile fields in the "Background" tab
				$this->generate_background_profile_fields();

				// Get ids of component pages (directories pages, register page, etc...)
				$directory_pages = bp_get_option( 'bp-pages' );

				// Fix members directory search form
				$this->fix_bps_form( 'Search directory', array(
					'field_any',
					'field_' . xprofile_get_field_id_from_name( 'Connection' ),
					'field_' . xprofile_get_field_id_from_name( 'Gender' ),
					'field_' . xprofile_get_field_id_from_name( 'Looking for' ),
					'field_' . xprofile_get_field_id_from_name( 'Birthdate' ),
					'field_' . xprofile_get_field_id_from_name( 'Passion' ),
					'field_' . xprofile_get_field_id_from_name( 'Main language' ),
					'field_' . xprofile_get_field_id_from_name( 'Body type' ),
					'field_' . xprofile_get_field_id_from_name( 'Pets' ),
				), $directory_pages['members'] );

				// Get Sidebar Left and Right directory pages
				$directory_sidebar_left  = get_page_by_title( 'Members Sidebar Left' );
				$directory_sidebar_right = get_page_by_title( 'Members Sidebar Right' );

				// Fix sidebar directories search forms
				$sidebar_directory_search_fields = array(
					'field_any',
					'field_' . xprofile_get_field_id_from_name( 'Connection' ),
					'field_' . xprofile_get_field_id_from_name( 'Gender' ),
					'field_' . xprofile_get_field_id_from_name( 'Looking for' ),
					'field_' . xprofile_get_field_id_from_name( 'Birthdate' ),
				);
				if ( ! empty( $directory_sidebar_left ) ) {
					$this->fix_bps_form( 'Search Directory Sidebar Left', $sidebar_directory_search_fields, $directory_sidebar_left->ID );
				}
				if ( ! empty( $directory_sidebar_right ) ) {
					$this->fix_bps_form( 'Search Directory Sidebar Right', $sidebar_directory_search_fields, $directory_sidebar_right->ID );
				}

				// Fix home search form
				$this->fix_bps_form( 'Search home', array(
					'field_' . xprofile_get_field_id_from_name( 'Connection' ),
					'field_' . xprofile_get_field_id_from_name( 'Gender' ),
					'field_' . xprofile_get_field_id_from_name( 'Birthdate' ),
				), $directory_pages['members'] );

				// Fix home search form
				$this->fix_bps_form( 'Search map', array(
					'field_' . xprofile_get_field_id_from_name( 'Location' ),
					'field_' . xprofile_get_field_id_from_name( 'Birthdate' ),
					'field_' . xprofile_get_field_id_from_name( 'Gender' ),
					'field_' . xprofile_get_field_id_from_name( 'Passion' ),
				), $directory_pages['members'] );

				// Adjust Interests menu items
				$directory_search_form = get_page_by_title( 'Search directory', 'OBJECT', 'bps_form' );
				if ( ! empty( $directory_search_form ) ) {
					foreach ( $interests_menu_items as $menu_item ) {
						switch ( $menu_item->title ) {
							case 'Long term dating':
								$menu_item->url = esc_url( add_query_arg( array(
									'field_' . xprofile_get_field_id_from_name( 'Connection' ) => 'Long-term+dating',
									'bps_form' => $directory_search_form->ID,
								), get_permalink( $directory_pages['members'] ) ) );
								break;
							case 'Sport addicts':
								$menu_item->url = esc_url( add_query_arg( array(
									'field_' . xprofile_get_field_id_from_name( 'Passion' ) => 'Sport',
									'bps_form' => $directory_search_form->ID,
								), get_permalink( $directory_pages['members'] ) ) );
								break;
							case 'Spanish speaking':
								$menu_item->url = esc_url( add_query_arg( array(
									'field_' . xprofile_get_field_id_from_name( 'Main language' ) => 'Spanish',
									'bps_form' => $directory_search_form->ID,
								), get_permalink( $directory_pages['members'] ) ) );
								break;
							case 'Cooking masters':
								$menu_item->url = esc_url( add_query_arg( array(
									'field_' . xprofile_get_field_id_from_name( 'Passion' ) => 'Cooking',
									'bps_form' => $directory_search_form->ID,
								), get_permalink( $directory_pages['members'] ) ) );
								break;
							case 'Cats lovers':
								$menu_item->url = esc_url( add_query_arg( array(
									'field_' . xprofile_get_field_id_from_name( 'Pets' ) => urlencode( 'Cat(s)' ),
									'bps_form' => $directory_search_form->ID,
								), get_permalink( $directory_pages['members'] ) ) );
								break;
						}

						$this->update_menu_item( $interests_menu->term_id, $menu_item );
					}
				}

				// Adjust BP Maps for Members settings
				$settings_single = get_site_option( 'bp-member-map-single-settings', array() );
				$settings_single['map_zoom_level']     = 15;
				$settings_single['map_height']         = 400;
				$settings_single['map_location_field'] = xprofile_get_field_id_from_name( 'Location' );
				update_site_option( 'bp-member-map-single-settings', $settings_single );

				$settings_all = get_site_option( 'bp-member-map-all-settings', array() );
				$settings_all['map_zoom_level_all']              = 18;
				$settings_all['map_height_all']                  = 500;
				$settings_all['map_location_field_all']          = xprofile_get_field_id_from_name( 'Location' );
				$settings_all['map_member_distance_measurement'] = 'kilometers';
				$settings_all['map_member_filter_bps']           = 1;
				update_site_option( 'bp-member-map-all-settings', $settings_all );

				// Assign PMPro pages
				$this->assign_pmpro_pages();

				// Activate Jetpack modules
				if ( class_exists( 'Jetpack' ) ) {
					Jetpack::activate_module( 'tiled-gallery' );
					Jetpack::activate_module( 'infinite-scroll' );
					Jetpack::activate_module( 'carousel' );
				}
			}
		}

		/**
		 * After import logic for the Shop demo
		 */
		public function after_import_shop() {
			$primary_menu  = get_term_by( 'name', 'Primary', 'nav_menu' );
			$tertiary_menu = get_term_by( 'name', 'Tertiary', 'nav_menu' );

			$tertiary_menu_items = wp_get_nav_menu_items( $tertiary_menu );

			// Assign menus to their locations.
			set_theme_mod(
				'nav_menu_locations', array(
					'primary' => $primary_menu->term_id,
				)
			);

			// Adjust Tertiary menu items
			foreach ( $tertiary_menu_items as $menu_item ) {
				switch ( $menu_item->title ) {
					case '+32 0458 85 96 51':
						$menu_item->title = '<i class="fa fa-phone"></i> +32 0458 85 96 51';
						break;
					case '07:00 - 20:30':
						$menu_item->title = '<i class="fa fa-clock-o"></i> 07:00 - 20:30';
						break;
					case 'support@gwangi-store.com':
						$menu_item->title = '<i class="fa fa-envelope-o"></i> support@gwangi-store.com';
						break;
				}

				$this->update_menu_item( $tertiary_menu->term_id, $menu_item );
			}

			// Setup WooCommerce pages
			if ( class_exists( 'WooCommerce' ) ) {
				$shop_page = get_page_by_title( 'Shop' );
				update_option( 'woocommerce_shop_page_id', $shop_page->ID );
				$cart_page = get_page_by_title( 'Cart' );
				update_option( 'woocommerce_cart_page_id', $cart_page->ID );
				$checkout_page = get_page_by_title( 'Checkout' );
				update_option( 'woocommerce_checkout_page_id', $checkout_page->ID );
				$account_page = get_page_by_title( 'My account' );
				update_option( 'woocommerce_myaccount_page_id', $account_page->ID );

				// Setup YITH Wishlist page
				if ( class_exists( 'YITH_WCWL' ) ) {
					$wishlist_page = get_page_by_title( 'My Wishlist' );
					update_option( 'yith_wcwl_wishlist_page_id', $wishlist_page->ID );
				}

				// Setup YITH Infnite Scroll
				if ( class_exists( 'YITH_INFS' ) ) {
					$yith_infs_options = get_option( YITH_INFS_OPTION_NAME );

					$yith_infs_options['yith-infs-navselector']     = 'nav.woocommerce-pagination';
					$yith_infs_options['yith-infs-nextselector']    = 'nav.woocommerce-pagination a.next';
					$yith_infs_options['yith-infs-itemselector']    = 'li.product';
					$yith_infs_options['yith-infs-contentselector'] = '#main';

					update_option( YITH_INFS_OPTION_NAME, $yith_infs_options );
				}
			}
		}

		/**
		 * Replace pages with their Elementor template counterpart
		 */
		public function setup_elementor() {
			if ( ! class_exists( 'Elementor\Plugin' ) ) {
				return;
			}

			update_option( 'elementor_disable_color_schemes', 'yes' );
			update_option( 'elementor_disable_typography_schemes', 'yes' );
			update_option( 'elementor_container_width', '1400' );
			update_option( 'elementor_space_between_widgets', '0' );
			update_option( 'elementor_viewport_lg', '992' );
			update_option( 'elementor_viewport_md', '768' );
			update_option( 'elementor_page_title_selector', '#custom_header' );

			$elementor = Elementor\Plugin::instance();

			$pages = get_posts( array(
				'post_type' => 'page',
				'posts_per_page' => -1,
			) );

			foreach ( $pages as $page ) {
				$elementor_template = get_page_by_title( $page->post_title, 'OBJECT', 'elementor_library' );

				if ( ! empty( $elementor_template ) ) {
					$template_data = $elementor->templates_manager->get_template_data( array(
						'display'       => true,
						'edit_mode'     => true,
						'page_settings' => true,
						'source'        => "local",
						'template_id'   => $elementor_template->ID,
					) );

					$elementor_page_template = 'elementor_header_footer';

					if ( ! empty( $template_data['page_settings'] ) ) {
						update_post_meta( $page->ID, '_elementor_page_settings', $template_data['page_settings'] );

						if ( ! empty( $template_data['page_settings']['template'] ) ) {
							$elementor_page_template = $template_data['page_settings']['template'];
						}
					}

					update_post_meta( $page->ID, '_wp_page_template', $elementor_page_template );
					update_post_meta( $page->ID, '_elementor_edit_mode', 'builder' );

					if ( ! empty( $template_data['content'] ) ) {
						update_post_meta( $page->ID, '_elementor_data', $template_data['content'] );
					}
				}
			}
		}

		/**
		 * Activate "groups", "friends" and "messages" components in BuddyPress
		 */
		private function activate_bp_components() {
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			require_once( buddypress()->plugin_dir . '/bp-core/admin/bp-core-admin-schema.php' );

			$bp_active_components             = bp_get_option( 'bp-active-components', array() );
			$bp_active_components['groups']   = 1;
			$bp_active_components['friends']  = 1;
			$bp_active_components['messages'] = 1;

			// Generate necessary pages and emails for newly activated components
			bp_core_install( $bp_active_components );
			bp_update_option( 'bp-active-components', $bp_active_components );
			bp_core_add_page_mappings( $bp_active_components );
			bp_core_install_emails();
		}

		/**
		 * Generate profile fields in the "Base" tab
		 *
		 * @param bool $use_xprofile_location
		 */
		private function generate_base_xprofile_fields( $use_xprofile_location = false ) {
			// Generate Birthdate field.
			$this->generate_xprofile_field( 'Birthdate', 'datebox' );

			// Generate Gender field.
			$this->generate_xprofile_field( 'Gender', 'selectbox', 1, true, array(
				'Male',
				'Female',
				'Other',
			) );

			if ( $use_xprofile_location ) {
				// Generate Location field
				$profile_field_id = $this->generate_xprofile_field( 'Location', 'location', 1, false );
				bp_xprofile_update_meta( $profile_field_id, 'data', 'geocode', '1' );
			}
			else {
				// Generate Country field.
				$this->generate_xprofile_field( 'Country', 'selectbox', 1, true, $this->get_countries() );

				// Generate City field.
				$this->generate_xprofile_field( 'City' );
			}

			// Generate Passion field.
			$this->generate_xprofile_field( 'Passion', 'selectbox', 1, false, array(
				'Sport',
				'Travel',
				'Cooking',
				'Cinema',
				'Music',
				'Tatoo',
				'Books',
				'Gaming',
				'History',
			) );
		}

		/**
		 * Generate profile fields in the "Availability" tab
		 */
		private function generate_availability_profile_fields() {
			xprofile_insert_field_group( array(
				'field_group_id' => 2,
				'name'           => 'Availability',
				'description'    => '',
				'can_delete'     => true
			) );

			// Generate Connection field.
			$this->generate_xprofile_field( 'Connection', 'selectbox', 2, true, array(
				'Long Term Dating',
				'Short Term Dating',
				'Hookups',
				'New Friends',
			), 'What kind of relationship are you looking for?' );

			// Generate Looking for field
			$this->generate_xprofile_field( 'Looking for', 'selectbox', 2, true, array(
				'Women',
				'Men',
				'Others',
			), 'What are you looking for?' );

			// Generate Status field
			$this->generate_xprofile_field( 'Status', 'selectbox', 2, true, array(
				'Single',
				'Not Single',
			) );

			// Generate Kids field
			$this->generate_xprofile_field( 'Kids', 'selectbox', 2, false, array(
				'Wants kids',
				'Might want kids',
				"Doesn't want kids",
			) );
		}

		/**
		 * Generate profile fields in the "Looks" tab
		 */
		private function generate_looks_profile_fields() {
			xprofile_insert_field_group( array(
				'field_group_id' => 3,
				'name'           => 'Looks',
				'description'    => '',
				'can_delete'     => true
			) );

			// Generate Height field.
			$this->generate_xprofile_field( 'Height', 'number', 3, false, array(), 'Enter your height in cm' );

			// Generate Body type field
			$this->generate_xprofile_field( 'Body type', 'selectbox', 3, false, array(
				'Average',
				'Thin',
				'Fit',
				'Jacked',
				'Curvy',
				'Thin',
				'Full figured',
				'A little extra',
				'Overweight',
			) );
		}

		/**
		 * Generate profile fields in the "Background" tab
		 */
		private function generate_background_profile_fields() {
			xprofile_insert_field_group( array(
				'field_group_id' => 4,
				'name'           => 'Background',
				'description'    => '',
				'can_delete'     => true
			) );

			// Generate Main language field.
			$this->generate_xprofile_field( 'Main language', 'selectbox', 4, false, array(
				'English',
				'Spanish',
				'German',
				'French',
				'Chinese',
				'Hindi',
				'Arabic',
				'Portuguese',
				'Russian',
				'Japanese',
				'Korean',
			) );

			// Generate Ethnicity field.
			$this->generate_xprofile_field( 'Ethnicity', 'checkbox', 4, false, array(
				'Asian',
				'Black',
				'Native American',
				'Hispanic / Latin',
				'Indian',
				'Middle Eastern',
				'Pacific Islander',
				'White',
				'Other',
			) );

			// Generate Drinks field.
			$this->generate_xprofile_field( 'Drinks', 'selectbox', 4, false, array(
				'Never',
				'Sometimes',
				'Often',
			) );

			// Generate Smokes field.
			$this->generate_xprofile_field( 'Smokes', 'selectbox', 4, false, array(
				'Never',
				'Sometimes',
				'Often',
			) );

			// Generate Education field.
			$this->generate_xprofile_field( 'Education', 'selectbox', 4, false, array(
				'High school',
				'2-year college',
				'University',
				'Post grad',
			) );

			// Generate Pets field.
			$this->generate_xprofile_field( 'Pets', 'checkbox', 4, false, array(
				'Cat(s)',
				'Dog(s)',
			) );

		}

		/**
		 * Fix BP Profile Search form after import
		 *
		 * @param string $name The form name
		 * @param array $fields The form fields in the same order as the demo
		 * @param string|int $results_page The results page id
		 */
		private function fix_bps_form( $name, $fields, $results_page ) {
			$form = get_page_by_title( $name, 'OBJECT', 'bps_form' );

			if ( ! empty( $form ) ) {
				$bps_options = get_post_meta( $form->ID, 'bps_options', true );

				$bps_options['field_code'] = $fields;

				$bps_options['action'] = strval( $results_page );

				update_post_meta( $form->ID, 'bps_options', $bps_options );
			}
		}

		/**
		 * Assign Paid Memberships Pro pages in settings
		 */
		private function assign_pmpro_pages() {
			if ( function_exists( 'pmpro_init' ) ) {
				$account_page = get_page_by_title( 'Membership Account' );
				pmpro_setOption( 'account_page_id', $account_page->ID, 'intval' );
				$billing_page = get_page_by_title( 'Membership Billing' );
				pmpro_setOption( 'billing_page_id', $billing_page->ID, 'intval' );
				$cancel_page = get_page_by_title( 'Membership Cancel' );
				pmpro_setOption( 'cancel_page_id', $cancel_page->ID, 'intval' );
				$checkout_page = get_page_by_title( 'Membership Checkout' );
				pmpro_setOption( 'checkout_page_id', $checkout_page->ID, 'intval' );
				$confirmation_page = get_page_by_title( 'Membership Confirmation' );
				pmpro_setOption( 'confirmation_page_id', $confirmation_page->ID, 'intval' );
				$invoice_page = get_page_by_title( 'Membership Invoice' );
				pmpro_setOption( 'invoice_page_id', $invoice_page->ID, 'intval' );
				$pricing_page = get_page_by_title( 'Pricing & Plans' );
				pmpro_setOption( 'levels_page_id', $pricing_page->ID, 'intval' );
				$restricted_page = get_page_by_title( 'Access Restricted' );
				pmpro_setOption( 'pmprobp_restricted_page_id', $restricted_page->ID, 'intval' );
			}
		}

		/**
		 * Generate a new xprofile field if it doesn't already exists
		 *
		 * @param string $name The field name
		 * @param string $type The field type
		 * @param int $field_group The field group (tab)
		 * @param bool $required Whether the field is required
		 * @param array $choices Choices for selectbox fields
		 * @param string $description The field description
		 *
		 * @return int|bool The profile field id or false if failed
		 */
		private function generate_xprofile_field( $name, $type = 'textbox', $field_group = 1, $required = true, $choices = array(), $description = '' ) {
			$slug = str_replace( ' ', '_', strtolower( $name ) );

			$profile_field_id = xprofile_get_field_id_from_name( $name );
			if ( function_exists( 'xprofile_get_field_id_from_name' ) && ! $profile_field_id ) {
				$profile_field_id = xprofile_insert_field( apply_filters( "gwangi_merlin_xprofile_{$slug}_field", array(
					'field_group_id' => $field_group,
					'name'           => $name,
					'description'    => $description,
					'is_required'    => $required,
					'type'           => $type,
					'can_delete'     => 1,
				) ) );

				if ( ( 'selectbox' === $type || 'checkbox' === $type ) && $profile_field_id ) {
					$choices = apply_filters( "gwangi_merlin_xprofile_{$slug}", $choices );

					foreach ( $choices as $i => $choice ) {
						xprofile_insert_field( array(
							'field_group_id' => $field_group,
							'parent_id'      => $profile_field_id,
							'type'           => $type,
							'name'           => $choice,
							'option_order'   => $i + 1,
						) );
					}
				}
			}

			return $profile_field_id;
		}

		/**
		 * Convert relative urls in menu items into absolute urls
		 *
		 * @param WP_Term $menu The menu object
		 * @param array $menu_items The menu items associated with the menu
		 */
		private function convert_relative_menu_items_urls( $menu, $menu_items ) {
			if ( class_exists( 'BuddyPress' ) ) {
				$directory_pages = bp_get_option( 'bp-pages' );

				foreach ( $menu_items as $menu_item ) {
					switch ( $menu_item->url ) {
						case '/members':
						case '/members/':
							$menu_item->url = esc_url( get_permalink( $directory_pages['members'] ) );
							$this->update_menu_item( $menu->term_id, $menu_item );
							break;
						case '/groups':
						case '/groups/':
							$menu_item->url = esc_url( get_permalink( $directory_pages['groups'] ) );
							$this->update_menu_item( $menu->term_id, $menu_item );
							break;
						case '/groups/create':
						case '/groups/create/':
							$menu_item->url = esc_url( trailingslashit( get_permalink( $directory_pages['groups'] ) ) . 'create' );
							$this->update_menu_item( $menu->term_id, $menu_item );
							break;
						case '/activity':
						case '/activity/':
							$menu_item->url = esc_url( get_permalink( $directory_pages['activity'] ) );
							$this->update_menu_item( $menu->term_id, $menu_item );
							break;
						case '/members/elia':
						case '/members/elia/':
							wp_delete_post( $menu_item->db_id );
							break;
						case '/groups/tatoo':
						case '/groups/tatoo/':
							wp_delete_post( $menu_item->db_id );
							break;
					}

					if ( strpos( $menu_item->url, '/register' ) ) {
						$menu_item->url = esc_url( get_permalink( $directory_pages['register'] ) );
						$this->update_menu_item( $menu->term_id, $menu_item );
					}
				}
			}
		}

		/**
		 * Return an array of all countries
		 *
		 * @return array
		 */
		private function get_countries() {
			return array(
				'Afghanistan',
				'Albania',
				'Algeria',
				'Andorra',
				'Angola',
				'Antigua and Barbuda',
				'Argentina',
				'Armenia',
				'Australia',
				'Austria',
				'Azerbaijan',
				'Bahamas',
				'Bahrain',
				'Bangladesh',
				'Barbados',
				'Belarus',
				'Belgium',
				'Belize',
				'Benin',
				'Bhutan',
				'Bolivia',
				'Bosnia and Herzegovina',
				'Botswana',
				'Brazil',
				'Brunei',
				'Bulgaria',
				'Burkina Faso',
				'Burundi',
				'Cambodia',
				'Cameroon',
				'Canada',
				'Cape Verde',
				'Central African Republic',
				'Chad',
				'Chile',
				'China',
				'Colombi',
				'Comoros',
				'Congo (Brazzaville)',
				'Congo',
				'Costa Rica',
				'Cote d\'Ivoire',
				'Croatia',
				'Cuba',
				'Cyprus',
				'Czech Republic',
				'Denmark',
				'Djibouti',
				'Dominica',
				'Dominican Republic',
				'East Timor (Timor Timur)',
				'Ecuador',
				'Egypt',
				'El Salvador',
				'Equatorial Guinea',
				'Eritrea',
				'Estonia',
				'Ethiopia',
				'Fiji',
				'Finland',
				'France',
				'Gabon',
				'Gambia, The',
				'Georgia',
				'Germany',
				'Ghana',
				'Greece',
				'Grenada',
				'Guatemala',
				'Guinea',
				'Guinea-Bissau',
				'Guyana',
				'Haiti',
				'Honduras',
				'Hungary',
				'Iceland',
				'India',
				'Indonesia',
				'Iran',
				'Iraq',
				'Ireland',
				'Israel',
				'Italy',
				'Jamaica',
				'Japan',
				'Jordan',
				'Kazakhstan',
				'Kenya',
				'Kiribati',
				'Korea, North',
				'Korea, South',
				'Kuwait',
				'Kyrgyzstan',
				'Laos',
				'Latvia',
				'Lebanon',
				'Lesotho',
				'Liberia',
				'Libya',
				'Liechtenstein',
				'Lithuania',
				'Luxembourg',
				'Macedonia',
				'Madagascar',
				'Malawi',
				'Malaysia',
				'Maldives',
				'Mali',
				'Malta',
				'Marshall Islands',
				'Mauritania',
				'Mauritius',
				'Mexico',
				'Micronesia',
				'Moldova',
				'Monaco',
				'Mongolia',
				'Morocco',
				'Mozambique',
				'Myanmar',
				'Namibia',
				'Nauru',
				'Nepal',
				'Netherlands',
				'New Zealand',
				'Nicaragua',
				'Niger',
				'Nigeria',
				'Norway',
				'Oman',
				'Pakistan',
				'Palau',
				'Panama',
				'Papua New Guinea',
				'Paraguay',
				'Peru',
				'Philippines',
				'Poland',
				'Portugal',
				'Qatar',
				'Romania',
				'Russia',
				'Rwanda',
				'Saint Kitts and Nevis',
				'Saint Lucia',
				'Saint Vincent',
				'Samoa',
				'San Marino',
				'Sao Tome and Principe',
				'Saudi Arabia',
				'Senegal',
				'Serbia and Montenegro',
				'Seychelles',
				'Sierra Leone',
				'Singapore',
				'Slovakia',
				'Slovenia',
				'Solomon Islands',
				'Somalia',
				'South Africa',
				'Spain',
				'Sri Lanka',
				'Sudan',
				'Suriname',
				'Swaziland',
				'Sweden',
				'Switzerland',
				'Syria',
				'Taiwan',
				'Tajikistan',
				'Tanzania',
				'Thailand',
				'Togo',
				'Tonga',
				'Trinidad and Tobago',
				'Tunisia',
				'Turkey',
				'Turkmenistan',
				'Tuvalu',
				'Uganda',
				'Ukraine',
				'United Arab Emirates',
				'United Kingdom',
				'United States',
				'Uruguay',
				'Uzbekistan',
				'Vanuatu',
				'Vatican City',
				'Venezuela',
				'Vietnam',
				'Yemen',
				'Zambia',
				'Zimbabwe',
			);
		}

		/**
		 * Update a menu item
		 *
		 * @param $menu_id int
		 * @param $menu_item WP_Post
		 *
		 * @return int|bool|WP_Error
		 */
		private function update_menu_item( $menu_id, $menu_item ) {
			if ( empty( $menu_id ) || empty( $menu_item ) ) {
				return false;
			}

			return wp_update_nav_menu_item( $menu_id, $menu_item->db_id, array(
				'menu-item-db-id'       => $menu_item->db_id,
				'menu-item-object-id'   => $menu_item->object_id,
				'menu-item-object'      => $menu_item->object,
				'menu-item-url'         => $menu_item->url,
				'menu-item-parent-id'   => $menu_item->menu_item_parent,
				'menu-item-position'    => $menu_item->menu_order,
				'menu-item-type'        => $menu_item->type,
				'menu-item-title'       => $menu_item->title,
				'menu-item-description' => $menu_item->description,
				'menu-item-attr-title'  => false,
				'menu-item-target'      => $menu_item->target,
				'menu-item-classes'     => is_array( $menu_item->classes ) ? implode( ' ', $menu_item->classes ) : $menu_item->classes,
				'menu-item-xfn'         => $menu_item->xfn,
				'menu-item-status'      => $menu_item->post_status,
			) );
		}

		/**
		 * Prevent Elementor from messing with the plugins installation step
		 */
		public function prevent_elementor_redirect() {
			delete_transient( 'elementor_activation_redirect' );
		}

		/**
		 * Remove the "child theme" step from Merlin
		 *
		 * @param $steps
		 *
		 * @return mixed
		 */
		public function change_merlin_steps( $steps ) {
			unset( $steps['child'] );

			return $steps;
		}

		/**
		 * Set directory locations, text strings, and settings for Merlin
		 */
		public function init() {
			new Merlin(
				$config  = array(
					'directory'            => 'libs/merlin', // Location / directory where Merlin WP is placed in your theme.
					'merlin_url'           => 'merlin', // The wp-admin page slug where Merlin WP loads.
					'parent_slug'          => 'themes.php', // The wp-admin parent page slug for the admin menu item.
					'capability'           => 'manage_options', // The capability required for this menu to be displayed to the user.
					'child_action_btn_url' => 'https://codex.wordpress.org/child_themes', // URL for the 'child-action-link'.
					'dev_mode'             => true, // Enable development mode for testing.
					'license_step'         => false, // EDD license activation step.
					'license_required'     => false, // Require the license activation step.
					'license_help_url'     => '', // URL for the 'license-tooltip'.
					'edd_remote_api_url'   => '', // EDD_Theme_Updater_Admin remote_api_url.
					'edd_item_name'        => '', // EDD_Theme_Updater_Admin item_name.
					'edd_theme_slug'       => '', // EDD_Theme_Updater_Admin item_slug.
					'ready_big_button_url' => home_url(), // Link for the big button on the ready step.
				),
				$strings = array(
					'admin-menu'               => esc_html__( 'Theme Setup', 'gwangi' ),
					/* translators: 1: Title Tag 2: Theme Name 3: Closing Title Tag */
					'title%s%s%s%s'            => esc_html__( '%1$s%2$s Themes &lsaquo; Theme Setup: %3$s%4$s', 'gwangi' ),
					'return-to-dashboard'      => esc_html__( 'Return to the dashboard', 'gwangi' ),
					'ignore'                   => esc_html__( 'Disable this wizard', 'gwangi' ),
					'btn-skip'                 => esc_html__( 'Skip', 'gwangi' ),
					'btn-next'                 => esc_html__( 'Next', 'gwangi' ),
					'btn-start'                => esc_html__( 'Start', 'gwangi' ),
					'btn-no'                   => esc_html__( 'Cancel', 'gwangi' ),
					'btn-plugins-install'      => esc_html__( 'Install', 'gwangi' ),
					'btn-child-install'        => esc_html__( 'Install', 'gwangi' ),
					'btn-content-install'      => esc_html__( 'Install', 'gwangi' ),
					'btn-import'               => esc_html__( 'Import', 'gwangi' ),
					'btn-license-activate'     => esc_html__( 'Activate', 'gwangi' ),
					'btn-license-skip'         => esc_html__( 'Later', 'gwangi' ),
					/* translators: Theme Name */
					'license-header%s'         => esc_html__( 'Activate %s', 'gwangi' ),
					/* translators: Theme Name */
					'license-header-success%s' => esc_html__( '%s is Activated', 'gwangi' ),
					/* translators: Theme Name */
					'license%s'                => esc_html__( 'Enter your license key to enable remote updates and theme support.', 'gwangi' ),
					'license-label'            => esc_html__( 'License key', 'gwangi' ),
					'license-success%s'        => esc_html__( 'The theme is already registered, so you can go to the next step!', 'gwangi' ),
					'license-json-success%s'   => esc_html__( 'Your theme is activated! Remote updates and theme support are enabled.', 'gwangi' ),
					'license-tooltip'          => esc_html__( 'Need help?', 'gwangi' ),
					/* translators: Theme Name */
					'welcome-header%s'         => esc_html__( 'Welcome to %s', 'gwangi' ),
					'welcome-header-success%s' => esc_html__( 'Hi. Welcome back', 'gwangi' ),
					'welcome%s'                => esc_html__( 'This wizard will set up your theme, install plugins, and import content. It is optional & should take only a few minutes.', 'gwangi' ),
					'welcome-success%s'        => esc_html__( 'You may have already run this theme setup wizard. If you would like to proceed anyway, click on the "Start" button below.', 'gwangi' ),
					'child-header'             => esc_html__( 'Install Child Theme', 'gwangi' ),
					'child-header-success'     => esc_html__( 'You\'re good to go!', 'gwangi' ),
					'child'                    => esc_html__( 'Let\'s build & activate a child theme so you may easily make theme changes.', 'gwangi' ),
					'child-success%s'          => esc_html__( 'Your child theme has already been installed and is now activated, if it wasn\'t already.', 'gwangi' ),
					'child-action-link'        => esc_html__( 'Learn about child themes', 'gwangi' ),
					'child-json-success%s'     => esc_html__( 'Awesome. Your child theme has already been installed and is now activated.', 'gwangi' ),
					'child-json-already%s'     => esc_html__( 'Awesome. Your child theme has been created and is now activated.', 'gwangi' ),
					'demo-header'              => esc_html__( 'Select Your Demo', 'gwangi' ),
					'demo'                     => esc_html__( 'You can import some demo content to help you get started with the theme.', 'gwangi' ),
					'plugins-header'           => esc_html__( 'Install Plugins', 'gwangi' ),
					'plugins-header-success'   => esc_html__( 'You\'re up to speed!', 'gwangi' ),
					'plugins'                  => esc_html__( 'Let\'s install some essential WordPress plugins to get your site up to speed.', 'gwangi' ),
					'plugins-success%s'        => esc_html__( 'The required WordPress plugins are all installed and up to date. Press "Next" to continue the setup wizard.', 'gwangi' ),
					'plugins-action-link'      => esc_html__( 'Advanced', 'gwangi' ),
					'import-header'            => esc_html__( 'Import Content', 'gwangi' ),
					'import'                   => esc_html__( 'Let\'s import content to your website, to help you get familiar with the theme.', 'gwangi' ),
					'import-action-link'       => esc_html__( 'Advanced', 'gwangi' ),
					'ready-header'             => esc_html__( 'All done. Have fun!', 'gwangi' ),
					/* translators: Theme Author */
					'ready%s'                  => esc_html__( 'Your theme has been all set up. Enjoy your new theme by %s.', 'gwangi' ),
					'ready-action-link'        => esc_html__( 'More links', 'gwangi' ),
					'ready-big-button'         => esc_html__( 'View your website', 'gwangi' ),
					'ready-link-1'             => sprintf( '<a href="%1$s" target="_blank">%2$s</a>', 'https://doc.themosaurus.com/gwangi', esc_html__( 'Documentation', 'gwangi' ) ),
					'ready-link-2'             => sprintf( '<a href="%1$s" target="_blank">%2$s</a>', 'https://support.themosaurus.com/', esc_html__( 'Theme Support', 'gwangi' ) ),
					'ready-link-3'             => sprintf( '<a href="%1$s">%2$s</a>', admin_url( 'customize.php' ), esc_html__( 'Start Customizing', 'gwangi' ) ),
				)
			);
		}
	}
endif;

return new Gwangi_Merlin();
