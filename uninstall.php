<?php
// Si WordPress no llama este archivo, salir
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Incluir el archivo de la clase Database si es necesario
require_once plugin_dir_path(__FILE__) . 'includes/class-erm-database.php';

// Borrar la tabla de recursos educativos
global $wpdb;
$table_name = $wpdb->prefix . 'educational_resources';

// Asegurarse de que la tabla existe antes de intentar eliminarla
if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
    $wpdb->query("DROP TABLE IF EXISTS $table_name");
}

// Limpiar opciones relacionadas si existen
delete_option('erm_version');
delete_option('erm_db_version');

// Limpiar transients si existen
delete_transient('erm_updating');

// Limpiar capacidades de usuario si se agregaron
$role = get_role('administrator');
if ($role) {
    $role->remove_cap('manage_educational_resources');
}