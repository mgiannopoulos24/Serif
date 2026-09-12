<?php
/**
 * Title: 404 Message
 * Slug: serif/not-found
 * Categories: serif-layout, text
 * Inserter: false
 *
 * Heading, explanation and a search form for the 404 template.
 *
 * @package Serif
 */

?>
<!-- wp:heading {"textAlign":"center","level":1} -->
<h1 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Page not found', 'serif' ); ?></h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center"><?php esc_html_e( 'The page you’re looking for doesn’t exist. It may have moved, or the address may be mistyped.', 'serif' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:search {"label":"<?php esc_attr_e( 'Search for a page', 'serif' ); ?>","showLabel":false,"placeholder":"<?php esc_attr_e( 'Search…', 'serif' ); ?>","buttonText":"<?php esc_attr_e( 'Search', 'serif' ); ?>"} /-->
