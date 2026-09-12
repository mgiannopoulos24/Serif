<?php
/**
 * Theme setup.
 *
 * Block themes get post-thumbnails, responsive-embeds, editor-styles, html5,
 * automatic-feed-links and the title tag from core automatically. Layout,
 * alignment, spacing and colour options live in theme.json. Only what core
 * does not already provide is declared here.
 *
 * @package Serif
 */

namespace Serif;

/**
 * Register theme supports that are not implied by being a block theme.
 */
function setup() {
	// Translations shipped in /languages (wp-content/languages/themes takes precedence).
	load_theme_textdomain( 'serif', get_template_directory() . '/languages' );

	add_theme_support(
		'custom-logo',
		array(
			'height'      => 60,
			'width'       => 240,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\\setup' );
