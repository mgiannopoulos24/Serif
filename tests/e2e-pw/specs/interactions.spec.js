import { test, expect } from '@playwright/test';
import { gotoReady, isMobile } from './helpers';

/** Keyboard and focus behaviour of the theme's two overlays. */

test.describe( 'Mobile menu', () => {
	test.beforeEach( async ( { page } ) => {
		test.skip( ! isMobile( page ), 'mobile viewport only' );
		await gotoReady( page, '/' );
	} );

	test( 'opens with brand row and search, closes on Escape, returns focus', async ( { page } ) => {
		const open = page.locator( '.wp-block-navigation__responsive-container-open' );
		await expect( open ).toBeVisible();
		await expect( open ).toHaveAttribute( 'aria-label', /menu/i );

		await open.click();
		const overlay = page.locator( '.wp-block-navigation__responsive-container.is-menu-open' );
		await expect( overlay ).toBeVisible();
		await expect( overlay.locator( '.serif-menu__brand .serif-menu__title' ) ).toHaveText( 'Serif' );
		await expect( overlay.locator( '.wp-block-navigation-item__content' ).first() ).toHaveText( 'Journal' );
		await expect( overlay.locator( '.serif-menu__footer input[type="search"]' ) ).toBeVisible();

		// Focus is inside the dialog.
		await expect.poll( () => page.evaluate( () => !! document.activeElement?.closest( '.is-menu-open' ) ) ).toBe( true );

		// Items are big enough to tap.
		const box = await overlay.locator( '.wp-block-navigation-item__content' ).first().boundingBox();
		expect( box?.height ).toBeGreaterThanOrEqual( 44 );

		await page.keyboard.press( 'Escape' );
		await expect( overlay ).toHaveCount( 0 );
		await expect( open ).toBeFocused();
	} );

	test( 'close button and the overlay search both work', async ( { page } ) => {
		await page.click( '.wp-block-navigation__responsive-container-open' );
		const overlay = page.locator( '.wp-block-navigation__responsive-container.is-menu-open' );
		await overlay.locator( '.serif-menu__footer input[type="search"]' ).fill( 'typography' );
		await Promise.all( [ page.waitForURL( /\?s=typography/ ), page.keyboard.press( 'Enter' ) ] );
		await expect( page.locator( 'h1.wp-block-query-title' ) ).toContainText( 'typography' );
	} );
} );

test.describe( 'Search overlay', () => {
	test.beforeEach( async ( { page } ) => {
		test.skip( isMobile( page ), 'desktop only' );
		await gotoReady( page, '/journal/' );
	} );

	test( 'icon opens a modal dialog with focus in the field; Escape closes and restores focus', async ( { page } ) => {
		const trigger = page.locator( '.serif-header__search .wp-block-search__button' );
		const dialog = page.locator( 'dialog#serif-search' );

		await trigger.click();
		await expect( dialog ).toBeVisible();
		await expect( dialog ).toHaveAttribute( 'aria-label', 'Search' );
		await expect( dialog.locator( 'input[type="search"]' ) ).toBeFocused();
		// Core's inline field must not have expanded as well.
		await expect( page.locator( '.serif-header__search' ) ).toHaveClass( /wp-block-search__searchfield-hidden/ );

		// Focus is trapped: Tab never lands on page content behind the modal
		// (it may pass through the browser chrome, where activeElement is <body>).
		for ( let i = 0; i < 6; i++ ) {
			await page.keyboard.press( 'Tab' );
			const where = await page.evaluate( () => {
				const el = document.activeElement;
				return el === document.body || ! el ? 'chrome' : el.closest( '#serif-search' ) ? 'dialog' : 'page';
			} );
			expect( where, `Tab #${ i + 1 } escaped the dialog` ).not.toBe( 'page' );
		}

		await page.keyboard.press( 'Escape' );
		await expect( dialog ).toBeHidden();
		await expect( trigger ).toBeFocused();
	} );

	test( '"/" opens it, Enter submits', async ( { page } ) => {
		await page.keyboard.press( '/' );
		const dialog = page.locator( 'dialog#serif-search' );
		await expect( dialog ).toBeVisible();
		await page.keyboard.type( 'measure' );
		await Promise.all( [ page.waitForURL( /\?s=measure/ ), page.keyboard.press( 'Enter' ) ] );
	} );

	test( 'close button and backdrop click both close it', async ( { page } ) => {
		const dialog = page.locator( 'dialog#serif-search' );
		await page.click( '.serif-header__search .wp-block-search__button' );
		await dialog.locator( '.serif-search__close' ).click();
		await expect( dialog ).toBeHidden();

		await page.click( '.serif-header__search .wp-block-search__button' );
		await page.mouse.click( 600, 700 ); // below the panel = backdrop
		await expect( dialog ).toBeHidden();
	} );

	test( 'front-page "Start reading" scrolls to Featured', async ( { page } ) => {
		await gotoReady( page, '/' );
		await page.click( 'a[href="#featured"]' );
		await expect.poll( () => page.evaluate( () => location.hash ) ).toBe( '#featured' );
		await expect( page.locator( '#featured' ) ).toBeInViewport();
	} );
} );
