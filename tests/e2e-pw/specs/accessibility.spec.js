import { test, expect } from '@playwright/test';
import { routes, gotoReady, expectAccessible, isMobile } from './helpers';

/**
 * axe-core scans (WCAG 2.2 AA + best practices) of every template, plus the
 * interactive states the theme adds: the mobile menu and the search overlay.
 */
test.describe( 'Accessibility (axe)', () => {
	for ( const [ name, path ] of Object.entries( routes ) ) {
		test( `${ name } — ${ path }`, async ( { page } ) => {
			await gotoReady( page, path );
			await expectAccessible( page, `${ name } (${ path })` );
		} );
	}

	test( 'mobile menu open', async ( { page } ) => {
		test.skip( ! isMobile( page ), 'mobile viewport only' );
		await gotoReady( page, '/' );
		await page.click( '.wp-block-navigation__responsive-container-open' );
		await expect( page.locator( '.wp-block-navigation__responsive-container.is-menu-open' ) ).toBeVisible();
		await page.waitForTimeout( 400 ); // let the item stagger finish
		await expectAccessible( page, 'mobile menu (open)' );
	} );

	test( 'search overlay open', async ( { page } ) => {
		test.skip( isMobile( page ), 'the header search trigger is desktop-only; mobile has search in the menu' );
		await gotoReady( page, '/journal/' );
		await page.click( '.serif-header__search .wp-block-search__button' );
		await expect( page.locator( 'dialog#serif-search' ) ).toBeVisible();
		await page.waitForTimeout( 300 );
		await expectAccessible( page, 'search overlay (open)' );
	} );
} );
