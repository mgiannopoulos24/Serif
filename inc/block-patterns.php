<?php
/**
 * Block patterns and pattern categories.
 *
 * Patterns themselves live in /patterns and are auto-registered by WordPress.
 *
 * @package Serif
 */

namespace Serif;

const RELATED_POSTS_NAMESPACE = 'serif/related-posts';

/**
 * Whether a parsed block is the Related Posts query block.
 *
 * @param array $parsed_block Parsed block array.
 * @return bool
 */
function is_related_posts_query( $parsed_block ) {
	return 'core/query' === ( $parsed_block['blockName'] ?? '' )
		&& RELATED_POSTS_NAMESPACE === ( $parsed_block['attrs']['namespace'] ?? '' );
}

/**
 * Whether the Related Posts query block is currently rendering.
 *
 * `query_loop_block_query_vars` receives the inner Post Template block, which
 * has no access to the parent Query block's `namespace`, so the namespaced
 * block flags its render window and the query-vars filter checks the flag.
 *
 * @param bool|null $set Pass a bool to update the flag; null to read it.
 * @return bool
 */
function rendering_related_posts( $set = null ) {
	static $rendering = false;
	if ( null !== $set ) {
		$rendering = (bool) $set;
	}
	return $rendering;
}

/**
 * Flag the start of the Related Posts query block's render.
 *
 * @param string|null $pre_render   Pre-rendered content (unused).
 * @param array       $parsed_block Parsed block array.
 * @return string|null
 */
function related_posts_pre_render( $pre_render, $parsed_block ) {
	if ( is_related_posts_query( $parsed_block ) ) {
		rendering_related_posts( true );
	}
	return $pre_render;
}
add_filter( 'pre_render_block', __NAMESPACE__ . '\\related_posts_pre_render', 10, 2 );

/**
 * Flag the end of the Related Posts query block's render.
 *
 * @param string $content      Rendered block content.
 * @param array  $parsed_block Parsed block array.
 * @return string
 */
function related_posts_after_render( $content, $parsed_block ) {
	if ( is_related_posts_query( $parsed_block ) ) {
		rendering_related_posts( false );
	}
	return $content;
}
add_filter( 'render_block_core/query', __NAMESPACE__ . '\\related_posts_after_render', 10, 2 );

/**
 * Restrict the Related Posts loop to the current post's categories and exclude the post itself.
 *
 * @param array $query Query vars passed to WP_Query.
 * @return array
 */
function related_posts_query_vars( $query ) {
	if ( ! rendering_related_posts() ) {
		return $query;
	}

	$post_id = get_the_ID();
	if ( ! $post_id ) {
		return $query;
	}

	$query['post__not_in'] = array( $post_id );

	$categories = wp_get_post_categories( $post_id );
	if ( ! empty( $categories ) ) {
		$query['category__in'] = $categories;
	}

	/**
	 * Filters the query vars used by the Related Posts pattern.
	 *
	 * @param array $query   Query vars passed to WP_Query.
	 * @param int   $post_id ID of the post being viewed.
	 */
	return apply_filters( 'serif_related_posts_query_vars', $query, $post_id );
}
add_filter( 'query_loop_block_query_vars', __NAMESPACE__ . '\\related_posts_query_vars' );
