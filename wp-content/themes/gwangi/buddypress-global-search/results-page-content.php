<?php
/**
 * The template file for BPS results page.
 *
 * @package buddypress-global-search
 */

?>

<div class="bboss_search_page">
	<div class="bboss_search_form_wrapper dir-search no-ajax">
		<?php get_search_form(); ?>
	</div>
	<div class="bboss_search_results_wrapper dir-form">
		<div id="subnav">
			<div class="search_filters item-list-tabs primary-list-tabs no-ajax" role="navigation">
				<ul class="item-list-tabs-ul clearfix">
					<?php buddyboss_global_search_filters(); ?>
				</ul>
			</div>
		</div>
		<div class="search_results">
			<?php buddyboss_global_search_results(); ?>
		</div>
	</div>
</div><!-- .bboss_search_page -->
