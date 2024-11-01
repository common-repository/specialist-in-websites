function delete_cookie(name, url) {
    document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    if(typeof url !== 'undefined'){
        window.location.href = url;
    }else{
        location.reload();
    }
}




