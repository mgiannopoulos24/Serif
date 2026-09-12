<?php
/**
 * Breadcrumbs: automatic placement of the SEO plugin's breadcrumb block.
 *
 * Loaded by inc/integrations/yoast.php and rankmath.php. Nothing is registered
 * here — the theme only positions what the plugin provides, via the Block Hooks
 * API, above the title on single posts and pages. Users can still move or
 * remove the hooked block in the Site Editor, or place it by hand anywhere.
 *
 * @package Serif
 */

namespace Serif\Integrations;

/**
 * Hook a block before the post title on single posts and pages.
 *
 * Note: single.html renders its title inside parts/entry-header.html, so the
 * template-part context is matched there; page.html is matched directly.
 *
 * @param string $block_name Block to insert, e.g. `yoast-seo/breadcrumbs`.
 */
function hook_before_title( $block_name ) {
	add_filter(
		'hooked_block_types',
		static function ( $hooked_blocks, $relative_position, $anchor_block, $context ) use ( $block_name ) {
			if ( 'core/post-title' !== $anchor_block || 'before' !== $relative_position ) {
				return $hooked_blocks;
			}
			if ( ! $context instanceof \WP_Block_Template || ! in_array( $context->slug, array( 'entry-header', 'page' ), true ) ) {
				return $hooked_blocks;
			}
			$hooked_blocks[] = $block_name;
			return $hooked_blocks;
		},
		10,
		4
	);
}

/**
 * Whether a hooked-block callback is for our before-title placement.
 *
 * @param string                                $relative_position   before|after|first_child|last_child.
 * @param array|null                            $parsed_anchor_block The anchor block.
 * @param \WP_Block_Template|\WP_Post|array     $context             Template, part, pattern or post.
 * @return bool
 */
function is_before_title_hook( $relative_position, $parsed_anchor_block, $context ) {
	return 'before' === $relative_position
		&& 'core/post-title' === ( $parsed_anchor_block['blockName'] ?? '' )
		&& $context instanceof \WP_Block_Template
		&& in_array( $context->slug, array( 'entry-header', 'page' ), true );
}

/**
 * Separator markup: MDL chevron, hidden from assistive tech.
 *
 * @return string
 */
function separator() {
	return \Serif\icon( 'chevron-right', 'serif-breadcrumbs__sep' );
}
