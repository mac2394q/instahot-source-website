<?php
/**
 * Gwangi_Grimlock_WooCommerce Class
 *
 * @package  gwangi
 * @author   Themosaurus
 * @since    1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The Gwangi Grimlock Hero integration class.
 */
class Gwangi_Grimlock_WooCommerce {
	/**
	 * Setup class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		require_once get_template_directory() . '/inc/grimlock-woocommerce/customizer/class-gwangi-grimlock-woocommerce-archive-product-customizer.php';
		add_action( 'after_switch_theme', array( $this, 'image_dimensions' ), 10, 1 );
	}

	/**
	 * Change catalog default image size on theme activation.
	 *
	 * @since 1.0.0
	 */
	public function image_dimensions() {
		global $pagenow;
		if ( ! isset( $_GET['activated'] ) || 'themes.php' !== $pagenow ) {
			return;
		}

		$catalog = array(
			'width'  => '400',
			'height' => '400',
			'crop'   => 1,
		);

		$single = array(
			'width'  => '650',
			'height' => '650',
			'crop'   => 1,
		);

		update_option( 'shop_catalog_image_size', $catalog );
		update_option( 'shop_single_image_size', $single );
	}
}

return new Gwangi_Grimlock_WooCommerce();
