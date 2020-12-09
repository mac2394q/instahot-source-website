<?php
/**
 * Listeners
 *
 * @package     GamiPress\Transfers\Listeners
 * @since       1.0.0
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * New transfer listener
 *
 * @param object $transfer
 */
function gamipress_transfers_new_transfer( $transfer ) {

    // Bail if transfer is wrong
    if( ! is_object( $transfer ) ) {
        return;
    }

    $transfer_items = gamipress_transfers_get_transfer_items( $transfer->transfer_id );

    // Bail if anything has been transferred
    if( empty( $transfer_items ) ) {
        return;
    }

    $user_id = absint( $transfer->user_id );

    // Bail if guest
    if( $user_id === 0 ) {
        return;
    }

    // Trigger new transfer action
    do_action( 'gamipress_transfers_new_transfer', $transfer->transfer_id, $user_id, $transfer, $transfer_items );

    // Get our types
    $points_types_slugs = gamipress_get_points_types_slugs();
    $achievement_types_slugs = gamipress_get_achievement_types_slugs();
    $rank_types_slugs = gamipress_get_rank_types_slugs();

    foreach( $transfer_items as $transfer_item ) {

        // Skip if not item assigned
        if( absint( $transfer_item->post_id ) === 0 ) {
            continue;
        }

        $post_type = get_post_type( $transfer_item->post_id );

        // Skip if can not get the type of this item
        if( ! $post_type ) {
            continue;
        }

        if( $post_type === 'points-type' && in_array( $transfer_item->post_type, $points_types_slugs ) ) {
            // Is a points

            // Amount of points transferred
            $quantity = absint( $transfer_item->quantity );

            // Trigger new points transfer action
            do_action( 'gamipress_transfers_new_points_transfer', $transfer->transfer_id, $user_id, $transfer, absint( $transfer_item->post_id ), $quantity, $transfer_item, $transfer_item->transfer_item_id );

        } else if( in_array( $post_type, $achievement_types_slugs ) ) {
            // Is an achievement

            // Trigger new achievement transfer action
            do_action( 'gamipress_transfers_new_achievement_transfer', $transfer->transfer_id, $user_id, $transfer, absint( $transfer_item->post_id ), $transfer_item, $transfer_item->transfer_item_id );

            // Trigger new specific achievement transfer action
            do_action( 'gamipress_transfers_new_specific_achievement_transfer', $transfer->transfer_id, $user_id, $transfer, absint( $transfer_item->post_id ), $transfer_item, $transfer_item->transfer_item_id );

        } else if( in_array( $post_type, $rank_types_slugs ) ) {
            // Is a rank

            // Trigger new rank transfer action
            do_action( 'gamipress_transfers_new_rank_transfer', $transfer->transfer_id, $user_id, $transfer, absint( $transfer_item->post_id ), $transfer_item, $transfer_item->transfer_item_id );

            // Trigger new specific rank transfer action
            do_action( 'gamipress_transfers_new_specific_rank_transfer', $transfer->transfer_id, $user_id, $transfer, absint( $transfer_item->post_id ), $transfer_item, $transfer_item->transfer_item_id );

        }

    }
}
add_action( 'gamipress_transfers_complete_transfer', 'gamipress_transfers_new_transfer' );