if (document.getElementById("ie-error")) {
    console.log(document.getElementsByClassName("ie-error"));
    document.getElementById('siw_pop_up_close').onclick = function () {
        const cookieDate = new Date();
        cookieDate.setDate(cookieDate.getDate() + 7);
        document.cookie = "ie_pop_up=true; expires="+cookieDate.toUTCString()+"; path=/";
        document.getElementsByClassName('ie-error')[0].style.display = 'none';
    };
}

if(document.getElementsByClassName('siw-snow').length > 0) {
    document.getElementById('login').innerHTML = this;
}