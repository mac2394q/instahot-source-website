<?php
/**
 * GamiPress Points Transfer Shortcode
 *
 * @package     GamiPress\Transfers\Shortcodes\Shortcode\GamiPress_Points_Transfer
 * @since       1.0.0
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Register the [gamipress_points_transfer] shortcode.
 *
 * @since 1.0.0
 */
function gamipress_transfers_register_points_transfer_shortcode() {

    // Setup the points types
    $points_types_options = array(
        '' => __( 'Default Points', 'gamipress-transfers' )
    );

    foreach( gamipress_get_points_types() as $slug => $data ) {
        $points_types_options[$slug] = $data['plural_name'];
    }

    gamipress_register_shortcode( 'gamipress_points_transfer', array(
        'name'              => __( 'Points Transfer', 'gamipress-transfers' ),
        'description'       => __( 'Render a points transfer form.', 'gamipress-transfers' ),
        'output_callback'   => 'gamipress_transfers_points_transfer_shortcode',
        'icon'              => 'transfer',
        'tabs' => array(
            'form' => array(
                'icon' => 'dashicons-feedback',
                'title' => __( 'Form', 'gamipress-transfers' ),
                'fields' => array(
                    'points_type',
                    'transfer_type',
                    'amount',
                    'options',
                    'allow_user_input',
                    'initial_amount',
                    'button_text',
                ),
            ),
            'recipient' => array(
                'icon' => 'dashicons-admin-users',
                'title' => __( 'Recipient', 'gamipress-transfers' ),
                'fields' => array(
                    'recipient_id',
                    'select_recipient',
                    'recipient_autocomplete',
                ),
            ),
        ),

        'fields'            => array(
            'points_type' => array(
                'name'        => __( 'Points Type', 'gamipress-transfers' ),
                'description' => __( 'The points type to transfer.', 'gamipress-transfers' ),
                'type' 	=> 'select',
                'options' 	=> $points_types_options,
                'default' => ''
            ),
            'transfer_type' => array(
                'name'        => __( 'Transfer Type', 'gamipress-transfers' ),
                'description' => __( 'The transfer type.', 'gamipress-transfers' ),
                'type' 	=> 'select',
                'options' => array(
                    'fixed'     => __( 'Fixed amount', 'gamipress-transfers' ),
                    'custom'    => __( 'Allow user inputs the amount', 'gamipress-transfers' ),
                    'options'   => __( 'Set of predefined options', 'gamipress-transfers' ),
                ),
                'default' => 'fixed'
            ),

            // Fixed

            'amount' => array(
                'name'        => __( 'Amount', 'gamipress-transfers' ),
                'description' => __( 'Amount user will transfer.', 'gamipress-transfers' ),
                'type' 	=> 'text',
                'default' => '100'
            ),

            // Options

            'options' => array(
                'name'        => __( 'Options', 'gamipress-transfers' ),
                'description' => __( 'Options available to transfer.', 'gamipress-transfers' ),
                'type' 	=> 'text',
                'attributes' => array(
                    'type' => 'number'
                ),
                'repeatable' => true,
                'text'     => array(
                    'add_row_text' => __( 'Add Option', 'gamipress-transfers' ),
                ),
            ),
            'allow_user_input' => array(
                'name'        => __( 'Allow User Input', 'gamipress-transfers' ),
                'description' => __( 'Allow user input a custom amount to transfer.', 'gamipress-transfers' ),
                'type' 	      => 'checkbox',
                'classes' => 'gamipress-switch',
                'default' => 'yes'
            ),

            // Used for custom and allow user input options

            'initial_amount' => array(
                'name'        => __( 'Initial amount', 'gamipress-transfers' ),
                'description' => __( 'Set the initial amount.', 'gamipress-transfers' ),
                'type' 	      => 'text',
                'default' => '100'
            ),

            // Transfer button text

            'button_text' => array(
                'name'        => __( 'Button Text', 'gamipress-transfers' ),
                'description' => __( 'Transfer button text.', 'gamipress-transfers' ),
                'type' 	=> 'text',
                'default' => __( 'Transfer', 'gamipress-transfers' )
            ),

            // Recipient

            'recipient_id' => array(
                'name'        => __( 'Recipient', 'gamipress-transfers' ),
                'description' => __( 'User that will receive the transfer.', 'gamipress-transfers' ),
                'type'        => 'select',
                'classes' 	  => 'gamipress-user-selector',
                'default'     => '',
                'options_cb'  => 'gamipress_options_cb_users'
            ),
            'select_recipient' => array(
                'name'        => __( 'Allow Select Recipient', 'gamipress-transfers' ),
                'description' => __( 'Allow user to select a specific transfer recipient. If recipient is set it will be used as initial recipient selected.', 'gamipress-transfers' ),
                'type' 	      => 'checkbox',
                'classes' => 'gamipress-switch',
            ),
            'recipient_autocomplete' => array(
                'name'        => __( 'Enable Select Recipient Auto-complete', 'gamipress-transfers' ),
                'description' => __( 'Enabling this functionality will add user suggestions to the recipient field.', 'gamipress-transfers' )
                . __( 'If this option is not enabled, user will be required to provide exactly the recipient user name or email.', 'gamipress-transfers' ),
                'type' 	      => 'checkbox',
                'classes' => 'gamipress-switch',
            ),

        ),
    ) );

}
add_action( 'init', 'gamipress_transfers_register_points_transfer_shortcode' );

