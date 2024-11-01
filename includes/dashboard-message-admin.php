<?php
/**
 * Created by PhpStorm.
 * User: marth
 * Date: 27-6-2018
 * Time: 20:25
 */

$justgonelive = get_transient('siwto_site_recently_live') ?: false;

?>
<div class="dashboard-item">
    <div class="social-media">
        <ul>
            <li>
                <i class="fa <?php echo $live ? 'fa-check' : 'fa-times';?>" aria-hidden="true"></i>
                Website staat live
            </li>
            <li>
                <i class="fa <?php echo $justgonelive ? 'fa-check' : 'fa-times';?>" aria-hidden="true"></i>
                Website zonet live gezet? (Controleert na livegang om de 2 dagen, anders continu)
            </li>
            <?php
                if($pl = get_option('siwto_planned_plugins')){ ?>
                    <li>
                        <i class="fa <?php echo $live ? 'fa-check' : 'fa-times';?>" aria-hidden="true"></i>
                        <?php echo count ($pl); ?> plug-in(s) <?php echo $live ? 'al verwijdert' : 'om te verwijderen' ?>
                        <ul>
                        <?php
                        $all_plugins = get_plugins();
                        foreach($pl as $plugin){?>
                            <li>- <?php echo $all_plugins[$plugin]['Name']; ?></li>
                        <?php } ?>
                        </ul>
                    </li>
               <?php  }
            ?>

            <li>
                <i class="fa fa-users" aria-hidden="true"></i>
                SiW Gebruikers die misschien weg kunnen:
                <?php $users = get_users( [ 'search' => '*specialistinwebsites.nl*', 'search_columns' => [
                    'user_login',
                    'user_nicename',
                    'user_email',
                    'user_url',
                ],] );
                // Array of WP_User objects.
                echo '<ul>';
                foreach ( $users as $user ) {
                    if($user->user_email !== 'dev@specialistinwebsites.nl') {
                        echo '<li><i class="fa fa-user" aria-hidden="true"></i>' . esc_html($user->user_email) . '</li>';
                    }
                }
                echo '</ul>'; ?>
            </li>
            <?php
            $smtp = false;
            $the_plugs = get_option('active_plugins');
            foreach($the_plugs as $key => $value) {
                $p_arr = explode('/',$value); // Folder name will be displayed
                if(stripos($value, 'smtp') !== false){
                    $smtp = $p_arr[0];
                }
            }?>
            <li>
                <i class="fa <?php echo $smtp ? 'fa-check' : 'fa-times';?>" aria-hidden="true"></i><span>
                SMTP plug-in gevonden? <?php echo $smtp ? '(Ook ingesteld? Even zelf kijken bij<a href="'.admin_url().'admin.php?page='.$smtp.'"> '.$smtp.'</a>)' : ''; ?>
                </span>
            </li>
        </ul>
    </div>
</div>
<?php


