jQuery( document ).on( 'click', '.js-twSearch', function( event ) {
    jQuery( '.twSearchFormWrapper' ).animate( { right: 0, easing: 'easein' }, 250 );
    jQuery( '.twSearchPopup' ).show();
    jQuery( '.twSearchBox' ).focus();
    jQuery( '.twSearchBg' ).click( function() {
        closeSearch();
    } );
    jQuery( document ).on( 'keydown', function ( event ) {
        if ( event.keyCode === 27 && jQuery('.twSearchBg').is(':visible')) { // 27 = Escape
            closeSearch();
        }
    });

    event.preventDefault();
} );

function closeSearch() {
    jQuery( '.twSearchPopup' ).hide();
    jQuery( '.twSearchFormWrapper' ).animate( { right: '-100%' }, 50 );
}
