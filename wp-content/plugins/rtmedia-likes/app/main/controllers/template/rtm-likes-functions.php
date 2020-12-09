<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Fetches details of users who liked media
 *
 * @param type $media_id
 *
 * @return Users list Object
 */
global $rtmedia;

$like_flag = false;

if ( isset( $rtmedia->options ) && ! isset( $rtmedia->options['general_enableLikes'] ) ) {
	$like_flag = true;
} else if ( isset( $rtmedia->options ) && isset( $rtmedia->options['general_enableLikes'] ) && $rtmedia->options['general_enableLikes'] == 1 ) {
	$like_flag = true;
}


// if likes are enabled from backend, then only load content of "who liked"
if ( $like_flag ) {
	remove_action( 'wp_footer', 'rtmpro_like_wrapper_div' );

	remove_action( 'wp_ajax_rtm_media_likes', 'rtm_media_likes_callback' );
	remove_action( 'wp_ajax_nopriv_rtm_media_likes', 'rtm_media_likes_callback' );
	add_action( 'wp_ajax_rtm_media_likes', 'rtmedia_media_likes_callback' );
	add_action( 'wp_ajax_nopriv_rtm_media_likes', 'rtmedia_media_likes_callback' );

	remove_filter( 'rtmedia_action_buttons_after_delete', 'rtm_media_like_stats_button', 10, 1 );
	add_filter( 'rtmedia_action_buttons_after_delete', 'rtmedia_media_like_stats_button', 10, 1 );
}
add_action( 'wp_footer', 'rtm_like_wrapper_div' );


if ( ! function_exists( 'rtm_likes_add_second_time' ) ) {
	function rtm_likes_add_second_time( $since, $diff, $from, $to ) {
		if ( $diff < MINUTE_IN_SECONDS ) {
			/* translators: min=minute */
			$since = sprintf( _n( '%s sec', '%s secs', $diff, 'rtmedia' ), $diff );
		}
		return $since;
	}
}


if ( ! function_exists( 'rtm_fetch_media_like_stats' ) ) {
	function rtm_fetch_media_like_stats( $media_id ) {
		if ( empty( $media_id ) ) {
			return false;
		}

		$rtmediainteractionmodel = new RTMediaInteractionModel();
		$media_like_cols = array(
			'media_id' => $media_id,
			'action' => 'like',
			'value' => 1,
		);
		$media_likes = $rtmediainteractionmodel->get( $media_like_cols, false, false, 'action_date' );

		if ( count( $media_likes ) == 0 ) {
			return false;
		}

		return $media_likes;
	}
}

if ( ! function_exists( 'rtmedia_media_likes_callback' ) ) {
	function rtmedia_media_likes_callback() {
		global $wpdb, $rtmedia_media;

		if ( ! empty( $rtmedia_media ) ) {
			$media_id = $rtmedia_media->id;
		} else {
			$media_id = ! empty( $_POST['media_id'] ) ? $_POST['media_id'] : '';
		}

		if ( empty( $media_id ) ) {
			return;
		}

		$like_list = '';
		$user_details = rtm_fetch_media_like_stats( $media_id );

		if ( ! $user_details ) {
			return '<li>' . _e( 'no likes', 'rtmedia' ) . '</li>';
			die();
		}

		$mysql_time = $wpdb->get_var( 'select CURRENT_TIMESTAMP()' );

		$current_user = get_current_user_id();

		foreach ( $user_details as $detail ) {
			$like_list .= rtm_like_single_li_html( $detail->user_id, $current_user, $detail->action_date, $mysql_time );
		}

		echo $like_list;
		die( 1 );
	}
}// End if().
//Append like info wrapper after div
if ( ! function_exists( 'rtm_like_wrapper_div' ) ) {
	function rtm_like_wrapper_div() {
		if ( 1 ) {
			?>
			<div class="rtm-media-likes-wrapper">
			<div class="rtm-media-likes">
				<h3><?php _e( 'People Who Like This', 'rtmedia' ); ?><span class="close" title="<?php _e( 'Close', 'rtmedia' ); ?>">x</span></h3>
				<img class="loading-gif" src="<?php echo admin_url( '/images/loading.gif' ); ?>" alt="<?php _e( 'Loading...', 'rtmedia' ); ?>" />
			</div>
		</div>
		<?php
		}
	}
}

