<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wrap">
    <?php olyoslivreblanc_display_admin_tabs($_GET['page']); ?>
    <h1><?php _e('Plugin informations', 'olyos-livre-blanc'); ?></h1>

    <div id="livreblanc-content">
        <div id="livreblanc-content-main">
            <div class="postbox">
                <div class="inside">
                    <p>Ce plugin vous permet de facilement mettre à disposition vos livres blancs sur internet dans votre site wordpress. Vous pouvez facilement créer un bloc de présentation pour vos visiteurs et l'intégrer en quelques clics où vous le souhaitez sur votre site grâce à un système de Shortcode. Pour pouvoir avoir accès à votre livre blanc, les internautes devrons renseigner un formulaire. Le livre blanc leur sera envoyé par email.</p>
                    
                    <h3>Les fonctionnalités</h3>
                    <ul>
                        <li>Vous pouvez générer autant de blocs "livre blanc" que vous le souhaitez, que vous pouvez voir dans l'onglet "<a href="?page=livreblanc-list">Vos livreblanc</a>"</li>
                        <li>Vous pouvez personnaliser votre "bloc livre blanc" comme bon vous semble</li>
                        <li>Intégrer votre page Facebook et Twitter pour augmenter les partages ainsi que votre communauté</li>
                        <li>Vous uploadez votre pdf directement dans l’interface du plugin.</li>
                        <li>Si vous souhaitez aller plus loin dans votre stratégie digitale, faites appel à notre agence <a href="https://www.olyos.fr/?utm_source=ContestWP&utm_campaign=contestplugin&utm_medium=adminlink" title="Agence web Nantes" target="_blank">Olyos</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div id="livreblanc-content-aside">
            <?php require_once( LIVREBLANC_PLUGIN_DIR . 'includes/admin_column.php' ); ?>
        </div>
    </div>
</div>