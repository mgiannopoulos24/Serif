<?php
/**
 * Title: No Search Results
 * Slug: serif/no-search-results
 * Categories: serif-layout, text
 * Inserter: false
 *
 * Shown inside the search template's "No results" block.
 *
 * @package Serif
 */

?>
<!-- wp:paragraph {"textColor":"secondary"} -->
<p class="has-secondary-color has-text-color"><?php esc_html_e( 'No results found. Try a different search term.', 'serif' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:search {"label":"<?php esc_attr_e( 'Try another search', 'serif' ); ?>","showLabel":false,"placeholder":"<?php esc_attr_e( 'Search…', 'serif' ); ?>","buttonText":"<?php esc_attr_e( 'Search', 'serif' ); ?>"} /-->
