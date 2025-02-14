<?php
class ERM_Admin {
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
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

    public function enqueue_admin_scripts($hook) {
        // Solo cargar en la página de recursos educativos
        if('toplevel_page_educational-resources' !== $hook) {
            return;
        }

        wp_enqueue_script('jquery');
        
        // Localizar el script para AJAX
        wp_localize_script('jquery', 'ajax_object', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('update_resource')
        ));
    }
    
    public function display_admin_page() {
        include ERM_PLUGIN_DIR . 'templates/admin/resources-list.php';
    }
}