jQuery( document ).ready( function( $ ) {
    if(! $('body').hasClass("wp-admin")) {
        var checkExist = setInterval(function () {
            var $fontFamily = jQuery("body").css("font-family");
            if ($('.cookie-bar-cb').length > 0) {
                $('.cookie-bar-cb .siw-accept, .cookie-bar-cb .cookie-content-txt-cb, .cookie-bar-cb .cookie-readmore-cb a,.cookie-bar-cb .CybotCookiebotDialogBodyLevelButton + label').css({
                    "font-family": $fontFamily
                });
                clearInterval(checkExist);
            }
        });
    }
});