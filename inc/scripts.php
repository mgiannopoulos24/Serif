<?php
/**
 * Enqueue scripts and styles.
 *
 * @package Serif
 */

namespace Serif;

/**
 * Enqueue theme scripts and styles.
 */
function enqueue_scripts() {
	$theme_version = wp_get_theme()->get( 'Version' );

	// Theme stylesheet.
	wp_enqueue_style(
		'serif-style',
		get_template_directory_uri() . '/style.css',
		array(),
		$theme_version
	);

	// Navigation script.
	wp_enqueue_script(
		'serif-navigation',
		get_template_directory_uri() . '/assets/js/navigation.js',
		array(),
		$theme_version,
		true
	);
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_scripts' );

/**
 * Enqueue editor styles.
 */
function enqueue_editor_styles() {
	add_editor_style( 'style.css' );
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\\enqueue_editor_styles' );

/**
 * Dequeue WP core block library styles on frontend pages that don't need them.
 */
function dequeue_block_styles() {
	wp_dequeue_style( 'wp-block-library-theme' );
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\dequeue_block_styles', 100 );
