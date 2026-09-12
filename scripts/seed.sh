#!/usr/bin/env sh
# Seed the wp-env site with sample content so every template has something to render.
#
# Runs INSIDE the wp-env cli container (Alpine, POSIX sh, wp-cli available):
#   bun run seed          # seed once (no-op if already seeded)
#   bun run seed:reset    # wipe all content and seed again
#
# Wired into .wp-env.json "afterStart", so `bun run start` seeds automatically.
set -eu

THEME_DIR="$(cd "$(dirname "$0")/.." && pwd)"
MARKER="serif_seeded"
TMP="${TMPDIR:-/tmp}/serif-seed"
mkdir -p "$TMP"

log() { printf '  %s\n' "$*"; }

if [ "${1:-}" = "--reset" ]; then
	log "Emptying site content (posts, comments, terms, uploads)..."
	wp site empty --yes --uploads
	wp option delete "$MARKER" >/dev/null 2>&1 || true
	wp option delete site_logo >/dev/null 2>&1 || true
elif [ "$(wp option get "$MARKER" 2>/dev/null || true)" = "1" ]; then
	log "Already seeded (wp option $MARKER). Use --reset to reseed."
	exit 0
else
	# Fresh install: drop WordPress's stock content so it doesn't top the feed.
	log "Removing default content..."
	for slug in hello-world sample-page privacy-policy; do
		for id in $(wp post list --post_type=post,page --post_status=any --name="$slug" --format=ids 2>/dev/null); do
			wp post delete "$id" --force >/dev/null
		done
	done
	wp comment delete 1 --force >/dev/null 2>&1 || true
fi

# ---------------------------------------------------------------------------
# Settings
# ---------------------------------------------------------------------------
log "Settings"
wp option update blogname "Serif" >/dev/null
wp option update blogdescription "Notes on writing, typography and the slow web" >/dev/null
wp option update posts_per_page 6 >/dev/null
wp option update timezone_string "Europe/Athens" >/dev/null
wp option update date_format "j F Y" >/dev/null
wp option update permalink_structure '/%postname%/' >/dev/null
wp rewrite flush --hard >/dev/null 2>&1 || true

# Author profile so the author box has something to show.
wp user update admin --display_name="Marios G." --first_name="Marios" --last_name="G." >/dev/null
wp user meta update admin description "Writes about type, tools and the craft of long-form. Editor of Serif." >/dev/null

