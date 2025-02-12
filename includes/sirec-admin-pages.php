<?php
// Función para mostrar la página principal
function sirec_pagina_principal() {
    if (!current_user_can('manage_options')) {
        wp_die('Acceso denegado');
    }

    $tabla = new Sirec_List_Table();
    $tabla->prepare_items();
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline">Recursos Educativos</h1>
        <a href="?page=sirec-nuevo-recurso" class="page-title-action">Añadir Nuevo</a>
        <hr class="wp-header-end">
        
        <form method="post">
            <?php
            $tabla->search_box('Buscar', 'search_id');
            $tabla->display();
            ?>
        </form>
    </div>
    <?php
}

// Función para mostrar el formulario de nuevo recurso
function sirec_nuevo_recurso() {
    if (!current_user_can('manage_options')) {
        wp_die('Acceso denegado');
    }

    // Procesar el formulario
    if (isset($_POST['submit_recurso'])) {
        global $wpdb;
        $tabla = $wpdb->prefix . 'sirec_recursos';

        $datos = array(
            'titulo' => sanitize_text_field($_POST['titulo']),
            'subtitulo' => sanitize_text_field($_POST['subtitulo']),
            'categoria' => sanitize_text_field($_POST['categoria']),
            'autor' => sanitize_text_field($_POST['autor']),
            'procedencia' => sanitize_text_field($_POST['procedencia']),
            'pais' => sanitize_text_field($_POST['pais']),
            'area_conocimiento' => sanitize_textarea_field($_POST['area_conocimiento']),
            'descripcion' => sanitize_textarea_field($_POST['descripcion']),
            'fecha_publicacion' => sanitize_text_field($_POST['fecha_publicacion']),
            'idioma' => sanitize_text_field($_POST['idioma']),
            'tipo_archivo' => sanitize_text_field($_POST['tipo_archivo']),
            'formato_visual' => sanitize_text_field($_POST['formato_visual']),
            'usuario_dirigido' => sanitize_text_field($_POST['usuario_dirigido']),
            'licencia' => sanitize_text_field($_POST['licencia']),
            'estado_revision' => 'pendiente'
        );

        $wpdb->insert($tabla, $datos);
        echo '<div class="notice notice-success"><p>Recurso guardado correctamente</p></div>';
    }

    // Mostrar el formulario
    ?>
    <div class="wrap">
        <h2>Nuevo Recurso Educativo</h2>
        <form method="post" action="">
            <table class="form-table">
                <tr>
                    <th><label for="titulo">Título</label></th>
                    <td><input type="text" name="titulo" id="titulo" class="regular-text" required></td>
                </tr>
                <tr>
                    <th><label for="subtitulo">Subtítulo</label></th>
                    <td><input type="text" name="subtitulo" id="subtitulo" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label for="categoria">Categoría</label></th>
                    <td>
                        <select name="categoria" id="categoria" required>
                            <option value="recurso_consulta">Recurso de Consulta</option>
                            <option value="recurso_apoyo">Recurso de apoyo para docentes</option>
                            <option value="recurso_didactico">Recurso didáctico complementario</option>
                            <option value="recurso_ludico">Recurso Lúdico</option>
                            <option value="recurso_trabajo">Recurso de trabajo para los estudiantes</option>
                        </select>
                    </td>
                </tr>
                <!-- Agregar más campos según los metadatos -->
            </table>
            <p class="submit">
                <input type="submit" name="submit_recurso" class="button-primary" value="Guardar Recurso">
            </p>
        </form>
    </div>
    <?php
}