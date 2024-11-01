<?php


add_action('wp_footer', 'siw_cookie_script', 100);
function siw_cookie_script(){ ?>

    <!-- Init the script -->
    <script>
        COOKIES_ENABLER.init({
            scriptClass: 'siw-script',
            iframeClass: 'siw-iframe',
            acceptClass: 'siw-accept',
            disableClass: 'siw-disable',
            dismissClass: 'siw-dismiss',
            bannerClass: 'siw_banner-wrapper',
            bannerHTML:
                document.getElementById('siw-banner-html') !== null ?
                    document.getElementById('siw-banner-html').innerHTML :
                    '<div class="siw_banner top light siw_container siw_container--open">'
                    + '<a href="#" class="siw_btn siw-accept siw_btn_accept_all">'
                    + 'Accepteer cookies'
                    + '<\/a>'
                    + '<\/div>',
            eventScroll: false,
            scrollOffset: 20,
            clickOutside: false,
            cookieName: 'siw-cookie',
            forceReload: false,
            iframesPlaceholder: true,
            iframesPlaceholderClass: 'siw-iframe-placeholder',
            iframesPlaceholderHTML:
                document.getElementById('siw-iframePlaceholder-html') !== null ?
                    document.getElementById('siw-iframePlaceholder-html').innerHTML :
                    '<p><?php echo 'Deze website maakt gebruik van een iframe wat cookies gebruikt.'  ?>'
                    + '<a href="#" class="siw_btn siw-accept">Accepteer cookies</a>'
                    + '<\/p>'
        });
    </script>
    <!-- End Ginger Script -->

<?php }


