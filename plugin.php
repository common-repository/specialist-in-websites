<?php
/*
Plugin Name: SIW TO
Plugin URI: https://www.specialistinwebsites.nl/
Description: Dit is een administratie tool voor <a href="https://www.specialistinwebsites.nl">Specialist in Websites</a>.
Author: Specialist in Websites
Version: 2.0.4.1
Author URI: https://www.specialistinwebsites.nl/
*/

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

defined('ABSPATH') or die();
require __DIR__ . '/vendor/autoload.php';

class SIW_TO
{

    public $is_live = false;

    /*
     * The construct function initiates all the functions.
     */
    public function __construct()
    {
        $this->actions();
        $this->is_live = ($url = $_SERVER['HTTP_HOST'] ?? false)
            && (stripos($url ?? '', 'siw-ontwikkeling') !== false
                || pathinfo($url, PATHINFO_EXTENSION) !== 'test');

        if (!$this->is_live) {
            // Website is still in production
        } else {
            // Website is live
            add_action('admin_init', [$this, 'noIndexCheck']);
        }

        add_filter('admin_email_check_interval', '__return_zero'); // disable admin email check;

        // $this->welcomeMessage();
        $this->siwto_klant_user_role();

        if (get_option('toggle-avg-msg')) {
            $this->AVGScript();
        }

        add_filter('plugin_action_links_' . plugin_basename(__FILE__), [$this, 'addSettingsLinks']);
    }


    public function actions()
    {
        add_action('admin_menu', [$this, 'createOptionPage']);
        add_action('init', [$this, 'browserIsIE']);
        add_action('admin_init', [$this, 'registerFields']);
        add_action('admin_enqueue_scripts', [$this, 'adminScripts']);
        add_action('login_message', [$this, 'siwto_load_custom_wp_login_style']);
        add_action('wp_dashboard_setup', [$this, 'siwto_custom_dashboard_widgets'], 999);
        add_action('wp_dashboard_setup', [$this, 'siwto_custom_dashboard_widgets_admin'], 999);
        add_action('wp_dashboard_setup', [$this, 'siwto_disable_default_dashboard_widgets'], 999);

        add_action('admin_bar_menu', [$this, 'wp_admin_bar_my_custom_account_menu'], 11);
        add_action('parse_request', [$this, 'siwto_comingsoon_redirect']);
        add_action('admin_bar_menu', [$this, 'siwto_customize_adminbar'], 999);
        add_action('login_message', [$this, 'siwto_comingsoon_infobox']);
        add_action('rest_authentication_errors', [$this, 'checkRestAPI']);
        add_action('admin_init', [$this, 'plugin_checks']);
    }


    /**
     * Welcome message
     */
    public function welcomeMessage(): void
    {
        if (!get_option('toggle_welcomemsg') && $this->is_siw_developer()) {
            add_action('admin_notices', static function () {
                include('includes/welcome_msg.php');
            });
        }
        add_action('wp_ajax_nopriv_siw_remove_welcomemsg', array($this, 'siwto_remove_welcomemsg'));
        add_action('wp_ajax_siw_remove_welcomemsg', [$this, 'siwto_remove_welcomemsg']);
    }


    /**
     * Register all options!
     */
    public function registerFields(): void
    {
        include_once('includes/register_fields.php');
    }

    /*
     * Check if no-index is on on live domains
     */
    public function noIndexCheck()
    {
        if (wp_doing_ajax() || !is_admin() || get_option('siwto_no_index_notice') !== 'on') {
            return;
        }
        if (isset($_GET['removeCache'])) {
            unset($_SESSION['removeCache']);
            header('Refresh:0; url=../wp-admin/admin.php?page=siw_analytics');
        }
        if (isset($_SESSION['removeCache'])) {
            echo $_SESSION['removeCache'];
        }

        if (!get_option('blog_public') && $this->is_live) {
            add_action('admin_notices', function () {
                $message = sprintf(
                    __('No-index staat aan! Klik <a href="%s">hier</a> om het uit te zetten of <a href="%s">hier</a> om deze melding uit te zetten.', 'siw-to'),
                    get_site_url() . '/wp-admin/options-reading.php',
                    admin_url() . 'admin.php?page=siw-to'
                );
                echo "<div class='notice notice-warning'><p>{$message}</p></div>";
            });
        }
    }