# ---------------------------------------------------------------------------
# Taxonomies
# ---------------------------------------------------------------------------
log "Categories and tags"
term_id() { wp term list "$1" --slug="$2" --field=term_id; }
for pair in "Essays:essays" "Reporting:reporting" "Notes:notes" "Design:design"; do
	name=${pair%%:*}; slug=${pair##*:}
	wp term create category "$name" --slug="$slug" >/dev/null 2>&1 || true
done
for slug in typography writing longform process tools; do
	wp term create post_tag "$slug" >/dev/null 2>&1 || true
done

# ---------------------------------------------------------------------------
# Post bodies (block markup). Three variants, cycled across posts.
# ---------------------------------------------------------------------------
cat > "$TMP/body-1.html" <<'BODY'
<!-- wp:paragraph {"dropCap":true} -->
<p class="has-drop-cap">There is a particular silence that settles over a page when the type is right. Nothing calls attention to itself; the words simply arrive. This is harder to achieve than it looks, and most of the difficulty is invisible to the reader, which is the point.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>The measure — the width of a line of text — is the first decision, and it constrains every other one. Too wide and the eye loses its way on the return; too narrow and the rhythm breaks into fragments. Somewhere between forty-five and seventy-five characters is where most long-form text wants to live.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">Why the measure matters</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Screens made the measure negotiable, and for a while that felt like freedom. Then it became clear that a paragraph stretched across a 27-inch display is not liberated; it is abandoned. Constraint returned, this time as a CSS value.</p>
<!-- /wp:paragraph -->

<!-- wp:quote -->
<blockquote class="wp-block-quote"><!-- wp:paragraph -->
<p>Typography exists to honour content.</p>
<!-- /wp:paragraph --><cite>Robert Bringhurst</cite></blockquote>
<!-- /wp:quote -->

<!-- wp:paragraph -->
<p>What follows is less a set of rules than a set of habits: read the text before setting it, set it at the size you would want to read it at, and then leave it alone.</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul class="wp-block-list"><!-- wp:list-item -->
<li>Choose the measure before the typeface.</li>
<!-- /wp:list-item --><!-- wp:list-item -->
<li>Let line-height follow from the measure, not the other way round.</li>
<!-- /wp:list-item --><!-- wp:list-item -->
<li>Resist the second typeface until the first has earned it.</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:paragraph -->
<p>None of this is new. It is only newly forgotten, every few years, and then remembered again.</p>
<!-- /wp:paragraph -->
BODY

cat > "$TMP/body-2.html" <<'BODY'
<!-- wp:paragraph -->
<p>I have been keeping a notebook of the small decisions that go into a piece — not the argument, but the scaffolding: where a section break falls, which sentence carries the transition, when a quotation earns a line of its own. The notebook has become more useful than the drafts.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">The problem with drafts</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>A draft records what you wrote. It does not record why you stopped, or what you tried and deleted, or the sentence you moved three times before returning it to where it started. Those are the decisions that make the next piece easier, and they vanish the moment you hit save.</p>
<!-- /wp:paragraph -->

<!-- wp:pullquote -->
<figure class="wp-block-pullquote"><blockquote><p>The notebook has become more useful than the drafts.</p></blockquote></figure>
<!-- /wp:pullquote -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">What goes in</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Three things, mostly. The structural choice and the alternative I rejected. The place where I got stuck and what unstuck me. And any sentence I cut that I still liked, because there is always a next piece.</p>
<!-- /wp:paragraph -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:paragraph -->
<p>It takes ten minutes at the end of a session. I have not yet regretted it.</p>
<!-- /wp:paragraph -->
BODY

cat > "$TMP/body-3.html" <<'BODY'
<!-- wp:paragraph -->
<p>The brief was simple: make the site faster without making it uglier. The two goals turned out to be the same goal, which is usually how it goes.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">Where the weight was</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Four web fonts, two of which were used for a single navigation label. A hero image served at three times its rendered size. A script for a carousel that had been removed from the design two redesigns ago. None of it was malicious. All of it was inertia.</p>
<!-- /wp:paragraph -->

<!-- wp:table -->
<figure class="wp-block-table"><table class="has-fixed-layout"><thead><tr><th>Asset</th><th>Before</th><th>After</th></tr></thead><tbody><tr><td>Fonts</td><td>412 KB</td><td>96 KB</td></tr><tr><td>Hero image</td><td>1.8 MB</td><td>210 KB</td></tr><tr><td>JavaScript</td><td>340 KB</td><td>0 KB</td></tr></tbody></table></figure>
<!-- /wp:table -->

<!-- wp:paragraph -->
<p>The fonts were the interesting part. Dropping to one family with a variable weight axis cost nothing visually and saved more than every other change combined.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">What we kept</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>The serif. The generous margins. The drop caps, which cost nothing. Speed is not the absence of design; it is design with the excuses removed.</p>
<!-- /wp:paragraph -->

<!-- wp:code -->
<pre class="wp-block-code"><code>@font-face {
  font-family: "IBM Plex Sans";
  font-weight: 100 700;
  font-display: swap;
}</code></pre>
<!-- /wp:code -->
BODY

# ---------------------------------------------------------------------------
# Posts
# ---------------------------------------------------------------------------
log "Posts"
# title|slug|category|tags|days-ago|body|with-image
POSTS='
The Slow Return of the Long Read|slow-return-long-read|essays|longform,writing|1|1|1
Notes From a Week Without Notifications|week-without-notifications|notes|process,tools|3|2|1
What a Newsroom Learned From Its Own Typography|newsroom-typography|reporting|typography|6|3|1
On the Measure|on-the-measure|design|typography|9|1|0
A Notebook for Decisions, Not Drafts|notebook-for-decisions|essays|process,writing|13|2|1
Shipping a Site That Weighs Less Than Its Fonts|site-lighter-than-fonts|design|tools,typography|18|3|1
The Case for Reading Slowly|case-for-reading-slowly|essays|longform|24|1|0
Interview: An Editor on Cutting Without Losing|editor-on-cutting|reporting|writing,process|31|2|1
Why Serif Still Wins on Screens|serif-on-screens|design|typography|40|3|1
Small Rituals for Finishing|small-rituals-for-finishing|notes|process|52|1|0
'
first_post_id=""
i=0
printf '%s\n' "$POSTS" | while IFS='|' read -r title slug cat tags days body img; do
	[ -z "$title" ] && continue
	i=$((i + 1))
	ts=$(( $(date +%s) - days * 86400 ))
	date=$(date -d "@$ts" '+%Y-%m-%d 09:00:00')
	id=$(wp post create "$TMP/body-$body.html" \
		--post_type=post --post_status=publish --post_author=1 \
		--post_title="$title" --post_name="$slug" --post_date="$date" \
		--post_category="$cat" --tags_input="$tags" \
		--meta_input='{"_serif_seed":"1"}' --porcelain)
	if [ "$img" = "1" ]; then
		att=$(wp media import "https://picsum.photos/seed/serif-${slug}/1600/1000.jpg" \
			--post_id="$id" --title="$title" --featured_image --porcelain 2>/dev/null || true)
		[ -n "$att" ] || log "  (no featured image for '$title' — network?)"
	fi
	log "  #$id $title"
	echo "$id" >> "$TMP/post-ids"
done

# ---------------------------------------------------------------------------
# Pages
# ---------------------------------------------------------------------------
log "Pages"
cat > "$TMP/about.html" <<'BODY'
<!-- wp:paragraph -->
<p>Serif is a small publication about writing, typography and building things for the web that respect the reader's time. It is edited by one person and updated when there is something worth saying.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">Colophon</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Set in IBM Plex Serif and IBM Plex Sans. Built on WordPress with the Serif block theme. No trackers, no newsletter pop-up, no carousel.</p>
<!-- /wp:paragraph -->
BODY
wp post create "$TMP/about.html" --post_type=page --post_status=publish --post_author=1 --post_title="About" --post_name="about" --meta_input='{"_serif_seed":"1"}' --porcelain >/dev/null

cat > "$TMP/contact.html" <<'BODY'
<!-- wp:paragraph -->
<p>Corrections, tips and disagreements are all welcome. The best way to reach the editor is by email; replies are slow but they do arrive.</p>
<!-- /wp:paragraph -->
BODY
wp post create "$TMP/contact.html" --post_type=page --post_status=publish --post_author=1 --post_title="Contact" --post_name="contact" --meta_input='{"_serif_seed":"1"}' --porcelain >/dev/null

# Front page (hero image = the page's featured image) and Journal (posts page).
home_id=$(wp post create --post_type=page --post_status=publish --post_author=1 --post_title="Home" --post_name="home" --post_content="" --meta_input='{"_serif_seed":"1"}' --porcelain)
wp media import "https://picsum.photos/seed/serif-home/2000/1200.jpg" --post_id="$home_id" --title="Front page hero" --featured_image --porcelain >/dev/null 2>&1 || log "  (no hero image — network?)"
journal_id=$(wp post create --post_type=page --post_status=publish --post_author=1 --post_title="Journal" --post_name="journal" --post_content="" --meta_input='{"_serif_seed":"1"}' --porcelain)
wp option update show_on_front page >/dev/null
wp option update page_on_front "$home_id" >/dev/null
wp option update page_for_posts "$journal_id" >/dev/null

# Patterns: every theme pattern on one page, for visual checks.
cat > "$TMP/patterns.html" <<'BODY'
<!-- wp:pattern {"slug":"serif/hero-cover"} /-->
<!-- wp:pattern {"slug":"serif/table-of-contents"} /-->
<!-- wp:pattern {"slug":"serif/featured-quote"} /-->
<!-- wp:pattern {"slug":"serif/article-grid"} /-->
<!-- wp:pattern {"slug":"serif/newsletter-cta"} /-->
BODY
wp post create "$TMP/patterns.html" --post_type=page --post_status=publish --post_author=1 --post_title="Patterns" --post_name="patterns" --meta_input='{"_serif_seed":"1"}' --porcelain >/dev/null

# Kitchen sink: every block style the theme registers, for visual checks.
cat > "$TMP/kitchen-sink.html" <<'BODY'
<!-- wp:heading --><h2 class="wp-block-heading">Heading two</h2><!-- /wp:heading -->
<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Heading three</h3><!-- /wp:heading -->
<!-- wp:heading {"level":4} --><h4 class="wp-block-heading">Heading four</h4><!-- /wp:heading -->
<!-- wp:paragraph {"dropCap":true} --><p class="has-drop-cap">A paragraph with a drop cap, a <a href="#">link</a>, some <strong>bold</strong> and <em>italic</em> text, and <code>inline code</code>. It runs long enough to wrap onto a second and third line so that the measure and line-height can be judged properly.</p><!-- /wp:paragraph -->
<!-- wp:quote --><blockquote class="wp-block-quote"><!-- wp:paragraph --><p>Default quote style.</p><!-- /wp:paragraph --><cite>Attribution</cite></blockquote><!-- /wp:quote -->
<!-- wp:quote {"className":"is-style-large"} --><blockquote class="wp-block-quote is-style-large"><!-- wp:paragraph --><p>Large quote style.</p><!-- /wp:paragraph --><cite>Attribution</cite></blockquote><!-- /wp:quote -->
<!-- wp:quote {"className":"is-style-pull-quote"} --><blockquote class="wp-block-quote is-style-pull-quote"><!-- wp:paragraph --><p>Pull quote style, centred between two rules.</p><!-- /wp:paragraph --></blockquote><!-- /wp:quote -->
<!-- wp:pullquote --><figure class="wp-block-pullquote"><blockquote><p>The core pullquote block.</p><cite>Attribution</cite></blockquote></figure><!-- /wp:pullquote -->
<!-- wp:separator --><hr class="wp-block-separator has-alpha-channel-opacity"/><!-- /wp:separator -->
<!-- wp:separator {"className":"is-style-narrow"} --><hr class="wp-block-separator has-alpha-channel-opacity is-style-narrow"/><!-- /wp:separator -->
<!-- wp:separator {"className":"is-style-thick"} --><hr class="wp-block-separator has-alpha-channel-opacity is-style-thick"/><!-- /wp:separator -->
<!-- wp:separator {"className":"is-style-dots"} --><hr class="wp-block-separator has-alpha-channel-opacity is-style-dots"/><!-- /wp:separator -->
<!-- wp:image {"className":"is-style-shadow"} --><figure class="wp-block-image is-style-shadow"><img src="https://picsum.photos/seed/serif-sink-1/1200/800.jpg" alt="Placeholder"/><figcaption class="wp-element-caption">Image, shadow style.</figcaption></figure><!-- /wp:image -->
<!-- wp:image {"className":"is-style-frame"} --><figure class="wp-block-image is-style-frame"><img src="https://picsum.photos/seed/serif-sink-2/1200/800.jpg" alt="Placeholder"/><figcaption class="wp-element-caption">Image, frame style.</figcaption></figure><!-- /wp:image -->
<!-- wp:cover {"url":"https://picsum.photos/seed/serif-sink-3/1600/900.jpg","dimRatio":50,"minHeight":320,"className":"is-style-gradient","layout":{"type":"constrained"}} --><div class="wp-block-cover is-style-gradient" style="min-height:320px"><img class="wp-block-cover__image-background" alt="" src="https://picsum.photos/seed/serif-sink-3/1600/900.jpg" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:heading {"textAlign":"center","textColor":"white"} --><h2 class="wp-block-heading has-text-align-center has-white-color has-text-color">Cover, gradient style</h2><!-- /wp:heading --></div></div><!-- /wp:cover -->
<!-- wp:cover {"url":"https://picsum.photos/seed/serif-sink-4/1600/900.jpg","dimRatio":50,"minHeight":320,"className":"is-style-overlay-dark","layout":{"type":"constrained"}} --><div class="wp-block-cover is-style-overlay-dark" style="min-height:320px"><img class="wp-block-cover__image-background" alt="" src="https://picsum.photos/seed/serif-sink-4/1600/900.jpg" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:heading {"textAlign":"center","textColor":"white"} --><h2 class="wp-block-heading has-text-align-center has-white-color has-text-color">Cover, dark overlay style</h2><!-- /wp:heading --></div></div><!-- /wp:cover -->
<!-- wp:list --><ul class="wp-block-list"><!-- wp:list-item --><li>Unordered list item</li><!-- /wp:list-item --><!-- wp:list-item --><li>Another item</li><!-- /wp:list-item --></ul><!-- /wp:list -->
<!-- wp:list {"ordered":true} --><ol class="wp-block-list"><!-- wp:list-item --><li>Ordered list item</li><!-- /wp:list-item --><!-- wp:list-item --><li>Another item</li><!-- /wp:list-item --></ol><!-- /wp:list -->
<!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#">Primary button</a></div><!-- /wp:button --><!-- wp:button {"className":"is-style-outline"} --><div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="#">Outline button</a></div><!-- /wp:button --></div><!-- /wp:buttons -->
<!-- wp:table --><figure class="wp-block-table"><table class="has-fixed-layout"><thead><tr><th>Column</th><th>Column</th></tr></thead><tbody><tr><td>Cell</td><td>Cell</td></tr><tr><td>Cell</td><td>Cell</td></tr></tbody></table></figure><!-- /wp:table -->
<!-- wp:code --><pre class="wp-block-code"><code>const measure = "45–75ch";</code></pre><!-- /wp:code -->
<!-- wp:search {"label":"Search","showLabel":false,"buttonText":"Search"} /-->
BODY
sink_id=$(wp post create "$TMP/kitchen-sink.html" --post_type=post --post_status=publish --post_author=1 --post_date="$(date -d "@$(( $(date +%s) - 400 * 86400 ))" "+%Y-%m-%d 09:00:00")" --post_title="Kitchen sink: every block and style" --post_name="kitchen-sink" --post_category=design --tags_input=tools --meta_input='{"_serif_seed":"1"}' --porcelain)
wp media import "https://picsum.photos/seed/serif-kitchen-sink/1600/1000.jpg" --post_id="$sink_id" --featured_image --porcelain >/dev/null 2>&1 || true
log "  #$sink_id Kitchen sink"

# ---------------------------------------------------------------------------
# Comments (on the two newest posts, including a threaded reply)
# ---------------------------------------------------------------------------
log "Comments"
newest=$(head -n1 "$TMP/post-ids")
second=$(sed -n 2p "$TMP/post-ids")
c1=$(wp comment create --comment_post_ID="$newest" --comment_author="Eleni" --comment_author_email="eleni@example.com" \
	--comment_content="This is the first thing I've read all week that made me slow down. Thank you." --comment_approved=1 --porcelain)
wp comment create --comment_post_ID="$newest" --comment_parent="$c1" --comment_author="Marios G." --comment_author_email="admin@example.com" --user_id=1 \
	--comment_content="That's the nicest possible outcome. Thanks for reading." --comment_approved=1 --porcelain >/dev/null
wp comment create --comment_post_ID="$newest" --comment_author="Tom" --comment_author_email="tom@example.com" \
	--comment_content="Disagree slightly on the measure — 80 characters has never bothered me — but the point about the return stroke is well taken." --comment_approved=1 --porcelain >/dev/null
wp comment create --comment_post_ID="$second" --comment_author="Priya" --comment_author_email="priya@example.com" \
	--comment_content="Tried this for three days. Two of them were great." --comment_approved=1 --porcelain >/dev/null

# ---------------------------------------------------------------------------
# Navigation menu (block theme: a wp_navigation post; the Navigation block
# with no ref falls back to the most recent one)
# ---------------------------------------------------------------------------
log "Navigation"
cat > "$TMP/nav.html" <<'BODY'
<!-- wp:navigation-link {"label":"Journal","type":"page","kind":"post-type","url":"/journal/"} /-->
<!-- wp:navigation-link {"label":"Essays","type":"category","kind":"taxonomy","url":"/category/essays/"} /-->
<!-- wp:navigation-link {"label":"Reporting","type":"category","kind":"taxonomy","url":"/category/reporting/"} /-->
<!-- wp:navigation-link {"label":"Notes","type":"category","kind":"taxonomy","url":"/category/notes/"} /-->
<!-- wp:navigation-link {"label":"About","type":"page","kind":"post-type","url":"/about/"} /-->
BODY
wp post create "$TMP/nav.html" --post_type=wp_navigation --post_status=publish --post_title="Primary" --post_name="primary" --porcelain >/dev/null

# ---------------------------------------------------------------------------
# Site logo
# ---------------------------------------------------------------------------
logo=$(wp media import "$THEME_DIR/assets/images/logo.png" --title="Site logo" --porcelain 2>/dev/null || true)
[ -n "$logo" ] && wp option update site_logo "$logo" >/dev/null && log "Site logo #$logo"

# ---------------------------------------------------------------------------
# Done
# ---------------------------------------------------------------------------
wp transient delete --all >/dev/null 2>&1 || true
wp transient delete --all --network >/dev/null 2>&1 || true
wp cache flush >/dev/null 2>&1 || true
wp option update "$MARKER" 1 >/dev/null
rm -rf "$TMP"
# `wp site empty` logs a core deprecation; start the theme's debug.log clean.
rm -f wp-content/debug.log
log "Seeded. $(wp post list --post_type=post --format=count) posts, $(wp post list --post_type=page --format=count) pages, $(wp comment list --format=count) comments."