if ( ! function_exists( 'rtmedia_media_like_stats_button' ) ) {
	function rtmedia_media_like_stats_button( $actions ) {
		global $rtmedia_media;

		if ( isset( $rtmedia_media->id ) ) {
			$actions[] = '<input class="current-media-item" type="hidden" value="' . $rtmedia_media->id . '" />';
		}

		return $actions;
	}
}


if ( ! function_exists( 'rtmedia_comment_extra_commnent_like' ) ) {
	function rtmedia_comment_extra_commnent_like( $html, $comment ) {
		$comment_id = ! empty( $comment['comment_ID'] ) ? $comment['comment_ID'] : false;
		if ( ! empty( $comment_id ) ) {
			$html .= rtmedia_get_comment_like_html( $comment_id );
		}
		return  $html;
	}
}


/**
 * Get comment like html using comment ID
 *
 * @param       array    $comment_id
 */
if ( ! function_exists( 'rtmedia_comment_like_html' ) ) {
	function rtmedia_comment_like_html( $comment_id ) {
		$comment_id = ! empty( $comment_id ) ? $comment_id : false;
		if ( $comment_id ) {
			echo rtmedia_get_comment_like_html( $comment_id );
		}
	}
}



/**
 * Get comment like html using comment ID
 *
 * @param       int    $comment_id
 *
 * @return      html
 */
if ( ! function_exists( 'rtmedia_get_comment_like_html' ) ) {
	function rtmedia_get_comment_like_html( $comment_id ) {
		$comment_id = ! empty( $comment_id ) ? $comment_id : false;
		$comment     = get_comment( $comment_id );
		$output = '';
		if ( ! empty( $comment ) && ! empty( $comment_id ) ) {
			$user_id = get_current_user_id();
			$like_user = array();
			$like_count = get_comment_meta( $comment_id, 'rtmedia-comment-like-count', true );
			$like_user = get_comment_meta( $comment_id, 'rtmedia-comment-like-user', true );

			$output = rtmedia_get_comment_like_html_logic( $comment_id, $user_id, $like_count, $like_user );
		}// End if().
		return  $output;
	}
}

/**
 * Get comment like html using comment ID
 *
 * @param       int    $comment_id ( current Comment ID )
 * @param       int    $user_id ( Login user ID  )
 * @param       int    $like_count ( Total like count )
 * @param       array    $like_user( list of user who like the comment )
 *
 * @return      html
 */
if ( ! function_exists( 'rtmedia_get_comment_like_html_logic' ) ) {
	function rtmedia_get_comment_like_html_logic( $comment_id, $user_id, $like_count, $like_user ) {
		$comment_id = ! empty( $comment_id ) ? $comment_id : false;
		$user_id = ! empty( $user_id ) ? $user_id : false;
		$like_count = ! empty( $like_count ) ? $like_count : false;
		$like_user = ! empty( $like_user ) ? $like_user : array();
		$output = '';
		$user_like_it = false;
		if ( ! empty( $user_id ) && is_array( $like_user ) && ! empty( $like_user ) ) {
			$user_like_it = array_key_exists( $user_id, $like_user );
		}
		if ( $comment_id ) {
			ob_start();
			?>
			<p class="rtmedia-comment-like-main">
				<input type="hidden" name="comment_id"  class="comment_id" value="<?php echo  $comment_id; ?>" />
				<?php $nonce = 'rtmedia-nonce-' . $comment_id; ?>
				<input type="hidden" name="<?php echo $nonce; ?>" class="rtmedia-nonce" id="<?php echo $nonce; ?>" value="<?php echo wp_create_nonce( $nonce ); ?>" />

				<?php
				if ( $user_id ) {

					$like_title = '';
					if ( ! empty( $user_like_it ) ) {
						$like_title = __( 'Unlike', 'rtmedia' );
					} else {
						$like_title = __( 'Like', 'rtmedia' );
					}
					?>
					<span class="rtmedia-comment-like-button">
						<a href="#" class="rtmedia-comment-like-click" data-comment_id=" <?php echo $comment_id; ?> " title="<?php echo esc_attr( $like_title ); ?>">
							<?php
							if ( $user_like_it ) {
								esc_html_e( 'Unlike', 'rtmedia' );
							} else {
								esc_html_e( 'Like', 'rtmedia' );
							}
							?>
						</a>
					</span>
					<?php
				}
				?>

				<i></i>
				<span class="rtmedia-comment-like-body rtmedia-comment-like-counter-wrap">
					<?php
					if( function_exists( 'rtmedia_who_like_html' ) ){
						echo rtmedia_who_like_html( $like_count, $user_like_it );
					}
					?>
				</span>
			</p>
			<?php
			$output = ob_get_contents();
			ob_end_clean();
		}// End if().
		return $output;
	}
}// End if().






