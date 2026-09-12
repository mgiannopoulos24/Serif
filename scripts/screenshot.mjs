/**
 * Regenerate screenshot.png (theme) and serif-child/screenshot.png (child) from
 * the running wp-env site. WordPress wants 1200×900.
 *
 *   bun run screenshot            # both
 *   bun run screenshot -- /about/ # a different page
 */
import { chromium } from 'playwright';
import { fileURLToPath } from 'node:url';
import path from 'node:path';

const root = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const base = process.env.WP_URL ?? 'http://localhost:8888';
const route = process.argv[2] ?? '/';

const childBadge = `body::after{content:"Child theme";position:fixed;top:88px;right:24px;z-index:9999;
	font:600 12px/1 "IBM Plex Sans",sans-serif;letter-spacing:.08em;text-transform:uppercase;
	color:#8b3a2a;border:1px solid #8b3a2a;border-radius:4px;padding:6px 8px;background:#faf8f5}`;

const browser = await chromium.launch();
const page = await browser.newPage({ viewport: { width: 1200, height: 900 }, deviceScaleFactor: 1 });
await page.goto(base + route, { waitUntil: 'networkidle' });
await page.evaluate(() => document.fonts.ready);

const shot = (file, css) => page
	.evaluate(() => { document.getElementById('serif-shot-css')?.remove(); })
	.then(() => css && page.addStyleTag({ content: css }).then((el) => el.evaluate((n) => (n.id = 'serif-shot-css'))))
	.then(() => page.screenshot({ path: file, clip: { x: 0, y: 0, width: 1200, height: 900 } }))
	.then(() => console.log('wrote', path.relative(root, file)));

await shot(path.join(root, 'screenshot.png'));
await shot(path.join(root, 'serif-child', 'screenshot.png'), childBadge);
await browser.close();
