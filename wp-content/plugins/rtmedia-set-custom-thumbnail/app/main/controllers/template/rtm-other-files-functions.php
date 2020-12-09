<?php
if ( ! defined( 'ABSPATH' ) ){
	exit;
}
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */






/**
 * Add mycred settings for adding points when Album Image is Set.
 *
 * @param  array $rtmedia_key  Contains the array related to the mycread points
 *
 * @return array
 */
if( ! function_exists( 'rtmedia_mycred_add_points_for_custom_thumbnail' ) ){
	function rtmedia_mycred_add_points_for_custom_thumbnail( $rtmedia_key ) {
		global $rtmedia;

		if ( is_array( $rtmedia_key ) ) {
			$rtmedia_key['after_set_album_cover'] = array( 'action' => 'rtm_after_set_custom_thumbnail' );
		}
		return $rtmedia_key;
	}
}
remove_filter( 'rtmedia_mycred_add_points', 'rtmedia_mycred_add_points_for_custom_thumbnail', 10 );
add_filter( 'rtmedia_mycred_add_points', 'rtmedia_mycred_add_points_for_custom_thumbnail', 10, 1 );
