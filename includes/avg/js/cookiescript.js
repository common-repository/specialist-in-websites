function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

jQuery( document ).ready( function( $ ) {
    var $siwip = $("[name='siw-ip']");
    if ($siwip.data("check") === 'on') {
        if ($siwip.attr("content").toString() === "46.44.132.128") {
            console.log("%c SIW IP found, no cookies for you!", 'color: #0055b8; background: white');
            return;
        }
        if ($("#wpadminbar .ab-item img").length > 1) {
            console.log("%c User is logged in, SiW TO AVG disabled.", 'color: #e70000; background: white');
            return;
        }
    } else if($siwip.data("check") === 'off') {
        console.log("%c Check is off, show me the goods.", 'color: #0055b8; background: white');
    }

    var $visible = false;
    if(! getCookie('siw-cookie')) {
        var intervalListener = self.setInterval(function () {
            if ($('#CybotCookiebotDialog').length > 0) {
                $('.siw_banner-wrapper').show();
                console.log('%cYummy, cookies! ', 'color: #bada55');
                window.clearInterval(intervalListener);
            } else if ($('#CybotCookiebotDialog').length <= 0) {
                $('.siw_banner-wrapper').hide();
                console.log('%c No cookies found, please check any errors above or disable any adblock extensions.', 'color: #ff0000');
            }
            if ($('#CybotCookiebotDialog').length > 0) {
                if (!$('#CybotCookiebotDialogBody').hasClass('cookie-dialogbody-cb')) {
                    $('.siw_banner > .siw-accept').appendTo("#CybotCookiebotDialogBodyLevelButtonsRow");
                    $('#CybotCookiebotDialogBodyLevelButtonAccept').css({"display": "none"});
                    $('.siw-accept').on("click", function () {
                        $("#CybotCookiebotDialogBodyLevelButtonAccept").click();
                    });
                    $('#CybotCookiebotDialog').addClass("cookie-bar-cb");
                    $('#CybotCookiebotDialogBodyContent').addClass("cookie-content-cb");
                    $('#CybotCookiebotDialogBodyContentText').addClass("cookie-content-txt-cb");
                    $('#CybotCookiebotDialogBodyLevelWrapper').addClass("cookie-buttonwpr-cb");
                    $('#CybotCookiebotDialogBody').addClass("cookie-dialogbody-cb");
                    $('#CybotCookiebotDialogBodyLevelButtons').addClass("cookie-cookiebuttons-cb");
                    $('#CybotCookiebotDialogBodyLevelButtonsSelectPane').addClass("cookie-selectplane-cb");
                    $('#CybotCookiebotDialogBodyLevelDetailsWrapper').addClass("cookie-readmore-cb");
                    $('.CybotCookiebotDialogBodyLevelButtonWrapper').addClass("cookie-cookiebutton-wrapper-cb");
                    $('.cookie-cookiebuttons-cb').find('input').append("<span class=\"checkmark\"></span>");
                    $('#CybotCookiebotDialogPoweredbyLink').addClass("cookie-imgcontainer-cb").appendTo('.cookie-content-cb');

                    // $('.siw_banner-wrapper').remove();
                }
            }
        }, 500);
    } else {
        console.log('%cCookies already accepted, thank you! ', 'color: #bada55');
    }
    var cookie_timeout = setTimeout( function() {
        if($('#CybotCookiebotDialogBody').hasClass('cookie-dialogbody-cb')){
            // console.log("Cookies found, stopped checking.");
            clearTimeout(cookie_timeout);
        } else {
            console.log("No cookies found, stopped checking for cookies.");
            clearTimeout(cookie_timeout);
            window.clearInterval(intervalListener);
        }
    }, 5000);


});