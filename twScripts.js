jQuery(document).on( "click", ".js-twSearch", function( event ) {
    jQuery('.twSearchFormWrapper').animate({right: 0, easing: "easein"}, 250);
    jQuery('.twSearchPopup').show();
    jQuery(".twSearchBox").focus();
    jQuery('.twSearchBg').click(function() {
        jQuery('.twSearchPopup').hide();
        jQuery('.twSearchFormWrapper').animate({right: '-100%'}, 50);
    });
    event.preventDefault();
});