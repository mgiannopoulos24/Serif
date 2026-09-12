# Serif — Build TODO

## Status: P0 + P1 done, boots clean, theme.json is the design system. 1.0.2 released. CI green. (`serif-child/` stays nested by decision; excluded from the bundle.)

---

## Priority Fixes (from review, 2026-09-12)

Do these **in order, before building anything new**. P0 breaks the site, P1 is structurally wrong, P2 is cleanup.

### P0 — Breaks on first boot — DONE 2026-09-12

- [x] **Missing patterns referenced by templates** — created `patterns/article-grid.php`, `patterns/author-box.php`, `patterns/related-posts.php`. Related posts filter to the current post's categories via a `namespace`-scoped `query_loop_block_query_vars` filter in `inc/block-patterns.php`.
- [x] **`wp:widget-area` is not a block** — `patterns/footer.php` rewritten with site-title/tagline, vertical nav, social links, dynamic copyright.
- [x] **Enqueue the real stylesheet** — `inc/scripts.php` and `serif-child/functions.php` now load `assets/css/style.min.css`; `add_editor_style` too.
- [x] **`assets/js/navigation.js` doesn't exist** — enqueue removed. Core Navigation block ships its own overlay menu; re-add only if Phase 7 needs it.
- [x] **Fatal with Yoast/RankMath active** — `functions.php` guards the integration requires with `file_exists()`.
- [x] **`blank` template declared but missing** — `templates/blank.html` created.
- [x] **`package.json` i18n scripts** — paths now `/themes/serif`.
- [x] **Templates/parts omitted serialized block HTML** (found during P0) — every static-save block (`group`, `query`, `columns`, `cover`, `comments`, `social-links`) needs its `<div class="wp-block-…">` markup between the delimiters or layout classes land on the wrong element and the Site Editor flags the block invalid. Also `query-pagination` / `comments-pagination` were self-closing (render nothing without child blocks). All of `templates/*.html` and `parts/*.html` rewritten.
- [x] **Pattern cache** — WP caches `patterns/` unless `WP_DEVELOPMENT_MODE` is `theme`. Added to `.wp-env.json`; new pattern files were invisible before this.
- [x] **Boot it** — verified home, single, page, category, tag, search (+ no results), 404, pagination (`/page/2/`), comments. No `debug.log` created with `WP_DEBUG_LOG` on.

Done incidentally while rewriting templates (were P1/P2): `home.html` uses template parts; `entry-header` has `minHeightUnit: vh`; empty `social-links` dropped from `entry-footer`; footer pattern no longer sets `tagName` (header pattern still does — see P1).

### P1 — Structurally wrong — DONE 2026-09-12

- [x] **`theme.json` is the single source of truth for design tokens.**
  - SCSS palette, `prefers-color-scheme` block and `@font-face` rules removed. SCSS now only holds what theme.json cannot express (box-sizing, reduced-motion, `::selection`, `:focus-visible`) plus non-design vars (`--serif-transition`, z-indexes, focus ring). Compiled CSS is 948 bytes.
  - Dark mode = user-selectable style variation (`styles/dark.json`). No OS-level `prefers-color-scheme` — the Font Control plugin (Phase 9) can still swap presets at runtime by overriding `--wp--preset--color--*`.
  - Custom colours / gradients / duotone / font sizes disabled; core default palette, gradients, duotone and font sizes hidden (`settings.color.default*`, `typography.defaultFontSizes`). Users pick from Serif presets only.
- [x] **Variable font weight** — Plex Sans face declared `100 700`.
- [x] **Navigation block** — `patterns/header.php` rewritten: logo + title left, `wp:navigation` (`overlayMenu: mobile`) right; no `tagName` on the pattern so there's a single `<header>`.
- [x] **`templates/index.html`** — query loop (done in P0).
- [x] **`contentSize: 720px`, `wideSize: 1100px`.**
- [x] **Style variations** — `styles/{dark,sepia,high-contrast}.json` now redefine `settings.color.palette` (same 12 slugs). Per-block overrides gone; only `button` text (and `link:hover` in high-contrast) are overridden for contrast. All text/bg, link/bg, muted/bg, button pairs verified ≥ AA; high-contrast is AAA throughout.
- [x] **`inc/setup.php` slimmed** to `custom-logo` only — everything else is core-default for block themes or lives in theme.json. `register_nav_menus`, `$content_width`, `serif_content_width` filter removed.
- [x] **`wp-block-styles` support + `wp-block-library-theme` dequeue** both removed. `inc/scripts.php` enqueues use `SERIF_VERSION`.
- [x] Hooks policy (see conversation): no action hooks in a block theme; filters only where PHP makes a decision. Added `serif_related_posts_query_vars`. Removed the unused `serif()` wrapper.

