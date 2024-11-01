jQuery('document').ready(function($){
    if ($("#ie-error")) {
        $('#siw_pop_up_close').on('click', function(){
            const cookieDate = new Date();
            cookieDate.setDate(cookieDate.getDate() + 7);
            document.cookie = "ie_pop_up=true; expires="+cookieDate.toUTCString()+"; path=/";
            $("#ie-error").fadeOut();
        });
    }
});
