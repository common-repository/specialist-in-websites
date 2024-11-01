<?php
/**
 * Created by PhpStorm.
 * User: Marthijn
 * Date: 22-Jun-18
 * Time: 3:29 PM
 */
$SIW_TO = new SIW_TO();
if (! $SIW_TO->is_siw_developer()) {
    echo '<h1>' .__('Je moet een SiW developer zijn om dit scherm te kunnen bekijken', 'siw-to'). '.</h1>';
    return;
}
_e('<h1>'.(get_option('siw_company_name') ?: 'Specialist in Websites').'<br><small>Technisch Onderhoudspakket</small></h1>');
?>
    <div class="wrap">
        <!-- run the settings_errors() function here. -->
        <?php settings_errors(); ?>

        <?php
        $active_tab = 'toggle-options';
        if (isset($_GET['tab'])) {
            $active_tab = htmlspecialchars($_GET['tab']);
        }
        ?>

        <div class="nav-tab-wrapper">
            <a href="?page=siw-to&tab=toggle-options" class="nav-tab <?php echo $active_tab === 'toggle-options' ? 'nav-tab-active' : ''; ?>">Toggles</a>
            <a href="?page=siw-to&tab=rebrand-options" class="nav-tab <?php echo $active_tab === 'rebrand-options' ? 'nav-tab-active' : ''; ?>">Rebrand</a>
            <a href="?page=siw-to&tab=page-speed-options" class="nav-tab <?php echo $active_tab === 'page-speed-options' ? 'nav-tab-active' : ''; ?>">Page Speed</a>
            <?php if (get_option('toggle-backend-style')) { ?>
                <a href="?page=siw-to&tab=style-options" class="nav-tab <?php echo $active_tab === 'style-options' ? 'nav-tab-active' : ''; ?>">Style</a>
            <?php }?>
        </div>

        <form action="options.php" class="siw_wp_ajax_save" id="main-toggles" method="post">
            <?php
            if ($active_tab === 'toggle-options') {
                settings_fields('toggle_section');
                do_settings_sections('theme-toggles');
                submit_button();
                submit_button(__('Op de oude manier opslaan', 'siw-to'), 'submit-backup');
            } else if ($active_tab === 'style-options') {
                settings_fields('style_section');
                do_settings_sections('theme-style');
                submit_button();
                submit_button(__('Op de oude manier opslaan', 'siw-to'), 'submit-backup');
            } else if ($active_tab === 'rebrand-options') {
                settings_fields('rebrand_section');
                do_settings_sections('theme-rebrand');
                submit_button();
                submit_button(__('Op de oude manier opslaan', 'siw-to'), 'submit-backup');
            } else if ($active_tab === 'safety-options') {
                settings_fields('safety_section');
                do_settings_sections('theme-safety');
                submit_button();
                submit_button(__('Op de oude manier opslaan', 'siw-to'), 'submit-backup');
            } else if ($active_tab === 'page-speed-options') {
                settings_fields('page_speed_section');
                do_settings_sections('theme-page-speed');
            }
            ?>
        </form>
    </div>

