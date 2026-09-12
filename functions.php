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
require_once SERIF_THEME_DIR . '/inc/recommended-plugin.php';

// SEO integrations (breadcrumb placement). Only one is loaded so a site with
// both plugins active never gets two breadcrumb trails; Yoast takes precedence.
if ( defined( 'WPSEO_VERSION' ) ) {
	require_once SERIF_THEME_DIR . '/inc/integrations/yoast.php';
} elseif ( defined( 'RANK_MATH_VERSION' ) ) {
	require_once SERIF_THEME_DIR . '/inc/integrations/rankmath.php';
}
