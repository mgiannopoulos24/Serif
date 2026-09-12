=== Serif ===
Contributors: mgiannopoulos24
Tags: blog, news, one-column, wide-blocks, block-styles, block-patterns, custom-colors, custom-logo, editor-style, featured-images, full-site-editing, style-variations, threaded-comments, translation-ready, accessibility-ready
Requires at least: 6.6
Tested up to: 7.1
Requires PHP: 8.1
Stable tag: 1.0.1
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

A minimalist, typography-first block theme for writers, journalists and designers. Warm paper, IBM Plex, WCAG 2.2 AA, no third-party requests.

== Description ==

Serif is a full-site-editing theme built for reading. Everything is set in IBM Plex Serif and IBM Plex Sans on a warm paper background, at a 720px measure, with the whole design system living in `theme.json` so every colour, size and spacing choice can be changed in the Site Editor.

* **Editorial layouts** – front page with a full-bleed hero, a paginated journal, single posts with the featured image as a cover header, author box, related posts and threaded comments.
* **Accessibility first** – WCAG 2.2 AA contrast on every palette, visible focus, 44px touch targets, keyboard-complete overlays, `prefers-reduced-motion` and forced-colors support. The theme ships with an axe-core test suite that runs against every template and style variation.
* **Style variations** – Default, Dark, Sepia and High Contrast, each a full palette.
* **Block styles** – quote (large, pull quote), separator (narrow, thick), cover (gradient, dark overlay), image (shadow, frame).
* **Patterns** – header, footer, hero cover, article grid, author box, related posts, featured quote, newsletter call-to-action, table of contents.
* **Search overlay** – a keyboard-friendly search dialog from the header icon or the `/` key.
* **Breadcrumbs** – when Yoast SEO or Rank Math is active, their breadcrumbs are placed above post and page titles automatically and styled to match the theme.
* **No CDN, no tracking** – fonts and icons are bundled; the only script is a 1.5 KB search-dialog helper.

== Recommended plugin ==

Serif works on its own, but it is designed together with **Serif ReadTime & Font Control**, which adds:

* a "minutes to read" estimate, inserted into the post meta row automatically;
* a floating reading-controls widget — text size, line height and contrast — that remembers each reader's choices in their browser.

Get it at https://github.com/mgiannopoulos24/Serif-ReadTime-Font-Control . After activating Serif, an admin notice links to it; dismiss it once and it stays dismissed.

== Installation ==

1. In the WordPress admin go to Appearance → Themes → Add New → Upload Theme, choose `serif.zip` and activate.
2. Set a logo in Appearance → Editor → Header, and create a menu in the Navigation block (until you do, the header lists your pages).
3. Optional: Settings → Reading → set a static front page and a posts page. The front page template shows your page's featured image as the hero; the posts page becomes the paginated journal.
4. Optional: pick a style variation in Appearance → Editor → Styles.

== Frequently Asked Questions ==

= Do I need the companion plugin? =

No. The theme is complete without it. The plugin adds reading time and reading controls; see "Recommended plugin" above.

= Does the theme load anything from third parties? =

No. IBM Plex is served from the theme, icons are inline SVG, and there are no analytics or external scripts.

= Where do breadcrumbs come from? =

From Yoast SEO or Rank Math, whichever is active. The theme styles them and places them above single post and page titles through the Block Hooks API (Yoast's breadcrumbs block, or a Shortcode block with `[rank_math_breadcrumb]` for Rank Math); you can move or remove the block in the Site Editor.

= Why does Greek text use a different serif? =

IBM Plex Serif has no Greek glyphs. Greek falls back to the next font in the stack (Georgia). IBM Plex Sans covers Greek for headings and interface text.

= How do I change the colours? =

Appearance → Editor → Styles → Colors. The twelve palette slots (background, foreground, primary, secondary, muted, border, light, dark, accent, white, black, transparent) drive the whole theme; the style variations are the same twelve slots with different values.

== Copyright ==

Serif WordPress Theme, (C) 2026 Marios Giannopoulos.
Serif is distributed under the terms of the GNU GPL v3 or later.

Serif bundles the following third-party resources:

IBM Plex Serif and IBM Plex Sans
Copyright 2017 IBM Corp.
License: SIL Open Font License 1.1, https://openfontlicense.org
Source: https://fonts.google.com/specimen/IBM+Plex+Serif , https://fonts.google.com/specimen/IBM+Plex+Sans

Material Design Icons Light (menu, magnify, chevron-right)
Copyright Pictogrammers
License: Apache License 2.0, https://www.apache.org/licenses/LICENSE-2.0
Source: https://github.com/Pictogrammers/MaterialDesignLight

Material Design Icons (close, redrawn as a thin stroke)
Copyright Pictogrammers
License: Apache License 2.0, https://www.apache.org/licenses/LICENSE-2.0
Source: https://github.com/Templarian/MaterialDesign

The theme logo mark (assets/images/logo.png) and screenshot were created for this theme and are released under the GPL v3 or later.

== Changelog ==

= 1.0.1 =
* Recommend the Serif ReadTime & Font Control plugin: dismissible admin notice and readme section.

= 1.0.0 =
* Initial release.
