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
	 * Functions hooked into gwangi_single action
	 *
	 * @hooked gwangi_single          - 10
	 * @hooked gwangi_grimlock_single - 10
	 */
	do_action( 'gwangi_single' ); ?>
</article><!-- #post-## -->
