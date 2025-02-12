<?php
// Crear tablas durante la activación del plugin
function sirec_crear_tablas() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    // Tabla principal de recursos educativos
    $tabla_recursos = $wpdb->prefix . 'sirec_recursos';
    $sql = "CREATE TABLE IF NOT EXISTS $tabla_recursos (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        titulo varchar(255) NOT NULL,
        subtitulo varchar(255),
        categoria varchar(100) NOT NULL,
        autor varchar(255) NOT NULL,
        procedencia varchar(100),
        pais varchar(100),
        area_conocimiento text,
        area_conocimiento_otros text,
        descripcion text,
        fecha_publicacion date,
        fecha_actualizacion date,
        idioma varchar(50),
        secuencia_escolar text,
        denominacion_nivel_otros text,
        tipo_archivo varchar(50),
        formato_visual varchar(100),
        usuario_dirigido varchar(100),
        destrezas_habilidades text,
        licencia text,
        valoracion_cab int,
        sello_cab varchar(50),
        estado_revision varchar(50),
        fecha_creacion datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    // Tabla para competencias
    $tabla_competencias = $wpdb->prefix . 'sirec_competencias';
    $sql2 = "CREATE TABLE IF NOT EXISTS $tabla_competencias (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        recurso_id mediumint(9) NOT NULL,
        tipo_competencia varchar(100),
        dimension varchar(100),
        habilidad varchar(100),
        PRIMARY KEY  (id),
        FOREIGN KEY (recurso_id) REFERENCES $tabla_recursos(id)
    ) $charset_collate;";

    // Tabla para evaluación
    $tabla_evaluacion = $wpdb->prefix . 'sirec_evaluacion';
    $sql3 = "CREATE TABLE IF NOT EXISTS $tabla_evaluacion (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        recurso_id mediumint(9) NOT NULL,
        criterio_id int,
        calificacion varchar(50),
        observaciones text,
        evaluador_id bigint(20),
        fecha_evaluacion datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY  (id),
        FOREIGN KEY (recurso_id) REFERENCES $tabla_recursos(id),
        FOREIGN KEY (evaluador_id) REFERENCES {$wpdb->users}(ID)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    dbDelta($sql2);
    dbDelta($sql3);
}

// Función de desinstalación
function sirec_desinstalar() {
    global $wpdb;
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}sirec_evaluacion");
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}sirec_competencias");
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}sirec_recursos");
}