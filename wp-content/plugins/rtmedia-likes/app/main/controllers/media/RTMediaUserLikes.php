<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RTMediaUserLikes
 *
 * @author sanket
 */
class RTMediaUserLikes {

	public function __construct() {
		if ( ! defined( 'RTMEDIA_USER_LIKES_PLURAL_LABEL' ) ) {
			define( 'RTMEDIA_USER_LIKES_PLURAL_LABEL', __( 'likes', 'rtmedia' ) );
		}

		if ( ! defined( 'RTMEDIA_USER_LIKES_LABEL' ) ) {
			define( 'RTMEDIA_USER_LIKES_LABEL', __( 'like', 'rtmedia' ) );
		}

		if ( ! defined( 'RTMEDIA_USER_LIKES_SLUG' ) ) {
			define( 'RTMEDIA_USER_LIKES_SLUG', __( 'likes', 'rtmedia' ) );
		}

		// init user likes feature
		add_action( 'init', array( $this, 'init_user_likes' ) );

		// add likes in allowed types
		add_filter( 'rtmedia_allowed_types', array( $this, 'add_likes_allowed_types' ), 99, 1 );

		// change media type for likes tab
		add_filter( 'rtmedia_media_type', array( $this, 'rtmedia_likes_media_type' ), 10, 1 );

		// add hidden field for likes page
		add_action( 'rtmedia_before_template_load', array( $this, 'rtmedia_userlikes_before_template_load' ), 10 );
	}

	/**
	 * hooks action and filter to initialize user's like page
	 */
	public function init_user_likes() {
		global $rtmedia;

		$option = $rtmedia->options;

		if ( ! empty( $option['general_enable_user_likes'] ) && ! empty( $rtmedia->options['general_enableLikes'] ) ) {
			// add "Likes" tab in navigation
			//add_action( 'add_extra_sub_nav', array( $this, 'add_extra_sub_nav' ) );
			add_filter( 'rtmedia_sub_nav_likes', array( $this, 'add_extra_sub_nav' ), 99 );
			// filter in action query
			add_filter( 'rtmedia_action_query_modifier_value', array( $this, 'rtmedia_action_query_modifier_value' ), 99, 2 );
			// add page link to admin nav on front end
			add_action( 'rtmedia_add_admin_bar_media_sub_menu', array( $this, 'add_admin_nav_likes' ), 99, 1 );
		} else {
			if ( isset( $rtmedia->allowed_types['likes'] ) ) {
				unset( $rtmedia->allowed_types['likes'] );
			}
		}

		$general_enable_user_likes_comment = ( isset( $option['general_enable_user_likes_comment'] ) ) ? $option['general_enable_user_likes_comment'] : true;
		if ( '0' != $general_enable_user_likes_comment && function_exists( 'rtmedia_comment_extra_commnent_like' ) ) {
			add_filter( 'rtmedia_comment_extra', 'rtmedia_comment_extra_commnent_like', 10, 2 );
		}
	}

	/**
	 * Filter query for likes page
	 * @param type $modifier_value
	 * @param type $raw_query
	 * @return string
	 */
	public function rtmedia_action_query_modifier_value( $modifier_value, $raw_query ) {
		if ( 'likes' == $modifier_value ) {
			// unset media_author from query
			add_filter( 'rtmedia_media_query', array( $this, 'modify_media_query' ), 10, 3 );
			// join with interaction table to get user's liked media
			add_filter( 'rtmedia-model-join-query', array( $this, 'rtmedia_model_join_interaction' ), 10, 2 );
			// remove interaction join filter
			add_action( 'bp_before_member_header', array( $this, 'remove_interaction_filter' ) );
			// set title
			add_filter( 'rtmedia_wp_title', array( $this, 'rtmedia_modify_wp_title' ), 10, 3 );
			// modify template title
			add_filter( 'rtmedia_gallery_title', array( $this, 'rtmedia_gallery_title' ) );

			$attr['attr']['hide_comment_media'] = false;
			$remove_comment_media = apply_filters( 'rtmedia_query_where_filter_remove_comment_media', true, 'likes' );
			if ( isset( $remove_comment_media ) && ! empty( $remove_comment_media ) ) {
				add_filter( 'rtmedia-model-where-query', array( $this, 'rtmedia_query_where_filter_remove_comment_media' ), 11, 3 );
				$attr['attr']['hide_comment_media'] = true;
			}

			return '';
		}

		return $modifier_value;
	}

