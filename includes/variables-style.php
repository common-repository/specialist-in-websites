<?php
/**
 * Created by PhpStorm.
 * User: Marthijn
 * Date: 09-Jul-18
 * Time: 10:07 AM
 */
$color = get_option('siw_color_one');
$lightest_color = $this->siwto_luminance($color, 70);
$lighter_color = $this->siwto_luminance($color, 25);
$light_color = $this->siwto_luminance($color, 10) ?>
<style>
    /*#wpadminbar #wp-admin-bar-my-sites a.ab-item, #wpadminbar #wp-admin-bar-site-name a.ab-item{*/
    /*   background-image: url("https://www.google.com/s2/favicons?domain=*/<?php //echo get_home_url() ?>/*");*/
    /*   background-size: contain;*/
    /*   background-repeat: no-repeat;*/
    /*   padding-left: 60px;*/
    /*   background-position-x: 10px;*/
    /*}*/
    /*.wp-admin #wpadminbar #wp-admin-bar-site-name>.ab-item:before{*/
    /*   content: "" !important;*/
    /*}*/
    #adminmenu, #adminmenu .wp-submenu, #adminmenuback, #adminmenuwrap, #adminmenu .wp-has-current-submenu .wp-submenu .wp-submenu-head, #adminmenu .wp-menu-arrow, #adminmenu .wp-menu-arrow div, #adminmenu li.current a.menu-top, #adminmenu li.wp-has-current-submenu a.wp-has-current-submenu, .folded #adminmenu li.current.menu-top, .folded #adminmenu li.wp-has-current-submenu{
        background-color: <?php echo $color; ?> !important;
    }
    #adminmenu li.menu-top:hover, #adminmenu li.opensub>a.menu-top, #adminmenu li>a.menu-top:focus, #adminmenu .wp-has-current-submenu .wp-submenu, #adminmenu .wp-has-current-submenu .wp-submenu.sub-open, #adminmenu .wp-has-current-submenu.opensub .wp-submenu, #adminmenu a.wp-has-current-submenu:focus+.wp-submenu, .no-js li.wp-has-current-submenu:hover .wp-submenu{
        background-color: <?php echo $light_color ?> !important;
    }
    #adminmenu a{
        color: white !important;
        opacity: 0.8 !important;
    }
    #adminmenu .wp-has-current-submenu .wp-submenu .wp-submenu-head, #adminmenu .wp-menu-arrow, #adminmenu .wp-menu-arrow div, #adminmenu li.current a.menu-top, #adminmenu li.wp-has-current-submenu a.wp-has-current-submenu, .folded #adminmenu li.current.menu-top, .folded #adminmenu li.wp-has-current-submenu{
        color: white !important;
        opacity: 1 !important;
    }
    body a {
        color: <?php echo $color; ?>;
    }
    body a:hover, body a:active, body a:focus {
        color: <?php echo $lighter_color?>;
    }
    .wrap .add-new-h2:hover, .wrap .page-title-action:hover{
        color: <?php echo $lightest_color; ?> !important;
    }
    #adminmenu li.menu-top:hover, #adminmenu li.opensub>a.menu-top, #adminmenu li>a.menu-top:focus, #adminmenu .wp-submenu a:focus, #adminmenu .wp-submenu a:hover, #adminmenu a:hover, #adminmenu li.menu-top>a:focus{
        color: white !important;
    }
    #adminmenu li.wp-has-submenu.wp-not-current-submenu.opensub:hover:after{
        border-right-color: <?php echo $color; ?> !important;
    }
    .wp-core-ui .button-primary{
        background: <?php echo $color; ?> !important;
        border-color: <?php echo $color; ?> !important;
        text-shadow: 0 -1px 1px <?php echo $color; ?>, 1px 0 1px <?php echo $color; ?>, 0 1px 1px <?php echo $color; ?>, -1px 0 1px <?php echo $color; ?> !important;
    }
    .wp-core-ui .button-primary.focus, .wp-core-ui .button-primary.hover, .wp-core-ui .button-primary:focus, .wp-core-ui .button-primary:hover{
        background: <?php echo $light_color ?> !important;
        border-color: <?php echo $light_color ?> !important;
    }
    #wpadminbar .quicklinks #wp-admin-bar-root-default li:hover a,
    #wpadminbar .quicklinks #wp-admin-bar-root-default li:hover span:before,
    #wpadminbar .quicklinks #wp-admin-bar-root-default li:hover span,
    #wpadminbar .quicklinks #wp-admin-bar-root-default li:hover a:before,
    #wpadminbar .quicklinks #wp-admin-bar-root-default li.hover a,
    #wpadminbar .quicklinks #wp-admin-bar-root-default li.hover span:before,
    #wpadminbar .quicklinks #wp-admin-bar-root-default li.hover span,
    #wpadminbar .quicklinks #wp-admin-bar-root-default li.hover a:before,
    #wpadminbar .quicklinks #wp-admin-bar-root-default li:hover ul li:hover a,
    #wpadminbar .quicklinks #wp-admin-bar-root-default li:hover ul li:hover span,
    #wpadminbar .quicklinks #wp-admin-bar-root-default li:hover ul a:hover a,
    #wpadminbar .quicklinks #wp-admin-bar-root-default li:hover ul a:hover span,
    #wpadminbar .quicklinks #wp-admin-bar-root-default li:hover ul span:hover a,
    #wpadminbar .quicklinks #wp-admin-bar-root-default li:hover ul span:hover span,
    #wpadminbar .quicklinks #wp-admin-bar-root-default li.hover ul li:hover a,
    #wpadminbar .quicklinks #wp-admin-bar-root-default li.hover ul li:hover span,
    #wpadminbar .quicklinks #wp-admin-bar-root-default li.hover ul a:hover a,
    #wpadminbar .quicklinks #wp-admin-bar-root-default li.hover ul a:hover span,
    #wpadminbar .quicklinks #wp-admin-bar-root-default li.hover ul span:hover a,
    #wpadminbar .quicklinks #wp-admin-bar-root-default li.hover ul span:hover span {
        background-color: <?php echo $color; ?> !important;
    }
    #wpadminbar .quicklinks #wp-admin-bar-root-default li:hover .yoast-issue-counter span,
    #wpadminbar .quicklinks #wp-admin-bar-root-default li.hover .yoast-issue-counter span {
        background-color: transparent !important;
    }

    .postbox .postbox-header,
    .hndle.ui-sortable-handle,
    .hndle.ui-sortable-handle + .handle-actions,
    .acf-field-object.open > .handle,
    .acf-hl.acf-thead,
    .components-button.is-primary,
    .acf-field[data-type='group'] > .acf-label {
        background-color: <?php echo $color; ?> !important;
        border-color: <?php echo $color; ?> !important;
    }
    .acf-table thead tr th{
        background-color: <?php echo $this->siwto_luminance($color, -20) ?> !important;
    }
    .wp-core-ui .button-primary-disabled, .wp-core-ui .button-primary.disabled, .wp-core-ui .button-primary:disabled, .wp-core-ui .button-primary[disabled]{
        background-color: <?php echo $color; ?> !important;
        border-color: <?php echo $color; ?> !important;
        color: <?php echo $this->siwto_luminance($color, 30) ?> !important;
    }
    .wp-core-ui .button-primary, .wrap .add-new-h2, .wrap .add-new-h2:active, .wrap .page-title-action, .wrap .page-title-action:active{
        background-color: <?php echo $color; ?> !important;
        border-color: <?php echo $color; ?> !important;
    }
    #adminmenu li.wp-has-submenu.wp-not-current-submenu.opensub:hover:after{
        border-right-color: <?php echo $color; ?> !important;
    }
    .siw_checkbox_container-item::after{
        background-color: <?php echo $color; ?> !important;
    }
    .plugins .active td, .plugins .active th{
        background-color: rgba(<?php list($r, $g, $b) = sscanf($color, '#%02x%02x%02x'); echo "$r, $g, $b";?>, 0.04) !important;
    }
    .plugin-update-tr.active td, .plugins .active th.check-column{
        border-left-color: <?php echo $color?> !important;
    }
    <?php
    if($loading_gif = get_option('siw_rebrand_loading_gif')){ ?>
    body.login:before,
    .siw_wp_ajax_save .submit.siw-ajax-busy:after,
    .page_speed_result
    {
        background-image: url(<?php echo $loading_gif ?>) !important;
    }
    <?php }
    if($loading_gif || get_option('rename_siw_to')){?>
    #wpwrap::after{
        display: none !important;
    }
    <?php } ?>

 /*TEST SIW */


</style>

<?php
//var_dump(get_current_screen());
//die();
?>