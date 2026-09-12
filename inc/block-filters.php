<?php
/**
 * Front-end output filters for core blocks.
 *
 * - Navigation: swap the open/close icons for Material Design Icons Light
 *   and add a brand row and a search form to the mobile overlay.
 * - Search: swap the icon button's icon for MDL "magnify".
 *
 * Icons © Pictogrammers, Apache License 2.0 — see CREDITS.md.
 *
 * @package Serif
 */

namespace Serif;

/**
 * Return an inline SVG from assets/images/icons, or an empty string.
 *
 * @param string $name    Icon file name without extension.
 * @param string $classes Extra class(es) for the <svg>.
 * @return string
 */
function icon( $name, $classes = '' ) {
	static $cache = array();

	if ( ! isset( $cache[ $name ] ) ) {
		$file           = SERIF_THEME_DIR . '/assets/images/icons/' . $name . '.svg';
		$cache[ $name ] = file_exists( $file ) ? (string) file_get_contents( $file ) : ''; // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	}

	if ( '' === $cache[ $name ] ) {
		return '';
	}

	$attrs = ' class="serif-icon' . ( $classes ? ' ' . esc_attr( $classes ) : '' ) . '" aria-hidden="true" focusable="false"';
	return preg_replace( '/<svg\s/', '<svg' . $attrs . ' ', $cache[ $name ], 1 );
}

/**
 * Replace the SVG inside a button matched by class with one of ours.
 *
 * @param string $html         Block HTML.
 * @param string $button_class Class on the <button>.
 * @param string $svg          Replacement SVG markup.
 * @return string
 */
function replace_button_icon( $html, $button_class, $svg ) {
	if ( '' === $svg ) {
		return $html;
	}
	$pattern = '/(<button[^>]*class="[^"]*' . preg_quote( $button_class, '/' ) . '[^"]*"[^>]*>)\s*<svg.*?<\/svg>/s';
	return preg_replace( $pattern, '$1' . str_replace( '$', '\$', $svg ), $html, 1 );
}

/**
 * Navigation block: icons, brand row, overlay footer.
 *
 * @param string $html Rendered block HTML.
 * @return string
 */
function filter_navigation( $html ) {
	if ( ! str_contains( $html, 'wp-block-navigation__responsive-container-open' ) ) {
		return $html; // Overlay disabled; nothing to do.
	}

	$html = replace_button_icon( $html, 'wp-block-navigation__responsive-container-open', icon( 'menu' ) );
	$html = replace_button_icon( $html, 'wp-block-navigation__responsive-container-close', icon( 'close', 'serif-icon--close' ) );

	// Brand row at the top of the overlay, before the close button.
	$brand = sprintf(
		'<div class="serif-menu__brand">%s<a class="serif-menu__title" href="%s" rel="home">%s</a></div>',
		get_custom_logo(),
		esc_url( home_url( '/' ) ),
		esc_html( get_bloginfo( 'name' ) )
	);
	$html  = preg_replace( '/(<button[^>]*wp-block-navigation__responsive-container-close)/', $brand . '$1', $html, 1 );

	// Footer with search and tagline at the bottom of the overlay content.
	$footer  = '<div class="serif-menu__footer">'
		. do_blocks( '<!-- wp:search {"label":"' . esc_attr__( 'Search the site', 'serif' ) . '","showLabel":false,"placeholder":"' . esc_attr__( 'Search…', 'serif' ) . '","buttonText":"' . esc_attr__( 'Search', 'serif' ) . '","buttonUseIcon":true} /-->' );
	$tagline = get_bloginfo( 'description' );
	if ( $tagline ) {
		$footer .= '<p class="serif-menu__tagline">' . esc_html( $tagline ) . '</p>';
	}
	$footer .= '</div>';

	$html = preg_replace( '/(<\/ul>)(\s*<\/div>\s*<\/div>\s*<\/div>\s*<\/div>)/s', '$1' . str_replace( '$', '\$', $footer ) . '$2', $html, 1 );

	return $html;
}
add_filter( 'render_block_core/navigation', __NAMESPACE__ . '\\filter_navigation' );

/**
 * Search block: use the MDL magnify icon on icon buttons.
 *
 * @param string $html  Rendered block HTML.
 * @param array  $block Parsed block.
 * @return string
 */
function filter_search( $html, $block ) {
	// Name the search landmark after the block's label so several forms on one
	// page (header, overlay, 404 body) are distinguishable to assistive tech.
	$label = $block['attrs']['label'] ?? __( 'Search', 'serif' );
	if ( $label && ! preg_match( '/<form[^>]*aria-label=/', $html ) ) {
		$html = preg_replace( '/<form(\s[^>]*role="search")/', '<form aria-label="' . esc_attr( $label ) . '"$1', $html, 1 );
	}

	if ( str_contains( $html, 'has-icon' ) ) {
		$html = replace_button_icon( $html, 'wp-block-search__button', icon( 'magnify' ) );
	}
	return $html;
}
add_filter( 'render_block_core/search', __NAMESPACE__ . '\\filter_search', 10, 2 );
