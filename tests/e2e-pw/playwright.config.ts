import { defineConfig, devices } from '@playwright/test';

/**
 * Serif end-to-end + accessibility tests.
 *
 *   bun run test:e2e            # everything
 *   bun run test:a11y           # axe scans only
 *
 * Expects the wp-env site (bun run start) — it is started if not reachable.
 * The seed script gives every template real content to render.
 *
 * Specs are plain JS (with JSDoc types): when Playwright runs under Bun, its
 * TypeScript transform is bypassed for test files. Shared helpers can be TS.
 */
const baseURL = process.env.WP_BASE_URL ?? 'http://localhost:8888';

export default defineConfig( {
	testDir: './specs',
	fullyParallel: true,
	forbidOnly: !! process.env.CI,
	retries: process.env.CI ? 1 : 0,
	reporter: [
		[ 'list' ],
		[ 'html', { open: 'never', outputFolder: 'playwright-report' } ],
	],
	outputDir: 'test-results',
	use: {
		baseURL,
		trace: 'retain-on-failure',
		screenshot: 'only-on-failure',
	},
	projects: [
		{
			name: 'desktop',
			use: { ...devices[ 'Desktop Chrome' ] },
			testIgnore: /variations\.spec\.[jt]s/,
		},
		{
			name: 'mobile',
			use: { ...devices[ 'Pixel 7' ] },
			testIgnore: /variations\.spec\.[jt]s/,
		},
		{
			// Switches the site's global styles, so it must not overlap other scans.
			name: 'variations',
			use: { ...devices[ 'Desktop Chrome' ] },
			testMatch: /variations\.spec\.[jt]s/,
			dependencies: [ 'desktop', 'mobile' ],
		},
	],
	webServer: {
		command: 'bun run start',
		url: baseURL,
		reuseExistingServer: true,
		timeout: 300_000,
	},
} );
