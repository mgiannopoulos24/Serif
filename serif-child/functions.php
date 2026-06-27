<?php
/**
 * Serif Child theme.
 *
 * @package Serif_Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue parent theme styles.
 */
function serif_child_enqueue_styles() {
	wp_enqueue_style(
		'serif-parent-style',
		get_template_directory_uri() . '/style.css'
	);
}
add_action( 'wp_enqueue_scripts', 'serif_child_enqueue_styles' );
