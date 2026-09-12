#!/usr/bin/env sh
# Build a distributable theme zip at build/serif.zip.
# Run via `bun run bundle` (which runs `bun run build` first).
set -eu

cd "$(dirname "$0")/.."

OUT=build/serif
rm -rf build
mkdir -p "$OUT/assets"

# Theme root files and directories.
cp style.css functions.php theme.json LICENSE CREDITS.md "$OUT/"
cp -r inc templates parts patterns styles "$OUT/"
[ -f screenshot.png ] && cp screenshot.png "$OUT/"
[ -f readme.txt ] && cp readme.txt "$OUT/"
[ -f changelog.txt ] && cp changelog.txt "$OUT/"
[ -d languages ] && [ -n "$(ls -A languages)" ] && cp -r languages "$OUT/"

# Compiled/static assets only — no SCSS or JS sources.
cp -r assets/css assets/fonts "$OUT/assets/"
[ -d assets/images ] && cp -r assets/images "$OUT/assets/"
mkdir -p "$OUT/assets/js"
cp assets/js/*.js "$OUT/assets/js/" 2>/dev/null || true

# Drop empty directories so the zip only contains what WordPress reads.
find "$OUT" -type d -empty -delete

(cd build && bestzip serif.zip serif)
rm -rf "$OUT"
echo "Wrote build/serif.zip ($(du -h build/serif.zip | cut -f1))"
