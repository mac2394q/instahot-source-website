<?php
/**
 * Gwangi template hooks.
 *
 * @package gwangi
 */

/**
 * Header Hooks
 *
 * @see gwangi_header()
 *
 * @since 1.0.0
 */
add_action( 'gwangi_header', 'gwangi_header', 10 );

/**
 * Footer Hooks
 *
 * @see gwangi_footer()
 *
 * @since 1.0.0
 */
add_action( 'gwangi_footer', 'gwangi_footer', 10 );

/**
 * Sidebar Hooks
 *
 * @see gwangi_sidebar_right()
 *
 * @since 1.0.0
 */
add_action( 'gwangi_sidebar_right', 'gwangi_sidebar_right', 10 );

/**
 * Before Posts Hooks.
 *
 * @see gwangi_before_posts()
 *
 * @since 1.0.0
 */
add_action( 'gwangi_before_posts', 'gwangi_before_posts',  10 );

/**
 * After Posts Hooks.
 *
 * @see gwangi_after_posts()
 * @see the_posts_navigation()
 *
 * @since 1.0.0
 */
add_action( 'gwangi_after_posts', 'gwangi_after_posts',   10 );
add_action( 'gwangi_after_posts', 'the_posts_navigation', 20 );

/**
 * Post Hooks
 *
 * @see gwangi_post()
 *
 * @since 1.0.0
 */
add_action( 'gwangi_post', 'gwangi_post', 10 );

/**
 * Search Post Hooks
 *
 * @see gwangi_search_post()
 *
 * @since 1.0.0
 */
add_action( 'gwangi_search_post', 'gwangi_search_post', 10 );

/**
 * Single Post Hooks
 *
 * @see gwangi_single()
 *
 * @since 1.0.0
 */
add_action( 'gwangi_single',              'gwangi_single',       10 );
add_action( 'gwangi_the_post_navigation', 'the_post_navigation', 10 );

/**
 * Page Hooks
 *
 * @see gwangi_page()
 *
 * @since 1.0.0
 */
add_action( 'gwangi_page', 'gwangi_page', 10 );

/**
 * 404 Hooks
 *
 * @see gwangi_404()
 *
 * @since 1.1.8
 */
add_action( 'gwangi_404', 'gwangi_404', 10 );

/**
 * Other Hooks
 *
 * @see gwangi_nav_menu_css_class()
 * @see gwangi_get_the_archive_title()
 *
 * @since 1.0.0
 */
add_filter( 'nav_menu_css_class',    'gwangi_nav_menu_css_class',    10, 4 );
add_filter( 'get_the_archive_title', 'gwangi_get_the_archive_title', 10, 1 );
add_filter( 'theme_page_templates',  'gwangi_theme_page_templates',  10, 1 );
