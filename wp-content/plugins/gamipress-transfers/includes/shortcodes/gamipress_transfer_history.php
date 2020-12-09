<?php
/**
 * GamiPress Transfers Transfer History Shortcode
 *
 * Important: Transfer history is not registered at GamiPress Shortcodes because it not has parameters
 *
 * @package     GamiPress\Transfers\Shortcodes\Shortcode\GamiPress_Transfer_History
 * @since       1.0.0
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Transfer History Shortcode.
 *
 * @since  1.0.0
 *
 * @param  array $atts Shortcode attributes.
 * @return string 	   HTML markup.
 */
function gamipress_transfers_transfer_history_shortcode( $atts = array() ) {

    global $gamipress_transfers_template_args;

    // Setup user id
    $user_id = get_current_user_id();

    if( $user_id === 0 ) {
        return '';
    }

    $gamipress_transfers_template_args = array();

    // Check if single transfer details
    if( isset( $_GET['transfer_id'] ) ) {

        // Setup CT Table
        ct_setup_table( 'gamipress_transfers' );
        $transfer = ct_get_object( $_GET['transfer_id'] );

        // Check if transfer exists
        if( ! $transfer ) {
            return '';
        }

        // Check if user is assigned to this transfer
        if( absint( $transfer->user_id ) !== absint( $user_id ) ) {
            return '';
        }

        $gamipress_transfers_template_args['transfer_id'] = $_GET['transfer_id'];

        // Enqueue assets
        gamipress_transfers_enqueue_scripts();

        ob_start();
        gamipress_get_template_part( 'transfer-details' );
        $output = ob_get_clean();

        // Return our rendered achievement
        return $output;

    } else {

        // Enqueue assets
        gamipress_transfers_enqueue_scripts();

        ob_start();
        gamipress_get_template_part( 'transfer-history' );
        $output = ob_get_clean();

        // Return our rendered achievement
        return $output;
    }

}
add_shortcode( 'gamipress_transfer_history', 'gamipress_transfers_transfer_history_shortcode' );
