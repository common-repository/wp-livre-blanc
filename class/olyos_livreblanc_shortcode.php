<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

class OlyosConcoursShortcode {
    public $livreblanc_table_name;
    public $user_table_name;
    public $download_table_name;
    public $livreblanc_id;
    private $item;

    function __construct($id) {
        global $wpdb;

        if (!absint($id)) {
            throw new Exception('Id is not an int: '. $id);
        }

        $this->livreblanc_table_name = $wpdb->prefix . "olyos_livreblanc";
        $this->user_table_name = $wpdb->prefix . "olyos_livreblanc_user";
        $this->download_table_name = $wpdb->prefix . "olyos_livreblanc_download";

        $sql = $wpdb->prepare(
            "SELECT * FROM $this->livreblanc_table_name WHERE id = %d",
            $id
        );
        $this->item = $wpdb->get_row($sql, 'ARRAY_A');

        if (!$this->item) {
            throw new Exception('Id not in database: '. $id);
        }

        $this->livreblanc_id = $id;
    }

    public function get_shortcode_html() {
        $str = '';
        $str .= '<section class="olyosconc">';
        // Header
        $str .= '<h2>'.stripslashes($this->item['name']).'</h2>';
        $str .= wpautop(stripslashes($this->item['description']));

        $str .= '<hr id="description_separator">';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Save new participation
            if ($this->save()) {
                $str .= $this->get_confirmation_html();
                // Send white paper by email
                $str .= $this->send_white_paper();
            } else {
                $str .= $this->get_form_html();
                $str .= '<p id="olyosconc-message" class="olyosconc-message error">'.__('Could not register your participation, try again.', 'olyos-livre-blanc').'</p>';
            }
        } else {
            $str .= $this->get_form_html();
        }

        // Social links
        $str .= '<h3>'.stripslashes($this->item['social_title']).'</h3>';
        $str .= '<div class="social-box facebook">'.stripslashes($this->item['social_facebook']).'</div>';

        // Twitter
        if ($this->item['social_twitter']) {
            $twitterurl = 'https://api.twitter.com/1/statuses/oembed.json?url='.$this->item['social_twitter'].'&hide_media=true&hide_thread=true';

            if ($this->get_http_response_code($twitterurl) != '200') {
                $str .= '<p id="olyosconc-message" class="olyosconc-message warning">'.__('Could not fetch twitter url.', 'olyos-livre-blanc').'</p>';
            } else {
                $json = file_get_contents($twitterurl);
                $data = json_decode($json);
                $str .= '<div class="social-box twitter">'.$data->html.'</div>';
            }
        }

        $str .= '</section>';
        $str .= '<p class="olyos-copyright"><img src="' . esc_url(plugins_url('img/icon_small_olyos.png', dirname(__FILE__))) . '"/>'.__('By ', 'olyos-livre-blanc').'
            <a target="_blank" title="'.__('Wordpress plugins creation - Nantes Web Agency', 'olyos-livre-blanc').'"href="https://www.olyos.fr/?utm_source=WPLB&utm_campaign=pluginlivreblanc&utm_medium=frontlink">Olyos</a>
            / <a target="_blank" title="'.__('News webdesign', 'olyos-livre-blanc').'"href="http://olybop.fr/?utm_source=WPLB&utm_campaign=pluginlivreblanc&utm_medium=frontlink">Olybop</a>
        </p>';

        return $str;
    }

    private function get_form_html() {
        $str = '';

        // Form
        $str .= '<h3>'.stripslashes($this->item['form_title']).'</h3>';
        $str .= '<form id="livreblanc-form" name="livreblanc_form" method="post" action="#olyosconc-message">';
 
        $str .= '<div class="email-field"><label for="email-input">'.__('Email', 'olyos-livre-blanc').'</label><input required type="text" name="email" id="email-input" value=""/></div>';

        if ($this->item['newsletter_option']) {
            $str .= '<div class="newsletter-field"><input type="checkbox" name="newsletter" id="newsletter-input"/><label for="newsletter-input">'.__('Subscribe to our newsletter', 'olyos-livre-blanc').'</label></div>';
        }

        $str.= '<div class="submit-field"><input type="submit" name="Save" value="'.__('Send me the white paper', 'olyos-livre-blanc').'" class="button-primary" /></div>';

        $str .= '</form>';

        return $str;
    }

    private function get_confirmation_html() {
        $str = '';
        // $str .= '<p id="olyosconc-message" class="olyosconc-message success">'.__('Your subscription has been received.', 'olyos-livre-blanc').'</p>';

        return $str;
    }

    private function save() {
        global $wpdb;
        
        if (!$this->check_values()) {
            return false;
        }

        $user_id = $this->get_user_from_email(sanitize_email($_REQUEST['email']));
        // New user?
        if ($user_id === false) {
            $user_data = array(
                'email' => sanitize_email($_REQUEST['email']),
            );
            $wpdb->insert($this->user_table_name, $user_data);
            $user_id = $wpdb->insert_id;
        }
        // Register participation
        $participation_data = array(
            'id_user' => $user_id,
            'id_livreblanc' => $this->livreblanc_id,
            'subscribe_newsletter' => ((isset($_REQUEST['newsletter']) && ($_REQUEST['newsletter'])) ? 1 : 0),
            'ip_address' => filter_var($_SERVER["REMOTE_ADDR"], FILTER_VALIDATE_IP)
        );

        return $wpdb->insert($this->download_table_name, $participation_data);
    }

    private function check_values() {
        if (sanitize_email($_REQUEST['email']) == '') {
            return false;
        }
        return true;
    }

    private function get_user_from_email($email) {
        global $wpdb;

        $sql = $wpdb->prepare(
            "SELECT `id` FROM $this->user_table_name
            WHERE email = '%s';",
            $email
        );

        $result = $wpdb->get_results($sql);
        if (!empty($result)) {
            return $result[0]->id;
        }

        return false;
    }

    private function get_http_response_code($url) {
        $headers = get_headers($url);
        return substr($headers[0], 9, 3);
    }

    private function send_white_paper() {
        $email_target = sanitize_email($_REQUEST['email']);
        $email_attachments = get_attached_file($this->item['upload_file']);

		$email_title = "Voici votre livre blanc";

		$email_content = file_get_contents( LIVREBLANC_PLUGIN_DIR . "/includes/simple-announcement.html");
        $email_content = str_replace('{{plugin_url}}', plugins_url('..', __FILE__), $email_content);

        add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));
        
		if (wp_mail($email_target, $email_title, $email_content, '', $email_attachments)) {
            return '<p id="olyosconc-message" class="olyosconc-message success">'.__('An email has been sent to your address', 'olyos-livre-blanc').'</p>';
		} else {
            return '<p id="olyosconc-message" class="olyosconc-message success">'.__('An error occured, the email could not be send', 'olyos-livre-blanc').'</p>';
		}
    }
}