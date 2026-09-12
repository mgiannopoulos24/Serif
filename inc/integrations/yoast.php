<?php
/**
 * Yoast SEO integration. Loaded only when Yoast is active (see functions.php).
 *
 * Yoast provides the `yoast-seo/breadcrumbs` block; the theme places it above
 * post and page titles and styles its output.
 *
 * @package Serif
 */

namespace Serif\Integrations\Yoast;

use function Serif\Integrations\hook_before_title;
use function Serif\Integrations\separator;

require_once __DIR__ . '/breadcrumbs.php';

// The theme places breadcrumbs itself; this also enables them in Yoast.
add_action(
	'after_setup_theme',
	static function () {
		add_theme_support( 'yoast-seo-breadcrumbs' );
	}
);

hook_before_title( 'yoast-seo/breadcrumbs' );

add_filter( 'wpseo_breadcrumb_output_wrapper', static fn() => 'nav' );
add_filter( 'wpseo_breadcrumb_output_class', static fn() => 'serif-breadcrumbs' );
add_filter( 'wpseo_breadcrumb_separator', static fn() => separator() );

// Name the landmark and mark the current page for assistive tech.
add_filter(
	'wpseo_breadcrumb_output',
	static function ( $output ) {
		$output = preg_replace( '/<nav(?![^>]*aria-label)/', '<nav aria-label="' . esc_attr__( 'Breadcrumbs', 'serif' ) . '"', (string) $output, 1 );
		return str_replace( '<span class="breadcrumb_last"', '<span class="breadcrumb_last" aria-current="page"', $output );
	}
);
