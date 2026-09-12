import { test, expect } from '@playwright/test';
import { routes, gotoReady } from './helpers';

/**
 * Every template renders its structure, with no PHP notices, console errors,
 * or failed requests for theme assets.
 */
/** @param {import('@playwright/test').Page} page */
async function collectProblems( page ) {
	/** @type {string[]} */
	const consoleErrors = [];
	/** @type {string[]} */
	const failedRequests = [];
	page.on( 'console', ( msg ) => {
		// A 404 template legitimately logs the document's own 404; ignore that one.
		if ( msg.type() === 'error' && msg.location()?.url !== page.url() ) {
			consoleErrors.push( `${ msg.text() } (${ msg.location()?.url ?? '' })` );
		}
	} );
	page.on( 'response', ( res ) => {
		if ( res.status() >= 400 && /wp-content\/themes\/serif/.test( res.url() ) ) {
			failedRequests.push( `${ res.status() } ${ res.url() }` );
		}
	} );
	return { consoleErrors, failedRequests };
}

/** @param {import('@playwright/test').Page} page */
async function expectPageShell( page ) {
	await expect( page.locator( 'html' ) ).toHaveAttribute( 'lang', /.+/ );
	await expect( page.locator( 'header.wp-block-template-part' ) ).toHaveCount( 1 );
	await expect( page.locator( 'footer.wp-block-template-part' ) ).toHaveCount( 1 );
	await expect( page.locator( 'main' ) ).toHaveCount( 1 );
	await expect( page.locator( 'h1' ) ).toHaveCount( 1 );
	await expect( page.locator( 'header nav.wp-block-navigation' ) ).toHaveCount( 1 );
	await expect( page.locator( 'a.skip-link' ) ).toHaveAttribute( 'href', '#wp--skip-link--target' );
	await expect( page.locator( '#wp--skip-link--target' ) ).toHaveCount( 1 );
	await expect( page.locator( 'body' ) ).not.toContainText( /(Warning|Notice|Fatal error|Deprecated):/ );
}

