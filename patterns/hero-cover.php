<?php
/**
 * Title: Hero Cover
 * Slug: serif/hero-cover
 * Categories: serif-hero, banner
 * Block Types: core/cover
 * Viewport width: 1280
 *
 * A full-width cover with headline, standfirst and a call to action.
 * Swap the overlay for a background image in the editor.
 *
 * @package Serif
 */

?>
<!-- wp:cover {"overlayColor":"dark","dimRatio":100,"isUserOverlayColor":true,"minHeight":60,"minHeightUnit":"vh","contentPosition":"center left","align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-cover alignfull has-custom-content-position is-position-center-left" style="min-height:60vh"><span aria-hidden="true" class="wp-block-cover__background has-dark-background-color has-background-dim-100 has-background-dim"></span><div class="wp-block-cover__inner-container">
	<!-- wp:group {"style":{"spacing":{"blockGap":"24px"}},"layout":{"type":"constrained","contentSize":"720px","justifyContent":"left"}} -->
	<div class="wp-block-group">
		<!-- wp:paragraph {"fontSize":"small","textColor":"white","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.08em"}}} -->
		<p class="has-white-color has-text-color has-small-font-size" style="letter-spacing:0.08em;text-transform:uppercase"><?php esc_html_e( 'Featured essay', 'serif' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:heading {"level":2,"textColor":"white","fontSize":"xxx-large"} -->
		<h2 class="wp-block-heading has-white-color has-text-color has-xxx-large-font-size"><?php esc_html_e( 'Writing that takes its time', 'serif' ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"textColor":"white","fontSize":"large"} -->
		<p class="has-white-color has-text-color has-large-font-size"><?php esc_html_e( 'Essays, reporting and notes on typography, tools and the craft of long-form — published when there is something worth saying.', 'serif' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:buttons -->
		<div class="wp-block-buttons">
			<!-- wp:button -->
			<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#"><?php esc_html_e( 'Start reading', 'serif' ); ?></a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->
	</div>
	<!-- /wp:group -->
</div></div>
<!-- /wp:cover -->