### P2 — Cleanup / consistency — DONE 2026-09-12

- [x] **Bundle** — `bun run bundle` runs `scripts/bundle.sh`: builds CSS first, copies only what WordPress reads (no SCSS/JS sources, no empty dirs, `screenshot.png`/`readme.txt`/`languages/` included when present) → `build/serif.zip`.
- [x] **`build:js`** — valid esbuild command targeting `assets/js/src/theme.js` → `assets/js/theme.min.js`. Not wired into `build` yet: no JS is needed while the core Navigation block handles the mobile menu. Enable in Phase 7 only if a real need appears.
- [x] **License** — GPL-3.0-or-later everywhere (`LICENSE` file was already v3; `style.css` headers updated to match).
- [x] **`Requires PHP: 8.1`** — `phpcs.xml` `testVersion` set to `8.1-` and the codebase passes.

---

## Phase 1 — Theme Foundation

### [X] 1.1 Fix `style.css`
- Added proper theme header: Theme Name, URI, Description, Version, Author, License, Text Domain, Tags
- Text Domain already set to `serif`
- Added `License: GNU General Public License v2 or later`
- Added `License URI: https://www.gnu.org/licenses/gpl-2.0.html`
- Fixed `Requires at least PHP` → `Requires PHP` (correct header format)

### [X] 1.2 Fix `functions.php` bugs
- Line 17: renamed second `SERIF_THEME_DIR` to `SERIF_THEME_URI`
- Completed the wrapper function skeleton (`serif()` wrapper for `\Serif\setup()`)
- Added `require_once` loads for all `inc/` files

### [X] 1.3 Fix `.wp-env.json`
- Change `afterStart` from `coldwell-banker` to `serif`

### [X] 1.4 Fix `phpcs.xml`
- Change `text-domain` from `dn` to `serif`

### [X] 1.5 `screenshot.png` (+ `serif-child/screenshot.png`)
- Real 1200×900 Playwright captures of the seeded home page via `bun run screenshot` (`scripts/screenshot.mjs`). Child gets a small "Child theme" badge. Regenerate after design changes.
- Also: `assets/images/logo.png` (simple S mark) used by the seed as site logo.

### [X] 1.6 Dev seed script
- `scripts/seed.sh` (runs inside the cli container). `bun run seed` / `bun run seed:reset`; also runs on `bun run start`. Creates settings, 4 categories, 5 tags, 11 posts with featured images, About/Contact/Patterns pages, threaded comments, a `wp_navigation` menu and a site logo. Idempotent via `serif_seeded` option.
---

## Phase 2— `theme.json` (Core Block Theme Config)

### [X] 2.1 Create `theme.json` with:

| Section | Detail | Status |
|---|---|---|
| **Fonts** | IBM Plex Serif (body), IBM Plex Sans (headings/UI) — local woff2 files only | 3 serif faces (400, 400i, 700), 1 variable sans face (100–700) |
| **Color palette** | 12 colors + 4 duotone filters | warm editorial: paper `#faf8f5`, ink `#1c1b1a`, oxblood `#8b3a2a` primary, ink-blue `#2f5d8a` accent. All text pairs ≥ AA. Decorative `border` is 1.2:1 — use `muted` for form-input borders (WCAG 1.4.11) |
| **Font sizes** | small→xxx-large (14px–48px) | fluid typography enabled |
| **Layout** | `contentSize: 720px`, `wideSize: 1100px` | done |
| **Spacing** | `padding`, `margin` units: px, em, rem, vh, vw | blockGap enabled |
| **Blocks** | All 13 requested blocks | settings + default styles for each |
| **Appearance Tools** | border, color, typography, spacing, position | sticky positioning enabled |
| **Style Variations** | Default (base), Dark, Sepia, High Contrast | palette-based, contrast verified |

---

## Phase 3 — PHP Includes (`inc/`)

### [X] 3.1 `inc/setup.php`
- `custom-logo` only. Core adds the rest for block themes; presets-only colours/sizes are `theme.json` settings.

