<?php
/**
 * Title: Newsletter Signup
 * Slug: serif/newsletter-cta
 * Categories: serif-layout, call-to-action
 * Viewport width: 1280
 *
 * A boxed call to action. The button links to a signup page; replace it with
 * a form block from your newsletter plugin if you use one.
 *
 * @package Serif
 */

?>
<!-- wp:group {"align":"wide","backgroundColor":"light","style":{"spacing":{"padding":{"top":"48px","right":"32px","bottom":"48px","left":"32px"},"margin":{"top":"48px","bottom":"48px"}},"border":{"radius":"4px"}},"layout":{"type":"constrained","contentSize":"560px"}} -->
<div class="wp-block-group alignwide has-light-background-color has-background" style="border-radius:4px;margin-top:48px;margin-bottom:48px;padding-top:48px;padding-right:32px;padding-bottom:48px;padding-left:32px">
	<!-- wp:heading {"textAlign":"center","level":2} -->
	<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'One letter a month. No more.', 'serif' ); ?></h2>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"align":"center","textColor":"secondary"} -->
	<p class="has-text-align-center has-secondary-color has-text-color"><?php esc_html_e( 'New essays, a few links worth your time, and nothing else. Unsubscribe whenever you like.', 'serif' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
	<div class="wp-block-buttons">
		<!-- wp:button -->
		<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#"><?php esc_html_e( 'Subscribe', 'serif' ); ?></a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->

	<!-- wp:paragraph {"align":"center","fontSize":"small","textColor":"muted"} -->
	<p class="has-text-align-center has-muted-color has-text-color has-small-font-size"><?php esc_html_e( 'No tracking pixels. Your address is never shared.', 'serif' ); ?></p>
	<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
