<?php
if (!defined('ABSPATH')) {
    exit;
}

$siw_logo = plugin_dir_url(__DIR__).'assets/images/siw_logo.svg';
$siw_loading = plugin_dir_url(__DIR__).'assets/images/loader.gif';
$add_settings = [
    'toggle_section' => [
        'title' => 'Toggles',
        'callback' => static function () {
        },
        'page' => 'theme-toggles',
        'settings' => [
            [
                'id' => 'toggle-backend-style',
                'title' => 'Back-end styling',
                'callback' => 'siw_toggles_callback',
                'type' => 'checkbox',
            ],
            [
                'id' => 'toggle-avg-msg',
                'title' => 'AVG/Google Tag Manager scripts',
                'callback' => 'siw_toggles_callback',
                'type' => 'checkbox',
            ],
            [
                'id' => 'toggle-avg-ip-msg',
                'title' => 'Cookiebot melding blokkade (IP/Ingelogd)',
                'callback' => 'siw_toggles_callback',
                'default' => 'on',
                'type' => 'checkbox',
            ],
            [
                'id' => 'toggle-coming-soon',
                'title' => '\'Coming soon mode\' + \'log-in bericht\'',
                'callback' => 'siw_toggles_callback',
                'type' => 'checkbox',
                'after_input' => '<p><small>' . __('Zorgt ervoor dat alle front-end pagina&apos;s worden omgeleid naar de loginpagina.', 'siw-to') .
                    '</p></small><p><small>' . __('Op de login pagina zie je een speciaal bericht om te laten weten dat de website in ontwikkeling is.', 'siw-to') .
                    '</small></p><p><small>' . __('Pas na het inloggen kan alles normaal bekeken worden.', 'siw-to') . '</small></p>'
            ],
            [
                'id' => 'toggle-disable-update-btn',
                'title' => 'WP update notificaties',
                'callback' => 'siw_toggles_callback',
                'type' => 'checkbox',
            ],
            [
                'id' => 'siw_enable_messages',
                'title' => 'Vervelende plug-in meldingen',
                'callback' => 'siw_toggles_callback',
                'type' => 'checkbox',
                'after_input'=> '<br/><br/>'.
                    '<p><small>'.__('Dit bevat meldingen zoals "Koop m&apos;n plugin", "Doneer please", "Installeer de volgende plug-ins, nu!"', 'siw-to').'</small></p>'.
                    '<p><small>'.__('Indien je zelf meldingen tegen komt waarbij je aan jezelf twijfelt of ze nuttig zijn, laat het aan Bimma weten.', 'siw-to').'</small></p>'
            ],
            [
                'id' => 'toggle_welcomemsg',
                'title' => 'Welkomstbericht',
                'callback' => 'siw_toggles_callback',
                'type' => 'checkbox',
            ],
            [
                'id' => 'siwto_wpjson_secure',
                'title' => 'WP JSON authenticatie',
                'callback' => 'siw_toggles_callback',
                'default' => 'on',
                'type' => 'checkbox',
                'after_input' => '<p><small>Normaliter kan je met WP-JSON gegevens opvragen zonder account.<br />Hiermee is het volledig afgeschermd met een inlog.<br />Als dit uit wordt gezet, moet er alsnog ingelogd worden indien er gevoelig informatie opgevraagd wordt.</small></p>'
            ],
            [
                'id' => 'siwto_no_index_notice',
                'title' => 'No-index notificatie melding',
                'callback' => 'siw_toggles_callback',
                'default' => 'on',
                'type' => 'checkbox',
                'after_input' => '<p><small>Dit controleerd of "no-index" bij een website aan of uit staat. Hiermee schakel je de melding volledig uit.</small></p>'
            ],
        ]
    ],
    'style_section' => [
        'title' => 'Style opties',
        'callback' => static function () {
        },
        'page' => 'theme-style',
        'settings' => [
            [
                'id' => 'siw_color_one',
                'title' => 'Huisstijl kleur #1',
                'callback' => 'siw_toggles_callback',
                'type' => 'text',
                'placeholder' => '#fffff',
                'input_class' => 'siw_color_picker',
                'after_input' => '<p>' . __('Standaard: #0a3549', 'siw-to') . '</p>',
            ],
            [
                'id' => 'siw_login_checkbox',
                'title' => 'De login pagina <em>niet</em> \'extra\' vormgeven',
                'callback' => 'siw_toggles_callback',
                'type' => 'checkbox',
            ],
            [
                'id' => 'toggle-no-logo',
                'title' => 'Login-scherm logo',
                'default' => 'on',
                'callback' => 'siw_toggles_callback',
                'conditional_if_off_target' => 'siw_custom_logo',
                'type' => 'checkbox',
                'after_input' => '<p><small>'.__('Het logo kan je terugvinden op de login-pagina.', 'siw-to').'</small></p>'
            ],
            [
                'id' => 'siw_custom_logo',
                'title' => 'Logo klant',
                'callback' => 'siw_toggles_callback',
                'type' => 'text',
                'input_class' => 'image_url',
                'before_input' => '<img class="input_image header_logo"' .
                    'src="' . (get_option('siw_custom_logo') ?: $siw_logo) .'" '.
                    'height="100" width="100" />',
                'after_input' => '<input id="upload_logo_button" type="button" class="button upload_img_btn" value="'.__('Upload Logo', 'siw-to').'"/><br><br><p><small>Zorg dat je hier een transparante PNG upload.</small></p>',
            ],
            [
                'id' => 'toggle-no-background',
                'title' => 'Login-scherm achtergrond',
                'default' => 'on',
                'callback' => 'siw_toggles_callback',
                'conditional_if_off_target' => 'siw_custom_background',
                'type' => 'checkbox',
                'after_input' => '<p><small>'.__('De achtergrond kan je terugvinden op de login-pagina.', 'siw-to').'</small></p>'
            ],
            [
                'id' => 'siw_custom_background',
                'title' => 'Background klant',
                'callback' => 'siw_toggles_callback',
                'type' => 'text',
                'input_class' => 'image_url',
                'before_input' => '<img class="input_image header_logo"' .
                    'src="' . (get_option('siw_custom_background') ?: $siw_logo) .'" '.
                    'height="100" width="100" />',
                'after_input' => '<input id="siw_custom_background" type="button" class="button upload_img_btn" value="'.__('Upload BG', 'siw-to').'"/><br><br>'
            ],
            [
                'id' => 'toggle-no-background',
            ],
            [
                'id' => 'siw_enable_events',
                'title' => 'Styling voor speciale gelegenheden',
                'callback' => 'siw_toggles_callback',
                'default' => 'on',
                'type' => 'checkbox',
                'after_input' => '<p><small>'.__('Zet bijvoorbeeld de kerst styling uit.', 'siw-to').'</small></p>'
            ],
        ]
    ],
    'rebrand_section' => [
        'title' => 'Rebranding opties',
        'callback' => static function () {
            echo 'Hernoem diverse onderdelen om huisstijl gelijk te trekken aan een klant.';
        },
        'page' => 'theme-rebrand',
        'settings' => [
            [
                'id' => 'rename_siw_to',
                'title' => 'Hernoem SIW TO',
                'callback' => 'siw_toggles_callback',
                'type' => 'text',
                'placeholder' => 'SIW TO',
                'after_input' => '<p><small>'.__('Hiermee kan je "SIW TO" hernoemen naar wat je zelf wilt.') . '</small></p>'
            ],
            [
                'id' => 'siw_company_name',
                'title' => 'Bedrijfsnaam',
                'callback' => 'siw_toggles_callback',
                'type' => 'text',
                'placeholder' => 'Specialist in Websites',
                'after_input' => '<p><small>'.__('Hiermee kan je het voorkomende bedrijfsnaam hernoemen naar wat je zelf wilt.') . '</small></p>'
            ],
            [
                'id' => 'siw_rebrand_loading_gif',
                'title' => 'Laad gif',
                'callback' => 'siw_toggles_callback',
                'type' => 'text',
                'input_class' => 'image_url',
                'before_input' => '<img class="input_image header_logo"' .
                    'src="' . (get_option('siw_rebrand_loading_gif') ?: $siw_loading) .'" '.
                    'height="100" width="100" />',
                'after_input' => '<input id="upload_logo_button" type="button" class="button upload_img_btn" value="'.__('Upload GIF', 'siw-to').'"/><br><br><p><small>Komt voornamelijk terug bij het inloggen & opslaan van velden.</small></p>',
            ],
//            [
//                'id' => 'test_siw_thing',
//                'title' => 'TEST',
//                'callback' => 'siw_toggles_callback',
//                'type' => 'textarea',
//                'input_class' => 'siw_syntax-editor',
//                'placeholder' => 'textarea',
//                'before_input' => 'before_input',
//                'after_input' => 'after_input'
//            ],
        ]
    ],
    'section_analytics' => [
        'title' => 'Google Tag Manager',
        'callback' => static function () {
            echo '<h4>Voer de opties gegeven in de tag manager, hieronder in.
                    <i class="fa fa-question-circle" aria-hidden="true" title="Klik voor meer informatie"></i>
                    <div class="image-container">
                        <span>De twee code secties, moeten van dit venster vandaan komen.</span>
                        <img src="'.plugin_dir_url(dirname(__DIR__) . '').'assets/images/tag-manager-screenshot.png"
                             alt="Screenshot"/>
                    </div>
                </h4>';
        },
        'page' => 'theme-options-analytics',
        'settings' => [
            [
                'id' => 'toggle-avg-ip-msg',
                'title' => 'IP / Ingelogd controle aan/uit',
                'callback' => 'siw_toggles_callback',
                'type' => 'checkbox'
            ],
            [
                'id' => 'container_id',
                'title' => 'Container ID <br /><small>Verplicht!</small>',
                'callback' => 'siw_toggles_callback',
                'type' => 'text',
                'required' => true,
            ],
            [
                'id' => 'code_one',
                'title' => 'Code voor &lt;body&gt;<br /><small>Verplicht!</small>',
                'callback' => 'siw_toggles_callback',
                'required' => false,
                'type' => 'textarea',
                'input_class' => 'siw_syntax-editor',
            ],
            [
                'id' => 'code_two',
                'title' => 'Code na &lt;body&gt;<br /><small>Verplicht!</small>',
                'callback' => 'siw_toggles_callback',
                'required' => false,
                'type' => 'textarea',
                'input_class' => 'siw_syntax-editor',
            ],
        ]
    ],
    'avg_style_section' => [
        'title' => 'Style opties',
        'callback' => static function () {
            echo '<h4>Voer hier de huisstijl in van een klant</h4>';
        },
        'page' => 'theme-options-analytics-style',
        'settings' => [
            [
                'id' => 'siw_avg_logo',
                'title' => 'Logo van klant',
                'callback' => 'siw_toggles_callback',
                'type' => 'text',
                'input_class' => 'image_url',
                'before_input' => '<img class="input_image header_logo"' .
                    'src="' . (get_option('siw_avg_logo') ?: $siw_logo) .'" '.
                    'height="100" width="100" />',
                'after_input' => '<input id="upload_logo_button" type="button" class="button upload_img_btn" value="'. __('Upload Logo', 'siw-to') .'"/><p><small>Het logo kan je terugvinden bij de cookie melding.</small></p>
    <p><small>Houd rekening dat het logo standaard een hoogte van <b>100%</b> en een breedte van <b>45px</b> heeft.</small></p>
    <br/>',
            ],
            [
                'id' => 'siw_avg_logo_style',
                'title' => 'Logo extra style',
                'callback' => 'siw_toggles_callback',
                'type' => 'textarea',
                'placeholder' => '.cookie-bar-cb .cookie-content-txt-cb:before{
            // height: 100%;
            }',
                'input_class' => 'siw_syntax-editor siw_syntax-editor_css',
            ],
            [
                'id' => 'siw_avg_color',
                'title' => 'Huisstijl kleur',
                'callback' => 'siw_toggles_callback',
                'type' => 'text',
                'placeholder' => '#0D5EA0',
                'input_class' => 'siw_color_picker',
                'after_input' => '<p>' . __('Standaard: #0D5EA0', 'siw-to') . '</p>',
            ],
            [
                'id' => 'siw_avg_font_one',
                'title' => 'Header font van thema pakken',
                'callback' => 'siw_toggles_callback',
                'type' => 'checkbox',
            ],
            [
                'id' => 'siw_avg_font_two',
                'title' => 'Body font van thema pakken',
                'callback' => 'siw_toggles_callback',
                'type' => 'checkbox',
            ],
            [
                'id' => 'siw_avg_pass_checkbox',
                'title' => 'Reset password',
                'callback' => 'siw_toggles_callback',
                'type' => 'checkbox',
            ],
        ]
    ],
    'avg_whiteblack_section' => [
        'title' => 'Script black -/whitelist',
        'callback' => static function () {
            echo '<h4>Is nog in ontwikkeling & niet in gebruik</h4>';
        },
        'page' => 'theme-options-whiteblack',
        'settings' => [
            [
                'id' => 'siwto_blacklist_textarea',
                'title' => 'Blacklist',
                'callback' => 'siw_toggles_callback',
                'type' => 'textarea',
            ],
        ]
    ],
    'avg_style_section-password' => [
        'title' => 'Style opties',
        'callback' => static function () {
            echo '<h4>Voer hier het wachtwoord in om de style aan te passen</h4>';
        },
        'page' => 'theme-options-analytics-style-password',
        'settings' => [
            [
                'id' => 'siw_avg_password',
                'title' => 'Wachtwoord',
                'callback' => 'siw_toggles_callback',
                'type' => 'password',
            ],
        ]
    ],
    'page_speed_section' => [
        'title' => 'Page Speed',
        'callback' => static function () {
            echo '<div>';
            echo '<p>Hier kan je de de snelheid van de website aflezen en de details bekijken.<br>';
            echo 'Als je op de <span class="dashicons dashicons-arrow-up"></span> icoontjes drukt kan je de bestanden zien die in aanmerking komen met de score.</p>';
            echo '<a class="button siw_pagespeed_btn" data-value="desktop" href="javascript:void(0)">Desktop score aanvragen</a>';
            echo '<a class="button siw_pagespeed_btn" data-value="mobile" href="javascript:void(0)">Mobiel score aanvragen</a>';
            echo '</div>';
            echo '<div id="page_speed_result" class="page_speed_result"></div>';
        },
        'page' => 'theme-page-speed',
        'settings' => [
            ['id' => 'siw_pagespeed_score_desktop'],
            ['id' => 'siw_pagespeed_score_mobile'],
            ['id' => 'siw_pagespeed_score_desktop_date'],
            ['id' => 'siw_pagespeed_score_mobile_date'],
        ]
    ]
];

