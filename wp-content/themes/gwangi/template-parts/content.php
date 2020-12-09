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
	 * Functions hooked into gwangi_post action
	 *
	 * @hooked gwangi_post          - 10
	 * @hooked gwangi_grimlock_post - 10
	 */
	do_action( 'gwangi_post' ); ?>
</article><!-- #post-## -->
