'use strict';

/**
 * File main.js
 *
 * Theme enhancements for a better user experience.
 */

jQuery( function( $ ) {


    /**
     * Scroll to href anchor
     */

    var $navbar = $( '#navigation' );
    var $wpadminbar = $( '#wpadminbar' );
    var $body = $( 'body' );
    var additionalOffset = 20;

    if ( $navbar.length && ( $body.hasClass( 'grimlock--navigation-stick-to-top' ) || $body.hasClass( 'grimlock--navigation-unstick-to-top' ) ) ) {
        additionalOffset += $navbar.outerHeight();
    }

    if ( $wpadminbar.length ) {
        additionalOffset += $wpadminbar.outerHeight();
    }

    $( 'a[href*="#"]' ).not( '[href="#"]' ).not( '[href="#0"]' ).not( '[href*="#tab-"]' ).not( '[href*="tab"]' ).not( '[href*="link"]' ).not( '[role="tab"]' ).not( '#cancel-comment-reply-link' ).on( 'click', function( event ) {
        if ( location.pathname.replace( /^\//, '' ) === this.pathname.replace( /^\//, '' ) && location.hostname === this.hostname && location.search === this.search ) {
            var target = $( this.hash );
            target = target.length ? target : $( '[name=' + this.hash.slice( 1 ) + ']' );
            if ( target.length ) {
                event.preventDefault();
                $( 'html, body' ).animate( {
                    scrollTop: target.offset().top - additionalOffset
                }, 800 );
            }
        }
    } );


    /**
     * Bootstrap tooltip init
     */

    $( function() {
        $( '[data-toggle="tooltip"]' ).tooltip({
            trigger: 'hover',
            delay: { "show": 0, "hide": 0 },
        });
    } );


    /**
     * Prevent body to scroll when hamburger navigation is open
     */

    $( '#navigation-collapse' ).on( 'show.bs.collapse', function() {
        $( 'body' ).addClass( 'ov-h navbar-collapse-show' ).removeClass( 'navbar-collapse-hide' );
    } );

    $( '#navigation-collapse' ).on( 'hide.bs.collapse', function() {
        $( 'body' ).removeClass( 'ov-h navbar-collapse-show' ).addClass( 'navbar-collapse-hide' );
    } );


    /**
     * Opacity scroll effect for parallax hero background
     */

    var $itemHeader = $( '#hero' );
    var $coverImage = $( '.parallax-mirror img' );

    if ( $itemHeader.length && $coverImage.length ) {

        // Increase this value to decrease the effect and vice versa
        var headerOffsetBottom = $itemHeader.offset().top + $itemHeader.height();

        var scrollTop = $( window ).scrollTop();
        var opacity = scrollTop < headerOffsetBottom ? 1 - ( scrollTop / headerOffsetBottom * 2 ) : 0;

        $coverImage.css( 'opacity', opacity );

        $( window ).on( 'scroll', function() {
            scrollTop = $( window ).scrollTop();
            opacity = scrollTop < headerOffsetBottom ? 1 - ( scrollTop / headerOffsetBottom * 2 ) : 0;

            $coverImage.css( 'opacity', opacity );
        } );
    }



} );
