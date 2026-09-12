<?php
/**
 * Search overlay.
 *
 * Renders a native <dialog> once per page; assets/js/theme.js opens it from
 * the header search icon (patterns/header.php). Without JS the icon falls
 * back to the core Search block's expanding field.
 *
 * @package Serif
 */

namespace Serif;

/**
 * Output the search dialog in the footer.
 */
function render_search_overlay() {
	if ( is_admin() ) {
		return;
	}

	/* translators: %s: site title. */
	$label = sprintf( __( 'Search %s', 'serif' ), get_bloginfo( 'name' ) );
	$block = '<!-- wp:search {"label":"' . esc_attr( $label ) . '","showLabel":false,"placeholder":"' . esc_attr__( 'Search…', 'serif' ) . '","buttonText":"' . esc_attr__( 'Search', 'serif' ) . '","buttonUseIcon":true,"className":"serif-search__form"} /-->';

	?>
	<dialog class="serif-search" id="serif-search" aria-label="<?php esc_attr_e( 'Search', 'serif' ); ?>">
		<div class="serif-search__panel">
			<div class="serif-search__bar">
				<p class="serif-search__label">
					<?php
					/* translators: %s: site title. */
					printf( esc_html__( 'Search %s', 'serif' ), esc_html( get_bloginfo( 'name' ) ) );
					?>
				</p>
				<button type="button" class="serif-search__close" aria-label="<?php esc_attr_e( 'Close search', 'serif' ); ?>">
					<?php echo icon( 'close', 'serif-icon--close' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from the theme. ?>
				</button>
			</div>
			<?php echo do_blocks( $block ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core block output (form/input/svg), already escaped by the Search block's render. ?>
			<p class="serif-search__hint"><?php esc_html_e( 'Press Esc to close', 'serif' ); ?></p>
		</div>
	</dialog>
	<?php
}
add_action( 'wp_footer', __NAMESPACE__ . '\\render_search_overlay' );
