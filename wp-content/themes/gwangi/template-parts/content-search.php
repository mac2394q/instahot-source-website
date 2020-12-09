<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package gwangi
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	/**
	 * Functions hooked into gwangi_search_post action
	 *
	 * @hooked gwangi_search_post          - 10
	 * @hooked gwangi_grimlock_search_post - 10
	 */
	do_action( 'gwangi_search_post' ); ?>
</article><!-- #post-## -->
