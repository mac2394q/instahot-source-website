<?php
/**
 * BP Profile Search - filters template 'bps-filters'
 *
 * See http://dontdream.it/bp-profile-search/form-templates/ if you wish to modify this template or develop a new one.
 *
 * @package BuddyPress
 */

// @codingStandardsIgnoreFile
// Allow plugin text domain in theme.

$filters = bps_escaped_filters_data();
if ( empty( $filters->fields ) ) {
	return false;
}
?>
	<div class='bps_filters mb-4 p-3'>

		<a href='<?php echo esc_url( $filters->action ); ?>' class="bps_filters_reset" data-toggle="tooltip" data-placement="top" title="<?php esc_html_e( 'Reset Filters', 'grimlock-buddypress' ); ?>">
			<span class="d-none"><?php esc_html_e( 'Reset Filters', 'grimlock-buddypress' ); ?></span>
		</a>

		<div class="bps_filters__content">
			<?php
			foreach ( $filters->fields as $filter ) {

				$filter_html = bps_print_filter( $filter );
				$filter_html = apply_filters( 'bps_print_filter', $filter_html, $filter ); ?>

				<div class="bps-filters-item d-sm-inline-block mr-sm-3">
					<strong><?php echo esc_html( $filter->label ); ?></strong>
					<span><?php echo esc_html( $filter_html ); ?></span>
				</div>
				<?php
			}
			?>
		</div>
	</div>
<?php

// BP Profile Search - end of template
