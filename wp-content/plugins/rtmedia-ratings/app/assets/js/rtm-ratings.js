function rtm_media_rate( value, args ) {
	if ( rt_user_logged_in == "1" ) {
		var url, data;
		if( typeof jQuery( "#rtm-media-rate-form" ).attr( "action" ) == 'undefined' ){
			url = rtmedia_ajax_url;
			data = {
				value: value,
				action: 'rtm_album_rating',
				media_id: args.media_id,
				nonce: jQuery( "#" + args.el_id ).data('nonce')
			}
		} else {
			url = jQuery( "#rtm-media-rate-form" ).attr( "action" );
			data = {
				value: value,
				nonce: jQuery( "#" + args.el_id ).data('nonce')
			};
		}
		var loading_img = "<img class='rtm-rate-loading' src='" + rMedia_loading_file + "' />";
		//  jQuery("#rtmedia_pro_rate_media").parent().append(loading_img);
		rating_content_el = jQuery( '#' + args.el_id ).parent('.rtm-media-rate-star-wrapper').siblings('.rtmedia-pro-average-rating');
		rating_content_html = rating_content_el.html();
		rating_content_el.html( loading_img );
		jQuery.post( url, data, function ( data ) {
			data = JSON.parse( data );
			rating_content_el.html( rating_content_html );
			var el_rating_wrapper = jQuery( "#" + args.el_id ).parent().siblings('.rtmedia-pro-average-rating');
			el_rating_wrapper.find('.rtmedia_pro_media_average_rating').text( data['average'].toFixed( 0 ) );
			el_rating_wrapper.find('.rtmedia_pro_media_user_rating').text( value );

			if( data.curr_value == '0' ){
				jQuery( '#rtm-undo-rating-' + data.media_id).css( 'display', 'none' );
			} else {
				jQuery( '#rtm-undo-rating-' + data.media_id).css( 'display', 'inline-block' );
			}
		});
	}

}

function rtmedia_init_rating() {
	if ( jQuery( ".rtmedia_pro_rate_media" ).length ) {
		jQuery( ".rtmedia_pro_rate_media").each( function( i ){
			if( jQuery(this).siblings('.webwidget_rating_simple').length == 0 ){
				extra_args = {
					media_id: jQuery(this).data('media-id'),
					el_id: jQuery(this).attr('id')
				};
				jQuery( this ).webwidget_rating_simple( {
					rating_star_length: '5',
					rating_initial_value: jQuery( this ).val(),
					rating_function_name: 'rtm_media_rate', //this is function name for click,
					rating_extra_args: extra_args
				} );
			}
		} );
	}
}

jQuery( document ).on( 'click', '.rtm-undo-rating', function(){
	var args = {
		media_id: jQuery( this ).data( 'media-id' ),
		el_id: jQuery( this ).data( 'el-id' )
	};
	rtm_media_rate( 0, args );

	if( '' !== args.el_id ) {
		var element = jQuery( '#' + args.el_id );
		element.val( 0 );
		element.parent( '.rtm-media-rate-star-wrapper' ).find( 'div' ).each( function() {
				jQuery( this ).removeClass( 'dashicons-star-filled' ).addClass( 'dashicons-star-empty' );
			}
		);

		element.next( 'ul' ).hover( function() {
		}, function() {
			var div_elements = $( this ).children( 'li' ).children( 'div' );
			div_elements.removeClass( 'dashicons-star-filled' );
			div_elements.addClass( 'dashicons-star-empty' );

			var stars = $( this ).parent().children( 'input' ).val();

			div_elements.slice(0, stars).removeClass( 'dashicons-star-empty' );
			div_elements.slice(0, stars).addClass( 'dashicons-star-filled' );
		});
	}
});

jQuery( document ).ready( function ( $ ) {

	rtmedia_init_rating();
	container_height_setter();
	rtMediaHook.register(
		'rtmedia_js_popup_after_content_added',
		function ( args ) {
			rtmedia_init_rating();
			return 1;
		}
	);
	rtMediaHook.register(
		'rtmedia_after_gallery_load',
		function ( args ) {
			rtmedia_init_rating();
			return 1;
		}
	);

	// This function is a fix for issue #14
	function container_height_setter() {
		var img = jQuery( '.rtmedia-container .rtmedia-list-item .rtmedia-item-thumbnail img' );
		var item = jQuery( '.rtmedia-container .rtmedia-list  .rtmedia-list-item' );

		var img_height = img.height() + 70 ;
		item.css( 'min-height', img_height + 'px' );
	}
});