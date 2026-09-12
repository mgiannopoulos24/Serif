<?php
/**
 * Apply a theme style variation as the site's user global styles.
 *
 * Usage (inside the wp-env cli container):
 *   wp eval-file wp-content/themes/serif/scripts/set-style-variation.php <slug|default>
 *
 * Used by tests/e2e-pw/specs/variations.spec.ts to check colour contrast
 * under every variation. `default` clears the user styles.
 *
 * @package Serif
 */

$serif_slug    = $args[0] ?? 'default';
$serif_post_id = WP_Theme_JSON_Resolver::get_user_global_styles_post_id();

if ( 'default' === $serif_slug ) {
	$serif_data = array(
		'version'                     => WP_Theme_JSON::LATEST_SCHEMA,
		'isGlobalStylesUserThemeJSON' => true,
	);
} else {
	$serif_file = get_stylesheet_directory() . '/styles/' . basename( $serif_slug ) . '.json';
	if ( ! file_exists( $serif_file ) ) {
		WP_CLI::error( "No variation: $serif_slug" );
	}
	$serif_data = json_decode( file_get_contents( $serif_file ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	unset( $serif_data['$schema'], $serif_data['title'] );
	$serif_data['isGlobalStylesUserThemeJSON'] = true;
}

wp_update_post(
	array(
		'ID'           => $serif_post_id,
		'post_content' => wp_json_encode( $serif_data ),
	)
);
WP_Theme_JSON_Resolver::clean_cached_data();
wp_cache_flush();
WP_CLI::success( "Style variation: $serif_slug" );
