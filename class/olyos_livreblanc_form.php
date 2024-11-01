<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

class OlyosLivreblancForm {
    public $table_name;

    private $livreblanc_item;
    private $is_edit;

    function __construct($is_edit, $id) {
        global $wpdb;

        $this->is_edit = $is_edit;

        if ($is_edit) {
            $sql = $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}olyos_livreblanc WHERE id = %d",
                $id
            );
            $this->livreblanc_item = $wpdb->get_row($sql, 'ARRAY_A');
        }
        $this->table_name = $wpdb->prefix . "olyos_livreblanc";
    }

    function save(&$error_str) {
        global $wpdb;

        if (!$this->check_values($error_str)) {
            return false;
        }
        
        $data = array(
            'name' => esc_html($_REQUEST['input-name']),
            'description' => wp_kses_post($_REQUEST['description']),
            'form_title' => esc_html($_REQUEST['form-title']),
            'social_title' => esc_html($_REQUEST['social-title']),
            'social_facebook' => $_REQUEST['input-social1'],
            'social_twitter' => $_REQUEST['input-social2'],
            'newsletter_option' => (isset($_REQUEST['newsletter-chb']) && $_REQUEST['newsletter-chb'] && ($_REQUEST['newsletter-chb'] == 'checked')) ? '1' : '0',
        );
        // var_dump($_FILES['upload-file']);
        // die();
        if (($_FILES['upload-file']['name']) !== '') {
            $file = $_FILES['upload-file'];
            $uploaded = media_handle_upload('upload-file', 0);
            if(is_wp_error($uploaded)){
                $error_str = "Error uploading file: " . $uploaded->get_error_message();
                return false;
            }
            $data['upload_file'] = $uploaded;
        }
        

        if ($this->is_edit) {
            // Modify contest
            $where = array(
                'id' => (int)$_REQUEST['livreblanc_id']
            );
            return $wpdb->update($this->table_name, $data, $where);
        } else {
            // New contest
            $wpdb->insert($this->table_name, $data);
            return $wpdb->insert_id;
        }
    }

    function check_values(&$message) {
        $message = "Values are OK";
        return true;
    }

    function display() {
        if ($this->is_edit) {
            $id = stripslashes($this->livreblanc_item['id']);
            $name = stripslashes($this->livreblanc_item['name']);
            $description = stripslashes($this->livreblanc_item['description']);
            $social_title_content = stripslashes($this->livreblanc_item['social_title']);
            $file_content = stripslashes(get_the_title($this->livreblanc_item['upload_file']));
            $url = explode('/', get_attached_file($this->livreblanc_item['upload_file']));
            $file_content = end($url);
            $form_title_content = stripslashes($this->livreblanc_item['form_title']);
            $social1 = esc_html(stripslashes($this->livreblanc_item['social_facebook']));
            $social2 = esc_html(stripslashes($this->livreblanc_item['social_twitter']));
            $newsletter_checkbox = $this->livreblanc_item['newsletter_option'];
            $insert_type = 'edit';
        } else {
            $id = '';
            $name = '';
            $description = '';
            $file_content = '';
            $social_title_content = '';
            $form_title_content = '';
            $social1 = '';
            $social2 = '';
            $newsletter_checkbox = '1';
            $insert_type = 'add';

        }

        require_once(LIVREBLANC_PLUGIN_DIR . 'includes/livreblanc_edit_display_form.php');
    }
}