/**
 * Serif front-end script.
 *
 * Search overlay: the header search icon opens a native <dialog> rendered by
 * inc/search.php. Without JS the icon falls back to core's expanding field.
 */
( () => {
	const dialog = document.getElementById( 'serif-search' );
	if ( ! dialog || typeof dialog.showModal !== 'function' ) {
		return;
	}

	const input = dialog.querySelector( 'input[type="search"]' );
	let opener = null;

	const open = () => {
		if ( dialog.open ) {
			return;
		}
		opener = document.activeElement;
		dialog.showModal();
		input?.focus();
		input?.select();
	};

	// Animate out, then close. The native close() is instant; `cancel` (Esc)
	// is intercepted so it takes the same path.
	const close = () => {
		if ( ! dialog.open || dialog.classList.contains( 'is-closing' ) ) {
			return;
		}
		const reduce = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
		if ( reduce ) {
			dialog.close();
			return;
		}
		dialog.classList.add( 'is-closing' );
		const done = () => {
			dialog.classList.remove( 'is-closing' );
			dialog.close();
		};
		dialog.querySelector( '.serif-search__panel' ).addEventListener( 'animationend', done, { once: true } );
		setTimeout( done, 300 ); // safety net if animationend never fires
	};

	dialog.addEventListener( 'cancel', ( event ) => {
		event.preventDefault();
		close();
	} );

	// Header trigger. Capture phase so core's "expand the field" handler never runs.
	document.addEventListener(
		'click',
		( event ) => {
			const trigger = event.target.closest( '.serif-header__search .wp-block-search__button' );
			if ( ! trigger ) {
				return;
			}
			event.preventDefault();
			event.stopPropagation();
			open();
		},
		true
	);

	dialog.querySelector( '.serif-search__close' )?.addEventListener( 'click', close );

	// Click on the backdrop (outside the panel) closes.
	dialog.addEventListener( 'click', ( event ) => {
		if ( event.target === dialog ) {
			close();
		}
	} );

	// Return focus to whatever opened the dialog.
	dialog.addEventListener( 'close', () => opener?.focus?.() );

	// "/" opens search unless the user is typing somewhere.
	document.addEventListener( 'keydown', ( event ) => {
		if ( event.key !== '/' || event.ctrlKey || event.metaKey || event.altKey ) {
			return;
		}
		const tag = document.activeElement?.tagName;
		if ( tag === 'INPUT' || tag === 'TEXTAREA' || document.activeElement?.isContentEditable ) {
			return;
		}
		event.preventDefault();
		open();
	} );

	document.documentElement.classList.add( 'serif-has-search-overlay' );
} )();
