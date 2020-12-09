<?php
/**
 * Ajax Functions
 *
 * @package     GamiPress\Notifications\Ajax_Functions
 * @since       1.0.0
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Ajax function to check live user notifications
 *
 * @since   1.0.0
 * @updated 1.1.3 Functionality moved to gamipress_notifications_get_user_notifications()
 */
function gamipress_notifications_ajax_get_notices() {

    // Bail if live checks has been disabled
    if( (bool) gamipress_notifications_get_option( 'disable_live_checks', false ) ) {
        wp_send_json_success( array( 'notices' => array(), 'last_check' => 0 ) );
    }

    ignore_user_abort( false );

    define( 'GAMIPRESS_NOTIFICATIONS_AJAX', true );

    // Setup vars
    $user_id = get_current_user_id();
    $user_points = ( isset( $_REQUEST['user_points'] ) && (bool) $_REQUEST['user_points'] );

    // Get user notices
    $response = gamipress_notifications_get_user_notifications( $user_id, $user_points );

    // Return user notices in format: array( 'notices' => array(), 'last_check' => 0 )
    wp_send_json_success( $response );

}
add_action( 'wp_ajax_gamipress_notifications_get_notices', 'gamipress_notifications_ajax_get_notices' );
add_action( 'wp_ajax_nopriv_gamipress_notifications_get_notices', 'gamipress_notifications_ajax_get_notices' );

/**
 * Page load version of ajax get notices
 *
 * @since   1.1.3
 */
function gamipress_notifications_page_load_get_notices() {

    // Bail if live checks are enabled
    if( ! (bool) gamipress_notifications_get_option( 'disable_live_checks', false ) ) {
        return;
    }

    // Just run on frontend
    if( is_admin() ) {
        return;
    }

    $user_id = get_current_user_id();

    // Get user notices
    $response = gamipress_notifications_get_user_notifications( $user_id ); ?>

    <div class="gamipress-notifications-user-notices" style="display: none;">

        <?php if( is_array( $response['notices'] ) ) :

            foreach( $response['notices'] as $notice ) :
                echo $notice;
            endforeach;

            if( $response['last_check'] !== 0 ) {
                // Set the last check
                gamipress_notifications_set_user_last_check( $user_id, $response['last_check'] );
            }

        endif; ?>

    </div>

    <?php

}
add_action( 'wp_footer', 'gamipress_notifications_page_load_get_notices' );

/**
 * Ajax function to notify to the server last time user has check the notifications
 *
 * @since   1.0.0
 */
function gamipress_notifications_ajax_last_check() {

    // Bail if live checks has been disabled
    if( (bool) gamipress_notifications_get_option( 'disable_live_checks', false ) ) {
        wp_send_json_success();
    }

    ignore_user_abort( false );

    $user_id = get_current_user_id();

    // Just continue if user is logged in
    if( $user_id === 0 ) {
        wp_send_json_success();
    }

    $last_check = isset( $_REQUEST['last_check'] ) ? absint( $_REQUEST['last_check'] ) : 0;

    if( $last_check !== 0 ) {
        gamipress_notifications_set_user_last_check( $user_id, $last_check );
    }

    /**
     * Action to meet when last time user has being updated through ajax
     *
     * @since 1.2.1
     *
     * @param int       $user_id    The given user's ID
     * @param int       $last_check Last check timestamp
     */
    do_action( 'gamipress_notifications_ajax_last_check_updated', $user_id, $last_check );

    wp_send_json_success();
}
add_action( 'wp_ajax_gamipress_notifications_last_check', 'gamipress_notifications_ajax_last_check' );
add_action( 'wp_ajax_nopriv_gamipress_notifications_last_check', 'gamipress_notifications_ajax_last_check' );