### [X] 3.2 `inc/scripts.php`
- Enqueue `assets/css/style.min.css` (fixed in P0)
- Google Fonts skipped — using local woff2 files via `theme.json` `fontFace` (user chose local-only)
- `navigation.js` enqueue removed — core Navigation block handles mobile; revisit in Phase 7 if needed
- `add_editor_style( 'assets/css/style.min.css' )` (fixed in P0)
- (removed) `wp-block-library-theme` dequeue — core loads block styles on demand for block themes

### [X] 3.3 `inc/block-styles.php`
- Registered with `register_block_style()` + `style_data` (theme.json-shaped, WP 6.6+ — `Requires at least` bumped to 6.6). CSS is generated by WP and only loaded when the block is on the page.
  - `core/quote`: `large`, `pull-quote` (core provides `default`, `plain`)
  - `core/separator`: `narrow`, `thick` (core provides `default`, `wide`, `dots`)
  - `core/cover`: `gradient`, `overlay-dark`
  - `core/image`: `shadow`, `frame` (core provides `default`, `rounded`)
- Pattern categories `serif-layout`, `serif-hero`, `serif-media` registered; existing patterns tagged `serif-layout`.
- Form inputs (`core/search`, `core/post-comments-form`) get a `muted` border via theme.json `css` — closes the WCAG 1.4.11 note from the palette work.
- Test post "Block styles test" (`/?p=13`) in wp-env exercises every style.

### [X] 3.4 Patterns (`patterns/`, auto-registered)
- `inc/block-patterns.php` holds only the related-posts query filter (+ `serif_related_posts_query_vars`).
  - [x] `serif/header`, `serif/footer` (used by template parts)
  - [x] `serif/article-grid` (404), `serif/author-box`, `serif/related-posts` (single)
  - [x] `serif/hero-cover` — full-width cover, kicker + h1 + standfirst + button (front page)
  - [x] `serif/featured-quote` — pull-quote style + byline
  - [x] `serif/newsletter-cta` — boxed CTA; button links out, swap for a plugin form block
  - [x] `serif/table-of-contents` — boxed ordered anchor list
- Seeded page `/patterns/` shows every pattern; `/kitchen-sink/` shows every block style.

