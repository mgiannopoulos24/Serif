<?php
/**
 * Serif Child theme.
 *
 * @package Serif_Child
 */

/**
 * Enqueue parent theme styles.
 */
function serif_child_enqueue_styles() {
	wp_enqueue_style(
		'serif-parent-style',
		get_template_directory_uri() . '/assets/css/style.min.css',
		array( 'serif-style' ),
		wp_get_theme()->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'serif_child_enqueue_styles' );