    /**
     * Check if rest-api is turned on
     *
     * @param $result
     * @return mixed|WP_Error
     */
    public function checkRestAPI($result)
    {
        if (defined('DOING_CRON') || (defined('DOING_AJAX') && DOING_AJAX)) {
            return $result;
        }
        if (!get_option('siwto_wpjson_secure')) {
            return $result;
        }
        if (!empty($result)) {
            return $result;
        }
        if (!is_user_logged_in()) {
            return new WP_Error('rest_not_logged_in', 'You are not currently logged in.', ['status' => 401]);
        }
        return $result;
    }

    /**
     * Check if running internet explorer
     *
     * @param $return
     * @return bool|void
     */
    public function browserIsIE($return)
    {
        // Check if browser is IE
        if (stripos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== false &&
            stripos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false
        ) {
            // If it still happens to be firefox or chrome
            if (stripos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== false
                || stripos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== false) {
            } else {
                return false;
            }

            if ($return) {
                return true;
            }

            if (!isset($_COOKIE['ie_pop_up'])) {
                if (get_option('toggle-no-logo')) {
                    echo '<style>body.login #login h1 {display: none;}</style>';
                }
                if (get_option('toggle-no-background')) {
                    echo '<style>body.login, body.login #login:after, body.login {background-image: none !important;}</style>';
                } ?>
                <div id="ie-error" class="ie-error-container">
                    <div class="ie-error-container-block">
                        <h1>Internet Explorer gedetecteerd!</h1>
                        <p>Deze website (zowel als vele anderen) functioneren en presteren in Internet Explorer niet
                            naar behoren.</p>
                        <p>Er wordt <strong>sterk aanbeloven</strong> om gebruik te maken van de nieuwste versie van <a
                                    target="_blank" href="https://www.google.com/intl/nl/chrome/">Google Chrome</a>, <a
                                    target="_blank" href="https://www.mozilla.org/nl/firefox/">Firefox</a> of <a
                                    target="_blank" href="https://www.microsoft.com/nl-nl/windows/microsoft-edge">Microsoft
                                Edge</a</p>
                        <br>
                        <a href='javascript:void(0);' class='ie-error-container-block-button' id="siw_pop_up_close">Ik
                            kies er bewust voor om verder te gaan</a>
                    </div>
                </div>
            <?php } elseif (is_admin()) { ?>
                <div class="notice notice-error">
                    <p>Je gebruikt internet explorer, dit word niet ondersteund door ons!</p>
                </div>
            <?php }
        } elseif ($return) {
            return false;
        }
    }

    /*
     * For the lighter custom colors.
     */
    public function siwto_luminance($hex, $adjustment)
    {
        $adjustment = max(-255, min(255, $adjustment));  // Adjustment value is between 255 & -255
        $hex = str_replace('#', '', $hex);   //Remove # from string

        //if hex value is 3 numbers set to 6
        if (strlen($hex) === 3) {
            $hex = str_repeat($hex[0], 2) . str_repeat($hex[1], 2) . str_repeat($hex[2], 2);
        }

        $colors = str_split($hex, 2); //split values into three channels
        $adjusted_value = '#';

        foreach ($colors as $color) {
            $color = hexdec($color); //convert to decimal
            $color = max(0, min(255, $color + $adjustment)); //perform adjustment
            $adjusted_value .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); //make two character hex code
        }

