<?php
/**
 * Title: Related Posts
 * Slug: serif/related-posts
 * Categories: query, serif-layout
 * Block Types: core/query
 * Viewport width: 1280
 *
 * @package Serif
 */

// The "serif/related-posts" namespace is picked up by inc/block-patterns.php,
// which restricts the loop to the current post's categories.
?>
<!-- wp:group {"align":"wide","style":{"spacing":{"margin":{"top":"48px"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide" style="margin-top:48px">
	<!-- wp:heading {"level":2,"align":"wide"} -->
	<h2 class="wp-block-heading alignwide"><?php esc_html_e( 'Related posts', 'serif' ); ?></h2>
	<!-- /wp:heading -->

	<!-- wp:query {"queryId":11,"query":{"perPage":3,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","sticky":"exclude","inherit":false},"namespace":"serif/related-posts","align":"wide"} -->
	<div class="wp-block-query alignwide">
		<!-- wp:post-template {"layout":{"type":"grid","columnCount":3}} -->
			<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"3/2"} /-->
			<!-- wp:post-title {"level":3,"isLink":true,"fontSize":"large"} /-->
			<!-- wp:post-date {"fontSize":"small"} /-->
		<!-- /wp:post-template -->

		<!-- wp:query-no-results -->
			<!-- wp:paragraph -->
			<p><?php esc_html_e( 'No related posts yet.', 'serif' ); ?></p>
			<!-- /wp:paragraph -->
		<!-- /wp:query-no-results -->
	</div>
	<!-- /wp:query -->
</div>
<!-- /wp:group -->
