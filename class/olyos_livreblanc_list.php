<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

if (!class_exists('WP_List_Table')) {
	require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class OlyosConcoursList extends WP_List_Table {

    /** Class constructor */
	public function __construct() {

		parent::__construct( [
			'singular' => __('White paper', 'olyos-livre-blanc'), //singular name of the listed records
			'plural'   => __('White papers', 'olyos-livre-blanc'), //plural name of the listed records
			'ajax'     => false //should this table support ajax?
		] );

	}

    public static function get_livreblanc($per_page = 5, $page_number = 1) {
        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->prefix}olyos_livreblanc";

        if (!empty($_REQUEST['orderby'])) {
            $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
            $sql .= !empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
        }

        $sql .= " LIMIT $per_page";
        $sql .= ' OFFSET ' . ($page_number - 1) * $per_page;

        $result = $wpdb->get_results($sql, 'ARRAY_A');

        return $result;
    }

    public static function delete_livreblanc($id) {
        global $wpdb;

        $wpdb->delete(
            "{$wpdb->prefix}olyos_livreblanc",
            [ 'id' => $id ],
            [ '%d' ]
        );
    }

    public static function record_count() {
        global $wpdb;
        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}olyos_livreblanc";
        return $wpdb->get_var( $sql );
    }

    function column_name($item){
        $delete_nonce = wp_create_nonce( 'delete_livreblanc' );

        // Build row actions
        $actions = array(
            'edit' 		=> sprintf('<a href="?page=%s&insert_type=%s&livreblanc_id=%s" id="%3$s" class="edit-entry">'.__('Edit', 'olyos-livre-blanc').'</a>', 'livreblanc', 'edit', absint($item['id'])),
            'delete' => sprintf('<a href="?page=%s&action=%s&livreblanc=%s&_wpnonce=%s">Delete</a>', esc_attr($_REQUEST['page']), 'delete', absint($item['id']), $delete_nonce)
        );
    
        return sprintf('%1$s %2$s', stripslashes($item['name']), $this->row_actions($actions));
    }

    function column_shortcode($item) {
        return sprintf('[livreblanc id=%s]', $item['id']);
    }

    function column_download_count($item) {
        // Build row actions
        $actions = array(
            'View' 		=> sprintf('<a href="?page=%s&livreblanc_id=%s" id="%2$s" class="view-entry">'.__('View participants', 'olyos-livre-blanc').'</a>', 'livreblanc-participant-list', absint($item['id'])),
        );
    
        return sprintf('%1$s %2$s', $this->get_download_count($item['id']), $this->row_actions($actions));
    }

    public function column_default($item, $column_name) {
        switch ($column_name) {
            case 'id':
                return $item[$column_name];
            default:
                return print_r( $item, true ); //Show the whole array for troubleshooting purposes
        }
    }

    function column_cb( $item ) {
        return sprintf(
            '<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['id']
        );
    }

    function get_columns() {
        $columns = [
            'cb'      => '<input type="checkbox" />',
            'id' => __('ID', 'olyos-livre-blanc'),
            'name' => __('Name', 'olyos-livre-blanc'),
            'shortcode' => __('Shortcode', 'olyos-livre-blanc'),
            'download_count' => __('Download count', 'olyos-livre-blanc'),
        ];
        return $columns;
    }

    function get_sortable_columns() {
        $columns = [
            'id' => array('id', true),
            'name' => array('name', true),
            // 'download_count' => array('download_count', true)
        ];
        return $columns;
    }

    public function get_bulk_actions() {
        $actions = [
            'bulk-delete' => __('Delete', 'olyos-livre-blanc')
        ];

        return $actions;
    }

    public function prepare_items() {
        /* -- Register the Columns -- */
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);

        $per_page = 20;
        $current_page = $this->get_pagenum();
        $total_items  = self::record_count();
        $total_pages = ceil($total_items/$per_page);

        $this->process_bulk_action();

        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page'    => $per_page,
            "total_pages" => $total_pages
        ]);

        $this->items = self::get_livreblanc($per_page, $current_page);
    }

    public function process_bulk_action() {
        // security check!
        if ( isset( $_POST['_wpnonce'] ) && ! empty( $_POST['_wpnonce'] ) ) {

            $nonce  = filter_input( INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING );
            $action = 'bulk-' . $this->_args['plural'];

            if (!wp_verify_nonce($nonce, $action)) {
                wp_die( 'Nope! Security check failed!' );
            }
        }

        $action = $this->current_action();

        switch ($action) {
            case 'delete':
                self::delete_livreblanc(absint($_GET['livreblanc']));
                break;;
            case 'bulk-delete':
                $delete_ids = esc_sql($_POST['bulk-delete']);
                // loop over the array of record IDs and delete them
                foreach ($delete_ids as $id) {
                    self::delete_livreblanc($id);
                }
                break;
            default:
                break;
        }
    }

    public function get_download_count($livreblanc_id) {
        global $wpdb;

        $sql = $wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}olyos_livreblanc_download
            WHERE id_livreblanc = %d",
            $livreblanc_id
        );

        return $wpdb->get_var($sql);
    }
}