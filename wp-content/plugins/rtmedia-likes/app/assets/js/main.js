/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*
For user who like the comment
*/
var _like_ajax = false;
jQuery( document ).ready( function( $ ) {

    rtMediaHook.register( 'rtmedia_js_popup_after_content_added', function( args ) {
        jQuery( '.rtmedia-like-counter-wrap' ).attr( 'title', rtmedia_like_main_js.rtmedia_media_who_liked );
        return 1;
    } );

    jQuery( 'body' ).on( 'click', '.rtmedia-like-counter-wrap', function( e ) {
        e.preventDefault();

        jQuery( '.rtm-media-likes-wrapper' ).show();
        jQuery( '.rtm-media-likes .loading-gif' ).show();

        $media_id = jQuery( '.current-media-item' ).val();
        jQuery( '.media-like-list' ).remove();

        jQuery.ajax( {
            type: 'POST',
            url: rtmedia_ajax_url,
            data: {
                action: 'rtm_media_likes',
                media_id: $media_id
            },
            success: function( response_data ) {
                jQuery( '.rtm-media-likes .loading-gif' ).hide();

                if ( response_data ) {
                    $likes_list = '<ul class="media-like-list">';
                    $likes_list += response_data;
                    $likes_list += '</ul>';
                } else {
                    $likes_list = '<p>' + rtmedia_like_main_js.rtmedia_media_no_likes + '</p>';
                }

                jQuery( '.rtm-media-likes' ).append( $likes_list );
            }
        } );
    } );

    jQuery( document ).keyup( function( e ) {
        if ( e.keyCode == 27 || e.keyCode == 37 || e.keyCode == 39 ) {
            jQuery( '.rtm-media-likes-wrapper' ).hide();
            jQuery( '.media-like-list' ).remove();
        }   // Esc
    } );

    jQuery( 'body' ).on( 'click', '.rtm-media-likes .close', function() {
        jQuery( '.rtm-media-likes-wrapper' ).hide();
        jQuery( '.media-like-list' ).remove();
    } );

    jQuery( document ).mouseup( function( e ) {
        var container = jQuery( '.rtm-media-likes' );

        if ( ! container.is( e.target ) && container.has( e.target ).length === 0 ) {
            container.parent().hide();
        }
    } );

    if ( jQuery( '.rtmedia-like-counter-wrap' ).length > 0 ) {
        jQuery( '.rtmedia-like-counter-wrap' ).attr( 'title', rtmedia_like_main_js.rtmedia_media_who_liked );
    }

    /* To comment like or unlike */
    rtmedia_comment_like();

    /* List of the user who like the comment */
    rtmedia_comment_who_like();

} );

/*
* Related to like an dislike of the comment
 */
function rtmedia_comment_like() {
    jQuery( 'body' ).on( 'click', '.rtmedia-comment-like-main a.rtmedia-comment-like-click', function( e ) {
        e.preventDefault();
        if ( _like_ajax ) {
            return false;
        }

        _like_ajax = true;
        var this_html = this;

        $comment_id = jQuery( this ).data( 'comment_id' );
        $rtmedia_nonce = jQuery( this_html ).closest( '.rtmedia-comment-like-main' ).find( '.rtmedia-nonce' ).val();
        jQuery.ajax( {
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'rtmedia_comment_like',
                comment_id: $comment_id,
                rtmedia_nonce: $rtmedia_nonce
            },
            success: function( response_data ) {
                _like_ajax = false;
                var response = jQuery.parseJSON( response_data );
                if ( response.status == true ) {
                    jQuery( this_html ).closest( '.rtmedia-comment-like-main' ).replaceWith( response.html );
                }
            },
            error: function( response_error ) {
                _like_ajax = false;
            }
        } );
    } );
}

/*
* Related to who like the comment user list
 */
function rtmedia_comment_who_like() {
    if ( jQuery( '.rtmedia-comment-like-counter-wrap' ).length > 0 ) {
        jQuery( '.rtmedia-comment-like-counter-wrap' ).attr( 'title', rtmedia_like_main_js.rtmedia_media_who_liked );
    }

    jQuery( 'body' ).on( 'click', '.rtmedia-comment-like-main .rtmedia-comment-like-body', function( e ) {
        e.preventDefault();
        if ( _like_ajax ) {
            return false;
        }

        jQuery( '.media-like-list' ).remove();
        jQuery( '.rtm-media-likes-wrapper' ).show();
        jQuery( '.rtm-media-likes .loading-gif' ).show();

        _like_ajax = true;
        var this_html = this;

        $comment_id = jQuery( this_html ).closest( 'p' ).find( '.comment_id' ).val();
        $rtmedia_nonce = jQuery( this_html ).closest( '.rtmedia-comment-like-main' ).find( '.rtmedia-nonce' ).val();
        if ( $comment_id > 0 ) {
            jQuery.ajax( {
                type: 'POST',
                url: ajaxurl,
                data: {
                    action: 'rtmedia_comment_who_like',
                    comment_id: $comment_id,
                    rtmedia_nonce: $rtmedia_nonce
                },
                success: function( response_data ) {
                    _like_ajax = false;
                    var response = jQuery.parseJSON( response_data );
                    jQuery( '.rtm-media-likes .loading-gif' ).hide();

                    if ( response.status == true ) {
                        $list_html = response.html;
                    }else {
                        $list_html = '<li>' + rtmedia_like_main_js.rtmedia_media_no_likes + '</li>';
                    }

                    $likes_list = '<ul class="media-like-list">';
                    $likes_list += $list_html;
                    $likes_list += '</ul>';

                    jQuery( '.rtm-media-likes' ).append( $likes_list );
                },
                error: function( response_error ) {
                    _like_ajax = false;
                }
            } );
        }
    } );
}
