<?php

if( ! function_exists( 'rtm_media_rating_interaction_args' ) ){
	function rtm_media_rating_interaction_args() {

		return array(
			'action' => 'rating',
			'label' => 'rating',
			'privacy' => 20,
		);
	}
}


if( ! function_exists( 'rtm_media_rating_ui' ) ){
	function rtm_media_rating_ui( $average_rating, $average_rating_rounded, $default_rating, $media_id ) {
		if ( is_user_logged_in() ) {
			$rating_label = apply_filters( 'rtm_modify_rating_label',array(
				'rating' => __( 'Total Rating', 'rtmedia' ),
				'your_rating' => __( 'Your Rating', 'rtmedia' ),
			) );

			global $rtmedia_backbone;
			$style = '';
			if ( $rtmedia_backbone['backbone'] ) {
				$rating_nonce = '<%= rating_nonce %>';
				if ( intval( $default_rating ) <= 0 ) {
					$style = '<%= remove_rating_style %>';
				}
			} else {
				$rating_nonce = wp_create_nonce( 'rtm_rating_nonce_' . $media_id );
				if ( intval( $default_rating ) <= 0 ) {
					$style = 'display:none';
				}
			}
		?>
			<div class="rtm-media-rate-star-wrapper">
				<input name='rtmedia_pro_rate_media' value='<?php echo $average_rating; ?>' id="rtmedia-media-rate-el-<?php echo $media_id; ?>" class='rtmedia_pro_rate_media' type='hidden' data-media-id="<?php echo $media_id ?>" data-nonce="<?php echo $rating_nonce; ?>" />
			</div>
			<div class="rtmedia-pro-average-rating" >
				(
					<span class='rtmedia-avg-rate'>
						<?php echo $rating_label['rating'] . ' : '; ?><span class="rtmedia_pro_media_average_rating"><?php echo $average_rating_rounded; ?></span>
					</span>
					<span class='rtmedia-user-rate'>
						<?php echo $rating_label['your_rating'] . ' : ' ?><span class="rtmedia_pro_media_user_rating"><?php echo $default_rating; ?></span>
					</span>
				)
				<div title="<?php _e( 'Remove your rating', 'rtmedia' ); ?>" class="rtm-undo-rating dashicons dashicons-no" style="<?php echo $style ?>" id="rtm-undo-rating-<?php echo $media_id ?>" data-media-id="<?php echo $media_id; ?>" data-el-id="rtmedia-media-rate-el-<?php echo $media_id; ?>"></div>
			</div>
		<?php
		} else {
		?>
			<ul class="webwidget_rating_simple disabled_rating">
				<?php

				for ( $i = 0, $k = 1; $i < 5; $i ++ ) {
					($k <= $average_rating) ? $class = 'dashicons-star-filled' : $class = 'dashicons-star-empty';
					?>

					<li> <div class="dashicons <?php echo $class; ?>"></div> </li>
					<?php
					$k ++;
				}
				?>
			</ul>
		<?php
		}
	}
}


if( ! function_exists( 'rtm_process_media_rating' ) ){
	function rtm_process_media_rating( $user_id, $media_id, $rating, $action ) {

		$curr_count = 0;
		$curr_total = 0;
		$curr_avg = 0;

		$rtmediainteraction = new RTMediaInteractionModel();
		$rtmedia_model = new RTMediaModel();

		$media_result = $rtmedia_model->get( array( 'id' => $media_id ) );
		if ( $media_result && '' != $media_result ) {
			$curr_count = $media_result[0]->ratings_count;
			$curr_total = $media_result[0]->ratings_total;
			$curr_avg = $media_result[0]->ratings_average;
		}

		$check_action = $rtmediainteraction->check( $user_id, $media_id, $action );
		if ( $check_action ) {
			$results = $rtmediainteraction->get_row( $user_id, $media_id, $action );
			$row = $results[0];
			$curr_value = $row->value;
			$update_data = array( 'value' => $rating );
			$where_columns = array(
				'user_id' => $user_id,
				'media_id' => $media_id,
				'action' => $action,
			);

			if ( intval( $rating ) == 0 ) {
				$update = $rtmediainteraction->delete( $where_columns );
				$curr_count--;
				$curr_total = $curr_total - $curr_value;
				$curr_count = ( $curr_count >= 0 ) ? $curr_count : 0;
			} else {
				$update = $rtmediainteraction->update( $update_data, $where_columns );
				$curr_total = $curr_total - $curr_value + $rating;
			}
			$curr_avg = $curr_total / ( ( $curr_count <= 0 ) ? 1 : $curr_count );
		} else {
			$columns = array(
				'user_id' => $user_id,
				'media_id' => $media_id,
				'action' => $action,
				'value' => $rating,
			);
			$insert_id = $rtmediainteraction->insert( $columns );
			$curr_count ++;
			$curr_total = $curr_total + $rating;
			$curr_avg = $curr_total / $curr_count;
		}

		$update_data = array(
			'ratings_count' => $curr_count,
			'ratings_total' => $curr_total,
			'ratings_average' => $curr_avg,
		);
		$update_count = $rtmedia_model->update( $update_data, array( 'id' => $media_id ) );

		return array(
			'curr_value' => $curr_count,
			'curr_avg' => $curr_avg,
			'curr_total' => $curr_total,
		);
	}
}






if( ! function_exists( 'rtmedia_mycred_add_points_for_rate_media' ) ){
	function rtmedia_mycred_add_points_for_rate_media( $rtmedia_key ) {
		global $rtmedia;

		if ( is_array( $rtmedia_key ) ) {
			if ( isset( $rtmedia->options['general_enableRatings'] ) && '0' != $rtmedia->options['general_enableRatings'] ||
				 isset( $rtmedia->options['general_enableAlbumRatings'] ) && '0' != $rtmedia->options['general_enableAlbumRatings'] ) {
				$rtmedia_key['after_media_rate'] = array( 'action' => 'rtmedia_pro_after_rating_media' );
			}
		}
		return $rtmedia_key;
	}
}
remove_filter( 'rtmedia_mycred_add_points', 'rtmedia_mycred_add_points_for_rate_media', 10 );
add_filter( 'rtmedia_mycred_add_points', 'rtmedia_mycred_add_points_for_rate_media', 10, 1 );

// disable rating for comment media
if ( ! function_exists( 'rtmedia_render_media_rate_callback' ) ) {
	function rtmedia_render_media_rate_callback( $value ) {
		$return = $value;
		// if rating in media is allow.
		if ( false == $value ) {
			// get the media id
			$rtmedia_id = rtmedia_id();
			// check if it's not empty.
			if ( $rtmedia_id && function_exists( 'rtmedia_is_comment_media' ) ) {
				// check if the current media is comment media or not.
				$comment_media = rtmedia_is_comment_media( rtmedia_id() );
				// if the media is comment media then dnt allow the rating to get display in media single page.
				if ( ! empty( $comment_media ) ) {
					$return = true;
				}
			}
		}
		return $return;
	}
}
remove_filter( 'rtmedia_render_media_rate', 'rtmedia_render_media_rate_callback', 10 );
add_filter( 'rtmedia_render_media_rate', 'rtmedia_render_media_rate_callback', 10, 1 );
