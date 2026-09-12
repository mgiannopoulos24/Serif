# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- Recommend the Serif ReadTime & Font Control plugin (admin notice + readme).

## [1.0.0] - 2026-09-12

### Added

- Block theme foundation: `theme.json` v3 as the single design system (warm paper palette, IBM Plex Serif/Sans, rem type scale with fluid sizes, 720px content / 1100px wide).
- Templates: `front-page`, `home`, `single`, `page`, `archive`, `search`, `404`, `index`, custom `blank`.
- Template parts: header, footer, post-meta, entry-header, entry-footer, comments.
- Patterns: header, footer, hero cover, article grid, author box, related posts (category-scoped), featured quote, newsletter signup, table of contents, 404 message, no-results, no-search-results, search form.
- Block styles via `register_block_style()` + `style_data`: quote *large* / *pull quote*, separator *narrow* / *thick*, cover *gradient* / *dark overlay*, image *shadow* / *frame*.
- Style variations: Dark, Sepia, High Contrast (palette-based; contrast verified ≥ AA, High Contrast AAA).
- Mobile navigation overlay (brand row, large targets, search) and a native `<dialog>` search overlay (header icon or `/`), both with animated open/close and reduced-motion support.
- Yoast SEO / Rank Math breadcrumbs auto-placed above post/page titles with the Block Hooks API and styled by the theme (no block registration in the theme).
- Self-hosted fonts and inline Material Design Icons Light; no CDN, no tracking.
- Accessibility: named search landmarks, one `h1` per template, skip link styling, `forced-colors` handling, form-input borders at 3:1.
- Tooling: wp-env with a content seed (`bun run seed`), screenshot generator, SCSS/esbuild builds, PHPCS (WPCS), Playwright + axe-core suite (desktop, mobile, every style variation), `bun run bundle` → installable zip.
- i18n: `languages/serif.pot`; Greek translation.

[1.0.0]: https://github.com/mgiannopoulos24/serif/releases/tag/1.0.0
