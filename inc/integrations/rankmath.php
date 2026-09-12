<?php
/**
 * Rank Math SEO integration. Loaded only when Rank Math is active (see functions.php).
 *
 * Rank Math has no breadcrumbs block, only `[rank_math_breadcrumb]`, so the
 * theme hooks a Shortcode block carrying it above post and page titles.
 *
 * @package Serif
 */

namespace Serif\Integrations\RankMath;

use function Serif\Integrations\hook_before_title;
use function Serif\Integrations\is_before_title_hook;

require_once __DIR__ . '/breadcrumbs.php';

// The theme places breadcrumbs itself; this also enables them in Rank Math.
add_action(
	'after_setup_theme',
	static function () {
		add_theme_support( 'rank-math-breadcrumbs' );
	}
);

hook_before_title( 'core/shortcode' );

// Give the hooked Shortcode block its content.
add_filter(
	'hooked_block_core/shortcode',
	static function ( $parsed_hooked_block, $hooked_block_type, $relative_position, $parsed_anchor_block, $context ) {
		if ( is_array( $parsed_hooked_block ) && is_before_title_hook( $relative_position, $parsed_anchor_block, $context ) ) {
			$parsed_hooked_block['innerContent'] = array( '[rank_math_breadcrumb]' );
			$parsed_hooked_block['innerHTML']    = '[rank_math_breadcrumb]';
		}
		return $parsed_hooked_block;
	},
	10,
	5
);

// Wrapper with the theme class and a landmark name. Rank Math passes the
// separator through wp_kses_post() (strips SVG), so the chevron is drawn in
// CSS on its <span class="separator">.
add_filter(
	'rank_math/frontend/breadcrumb/args',
	static function ( $args ) {
		$args['separator']   = '';
		$args['wrap_before'] = '<nav aria-label="' . esc_attr__( 'Breadcrumbs', 'serif' ) . '" class="rank-math-breadcrumb serif-breadcrumbs"><p>';
		$args['wrap_after']  = '</p></nav>';
		return $args;
	}
);
