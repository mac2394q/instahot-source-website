'use strict';

/**
 * File grid.js
 *
 * Theme enhancements for the posts grid layout selected
 * in the Customizer.
 */

jQuery( function( $ ) {

    /**
     * Initiate Masonry grid for posts.
     */

    $( '#posts' ).masonry( {
        itemSelector: '[id*="post-"]'
    } );

} );
