# Serif — Build TODO

## Status: Phase 0 (Scaffolding exists, nothing built yet)

---

## Phase 1 — Theme Foundation

### [X] 1.1 Fix `style.css`
- Added proper theme header: Theme Name, URI, Description, Version, Author, License, Text Domain, Tags
- Text Domain already set to `serif` ✓
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

### [ ] 1.5 Create `screenshot.png`
- 1200×900 px, visually represents the theme (clean editorial layout)

---

## Phase 2— `theme.json` (Core Block Theme Config)

### [X] 2.1 Create `theme.json` with:

| Section | Detail | Status |
|---|---|---|
| **Fonts** | IBM Plex Serif (body), IBM Plex Sans (headings/UI) — local woff2 files only | ✅ 3 serif faces (400, 400i, 700), 1 sans face (400) |
| **Color palette** | 12 colors + 4 duotone filters | ✅ white, black, transparent, background, foreground, primary, secondary, muted, border, light, dark, accent |
| **Font sizes** | small→xxx-large (14px–48px) | ✅ fluid typography enabled |
| **Layout** | `contentSize: 720px`, `wideSize: 1100px` | ✅ |
| **Spacing** | `padding`, `margin` units: px, em, rem, vh, vw | ✅ blockGap enabled |
| **Blocks** | All 13 requested blocks | ✅ settings + default styles for each |
| **Appearance Tools** | border, color, typography, spacing, position | ✅ sticky positioning enabled |
| **Style Variations** | Default (base), Dark, Sepia, High Contrast | ✅ `styles/dark.json`, `styles/sepia.json`, `styles/high-contrast.json` |

---

## Phase 3 — PHP Includes (`inc/`)

### [X] 3.1 `inc/setup.php`
- `Serif\setup()`: theme support (editor styles, responsive embeds, block styles, align-wide, custom logo, post thumbnails, custom line height, custom spacing)
- Register nav menus (Primary, Footer, Social)
- Content width setter (720px)
- Disable custom colors/font-sizes/gradients (use theme.json presets only)

### [X] 3.2 `inc/scripts.php`
- Enqueue `style.css` (theme styles) ✅
- Google Fonts skipped — using local woff2 files via `theme.json` `fontFace` ❌ (user chose local-only)
- Enqueue `assets/js/navigation.js` (mobile menu toggle) ✅
- `add_editor_style()` for editor styles ✅
- Dequeue `wp-block-library-theme` on frontend (performance) ✅

### [ ] 3.3 `inc/block-styles.php`
- Register custom block styles:
  - `core/quote`: `default`, `large`, `pull-quote`
  - `core/separator`: `default`, `narrow`, `dots`, `thick`
  - `core/cover`: `default`, `gradient`, `overlay-dark`
  - `core/image`: `default`, `rounded`, `shadow`, `frame`
- Register block pattern categories (serif-hero, serif-layout, serif-media)

### [ ] 3.4 `inc/block-patterns.php`
- Register block patterns:
  - Hero with post featured image + title overlay
  - Two-column article grid
  - Featured quote with author bio
  - Newsletter signup with cover background
  - Author box (for single posts)
  - Related posts grid
  - Table of contents (TOC) pattern

### [ ] 3.5 `inc/integrations/yoast.php`
- Filter Yoast breadcrumb output to use theme's block styles

### [ ] 3.6 `inc/integrations/rankmath.php`
- Filter RankMath breadcrumb output to use theme's block styles

### [ ] 3.7 `inc/deprecated.php` (optional)
- Keep any backward-compat shims here

---

## Phase 4 — Templates (`templates/`)

Each file is HTML with block markup. All use `<!-- wp:template-part -->` for header/footer.

### [X] `templates/index.html`
Fallback. `group` > `post-title`, `post-featured-image`, `post-content`.

### [X] `templates/home.html`
Blog posts index. Uses `query-loop` with post-title, featured-image, excerpt, post-meta. Pagination via `query-pagination`.

