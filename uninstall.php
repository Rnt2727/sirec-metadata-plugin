<?php
// Si WordPress no llama este archivo, salir
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Borrar la tabla de recursos educativos
global $wpdb;
$table_name = $wpdb->prefix . 'educational_resources';
$wpdb->query("DROP TABLE IF EXISTS $table_name");