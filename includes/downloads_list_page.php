<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

// Get all contests
$livresblancs = olyoslivreblanc_get_livreblancs();
?>


<div class="wrap">
    <?php olyoslivreblanc_display_admin_tabs($_GET['page']); ?> 
    <h2><?php _e('List of your white papers downloads', 'olyos-livre-blanc'); ?></h2>

    <div id="poststuff" class="">
        <div id="post-body" class="metabox-holder">
            <div id="post-body-content">
                <div class="postbox choice-box" id="select-concours">
                    <div class="inside">
                        <form name="select_contest" method="get" action="">
                            <h3><?php _e('1 - Choisir votre livre blanc', 'olyos-livre-blanc'); ?></h3>
                            <p>Sélectionnez le livre blanc pour en extraire la liste des downloads et des inscrits à la newsletter.</p>
                            <select name="livreblanc_id">
                                <option value="">Tous</option>
                                <?php foreach ($livresblancs as $livreblanc):?>
                                    <option 
                                        value="<?php echo $livreblanc->id; ?>"
                                        <?php echo ((!empty($_REQUEST['livreblanc_id']) && ($_REQUEST['livreblanc_id'] == $livreblanc->id)) ? 'selected' : ''); ?>
                                    >
                                        <?php echo($livreblanc->id." : ".stripslashes($livreblanc->name)); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" name="page" value="livreblanc-participant-list" />
                            <input type="submit" name="pick" value="<?php _e('Select white paper', 'olyos-livre-blanc') ?>" class="button-primary" />
                        </form>
                    </div>
                </div>

                <div class="postbox choice-box" id="select-newsletter">
                    <div class="inside">
                        <form name="newsletter_list" method="post" action="">
                            <h3><?php _e('2 - Générer une liste des "Optin" Newsletter', 'olyos-livre-blanc'); ?></h3>
                            <p>Générer une liste des inscrits à la newsletter "optin" sur un ou tous les livres blancs. (en fonction du choix 1)</p>
                            <input type="hidden" name="form-type" value="generate-newsletter" />
                            <input type="hidden" name="livreblanc-select" value="<?php echo (!empty($_REQUEST['livreblanc_id']) ? $_REQUEST['livreblanc_id'] : '' ) ?>" />
                            <input type="submit" name="generate-newsletter" value="<?php _e('Generate', 'olyos-livre-blanc') ?>" class="button-primary" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_REQUEST['form-type'])) {
            if ($_REQUEST['form-type'] == 'generate-newsletter') {
                // Generate list of participants who want the newsletter
                $id_livreblanc = $_REQUEST['livreblanc-select'];

                if ($id_livreblanc === '') {
                    echo '<div class="message">Vous devez sélectionner un livreblanc</div>';
                }
                
                $participants = olyoslivreblanc_get_downloads($id_livreblanc, true);

                echo olyoslivreblanc_generate_users_list_html($participants);
            }
        }
    }
?>

<h2>Liste des téléchargements de vos livres blancs</h2>

<?php
    require_once( LIVREBLANC_PLUGIN_DIR . 'class/olyos_downloads_list.php' );
    $participants_list = new OlyosDownloadsList();
    $participants_list->prepare_items();
    
    $participants_list->display();
?>

</div>





<?php
////////////////////////////
// Utilitary functions
////////////////////////////

function olyoslivreblanc_get_livreblancs() {
    global $wpdb;

    $sql = "SELECT id, name FROM {$wpdb->prefix}olyos_livreblanc";

    $result = $wpdb->get_results($sql);

    return $result;
}

function olyoslivreblanc_get_downloads($id_livreblanc, $only_newsletter = false) {
    global $wpdb;

    $sql = $wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}olyos_livreblanc_download p
        LEFT JOIN {$wpdb->prefix}olyos_livreblanc_user u
        ON p.id_user = u.id
        WHERE p.`id_livreblanc` = %d",
        $id_livreblanc
    );

    if ($only_newsletter) {
        $sql .= " AND `subscribe_newsletter` = 1";
    }

    $result = $wpdb->get_results($sql);

    return $result;

}

function olyoslivreblanc_generate_users_list_html($users) {
    $str ='';

     $str .= '<ul id="livreblanc-participant-list-list">';

    foreach ($users as $user) {
        $str .= '<li>';
        $str .= esc_html('<'.$user->email.'>');
        $str .= '</li>';
    }

    $str .= '</ul>';

    return $str;
}