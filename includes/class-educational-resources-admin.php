<?php
class Educational_Resources_Admin {
    public function add_admin_menu() {
        add_menu_page(
            __('Educational Resources', 'educational-resources'),
            __('Educational Resources', 'educational-resources'),
            'manage_options',
            'educational-resources',
            array($this, 'display_admin_page'),
            'dashicons-welcome-learn-more'
        );
    }
    
    public function display_admin_page() {
        include EDUCATIONAL_RESOURCES_PLUGIN_DIR . 'templates/admin-table.php';
    }
    
    public function enqueue_styles() {
        wp_enqueue_style('educational-resources-admin', 
            plugins_url('assets/css/style.css', dirname(__FILE__)));
    }
    
    public function enqueue_scripts() {
        wp_enqueue_script('educational-resources-admin',
            plugins_url('assets/js/script.js', dirname(__FILE__)));
    }
}