# Serif — Template Structure

FSE block theme using `.html` files with block markup. No PHP templates.

This file is the **target spec**. Where the current file on disk differs, the
Status table below says so and `TODO.md` → *Priority Fixes* has the action.

---

## Status (2026-09-12, after P0 + P1)

All files below now carry full serialized block markup and render without PHP
notices in wp-env (`WP_DEVELOPMENT_MODE: theme` is required for new pattern
files to be picked up).

| File | Exists | State |
|---|---|---|
| `templates/index.html` | yes | OK — query loop fallback |
| `templates/home.html` | yes | OK |
| `templates/front-page.html` | yes | OK — hero uses the front page's featured image |
| `templates/single.html` | yes | OK |
| `templates/page.html` | yes | OK |
| `templates/archive.html` | yes | OK |
| `templates/search.html` | yes | OK |
| `templates/404.html` | yes | OK |
| `templates/blank.html` | yes | OK |
| `parts/header.html` | yes | OK — wraps `patterns/header.php`: logo, title, Navigation (mobile overlay), expanding search |
| `parts/footer.html` | yes | OK — wraps `patterns/footer.php` |
| `parts/post-meta.html` | yes | OK |
| `parts/entry-header.html` | yes | OK |
| `parts/entry-footer.html` | yes | OK |
| `parts/comments.html` | yes | OK |
| `parts/off-canvas.html` | no | **probably unnecessary** — see note under Template Parts |
| `patterns/article-grid.php` | yes | OK |
| `patterns/author-box.php` | yes | OK |
| `patterns/related-posts.php` | yes | OK — category-scoped via `inc/block-patterns.php` |
| `patterns/newsletter-cta.php` | yes | OK |
| `patterns/hero-cover.php` | yes | OK — for `front-page.html` |
| `patterns/featured-quote.php` | yes | OK |
| `patterns/table-of-contents.php` | yes | OK |

---

## A Walk Through the Site

You land on **front-page.html** — a full-bleed cover image with the site title, a
"Featured" section showing three latest posts, and a newsletter CTA. For a
minimalist who just wants a clean blog landing, **home.html** skips the hero and
goes straight into a paginated post list.

Click a post title and you're on **single.html**: the featured image splashed
across the top as a cover background, the title overlaid on it, then the full
article body below. Tags and prev/next links sit at the bottom. Below that, an
author card, a row of related posts, and finally the comment thread. If you're
on a static page like About or Contact, **page.html** strips it down — just
title, featured image, and content.

Browsing a category or tag sends you to **archive.html**: a heading showing
"Category: Design" or "Tag: Typography", an optional description, then a post
list matching the archive.

Hit **search.html** and you get a search bar at the top, then results below. If
nothing matches, a friendly "No results found" message appears with the search
bar again so you can retry.

Typo a URL? **404.html** shows a centered "Page Not Found" heading, another
search bar, and a grid of recent articles to reclaim the lost visitor.

Need a full-canvas landing page with no chrome? **blank.html** gives you just
the post content — no header, no footer, nothing else.

If nothing else matches, **index.html** catches everything as a last resort with
a plain post list — the same loop as `home.html`.

**Behind every page:** The **header** carries the site logo, title, and
navigation (the core Navigation block collapses into its own overlay menu on
mobile). The **footer** has a secondary nav, a short blurb, copyright, and
social links. On single posts, **post-meta** shows the author avatar, name,
date, categories, and reading time. The **entry-header** wraps the title and
meta inside the featured image cover. The **entry-footer** handles tags and
post navigation. And **comments** renders the threaded discussion with the
reply form at the bottom.

---

## File Tree

```
templates/
├── index.html          # Fallback (lowest priority) — query loop
├── home.html           # Blog posts index
├── front-page.html     # Static front page (optional)
├── single.html         # Single post
├── page.html           # Static page
├── archive.html        # Category, tag, author, date archives
├── search.html         # Search results
├── 404.html            # 404 error
└── blank.html          # Full canvas, no header/footer

parts/
├── header.html         # <!-- wp:pattern serif/header -->
├── footer.html         # <!-- wp:pattern serif/footer -->
├── post-meta.html      # Author avatar, date, reading time, categories
├── entry-header.html   # Post title + featured image + overlay
├── entry-footer.html   # Tags, prev/next nav
└── comments.html       # Comment list + form

patterns/
├── header.php          # Logo + title + navigation
├── footer.php          # Nav + blurb + copyright + social
├── article-grid.php    # Recent posts grid (used by 404)
├── author-box.php      # Avatar + bio (used by single)
├── related-posts.php   # Same-category posts (used by single)
├── newsletter-cta.php  # Cover + form (used by front-page)
```

