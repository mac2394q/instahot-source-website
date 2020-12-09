<?php
/**
 * Requirements
 *
 * @package GamiPress\BuddyPress\Requirements
 * @since 1.0.0
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Add the bp fields to the requirement object
 *
 * @param $requirement
 * @param $requirement_id
 *
 * @return array
 */
function gamipress_bp_requirement_object( $requirement, $requirement_id ) {

    if( isset( $requirement['trigger_type'] ) ) {

        if( $requirement['trigger_type'] === 'gamipress_bp_set_member_type' ) {

            // The member type
            $requirement['bp_member_type'] = gamipress_get_post_meta( $requirement_id, '_gamipress_bp_member_type', true );

        }

    }

    return $requirement;
}
add_filter( 'gamipress_requirement_object', 'gamipress_bp_requirement_object', 10, 2 );

/**
 * Link fields on requirements UI
 *
 * @param $requirement_id
 * @param $post_id
 */
function gamipress_bp_requirement_ui_fields( $requirement_id, $post_id ) {

    $member_types = bp_get_member_types( array(), 'objects' );
    $bp_member_type = gamipress_get_post_meta( $requirement_id, '_gamipress_bp_member_type', true ); ?>

    <select class="select-bp-member-type">
        <?php foreach( $member_types as $member_type => $member_type_obj ) : ?>
            <option value="<?php echo $member_type; ?>" <?php selected( $bp_member_type, $member_type ); ?>><?php echo $member_type_obj->labels['singular_name']; ?></option>
        <?php endforeach; ?>
    </select>

    <?php
}
add_action( 'gamipress_requirement_ui_html_after_achievement_post', 'gamipress_bp_requirement_ui_fields', 10, 2 );

/**
 * Custom handler to save the custom fields on requirements UI
 *
 * @param $requirement_id
 * @param $requirement
 */
function gamipress_bp_ajax_update_requirement( $requirement_id, $requirement ) {

    if( isset( $requirement['trigger_type'] ) ) {

        if( $requirement['trigger_type'] === 'gamipress_bp_set_member_type' ) {

            // The member type
            update_post_meta( $requirement_id, '_gamipress_bp_member_type', $requirement['bp_member_type'] );
        }

    }
}
add_action( 'gamipress_ajax_update_requirement', 'gamipress_bp_ajax_update_requirement', 10, 2 );