### [X] 3.5 / 3.6 Breadcrumbs — Yoast SEO and Rank Math
- No block is registered by the theme (theme-check: `register_block_type()` is plugin territory; a separate "core" plugin isn't worth it for one block).
- `inc/integrations/breadcrumbs.php` — `hook_before_title( $block )` places a block before `core/post-title` in `entry-header` (single) and `page` via the Block Hooks API; users can move/remove it in the Site Editor.
- `inc/integrations/yoast.php` — hooks Yoast's own `yoast-seo/breadcrumbs` block; `yoast-seo-breadcrumbs` theme support; wrapper `<nav class="serif-breadcrumbs" aria-label>` + MDL chevron via Yoast's output filters; `aria-current` on the last crumb.
- `inc/integrations/rankmath.php` — Rank Math (free) has no block, so it hooks a `core/shortcode` block and fills it with `[rank_math_breadcrumb]` via `hooked_block_core/shortcode`; wrapper/class via `rank_math/frontend/breadcrumb/args`; separator drawn in CSS (Rank Math `wp_kses_post()`s it). Note: Rank Math's whole front end is off until its setup wizard is completed or `rank_math_registration_skip` is set.
- `assets/scss/components/_breadcrumbs.scss` — one style for both; inherits white inside the entry-header cover.
- Verified with each plugin active and with neither (renders nothing, no notices).

### [~] 3.7 `inc/deprecated.php` — not created
- Nothing is deprecated at 1.0. Policy when something is: put the shim in `inc/deprecated.php` with `_deprecated_function()`, load it last from `functions.php`, remove after two minor versions.

---

## Phase 4 — Templates (`templates/`)

Each file is HTML with block markup. All use `<!-- wp:template-part -->` for header/footer.

### [X] `templates/index.html`
Query loop fallback with `inherit: true`, no-results, pagination.

### [X] `templates/home.html`
Blog posts index. Uses `query-loop` with post-title, featured-image, excerpt, post-meta. Pagination via `query-pagination`.

### [X] `templates/front-page.html`
Full-bleed cover (hero image = the front page's featured image, dark overlay fallback) with site title, tagline, "Start reading" → `#featured`; three latest posts in a grid; newsletter CTA. Seed sets Home as front page and Journal as the posts page so `home.html` is the paginated list at `/journal/`.

### [X] `templates/single.html`
Single post layout. Entry header (cover with title + meta), content, entry footer, author box, related posts, comments.

### [X] `templates/page.html`
Page layout. Title, featured image, content. Clean, minimal.

### [X] `templates/archive.html`
Archive listing. Header with title/description, post grid, pagination.

### [X] `templates/search.html`
Search results. Search form, results grid, "no results" message, pagination.

### [X] `templates/404.html`
Full-width "Page Not Found" with search form and suggested links.

### [X] `templates/blank.html`
No header/footer. `<!-- wp:post-content -->` only.

---

## Phase 5 — Template Parts (`parts/`)

### [X] `parts/header.html`
Wraps `patterns/header.php`: logo + title, Navigation block with mobile overlay.

### [X] `parts/footer.html`
Wraps `patterns/footer.php`: site title/tagline, vertical nav, social links, copyright.

### [X] `parts/post-meta.html`
Author avatar, author name, date, category list, tag list.

### [X] `parts/entry-header.html`
Cover block with featured image, dimRatio 50%, minHeight 60 — title + post-meta overlaid.

### [X] `parts/entry-footer.html`
Tags, social links, prev/next post navigation.

### [X] `parts/comments.html`
Comment list + form wrapped in block markup.

### [X] Mobile menu (no `off-canvas.html` needed)
- Core Navigation overlay, styled in `assets/scss/layout/_header.scss`: brand row (logo + title) where the header was, MDL close icon at the hamburger position, 26–34px Plex Sans items with hairlines and a short stagger, search + tagline pinned to the bottom. Overlay colours set on the block (`overlayBackgroundColor`/`overlayTextColor`).
- `inc/block-filters.php` (replaces `template-tags.php`): swaps Navigation/Search icons for Material Design Icons Light (`assets/images/icons/`, Apache 2.0 — see `CREDITS.md`) and injects the overlay brand/footer.
- Stylesheet is cache-busted with `filemtime`.

---

## Phase 6 — Styles (SCSS/CSS) — DONE 2026-09-12

Principle: `theme.json` is the design system; SCSS holds only what it cannot express (descendant selectors, specificity fights with core block CSS, pseudo-elements, media queries). Compiled output ≈ 15 KB.

### [X] `assets/scss/style.scss` — entry
```scss
@use 'abstracts/variables';   // non-design vars: transition, z-index, focus ring
@use 'base/reset';            // box-sizing, smooth scroll, reduced motion
@use 'base/typography';       // ::selection, :focus-visible
@use 'base/accessibility';    // forced-colors, skip-link
@use 'layout/header';         // mobile overlay menu, header search trigger, MDL icons
@use 'layout/entry';          // meta colours inside the single-post cover
@use 'blocks/paragraph';      // drop cap (core default is 8.4em/weight 100)
@use 'blocks/table';          // hairline cells (core's `border:1px solid` out-ranks theme.json)
@use 'components/search';     // <dialog> search overlay, open/close animations
@use 'components/breadcrumbs';
```

### [X] What moved into `theme.json` instead (this pass)
- Heading margins (`1.6em` above / `0.6em` below); `cite` under quote/pullquote (small sans, muted)
- `core/code` + `core/preformatted`: `light` background, mono stack, padding, radius
- Comments: title `x-large`; author name sans 600; date muted; reply link sans; hairline between comments (markup); `core/avatar` replaces the deprecated `comment-author-avatar` block
- Pagination: sans 600, current page as ink pill
- Link underline thickness/offset

### [X] Markup alignment fixes
- Section headings in `article-grid`, `related-posts`, archive title/description → `alignwide` to line up with their grids
- Footer pattern: hairline top border, 50/25/25 columns, tighter nav gap, muted sans copyright, 24px logos-only social icons
- Entry footer: "Tagged …" prefix

### [~] Dropped as classic-theme concepts
- `_layout.scss` (`.site-container` etc.), `_components.scss` (back-to-top, hamburger — core Navigation does it), `_dark-mode.scss` (style variation instead), `.screen-reader-text` (core ships it)

### [X] Build pipeline
- `bun run build:css` → compressed `assets/css/style.min.css`; `build:css:dev` / `watch:css` → expanded with embedded source map. Stylesheet is cache-busted with `filemtime`.

## Phase 7 — JavaScript — DONE

### [X] `assets/js/src/theme.js` → `assets/js/theme.min.js` (esbuild, `bun run build:js`)
- The only JS in the theme; self-hosted, deferred, no CDN.
- Search overlay: native `<dialog>` rendered by `inc/search.php`, opened from the header icon or `/`. Focus trap/Esc/inert come from the platform; animated open *and* close (Esc intercepted via `cancel`). No-JS fallback: core's expanding search field.
- Mobile menu open/close fade is CSS-only (`transition: display … allow-discrete` + `@starting-style`); core's own `fill-mode: forwards` fade-in is disabled so the close transition can run.
- No navigation JS — core Navigation overlay handles mobile.

### [X] Other JS — nothing further needed
- Smooth anchor scroll: CSS (`html { scroll-behavior: smooth }`, reduced-motion safe).
- Skip-link: core injects it for block themes; styled in `base/_accessibility.scss`.
- Back-to-top: not planned.

### [X] Build pipeline
- `bun run build` = `build:css` + `build:js` (esbuild, ES2020, minified → `assets/js/theme.min.js`). `inc/scripts.php` enqueues it deferred, cache-busted with `filemtime`; `scripts/bundle.sh` ships only the built file.

---

## Phase 8 — Font Assets — DONE (self-hosted, no CDN)

### [X] IBM Plex Serif / IBM Plex Sans — self-hosted only
- `assets/fonts/IBMPlexSerif-{Regular,Italic,Bold}.woff2`, `assets/fonts/IBMPlexSans.woff2` (variable, 100–700), downloaded from Google Fonts (OFL — see `CREDITS.md`).
- Declared once, in `theme.json` `fontFace` (`font-display: fallback` is emitted by WP). No `@font-face` in SCSS.

### [~] Google Fonts strategy — dropped
- No CDN. Self-hosting is faster (no third-party connection), GDPR-clean, and works offline / in wp-env. Nothing to preconnect.
- Subsetting (Latin + Greek) remains a Phase 12 option if size matters.

---

## Phase 9 — Custom Plugin: Serif ReadTime & Font Control — MOVED

Now its own repo: `../Serif-ReadTime-Font-Control` (github.com/mgiannopoulos24/Serif-ReadTime-Font-Control). Full plan and current state in its `HANDOFF.md`.

Theme-side prerequisites are done:
- `theme.json` font sizes are **rem** so the widget's root-scaling works.
- The plugin inserts `serif/read-time` after `core/post-date` in `parts/post-meta.html` via Block Hooks (guarded by `get_template() === 'serif'`) — nothing to add in the theme.
- To run both in one wp-env, add `"../Serif-ReadTime-Font-Control"` to `.wp-env.json` `plugins` (and avoid the port-8888 clash with the plugin's own env).

---

## Phase 10 — Testing

### [~] 10.1 PHPUnit setup — moved to the plugin repo (the theme has no PHP logic worth unit-testing; templates/patterns are covered by Playwright)
- Add `phpunit/phpunit:^11` + `yoast/phpunit-polyfills` to `composer.json`
- Create `phpunit.xml` with tests directory
- Bootstrap file that loads WP test environment (for integrated tests) or mocks (for unit tests)

### [~] 10.2 ReadTime unit tests — moved to the plugin repo
- `tests/phpunit/ReadTimeTest.php`
- Test cases:
  - Plain text: "Hello world" → "< 1 min"
  - 400 words → "2 min read"
  - Shortcode in content is stripped
  - Gutenberg blocks stripped properly
  - Media attachment metadata excluded
  - Empty content → "< 1 min"
  - Custom WPM argument (e.g., 100 WPM)
  - Long text with HTML tags

### [X] 10.3 Playwright E2E setup
- `tests/e2e-pw/playwright.config.ts`: projects `desktop` (Desktop Chrome), `mobile` (Pixel 7), and `variations` (runs after the others because it switches global styles). Reuses the running wp-env or starts it. HTML report in `tests/e2e-pw/playwright-report/` (`bun run test:e2e:report`).
- Specs are **plain JS with JSDoc** — Playwright runs under Bun here (no Node), which bypasses its TS transform for test files. Shared `helpers.ts` is TypeScript. Also: inline `type` import modifiers break under Bun; use `import type`.
- `bun run test:e2e` (all), `bun run test:a11y` (axe specs only).

### [~] 10.4 Font Widget E2E tests — moved to the plugin repo

### [X] 10.5 Accessibility tests (axe-core, WCAG 2.2 AA + best-practice)
- `specs/accessibility.spec.js`: every template route (front page, journal + page 2, single, kitchen sink, page, patterns page, category, tag, search, no-results, 404) on desktop and mobile, plus the open mobile menu and the open search overlay.
- `specs/variations.spec.js`: front page, single, kitchen sink and archive under Default / Dark / Sepia / High Contrast, applied for real via `scripts/set-style-variation.php` (wp-cli), restored to default afterwards.
- Fixed to get to zero violations: distinct `aria-label` per search landmark (from the block's `label`, via `inc/block-filters.php`); `h1` on `home.html` ("Journal") and a screen-reader `h1` on `index.html`; front-page hero moved inside `<main>` (was outside every landmark); `serif/hero-cover` uses `h2` (patterns must not add an `h1`); kitchen-sink seed no longer includes an `h1`.

### [X] 10.6 Template rendering E2E
- `specs/templates.spec.js`: for every route — one `header`/`footer`/`main`/`h1`, header nav, skip link → `#wp--skip-link--target`, no PHP notice text, no console errors, no failed requests for theme assets; plus per-template structure (hero + 3 featured + CTA; 6 journal rows + pagination; cover header + meta + author box + related + 3 comments with avatars + form; related posts exclude the current post; archive title; search results and no-results fallback; 404 status + recent grid; every registered block style emits scoped CSS).
- `specs/interactions.spec.js`: mobile menu (brand row, 44px targets, focus inside, Esc closes and restores focus, overlay search submits); search overlay (opens with focus in field, core inline field stays collapsed, focus never escapes to page content, Esc/close button/backdrop close and restore focus, `/` shortcut, Enter submits); "Start reading" smooth-scrolls to `#featured`.
- Result: 88 passed, 8 skipped (viewport-specific), 0 axe violations.

---

## Phase 11 — i18n / l10n — POT done; Greek translation pending

### [X] `languages/serif.pot`
- `bun run makepot` = plain `wp i18n make-pot . languages/serif.pot --domain=serif --exclude=…`. 102 strings: PHP (patterns, inc/, blocks), `theme.json` names (colours, sizes, duotones, fonts, template parts, custom templates), style-variation titles, `block.json`.
- Caveat: while `serif-child/` sits inside the parent, `make-pot` reads *its* `style.css` for the theme-header entries (Theme Name/Description show "Serif Child"). Everything else is correct. Fix = move the child theme out to a sibling folder (WordPress needs it there anyway).
- Template text moved into PHP patterns so it's translatable: `serif/not-found` (404), `serif/no-results` (journal/archive/index/front-page), `serif/no-search-results`, `serif/search-form`. Editorial headings ("Journal", "Featured", "Start reading") stay in templates on purpose — site owners edit them in the Site Editor.
- `inc/setup.php` now calls `load_theme_textdomain( 'serif', …/languages )`.

### [ ] L10n — Greek (owner will translate)
- Workflow: `bun run makepot` → copy `languages/serif.pot` to `languages/el.po` and translate → `bun run makemo` (creates `el.mo`; WordPress reads `.mo` directly). `inc/setup.php` already loads `/languages`. Test with `wp site switch-language el`.
- Known: **IBM Plex Serif has no Greek glyphs** (Plex Sans does). Greek body copy falls back to Georgia. Options: accept the mix; or switch the body face to a Greek-capable OFL serif (Literata, Noto Serif, Source Serif 4).

### Seed
- First run on a fresh install now deletes WordPress's stock "Hello world!" / Sample Page / Privacy Policy so they never top the feed.

---

## Phase 12 — Performance — DONE 2026-09-12

Measured with Playwright (`scratchpad/perf.mjs`-style: resource timing, LCP/CLS observers) on the seeded site:

| Page | Requests | Transfer | of which images (content) | Fonts | LCP | CLS |
|---|---|---|---|---|---|---|
| Front page | 19 | ~1.0 MB | 717 KB | 272 KB | ~450 ms (hero image) | 0 |
| Single post | 26 | ~610 KB | 164 KB | 375 KB (+italic, bold) | ~540 ms | 0 |
| Journal | 19 | ~650 KB | 313 KB | 272 KB | ~620 ms | 0 |

### [X] Asset optimization — nothing left to do
- No render-blocking JS: `theme.min.js` (1.4 KB) is deferred; core Navigation/Search view scripts are modules; comment-reply is async.
- Block CSS: core loads per-block styles on demand (block theme default). Inline `<style>` is ~57 KB uncompressed / ~10 KB gzipped: 20 KB `global-styles-inline-css` (theme.json) + core block stylesheets that WP inlines for every block with theme.json styles. Accepted — the alternative is moving block styling out of theme.json.
- Hero/featured images get `fetchpriority="high"` + `sizes` from core; below-fold images lazy-load. No emoji script in output.
- Critical-CSS inlining not worth it: the theme stylesheet is 16 KB and everything else is already inline.

### [X] Font optimization
- `font-display: swap` on all four faces (theme.json `fontDisplay`; WP's default was `fallback`).
- Preload Plex Sans (variable) and Plex Serif Regular via `wp_preload_resources` (`inc/scripts.php`); italic/bold load on demand.
- **Not subsetting**: IBM Plex is OFL with Reserved Font Name "Plex" — a subset is a modified version and can't ship under that name. Options if 222 KB for Plex Sans matters: static 400/600/700 instances (~120 KB, lose the weight axis) or a different family. Greek/Cyrillic glyphs are kept as-is.
- No CDN, no `preconnect` — nothing external.

### [X] Bundle & release
- `bun run bundle` → `build/serif.zip` (~1.4 MB, fonts included), allowlisted contents only.

---

## Phase 13 — Documentation — DONE 2026-09-12

### [X] `readme.txt`
- WP.org format: tags, Requires 6.6 / PHP 8.1, Tested up to 7.1, description, installation, FAQ (no third-party requests, breadcrumbs, Greek fallback font, changing colours), Copyright section with every bundled resource (IBM Plex OFL via Google Fonts, MDL and MDI Apache 2.0, logo/screenshot GPL), changelog.

### [X] `README.md`
- Developer README: principles, getting started (Bun/Composer/wp-env, seed, dev pages), every `bun run` script, structure, how header/search/breadcrumbs/related/front-page/block-styles/cache-busting work, testing, i18n, child theme note, credits.

### [X] `changelog.md` (Keep a Changelog) + `changelog.txt` (plain, shipped in the zip)
- Note: `.gitignore` ignores `CHANGELOG*` (uppercase). The lowercase files are tracked on Linux; on a case-insensitive filesystem they would be ignored — consider removing that line.

---

## 1.0.1 — DONE 2026-09-12

- [x] Recommend the companion plugin: a dismissible admin notice on theme activation (and a "Recommended plugins" note in `readme.txt` / README) pointing to **Serif ReadTime & Font Control** — https://github.com/mgiannopoulos24/Serif-ReadTime-Font-Control. No TGM/auto-install (theme-check disallows); just the name, what it adds (reading time in the post meta row, reading controls), and the link.

## 1.0.2 — DONE 2026-09-12

- [x] High Contrast: hero tagline / Hero Cover text used `light` (black in that variation) → `white`. Rule going forward: text over the cover overlay always uses `white`; `light` is a surface colour.

## Workflows (`.github/workflows/`)

- `ci.yml` — lint (PHPCS → PR annotations, `php -l` on 8.1) · build (fails on stale compiled assets) · e2e (wp-env + Playwright/axe, report artifact on failure).
- `release.yml` — tag `vX.Y.Z` → verify against `style.css` → `bun run bundle` → GitHub Release with the zip and `changelog.txt`'s top entry.
- Issue templates (bug report, feature request) and a PR checklist, modelled on Cooked's.
- Not added: POT auto-refresh (rejected), deploys (no target yet), Dependabot (bun lockfile support is patchy), visual regression (screenshots change with seed images).

---

## Quick Reference: File Tree

```
serif/
├── style.css                     # theme header only (CSS is compiled into assets/css/)
├── functions.php                 # constants + requires inc/*.php
├── theme.json                    # design system: palette, fonts, rem type scale, layout, block styles
├── screenshot.png                # 1200×900, generated by `bun run screenshot`
├── readme.txt                    # WP.org readme (features, FAQ, credits, changelog)
├── README.md                     # developer README
├── changelog.md / changelog.txt  # Keep a Changelog / plain text (shipped in the zip)
├── CREDITS.md                    # bundled third-party assets and licences
├── LICENSE                       # GPL-3.0
├── phpcs.xml                     # WordPress Coding Standards
├── package.json / bun.lock       # scripts and dev dependencies (Bun)
├── composer.json / composer.lock # PHPCS + WPCS
├── .wp-env.json                  # local WordPress (Docker) — maps this dir to wp-content/themes/serif
├── .editorconfig
├── assets/
│   ├── css/style.min.css         # compiled from scss/ (`bun run build:css`)
│   ├── fonts/                    # IBM Plex Serif 400/400i/700, IBM Plex Sans variable (OFL)
│   ├── images/
│   │   ├── logo.png              # "S" mark used by the seed as site logo
│   │   └── icons/                # menu, magnify, chevron-right (MDI Light), close (MDI) — inlined by PHP
│   ├── js/
│   │   ├── src/theme.js          # search-overlay behaviour (the only JS)
│   │   └── theme.min.js          # compiled (`bun run build:js`)
│   └── scss/
│       ├── style.scss            # entry
│       ├── abstracts/_variables.scss   # non-design vars (transition, z-index, focus ring)
│       ├── base/                 # _reset, _typography (::selection, :focus-visible), _accessibility
│       ├── layout/               # _header (mobile overlay, header search), _entry (cover meta colours)
│       ├── blocks/               # _paragraph (drop cap), _table
│       └── components/           # _search (dialog overlay), _breadcrumbs
├── inc/
│   ├── setup.php                 # custom-logo support, load_theme_textdomain
│   ├── scripts.php               # enqueue compiled CSS/JS (cache-busted with filemtime)
│   ├── block-styles.php          # register_block_style() + style_data; pattern categories
│   ├── block-patterns.php        # related-posts query filter (serif_related_posts_query_vars)
│   ├── block-filters.php         # icon swaps; mobile-overlay brand row + search; search landmark names
│   ├── search.php                # <dialog> search overlay markup
│   ├── recommended-plugin.php    # dismissible admin notice for the companion plugin
│   └── integrations/
│       ├── breadcrumbs.php       # Block Hooks placement of the SEO plugin's breadcrumbs
│       ├── yoast.php             # loaded only when Yoast SEO is active
│       └── rankmath.php          # loaded only when Rank Math is active
├── languages/
│   ├── serif.pot                 # `bun run makepot`
│   └── serif-<locale>.po/.mo     # translations (`bun run makemo`)
├── parts/
│   ├── header.html               # wraps patterns/header.php
│   ├── footer.html               # wraps patterns/footer.php
│   ├── post-meta.html            # avatar, author, date, categories, tags (+ reading time via plugin)
│   ├── entry-header.html         # cover with featured image, breadcrumbs (hooked), title, meta
│   ├── entry-footer.html         # tags, prev/next
│   └── comments.html
├── patterns/                     # PHP so strings are translatable
│   ├── header.php  footer.php  hero-cover.php  article-grid.php  author-box.php
│   ├── related-posts.php  featured-quote.php  newsletter-cta.php  table-of-contents.php
│   └── not-found.php  no-results.php  no-search-results.php  search-form.php   # template UI text
├── scripts/                      # dev tooling, not bundled
│   ├── seed.sh                   # demo content (runs in the cli container; also on `bun run start`)
│   ├── screenshot.mjs            # screenshot.png + serif-child/screenshot.png via Playwright
│   ├── bundle.sh                 # build/serif.zip from an allowlist
│   └── set-style-variation.php   # wp eval-file helper used by the variations tests
├── serif-child/                  # minimal child theme: style.css, functions.php, screenshot.png
├── styles/
│   ├── dark.json  sepia.json  high-contrast.json   # palette-based style variations
├── templates/
│   ├── front-page.html  home.html  single.html  page.html  archive.html
│   ├── search.html  404.html  index.html  blank.html
├── tests/
│   └── e2e-pw/
│       ├── playwright.config.ts  # desktop, mobile, variations projects
│       └── specs/                # helpers.ts, accessibility, variations, templates, interactions (.spec.js)
├── plugins/                      # dev-only plugins for wp-env (e.g. envato-theme-check); not bundled
├── .github/                      # issue/PR templates; workflows: ci.yml, release.yml
├── TODO.md  template_list.md     # build plan / template spec
└── build/                        # `bun run bundle` output (gitignored)
```
