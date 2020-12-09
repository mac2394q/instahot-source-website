<?php
/**
 * Template part for displaying page content in page.php.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package gwangi
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	/**
	 * Functions hooked into gwangi_page action
	 *
	 * @hooked gwangi_page          - 10
	 * @hooked gwangi_grimlock_page - 10
	 */
	do_action( 'gwangi_page' ); ?>
</article><!-- #post-## -->
