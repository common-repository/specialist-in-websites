<?php
/* SIW AVG CORE */


function siw_run(){
    if (is_feed()) return;

    if (isset($_COOKIE['siw-cookie']) && $_COOKIE['siw-cookie'] == 'Y'):
	    //something
    endif;

    ob_start();
    add_action('shutdown', '__shutdown', 0);
    add_filter('final_output', 'siw_parse_dom');
}
add_action('wp', 'siw_run');


function __shutdown(){
    $final = '';
    // We'll need to get the number of ob levels we're in, so that we can iterate over each, collecting
    // that buffer's output into the final output.
    $levels = ob_get_level();
    for ($i = 0; $i < $levels; $i++) {
        $final .= ob_get_clean();
    }
    // Apply any filters to the final output
    echo apply_filters('final_output', $final);
}


function siw_parse_dom($output)
{

    $siw_cookie_script_tags = array(
        'platform.twitter.com/widgets.js',
        'apis.google.com/js/plusone.js',
        'apis.google.com/js/platform.js',
        'connect.facebook.net',
        'platform.linkedin.com',
        'assets.pinterest.com',
        'www.youtube.com/iframe_api',
        'www.google-analytics.com/analytics.js',
        'google-analytics.com/ga.js',
        'new google.maps.',
        '_getTracker',
        'disqus.com',
	    'embed.tawk.to',
	    'secure.gravatar.com'
    );
    $siw_cookie_script_tags = apply_filters('siw_cookie_script_tags', $siw_cookie_script_tags);
    $siw_cookie_script_async_tags = array(
        'addthis.com',
        'sharethis.com'
    );
    $siw_cookie_script_async_tags = apply_filters('siw_cookie_script_async_tags', $siw_cookie_script_async_tags);
    $siw_iframe_tags = array(
        'youtube.com',
        'platform.twitter.com',
        'www.facebook.com/plugins/like.php',
        'www.facebook.com/plugins/likebox.php',
        'apis.google.com',
        'www.google.com/maps/embed/',
//        'player.vimeo.com',
        'disqus.com'
    );
    $siw_iframe_tags = apply_filters('siw_add_iframe', $siw_iframe_tags);


    if (strpos($output, '<html') === false):
        return $output;
    elseif (strpos($output, '<html') > 200):
        return $output;
    endif;
    libxml_use_internal_errors(true);
    $doc = new DOMDocument();
    $doc->encoding = 'utf-8';
    $doc->loadHTML(mb_convert_encoding($output, 'HTML-ENTITIES', 'UTF-8'));
    // get all the script tags
    $script_tags = $doc->getElementsByTagName('script');
    $async_array = array();
    $domElemsToRemove = array();
    foreach ($script_tags as $script):
        $src_script = $script->getAttribute('src');
        if ($src_script):
            if (siw_strpos_arr($src_script, $siw_cookie_script_tags) !== false):
                $script->setAttribute("class", "siw-script");
                $script->setAttribute("type", "text/plain");
                continue;
            endif;
            if (siw_strpos_arr($src_script, $siw_cookie_script_async_tags) !== false):
                //return print_r($script->nodeValue);
                $async_array[] = $src_script;
                $domElemsToRemove[] = $script;
                continue;
            endif;
        endif;
        if ($script->nodeValue):
            $key = siw_strpos_arr($script->nodeValue, $siw_cookie_script_tags);
            if ($key !== false):
                if ($siw_cookie_script_tags[$key] == 'www.google-analytics.com/analytics.js' || $siw_cookie_script_tags[$key] == 'google-analytics.com/ga.js')
                    if (strpos($script->nodeValue, 'anonymizeIp') !== false):
                        continue;
                    endif;
                $script->setAttribute('class', 'siw-script');
                $script->setAttribute('type', 'text/plain');
                if ((string)$siw_cookie_script_tags[$key] === 'disqus.com/embed.js' || (string)$siw_cookie_script_tags[$key] === 'disqus.com'):
                    $script->setAttribute('class', 'siw-script');
                    $script->setAttribute('type', 'text/plain');
                endif;
            endif;
        endif;
    endforeach;
    foreach ($domElemsToRemove as $domElement) {
        $domElement->parentNode->removeChild($domElement);
    }
    // get all the iframe tags
    $iframe_tags = $doc->getElementsByTagName('iframe');
    foreach ($iframe_tags as $iframe):
        $src_iframe = $iframe->getAttribute('src');
        if ($src_iframe):
            if (siw_strpos_arr($src_iframe, $siw_iframe_tags) !== false):
                $iframe->removeAttribute('src');
                $iframe->setAttribute("data-ce-src", $src_iframe);
                if ($iframe->hasAttribute('class')):
                    $addclass = $iframe->getAttribute('class');
                else:
                    $addclass = '';
                endif;
                $iframe->setAttribute("class", "siw-iframe " . $addclass);
            endif;
        endif;
    endforeach;
    if (!empty($async_array)):
        $text = json_encode($async_array);
        $text = 'var async_siw_cookie_script = ' . $text . ';';
        $head = $doc->getElementsByTagName('head')->item(0);
        $element = $doc->createElement('script', $text);
        $head->appendChild($element);
    endif;

    // get the HTML string back
    $output = $doc->saveHTML();
    libxml_use_internal_errors(false);
    return $output;
}

function siw_strpos_arr($haystack, $needle)
{
    if (!is_array($needle)) $needle = array($needle);
    foreach ($needle as $key => $what) {
        if (($pos = strpos($haystack, $what)) !== false) return $key;
    }
    return false;
}

