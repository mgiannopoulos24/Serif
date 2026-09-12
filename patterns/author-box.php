<?php
/**
 * Title: Author Box
 * Slug: serif/author-box
 * Categories: posts, serif-layout
 * Block Types: core/post-author
 * Viewport width: 720
 *
 * @package Serif
 */

?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"24px","right":"24px","bottom":"24px","left":"24px"},"margin":{"top":"48px"}},"border":{"top":{"color":"var:preset|color|border","width":"1px"},"bottom":{"color":"var:preset|color|border","width":"1px"}}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
<div class="wp-block-group" style="border-top-color:var(--wp--preset--color--border);border-top-width:1px;border-bottom-color:var(--wp--preset--color--border);border-bottom-width:1px;margin-top:48px;padding-top:24px;padding-right:24px;padding-bottom:24px;padding-left:24px">
	<!-- wp:avatar {"size":80,"style":{"border":{"radius":"50%"}}} /-->

	<!-- wp:group {"style":{"spacing":{"blockGap":"8px"}},"layout":{"type":"flex","orientation":"vertical"}} -->
	<div class="wp-block-group">
		<!-- wp:paragraph {"fontSize":"small","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.05em"}},"textColor":"muted"} -->
		<p class="has-muted-color has-text-color has-small-font-size" style="letter-spacing:0.05em;text-transform:uppercase"><?php esc_html_e( 'Written by', 'serif' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:post-author-name {"isLink":true,"fontSize":"large","style":{"typography":{"fontWeight":"700"}}} /-->

		<!-- wp:post-author-biography {"fontSize":"small"} /-->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
