<?php
/**
 * Serif
 *
 * Please do not make any edits to this file. All edits should be done in a child theme.
 *
 * @package Serif
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SERIF_VERSION', wp_get_theme()->get( 'Version' ) );

define( 'SERIF_THEME_DIR', get_template_directory() );
define( 'SERIF_THEME_URI', get_template_directory_uri() );

// Load core files.
require_once SERIF_THEME_DIR . '/inc/setup.php';
require_once SERIF_THEME_DIR . '/inc/scripts.php';
require_once SERIF_THEME_DIR . '/inc/block-styles.php';
require_once SERIF_THEME_DIR . '/inc/block-patterns.php';
require_once SERIF_THEME_DIR . '/inc/block-filters.php';
require_once SERIF_THEME_DIR . '/inc/search.php';

// Load integration files (only when the plugin is active and the file exists).
if ( defined( 'WPSEO_VERSION' ) && file_exists( SERIF_THEME_DIR . '/inc/integrations/yoast.php' ) ) {
	require_once SERIF_THEME_DIR . '/inc/integrations/yoast.php';
}

if ( defined( 'RANK_MATH_VERSION' ) && file_exists( SERIF_THEME_DIR . '/inc/integrations/rankmath.php' ) ) {
	require_once SERIF_THEME_DIR . '/inc/integrations/rankmath.php';
}
