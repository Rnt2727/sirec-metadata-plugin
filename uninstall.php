<?php
// Verificar si se llama desde WordPress
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

global $wpdb;

// Eliminar tablas
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}sirec_evaluacion");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}sirec_competencias");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}sirec_recursos");