        return $adjusted_value;
    }

    /*
     * Checks if plugins are installed
     */
    public function plugin_checks()
    {
        if (function_exists('is_plugin_active') && is_plugin_active('google-analytics-for-wordpress/googleanalytics.php')) {
            add_action('admin_notices', static function () { ?>
                <div class="notice notice-error">
                    <p><?php _e('The plugin "Monsterinsight" is active. Please deactivate or uninstall it.', 'sample-text-domain'); ?></p>
                </div>
                <?php
            });
        }
        if (function_exists('is_plugin_active') && is_plugin_active('protect-wp-admin/protect-wp-admin.php') && get_option("wp-admin-url-active") === "on") {
            add_action('admin_notices', static function () { ?>
                <div class="notice notice-error">
                    <p><?php _e('The plugin "protect-wp-admin" is active. It overrides the current function of SIW TO.', 'sample-text-domain'); ?></p>
                </div>
                <?php
            });
        }
    }

    /*
     * Remove Welcome message
     */
    public function siwto_remove_welcomemsg()
    {
        update_option('toggle_welcomemsg', 'on');
    }

    /*
     * SIW Klant User Role
     */
    public function siwto_klant_user_role()
    {
        global $wp_roles;

//        remove_role('siw_klant'); needed for changes
        $siw_klant = add_role(
            'siw_klant',
            'SIW Klant',
            [
                'activate_plugins' => false,
                'create_pages' => true,
                'create_posts' => true,
                'create_users' => true,
                'delete_others_pages' => true,
                'delete_others_posts' => true,
                'delete_pages' => true,
                'delete_plugins' => false,
                'delete_posts' => true,
                'delete_private_pages' => true,
                'delete_private_posts' => true,
                'delete_published_pages' => true,
                'delete_published_posts' => true,
                'delete_site' => false,
                'delete_themes' => false,
                'delete_users' => true,
                'edit_dashboard' => true,
                'edit_others_pages' => true,
                'edit_others_posts' => true,
                'edit_pages' => true,
                'edit_plugins' => false,
                'edit_posts' => true,
                'edit_private_pages' => true,
                'edit_private_posts' => true,
                'edit_published_pages' => true,
                'edit_published_posts' => true,
                'edit_theme_options' => false,
                'edit_themes' => false,
                'edit_users' => false,
                'export' => false,
                'import' => false,
                'install_plugins' => false,
                'install_themes' => false,
                'list_users' => true,
                'loco_admin' => true,
                'manage_categories' => true,
                'manage_links' => true,
                'manage_options' => true,
                'moderate_comments' => true,
                'promote_users' => true,
                'publish_pages' => true,
                'publish_post' => true,
                'publish_posts' => true,
                'read' => true,
                'read_private_pages' => true,
                'read_private_posts' => true,
                'remove_users' => true,
                'switch_themes' => false,
                'unfiltered_html' => true,
                'unfiltered_upload' => true,
                'update_core' => false,
                'update_plugins' => false,
                'update_themes' => false,
                'upload_files' => true,
                'user_admin_menu_access' => true,
                'user_create_capabilities' => true,
                'user_create_roles' => false,
                'user_delete_capabilities' => false,
                'user_delete_roles' => false,
                'user_edit_posts_access' => true,
                'user_edit_roles' => true,
                'user_export_roles' => true,
                'user_import_roles' => true,
                'user_manage_options' => true,
                'user_meta_boxes_access' => true,
                'user_other_roles_access' => false,
                'user_plugins_activation_access' => false,
                'user_reset_roles' => true,
                'user_view_posts_access' => true,
                'user_widgets_access' => true,
                'user_widgets_show_access' => true,
                'wpseo_bulk_edit' => true,
                'wpseo_edit_advanced_metadata' => true,
                'wpseo_manage_options' => true

            ]
        );

        add_action('init', static function () {
            global $wp_roles;
            if (!isset($wp_roles)) {
                $wp_roles = new WP_Roles();
            }
            $wp_roles->roles['administrator']['name'] = 'SIW Admin';
            $wp_roles->role_names['administrator'] = 'SIW Admin';
            // Change WP default Admin to SIW Admin
        });

        remove_role('klant_admin');
        $admin_cap = $wp_roles->get_role('administrator');
        $klant_admin = add_role('klant_admin', 'Klant Admin', $admin_cap->capabilities);

        // Process blocks for users
        add_action('init', static function () {
            $user = wp_get_current_user();
            if ($user && in_array('siw_klant', (array)$user->roles, true)) {
                add_action('admin_menu', static function () {
                    remove_menu_page('index.php');                  //Dashboard
                    remove_menu_page('jetpack');
                    remove_menu_page('edit.php?post_type=acf-field-group');
                    remove_menu_page('cptui_main_menu');
                    remove_menu_page('themes.php');             //Appearance
                    remove_menu_page('plugins.php');            //Plugins
                    remove_menu_page('tools.php');              //Tools
                });
            }
        });
    }

    /*
     * Ben jij een SiW Developer?
     * $param =
     */
    public function is_siw_developer($id = null)
    {
        $user = $id === null ? get_userdata(get_current_user_id()) : get_userdata($id);
        return $user
            && in_array('administrator', get_userdata(get_current_user_id())->roles, true)
            && (!in_array('klant_admin', get_userdata(get_current_user_id())->roles, true));
    }

    /*
     * Is client op SIW IP of ingelogd?
     */
    public function is_siw_ip_or_logged_in($force = false)
    {
        $check = get_option('toggle-avg-ip-msg') === 'on';

        if ($this->siwto_visitor_ip() === '46.44.132.128' || is_user_logged_in()) {
            return true;
        }

        if ($check && $this->siwto_visitor_ip() === '46.44.132.128') {
            return false;
        }

        if ($force) {
            return $check;
        }

        return true;
    }

    /**
     * Is het al kerst?
     *
     * @return false|string
     */
    private function isEvent()
    {
        if (!get_option('siw_enable_events')) {
            return false;
        }

        $year = (int)date('Y');
        $month = (int)date('n');
        $day = (int)date('j');
        $daymonth = date('d m');

        if (($month === 12 && $day >= 5) || ($month === 1 && $day <= 6)) {
            return 'christmas';
        }

        if ($daymonth === date('d m', easter_date($year)) || $daymonth === date('d m', strtotime(easter_date($year) . '+ 1 day'))) {
            return 'easter';
        }

        if ($month === 4 && ($day === 27 || (date('D', strtotime('+ 1 day')) === 'Sun' && (int)date('j', strtotime('+ 1 day')) === 27))) {
            return 'kingsday';
        }

        return false;
    }

    /*
     * Settings link in /plugins
     */
    public function addSettingsLinks($links)
    {
        $settings_link = '<a href="' . admin_url() . 'admin.php?page=siw-to">' . __('Settings', 'siw-to') . '</a>';
        $links[] = $settings_link;
        return $links;
    }

    /**
     * Any additional menus
     */
    public function createOptionPage()
    {
        if ($this->is_siw_developer()) {
            $page_title = 'SIW Technisch Onderhoudspakket';
            $menu_title = get_option('rename_siw_to') ?: 'SIW TO';
            $capability = 'manage_options';
            $menu_slug = 'siw-to';
            $icon_url = plugin_dir_url(__FILE__) . '/assets/images/siw-dashicon.png';
            $position = 99999999999;

            add_menu_page($page_title, $menu_title, $capability, $menu_slug, static function () {
                include_once('includes/page-toggl.php');
            }, $icon_url, $position);

            add_submenu_page($menu_slug, 'GTag Manager (AVG)', 'GTag Manager (AVG)', $capability, 'siw_analytics', static function () {
                include_once('includes/page-avg.php');
            });
        }
    }

    /**
     * Load custom wp-admin style.
     */
    public function adminScripts(): void
    {
        if (!wp_script_is('jquery')) {
            wp_enqueue_script('jquery');
        }
        if (!wp_script_is('jquery-form')) {
            wp_enqueue_script('jquery-form');
        }

        $tab = ($tab = $_GET['tab'] ?? false) ? htmlspecialchars($tab) : false;
        $page = $_GET['page'] ?? false;

        if ('toplevel_page_siw-to' === get_current_screen()->id
            || wordwrap(strtolower((get_option('rename_siw_to')) ?: 'siw-to'), 1, '-', 0) . '_page_siw_analytics' === get_current_screen()->id) {
            wp_enqueue_media();

            wp_enqueue_script('thickbox');
            wp_enqueue_style('thickbox');

            wp_enqueue_script('media-upload');
            wp_enqueue_script('wptuts-upload');

            wp_enqueue_script('wp-color-picker');
            wp_enqueue_style('wp-color-picker');

            $cm_settings['css'] = wp_enqueue_code_editor(array('type' => 'text/css'));
            $cm_settings['html'] = wp_enqueue_code_editor(array('type' => 'htmlmixed'));
            wp_localize_script('jquery', 'cm_settings', $cm_settings);

            wp_enqueue_script('wp-theme-plugin-editor');
            wp_enqueue_style('wp-codemirror');

            if ($tab === 'page-speed-options') {
                wp_enqueue_script('page-speed-chart-script', 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js');
                wp_enqueue_style('page-speed-chart-style', 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.css');
            }
        }

        if ($page === 'siw_analytics') {
            wp_enqueue_script('code-mirror-js', 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.48.2/codemirror.min.js');
            wp_enqueue_style('code-mirror-css', 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.48.2/codemirror.min.css');
            wp_enqueue_script('code-mirror-html', 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.48.2/addon/mode/loadmode.min.js');
            wp_enqueue_script('code-mirror-html', 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.48.2/mode/htmlmixed/htmlmixed.js');
        }
        if ($page === 'siw-to' && $tab === 'page-speed-options') {
            wp_enqueue_script('code-mirror-js', plugin_dir_url(__FILE__) . 'assets/js/admin-pagespeed.min.js', array(), '2.0');
        }
        wp_enqueue_script('custom-backend-js', plugin_dir_url(__FILE__) . 'assets/js/plugin-backend.min.js', [], '2.0', true);
        wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
        wp_enqueue_style('siwplugin-style', plugin_dir_url(__FILE__) . 'assets/scss/siw-to.css', [], '2.0');


        if (get_option('siw_enable_messages')) { //remove annoying messages, vevelende berichten meldingen
            $classes = get_option('siw_extra_classes');
            echo "<style>
              {$classes} #message .is-dismissible,
              .wrap .is-dismissible,
              .vc_license-activation-notice,
              .rs-update-notice-wrap,
              #wp-optimize-dashnotice{
                  display: none;
              }
              .updated.is-dismissible, .notice-success.is-dismissible, .notice-siwto {
                  display: block !important;
              }</style>";
        }
        if (!get_option('toggle-disable-update-btn')) {
            echo '<style>
                #wpbody-content .update-nag{
                    display: none;
                }</style>';
        }

        if (get_option('toggle-backend-style')) {
            add_action('admin_head', static function () {
                echo '<meta name="theme-color" content="' . get_option('siw_color_one') . '">';
            });
            wp_enqueue_script('custom-menufix-js', plugin_dir_url(__FILE__) . 'assets/js/menu-fix.min.js', [], '2.0', true);
            wp_enqueue_style('admin', plugin_dir_url(__FILE__) . 'assets/scss/style.css', [], '1.0');
            include_once(ABSPATH . 'wp-admin/includes/plugin.php');

            if (function_exists('is_plugin_active') && !is_plugin_active('loginpress/loginpress.php') && !get_option('siw_login_checkbox')) {
                wp_enqueue_style('admin', plugin_dir_url(__FILE__) . 'assets/scss/login.css', [], '2.0');
            }

            if (get_option('siw_color_one')) {
                include_once('includes/variables-style.php');
            }
        }
    }

    /*
     * Load custom login style.
     */
    public function siwto_load_custom_wp_login_style()
    {
        if (!wp_script_is('jquery')) {
            wp_enqueue_script('jquery');
        }

        if (get_option('toggle-backend-style')) {
            include_once(ABSPATH . 'wp-admin/includes/plugin.php');
            if (function_exists('is_plugin_active') && !is_plugin_active('loginpress/loginpress.php') && !get_option('siw_login_checkbox')) {
                wp_enqueue_script('custom-backend-js', plugin_dir_url(__FILE__) . 'assets/js/plugin-backend.js', [], '2.0', true);
                wp_enqueue_style('admin', plugin_dir_url(__FILE__) . 'assets/scss/login.css', [], '2.0');


                if ($event = $this->isEvent()) {
                    if ($event === 'christmas') {
                        $img_url = plugin_dir_url(__FILE__) . 'assets/images/santa-hat.png';
                        ob_start(); ?>
                        <div class="siw-snow">
                            <?php for ($x = 0; $x <= 6; $x++) { ?>
                                <i class="dot"></i>
                            <?php } ?>
                        </div>
                        <style>#login h1:before {
                                content: "";
                                width: 110px;
                                height: 110px;
                                position: absolute;
                                top: -18px;
                                left: -45px;
                                background-repeat: no-repeat;
                                background-size: contain;
                                background-image: url('<?php echo $img_url; ?>');
                            }</style>
                        <?php
                        echo ob_get_clean();
                    }
                    if ($event === 'easter') {
                        $img_url = plugin_dir_url(__FILE__) . 'assets/images/easter-egg.png';
                        echo '<style>#login:before {
                            content: "";
                              width: 70px;
                              height: 70px;
                              position: fixed;
                              top: unset;
                              bottom: -7px;
                              right: 0;
                              background-repeat: no-repeat;
                              background-size: contain;
                              background-image: url(' . $img_url . ');
                          }</style>';
                    }
                    if ($event === 'kingsday') {
                        $img_url = plugin_dir_url(__FILE__) . 'assets/images/crowns.png';
                        echo '<style>#login h1:before {
                              content: "";
                              width: 80px;
                              height: 80px;
                              position: absolute;
                              top: -28px;
                              left: -45px;
                              background-repeat: no-repeat;
                              background-size: contain;
                              background-image: url(' . $img_url . ');
                          }</style>';
                    }
                }

                $base_dir = trailingslashit(plugin_dir_path(__FILE__));
                $dir = '/assets/images/bgs/';
                $images = glob($base_dir . $dir . '*.jpg');

                if (get_option('siw_custom_background')) {
                    echo '<style>body.login #login:after, body.login { background-image: url("' . get_option('siw_custom_background') . '") !important; }</style>';
                } else {
                    echo '<style>body.login #login:after, body.login { background-image: url("' . plugin_dir_url(__FILE__) . $dir . basename($images[mt_rand(0, (count($images) - 1))]) . '") !important; }</style>';
                }
                if (get_option('siw_color_one')) {
                    echo '<style> .wp-core-ui .button-primary { background-color: ' . get_option('siw_color_one') . '!important; text-shadow: none !important; border-color: ' . get_option('siw_color_one') . ' !important; } </style>';
                }
            }

            if (get_option('siw_custom_logo')) {
                echo '<style>';
                echo '#wpwrap:after{background-image: none !important;}';
                echo 'body.login #login h1{ background-image: url("' . get_option('siw_custom_logo') . '") !important; background-repeat: no-repeat; background-size: contain !important; background-position: center; }';
                echo '.login h1 a{ background-image: none !important; }';
                echo '</style>';
            }

            if (get_option('siw_color_one')) {
                include_once('includes/variables-style.php');
            }
        }
        if ($this->browserIsIE(true)) {
            wp_enqueue_script('siw_ie_message', plugin_dir_url(__FILE__) . 'assets/js/siw_ie_message.min.js', ['jquery'], null);
            wp_enqueue_style('siw_ie_message', plugin_dir_url(__FILE__) . 'assets/scss/siw_ie_message.css', [], null);
        }
    }

    /*
     * SiW Dashboard Item(s)
     */
    public function siwto_custom_dashboard_widgets()
    {
        $db_title = 'SiW - Welkomstbericht';
        wp_add_dashboard_widget('SIW_welcome_msg', $db_title, static function () {
            include_once 'includes/dashboard-message.php';
        });
    }

    /*
     * SiW Dashboard Item(s)
     */
    public function siwto_custom_dashboard_widgets_admin()
    {
        $db_title = 'SiW - Admin overzicht';
        $live = $this->is_live;
        if ($this->is_siw_developer()) {
            wp_add_dashboard_widget('SIW_admin_msg', $db_title, static function () use ($live) {
                include_once 'includes/dashboard-message-admin.php';
            });
        }
    }

    /*
     * Disable Default Dashboard Widgets
     */
    public function siwto_disable_default_dashboard_widgets()
    {
        global $wp_meta_boxes;
        unset(
            $wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity'],
            $wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now'],
            $wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments'],
            $wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links'],
            $wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins'],
            $wp_meta_boxes['dashboard']['side']['core']['dashboard_primary'],
            $wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary'],
            $wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press'],
            $wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts'],
            $wp_meta_boxes['dashboard']['normal']['core']['bbp-dashboard-right-now'],
            $wp_meta_boxes['dashboard']['normal']['core']['yoast_db_widget'],
            $wp_meta_boxes['dashboard']['normal']['core']['rg_forms_dashboard']
        );

        remove_action('welcome_panel', 'wp_welcome_panel');
    }

    /*
     * Remove "Hi Admin!"
     */
    public function wp_admin_bar_my_custom_account_menu($wp_admin_bar)
    {
        $user_id = get_current_user_id();
        $current_user = wp_get_current_user();
        $profile_url = get_edit_profile_url($user_id);

        if ($user_id !== 0) {
            $avatar = get_avatar($user_id, 28);
            $howdy = sprintf(__(''), $current_user->display_name);
            $class = empty($avatar) ? '' : 'with-avatar';
            $wp_admin_bar->add_menu([
                'id' => 'my-account',
                'parent' => 'top-secondary',
                'title' => $howdy . $avatar,
                'href' => $profile_url,
                'meta' => [
                    'class' => $class,
                ],
            ]);
        }
    }

    /*
     * Add menu item to topbar
     */
    public function siwto_customize_adminbar($wp_admin_bar)
    {
        $frontpage_id = get_option('page_on_front');
        if ($frontpage_id) {
            $args = [
                'id' => 'siw_edit_homepage_link',
                'title' => __('Edit Homepage', 'siw-to'),
                'href' => '/wp-admin/post.php?post=' . $frontpage_id . '&action=edit',
                'meta' => [
                    'class' => 'siw_edit_homepage_link',
                    'title' => __('Edit Homepage', 'siw-to')
                ]
            ];
            $wp_admin_bar->add_node($args);

            $all_toolbar_nodes = $wp_admin_bar->get_nodes();
            foreach ($all_toolbar_nodes as $node) {
                parse_str($node->href, $node_arr);
                $edit_id = reset($node_arr);
                if (($node->id === 'edit') && (int)$edit_id === (int)$frontpage_id) {
                    $wp_admin_bar->remove_node($node->id);
                }
            }
        }
        if (get_option('toggle-backend-style')) {
            echo '<link rel="stylesheet" type="text/css" href="' . plugin_dir_url(__FILE__) . 'assets/scss/wpadminbar.css?2" />';

            if ($event = $this->isEvent()) {
                $img_url = '';
                if ($event === 'christmas') {
                    $img_url = plugin_dir_url(__FILE__) . 'assets/images/santa-hat.png';
                }
                if ($event === 'easter') {
                    $img_url = plugin_dir_url(__FILE__) . 'assets/images/easter-egg.png';
                }
                if ($event === 'kingsday') {
                    $img_url = plugin_dir_url(__FILE__) . 'assets/images/crowns.png';
                }

                echo "<style>#wp-admin-bar-user-info>a:after {
                content: '';
                width: 30px;
                height: 30px;
                position: absolute;
                opacity: 1 ;
                margin: 0 ;
                padding: 0 ;
                top: -2px;
                z-index: 2;
                left: -2px;
                background-repeat: no-repeat;
                background-size: contain;
                background-image: url('" . $img_url . "');
            }</style>";
            }
        }
    }

    /*
     * Coming soon mode + login
     */
    public function siwto_comingsoon_redirect()
    {
        if (get_option('toggle-coming-soon')) {
            is_user_logged_in() || auth_redirect();
        }
    }

    /*
     * Coming soon message.
     */
    public function siwto_comingsoon_infobox()
    {
        if (!wp_doing_ajax() && $GLOBALS['pagenow'] === 'wp-login.php' && get_option('toggle-coming-soon')) {
            echo '
            <h2>Website is in ontwikkeling</h2>
            <p>Beste bezoeker, momenteel zijn we druk bezig om deze website te ontwikkelen naar wens van onze klant.</p>
            <br>
            <p>Indien je een SiW Developer bent kun je hieronder inloggen zoals je gewend bent.</p>';
        }
    }

    /*
     * IP visitor
     */
    public function siwto_visitor_ip()
    {
        if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR']) {
            return $_SERVER['REMOTE_ADDR'];
        } else {
            return false;
        }
    }

    /*
     * AVG Code
     */
    public function siwto_avgcodecheck($msg)
    {
        if (!$this->is_siw_developer()) {
            return;
        }

        echo '<div class="notice notice-error is-dismissible notice-siwto">
        ' . (get_option('rename_siw_to') ?: 'SIW-TO') . ': AVG - Mist gegevens (' . $msg . '). <br>
        <small>Vul aub de benodigde gegevens <a href="admin.php?page=siw_analytics">hier</a> in of zet deze melding <a href="admin.php?page=siw-to">hier</a> uit.</small>
    </div>';
    }


    public function AVGCustomFonts()
    {
        if (is_page()) {
            if (get_option('siw_avg_font_one')) {
                wp_enqueue_script('siw_avg_font_one', plugin_dir_url(__FILE__) . 'assets/js/avg_custom_font_one.js', array(), '1.0', true);
            }
            if (get_option('siw_avg_font_two')) {
                wp_enqueue_script('siw_avg_font_two', plugin_dir_url(__FILE__) . 'assets/js/avg_custom_font_two.js', array(), '1.0', true);
            }
        }
    }

    public function AVGScript()
    {
        $container_id = get_option('container_id') ?: false;
        $code_one = get_option('code_one') ?: false;
        $code_two = get_option('code_two') ?: false;

        if (isset($_GET['page']) && $_GET['page'] === 'siw_analytics') {
            // waarom is dit gemaakt ?
        }

        add_action('admin_notices', function () use ($container_id, $code_one, $code_two) {
            if (!$container_id) {
                $this->siwto_avgcodecheck('Container ID');
                if (!$code_one) {
                    $this->siwto_avgcodecheck('Code 1 of container ID');
                }
                if (!$code_two) {
                    $this->siwto_avgcodecheck('Code 2 of container ID');
                }
            }
        }, 10);

        if ($container_id || ($code_one && $code_two)) {
            add_action('wp_head', array($this, 'cookieScript'), 1);

            // Add notices
            if (!is_admin()) { // is_admin == not backend
                require_once('includes/avg/siwavg.utils.php');
                require_once('includes/avg/siwavg.core.php');
            }

            if ($container_id && !$code_one) {
                add_action('wp_head', static function () use ($container_id) {
                    echo '<!-- Google Tag Manager -->
                    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({"gtm.start":
                    new Date().getTime(),event:"gtm.js"});var f=d.getElementsByTagName(s)[0],
                    j=d.createElement(s),dl=l!="dataLayer"?"&l="+l:"";j.async=true;j.src=
                    "https://www.googletagmanager.com/gtm.js?id="+i+dl;f.parentNode.insertBefore(j,f);
                    })(window,document,"script","dataLayer","' . $container_id . '");</script>
                    <!-- End Google Tag Manager -->';
                }, 2);
            }
            if ($container_id && !$code_two) {
                add_action('wp_head', static function () use ($container_id) {
                    echo '<!-- Google Tag Manager (noscript) -->
                    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=' . $container_id . '"
                    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
                    <!-- End Google Tag Manager (noscript) -->';
                }, 999999999);
            }

            if (!$container_id) {
                if ($code_one) {
                    add_action('wp_head', static function () {
                        echo get_option('code_one');
                    }, 2);
                }
                if ($code_two) {
                    add_action('wp_head', static function () {
                        echo get_option('code_two');
                    }, 9999999);
                }
            }

            add_action('wp', static function () {
                add_action('shutdown', static function () {
                    $final = '';
                    $levels = ob_get_level();
                    for ($i = 0; $i < $levels; $i++) {
                        $final .= ob_get_clean();
                    }
                    echo apply_filters('final_output', $final);
                }, 0);
            });

            if (get_option('siw_avg_logo_style')) {
                add_action('wp_head', function () {
                    $avg_color = get_option('siw_avg_logo_style'); // this includes classes etc.
                    echo '<style>' . $avg_color . '</style>';
                }, 1);
            }
            if (get_option('siw_avg_color')) {
                add_action('wp_head', function () {
                    $avg_color = get_option('siw_avg_color');
                    echo '<style>#CybotCookiebotDialog.cookie-bar-cb .cookie-dialogbody-cb .cookie-content-cb h2 {
                                    color: ' . $avg_color . ' !important } 
                                    #CybotCookiebotDialog.cookie-bar-cb .cookie-dialogbody-cb .siw-accept {
                                    background-color: ' . $avg_color . ' !important } 
                                    #CybotCookiebotDialog.cookie-bar-cb .cookie-dialogbody-cb .siw-accept:hover {
                                    background-color: ' . $this->siwto_luminance($avg_color, 10) . ' !important } 
                                    #CybotCookiebotDialog.cookie-bar-cb .cookie-dialogbody-cb .cookie-cookiebuttons-cb .cookie-readmore-cb {
                                    border-color: ' . $avg_color . ' !important } 
                                    #CybotCookiebotDialog.cookie-bar-cb .cookie-dialogbody-cb .cookie-cookiebuttons-cb input[type=checkbox].CybotCookiebotDialogBodyLevelButton + label:after {
                                    border-color: ' . $avg_color . ' !important }
                                    #CybotCookiebotDialog.cookie-bar-cb .cookie-dialogbody-cb .cookie-cookiebuttons-cb input[type=checkbox].CybotCookiebotDialogBodyLevelButton:checked + label:after {
                                    background-color: ' . $avg_color . ' !important } </style>';
                }, 1);
            }
            if (get_option('siw_avg_logo')) {
                add_action('wp_head', function () {
                    $avg_logo_url = get_option('siw_avg_logo');
                    echo '
            <style>
                #CybotCookiebotDialog.cookie-bar-cb .cookie-dialogbody-cb .cookie-content-cb .cookie-content-txt-cb:before {
                    background-image: url(' . $avg_logo_url . ') !important;
                }
            </style>
            ';
                }, 1);
            }
            add_action('wp_footer', [$this, 'AVGCustomFonts']);
        }
    }

    /**
     * Enqueue cookie scripts
     */
    public function cookieScript(): void
    {
        if (!wp_script_is('jquery')) {
            wp_enqueue_script('jquery');
        }

        wp_enqueue_script('siw-cookies-enabler', plugin_dir_url(__FILE__) . 'includes/avg/js/cookies-enabler.min.js');
        wp_enqueue_script('enable-cookies', plugin_dir_url(__FILE__) . 'includes/avg/js/cookiescript.js', ['siw-cookies-enabler'], '2.0', true);

        if ($this->is_siw_ip_or_logged_in()) {
            wp_enqueue_style('cookie style', plugin_dir_url(__FILE__) . 'assets/scss/cookie.css', [], '2.0');
        }

        echo '<meta name="siw-ip" content="' . $this->siwto_visitor_ip() . '" ' . (!get_option('toggle-avg-ip-msg') === 'on' ? 'data-check="on"' : 'data-check="off"') . '"/>';
    }
}

$SIW_TO = new SIW_TO();


/**
 * SiW DUMP, checks if admin and adds <pre> tags
 * @param $value , pass down the value you want to dump
 * @param boolean $admin , if admin rights needs checking
 * @param boolean $die , wether to die after dump
 */
function siw_dump($value, bool $admin = true, bool $die = false)
{
    if ($admin && !current_user_can('administrator')) {
        return;
    }

    echo '<pre>';
    var_dump($value);
    echo '</pre>';

    if ($die) {
        die();
    }
}