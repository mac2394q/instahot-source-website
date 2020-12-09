<?php
/**
 * Points Transfer Form template
 *
 * This template can be overridden by copying it to yourtheme/gamipress/transfers/points-transfer-form.php
 * To override a specific points type just copy it as yourtheme/gamipress/transfers/points-transfer-form-{points-type}.php
 */
global $gamipress_transfers_template_args;

// Shorthand
$a = $gamipress_transfers_template_args;

// Setup vars
$user_id = get_current_user_id();
$points_type = $a['points_type_object'];
$transfer_type = $a['transfer_type'];
$recipient = get_userdata( $a['recipient_id'] );

// Get user points balance
$user_points = gamipress_get_user_points( $user_id, $a['points_type'] );
$new_balance = ( $user_points - $a['amount'] ); ?>

<fieldset id="gamipress-transfers-form-wrapper" class="gamipress-transfers-form-wrapper gamipress-transfers-points-transfer-form-wrapper">

    <form id="<?php echo $a['form_id']; ?>" class="gamipress-transfers-form gamipress-transfers-points-transfer-form" action="" method="POST">

        <?php
        /**
         * Before render points transfer form
         *
         * @since 1.0.0
         *
         * @param integer     $user_id          User ID
         * @param integer     $recipient_id     Recipient ID (if is set)
         * @param integer     $amount           Amount to be transfer
         * @param integer     $user_points      User earned points
         * @param string      $points_type      Points type
         * @param array       $template_args    Template received arguments
         */
        do_action( 'gamipress_transfers_before_points_transfer_form', $user_id, $a['recipient_id'], $a['amount'], $user_points, $a['points_type'], $a ); ?>

        <?php // Setup the recipient form ?>
        <?php gamipress_get_template_part( 'recipient-form', $a['points_type'] ); ?>

        <?php // Setup the form based on form type ?>

        <?php if( $transfer_type === 'fixed' ) : ?>

            <fieldset class="gamipress-transfers-points-transfer-form-fixed">

                <legend><?php _e( 'Transfer details', 'gamipress-transfers' ); ?></legend>

                <p class="gamipress-transfers-points-transfer-form-fixed-amount">
                    <?php echo sprintf( __( 'You will transfer %d %s', 'gamipress-transfers' ),
                        $a['amount'],
                        _n( $points_type['singular_name'], $points_type['plural_name'], $a['amount'] )
                    ); ?>
                </p>

            </fieldset>

        <?php elseif( $transfer_type === 'custom' ) : ?>

            <fieldset class="gamipress-transfers-points-transfer-form-custom">

                <legend><?php _e( 'Enter the amount you want to transfer', 'gamipress-transfers' ); ?></legend>

                <p class="gamipress-transfers-points-transfer-form-custom-input">

                    <label for="<?php echo $a['form_id']; ?>-custom-amount"><?php _e( 'Amount:', 'gamipress-transfers' ); ?></label>

                    <input
                        id="<?php echo $a['form_id']; ?>-custom-amount"
                        class="gamipress-transfers-points-transfer-form-custom-amount"
                        name="<?php echo $a['form_id']; ?>-amount"
                        type="text"
                        value="<?php echo $a['initial_amount']; ?>">

                    <span class="gamipress-transfers-points-transfer-form-custom-points-label"><?php echo $points_type['plural_name']; ?></span>
                </p>

            </fieldset>

        <?php elseif( $transfer_type === 'options' ) : ?>

            <fieldset class="gamipress-transfers-points-transfer-form-options">

                <legend><?php _e( 'Choose the amount you want to transfer', 'gamipress-transfers' ); ?></legend>

                <div class="gamipress-transfers-points-transfer-form-options-list">

                    <?php foreach( $a['options'] as $option_index => $option ) : ?>

                        <div id="gamipress-transfers-points-transfer-form-option-list-<?php echo $option_index; ?>" class="gamipress-transfers-points-transfer-form-option">

                            <input
                                id="<?php echo $a['form_id']; ?>-option-<?php echo $option_index; ?>"
                                name="<?php echo $a['form_id']; ?>-option"
                                type="radio"
                                value="<?php echo $option; ?>"
                                <?php if( $option_index === 0 ) : ?>checked="checked"<?php endif; ?>>
                            <label for="<?php echo $a['form_id']; ?>-option-<?php echo $option_index; ?>"><?php echo $option . ' ' . _n( $points_type['singular_name'], $points_type['plural_name'], $option ); ?></label>

                        </div>

                    <?php endforeach; ?>

                    <?php if( $a['allow_user_input'] === 'yes' ) : ?>

                        <div class="gamipress-transfers-points-transfer-form-option gamipress-transfers-points-transfer-form-option-list-custom">

                            <input id="<?php echo $a['form_id']; ?>-option-custom" name="<?php echo $a['form_id']; ?>-option" type="radio" value="custom">
                            <label for="<?php echo $a['form_id']; ?>-option-custom"><?php _e( 'Other', 'gamipress-transfers' ); ?></label>

                        </div>

                    <?php endif; ?>

                </div>

                <?php if( $a['allow_user_input'] === 'yes' ) : ?>

                    <p class="gamipress-transfers-points-transfer-form-options-custom-amount" style="display: none;">

                        <label for="<?php echo $a['form_id']; ?>-options-custom-amount-input"><?php _e( 'Enter amount:', 'gamipress-transfers' ); ?></label>

                        <input
                            id="<?php echo $a['form_id']; ?>-options-custom-amount-input"
                            class="gamipress-transfers-points-transfer-form-options-custom-amount-input"
                            name="<?php echo $a['form_id']; ?>-custom"
                            type="text"
                            value="<?php echo $a['initial_amount']; ?>">

                        <span class="gamipress-transfers-points-transfer-form-options-points-label"><?php echo $points_type['plural_name']; ?></span>

                    </p>

                <?php endif; ?>

            </fieldset>

        <?php endif; ?>

        <?php // Transfer total ?>

        <p class="gamipress-transfers-points-transfer-form-total">
            <span class="gamipress-transfers-points-transfer-form-total-label"><?php _e( 'Amount to transfer:', 'gamipress-transfers' ); ?></span>
            <span class="gamipress-transfers-points-transfer-form-total-amount"><?php echo $a['amount']; ?></span>
            <span class="gamipress-transfers-points-transfer-form-total-points-label"><?php echo $points_type['plural_name']; ?></span>
        </p>
        <p class="gamipress-transfers-points-transfer-form-current-balance">
            <span class="gamipress-transfers-points-transfer-form-current-balance-label"><?php _e( 'Current balance:', 'gamipress-transfers' ); ?></span>
            <span class="gamipress-transfers-points-transfer-form-current-balance-amount"><?php echo $user_points; ?></span>
            <span class="gamipress-transfers-points-transfer-form-current-balance-points-label"><?php echo $points_type['plural_name']; ?></span>
        </p>
        <p class="gamipress-transfers-points-transfer-form-new-balance">
            <span class="gamipress-transfers-points-transfer-form-new-balance-label"><?php _e( 'New balance after transfer:', 'gamipress-transfers' ); ?></span>
            <span class="gamipress-transfers-points-transfer-form-new-balance-amount gamipress-transfers-<?php echo ( $new_balance > 1 ? 'positive' : 'negative' ); ?>"><?php echo $new_balance; ?></span>
            <span class="gamipress-transfers-points-transfer-form-new-balance-points-label"><?php echo $points_type['plural_name']; ?></span>
        </p>

        <?php // Setup submit actions ?>

        <p class="gamipress-transfers-form-submit gamipress-transfers-points-transfer-form-submit">
            <?php // Loading spinner ?>
            <span class="gamipress-spinner" style="display: none;"></span>
            <input type="submit" id="<?php echo $a['form_id']; ?>-submit-button" class="gamipress-transfers-form-submit-button gamipress-transfers-points-transfer-form-submit-button" value="<?php echo $a['button_text']; ?>">
        </p>

        <?php // Output hidden fields ?>
        <input type="hidden" name="nonce" value="<?php echo wp_create_nonce( 'gamipress_transfers_transfer_form' ); ?>">
        <input type="hidden" name="referrer" value="<?php echo get_the_permalink(); ?>">
        <input type="hidden" name="transfer_type" value="points">
        <input type="hidden" name="transfer_key" value="<?php echo $a['transfer_key']; ?>">
        <input type="hidden" name="points_type" value="<?php echo $a['points_type']; ?>">
        <input type="hidden" name="recipient_id" value="<?php echo $a['recipient_id']; ?>">
        <input type="hidden" name="amount" value="<?php echo $a['amount']; ?>">
        <input type="hidden" name="user_points" value="<?php echo $user_points; ?>">

        <?php
        /**
         * After render transfer form
         *
         * @since 1.0.0
         *
         * @param integer     $user_id          User ID
         * @param integer     $recipient_id     Recipient ID (if is set)
         * @param integer     $amount           Amount to be transfer
         * @param integer     $user_points      User earned points
         * @param string      $points_type      Points type
         * @param array       $template_args    Template received arguments
         */
        do_action( 'gamipress_transfers_after_points_transfer_form', $user_id, $a['recipient_id'], $a['amount'], $user_points, $a['points_type'], $a ); ?>

    </form>

</fieldset>
