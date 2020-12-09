(function( $ ) {

    // On change select recipient
    $( '#gamipress_points_transfer_select_recipient, #gamipress_achievement_transfer_select_recipient, #gamipress_rank_transfer_select_recipient').change(function() {
        // Get the recipient autocomplete checkbox wrap
        var target = $(this).closest('.cmb-row').next();

        if( $(this).prop('checked') ) {
            target.slideDown().removeClass('cmb2-tab-ignore');
        } else {
            target.slideUp().addClass('cmb2-tab-ignore');
        }
    });

    $( '#gamipress_points_transfer_select_recipient, #gamipress_achievement_transfer_select_recipient, #gamipress_rank_transfer_select_recipient').change();

    // On change points transfer type
    $( '#gamipress_points_transfer_transfer_type').change(function() {

        var type = $(this).val();

        $('.cmb2-id-gamipress-points-transfer-amount').hide().addClass('cmb2-tab-ignore');


        $('.cmb2-id-gamipress-points-transfer-options').hide().addClass('cmb2-tab-ignore');
        $('.cmb2-id-gamipress-points-transfer-allow-user-input').hide().addClass('cmb2-tab-ignore');

        $('.cmb2-id-gamipress-points-transfer-initial-amount').hide().addClass('cmb2-tab-ignore');

        if( type === 'fixed' ) {
            $('.cmb2-id-gamipress-points-transfer-amount').show().removeClass('cmb2-tab-ignore');
        } else if( type === 'custom' ) {
            $('.cmb2-id-gamipress-points-transfer-initial-amount').show().removeClass('cmb2-tab-ignore');
        } else if( type === 'options' ) {
            $('.cmb2-id-gamipress-points-transfer-options').show().removeClass('cmb2-tab-ignore');
            $('.cmb2-id-gamipress-points-transfer-allow-user-input').show().removeClass('cmb2-tab-ignore');

            $('#gamipress_points_transfer_allow_user_input').change();
        }
    });

    $( '#gamipress_points_transfer_transfer_type').change();

    // On change points allow user input
    $( '#gamipress_points_transfer_allow_user_input').change(function() {
        var target = $('.cmb2-id-gamipress-points-transfer-initial-amount');
        var type = $( '#gamipress_points_transfer_transfer_type').val();

        if( $(this).prop('checked') && type === 'options' ) {
            target.show().removeClass('cmb2-tab-ignore');
        } else if( type !== 'custom' ) {
            target.hide().addClass('cmb2-tab-ignore');
        }

    });

    $( '#gamipress_points_transfer_allow_user_input').change();

    // Parse recipients atts to all shortcodes
    $('body').on( 'gamipress_get_shortcode_attributes', '#gamipress_points_transfer_wrapper, #gamipress_achievement_transfer_wrapper, #gamipress_rank_transfer_wrapper', function( e, attrs, inputs ) {

        // If select recipient is set to no, then remove recipient autocomplete
        if( attrs.select_recipient === 'no' ) {
            delete attrs.recipient_autocomplete;
        }

    } );

    // Parse [gamipress_points_transfer] atts
    $('body').on( 'gamipress_get_shortcode_attributes', '#gamipress_points_transfer_wrapper', function( e, attrs, inputs ) {

        // Check points transfer_type value
        if( attrs.transfer_type === 'fixed' ) {
            delete attrs.initial_amount;
            delete attrs.options;
            delete attrs.allow_user_input;
        } else if( attrs.transfer_type === 'custom' ) {
            delete attrs.amount;
            delete attrs.options;
            delete attrs.allow_user_input;
        } else if( attrs.transfer_type === 'options' ) {

            delete attrs.amount;

            if( attrs.allow_user_input === 'no' ) {
                delete attrs.initial_amount;
            }
        }
    } );

    })( jQuery );