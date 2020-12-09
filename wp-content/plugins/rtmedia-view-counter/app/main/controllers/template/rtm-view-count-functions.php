<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//show media views below user details in new-lightbox-UI
remove_action( 'rtmedia_actions_before_description', 'rtmedia_get_media_view_counts', 10, 1 );
add_action( 'rtmedia_actions_before_description', 'rtm_get_media_view_counts', 10, 1 );
add_action( 'wp_footer', 'rtm_view_wrapper_div' );
add_action( 'wp_ajax_rtmedia_view_list', 'rtmedia_view_list_callback' );

if ( ! function_exists( 'rtm_get_media_view_counts' ) ) {
	/**
	 *  Adds 'view counts' for media in single media page
	 * @global type $rtmedia
	 * @param type $media_id
	 */
	function rtm_get_media_view_counts( $media_id = false ) {
		global $rtmedia;

		$rtmediainteraction = new RTMediaInteractionModel();
		$options = $rtmedia->options;

		$model = new RTMediaModel();
		$media = $model->get_media( array(
			'id' => rtmedia_id(),
		), 0, 1 );

		if ( isset( $options['general_viewcount'] ) && ( '1' == $options['general_viewcount'] ) ) {
			$results       = $rtmediainteraction->get_row( '', $media_id, 'view' );
			$counts = count( $results );
			if ( '' == $counts || sizeof( $counts ) == 0 || 0 == $counts ) {
				$counts = 1;
			}

			$view = _n( 'View', 'Views', $counts, 'rtmedia' );

			$counts = apply_filters( 'rtmedia_view_count_content', array( 'count' => $counts, 'text' => $view ), $media_id );

			$html = '';
			if ( bp_loggedin_user_id() == $media[0]->media_author ) {
				$html .= "<div id='view_list' class='rtmedia-media-views'><span>" . implode( ' ', $counts ) . '</span> </div>';
				$html .= "<input class='current-media-view' type='hidden' value=' " . $media_id . " '>";
			} else {
				$html .= "<div class='rtmedia-media-views'><span>" . implode( ' ', $counts ) . '</span> </div>';
			}
			echo $html;
		}
	}
}

/* Add wrapper for viewer details */
if ( ! function_exists( 'rtm_view_wrapper_div' ) ) {
	function rtm_view_wrapper_div() {
		?>
		<div class="rtm-media-view-wrapper">
			<div class="rtm-media-view">
				<h3><?php echo esc_html__( 'People Who viewed This', 'rtmedia' ); ?><span class="close" title="<?php esc_html__( 'Close', 'rtmedia' ); ?>"><?php echo esc_html__( 'X', 'rtmedia' ); ?></span></h3>
				<img class="loading-gif" src="<?php echo admin_url( '/images/loading.gif' ); ?>" alt="<?php esc_html__( 'Loading...', 'rtmedia' ); ?>" />
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'rtmedia_view_list_callback' ) ) {
	function rtmedia_view_list_callback() {
		global $wpdb;

		$rtmediainteraction = new RTMediaInteractionModel();
		$media_id = $results = $html = '';

		if ( isset( $_POST['media_id'] ) ) {
			$media_id	= $_POST['media_id'];
			$results	= $rtmediainteraction->get_row( '', $media_id, 'view' );

			if ( $results ) {
				foreach ( $results as $key => $val ) {
					$mysql_time = $wpdb->get_var( 'select CURRENT_TIMESTAMP()' );
					$like_time = human_time_diff( strtotime( $val->action_date ), strtotime( $mysql_time ) );

					if ( $val->user_id > 0 ) {
						$media_author	= get_userdata( $val->user_id );
						$user_avarar	= get_avatar( $media_author->user_email, 32 );
						$user_link		= bp_core_get_userlink( $val->user_id );

						$html .= '<li class="view-user">';
						$html .= '<div class="view-user-avatar"> ' . $user_avarar . ' </div>';
						$html .= '<div class="view-desc"> ' . $user_link . ' viewed this ' . $like_time . ' ago. </div>';
						$html .= '</li>';
					}
				}
			}
		} else {
			return;
		}

		die( $html );
	}
}