### [ ] `templates/front-page.html`
Optional static front page. Full-width cover hero, featured posts grid, about section, CTA.

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

### [ ] `templates/blank.html`
No header/footer. Full-width canvas for landing pages or custom page builders. Uses `<!-- wp:post-content -->` only.

---

## Phase 5 — Template Parts (`parts/`)

### [X] `parts/header.html`
Site logo + site title + navigation block. Sticky/mobile handled by CSS/JS.

### [X] `parts/footer.html`
Footer widget area (3 columns) + copyright + social links.

### [X] `parts/post-meta.html`
Author avatar, author name, date, category list, tag list.

### [X] `parts/entry-header.html`
Cover block with featured image, dimRatio 50%, minHeight 60 — title + post-meta overlaid.

### [X] `parts/entry-footer.html`
Tags, social links, prev/next post navigation.

### [X] `parts/comments.html`
Comment list + form wrapped in block markup.

### [ ] `parts/off-canvas.html`
Mobile menu panel (slide-in from left/right).

---

## Phase 6 — Styles (SCSS/CSS)

### [ ] `assets/scss/style.scss`
Main entry, imports:
```scss
@import 'variables';
@import 'reset';
@import 'typography';
@import 'layout';
@import 'blocks';
@import 'components';
@import 'accessibility';
@import 'dark-mode';
```

### [ ] `assets/scss/_variables.scss`
- CSS custom properties for colors (including dark mode overrides), spacing scale, font stacks, z-index layers, transition speeds
- Match `theme.json` values exactly

### [ ] `assets/scss/_typography.scss`
- Font-face declarations for local IBM Plex files (fallback)
- Fluid type scale (clamp-based)
- Prose body styles, heading styles, link styles
- Selection color, focus styles

### [ ] `assets/scss/_layout.scss`
- `.site-container`, `.content-area`, `.site-header`, `.site-footer` structural styles
- Alignment classes (`.alignwide`, `.alignfull`)
- Main column layout, sticky sidebar (if applicable)

### [ ] `assets/scss/_blocks.scss`
- Core block overrides: paragraphs, headings, images, galleries, covers, columns, groups, buttons, quotes, separators, navigation, post templates, comments, search
- Match theme.json aesthetic

### [ ] `assets/scss/_components.scss`
- Pagination
- Breadcrumbs
- Author box
- Social links
- Mobile menu toggle / hamburger
- Back-to-top button
- Search form overlay
- Skip-link (accessibility)

### [ ] `assets/scss/_accessibility.scss`
- Focus outlines (visible, high-contrast friendly)
- Reduced motion media query
- Screen reader class (`.screen-reader-text`)
- High contrast mode tweaks (`forced-colors`)

### [ ] `assets/scss/_dark-mode.scss`
- Color overrides for preferred-color-scheme: dark

### [ ] Build pipeline
- `npm run build:css` should compile SCSS → `assets/css/style.css`
- Verify sourcemaps are working

---

## Phase 7 — JavaScript

### [ ] `assets/js/navigation.js`
- Mobile menu toggle (open/close, aria-expanded, body class)
- Click-outside-to-close behavior
- Submenu toggle on touch devices
- Keyboard navigation (Escape to close, Tab trap)
- Progressive enhancement (only active if menu exists)

### [ ] `assets/js/theme.js`
- Skip-link focus fix
- Back-to-top visibility toggle
- Optional: smooth scroll for anchor links

### [ ] Build pipeline
- Wire up `esbuild` for minification in `build:js` script
- Output to `assets/js/build/` or similar

---

## Phase 8 — Font Assets

### [ ] IBM Plex Serif (local fallback)
- Download woff2 files for weights 400, 500, 600, 700 (regular + italic)
- Place in `assets/fonts/ibm-plex-serif/`
- Add `@font-face` declarations in SCSS + `theme.json`

