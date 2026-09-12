<?php
/**
 * Title: Footer
 * Slug: serif/footer
 * Categories: footer, serif-layout
 * Block Types: core/template-part/footer
 * Viewport width: 1280
 *
 * @package Serif
 */

?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"48px","bottom":"32px"},"margin":{"top":"64px"}},"border":{"top":{"color":"var:preset|color|border","width":"1px"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="border-top-color:var(--wp--preset--color--border);border-top-width:1px;margin-top:64px;padding-top:48px;padding-bottom:32px">
	<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"left":"48px"}}}} -->
	<div class="wp-block-columns alignwide">
		<!-- wp:column {"width":"50%"} -->
		<div class="wp-block-column" style="flex-basis:50%">
			<!-- wp:site-title {"level":0,"fontSize":"large"} /-->
			<!-- wp:site-tagline {"fontSize":"small","textColor":"secondary"} /-->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"width":"25%"} -->
		<div class="wp-block-column" style="flex-basis:25%">
			<!-- wp:navigation {"overlayMenu":"never","fontSize":"small","style":{"spacing":{"blockGap":"8px"}},"layout":{"type":"flex","orientation":"vertical"}} /-->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"width":"25%"} -->
		<div class="wp-block-column" style="flex-basis:25%">
			<!-- wp:social-links {"iconColor":"foreground","iconColorValue":"var(--wp--preset--color--foreground)","className":"is-style-logos-only","style":{"spacing":{"blockGap":{"left":"16px"}}}} -->
			<ul class="wp-block-social-links has-icon-color is-style-logos-only">
				<!-- wp:social-link {"url":"https://x.com/","service":"x"} /-->
				<!-- wp:social-link {"url":"https://www.instagram.com/","service":"instagram"} /-->
				<!-- wp:social-link {"url":"https://www.facebook.com/","service":"facebook"} /-->
				<!-- wp:social-link {"url":"https://www.youtube.com/","service":"youtube"} /-->
			</ul>
			<!-- /wp:social-links -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->

	<!-- wp:paragraph {"align":"center","fontSize":"small","textColor":"muted","fontFamily":"ibm-plex-sans","style":{"spacing":{"margin":{"top":"48px"}}}} -->
	<p class="has-text-align-center has-muted-color has-text-color has-ibm-plex-sans-font-family has-small-font-size" style="margin-top:48px">
		<?php
		/* translators: %s: site title. */
		printf( esc_html__( '&copy; %s. All rights reserved.', 'serif' ), esc_html( get_bloginfo( 'name' ) ) );
		?>
	</p>
	<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