	/**
	 * Gives gallery title for likes page
	 * @param type $title
	 * @return String  Title for 'Likes page'
	 */
	public function rtmedia_gallery_title( $title ) {
		global $media_query_clone;

		$user = get_userdata( $media_query_clone['context_id'] );

		if ( get_current_user_id() == $media_query_clone['context_id'] ) {
			return apply_filters( 'rtmedia_my_likes_media_page_title', __( 'Media liked by me', 'rtmedia' ) );
		} else {
			return apply_filters( 'rtmedia_user_likes_media_page_title', __( 'Media liked by', 'rtmedia' ) . ' ' . $user->display_name );
		}
	}

	// for gallery shortcode remove all comment media reply
	public function rtmedia_query_where_filter_remove_comment_media( $where, $table_name, $join ) {
		if ( function_exists( 'rtmedia_query_where_filter_remove_comment_media' ) ) {
			$where = rtmedia_query_where_filter_remove_comment_media( $where, $table_name, $join );
		}
		return $where;
	}

	/**
	 * Sets title for like page
	 * @param String $title	title
	 * @param type $default	defualt title
	 * @param string $sep  seprater
	 * @return string returns new modified title for likes page
	 */
	public function rtmedia_modify_wp_title( $title, $default, $sep ) {
		return ucfirst( constant( 'RTMEDIA_USER_LIKES_PLURAL_LABEL' ) ) . $title;
	}

	/**
	 * unset media author and context_id from media query
	 * @param array $media_query	media query
	 * @param type $action_query
	 * @param type $query
	 * @return array modified media query
	 */
	public function modify_media_query( $media_query, $action_query, $query ) {
		global $media_query_clone, $rtmedia_like_page; // store media_query for reference

		$rtmedia_like_page = true;
		$media_query_clone = $media_query;

		if ( isset( $media_query['context_id'] ) ) {
			unset( $media_query['context_id'] );
		}
		if ( isset( $media_query['context'] ) ) {
			unset( $media_query['context'] );
		}
		if ( isset( $media_query['media_author'] ) ) {
			unset( $media_query['media_author'] );
		}

		return $media_query;
	}

	/**
	 * removes joint interaction filter
	 */
	public function remove_interaction_filter() {
		// removes filter 'rtmedia_model_join_interaction'
		remove_filter( 'rtmedia-model-join-query', array( $this, 'rtmedia_model_join_interaction' ), 10, 2 );

		remove_filter( 'rtmedia-model-where-query', array( $this, 'rtmedia_query_where_filter_remove_comment_media' ), 11 );
	}

	/**
	 * Configure joint clause to retrive Liked media by current logged in user.
	 * @param String $join	Joint clause
	 * @param String $table_name
	 * @return String Joint clause to retrive Liked media by user
	 */
	public function rtmedia_model_join_interaction( $join, $table_name ) {
		global $rtmedia_interaction;

		$interaction_table = new RTMediaInteractionModel();
		$user_id = $rtmedia_interaction->context->id;
		$join .= " INNER JOIN {$interaction_table->table_name} ON ( {$table_name}.id = {$interaction_table->table_name}.media_id AND {$interaction_table->table_name}.action = 'like' AND {$interaction_table->table_name}.user_id = '{$user_id}' AND {$interaction_table->table_name}.value = '1' ) ";

		return $join;
	}

