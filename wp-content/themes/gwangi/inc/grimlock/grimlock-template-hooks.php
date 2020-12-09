<?php
/**
 * Gwangi template Hooks for Grimlock.
 *
 * @package gwangi
 */

/**
 * Before Site Hooks.
 *
 * @see gwangi_grimlock_before_site()
 *
 * @since 1.0.0
 */
add_action( 'gwangi_before_site', 'gwangi_grimlock_before_site', 10 );

/**
 * Header Hooks.
 *
 * @see gwangi_header()
 * @see gwangi_grimlock_header()
 * @see gwangi_grimlock_before_content()
 *
 * @since 1.0.0
 */
remove_action( 'gwangi_header', 'gwangi_header',                  10 );
add_action(    'gwangi_header', 'gwangi_grimlock_header',         10 );
add_action(    'gwangi_header', 'gwangi_grimlock_before_content', 20 );

/**
 * Navigation Hooks.
 *
 * @see gwangi_grimlock_navbar_nav_menu()
 * @see gwangi_grimlock_vertical_navbar_nav_menu()
 *
 * @since 1.0.0
 */
add_action( 'grimlock_navbar_nav_menu',          'gwangi_grimlock_navbar_nav_menu',          10, 1 );
add_action( 'grimlock_vertical_navbar_nav_menu', 'gwangi_grimlock_vertical_navbar_nav_menu', 10, 1 );

/**
 * Footer Hooks.
 *
 * @see gwangi_footer()
 * @see gwangi_grimlock_footer()
 * @see gwangi_grimlock_after_content()
 *
 * @since 1.0.0
 */
remove_action( 'gwangi_footer', 'gwangi_footer',                 10 );
add_action(    'gwangi_footer', 'gwangi_grimlock_after_content', 10 );
add_action(    'gwangi_footer', 'gwangi_grimlock_footer',        20 );

/**
 * After Site Hooks.
 *
 * @see gwangi_grimlock_after_site()
 *
 * @since 1.0.0
 */
add_action( 'gwangi_after_site', 'gwangi_grimlock_after_site', 10 );

/**
 * Sidebar Hooks
 *
 * @see gwangi_sidebar_left()
 * @see gwangi_sidebar_right()
 * @see gwangi_grimlock_sidebar_left()
 * @see gwangi_grimlock_sidebar_right()
 *
 * @since 1.0.0
 */
remove_action( 'gwangi_sidebar_left', 'gwangi_sidebar_left',          10 );
add_action(    'gwangi_sidebar_left', 'gwangi_grimlock_sidebar_left', 10 );

remove_action( 'gwangi_sidebar_right', 'gwangi_sidebar_right',          10 );
add_action(    'gwangi_sidebar_right', 'gwangi_grimlock_sidebar_right', 10 );

/**
 * Before Posts Hooks.
 *
 * @see gwangi_before_posts()
 * @see gwangi_grimlock_before_posts()
 *
 * @since 1.0.0
 */
remove_action( 'gwangi_before_posts', 'gwangi_before_posts',           10 );
add_action(    'gwangi_before_posts', 'gwangi_grimlock_before_posts',  10 );

/**
 * After Posts Hooks.
 *
 * @see gwangi_after_posts()
 * @see gwangi_grimlock_after_posts()
 *
 * @since 1.0.0
 */
remove_action( 'gwangi_after_posts', 'gwangi_after_posts',          10 );
add_action(    'gwangi_after_posts', 'gwangi_grimlock_after_posts', 10 );

/**
 * Post Hooks.
 *
 * @see gwangi_post()
 * @see gwangi_grimlock_post()
 * @see gwangi_grimlock_the_post_thumbnail()
 * @see gwangi_grimlock_post_thumbnail_size()
 * @see gwangi_grimlock_the_excerpt()
 * @see gwangi_the_date()
 * @see gwangi_the_author()
 * @see gwangi_the_category_list()
 * @see gwangi_the_tag_list()
 * @see gwangi_the_post_format()
 * @see gwangi_the_sticky_mark()
 * @see gwangi_get_more_link_text()
 * @see gwangi_comments_link()
 *
 * @since 1.0.0
 */
remove_action( 'gwangi_post', 'gwangi_post',           10 );
add_action(    'gwangi_post', 'gwangi_grimlock_post',  10 );

add_action( 'grimlock_post_thumbnail',      'gwangi_grimlock_the_post_thumbnail',  10, 2 );
add_action( 'grimlock_post_excerpt',        'gwangi_grimlock_the_excerpt',         10, 1 );
add_action( 'grimlock_post_date',           'gwangi_the_date',                     10    );
add_action( 'grimlock_post_author',         'gwangi_the_author',                   10    );
add_action( 'grimlock_category_list',       'gwangi_the_category_list',            10    );
add_action( 'grimlock_post_tag_list',       'gwangi_the_tag_list',                 10    );
add_action( 'grimlock_post_format',         'gwangi_the_post_format',              10    );
add_action( 'grimlock_post_format',         'gwangi_the_sticky_mark',              10    );
add_filter( 'grimlock_post_more_link_text', 'gwangi_get_more_link_text',           10, 1 );
add_action( 'grimlock_comments_link',       'gwangi_comments_link',                10    );

/**
 * Search Hooks.
 *
 * @see gwangi_search_post()
 * @see gwangi_grimlock_search_post()
 *
 * @since 1.0.0
 */
remove_action( 'gwangi_search_post', 'gwangi_search_post',          10 );
add_action(    'gwangi_search_post', 'gwangi_grimlock_search_post', 10 );

/**
 * Single Post Hooks.
 *
 * @see gwangi_single()
 * @see gwangi_the_author_biography()
 * @see gwangi_grimlock_single()
 * @see gwangi_grimlock_the_post_navigation()
 *
 * @since 1.0.0
 */

remove_action( 'gwangi_single', 'gwangi_single',          10 );
add_action(    'gwangi_single', 'gwangi_grimlock_single', 10 );

remove_action( 'gwangi_the_post_navigation', 'the_post_navigation',                 10 );
add_action(    'gwangi_the_post_navigation', 'gwangi_grimlock_the_post_navigation', 10 );

remove_action( 'grimlock_single_content', 'grimlock_single_author_biography',     30 );
add_action( 'grimlock_single_content',    'gwangi_grimlock_the_author_biography', 30 );

/**
 * Page Hooks.
 *
 * @see gwangi_page()
 * @see gwangi_grimlock_page()
 *
 * @since 1.0.0
 */
remove_action( 'gwangi_page', 'gwangi_page',          10 );
add_action(    'gwangi_page', 'gwangi_grimlock_page', 10 );

/**
 * 404 Hooks.
 *
 * @see gwangi_404()
 * @see gwangi_grimlock_404()
 *
 * @since 1.1.8
 */
remove_action( 'gwangi_404', 'gwangi_404',          10 );
add_action(    'gwangi_404', 'gwangi_grimlock_404', 10 );

/**
 * Homepage Hooks.
 *
 * @see gwangi_grimlock_homepage()
 *
 * @since 1.0.0
 */
add_action( 'gwangi_homepage', 'gwangi_grimlock_homepage', 10 );

/**
 * Other Hooks.
 *
 * @see gwangi_grimlock_remove_actions()
 *
 * @since 1.0.0
 */
remove_filter( 'nav_menu_css_class',   'gwangi_nav_menu_css_class',   10 );
remove_filter( 'theme_page_templates', 'gwangi_theme_page_templates', 10 );

add_action( 'template_redirect', 'gwangi_grimlock_remove_actions', 10 );