foreach ($add_settings as $setting_id => $add_setting) {
    add_settings_section(
        $setting_id,
        $add_setting['title'],
        $add_setting['callback'],
        $add_setting['page']
    );
    if (isset($add_setting['settings'])) {
        foreach ($add_setting['settings'] as $setting) {
            if ($title = $setting['title'] ?? false) {
                add_settings_field(
                    $setting['id'] ?? '',
                    $title,
                    $setting['callback'] ?? '',
                    $add_setting['page'],
                    $setting_id,
                    [
                        'type' => $setting['type'] ?? 'text',
                        'id' => $setting['id'] ?? '',
                        'placeholder' => $setting['placeholder'] ?? '',
                        'before_input' => $setting['before_input'] ?? '',
                        'after_input' => $setting['after_input'] ?? '',
                        'input_class' => $setting['input_class'] ?? '',
                        'required' => $setting['required'] ?? '',
                        'class' => isset($setting['id']) ? $setting['id'] . '_container' : '',
                        'conditional_if_on_target' => $setting['conditional_if_on_target'] ?? '',
                        'conditional_if_off_target' => $setting['conditional_if_off_target'] ?? '',
                        'default' => $setting['default'] ?? ''
                    ]
                );
            }
            register_setting($setting_id, $setting['id']);
        }
    }
}

