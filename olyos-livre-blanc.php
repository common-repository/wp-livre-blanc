<?php
/*
Plugin Name: WP Livre Blanc
Version: 1.0.1
Description: Organize white papers : personalize the way you present your paper, send your white paper by email,generate a list of email.
Author: Olyos - Web Agency
Author URI: https://www.olyos.fr
Text Domain: olyos-livre-blanc
Domain Path: /lang/
Licence: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if (!defined('ABSPATH')) exit; // Exit if accessed directly

define('LIVREBLANC_PLUGIN_VERSION','1.0.1');
define('LIVREBLANC_PLUGIN_DIR', plugin_dir_path(__FILE__));

// init translation
add_action( 'init', 'olyoslivreblanc_init_languages');

// Hook when install
register_activation_hook(__FILE__,"olyoslivreblanc_install");
// Hook when desactivate
register_deactivation_hook(__FILE__,"olyoslivreblanc_desactivate");
// Hook when uninstall
register_uninstall_hook(__FILE__,"olyoslivreblanc_uninstall");

// add admin main menu
add_action( 'admin_menu', 'olyoslivreblanc_register_admin_menu' );

// add to footer
add_action('admin_footer', 'olyoslivreblanc_admin_footer');

// Register style sheet.
add_action('wp_enqueue_scripts', 'olyoslivreblanc_register_fo_css');

add_action('admin_action_process_livreblanc_form', 'olyoslivreblanc_process_livreblanc_form');

/**
 * Init language
 */
function olyoslivreblanc_init_languages(){
    load_plugin_textdomain('olyos-livre-blanc', false, plugin_basename(dirname(__FILE__)) . '/lang/');
}

/**
 * On install
 */
function olyoslivreblanc_install() {
    global $wpdb;
    $table_livreblanc = $wpdb->prefix . "olyos_livreblanc";
    $table_user = $wpdb->prefix . "olyos_livreblanc_user";
    $table_download = $wpdb->prefix . "olyos_livreblanc_download";

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "
        CREATE TABLE $table_livreblanc (
            id INT UNSIGNED AUTO_INCREMENT,
            name VARCHAR(255),
            description TEXT,
            upload_file VARCHAR(255),
            form_title VARCHAR(255),
            social_title VARCHAR(255),
            social_facebook TEXT,
            social_twitter TEXT,
            newsletter_option BOOLEAN,
            PRIMARY KEY  (id)
        ) $charset_collate;
        CREATE TABLE $table_user (
            id BIGINT UNSIGNED AUTO_INCREMENT,
            email VARCHAR(100),
            PRIMARY KEY  (id)
        ) $charset_collate;
        CREATE TABLE $table_download (
            id BIGINT UNSIGNED AUTO_INCREMENT,
            id_user BIGINT UNSIGNED,
            id_livreblanc INT UNSIGNED,
            subscribe_newsletter BOOLEAN DEFAULT 0,
            ip_address VARCHAR(45),
            PRIMARY KEY  (id)
        ) $charset_collate;
    ";

    // Update tables without deleting existing content
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

}

/**
 * On uninstall
 */
function olyoslivreblanc_uninstall() {
    global $wpdb;
    $table_livreblanc = $wpdb->prefix . "olyos_livreblanc";
    $table_download = $wpdb->prefix . "olyos_livreblanc_download";
    $table_user = $wpdb->prefix . "olyos_livreblanc_user";

    // delete table
    $wpdb->query("DROP TABLE IF EXISTS $table_download");
    $wpdb->query("DROP TABLE IF EXISTS $table_user");
    $wpdb->query("DROP TABLE IF EXISTS $table_livreblanc");
}

function olyoslivreblanc_desactivate() {
    // Flush Cache/temp
    // Flush Permalinks
}

/**
 * Plugin sub navigation menu
 */
function olyoslivreblanc_register_admin_menu(){
    $menu_hook_suffixes = array();

    add_menu_page( 'WP Livre Blanc  ', 'WP Livre Blanc    ', 'manage_options', 'livreblanc-infos', 'olyoslivreblanc_display_livreblanc_infos', plugins_url('img/icon.png', __FILE__), 100 );
    $menu_hook_suffixes[] = add_submenu_page('livreblanc-infos', __('Informations', 'olyos-livre-blanc'), __('Informations', 'olyos-livre-blanc'), 'manage_options', "livreblanc-infos", "olyoslivreblanc_display_livreblanc_infos");
    $menu_hook_suffixes[] = add_submenu_page('livreblanc-infos', __('White papers list', 'olyos-livre-blanc'), __('White papers list', 'olyos-livre-blanc'), 'manage_options', "livreblanc-list", "olyoslivreblanc_display_livreblanc_list");
    $menu_hook_suffixes[] = add_submenu_page('livreblanc-infos', __('Add new white paper', 'olyos-livre-blanc'), __('Add new white paper', 'olyos-livre-blanc'), 'manage_options', "livreblanc", "olyoslivreblanc_display_livreblanc_add");
    $menu_hook_suffixes[] = add_submenu_page('livreblanc-infos', __('View downloads', 'olyos-livre-blanc'), null, 'manage_options', "livreblanc-participant-list", "olyoslivreblanc_display_livreblanc_participants"); // null to not display item in admin menu

    // Only add JS/CSS when on a plugin page
    foreach ($menu_hook_suffixes as $hook_suffix) {
        add_action( 'load-' . $hook_suffix , 'olyoslivreblanc_admin_init' );
    }
}

