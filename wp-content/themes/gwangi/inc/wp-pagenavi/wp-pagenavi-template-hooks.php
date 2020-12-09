<?php
/**
 * Gwangi template hooks for WP PageNavi.
 *
 * @package gwangi
 */

/**
 * After posts hooks.
 *
 * @since 1.0.0
 */
remove_action( 'gwangi_after_posts', 'the_posts_navigation', 20 );
add_action(    'gwangi_after_posts', 'gwangi_wp_pagenavi',   10 );
