<?php
// Agregar menú en el panel de administración
function sirec_agregar_menu() {
    add_menu_page(
        'SIREC Recursos',
        'SIREC Recursos',
        'manage_options',
        'sirec-recursos',
        'sirec_pagina_principal',
        'dashicons-book-alt'
    );

    add_submenu_page(
        'sirec-recursos',
        'Nuevo Recurso',
        'Nuevo Recurso',
        'manage_options',
        'sirec-nuevo-recurso',
        'sirec_nuevo_recurso'
    );

    add_submenu_page(
        'sirec-recursos',
        'Evaluación',
        'Evaluación',
        'manage_options',
        'sirec-evaluacion',
        'sirec_evaluacion'
    );
}

// Agregar estilos CSS
function sirec_admin_styles() {
    wp_enqueue_style('sirec-admin-css', plugin_dir_url(__FILE__) . '../assets/css/sirec-admin.css');
}