function siw_toggles_callback($args)
{
//    var_dump($args);
//    var_dump(get_option($args['id']));
    echo $args['before_input'] ?: '';
    if (isset($args['default']) && $args['default'] && get_option($args['id']) === false) {
        update_option($args['id'], $args['default']);
    }
    if ($args['type'] === 'text' || $args['type'] === 'password' || $args['type'] === 'checkbox') {
        echo $args['type'] === 'checkbox' ? '<label class="siw_checkbox_container">' : '';
        echo '<input ';
        echo 'type="' . $args['type'] . '" ';
        echo 'id="' . $args['id'] . '" ';
        echo 'name="' . $args['id'] . '" ';
        echo $args['required'] ? ' required ' : '';
        echo 'class="' . $args['input_class'] . '" ';
        echo 'placeholder="' . $args['placeholder'] . '" ';
        echo $args['conditional_if_on_target'] ? 'data-if-on-remove="'.$args['conditional_if_on_target'].'" ' : '';
        echo $args['conditional_if_off_target'] ? 'data-if-off-remove="'.$args['conditional_if_off_target'].'" ' : '';
        echo 'on' === (string)get_option($args['id']) ? 'checked="checked"' : '';
        $value = $args['value'] ?? false;
        $value = get_option($args['id']) ?? $value;
        echo $args['type'] !== 'checkbox' ? 'value="' . ( (string)$value ) . '""' : '';
        echo '/>';
        echo $args['type'] === 'checkbox' ? '<div class="siw_checkbox_container-item"><span>aan</span><span>uit</span></div></label>' : '';
    } elseif ($args['type'] === 'textarea') {
        echo '<textarea style="height: 200px"';
        echo 'id="' . $args['id'] . '" ';
        echo 'name="' . $args['id'] . '" ';
        echo $args['required'] ? ' required ' : '';
        echo 'class="' . $args['input_class'] . '" ';
        echo 'placeholder="' . $args['placeholder'] . '">';
        echo get_option($args['id']) ?: '';
        echo '</textarea>';
    }
    echo $args['after_input'] ?: '';
}
