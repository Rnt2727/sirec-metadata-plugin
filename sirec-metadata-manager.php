<?php
/*
Plugin Name: SIREC Metadata Manager
Description: Gestión de metadatos para recursos educativos del SIREC
Version: 1.0
Author: Tu Nombre
*/

// Prevenir acceso directo
if (!defined('ABSPATH')) exit;

// Incluir archivos necesarios
require_once plugin_dir_path(__FILE__) . 'includes/sirec-database.php';
require_once plugin_dir_path(__FILE__) . 'includes/sirec-functions.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-sirec-list-table.php';
require_once plugin_dir_path(__FILE__) . 'includes/sirec-admin-pages.php';

// Registrar hooks de activación y desinstalación
register_activation_hook(__FILE__, 'sirec_crear_tablas');
register_uninstall_hook(__FILE__, 'sirec_desinstalar');

// Agregar menú en el panel de administración
add_action('admin_menu', 'sirec_agregar_menu');

// Agregar estilos CSS
add_action('admin_enqueue_scripts', 'sirec_admin_styles');