**Why header/footer parts wrap patterns:** the pattern is the editable
source of truth, the part is the slot templates reference. Only the *part*
sets `tagName` (`header`/`footer`) — the pattern's outer group must **not**,
or the output is `<header><header>`.

---

## Template Details

### `templates/index.html` — Fallback

Lowest-priority fallback. Used when no other template matches — which
includes *list* views, so it must be a loop, not a single-post layout.

```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:group {"tagName":"main","layout":{"type":"constrained"}} -->
    <!-- wp:query {"query":{"inherit":true}} -->
        <!-- wp:post-template -->
            <!-- wp:post-featured-image /-->
            <!-- wp:post-title {"isLink":true} /-->
            <!-- wp:post-excerpt /-->
            <!-- wp:template-part {"slug":"post-meta"} /-->
        <!-- /wp:post-template -->
        <!-- wp:query-no-results -->
            <!-- wp:paragraph --><p>Nothing here yet.</p><!-- /wp:paragraph -->
        <!-- /wp:query-no-results -->
        <!-- wp:query-pagination /-->
    <!-- /wp:query -->
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

### `templates/home.html` — Blog Index

Landing page when front page displays "Your latest posts."

```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:group {"tagName":"main","layout":{"type":"constrained"}} -->
    <!-- wp:query {"queryId":0,"query":{"perPage":10,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","sticky":"","inherit":true}} -->
        <!-- wp:post-template -->
            <!-- wp:post-featured-image /-->
            <!-- wp:post-title {"isLink":true} /-->
            <!-- wp:post-excerpt /-->
            <!-- wp:template-part {"slug":"post-meta"} /-->
        <!-- /wp:post-template -->
        <!-- wp:query-no-results -->
            <!-- wp:paragraph --><p>No posts yet.</p><!-- /wp:paragraph -->
        <!-- /wp:query-no-results -->
        <!-- wp:query-pagination /-->
    <!-- /wp:query -->
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

### `templates/front-page.html` — Static Front Page

Used when a static page is set as front page. Hero, three featured posts, CTA.

```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:cover {"useFeaturedImage":true,"overlayColor":"dark","dimRatio":60,"minHeight":70,"minHeightUnit":"vh","align":"full"} -->
    <!-- wp:site-title {"level":1,"textColor":"white"} /-->
    <!-- wp:site-tagline {"textColor":"light"} /-->
    <!-- wp:buttons --> Start reading → #featured <!-- /wp:buttons -->
<!-- /wp:cover -->

<!-- wp:group {"tagName":"main","layout":{"type":"constrained"}} -->
    <!-- wp:heading {"level":2} --><h2>Featured</h2><!-- /wp:heading -->
    <!-- wp:query {"query":{"perPage":3,"postType":"post","inherit":false}} -->
        <!-- wp:post-template {"layout":{"type":"grid","columnCount":3}} -->
            <!-- wp:post-featured-image /-->
            <!-- wp:post-title {"isLink":true} /-->
            <!-- wp:post-excerpt /-->
        <!-- /wp:post-template -->
    <!-- /wp:query -->
    <!-- wp:pattern {"slug":"serif/newsletter-cta"} /-->
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

### `templates/single.html` — Single Post

```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:group {"tagName":"main","layout":{"type":"constrained"}} -->
    <!-- wp:template-part {"slug":"entry-header"} /-->
    <!-- wp:post-content /-->
    <!-- wp:template-part {"slug":"entry-footer"} /-->
    <!-- wp:pattern {"slug":"serif/author-box"} /-->
    <!-- wp:pattern {"slug":"serif/related-posts"} /-->
    <!-- wp:template-part {"slug":"comments"} /-->
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

### `templates/page.html` — Static Page

```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:group {"tagName":"main","layout":{"type":"constrained"}} -->
    <!-- wp:post-title /-->
    <!-- wp:post-featured-image /-->
    <!-- wp:post-content /-->
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

### `templates/archive.html` — Archive

Handles categories, tags, authors, and date archives.

```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:group {"tagName":"main","layout":{"type":"constrained"}} -->
    <!-- wp:query-title /-->
    <!-- wp:term-description /-->
    <!-- wp:query {"query":{"inherit":true}} -->
        <!-- wp:post-template -->
            <!-- wp:post-featured-image /-->
            <!-- wp:post-title {"isLink":true} /-->
            <!-- wp:post-excerpt /-->
            <!-- wp:template-part {"slug":"post-meta"} /-->
        <!-- /wp:post-template -->
        <!-- wp:query-no-results -->
            <!-- wp:paragraph --><p>No posts in this archive.</p><!-- /wp:paragraph -->
        <!-- /wp:query-no-results -->
        <!-- wp:query-pagination /-->
    <!-- /wp:query -->
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

### `templates/search.html` — Search Results

```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:group {"tagName":"main","layout":{"type":"constrained"}} -->
    <!-- wp:search {"label":"Search","showLabel":false,"placeholder":"Search...","buttonText":"Search"} /-->
    <!-- wp:query-title {"type":"search"} /-->
    <!-- wp:query {"query":{"inherit":true}} -->
        <!-- wp:post-template -->
            <!-- wp:post-title {"isLink":true} /-->
            <!-- wp:post-excerpt /-->
        <!-- /wp:post-template -->
        <!-- wp:query-no-results -->
            <!-- wp:paragraph --><p>No results found. Try a different search term.</p><!-- /wp:paragraph -->
            <!-- wp:search /-->
        <!-- /wp:query-no-results -->
        <!-- wp:query-pagination /-->
    <!-- /wp:query -->
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

### `templates/404.html` — Not Found

```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:group {"tagName":"main","layout":{"type":"constrained"}} -->
    <!-- wp:heading {"level":1,"textAlign":"center"} --><h1>Page Not Found</h1><!-- /wp:heading -->
    <!-- wp:paragraph {"align":"center"} --><p>The page you're looking for doesn't exist.</p><!-- /wp:paragraph -->
    <!-- wp:search {"label":"Search","showLabel":false} /-->
    <!-- wp:pattern {"slug":"serif/article-grid"} /-->
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

### `templates/blank.html` — Full Canvas

No header, no footer. Registered in `theme.json` `customTemplates` as `blank`.

```html
<!-- wp:post-content {"layout":{"inherit":false}} /-->
```

---

## Template Parts

### `parts/header.html`

```html
<!-- wp:pattern {"slug":"serif/header"} /-->
```

### `patterns/header.php` (what the part renders)

No `tagName` on the outer group — the part already supplies `<header>`.

```html
<!-- wp:group {"align":"full","layout":{"type":"constrained"}} -->
    <!-- wp:group {"align":"wide","style":{"spacing":{"padding":{"top":"12px","bottom":"12px"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
        <!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap"}} -->
            <!-- wp:site-logo {"width":48} /-->
            <!-- wp:site-title /-->
        <!-- /wp:group -->
        <!-- wp:navigation {"overlayMenu":"mobile","layout":{"type":"flex","justifyContent":"right"}} /-->
    <!-- /wp:group -->
<!-- /wp:group -->
```

Sticky header is CSS (`position: sticky` on `header.wp-block-template-part`).

### `parts/footer.html`

```html
<!-- wp:pattern {"slug":"serif/footer"} /-->
```

### `patterns/footer.php` (what the part renders)

Block themes have no widget areas — `wp:widget-area` is not a block. Use real
blocks in the columns.

```html
<!-- wp:group {"align":"full","layout":{"type":"constrained"}} -->
    <!-- wp:columns {"align":"wide"} -->
        <!-- wp:column -->
            <!-- wp:site-title {"level":0} /-->
            <!-- wp:site-tagline /-->
        <!-- /wp:column -->
        <!-- wp:column -->
            <!-- wp:navigation {"overlayMenu":"never","layout":{"type":"flex","orientation":"vertical"}} /-->
        <!-- /wp:column -->
        <!-- wp:column -->
            <!-- wp:social-links -->
                <!-- wp:social-link {"service":"twitter"} /-->
                <!-- wp:social-link {"service":"github"} /-->
            <!-- /wp:social-links -->
        <!-- /wp:column -->
    <!-- /wp:columns -->
    <!-- wp:paragraph {"align":"center","fontSize":"small"} -->
        <p>&copy; Serif. All rights reserved.</p>
    <!-- /wp:paragraph -->
<!-- /wp:group -->
```

### `parts/post-meta.html`

```html
<!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap"}} -->
    <!-- wp:avatar {"size":24} /-->
    <!-- wp:post-author-name /-->
    <!-- wp:post-date /-->
    <!-- wp:post-terms {"term":"category"} /-->
    <!-- wp:post-terms {"term":"post_tag"} /-->
<!-- /wp:group -->
```

Reading time injected dynamically (via the Serif ReadTime plugin block, Phase 9).

### `parts/entry-header.html`

`minHeight` defaults to **px** — `minHeightUnit` is required for `vh`.

```html
<!-- wp:cover {"useFeaturedImage":true,"dimRatio":50,"minHeight":60,"minHeightUnit":"vh","align":"full"} -->
    <!-- wp:group {"layout":{"type":"constrained"}} -->
        <!-- wp:post-title {"level":1} /-->
        <!-- wp:template-part {"slug":"post-meta"} /-->
    <!-- /wp:group -->
<!-- /wp:cover -->
```

### `parts/entry-footer.html`

Sharing links dropped — an empty `wp:social-links` renders nothing, and
per-post share buttons belong to a plugin, not the theme.

```html
<!-- wp:group -->
    <!-- wp:post-terms {"term":"post_tag"} /-->
    <!-- wp:group {"layout":{"type":"flex","justifyContent":"space-between"}} -->
        <!-- wp:post-navigation-link {"type":"previous"} /-->
        <!-- wp:post-navigation-link {"type":"next"} /-->
    <!-- /wp:group -->
<!-- /wp:group -->
```

### `parts/comments.html`

```html
<!-- wp:group -->
    <!-- wp:comments -->
        <!-- wp:comments-title /-->
        <!-- wp:comment-template -->
            <!-- wp:comment-author-avatar /-->
            <!-- wp:comment-author-name /-->
            <!-- wp:comment-date /-->
            <!-- wp:comment-content /-->
            <!-- wp:comment-reply-link /-->
        <!-- /wp:comment-template -->
        <!-- wp:comments-pagination /-->
        <!-- wp:post-comments-form /-->
    <!-- /wp:comments -->
<!-- /wp:group -->
```

### `parts/off-canvas.html` — probably not needed

The core Navigation block with `"overlayMenu":"mobile"` already ships a
responsive, accessible (focus-trapped, Escape-to-close) overlay menu — no
custom JS or extra part required. Only build a custom off-canvas panel if the
built-in overlay proves insufficient (e.g. you want a search field inside it).
If so:

```html
<!-- wp:group {"className":"off-canvas-panel"} -->
    <!-- wp:navigation {"overlayMenu":"never","layout":{"type":"flex","orientation":"vertical"}} /-->
    <!-- wp:search /-->
<!-- /wp:group -->
```

---

## Template Hierarchy (block themes)

Block templates resolve in the same order as the classic hierarchy; `.html`
wins over `.php`. Most specific first:

```
front-page.html   → used for the front page whether static page or posts list
home.html         → posts index (front page when "latest posts", else the Posts page)
single.html       → single-{post-type}-{slug}.html → single-{post-type}.html → single.html
page.html         → page-{slug}.html → page-{id}.html → page.html
archive.html      → category-{slug}.html → category.html → taxonomy-*.html → author.html → date.html → archive.html
search.html
404.html
index.html        → last resort for everything
```

Note: `front-page.html` takes `/` regardless of the Reading setting. Set a
Posts page (Settings → Reading) to get the paginated `home.html` list — the
seed uses `/journal/`. The hero image is the front page's featured image.

---

## Theme.json Dependencies

Every template relies on `theme.json` for:

- **Layout:** `contentSize: 720px`, `wideSize: 1100px`
- **Typography:** IBM Plex Serif (body), IBM Plex Sans (headings)
- **Colors:** 12-color palette; dark/sepia/high-contrast via `styles/*.json` variations
- **Spacing:** `padding`, `margin` units
- **Per-block settings:** `core/post-title`, `core/navigation`, `core/cover`, etc.

`theme.json` is the single source of truth for design tokens. SCSS only
consumes `var(--wp--preset--*)`.
