<?php
    if (!defined('ABSPATH')) exit; // Exit if accessed directly

    require_once( LIVREBLANC_PLUGIN_DIR . 'class/olyos_livreblanc_list.php' );
    $livreblanc_list = new OlyosConcoursList();
    
    $page  = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRIPPED );
    $paged = filter_input( INPUT_GET, 'paged', FILTER_SANITIZE_NUMBER_INT );
?>

<div class="wrap">
    <?php olyoslivreblanc_display_admin_tabs($_GET['page']); ?>
    <h1><?php _e('List of your white papers', 'olyos-livre-blanc'); ?></h1>
    <p>Pour ajouter un nouveau livre blanc sur une page article, utilisez le shortcode suivant : [livreblanc id="ID"] (ex = [livreblanc id="1"]</p>

    <form id="wpse-list-table-form" method="post">
        <input type="hidden" name="page" value="<?php echo $page ?>" />
        <input type="hidden" name="paged" value="<?php echo $paged ?>" />
        <?php $livreblanc_list->prepare_items(); ?>
        <?php $livreblanc_list->display(); ?>
    </form>

</div>