/**
 * Commnet who like ajax call
 *
 * @param       int    $comment_id ( current Comment ID )
 *
 * @return      array 	Contant html and status
 */
add_action( 'wp_ajax_rtmedia_comment_who_like', 'rtmedia_comment_who_like_ajax_callback' );
add_action( 'wp_ajax_nopriv_rtmedia_comment_who_like', 'rtmedia_comment_who_like_ajax_callback' );
if ( ! function_exists( 'rtmedia_comment_who_like_ajax_callback' ) ) {
	function rtmedia_comment_who_like_ajax_callback() {
		$comment_id = ! empty( $_POST['comment_id'] ) ? $_POST['comment_id'] : false;
		$comment_id = (int) $comment_id;
		$nonce = 'rtmedia-nonce-' . $comment_id;

		if ( $comment_id && check_ajax_referer( $nonce, 'rtmedia_nonce', false ) ) {

			$responces = rtmedia_comment_who_like_html( $comment_id );
		} else {
			$responces['status'] = false;
		}

		$responces = json_encode( $responces );
		die( $responces );
	}
}



/**
 * Get comment like html
 *
 * @param       int    $comment_id ( current Comment ID )
 *
 * @return      html
 */
if ( ! function_exists( 'rtmedia_comment_who_like_html' ) ) {
	function rtmedia_comment_who_like_html( $comment_id ) {
		$comment_id = ! empty( $comment_id ) ? $comment_id : false;
		if ( $comment_id ) {
			global $wpdb;

			$like_list = '';
			$like_user = array();
			$like_users = get_comment_meta( $comment_id, 'rtmedia-comment-like-user', true );
			$like_counts = get_comment_meta( $comment_id, 'rtmedia-comment-like-count', true );
			if ( ! $like_users ) {
				$like_list = '<li>' . _e( 'no likes', 'rtmedia' ) . '</li>';
			}
			$mysql_time = $wpdb->get_var( 'select CURRENT_TIMESTAMP()' );
			$current_user = get_current_user_id();

			foreach ( $like_users as $user_id => $like_user ) {
				$like_list .= rtm_like_single_li_html( $user_id, $current_user, $like_user['time'], $mysql_time );
			}
			$responces['status'] = true;
			$responces['html'] = $like_list;
		} else {
			$responces['status'] = false;
		}// End if().
		return $responces;
	}
}// End if().


/**
 * Get comment li html when click who like the comment
 *
 * @param       int    $user_id ( who has like the comment  )
 * @param       int    $current_user ( loged in user ID )
 * @param       int    $action_date ( Like Date )
 * @param       array    $mysql_time( Today date in mYSQL format )
 *
 * @return      html
 */