### [ ] IBM Plex Sans (local fallback)
- Download woff2 files for weights 400, 500, 600, 700
- Place in `assets/fonts/ibm-plex-sans/`
- Add `@font-face` declarations in SCSS + `theme.json`

### [ ] Google Fonts strategy
- Primary: enqueue from Google Fonts (faster first paint with `display=swap`)
- Fallback: local woff2 files if Google Fonts blocked
- Use `preconnect` + `preload` for performance

---

## Phase 9 — Custom Plugin: Serif ReadTime & Font Control

### [ ] 9.1 Plugin scaffolding
- `plugins/serif-readtime-font-control/serif-readtime-font-control.php`
- Plugin header (Name, URI, Description, Version, License, Text Domain)
- Activation / deactivation hooks
- `defined('ABSPATH')` guard

### [ ] 9.2 ReadTime Block
- Dynamic block (PHP render callback, no JS needed on frontend)
- Attributes: `show_icon` (bool), `label` (string), `words_per_minute` (int, default 200)
- Logic:
  - Strip shortcodes, strip HTML tags, strip media attachment metadata
  - Count words, divide by WPM, round up
  - Output: `"X min read"` or `"< 1 min read"`
- Register via `init`, use `register_block_type`

### [ ] 9.3 Font Control Widget
- Frontend floating widget (sticky bottom-right or bottom-left)
- Uses CSS custom properties for all changes
- Controls:
  - Font size: `--serif-font-size` (scales 80%–150% in 10% steps)
  - Line height: `--serif-line-height` (toggles between 1.6 / 1.8 / 2.0)
  - Contrast: `--serif-text-color`, `--serif-bg-color` (normal / high contrast / sepia)
  - Reset button
- Save to `localStorage` on every change
- On page load, read `localStorage` and apply immediately (before FOUC)
- Inline script in `<head>` (via `wp_head` with low priority) to restore saved state before render
- Accessible: keyboard navigable, aria labels, focus management, prefers-reduced-motion

### [ ] 9.4 Widget block/pattern
- Register a block pattern so users can place the widget trigger in the template
- Or auto-inject via `wp_footer` with a toggle switch in Customizer

---

## Phase 10 — Testing

### [ ] 10.1 PHPUnit setup
- Add `phpunit/phpunit:^11` + `yoast/phpunit-polyfills` to `composer.json`
- Create `phpunit.xml` with tests directory
- Bootstrap file that loads WP test environment (for integrated tests) or mocks (for unit tests)

### [ ] 10.2 ReadTime unit tests
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

### [ ] 10.3 Playwright E2E setup
- Create `tests/e2e-pw/playwright.config.ts`
- Configure: webServer (wp-env), baseURL, testDir, projects (chromium, firefox), reporter

### [ ] 10.4 Font Widget E2E tests
- `tests/e2e-pw/specs/font-widget.spec.ts`
  - Widget renders on frontend
  - Toggling font size updates body style
  - Toggling contrast changes background color
  - Reset button restores defaults
  - Values persist in localStorage after reload
  - Values restore correctly on fresh page load

### [ ] 10.5 Accessibility tests (aXe)
- `tests/e2e-pw/specs/accessibility.spec.ts`
  - Scan home page, single post, archive, 404 — no critical violations
  - Font widget open/closed states are accessible
  - Color contrast passes at all widget presets
  - Focus management is correct

### [ ] 10.6 Template rendering E2E
- `tests/e2e-pw/specs/templates.spec.ts`
  - Each template renders without errors
  - Header/footer appear on all pages
  - Dynamic blocks (post title, featured image) render correctly

---

## Phase 11 — i18n / l10n

### [ ] `languages/serif.pot`
- Generate with `wp i18n make-pot . languages/serif.pot`
- Include all `__()`, `_e()`, `esc_html__()`, etc. strings

### [ ] L10n
- At minimum: Greek (`el`) translation file (since author email is `.gr`)

---

## Phase 12 — Performance

