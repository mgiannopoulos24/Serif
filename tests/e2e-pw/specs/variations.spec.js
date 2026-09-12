import { test } from '@playwright/test';
import { gotoReady, expectAccessible, setStyleVariation } from './helpers';

/**
 * Colour contrast under every style variation. Switches the site's global
 * styles via wp-cli, so this runs in its own project after everything else.
 */
const variations = [ 'default', 'dark', 'sepia', 'high-contrast' ];
const pages = { 'front-page': '/', single: '/slow-return-long-read/', 'kitchen sink': '/kitchen-sink/', archive: '/category/design/' };

test.describe.configure( { mode: 'serial' } );

test.afterAll( () => setStyleVariation( 'default' ) );

for ( const variation of variations ) {
	test.describe( `variation: ${ variation }`, () => {
		test.beforeAll( () => setStyleVariation( variation ) );

		for ( const [ name, path ] of Object.entries( pages ) ) {
			test( `${ name } passes axe`, async ( { page } ) => {
				await gotoReady( page, path );
				await expectAccessible( page, `${ name } under "${ variation }"` );
			} );
		}
	} );
}