if( ! function_exists( 'rtm_like_single_li_html' ) ){
	function rtm_like_single_li_html( $user_id, $current_user, $action_date, $mysql_time ){
			global $wpdb;

			$user_data = get_userdata( $user_id );

			if ( $current_user === intval( $user_id ) ) {
		        $user_name = esc_html__( 'You', 'rtmedia' );
		    } else {
		        $user_name = ! empty( $user_data ) ? $user_data->data->display_name : '';
		    }

			$user_profile = '';

			if ( class_exists( 'BuddyPress' ) ) {
				$user_profile = bp_core_get_user_domain( $user_id );
			} else {
				$user_profile = site_url() . '/author/' . $user_data->data->user_login;
			}

			$user_avatar = rtmedia_author_profile_pic( '', false, $user_id );

			add_filter( 'human_time_diff', 'rtm_likes_add_second_time', 10, 4 );
			$like_time = human_time_diff( strtotime( $action_date ), strtotime( $mysql_time ) );
			remove_filter( 'human_time_diff', 'rtm_likes_add_second_time', 10 );

			return  '<li class="like-user"><div class="like-user-avatar"><a href="' . $user_profile . '">' . $user_avatar . '</a></div><div class="like-desc"><a href="' . $user_profile . '">' . $user_name . '</a> ' . esc_html__( 'liked this ', 'rtmedia' ) . '<span class="user-like-time">' . $like_time . esc_html__( ' ago', 'rtmedia' ) . '</span></div></li>';

	}
}// End if().



/**
 *  Called when user like or dialike the comment
 *
 * @param       int    $comment_id ( current Comment ID )
 *
 * @return      array 	contant html and status
 */
add_action( 'wp_ajax_rtmedia_comment_like', 'rtmedia_comment_like_ajax_callback' );
if ( ! function_exists( 'rtmedia_comment_like_ajax_callback' ) ) {
	function rtmedia_comment_like_ajax_callback() {
		$comment_id = ! empty( $_POST['comment_id'] ) ? $_POST['comment_id'] : false;
		$comment_id = (int) $comment_id;
		$nonce = 'rtmedia-nonce-' . $comment_id;

		if ( $comment_id && check_ajax_referer( $nonce, 'rtmedia_nonce', false ) ) {
			$responces = rtmedia_update_comment_like( $comment_id );
		} else {
			$responces['status'] = false;
		}

		$responces = json_encode( $responces );
		die( $responces );
	}
}


/**
 *  get the html and also update who like the comment or unlike the comment
 *
 * @param       int    $comment_id ( current Comment ID )
 *
 * @return      array 	contant html and status
 */
if ( ! function_exists( 'rtmedia_update_comment_like' ) ) {
	function rtmedia_update_comment_like( $comment_id ) {
		$comment_id = ! empty( $comment_id ) ? $comment_id : false;
		$responces['status'] = false;
		$responces['comment_id'] = $comment_id;
		if ( $comment_id ) {
			$comment = get_comment( $comment_id );
			$user_id = get_current_user_id();
			if ( $comment && $user_id ) {
				$like_user    = get_comment_meta( $comment_id, 'rtmedia-comment-like-user', true );
				$like_user    = ( ! empty( $like_user ) ) ? $like_user : array();
				$like_count   = get_comment_meta( $comment_id, 'rtmedia-comment-like-count', true );
				$user_like_it = false;
				if( is_array( $like_user ) && ! empty( $like_user ) ) {
					$user_like_it = array_key_exists( $user_id, $like_user );
				}
				if ( $user_like_it ) {
					unset( $like_user[ $user_id ] );
					$like_count--;
				} else {
					global $wpdb;
					// Below condition is added here because in prevous version comment likes user meta are not stored in array as we expected.So all count were stored in a single key so it causes likes toggle issue.
					if ( ! is_array( $like_user ) ) {
						$like_user = array();
					}
					$like_user[ $user_id ] = array(
							'time' => $wpdb->get_var( 'select CURRENT_TIMESTAMP()' ),
						);
					$like_count++;
				}

				update_comment_meta( $comment_id, 'rtmedia-comment-like-user', $like_user );
				update_comment_meta( $comment_id, 'rtmedia-comment-like-count', abs( $like_count ) );

				$responces['html'] = rtmedia_get_comment_like_html_logic( $comment_id, $user_id, $like_count, $like_user );
				$responces['status'] = true;
			}
		}
		return $responces;
	}
}