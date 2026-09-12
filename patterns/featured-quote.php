<?php
/**
 * Title: Featured Quote
 * Slug: serif/featured-quote
 * Categories: serif-layout, text
 * Block Types: core/quote
 * Viewport width: 1280
 *
 * A large centred quotation with a byline: name and role.
 *
 * @package Serif
 */

?>
<!-- wp:group {"align":"wide","style":{"spacing":{"padding":{"top":"48px","bottom":"48px"}}},"layout":{"type":"constrained","contentSize":"720px"}} -->
<div class="wp-block-group alignwide" style="padding-top:48px;padding-bottom:48px">
	<!-- wp:quote {"className":"is-style-pull-quote"} -->
	<blockquote class="wp-block-quote is-style-pull-quote">
		<!-- wp:paragraph -->
		<p><?php esc_html_e( 'The difference between the almost right word and the right word is really a large matter — it is the difference between the lightning bug and the lightning.', 'serif' ); ?></p>
		<!-- /wp:paragraph -->
	</blockquote>
	<!-- /wp:quote -->

	<!-- wp:group {"style":{"spacing":{"blockGap":"2px","margin":{"top":"24px"}}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
	<div class="wp-block-group" style="margin-top:24px">
		<!-- wp:paragraph {"align":"center","fontSize":"small","style":{"typography":{"fontWeight":"600"}},"fontFamily":"ibm-plex-sans"} -->
		<p class="has-text-align-center has-ibm-plex-sans-font-family has-small-font-size" style="font-weight:600"><?php esc_html_e( 'Mark Twain', 'serif' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph {"align":"center","fontSize":"small","textColor":"muted","fontFamily":"ibm-plex-sans"} -->
		<p class="has-text-align-center has-muted-color has-text-color has-ibm-plex-sans-font-family has-small-font-size"><?php esc_html_e( 'Letter to George Bainton, 1888', 'serif' ); ?></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
