<?php
class ERM_Admin {
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
    }
    
    public function add_admin_menu() {
        add_menu_page(
            'Educational Resources',
            'Educational Resources',
            'manage_options',
            'educational-resources',
            array($this, 'display_admin_page'),
            'dashicons-welcome-learn-more'
        );
    }
    
    public function display_admin_page() {
        include ERM_PLUGIN_DIR . 'templates/admin/resources-list.php';
    }
}