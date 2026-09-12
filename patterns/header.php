<?php
/**
 * Title: Header
 * Slug: serif/header
 * Categories: header, serif-layout
 * Block Types: core/template-part/header
 * Viewport width: 1280
 *
 * @package Serif
 */

?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"12px","bottom":"12px"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:12px;padding-bottom:12px">
	<!-- wp:group {"align":"wide","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
	<div class="wp-block-group alignwide">
		<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap"}} -->
		<div class="wp-block-group">
			<!-- wp:site-logo {"width":48} /-->
			<!-- wp:site-title {"level":0} /-->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"className":"serif-header__actions","style":{"spacing":{"blockGap":"8px"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
		<div class="wp-block-group serif-header__actions">
			<!-- wp:navigation {"overlayMenu":"mobile","overlayBackgroundColor":"background","overlayTextColor":"foreground","layout":{"type":"flex","justifyContent":"right"}} /-->

			<!-- wp:search {"label":"<?php esc_attr_e( 'Search this site', 'serif' ); ?>","showLabel":false,"placeholder":"<?php esc_attr_e( 'Search…', 'serif' ); ?>","buttonText":"<?php esc_attr_e( 'Search', 'serif' ); ?>","buttonPosition":"button-only","buttonUseIcon":true,"className":"serif-header__search"} /-->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
