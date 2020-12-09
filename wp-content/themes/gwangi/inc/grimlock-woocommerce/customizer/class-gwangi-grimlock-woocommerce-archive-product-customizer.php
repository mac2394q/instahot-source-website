<?php
/**
 * Gwangi_Grimlock_WooCommerce_Archive_Product_Customizer Class
 *
 * @author   Themosaurus
 * @since    1.0.0
 * @package grimlock
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The post archive page class for the Customizer.
 */
class Gwangi_Grimlock_WooCommerce_Archive_Product_Customizer {
	/**
	 * Setup class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_filter( 'grimlock_woocommerce_archive_product_customizer_defaults', array( $this, 'add_defaults' ), 10, 1 );
	}

	/**
	 * Add default values and control settings for the Customizer.
	 *
	 * @since 1.0.0
	 *
	 * @param array $defaults The array of default values for the Customizer controls.
	 *
	 * @return array          The updated array of default values for the Customizer controls.
	 */
	public function add_defaults( $defaults ) {
		$defaults['archive_product_custom_header_displayed']        = true;
		$defaults['archive_product_custom_header_background_image'] = get_stylesheet_directory_uri() . '/assets/images/pages/header-default-products.jpg';
		$defaults['archive_product_custom_header_padding_y']        = GWANGI_HEADER_PADDING_Y;
		$defaults['archive_product_content_padding_y']              = GWANGI_CONTENT_PADDING_Y;
		return $defaults;
	}
}

return new Gwangi_Grimlock_WooCommerce_Archive_Product_Customizer();
