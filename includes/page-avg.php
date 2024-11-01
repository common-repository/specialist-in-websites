<?php
/**
 * Created by PhpStorm.
 * User: Marthijn
 * Date: 22-Jun-18
 * Time: 3:29 PM
 */
if (! get_option('toggle-avg-msg')) {
    _e("AVG staat volledig uit. Zet hem aan op <a href='?page=siw-to'>de SIW TO pagina</a>.");
}
$active_tab = isset($_GET['tab']) && $_GET['tab'] ? $_GET['tab'] : 'avg-options';
//var_dump($active_tab);
_e('<h1>GTag Manager (AVG)</h1>');
settings_errors();
if (isset($_GET['settings-updated']) && $_GET['settings-updated']) {
        $plugins = [
            [
                'name' => 'WP Rocket',
                'path' => 'wp-rocket'
            ], [
                'name' => 'W3 Total Cache',
                'path' => 'w3-total-cache/w3-total-cache.php'
            ], [
                'name' => 'Redis',
                'path' => 'redis-cache/redis-cache.php'
            ]
        ];
        $pluginame = false;
        foreach ($plugins as $plugin) {
            if (is_plugin_active($plugin['path'])) {
                $pluginame .= ' ( ' . $plugin['name'] . ' ) ';
            }
        }
        echo $pluginame ? $_SESSION['removeCache'] = "
        <div class='removeCache updated settings-error error notice is-dismissible'>
            <p>Verwijder de {$pluginame} cache.</p>
            <a href='?removeCache=true'>
                <button class='notice-dismiss customDismiss'>
                    <span class='screen-reader-text'>Dit bericht verbergen.</span>
                </button>
            </a>
        </div>" : '';
    }
    ?>
    <div class="wrap analytic-options">
        <p><?php _e('Wanneer dit is ingeschakeld, blokkeert de plug-in alle tracking scripts behalve Google Tag Manager. 
            (mits deze hieronder correct is ingesteld).
            Zodra er op "Accepteer Cookies" wordt gedrukt, komen de geblokkeerde scripts weer tevoorschijn.<br/><br/>
            Dit werkt allemaal in samenwerking met <a href="https://manage.cookiebot.com/en/manage">CookieBot</a>. We
            gebruiken CookieBot voor de teksten en waarschuwingen die in de melding voorkomen. Dit wordt namelijk door
            CookieBot maandelijks geanalyseerd en ge-update.<br/>
            <b>Als CookieBot niet is ingeschakeld, komt er dus ook geen melding!</b>'); ?>
        </p>
        <p>
            <small><em><?php _e('Mis je hier iets, zoals een waarschuwing voor andere analytic plug-ins, laat het me weten!', 'siw-to'); ?></em>
            </small>
        </p>
        <!-- run the settings_errors() function here. -->

        <div class="nav-tab-wrapper">
            <a href="<?php echo admin_url(); ?>admin.php?page=siw_analytics&tab=avg-options"
               class="nav-tab <?php if ($active_tab == 'avg-options') echo 'nav-tab-active'; ?>">GTag Manager</a>
            <a href="<?php echo admin_url(); ?>admin.php?page=siw_analytics&tab=avg-style-options"
               class="nav-tab <?php if ($active_tab == 'avg-style-options') echo 'nav-tab-active'; ?>">Style</a>
            <a href="<?php echo admin_url(); ?>admin.php?page=siw_analytics&tab=avg-whiteblack"
               class="nav-tab <?php if ($active_tab == 'avg-whiteblack') echo 'nav-tab-active'; ?>">Script black
                -/whitelist</a>
        </div>

        <form method="post" action="options.php" class="siw_wp_ajax_save">
            <?php if ($active_tab === 'avg-options') {
                settings_fields('section_analytics');
                do_settings_sections('theme-options-analytics');
                include_once(ABSPATH . 'wp-admin/includes/plugin.php');
            } else if ($active_tab === 'avg-style-options') {
                if ('on' === get_option('siw_avg_pass_checkbox')) update_option('siw_avg_password', '');
                update_option('siw_avg_pass_checkbox', 'off');
                if (get_option('siw_avg_password') && get_option('siw_avg_password') === 'heyhallomagikerin') {
                    settings_fields('avg_style_section');
                    do_settings_sections('theme-options-analytics-style');
                } else {
                    settings_fields('avg_style_section-password');
                    do_settings_sections('theme-options-analytics-style-password');
                }
            } elseif ($active_tab === 'avg-whiteblack') {
                settings_fields('avg_whiteblack_section');
                do_settings_sections('theme-options-whiteblack');
            }
            submit_button();
            submit_button(__('Op de oude manier opslaan', 'siw-to'), 'submit-backup');
            ?>
        </form>
    </div>