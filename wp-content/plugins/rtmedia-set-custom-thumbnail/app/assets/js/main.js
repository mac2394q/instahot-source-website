jQuery( document ).ready( function(){

	const thumbnailFileInput = jQuery( '#rtmedia_media_custom_thumbnail' );

	// Here checking the correct file format for video/music thumbnail.
	thumbnailFileInput.change( function( e ) {
		const fileName        = e.target.files[0].name;
		const validExtensions = [ 'gif', 'png', 'jpg', 'jpeg' ];
		if ( jQuery.inArray( fileName.split( '.' ).pop(), validExtensions ) === -1 ) {
			thumbnailFileInput.val( null );
			alert( rtm_custom_thumb_localization_object.incorrect_file_error_message );
		}
	} );

	// edit custom thumbnails
	thumbnailFileInput.closest('form').attr({ enctype:"multipart/form-data", encoding: "multipart/form-data" });
} );
