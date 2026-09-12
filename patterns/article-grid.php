<?php
/**
 * Title: Article Grid
 * Slug: serif/article-grid
 * Categories: query, serif-layout
 * Block Types: core/query
 * Viewport width: 1280
 *
 * @package Serif
 */

?>
<!-- wp:group {"align":"wide","style":{"spacing":{"margin":{"top":"48px"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide" style="margin-top:48px">
	<!-- wp:heading {"level":2,"align":"wide"} -->
	<h2 class="wp-block-heading alignwide"><?php esc_html_e( 'Recent articles', 'serif' ); ?></h2>
	<!-- /wp:heading -->

	<!-- wp:query {"queryId":10,"query":{"perPage":6,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","sticky":"exclude","inherit":false},"align":"wide"} -->
	<div class="wp-block-query alignwide">
		<!-- wp:post-template {"layout":{"type":"grid","columnCount":3}} -->
			<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"3/2"} /-->
			<!-- wp:post-title {"level":3,"isLink":true,"fontSize":"large"} /-->
			<!-- wp:post-date {"fontSize":"small"} /-->
		<!-- /wp:post-template -->

		<!-- wp:query-no-results -->
			<!-- wp:paragraph -->
			<p><?php esc_html_e( 'No articles yet.', 'serif' ); ?></p>
			<!-- /wp:paragraph -->
		<!-- /wp:query-no-results -->
	</div>
	<!-- /wp:query -->
</div>
<!-- /wp:group -->
