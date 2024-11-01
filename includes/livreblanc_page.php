<?php
    if (!defined('ABSPATH')) exit; // Exit if accessed directly

   require_once( LIVREBLANC_PLUGIN_DIR . 'class/olyos_livreblanc_form.php' );

    // Check if edit or add
    if (isset($_REQUEST['insert_type']) && ($_REQUEST['insert_type'] == 'edit')) {
        $is_edit = true;
        $livreblanc_id = (int)esc_attr($_REQUEST['livreblanc_id']);
    } else {
        // Defaults to a new contest
        $is_edit = false;
        $livreblanc_id = "";
    }

    $message_str = '';
    if (isset($_REQUEST['result_message'])) {
        $message_str = '<div id="message" class="updated notice is-dismissible"><p>'.urldecode($_REQUEST['result_message']).'</p></div>';
    }

    $livreblanc_form = new OlyosLivreblancForm($is_edit, $livreblanc_id);
?>

<div class="wrap">
    <?php olyoslivreblanc_display_admin_tabs($_GET['page']); ?>
    <h1><?php ($is_edit) ? _e('Modify white paper', 'olyos-livre-blanc') : _e('Add new white paper', 'olyos-livre-blanc') ?></h1>

    <?php echo $message_str; ?>
    <div id="livreblanc-content">
        <div id="livreblanc-content-main">
            <?php $livreblanc_form->display(); ?>
        </div>
        <div id="livreblanc-content-aside">
            <?php require_once( LIVREBLANC_PLUGIN_DIR . 'includes/admin_column.php' ); ?>
        </div>
    </div>
</div>
