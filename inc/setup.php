<?php
/**
 * Theme setup functions.
 *
 * @package Serif
 */

namespace Serif;

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function setup() {
	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Let WordPress manage the document title.
	add_theme_support( 'title-tag' );

	// Enable support for Post Thumbnails.
	add_theme_support( 'post-thumbnails' );

	// Register navigation menus.
	register_nav_menus(
		array(
			'primary' => __( 'Primary', 'serif' ),
			'footer'  => __( 'Footer', 'serif' ),
			'social'  => __( 'Social', 'serif' ),
		)
	);

	// Switch default core markup to valid HTML5.
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Enable responsive embeds.
	add_theme_support( 'responsive-embeds' );

	// Enable block styles.
	add_theme_support( 'wp-block-styles' );

	// Enable align-wide and align-full support.
	add_theme_support( 'align-wide' );

	// Enable editor styles.
	add_theme_support( 'editor-styles' );

	// Enable custom line height (also set in theme.json).
	add_theme_support( 'custom-line-height' );

	// Enable custom spacing (also set in theme.json).
	add_theme_support( 'custom-spacing' );

	// Enable custom logo.
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 60,
			'width'       => 240,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);

	// Disable custom colors and font sizes in favor of theme.json presets.
	add_theme_support( 'disable-custom-colors' );
	add_theme_support( 'disable-custom-font-sizes' );
	add_theme_support( 'disable-custom-gradients' );
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\\setup' );

/**
 * Set the content width in pixels.
 *
 * @global int $content_width
 */
function content_width() {
	$GLOBALS['content_width'] = apply_filters( 'serif_content_width', 720 );
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\\content_width', 0 );
