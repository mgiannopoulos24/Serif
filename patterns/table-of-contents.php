<?php
/**
 * Title: Table of Contents
 * Slug: serif/table-of-contents
 * Categories: serif-layout, text
 * Viewport width: 720
 *
 * A boxed list of anchor links for long articles. Edit the links to match
 * the HTML anchors set on your headings (Advanced > HTML anchor).
 *
 * @package Serif
 */

?>
<!-- wp:group {"backgroundColor":"light","style":{"spacing":{"padding":{"top":"24px","right":"32px","bottom":"24px","left":"32px"},"margin":{"bottom":"32px"}},"border":{"left":{"color":"var:preset|color|primary","width":"4px"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group has-light-background-color has-background" style="border-left-color:var(--wp--preset--color--primary);border-left-width:4px;margin-bottom:32px;padding-top:24px;padding-right:32px;padding-bottom:24px;padding-left:32px">
	<!-- wp:paragraph {"fontSize":"small","fontFamily":"ibm-plex-sans","style":{"typography":{"fontWeight":"600","textTransform":"uppercase","letterSpacing":"0.05em"}}} -->
	<p class="has-ibm-plex-sans-font-family has-small-font-size" style="font-weight:600;letter-spacing:0.05em;text-transform:uppercase"><?php esc_html_e( 'Contents', 'serif' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:list {"ordered":true,"fontFamily":"ibm-plex-sans","fontSize":"small"} -->
	<ol class="wp-block-list has-ibm-plex-sans-font-family has-small-font-size">
		<!-- wp:list-item --><li><a href="#introduction"><?php esc_html_e( 'Introduction', 'serif' ); ?></a></li><!-- /wp:list-item -->
		<!-- wp:list-item --><li><a href="#background"><?php esc_html_e( 'Background', 'serif' ); ?></a></li><!-- /wp:list-item -->
		<!-- wp:list-item --><li><a href="#findings"><?php esc_html_e( 'What we found', 'serif' ); ?></a></li><!-- /wp:list-item -->
		<!-- wp:list-item --><li><a href="#conclusion"><?php esc_html_e( 'Conclusion', 'serif' ); ?></a></li><!-- /wp:list-item -->
	</ol>
	<!-- /wp:list -->
</div>
<!-- /wp:group -->