function olyoslivreblanc_display_livreblanc_list() {
    if (!current_user_can('manage_options')) {
      wp_die(__('You do not have sufficient permissions to access this page.', 'olyos-livre-blanc'));
    }
    require_once( LIVREBLANC_PLUGIN_DIR . 'includes/list_livreblanc_page.php' );
}

function olyoslivreblanc_display_livreblanc_add() {
    if (!current_user_can('manage_options')) {
      wp_die(__('You do not have sufficient permissions to access this page.', 'olyos-livre-blanc'));
    }
    require_once( LIVREBLANC_PLUGIN_DIR . 'includes/livreblanc_page.php' );
}

function olyoslivreblanc_display_livreblanc_infos() {
    if (!current_user_can('manage_options')) {
      wp_die(__('You do not have sufficient permissions to access this page.', 'olyos-livre-blanc'));
    }
    require_once( LIVREBLANC_PLUGIN_DIR . 'includes/infos_livreblanc_page.php' );
}

function olyoslivreblanc_display_livreblanc_participants() {
    if (!current_user_can('manage_options')) {
      wp_die(__('You do not have sufficient permissions to access this page.', 'olyos-livre-blanc'));
    }
    require_once( LIVREBLANC_PLUGIN_DIR . 'includes/downloads_list_page.php' );
}

function olyoslivreblanc_process_livreblanc_form() {
    require_once( LIVREBLANC_PLUGIN_DIR . 'includes/process_livreblanc_form.php' );
}

function olyoslivreblanc_admin_init() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-core');

    wp_register_style('myBackofficeStyleSheet', plugins_url('css/backoffice.css', __FILE__));
    wp_enqueue_style( 'myBackofficeStyleSheet');
}

function olyoslivreblanc_display_admin_tabs($current = 'list') {
    require_once( LIVREBLANC_PLUGIN_DIR . 'includes/admin_header.php' );

    $tabs = array(
        'livreblanc-infos' => array('', __('Informations', 'olyos-livre-blanc')),
        'livreblanc-list' => array('', __('White papers list', 'olyos-livre-blanc')),
        'livreblanc-participant-list' => array('', __('View downloads', 'olyos-livre-blanc')),
    );

    echo '<div class="nav-tab-wrapper">';
    foreach( $tabs as $slug => $value ) {
        $class = ($slug == $current) ? ' nav-tab-active' : '';
        echo '<a class="nav-tab'.$class.'" href="?page='.$slug.$value[0].'">'.$value[1].'</a>';
    }

    echo '<a href="?page=livreblanc&insert_type=add" id="livreblanc-add-tab" class="nav-tab">'.__('Add new white paper', 'olyos-livre-blanc').'</a>';
    echo '</div>';
}

function olyoslivreblanc_admin_footer() {
    $locale = explode('_', get_locale())[0];
?>
    <script type="text/javascript">
        jQuery(document).ready(function(){
            jQuery("#livreblanc-shortcode input[type='text']").click(function () {
                jQuery(this).select();
            });
        });

    </script>
<?php
}

function olyoslivreblanc_register_fo_css() {
    wp_register_style('livreblanc-frontend', plugins_url('css/style.css', __FILE__));
}

/////////////////////////
// Shortcode
/////////////////////////
add_shortcode('livreblanc', 'olyoslivreblanc_livreblanc_shortcode');

function olyoslivreblanc_livreblanc_shortcode($atts) {
    $a = shortcode_atts( array(
        'id' => -1
    ), $atts );

    if ($a['id'] == -1) {
        return '<p class="livreblanc-message error">'.__('No id attribute found.', 'olyos-livre-blanc').'</p>';
    }

    require_once( LIVREBLANC_PLUGIN_DIR . 'class/olyos_livreblanc_shortcode.php' );
    try {
        $shortcode = new OlyosConcoursShortcode($a['id']);
    } catch (Exception $e) {
        return '<p class="livreblanc-message error">'.__('Shortcode ID unknown: ', 'olyos-livre-blanc').$e->getMessage().'</p>';

    }

    wp_enqueue_style('livreblanc-frontend');

    // Show the shortcode
    return $shortcode->get_shortcode_html();
}