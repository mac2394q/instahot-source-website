<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package gwangi
 */

?>

<div class="container container--narrow">

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<div class="p-4 bg-primary rounded search-module">
			<form role="search" method="get" class="search-form text-center pos-r" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<div class="form-group mb-0">
					<label class="sr-only">
						<span class="screen-reader-text sr-only"><?php esc_html_e( 'Search everything...', 'gwangi' ); ?></span>
					</label>
					<input type="search" class="search-field form-control form-control-lg" placeholder="<?php echo esc_attr( 'Search everything...', 'gwangi' ); ?>"  title="<?php echo esc_attr( 'Search everything...', 'gwangi' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s"/>
					<button type="submit" class="search-submit"><i class="fa fa-search"></i></button>
				</div>
			</form><!-- .search-form -->
		</div>

		<?php if ( function_exists( 'buddypress' ) ) : ?>
			<div class="text-center mt-4">
				<a href="<?php bp_signup_page(); ?>" class="btn btn-primary btn-sm col-12 col-sm-auto mt-2 mb-1"><?php esc_html_e( 'Register', 'gwangi' ); ?></a>
				<a href="<?php bp_activation_page(); ?>" class="btn btn-primary btn-sm col-12 col-sm-auto mt-2 mb-1"><?php esc_html_e( 'Activation', 'gwangi' ); ?></a>
				<a href="<?php echo esc_url( home_url( '/' ) . bp_get_activity_slug() ); ?>" class="btn btn-primary btn-sm col-12 col-sm-auto mt-2 mb-1"><?php echo esc_html( bp_get_activity_slug() ); ?></a>
				<a href="<?php echo esc_url( home_url( '/' ) . bp_get_members_slug() ); ?>" class="btn btn-primary btn-sm col-12 col-sm-auto mt-2 mb-1"><?php echo esc_html( bp_get_members_slug() ); ?></a>
				<a href="<?php echo esc_url( home_url( '/' ) . bp_get_groups_slug() ); ?>" class="btn btn-primary btn-sm col-12 col-sm-auto mt-2 mb-1"><?php echo esc_html( bp_get_groups_slug() ); ?></a>
			</div>
			<hr class="mt-4 mb-4" />
		<?php endif; ?>

		<div class="pt-4">
			<?php the_content(); ?>
		</div>

	</article><!-- #post-## -->

</div>
