<?php
/**
 * Enqueue scripts and styles.
 *
 * @package Serif
 */

namespace Serif;

/**
 * Enqueue the compiled theme stylesheet and script.
 *
 * The style.css file holds only the theme header; the real CSS is compiled from
 * assets/scss/ into assets/css/style.min.css.
 */
function enqueue_assets() {
	$file = '/assets/css/style.min.css';

	wp_enqueue_style(
		'serif-style',
		get_template_directory_uri() . $file,
		array(),
		SERIF_VERSION . '.' . (int) filemtime( SERIF_THEME_DIR . $file )
	);

	$script = '/assets/js/theme.min.js';
	if ( file_exists( SERIF_THEME_DIR . $script ) ) {
		wp_enqueue_script(
			'serif-theme',
			get_template_directory_uri() . $script,
			array(),
			SERIF_VERSION . '.' . (int) filemtime( SERIF_THEME_DIR . $script ),
			array( 'strategy' => 'defer' )
		);
	}
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_assets' );

/**
 * Load the same stylesheet inside the block editor.
 */
function editor_styles() {
	add_editor_style( 'assets/css/style.min.css' );
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\\editor_styles' );

/**
 * Preload the two faces every page paints with above the fold: Plex Sans (site
 * title, navigation, headings) and Plex Serif Regular (body). Italic and Bold
 * load on demand. Fonts are declared in theme.json with font-display: swap.
 *
 * @param array $resources Resources to preload.
 * @return array
 */
function preload_fonts( $resources ) {
	foreach ( array( 'IBMPlexSans.woff2', 'IBMPlexSerif-Regular.woff2' ) as $file ) {
		$resources[] = array(
			'href'        => get_template_directory_uri() . '/assets/fonts/' . $file,
			'as'          => 'font',
			'type'        => 'font/woff2',
			'crossorigin' => 'anonymous',
		);
	}
	return $resources;
}
add_filter( 'wp_preload_resources', __NAMESPACE__ . '\\preload_fonts' );
