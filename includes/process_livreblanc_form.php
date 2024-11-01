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
    $livreblanc_id = '';
}

$error_str = '';

$livreblanc_form = new OlyosLivreblancForm($is_edit, $livreblanc_id);

$result = $livreblanc_form->save($error_str);

if ($result === false) {
    // There was an error, could not save to BDD
    $message_str = __('Error while saving to database', 'olyos-livre-blanc') . ': '.$error_str;

    if ($is_edit) {
        wp_redirect('admin.php?page=livreblanc&insert_type=edit&livreblanc_id='.$livreblanc_id.'&result_message='.urlencode($message_str));
    } else {
        wp_redirect('admin.php?page=livreblanc&insert_type=add&result_message='.urlencode($message_str));
    }
} else {
    // Could save the white paper
    $message_str = __('White paper Modified.', 'olyos-livre-blanc');
    if ($is_edit) {
        wp_redirect('admin.php?page=livreblanc&insert_type=edit&livreblanc_id='.$livreblanc_id.'&result_message='.urlencode($message_str));
    } else {
        wp_redirect('admin.php?page=livreblanc&insert_type=edit&livreblanc_id='.$result.'&result_message='.urlencode($message_str));
    }
}