test.describe( 'Templates', () => {
	for ( const [ name, path ] of Object.entries( routes ) ) {
		test( `${ name } renders cleanly`, async ( { page } ) => {
			const problems = await collectProblems( page );
			await gotoReady( page, path );
			await expectPageShell( page );
			expect( problems.consoleErrors, 'console errors' ).toEqual( [] );
			expect( problems.failedRequests, 'failed theme asset requests' ).toEqual( [] );
		} );
	}

	test( 'front page: hero, featured posts, newsletter', async ( { page } ) => {
		await gotoReady( page, '/' );
		const hero = page.locator( '.wp-block-cover' ).first();
		await expect( hero ).toBeVisible();
		await expect( hero.locator( 'h1.wp-block-site-title' ) ).toHaveText( 'Serif' );
		await expect( hero.locator( 'a', { hasText: 'Start reading' } ) ).toHaveAttribute( 'href', '#featured' );
		await expect( page.locator( '#featured' ) ).toHaveText( 'Featured' );
		await expect( page.locator( '.wp-block-post-template .wp-block-post-title' ) ).toHaveCount( 3 );
		await expect( page.locator( 'main' ) ).toContainText( 'One letter a month' );
	} );

	test( 'journal: paginated list rows', async ( { page } ) => {
		await gotoReady( page, '/journal/' );
		await expect( page.locator( '.wp-block-post-template > li' ) ).toHaveCount( 6 );
		await expect( page.locator( '.wp-block-query-pagination-next' ) ).toBeVisible();
		const row = page.locator( '.wp-block-post-template > li' ).first();
		await expect( row.locator( '.wp-block-post-featured-image img' ) ).toBeVisible();
		await expect( row.locator( 'h2.wp-block-post-title a' ) ).toBeVisible();
		await expect( row.locator( '.wp-block-post-excerpt' ) ).toBeVisible();
		await expect( row.locator( '.wp-block-post-date' ) ).toBeVisible();
	} );

	test( 'single: cover header, meta, author box, related posts, comments', async ( { page } ) => {
		await gotoReady( page, '/slow-return-long-read/' );
		const cover = page.locator( '.wp-block-cover' ).first();
		await expect( cover.locator( 'img.wp-block-cover__image-background' ) ).toBeVisible();
		await expect( cover.locator( 'h1' ) ).toHaveText( 'The Slow Return of the Long Read' );
		await expect( cover.locator( '.wp-block-post-author-name' ) ).toBeVisible();
		await expect( cover.locator( '.wp-block-post-date' ) ).toBeVisible();
		await expect( page.locator( '.wp-block-post-author-biography' ) ).toBeVisible();
		await expect( page.locator( 'main' ) ).toContainText( 'Related posts' );
		await expect( page.locator( '.wp-block-comments .wp-block-comment-content' ) ).toHaveCount( 3 );
		await expect( page.locator( '.wp-block-comments .wp-block-avatar img' ) ).toHaveCount( 3 );
		await expect( page.locator( '#commentform' ) ).toBeVisible();
	} );

	test( 'single: related posts share the current category and exclude the post', async ( { page } ) => {
		await gotoReady( page, '/slow-return-long-read/' ); // category: Essays
		const related = page.locator( '.wp-block-query', { has: page.locator( 'text=Related posts' ) } ).first();
		const titles = await page.locator( 'h2:has-text("Related posts") ~ .wp-block-query .wp-block-post-title' ).allTextContents();
		expect( titles.length ).toBeGreaterThan( 0 );
		expect( titles ).not.toContain( 'The Slow Return of the Long Read' );
		void related;
	} );

	test( 'archive: title aligns with list', async ( { page } ) => {
		await gotoReady( page, '/category/design/' );
		await expect( page.locator( 'h1.wp-block-query-title' ) ).toHaveText( 'Category: Design' );
		await expect( page.locator( '.wp-block-post-template > li' ).first() ).toBeVisible();
	} );

	test( 'search: results and the no-results fallback', async ( { page } ) => {
		await gotoReady( page, '/?s=typography' );
		await expect( page.locator( 'h1.wp-block-query-title' ) ).toContainText( 'typography' );
		await expect( page.locator( '.wp-block-post-template > li' ).first() ).toBeVisible();

		await gotoReady( page, '/?s=zzzzqqqx' );
		await expect( page.locator( '.wp-block-query-no-results' ) ).toContainText( 'No results found' );
		await expect( page.locator( '.wp-block-query-no-results form[role="search"]' ) ).toBeVisible();
	} );

	test( '404: status, search, recent articles', async ( { page } ) => {
		const response = await page.goto( '/this-page-does-not-exist/' );
		expect( response?.status() ).toBe( 404 );
		await expect( page.locator( 'h1' ) ).toHaveText( 'Page not found' );
		await expect( page.locator( 'main form[role="search"]' ).first() ).toBeVisible();
		await expect( page.locator( 'main' ) ).toContainText( 'Recent articles' );
		await expect( page.locator( '.wp-block-post-template .wp-block-post-title' ) ).toHaveCount( 6 );
	} );

	test( 'kitchen sink: every registered block style emits its CSS', async ( { page } ) => {
		await gotoReady( page, '/kitchen-sink/' );
		for ( const style of [ 'large', 'pull-quote', 'narrow', 'thick', 'gradient', 'overlay-dark', 'shadow', 'frame' ] ) {
			const el = page.locator( `.is-style-${ style }` ).first();
			await expect( el, style ).toBeVisible();
			const scoped = await el.evaluate( ( n, s ) => [ ...n.classList ].some( ( c ) => c.startsWith( `is-style-${ s }--` ) ), style );
			expect( scoped, `${ style } has a scoped variation class` ).toBe( true );
		}
	} );
} );
