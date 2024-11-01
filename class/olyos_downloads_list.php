<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

if (!class_exists('WP_List_Table')) {
	require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class OlyosDownloadsList extends WP_List_Table {

    /** Class constructor */
	public function __construct() {

		parent::__construct( [
			'singular' => __('Download', 'olyos-livre-blanc'), //singular name of the listed records
			'plural'   => __('Downloads', 'olyos-livre-blanc'), //plural name of the listed records
			'ajax'     => false //should this table support ajax?
		] );

	}

    public static function get_participants($per_page = 5, $page_number = 1) {
        global $wpdb;

        if (!empty($_REQUEST['livreblanc_id'])) {
            // Only users for specific contests
            $sql = 'SELECT * FROM '. $wpdb->prefix .'olyos_livreblanc_download p';
            $sql .= ' LEFT JOIN '. $wpdb->prefix .'olyos_livreblanc_user u ON p.id_user = u.id';
            $sql .= ' WHERE p.id_livreblanc = ' . esc_sql($_REQUEST['livreblanc_id']);
        } else {
            // All registered users
            $sql = 'SELECT * , "_" AS `id_livreblanc`, "_" AS `subscribe_newsletter`, "_" AS `ip_address` FROM '. $wpdb->prefix .'olyos_livreblanc_user';
        }


        if (!empty($_REQUEST['orderby'])) {
            $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
            $sql .= !empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
        }

        $sql .= ' LIMIT ' . $per_page;
        $sql .= ' OFFSET ' . ($page_number - 1) * $per_page;

        $result = $wpdb->get_results($sql, 'ARRAY_A');

        return $result;
    }

    public static function record_count() {
        global $wpdb;
        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}olyos_livreblanc_user";
        return $wpdb->get_var($sql);
    }

    public function column_default($item, $column_name) {
        switch ($column_name) {
            case 'id':
            case 'id_livreblanc':
            case 'email':
            case 'subscribe_newsletter':
            case 'ip_address':
                return $item[$column_name];
            default:
                return print_r( $item, true ); //Show the whole array for troubleshooting purposes
        }
    }

    function get_columns() {
        $columns = [
            'id' => __('ID', 'olyos-livre-blanc'),
            'id_livreblanc' => __('White paper ID', 'olyos-livre-blanc'),
            'email' => __('Email', 'olyos-livre-blanc'),
            'subscribe_newsletter' => __('Newsletter', 'olyos-livre-blanc'),
            'ip_address' => __('IP', 'olyos-livre-blanc'),
        ];
        return $columns;
    }

    function get_sortable_columns() {
        $columns = [
            'id' => array('id', true),
            'id_livreblanc' => array('id_livreblanc', true),
        ];
        return $columns;
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

        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page'    => $per_page,
            "total_pages" => $total_pages
        ]);

        $this->items = self::get_participants($per_page, $current_page);
    }

}