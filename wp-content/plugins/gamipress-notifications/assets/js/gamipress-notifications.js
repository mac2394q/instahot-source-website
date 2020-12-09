(function( $, document ) {

    // Add our notify style
    $.gamipress_notify.addStyle('gamipress', {
        html: '<div>' +
            '<div class="gamipress-notification-close"></div>' +
            '<div class="gamipress-notification-content" data-notify-html="content"></div>' +
        '</div>'
    });

    // Programmatically trigger propagating hide event
    $(document).on('click', '.gamipress-notification .gamipress-notification-close', function() {
        $(this).trigger('notify-hide');
    });

    /**
     * Shows a notification
     *
     * @since 1.1.3
     *
     * @param {string} content HTML content to show, check templates provided on this add-on to see examples of HTML passed
     */
    function gamipress_notifications_notify( content ) {

        var position = gamipress_notifications.position;

        // Backward compatibility, notify js don't works if middle position is set first
        if( position === 'middle left' )
            position = 'left middle';
        else if( position === 'middle right' )
            position = 'right middle';

        var extra_classes = $(content).attr('class');

        // Setup our notification object
        $.gamipress_notify( {
            content: content,
        }, {
            style: 'gamipress',
            className: 'notification gamipress-notification ' + extra_classes,

            position: position,

            clickToHide: gamipress_notifications.click_to_hide,
            autoHide: ( gamipress_notifications.auto_hide && parseInt( gamipress_notifications.auto_hide_delay ) > 0 ),
            autoHideDelay: gamipress_notifications.auto_hide_delay,

            showAnimation: 'slideDown',
            showDuration: 400,

            hideAnimation: 'slideUp',
            hideDuration: 200,
            onOpen: function( notification ) {
                var show_sound = notification.find('#gamipress-notification-show-sound');

                if( show_sound.length )
                    gamipress_notifications_play_sound( show_sound.data('src') );
            },
            onClose: function( notification ) {
                var hide_sound = notification.find('#gamipress-notification-hide-sound');

                if( hide_sound.length )
                    gamipress_notifications_play_sound( hide_sound.data('src') );
            }
        });

    }

    /**
     * Helper function to play an audio
     *
     * @since 1.1.9
     *
     * @param {string} src HTML content to show, check templates provided on this add-on to see examples of HTML passed
     */
    function gamipress_notifications_play_sound ( src ) {

        var filename = src.split(/[\\\/]/).pop();

        // Bail if not a correct filename
        if( filename === undefined ) return;

        var src_parts = src.match(/\.([^.]+)$/);

        // Bail if can't determine the file extension
        if( src_parts === null ) return;

        var ext = src_parts[1];
        var id = filename.replace( '.', '_' );

        // Create and setup the source element
        var source = document.createElement('source');
        source.src = src;
        source.type = 'audio/' + ext; // audio/{file extension}

        // Create the audio element
        var audio = document.createElement('audio');
        audio.id = id;

        // Append the source element
        audio.appendChild( source );

        // Append audio element to the body
        document.body.appendChild( audio );

        audio.currentTime = 0;
        audio.volume = 1;

        audio.load();

        setTimeout(function() {
            audio.play().catch(() => { audio.play(); });
        }, 0);
    }

    // Setup vars
    var notifications_request;
    var notifications_last_check_request;
    var last_check = 0;

    // Check for new notifications
    function gamipress_notifications_check_notices() {

        if( gamipress_notifications.disable_live_checks ) {
            return;
        }

        if( notifications_request === undefined ) {

            var show_user_points = $('.gamipress-user-points').length;

            notifications_request = $.ajax({
                url: gamipress_notifications.ajaxurl,
                data: {
                    action: 'gamipress_notifications_get_notices',
                    user_points: show_user_points
                },
                success: function( response ) {

                    var i;

                    // Get the configured notifications check delay
                    var delay = parseInt( gamipress_notifications.delay );

                    if( response.data.notices.length ) {

                        // Loop all notices to show them
                        for( i = 0; i < response.data.notices.length; i++ ) {

                            var content = response.data.notices[i];

                            // Show the notification
                            gamipress_notifications_notify( content );

                        }

                        // Last check setup
                        if( response.data.last_check !== undefined ) {

                            last_check = response.data.last_check;

                            // Launch last check before check notices again
                            setTimeout( gamipress_notifications_update_last_check, delay/2 );

                        }

                    } else {

                        // No more notices so check them again before a delay
                        setTimeout( gamipress_notifications_check_notices, delay );
                    }

                    if( show_user_points && response.data.user_points.length ) {

                        // Loop all points info
                        for( i = 0; i < response.data.user_points.length; i++ ) {

                            var user_points = response.data.user_points[i];

                            // Update the HTML with old user points with new balance
                            $('.gamipress-user-points.gamipress-is-current-user .gamipress-user-points-' + user_points.points_type + ' .gamipress-user-points-count').text( user_points.points );

                        }
                    }

                    // Restore request var to allow request again
                    notifications_request = undefined;

                }
            });

        }

    }

    function gamipress_notifications_update_last_check() {

        if( gamipress_notifications.disable_live_checks ) {
            return;
        }

        if( notifications_last_check_request === undefined ) {

            notifications_last_check_request = $.ajax({
                url: gamipress_notifications.ajaxurl,
                data: {
                    action: 'gamipress_notifications_last_check',
                    last_check: last_check
                },
                success: function( response ) {

                    // Get the configured notifications check delay
                    var delay = parseInt( gamipress_notifications.delay );

                    // Check new notifications again before a delay
                    setTimeout( gamipress_notifications_check_notices, delay );

                    // Restore request var to allow request again
                    notifications_last_check_request = undefined;
                }
            });

        }
    }

    // Initial check

    // Check in line notices
    $('.gamipress-notifications-user-notices > div').each(function() {

        // Bail show sound div
        if( $(this).attr('id') === 'gamipress-notification-show-sound' )
            return true;

        // Bail hide sound div
        if( $(this).attr('id') === 'gamipress-notification-hide-sound' )
            return true;

        var content = $(this)[0].outerHTML;
        var show_selector = '#gamipress-notification-show-sound';
        var hide_selector = '#gamipress-notification-hide-sound';

        // Append show sound html
        if( $(this).next(show_selector).length )
            content += $(this).next(show_selector)[0].outerHTML;

        // Append hide sound html
        if( $(this).next(hide_selector).length )
            content += $(this).next(hide_selector)[0].outerHTML;

        // If show sound and hide sound then append it
        if( $(this).next(show_selector).next(hide_selector).length )
            content += $(this).next(show_selector).next(hide_selector)[0].outerHTML;

        gamipress_notifications_notify( content );
    });

    // Check server notices (if enabled)
    if( ! gamipress_notifications.disable_live_checks ) {
        gamipress_notifications_check_notices();
    }

})( jQuery, document );