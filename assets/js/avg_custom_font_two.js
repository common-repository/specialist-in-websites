jQuery( document ).ready( function( $ ) {
    if(! $('body').hasClass("wp-admin")) {
        var checkExist = setInterval(function () {
            var $fontFamily = jQuery("h1").css("font-family"),
                $fontWeight = jQuery("h1").css("font-weight"),
                $fontSpacing = jQuery("h1").css("letter-spacing"),
                $fontTTransform = jQuery("h1").css("text-transform");
            if ($('.cookie-bar-cb').length > 0) {
                $('.cookie-bar-cb h2').css({
                    "font-family": $fontFamily,
                    "font-weight": $fontWeight,
                    "letter-spacing": $fontSpacing,
                    "text-transform": $fontTTransform
                });
                clearInterval(checkExist);
            }
        });
    }
});