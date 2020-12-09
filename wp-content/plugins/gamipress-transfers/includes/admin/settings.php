<?php
/**
 * Settings
 *
 * @package     GamiPress\Transfers\Admin\Settings
 * @since       1.0.0
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Shortcut function to get plugin options
 *
 * @since  1.0.0
 *
 * @param string    $option_name
 * @param bool      $default
 *
 * @return mixed
 */
function gamipress_transfers_get_option( $option_name, $default = false ) {

    $prefix = 'gamipress_transfers_';

    return gamipress_get_option( $prefix . $option_name, $default );
}

/**
 * GamiPress Transfers Settings meta boxes
 *
 * @since  1.0.0
 *
 * @param array $meta_boxes
 *
 * @return array
 */
function gamipress_transfers_settings_meta_boxes( $meta_boxes ) {

    $prefix = 'gamipress_transfers_';

    // Page Options
    $pages = get_posts( array(
        'post_type' => 'page',
        'numberposts' => -1
    ) );

    $pages_options = array();

    foreach( $pages as $page ) {
        $pages_options[$page->ID] = $page->post_title;
    }

    $meta_boxes['gamipress-transfers-settings'] = array(
        'title' => gamipress_dashicon( 'transfer' ) . __( 'Transfers', 'gamipress-transfers' ),
        'fields' => apply_filters( 'gamipress_transfers_settings_fields', array(
            $prefix . 'transfer_history_page' => array(
                'name' => __( 'Transfer History Page', 'gamipress-transfers' ),
                'desc' => __( 'Page to show a complete transfer history for the current user, including each transfer details. The [gamipress_transfer_history] shortcode should be on this page.', 'gamipress-transfers' ),
                'type' => 'select',
                'options' => $pages_options,
            ),
            $prefix . 'pending_transfers' => array(
                'name' => __( 'Keep Transfers Pending', 'gamipress-transfers' ),
                'desc' => __( 'Check this option to keep all new transfer as pending. The intervention of an administrator will be necessary in order to mark them as complete.', 'gamipress-transfers' ),
                'type' => 'checkbox',
                'classes' => 'gamipress-switch'
            ),
        ) ),
    );

    return $meta_boxes;

}
add_filter( 'gamipress_settings_addons_meta_boxes', 'gamipress_transfers_settings_meta_boxes' );