/**
 * Display People Who viewed This Media
 * 26-10-2016
 * By: Yahil
 */


jQuery( document ).ready( function( $ ) {
	jQuery( 'body' ).on( 'click', '#view_list', function (e) {

		/* Display ViewBox with loder */
		jQuery( '.rtm-media-view-wrapper' ).show();
        jQuery( '.rtm-media-view .loading-gif' ).show();

    	/* Ajax process to fatch user details who viewed media */
        var media_id = jQuery( '.current-media-view' ).val();

        jQuery.ajax( {
        	type: 'POST',
        	url: rtmedia_ajax_url,
        	data: {
        		action: 'rtmedia_view_list',
        		media_id: media_id
        	},
	        success: function( response_data ) {
	        	/* After Ajax process Display user details */
	        	jQuery( '.rtm-media-view .loading-gif' ).hide();
	        	if ( response_data ) {
	        		$view_list = '<ul class="media-view-list">';
	        		$view_list += response_data;
	        		$view_list += '</ul>';
	        	} else {
	        		$likes_list = "<p>" + rtmedia_view_main_js.rtmedia_media_no_view + "</p>";
	        	}
	        	jQuery( '.media-view-list' ).remove();
	        	jQuery( '.rtm-media-view' ).append( $view_list );
	        }
        } );
	} );

	/* Hide viewer details */
	jQuery( document ).keyup( function( e ) {
        if( e.keyCode == 27 || e.keyCode == 37 || e.keyCode == 39 ) {
            jQuery( '.rtm-media-view-wrapper' ).hide();
            jQuery( '.media-view-list' ).remove();
        }
    } );

	/* Hide viewer details */
	jQuery( 'body' ).on( 'click', '.rtm-media-view .close', function() {
        jQuery( '.rtm-media-view-wrapper' ).hide();
        jQuery( '.media-like-list' ).remove();
    } );

    /* Hide viewer details */
    jQuery( document ).mouseup( function( e ) {
        var container = jQuery( ".rtm-media-view" );
        if( !container.is( e.target ) && container.has( e.target ).length === 0 ) {
            container.parent().hide();
        }
    } );
} );
