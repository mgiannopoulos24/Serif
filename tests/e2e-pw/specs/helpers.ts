import { expect } from '@playwright/test';
import type { Page } from '@playwright/test';
import { AxeBuilder } from '@axe-core/playwright';
import type { Result as AxeViolation } from 'axe-core';
import { execSync } from 'node:child_process';

/** Routes created by scripts/seed.sh, keyed by the template that renders them. */
export const routes = {
	'front-page': '/',
	home: '/journal/',
	'home (page 2)': '/journal/page/2/',
	single: '/slow-return-long-read/',
	'single (kitchen sink)': '/kitchen-sink/',
	page: '/about/',
	'page (patterns)': '/patterns/',
	'archive (category)': '/category/design/',
	'archive (tag)': '/tag/typography/',
	search: '/?s=typography',
	'search (no results)': '/?s=zzzzqqqx',
	'404': '/this-page-does-not-exist/',
} as const;

/** WCAG 2.2 AA plus axe best practices. */
export const axeTags = [ 'wcag2a', 'wcag2aa', 'wcag21a', 'wcag21aa', 'wcag22aa', 'best-practice' ];

export const isMobile = ( page: Page ) => ( page.viewportSize()?.width ?? 1200 ) < 600;

export async function gotoReady( page: Page, path: string ) {
	await page.goto( path, { waitUntil: 'networkidle' } );
	await page.evaluate( () => document.fonts.ready );
}

function describeViolations( violations: AxeViolation[] ) {
	return violations
		.map( ( v ) => {
			const nodes = v.nodes.slice( 0, 5 ).map( ( n ) => `      - ${ n.target.join( ' ' ) }\n        ${ n.failureSummary?.split( '\n' ).join( '\n        ' ) }` ).join( '\n' );
			return `  [${ v.impact }] ${ v.id }: ${ v.help } (${ v.helpUrl })\n${ nodes }`;
		} )
		.join( '\n\n' );
}

/** Run axe on the current page state and fail with a readable report. */
export async function expectAccessible( page: Page, label: string, options: { exclude?: string[]; disableRules?: string[] } = {} ) {
	let builder = new AxeBuilder( { page } ).withTags( axeTags );
	for ( const sel of options.exclude ?? [] ) {
		builder = builder.exclude( sel );
	}
	if ( options.disableRules?.length ) {
		builder = builder.disableRules( options.disableRules );
	}
	const results = await builder.analyze();
	expect( results.violations, `axe violations on ${ label }:\n${ describeViolations( results.violations ) }` ).toEqual( [] );
}

/** Run a wp-cli command in the wp-env cli container. */
export function wp( command: string ) {
	return execSync( `bun run --silent wp-env run cli ${ command }`, { encoding: 'utf8', stdio: [ 'ignore', 'pipe', 'pipe' ] } );
}

export function setStyleVariation( slug: string ) {
	wp( `wp eval-file wp-content/themes/serif/scripts/set-style-variation.php ${ slug }` );
}
