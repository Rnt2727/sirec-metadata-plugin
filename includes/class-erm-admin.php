<?php
class ERM_Admin {
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }
    
    public function add_admin_menu() {
        // Obtener el usuario actual
        $current_user = wp_get_current_user();
        $user_roles = $current_user->roles;
    
        // Verificar si el usuario es catalogador o evaluador
        $is_catalogator = in_array('catalogator', $user_roles);
        $is_evaluator = in_array('evaluator', $user_roles);
    
        // Si el usuario es catalogador o evaluador, mostrar el menú
        if ($is_catalogator || $is_evaluator) {
            add_menu_page(
                'Educational Resources',
                'Educational Resources',
                'read', // Cambiar a 'read' para permitir acceso básico
                'educational-resources',
                array($this, 'display_admin_page'),
                'dashicons-welcome-learn-more'
            );
        }
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