### [ ] Asset optimization
- Inline critical CSS (above-the-fold) — optional, consider `theme.json` + block styles first
- Dequeue WP block library styles on pages that don't use them
- `async` / `defer` on non-critical JS
- Preload hero images

### [ ] Font optimization
- `font-display: swap` on all `@font-face`
- `preconnect` to Google Fonts origin
- Subset fonts (Latin, Greek) if possible

### [ ] Bundle & release
- `npm run bundle` script should produce a clean `build/serif.zip`

---

## Phase 13 — Documentation

### [ ] `readme.txt`
- WordPress.org-style readme: contributors, tags, tested up to, stable tag, description, installation, changelog
- Screenshots section (at least 1 hero screenshot)

### [ ] `README.md` update
- Add build instructions, structure overview, development guide
- Keep existing positioning text, expand with setup steps

---

## Fixes Queue (already identified bugs)

| # | File | Issue | Fix in phase |
|---|---|---|---|
| 1 | `functions.php:17` | `SERIF_THEME_DIR` defined twice | Phase 1.2 |
| 2 | `.wp-env.json` | Activates `coldwell-banker` theme | Phase 1.3 |
| 3 | `phpcs.xml` | `text-domain="dn"` | Phase 1.4 |
| 4 | `style.css` | Empty theme header | Phase 1.1 |
| 5 | `package.json` | `bundle` script references `screenshot.png` | Phase 1.5 |
| 6 | `composer.json` | Missing `phpunit/phpunit` | Phase 10.1 |

---

## Quick Reference: Final File Tree

```
serif/
├── style.css                         # Theme header
├── functions.php                     # Modified from skeleton
├── theme.json                        # Core block theme config
├── screenshot.png                    # 1200×900
├── readme.txt                        # WP.org readme
├── phpcs.xml                         # Fixed text-domain
├── .wp-env.json                      # Fixed theme activation
├── package.json
├── composer.json
├── assets/
│   ├── css/style.css                 # Compiled from SCSS
│   ├── fonts/
│   │   ├── ibm-plex-serif/           # woff2 files
│   │   └── ibm-plex-sans/            # woff2 files
│   ├── images/svg/                   # Theme SVGs
│   ├── js/
│   │   ├── navigation.js
│   │   └── theme.js
│   └── scss/
│       ├── style.scss
│       ├── _variables.scss
│       ├── _typography.scss
│       ├── _layout.scss
│       ├── _blocks.scss
│       ├── _components.scss
│       ├── _accessibility.scss
│       └── _dark-mode.scss
├── inc/
│   ├── setup.php
│   ├── scripts.php
│   ├── block-styles.php
│   ├── block-patterns.php
│   ├── template-tags.php
│   └── integrations/
│       ├── yoast.php
│       └── rankmath.php
├── languages/
│   └── serif.pot
├── parts/
│   ├── header.html
│   ├── footer.html
│   ├── post-meta.html
│   ├── entry-header.html
│   ├── entry-footer.html
│   ├── comments.html
│   └── off-canvas.html
├── patterns/
│   ├── hero-cover.php
│   ├── article-grid.php
│   ├── featured-quote.php
│   ├── newsletter-cta.php
│   ├── author-box.php
│   ├── related-posts.php
│   └── table-of-contents.php
├── plugins/
│   └── serif-readtime-font-control/
│       ├── serif-readtime-font-control.php
│       └── (block json, render callbacks, widget assets...)
├── templates/
│   ├── index.html
│   ├── home.html
│   ├── front-page.html
│   ├── single.html
│   ├── page.html
│   ├── archive.html
│   ├── search.html
│   ├── 404.html
│   └── blank.html
└── tests/
    ├── phpunit/
    │   ├── bootstrap.php
    │   └── ReadTimeTest.php
    └── e2e-pw/
        ├── playwright.config.ts
        ├── fixtures/
        │   └── auth.setup.ts
        └── specs/
            ├── font-widget.spec.ts
            ├── accessibility.spec.ts
            └── templates.spec.ts
```
