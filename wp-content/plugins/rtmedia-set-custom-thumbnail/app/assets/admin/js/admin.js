/**
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// Custom thumbnail Script.
var customThumb = {
	init: function() {
		jQuery( '.rtm-delete-preview' ).on( 'click', function( e ) {
			e.preventDefault();
			var conf = confirm( rtm_custom_thumbnails_admin_object.rtmedia_custom_thumbnails_delete_confirm_msg );
			$current = jQuery( this );

			if ( true === conf ) {

				const thumbnailPreviewImage = jQuery( '<img />' ).attr( {
					'src'  : rtm_custom_thumbnails_admin_object.rtmedia_custom_thumbnails_loading_file,
					'class': 'rtm-delete-preview',
				} );

				media_type = jQuery( this ).data( 'media_type' );

				// Here loader get replace with delete icon when click on icon.
				jQuery( 'a[data-media_type="' + media_type + '"]' ).children().replaceWith( thumbnailPreviewImage );
				jQuery( this ).css( { 'right':'36%', 'top':'38%', 'opacity': '1' } );
				jQuery.post( ajaxurl, {
					action: 'rtmedia_delete_default_thumbnail',
					media_type: media_type
				}, function( data ) {
					if ( true === data.success ) {
						$current.parent( '.rtm-file-preview' ).prev( 'input[type="hidden"]' ).val( '' );
						$current.parent( '.rtm-file-preview' ).remove();

					} else {
						alert( rtm_custom_thumbnails_admin_object.rtmedia_custom_thumbnail_delete_failed_msg );
					}
				} );
			}
		} )

		// Check correct file format for the audio custom thumbnail.
		rtmDefaultAudioFileUploader = jQuery( 'input[name="rtm_default_thumbnail_audio"]' );
		rtmDefaultAudioFileUploader.change( function( e ) {
			customThumb.checkThumbnailFileFormat( rtmDefaultAudioFileUploader, e );
		} );

		// Check correct file format for the video custom thumbnail.
		rtmDefaultVideoFileUploader = jQuery( 'input[name="rtm_default_thumbnail_video"]' );
		rtmDefaultVideoFileUploader.change( function( e ) {
			customThumb.checkThumbnailFileFormat( rtmDefaultVideoFileUploader, e );
		} );
	},

	/*
	* Check appropriate file for audio/video thumbnail.
	*
	* @param {HTML Object} thumbnailFileInput It contain the html tag.
	* @param {Object}      event              It contain the event as change.
	*/
	checkThumbnailFileFormat: function( thumbnailFileInput , event ) {
		const fileName        = event.target.files[0].name;
		const validExtensions = [ 'gif', 'png', 'jpg', 'jpeg' ];
		if ( jQuery.inArray( fileName.split( '.' ).pop(), validExtensions ) === -1 ) {
			thumbnailFileInput.val( null );
			alert( rtm_custom_thumbnails_admin_object.rtmedia_custom_thumbnails_incorrect_file_error_msg );
		} else {
			// Here show the file name when select as custom thumbnail for audio/video.
			const previewContainer     = thumbnailFileInput.parents( 'td' );
			const oldFileNameAsPreview = previewContainer.find( '.rtm-file-name-preview' )
			if (  0 !== oldFileNameAsPreview.length ) {
				oldFileNameAsPreview.remove();
			}
			const newFileNameAsPreview = jQuery( '<p>' ).attr( {
				'class': 'rtm-file-name-preview',
			} ).text( fileName );
			previewContainer.append( newFileNameAsPreview );
		}
	}
}
customThumb.init();
