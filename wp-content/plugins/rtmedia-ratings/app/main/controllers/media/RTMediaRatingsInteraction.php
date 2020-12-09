<?php
/**
 * Author: Ritesh <ritesh.patel@rtcamp.com>
 */

class RTMediaRatingsInteraction extends RTMediaUserInteraction {

	function __construct () {
		// must set action and label
		if ( $this->check_disable() ) {
			return true; }

		$args = rtm_media_rating_interaction_args();
		parent::__construct( $args );
		remove_filter( 'rtmedia_action_buttons_before_delete', array( $this, 'button_filter' ) );
		//add rating button
		add_action( 'rtmedia_actions_before_description', array( $this, 'button_filter' ) );
	}


	/**
	 * Adds the ratings button to single media page
	 */
	function button_filter($buttons) {
		if ( empty($this->media) ) {
			$this->init();
		}
				echo "<div class='rtmedia-media-rating'>";
				$this->render();
		echo '</div>';
	}

	/**
	 * check 5 star rating for media is disable or not
	 * @return boolean True if ratings is disable else returns false
	 */
	function check_disable() {
		global $rtmedia;
		$options = $rtmedia->options;
		if ( ! (isset($options['general_enableRatings']) && '1' == ($options['general_enableRatings'] ) ) ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Rendering ratings button for media
	 * @param type $media_id ID of media
	 */
	function render( $media_id = false ) {
		if ( $this->check_disable() || apply_filters( 'rtmedia_render_media_rate', false ) ) {
			return true; }

		$default_rating = 0;
		$action = $this->action;
		$user_id = $this->interactor;
		$media_id = $this->action_query->id;
		$rtmediainteraction = new RTMediaInteractionModel();
		$media_result = $this->model->get( array( 'id' => $media_id ) );
		$curr_avg = 0;
		if ( $media_result && '' != $media_result ) {
			$curr_avg = $media_result[0]->ratings_average;
		}
		$results = $rtmediainteraction->get_row( $user_id, $media_id, $action );
		if ( $results && ! empty( $results ) && is_array( $results ) && count( $results ) > 0 ) {
			$row = $results[0];
			if ( is_numeric( $row->value ) && intval( $row->value ) >= 0 ) {
				$default_rating = $row->value;
			}
		}
		$link = trailingslashit( get_rtmedia_permalink( $this->media->id ) ) . $this->action . '/';
		$average_rating = ($curr_avg > 0) ? $curr_avg : $default_rating;
		$average_rating_rounded = ($average_rating > 0) ? round( $average_rating, 1 ) : __( 'NA', 'rtmedia' );
		$default_rating = ($default_rating > 0) ? $default_rating : __( 'NA', 'rtmedia' );
		?>
		<form method='post' id="rtm-media-rate-form" action='<?php echo $link ?>'>
			<?php rtm_media_rating_ui( $average_rating, $average_rating_rounded, $default_rating, $media_id );  ?>
		</form>
		<?php
	}

	/**
	 * Process the rating oprations
	 * @return boolean
	 */
	function process() {
		$post_data = $_POST;
		if( ! wp_verify_nonce( $post_data['nonce'], 'rtm_rating_nonce_' . $this->action_query->id ) ){
			die();
		}
		if ( $this->check_disable() ) {
			return true;
		}

		$action = $this->action_query->action;
		$user_id = $this->interactor;
		$media_id = $this->action_query->id;
		$value = $_REQUEST['value'];

		$rating_process = rtm_process_media_rating( $user_id, $media_id, $value, $action );

		$curr_avg = $rating_process['curr_avg'];
		$curr_value = $rating_process['curr_value'];
		$curr_total = $rating_process['curr_total'];

		global $rtmedia_points_media_id;
		$rtmedia_points_media_id = $this->action_query->id;
		do_action( 'rtmedia_pro_after_rating_media', $this );
		$data = array( 'average' => $curr_avg, 'curr_value' => $curr_value, 'media_id' => $media_id );
		$data_json = json_encode( $data );
		echo $data_json;
		die();
	}
}
