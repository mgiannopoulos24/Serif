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
define( 'SERIF_THEME_DIR', get_template_directory_uri() );

// Load namespaced files.



/**
 * Wrapper for Serif\setup()
 */