	/**
	 * Adds navigation tab under media section of user profile
	 */
	public function add_extra_sub_nav( $nav ) {
		global $rtmedia_query, $rtmedia_like_page;

		if ( isset( $rtmedia_query->query['context'] ) && $rtmedia_query->query['context'] == 'profile' ) {
			if ( ( isset( $rtmedia_query->action_query->media_type ) && 'likes' == $rtmedia_query->action_query->media_type ) || ( isset( $rtmedia_like_page ) && $rtmedia_like_page ) ) {
				$selected = ' class="current selected"';
			} else {
				$selected = '';
			}

			$context = isset( $rtmedia_query->query['context'] ) ? $rtmedia_query->query['context'] : 'default';
			$context_id = isset( $rtmedia_query->query['context_id'] ) ? $rtmedia_query->query['context_id'] : 0;
			$profile_link = trailingslashit( get_rtmedia_user_link( $rtmedia_query->query['context_id'] ) );
			$li_content = '<li id="rtmedia-nav-item-user-likes-' . $context . '-' . $context_id . '-li" ' . $selected . '> <a id="rtmedia-nav-item-user-likes" href="' . $profile_link . RTMEDIA_MEDIA_SLUG . '/' . constant( 'RTMEDIA_USER_LIKES_SLUG' ) . '/">' . ucfirst( constant( 'RTMEDIA_USER_LIKES_PLURAL_LABEL' ) ) . '</a> </li>';

			echo apply_filters( 'rtmedia_sub_nav_user_likes', $li_content );
		}
	}

	function add_admin_nav_likes( $parent_id ) {
		global $wp_admin_bar;
		$wp_admin_bar->add_menu ( array(
			'parent' => $parent_id,
			'id' => 'my-account-media-' . RTMEDIA_USER_LIKES_PLURAL_LABEL,
			'title' => ucfirst( constant( 'RTMEDIA_USER_LIKES_PLURAL_LABEL' ) ),
			'href' => trailingslashit( get_rtmedia_user_link( get_current_user_id() ) ) . RTMEDIA_MEDIA_SLUG . '/likes/'
		) );
	}

	/**
	 * add_likes_allowed_types		Add likes as an allowed type
	 *
	 * return array 				Return all media types
	 *
	 */
	function add_likes_allowed_types( $allowed_types ) {
		$likes_type = array(
			'likes' => array(
				'name' => 'likes',
				'plural' => 'likes',
				'label' => __( 'Likes', 'rtmedia' ),
				'plural_label' => __( 'Likes', 'rtmedia' ),
				'extn' => '',
				'settings_visibility' => false,
			),
		);

		if ( ! defined( 'RTMEDIA_LIKES_SLUG' ) ) {
			define( 'RTMEDIA_LIKES_SLUG', $likes_type['likes']['name'] );
		}

		$allowed_types = array_merge( $allowed_types, $likes_type );

		return $allowed_types;
	}

	/**
	 * rtmedia_likes_media_type		Set media type for likes page
	 *
	 * return string 				Return 'likes' as a type
	 *
	 */
	function rtmedia_likes_media_type( $media_type ) {

		// get only media type from query vars
		// because we need only media type from the url
		$media_type = '';
		$media_type_query_var = explode( '/',get_query_var( 'media' ) );
		if ( is_array( $media_type_query_var ) && isset( $media_type_query_var[0] ) ) {
			$media_type = $media_type_query_var[0];
		}

		if ( ( ! empty( $media_type ) && 'likes' === $media_type ) || ( isset( $_REQUEST['is_like'] ) &&  '1' === $_REQUEST['is_like'] ) ) {
			$media_type = 'likes';
		}

		return $media_type;
	}

	/**
	 * rtmedia_userlikes_before_template_load	Add hidden field for likepage
	 *
	 */
	function rtmedia_userlikes_before_template_load() {
		$query_var = get_query_var( 'media' );
		if ( ! empty( $query_var ) && 'likes' === $query_var ) {
			echo '<input type="hidden" name="is_like" id="is_like" value="1" />';
		}
	}
}
