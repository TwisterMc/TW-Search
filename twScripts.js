jQuery(document).on( "click", ".js-search", function() {
    jQuery('.search-form').animate({right: 0, easing: "easein"}, 250);
    jQuery('.search-popup').show();
    jQuery(".searchBox").focus();
    jQuery('.search-bg').click(function() {
        jQuery('.search-popup').hide();
        jQuery('.search-form').animate({right: '-100%'}, 50);
    });
});