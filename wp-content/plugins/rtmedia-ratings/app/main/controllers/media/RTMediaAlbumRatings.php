<?php

class RTMediaAlbumRatings {

	public $action;

	public $label;

	function __construct(){

		if( $this->is_album_rating_enable() ){
			// album rating UI
			add_action( 'rtmedia_after_album_gallery_item', array( $this, 'render_album_rating' ) );

			// handle album raring
			add_action( 'wp_ajax_rtm_album_rating', array( $this, 'album_rating_process' ) );

			// Handle load more album request and set rating data into media object
			add_filter( 'rtmedia_media_array_backbone', array( $this, 'rtmedia_media_array_backbone' ), 10, 1 );

			$args = rtm_media_rating_interaction_args();

			$this->action = ! empty( $args['action'] ) ? $args['action'] : 'rating';
			$this->label = ! empty( $args['label'] ) ? $args['label'] : 'rating';
		}
	}

	/**
	 * Set album rating data into media object
	 *
	 * @param $media
	 *
	 * @return mixed
	 */
	function rtmedia_media_array_backbone( $media ){
		if( $media->media_type == 'album' ){
			$ratings = $this->get_album_rating_details( $media->id );

			$media->average_rating = $ratings['average_rating'];
			$media->average_rating_rounded = $ratings['average_rating_rounded'];
			$media->default_rating = $ratings['default_rating'];
			$media->rating_nonce = wp_create_nonce( 'rtm_rating_nonce_' . $media->id );

			$media->remove_rating_style = '';
			if( intval( $media->default_rating ) <= 0 ) {
				$media->remove_rating_style = 'display:none';
			}
		}
		return $media;
	}

	/**
	 * handle ajax request to set user rating for albums
	 */
	function album_rating_process(){
		$post_data = $_POST;
		$media_id = $post_data['media_id'];
		$rating = $post_data['value'];

		if( ! wp_verify_nonce( $post_data['nonce'], 'rtm_rating_nonce_' . $media_id ) ){
			wp_die();
		}
		$user_id = get_current_user_id();
		$action = $this->action;

		$curr_count = 0;
		$curr_total = 0;
		$curr_avg = 0;

		$rating_process = rtm_process_media_rating( $user_id, $media_id, $rating, $action );

		$curr_avg = $rating_process['curr_avg'];
		$curr_value = $rating_process['curr_value'];
		$curr_total = $rating_process['curr_total'];

		global $rtmedia_points_media_id;
		$rtmedia_points_media_id = $media_id;
		do_action( 'rtmedia_pro_after_rating_media', $this );
		$data = array( 'average' => $curr_avg, 'curr_value' => $curr_value, 'media_id' => $media_id );
		$data_json = json_encode( $data );
		echo $data_json;
		die();
	}

	/**
	 * Render album rating UI
	 */
	function render_album_rating(){
		$media_id = rtmedia_id();

		global $rtmedia_backbone;
		if ( $rtmedia_backbone[ 'backbone' ] ) {
			$average_rating = '<%= average_rating %>';
			$average_rating_rounded = '<%= average_rating_rounded %>';
			$default_rating = '<%= default_rating %>';
		} else {
			$ratings = $this->get_album_rating_details( $media_id );

			$average_rating = $ratings['average_rating'];
			$average_rating_rounded = $ratings['average_rating_rounded'];
			$default_rating = $ratings['default_rating'];
		}
		?>
		<div class="rtmedia-media-rating">
		<?php rtm_media_rating_ui( $average_rating, $average_rating_rounded, $default_rating, $media_id );  ?>
		</div>
		<?php
	}

	/**
	 * Get album rating details
	 *
	 * @param $media_id
	 *
	 * @return array
	 */
	function get_album_rating_details( $media_id ){
		$default_rating = 0;
		$action = $this->action;
		$user_id = get_current_user_id();

		$rtmediainteraction = new RTMediaInteractionModel();
		$rtmedia_model = new RTMediaModel();

		$media_result = $rtmedia_model->get( array( 'id' => $media_id ) );
		$average_rating = 0;
		if ( !empty( $media_result ) ) {
			$average_rating = $media_result[0]->ratings_average;
		}
		$results = $rtmediainteraction->get_row( $user_id, $media_id, $action );
		if ( $results && ! empty( $results ) && is_array( $results ) && count( $results ) > 0 ) {
			$row = $results[0];
			if ( is_numeric( $row->value ) && intval( $row->value ) >= 0 ) {
				$default_rating = $row->value;
			}
		}

		$average_rating = ($average_rating > 0) ? $average_rating : $default_rating;
		$average_rating_rounded = ($average_rating > 0) ? round( $average_rating, 1 ) : __( 'NA', 'rtmedia' );
		$default_rating = ($default_rating > 0) ? $default_rating : __( 'NA', 'rtmedia' );

		return array(
			'average_rating' => $average_rating,
			'average_rating_rounded' => $average_rating_rounded,
			'default_rating' => $default_rating,
		);
	}

	/**
	 * check whether album rating is enabled or not
	 *
	 * @return bool
	 */
	function is_album_rating_enable(){
		global $rtmedia;
		$options = $rtmedia->options;

		$enable = false;
		if( isset( $options['general_enableAlbumRatings'] ) && '1' == $options['general_enableAlbumRatings'] ){
			$enable = true;
		}

		return $enable;
	}

}