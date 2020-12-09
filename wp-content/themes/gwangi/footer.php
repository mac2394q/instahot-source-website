<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package gwangi
 */

?>

			<?php
			/**
			 * Functions hooked into gwangi_footer action
			 *
			 * @hooked gwangi_footer                 - 10
			 * @hooked gwangi_grimlock_after_content - 10
			 * @hooked gwangi_grimlock_footer        - 20
			 */
			do_action( 'gwangi_footer' ); ?>

		</div><!-- #site -->

		<?php
		do_action( 'gwangi_after_site' ); ?>

	</div><!-- #site-wrapper -->

<?php wp_footer(); ?>

</body>
</html>