/**
 * Transfer Shortcode.
 *
 * @since  1.0.0
 *
 * @param  array $atts Shortcode attributes.
 * @return string 	   HTML markup.
 */
function gamipress_transfers_points_transfer_shortcode( $atts = array() ) {

    global $gamipress_transfers_template_args;

    // Get the shortcode attributes
    $atts = shortcode_atts( array(

        'points_type'                   => '',
        'transfer_type' 	            => 'fixed',
        'amount' 		                => '100',
        'initial_amount'                => '100',
        'options' 		                => '',
        'allow_user_input'              => 'yes',
        'button_text' 		            => __( 'Transfer', 'gamipress-transfers' ),

        // Recipient
        'select_recipient'              => 'no',
        'recipient_autocomplete'        => 'no',
        'recipient_id'                  => '0'

    ), $atts, 'gamipress_points_transfer' );

    // Ensure values as int
    $atts['amount'] = absint( $atts['amount'] );
    $atts['initial_amount'] = absint( $atts['initial_amount'] );
    $atts['recipient_id'] = absint( $atts['recipient_id'] );

    // Setup user id
    $user_id = get_current_user_id();

    if( $user_id === 0 ) {
        return sprintf( __( 'You need to <a href="%s">log in</a> to make a transfer.', 'gamipress-transfers' ), wp_login_url( get_permalink() ) );
    }

    // If user is not able to select a recipient, need to check if recipient ID is correctly
    if( $atts['select_recipient'] === 'no' ) {

        $recipient = get_userdata( $atts['recipient_id'] );

        if( ! $recipient ) {
            return gamipress_transfers_notify_form_error( __( 'Invalid recipient ID.', 'gamipress-transfers' ) );
        } else if( $atts['recipient_id'] === $user_id ) {
            // user can't transfer to himself
            return '';
        }

    }

    $gamipress_transfers_template_args = $atts;

    // Setup points types
    $points_types = gamipress_get_points_types();

    if( ! isset( $points_types[$atts['points_type']] ) ) {
        return gamipress_transfers_notify_form_error( __( 'The points type provided is not a registered points type.', 'gamipress-transfers' ) );
    }

    // Setup points type object
    $points_type = $points_types[$atts['points_type']];

    $gamipress_transfers_template_args['points_type_object'] = $points_type;

    $amount = 0;

    // Setup options
    if( $atts['transfer_type'] === 'fixed' ) {

        $amount = $atts['amount'];

    } else if( $atts['transfer_type'] === 'custom' ) {

        $amount = $atts['initial_amount'];

    } else if( $atts['transfer_type'] === 'options' ) {

        // Explode the comma separated options
        $options = explode( ',', $atts['options'] );

        if( empty( $options ) ) {
            return gamipress_transfers_notify_form_error( __( 'There is no transfer options.', 'gamipress-transfers' ) );
        }

        // Ensure options amounts as an int
        foreach( $options as $index => $option ) {

            // Initialize form total based on first option
            if( $index === 0 ) {
                $amount = absint( $option );
            }

            $options[$index] = absint( $option );

        }

        $gamipress_transfers_template_args['options'] = $options;
    }

    // Setup the form vars
    $gamipress_transfers_template_args['transfer_key'] = gamipress_transfers_generate_transfer_key();
    $gamipress_transfers_template_args['form_id'] = 'gamipress-transfers-transfer-form-' . esc_attr( $gamipress_transfers_template_args['transfer_key'] );

    // Setup total amount to transfer
    $gamipress_transfers_template_args['amount'] = $amount;

    // Enqueue assets
    gamipress_transfers_enqueue_scripts();

    ob_start();
    gamipress_get_template_part( 'points-transfer-form', $atts['points_type'] );
    $output = ob_get_clean();

    // Return our rendered achievement
    return $output;
}
