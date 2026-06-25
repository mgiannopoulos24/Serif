# Serif — Template Structure

FSE block theme using `.html` files with block markup. No PHP templates.

---

## File Tree

```
templates/
├── index.html          # Fallback (lowest priority)
├── home.html           # Blog posts index
├── front-page.html     # Static front page (optional)
├── single.html         # Single post
├── page.html           # Static page
├── archive.html        # Category, tag, author, date archives
├── search.html         # Search results
├── 404.html            # 404 error
└── blank.html          # Full canvas, no header/footer

parts/
├── header.html         # Site header (logo, nav, search toggle)
├── footer.html         # Site footer (widgets, copyright, social)
├── post-meta.html      # Author avatar, date, reading time, categories
├── entry-header.html   # Post title + featured image + overlay
├── entry-footer.html   # Tags, share, prev/next nav
├── comments.html       # Comment list + form
└── off-canvas.html     # Mobile menu panel (slide-in)
```

---

## Template Details

### `templates/index.html` — Fallback

Lowest-priority fallback. Used when no other template matches.

```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:group {"tagName":"main","layout":{"type":"constrained"}} -->
    <!-- wp:post-title /-->
    <!-- wp:post-featured-image /-->
    <!-- wp:post-content /-->
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

### `templates/home.html` — Blog Index

Landing page when front page displays "Your latest posts."

**Blocks:** `query-loop` with card-style post items (title, excerpt, featured image, meta). Pagination.

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
        <!-- wp:query-pagination /-->
    <!-- /wp:query -->
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

### `templates/front-page.html` — Static Front Page

Used when a static page is set as front page. Hero section, featured posts, optional CTAs.

```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:cover {"overlayColor":"primary","minHeight":70,"align":"full"} -->
    <!-- wp:heading {"level":1} --><!-- /wp:heading -->
    <!-- wp:paragraph --><!-- /wp:paragraph -->
<!-- /wp:cover -->

<!-- wp:group {"tagName":"main","layout":{"type":"constrained"}} -->
    <!-- wp:heading {"level":2} -->Featured<!-- /wp:heading -->
    <!-- wp:query {"query":{"perPage":3,"postType":"post"}} -->
        <!-- wp:post-template /-->
    <!-- /wp:query -->
    <!-- wp:pattern {"slug":"serif/newsletter-cta"} /-->
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

### `templates/single.html` — Single Post

Blog post detail view.

**Blocks:** Entry header (title + featured image + meta), post content, author box, related posts, comments.

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

Clean minimal layout for pages like About, Contact.

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

<!-- wp:group {"tagName":"main","layout":{"type":"constrained"},"align":"full"} -->
    <!-- wp:heading {"level":1,"textAlign":"center"} --><h1>Page Not Found</h1><!-- /wp:heading -->
    <!-- wp:paragraph {"align":"center"} --><p>The page you're looking for doesn't exist.</p><!-- /wp:paragraph -->
    <!-- wp:search {"label":"Search","showLabel":false} /-->
    <!-- wp:pattern {"slug":"serif/article-grid"} /-->
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

### `templates/blank.html` — Full Canvas

No header, no footer. For landing pages or custom builders. Only post content.

```html
<!-- wp:post-content {"layout":{"inherit":false}} /-->
```

---

## Template Parts

### `parts/header.html`

Site identity + navigation + optional search toggle.

```html
<!-- wp:group {"tagName":"header","layout":{"type":"flex","flexWrap":"nowrap"}} -->
    <!-- wp:site-logo {"width":48} /-->
    <!-- wp:site-title /-->
    <!-- wp:navigation {"icon":"menu","layout":{"type":"flex","setCascadingMenu":true,"justify":"right"}} /-->
<!-- /wp:group -->
```

Sticky behavior, hamburger menu, and search toggle handled via CSS/JS, not block markup.

### `parts/footer.html`

```html
<!-- wp:group {"tagName":"footer","layout":{"type":"constrained"}} -->
    <!-- wp:columns -->
        <!-- wp:column --><!-- wp:widget-area /--><!-- /wp:column -->
        <!-- wp:column --><!-- wp:widget-area /--><!-- /wp:column -->
        <!-- wp:column --><!-- wp:widget-area /--><!-- /wp:column -->
    <!-- /wp:columns -->
    <!-- wp:paragraph {"align":"center","fontSize":"small"} -->
        <p>&copy; 2025 Serif. All rights reserved.</p>
    <!-- /wp:paragraph -->
    <!-- wp:social-links /-->
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

Reading time injected dynamically (via the Serif ReadTime plugin block).

### `parts/entry-header.html`

```html
<!-- wp:cover {"useFeaturedImage":true,"dimRatio":50,"minHeight":60,"align":"full"} -->
    <!-- wp:post-title {"level":1} /-->
    <!-- wp:template-part {"slug":"post-meta"} /-->
<!-- /wp:cover -->
```

### `parts/entry-footer.html`

```html
<!-- wp:group -->
    <!-- wp:post-terms {"term":"post_tag"} /-->
    <!-- wp:social-links /-->
    <!-- wp:post-navigation-link {"type":"previous"} /-->
    <!-- wp:post-navigation-link {"type":"next"} /-->
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

### `parts/off-canvas.html`

Mobile navigation panel — toggled via CSS/JS, not by block visibility.

```html
<!-- wp:group {"className":"off-canvas-panel"} -->
    <!-- wp:navigation {"layout":{"type":"vertical"}} /-->
    <!-- wp:search /-->
<!-- /wp:group -->
```

---

## Template Hierarchy (WordPress FSE)

```
front-page.html          → front-page.php (classic fallback)
home.html                 → home.php
single.html               → single-{post-type}.php → single.php
page.html                 → page-{slug}.php → page.php
archive.html              → archive-{term}.php → archive.php
search.html               → search.php
404.html                  → 404.php
index.html                → index.php (last resort)
```

All `.html` files resolve natively in FSE themes. No PHP templates needed unless overriding block behavior.

---

## Theme.json Dependencies

Every template relies on `theme.json` for:

- **Layout:** `contentSize: 720px`, `wideSize: 1100px`
- **Typography:** IBM Plex Serif (body), IBM Plex Sans (headings)
- **Colors:** 10–12 color palette, dark mode presets
- **Spacing:** `padding`, `margin` units
- **Per-block settings:** `core/post-title`, `core/navigation`, `core/cover`, etc.

`theme.json` must exist before templates render correctly.
