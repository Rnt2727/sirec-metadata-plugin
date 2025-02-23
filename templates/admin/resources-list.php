<?php
$current_user = wp_get_current_user();
$user_roles = $current_user->roles;

// Inicializar $resources como array vacío por defecto
$resources = array();

try {
    $db = new ERM_Database();

    // Determinar si es catalogador o evaluador
    $is_catalogator = in_array('catalogator', $user_roles);
    $is_evaluator = in_array('evaluator', $user_roles);
    $is_administrator = in_array('administrator', $user_roles);

    if (!$is_catalogator && !$is_evaluator && !$is_administrator) {
        wp_die('No tienes permisos para acceder a esta página.');
    }

    // Obtener recursos según el rol
    if ($is_administrator) {
        $resources = $db->get_resources_with_min_score(81);
    } elseif ($is_catalogator) {
        $resources = $db->get_resources();
    } elseif ($is_evaluator) {
        $resources = $db->get_approved_resources();
    }

} catch (Exception $e) {
    error_log('Error en resources-list.php: ' . $e->getMessage());
}

// Asegurar que $resources sea un array
$resources = is_array($resources) ? $resources : array();
?>

<!-- Include Tutor LMS styles -->
<link rel="stylesheet" href="<?php echo plugins_url('tutor/assets/css/tutor.min.css'); ?>">

<style>
:root {
    --tutor-primary-color: #3E64DE;
    --tutor-primary-hover-color: #395BCA;
    --tutor-text-color: #212327;
    --tutor-border-color: #E3E5EB;
    --tutor-table-border: #E3E5EB;
    --tutor-body-color: #5B616F;
}

.tutor-table {
    width: 100%;
    font-size: 14px;
    border-collapse: separate;
    border-spacing: 0;
    background: #fff;
    border: 1px solid var(--tutor-table-border);
    border-radius: 4px;
}

.tutor-table th {
    font-weight: 500;
    background: #F7F9FA;
    padding: 15px 20px;
    border-bottom: 1px solid var(--tutor-table-border);
    color: var(--tutor-text-color);
    text-align: left;
}

.tutor-table td {
    padding: 15px 20px;
    border-bottom: 1px solid var(--tutor-table-border);
    color: var(--tutor-body-color);
}

.tutor-table tr:last-child td {
    border-bottom: none;
}

.tutor-badge-outline-primary {
    color: var(--tutor-primary-color);
    background: rgba(62, 100, 222, 0.1);
    border: 1px solid var(--tutor-primary-color);
    padding: 4px 8px;
    font-size: 12px;
    border-radius: 4px;
    display: inline-block;
}

.tutor-btn {
    display: inline-flex;
    align-items: center;
    padding: 8px 16px;
    border-radius: 4px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    border: 1px solid transparent;
    transition: all 0.3s ease;
}

.tutor-btn-outline-primary {
    color: var(--tutor-primary-color);
    border-color: var(--tutor-primary-color);
    background: transparent;
}

.tutor-btn-outline-primary:hover {
    color: #fff;
    background: var(--tutor-primary-hover-color);
    border-color: var(--tutor-primary-hover-color);
}

.tutor-btn-sm {
    padding: 4px 12px;
    font-size: 13px;
}

.tutor-form-select {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid var(--tutor-border-color);
    border-radius: 4px;
    background-color: #fff;
    font-size: 14px;
    color: var(--tutor-text-color);
}

.description-content {
    max-height: 50px;
    overflow: hidden;
    position: relative;
}

.description-content.expanded {
    max-height: none;
}

.toggle-description {
    margin-top: 5px;
    font-size: 12px !important;
    padding: 2px 8px !important;
}

@media (max-width: 768px) {
    .tutor-table-responsive {
        overflow-x: auto;
    }
    
    .tutor-table th,
    .tutor-table td {
        min-width: 200px;
    }
}
.table-outer-wrapper {
    position: relative;
    width: 100%;
    overflow: hidden;
    background: #fff;
    border-radius: 4px;
    border: 1px solid var(--tutor-table-border);
}

.tutor-table-responsive {
    position: relative;
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    margin: 0;
    border: none;
}

.tutor-table {
    margin: 0;
    border: none;
    min-width: 100%;
    width: max-content;
}

/* Personalizar la barra de desplazamiento */
.tutor-table-responsive::-webkit-scrollbar {
    height: 8px;
}

.tutor-table-responsive::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.tutor-table-responsive::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

.tutor-table-responsive::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Asegurar que las columnas tengan un ancho mínimo */
.tutor-table th,
.tutor-table td {
    white-space: nowrap;
    min-width: 120px;
}

/* Ajustar el ancho de la columna de descripción */
.tutor-table th[width="15%"],
.tutor-table td:nth-child(11) {
    min-width: 200px;
    max-width: 300px;
    white-space: normal;
}

/* Mantener la columna de acciones visible */
.tutor-table td:last-child,
.tutor-table th:last-child {
    position: sticky;
    right: 0;
    background: inherit;
    border-left: 1px solid var(--tutor-table-border);
}

.tutor-table th:last-child {
    background: #F7F9FA;
}

.tutor-table td:last-child {
    background: #fff;
}

.editable-field {
    width: 100%;
    padding: 5px;
    border: 1px solid transparent;
    background: transparent;
}

.editable-field:not([readonly]):not([disabled]) {
    border-color: var(--tutor-border-color);
    background: #fff;
}

.edit-btn, .save-btn, .cancel-btn {
    padding: 4px 8px !important;
    margin: 2px !important;
}

.description-content textarea {
    min-height: 100px;
    resize: vertical;
}
/* Estilos para el modo de edición */
.editable-field {
    width: 100%;
    padding: 8px;
    border: 1px solid var(--tutor-border-color);
    border-radius: 4px;
    background-color: #fff;
    font-size: inherit;
    color: inherit;
    transition: all 0.3s ease;
}

/* Estilos para la fila en modo edición */
tr.editing {
    background-color: rgba(62, 100, 222, 0.05);
}

tr.editing td {
    background-color: transparent;
}

/* Estilos para los botones de acción */
.edit-actions {
    display: flex;
    gap: 8px;
}

.edit-actions button {
    padding: 6px 12px;
    font-size: 13px;
}

/* Animaciones */
.editable-field, .display-value {
    transition: all 0.3s ease;
}

/**modal evaluate */
.tutor-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1000;
}

.tutor-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
}

.tutor-modal-window {
    position: relative;
    width: 90%;
    max-width: 800px;
    max-height: 90vh;
    margin: 20px auto;
    background: white;
    border-radius: 8px;
    overflow-y: auto;
}

.tutor-modal-content {
    padding: 20px;
}

.tutor-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
}

.tutor-modal-close {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
}

.evaluation-item {
    margin-bottom: 15px;
    padding: 10px;
    border-bottom: 1px solid #eee;
}

.evaluation-item select {
    width: 100px;
    padding: 5px;
    margin-top: 5px;
}

@media (max-width: 768px) {
    .table-outer-wrapper {
        margin: 0 -20px;
        width: calc(100% + 40px);
        border-left: none;
        border-right: none;
        border-radius: 0;
    }
    
    .tutor-table {
        min-width: 1500px;
    }
}
</style>

<div class="tutor-wrap tutor-dashboard tutor-dashboard-content">
    <div class="tutor-fs-5 tutor-fw-medium tutor-color-black tutor-mb-24">
        <?php 
        if ($is_catalogator) {
            echo esc_html('Panel de Catalogador - Recursos Educativos');
        } elseif ($is_evaluator) {
            echo esc_html('Panel de Evaluador - Recursos Educativos Aprobados');
        } else {
            echo esc_html('Panel de Administrador - Recursos Educativos');
        }
        ?>
    </div>

    <!-- Filter Section -->
    <div class="tutor-row tutor-mb-32">
        <div class="tutor-col-lg-6 tutor-mb-16 tutor-mb-lg-0">
            <label class="tutor-form-label">
                <?php esc_html_e('Filtrar por categoría', 'tutor'); ?>
            </label>
            <select class="tutor-form-select tutor-form-control" data-search="no">
                <option value=""><?php esc_html_e('Todas las categorías', 'tutor'); ?></option>
                <option value="RC"><?php esc_html_e('R.C. Recursos de consulta', 'tutor'); ?></option>
                <option value="RAD"><?php esc_html_e('R.A.D. Recursos de Apoyo para Docentes', 'tutor'); ?></option>
                <option value="RDC"><?php esc_html_e('R.D.C. Recursos Didácticos Complementarios', 'tutor'); ?></option>
                <option value="RL"><?php esc_html_e('R.L. Recursos Lúdicos', 'tutor'); ?></option>
                <option value="RTE"><?php esc_html_e('R.T.E. Recursos de Trabajo para el Estudiante', 'tutor'); ?></option>
            </select>
        </div>
    </div>












    <div class="table-outer-wrapper">
        <div class="tutor-table-responsive">
            <table class="tutor-table tutor-table-middle">
                <thead>
                    <tr>
                        <th width="5%"><?php esc_html_e('ID', 'tutor'); ?></th>
                        <th width="15%"><?php esc_html_e('Título', 'tutor'); ?></th>
                        <th width="10%"><?php esc_html_e('Subtítulo', 'tutor'); ?></th>
                        <th width="8%"><?php esc_html_e('Categoría', 'tutor'); ?></th>
                        <th width="10%"><?php esc_html_e('Autor', 'tutor'); ?></th>
                        <th width="10%"><?php esc_html_e('Email del Autor', 'tutor'); ?></th>
                        <th width="8%"><?php esc_html_e('Procedencia', 'tutor'); ?></th>
                        <th width="8%"><?php esc_html_e('País', 'tutor'); ?></th>
                        <th width="10%"><?php esc_html_e('Área de Conocimiento', 'tutor'); ?></th>
                        <th width="10%"><?php esc_html_e('Áreas en Otros Países', 'tutor'); ?></th>
                        <th width="15%"><?php esc_html_e('Descripción', 'tutor'); ?></th>
                        <th width="10%"><?php esc_html_e('Fecha de Publicación', 'tutor'); ?></th>
                        <th width="8%"><?php esc_html_e('Idioma', 'tutor'); ?></th>
                        <th width="10%"><?php esc_html_e('Secuencia Escolar', 'tutor'); ?></th>
                        <th width="8%"><?php esc_html_e('Edad', 'tutor'); ?></th>
                        <th width="10%"><?php esc_html_e('Nivel en Otros Países', 'tutor'); ?></th>
                        <th width="8%"><?php esc_html_e('Tipo de Archivo', 'tutor'); ?></th>
                        <th width="10%"><?php esc_html_e('Archivo', 'tutor'); ?></th>
                        <th width="10%"><?php esc_html_e('Licencia', 'tutor'); ?></th>
                        <th width="8%"><?php esc_html_e('Formato Visual', 'tutor'); ?></th>
                        <th width="10%"><?php esc_html_e('Usuario Destinatario', 'tutor'); ?></th>
                        <th width="15%"><?php esc_html_e('Habilidades y Competencias', 'tutor'); ?></th>
                        <th width="8%"><?php esc_html_e('Sello CAB', 'tutor'); ?></th>

                        <th width="8%"><?php esc_html_e('Estado', 'tutor'); ?></th>
                        <th width="15%"><?php esc_html_e('Razón de Rechazo', 'tutor'); ?></th>
                        <th width="8%"><?php esc_html_e('Puntuación', 'tutor'); ?></th>
                        <th width="10%"><?php esc_html_e('Fecha de Envío', 'tutor'); ?></th>

                        <th width="10%"><?php esc_html_e('Acciones', 'tutor'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($resources)): ?>
                        <?php foreach ($resources as $resource): ?>
                            <tr data-resource-id="<?php echo esc_attr($resource->id); ?>">
                                <td>
                                    <span class="tutor-text-regular-body tutor-color-black">
                                        <?php echo esc_html($resource->id); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="display-value tutor-fs-6 tutor-fw-medium">
                                        <?php echo esc_html($resource->title); ?>
                                    </span>
                                    <input type="text" class="editable-field tutor-fs-6 tutor-fw-medium" name="title" 
                                        value="<?php echo esc_attr($resource->title); ?>" style="display: none;">
                                </td>
                                <td>
                                    <span class="display-value tutor-fs-7">
                                        <?php echo esc_html($resource->subtitle); ?>
                                    </span>
                                    <input type="text" class="editable-field tutor-fs-7" name="subtitle" 
                                        value="<?php echo esc_attr($resource->subtitle); ?>" style="display: none;">
                                </td>
                                <td>
                                    <span class="display-value tutor-badge-outline-primary">
                                        <?php echo esc_html($resource->category); ?>
                                    </span>
                                    <select class="editable-field tutor-badge-outline-primary" name="category" style="display: none;">
                                        <option value="RC" <?php selected($resource->category, 'RC'); ?>>R.C.</option>
                                        <option value="RAD" <?php selected($resource->category, 'RAD'); ?>>R.A.D.</option>
                                        <option value="RDC" <?php selected($resource->category, 'RDC'); ?>>R.D.C.</option>
                                        <option value="RL" <?php selected($resource->category, 'RL'); ?>>R.L.</option>
                                        <option value="RTE" <?php selected($resource->category, 'RTE'); ?>>R.T.E.</option>
                                    </select>
                                </td>
                                <td>
                                    <span class="display-value tutor-fs-7">
                                        <?php echo esc_html($resource->author); ?>
                                    </span>
                                    <input type="text" class="editable-field tutor-fs-7" name="author" 
                                        value="<?php echo esc_attr($resource->author); ?>" style="display: none;">
                                </td>
                                <td>
                                    <span class="display-value tutor-fs-7">
                                        <?php echo esc_html($resource->author_email); ?>
                                    </span>
                                    <input type="email" class="editable-field tutor-fs-7" name="author_email" 
                                        value="<?php echo esc_attr($resource->author_email); ?>" style="display: none;">
                                </td>
                                <td>
                                    <span class="display-value tutor-fs-7">
                                        <?php echo esc_html($resource->origin); ?>
                                    </span>
                                    <input type="text" class="editable-field tutor-fs-7" name="origin" 
                                        value="<?php echo esc_attr($resource->origin); ?>" style="display: none;">
                                </td>
                                <td>
                                    <span class="display-value tutor-fs-7">
                                        <?php echo esc_html($resource->country); ?>
                                    </span>
                                    <input type="text" class="editable-field tutor-fs-7" name="country" 
                                        value="<?php echo esc_attr($resource->country); ?>" style="display: none;">
                                </td>
                                <td>
                                    <span class="display-value tutor-fs-7">
                                        <?php echo esc_html($resource->knowledge_area); ?>
                                    </span>
                                    <input type="text" class="editable-field tutor-fs-7" name="knowledge_area" 
                                        value="<?php echo esc_attr($resource->knowledge_area); ?>" style="display: none;">
                                </td>
                                <td>
                                    <span class="display-value tutor-fs-7">
                                        <?php echo esc_html($resource->knowledge_area_other_countries); ?>
                                    </span>
                                    <input type="text" class="editable-field tutor-fs-7" name="knowledge_area_other_countries" 
                                        value="<?php echo esc_attr($resource->knowledge_area_other_countries); ?>" style="display: none;">
                                </td>
                                <td>
                                    <div class="display-value tutor-fs-7 description-content">
                                        <?php echo esc_html($resource->description); ?>
                                        <button class="tutor-btn tutor-btn-outline-primary tutor-btn-sm toggle-description">
                                            <?php esc_html_e('Ver más', 'tutor'); ?>
                                        </button>
                                    </div>
                                    <textarea class="editable-field tutor-fs-7" name="description" style="display: none;">
                                        <?php echo esc_textarea($resource->description); ?>
                                    </textarea>
                                </td>
                                <td>
                                    <span class="display-value tutor-fs-7">
                                        <?php echo esc_html($resource->publication_date); ?>
                                    </span>
                                    <input type="date" class="editable-field tutor-fs-7" name="publication_date" 
                                        value="<?php echo esc_attr($resource->publication_date); ?>" style="display: none;">
                                </td>
                                <td>
                                    <span class="display-value tutor-fs-7">
                                        <?php echo esc_html($resource->language); ?>
                                    </span>
                                    <input type="text" class="editable-field tutor-fs-7" name="language" 
                                        value="<?php echo esc_attr($resource->language); ?>" style="display: none;">
                                </td>
                                <td>
                                    <span class="display-value tutor-fs-7">
                                        <?php echo esc_html($resource->school_sequence); ?>
                                    </span>
                                    <input type="text" class="editable-field tutor-fs-7" name="school_sequence" 
                                        value="<?php echo esc_attr($resource->school_sequence); ?>" style="display: none;">
                                </td>
                                <td>
                                    <span class="display-value tutor-fs-7">
                                        <?php echo esc_html($resource->age); ?>
                                    </span>
                                    <input type="text" class="editable-field tutor-fs-7" name="age" 
                                        value="<?php echo esc_attr($resource->age); ?>" style="display: none;">
                                </td>
                                <td>
                                    <span class="display-value tutor-fs-7">
                                        <?php echo esc_html($resource->level_other_countries); ?>
                                    </span>
                                    <input type="text" class="editable-field tutor-fs-7" name="level_other_countries" 
                                        value="<?php echo esc_attr($resource->level_other_countries); ?>" style="display: none;">
                                </td>
                                <td>
                                    <span class="display-value tutor-fs-7">
                                        <?php echo esc_html($resource->file_type); ?>
                                    </span>
                                    <input type="text" class="editable-field tutor-fs-7" name="file_type" 
                                        value="<?php echo esc_attr($resource->file_type); ?>" style="display: none;">
                                </td>
                                <td>
                                    <?php if (!empty($resource->file_url)): ?>
                                        <a href="<?php echo esc_url($resource->file_url); ?>" 
                                        target="_blank" 
                                        class="tutor-btn tutor-btn-outline-primary tutor-btn-sm">
                                            <span class="dashicons dashicons-download"></span>
                                            Descargar <?php echo esc_html(strtoupper($resource->file_type)); ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="tutor-badge-outline-warning">Sin archivo</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <span class="display-value tutor-fs-7">
                                        <?php echo esc_html($resource->license); ?>
                                    </span>
                                    <select class="editable-field tutor-fs-7" name="license" style="display: none;">
                                        <option value="CC BY" <?php selected($resource->license, 'CC BY'); ?>>CC BY - Atribución</option>
                                        <option value="CC BY-SA" <?php selected($resource->license, 'CC BY-SA'); ?>>CC BY-SA - Atribución-CompartirIgual</option>
                                        <option value="CC BY-ND" <?php selected($resource->license, 'CC BY-ND'); ?>>CC BY-ND - Atribución-SinDerivadas</option>
                                        <option value="CC BY-NC" <?php selected($resource->license, 'CC BY-NC'); ?>>CC BY-NC - Atribución-NoComercial</option>
                                        <option value="CC BY-NC-SA" <?php selected($resource->license, 'CC BY-NC-SA'); ?>>CC BY-NC-SA - Atribución-NoComercial-CompartirIgual</option>
                                        <option value="CC BY-NC-ND" <?php selected($resource->license, 'CC BY-NC-ND'); ?>>CC BY-NC-ND - Atribución-NoComercial-SinDerivadas</option>
                                        <option value="GPL" <?php selected($resource->license, 'GPL'); ?>>GPL</option>
                                        <option value="MIT" <?php selected($resource->license, 'MIT'); ?>>MIT</option>
                                        <option value="Apache" <?php selected($resource->license, 'Apache'); ?>>Apache</option>
                                        <option value="Public Domain" <?php selected($resource->license, 'Public Domain'); ?>>Dominio Público</option>
                                    </select>
                                </td>
                                <td>
                                    <span class="display-value tutor-fs-7">
                                        <?php echo esc_html($resource->visual_format); ?>
                                    </span>
                                    <input type="text" class="editable-field tutor-fs-7" name="visual_format" 
                                        value="<?php echo esc_attr($resource->visual_format); ?>" style="display: none;">
                                </td>
                                <td>
                                    <span class="display-value tutor-fs-7">
                                        <?php echo esc_html($resource->target_user); ?>
                                    </span>
                                    <input type="text" class="editable-field tutor-fs-7" name="target_user" 
                                        value="<?php echo esc_attr($resource->target_user); ?>" style="display: none;">
                                </td>
                                <td>
                                    <span class="display-value tutor-fs-7">
                                        <?php echo esc_html($resource->skills_competencies); ?>
                                    </span>
                                    <input type="text" class="editable-field tutor-fs-7" name="skills_competencies" 
                                        value="<?php echo esc_attr($resource->skills_competencies); ?>" style="display: none;">
                                </td>
                               
                               
                                <td>
                                    <span class="display-value tutor-fs-7">
                                        <?php echo esc_html($resource->cab_seal); ?>
                                    </span>
                                    <input type="text" class="editable-field tutor-fs-7" name="cab_seal" 
                                        value="<?php echo esc_attr($resource->cab_seal); ?>" style="display: none;">
                                </td>
                                <!-- Estado -->
                                <td>
                                    <?php if (isset($resource->approved_by_catalogator)): ?>
                                        <span class="tutor-badge-outline-<?php echo $resource->approved_by_catalogator ? 'success' : 'danger'; ?>">
                                            <?php echo $resource->approved_by_catalogator ? 'Aprobado' : 'Rechazado'; ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="tutor-badge-outline-warning">Pendiente</span>
                                    <?php endif; ?>
                                </td>

                                <!-- Razón de Rechazo -->
                                <td>
                                    <span class="display-value tutor-fs-7">
                                        <?php echo esc_html($resource->rejection_reason); ?>
                                    </span>
                                    <input type="text" class="editable-field tutor-fs-7" name="rejection_reason" 
                                        value="<?php echo esc_attr($resource->rejection_reason); ?>" style="display: none;">
                                </td>

                                <!-- Puntuación -->
                                <td>
                                    <span class="display-value tutor-fs-7">
                                        <?php echo $resource->evaluation_score ? number_format($resource->evaluation_score, 2) : '-'; ?>
                                    </span>
                                </td>

                                <!-- Fecha de Envío -->
                                <td>
                                    <span class="display-value tutor-fs-7">
                                        <?php echo esc_html($resource->submission_date); ?>
                                    </span>
                                </td>
                                <td>
                                        <div class="tutor-d-flex tutor-align-center tutor-gap-1">
                                            <?php if ($is_administrator): ?>
                                                <!-- Botón de DSpace solo para administradores -->
                                                <button type="button" 
                                                        class="tutor-btn tutor-btn-outline-primary tutor-btn-sm dspace-upload-btn"
                                                        data-resource-id="<?php echo esc_attr($resource->id); ?>"
                                                        data-title="<?php echo esc_attr($resource->title); ?>"
                                                        data-description="<?php echo esc_attr($resource->description); ?>"
                                                        data-file-url="<?php echo esc_attr($resource->file_url); ?>"
                                                        data-publication-date="<?php echo esc_attr($resource->publication_date); ?>"
                                                        data-author="<?php echo esc_attr($resource->author); ?>">
                                                    <span class="dashicons dashicons-upload"></span>
                                                    Subir a DSpace
                                                </button>
                                            <?php endif; ?>
                                        
                                            <?php if ($is_catalogator): ?>
                                                <!-- Mostrar botones de aprobar/rechazar solo para catalogadores -->
                                                <?php if (!isset($resource->approved_by_catalogator) || $resource->approved_by_catalogator === null): ?>
                                                    <div class="approval-buttons">
                                                        <button type="button" class="tutor-btn tutor-btn-success tutor-btn-sm approve-btn" 
                                                                data-resource-id="<?php echo esc_attr($resource->id); ?>"
                                                                data-author-email="<?php echo esc_attr($resource->author_email); ?>">
                                                            Aprobar
                                                        </button>
                                                        <button type="button" class="tutor-btn tutor-btn-danger tutor-btn-sm reject-btn" 
                                                                data-resource-id="<?php echo esc_attr($resource->id); ?>"
                                                                data-author-email="<?php echo esc_attr($resource->author_email); ?>">
                                                            Rechazar
                                                        </button>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <!-- Botones de edición solo para catalogadores -->
                                                <div class="edit-actions-container">
                                                    <button type="button" class="tutor-btn tutor-btn-outline-primary tutor-btn-sm edit-btn">
                                                        <span class="tutor-icon-edit"></span>
                                                        <span>Editar</span>
                                                    </button>
                                                    <div class="edit-actions" style="display: none;">
                                                        <button type="button" class="tutor-btn tutor-btn-primary tutor-btn-sm save-btn">
                                                            <span>Guardar</span>
                                                        </button>
                                                        <button type="button" class="tutor-btn tutor-btn-outline-primary tutor-btn-sm cancel-btn">
                                                            <span>Cancelar</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            <?php elseif ($is_evaluator): ?>
                                                <button type="button" class="tutor-btn tutor-btn-primary tutor-btn-sm evaluate-btn"
                                                        data-resource-id="<?php echo esc_attr($resource->id); ?>"
                                                        data-category="<?php echo esc_attr($resource->category); ?>">
                                                    Evaluar
                                                </button>
                                            
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                                                    
                            </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="30">
                                    <div class="tutor-empty-state td-empty-state" style="padding: 30px; text-align: center;">
                                        <div class="tutor-fs-6 tutor-color-secondary tutor-text-center">
                                            <?php esc_html_e('No hay recursos disponibles.', 'tutor'); ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="evaluation-modal" class="tutor-modal" style="display: none;">
    <div class="tutor-modal-overlay"></div>
    <div class="tutor-modal-window">
        <div class="tutor-modal-content">
            <div class="tutor-modal-header">
                <h3 class="tutor-fs-5 tutor-fw-medium tutor-color-black">
                    Evaluación del Recurso Educativo
                </h3>
                <button class="tutor-modal-close">×</button>
            </div>

            <div class="tutor-modal-body">
                <form id="evaluation-form">
                    <?php wp_nonce_field('submit_evaluation', 'evaluation_nonce'); ?>
                    <input type="hidden" id="resource-id-eval" name="resource_id">
                    <input type="hidden" id="resource-category" name="resource_category">

                    <?php
                    // Definir criterios por categoría
                    $criteria_matrix = array(
                        "Evidencia un propósito educativo e intencionalidad o finalidad pedagógica, tomando en cuenta el nivel y el área en el que se presentan." => ['NA','I','I','V','V'],
                        "El objetivo del RE es fácil de identificar, está formulado de manera clara y comprensible para el usuario." => ['NA', 'I', 'I', 'V', 'NA'],
                        "Respeta las leyes de derechos de autor (uso de imágenes, videos, licencia abierta o de creative commons) y se cita o reconoce correctamente." => ['I', 'I', 'I', 'I', 'I'],
                        'El RE se puede contextualizar, adecuar, adaptar, reutilizar, integrar y/o compartir fácilmente con otros materiales.' => ['V', 'V', 'V', 'V', 'V'],
                        'El RE cumple con las leyes de privacidad y protección de datos aplicables.' => ['I', 'I', 'I', 'I', 'I'],
                        'Presenta sus referencias (fuentes de información) de acuerdo con las normas de citación y referencias bibliográficas (Normas APA, Chicago...), y/o proviene de fuentes confiables.' => ['I', 'I', 'I', 'V', 'V'],
                        'El contenido del RE está actualizado con base en fuentes confiables que muestran avances en la disciplina que aborda.' => ['I', 'V', 'I', 'NA', 'I'],
                        'El contenido del RE presenta afirmaciones, explicaciones, ideas, procedimientos, entre otros, en coherencia con las fuentes citadas.' => ['I', 'V', 'V', 'NA', 'NA'],
                        'El RE promueve la adaptación para usuarios con necesidades especiales.' => ['NA', 'V', 'V', 'NA', 'NA'],
                        'El RE permite al usuario elegir la forma en que interactúa con la diversidad de contenidos según su nivel de aprendizaje.' => ['V', 'NA', 'V', 'V', 'V'],
                        'Proporciona contenido abierto y accesible para una comunidad amplia de usuarios.' => ['V', 'V', 'V', 'V', 'V'],
                        'El RE es responsivo y permite visualización en diversas plataformas y dispositivos.' => ['I', 'I', 'I', 'I', 'I'],
                        'El RE promueve el aprendizaje significativo del estudiante (relaciona conceptos nuevos con los que ya conoce).' => ['V', 'NA', 'V', 'V', 'V'],
                        'Plantea herramientas que brindan a los usuarios la posibilidad de retomar sus conocimientos/saberes previos y experiencias para el desarrollo de sus aprendizajes.' => ['V', 'NA', 'V', 'V', 'V'],
                        'Plantea tareas/actividades o preguntas que activan los conocimientos/saberes previos relacionados con el/los propósito(s) de aprendizaje.' => ['V', 'NA', 'V', 'V', 'V'],
                        'Proporciona, a los usuarios, oportunidades de conocimientos/procedimientos/ideas aprendidas en situaciones prácticas y reales.' => ['V', 'V', 'V', 'V', 'V'],
                        'El RE promueve actitudes positivas y/o de reconocimiento por los logros de los usuarios.' => ['NA', 'NA', 'V', 'V', 'V'],
                        'El RE está orientado a mantener la atención y el interés del usuario.' => ['V', 'NA', 'V', 'I', 'V'],
                        'Cuenta con herramientas que promueven la gestión individual y compartida de los aprendizajes en pro de la resolución de problemas.' => ['NA', 'NA', 'V', 'NA', 'V'],
                        'Fomenta la creatividad e innovación para que el estudiante genere nuevas ideas y formas de aplicación.' => ['V', 'NA', 'V', 'V', 'V'],
                        'Orienta una secuencia de tareas/actividades que promueven el pensamiento crítico y/o creativo.' => ['V', 'NA', 'V', 'NA', 'V'],
                        'Proporciona al usuario el desarrollo de habilidades para tomar decisiones informadas y éticas.' => ['V', 'V', 'V', 'V', 'V'],
                        'Dispone de guías de uso, videos tutoriales y demás materiales que ayuden a los usuarios en el manejo del recurso.' => ['V', 'NA', 'V', 'I', 'V'],
                        'El RE está libre de publicidad y propaganda que evidencien conflicto de intereses.' => ['I', 'I', 'I', 'I', 'I']
                    );
                    ?>

<div id="criteria-container" class="evaluation-sections">
                        <?php foreach ($criteria_matrix as $criterion => $values): ?>
                            <div class="evaluation-section tutor-mb-32">
                                <h4 class="tutor-fs-6 tutor-fw-medium tutor-color-black tutor-mb-16">
                                    <?php echo esc_html($criterion); ?>
                                </h4>
                                <div class="evaluation-options">
                                    <label class="evaluation-checkbox">
                                        <input type="radio" name="criterion_<?php echo sanitize_key($criterion); ?>" value="0.25">
                                        <span class="checkmark"></span>
                                        <span class="label-text">Básico (0.25)</span>
                                    </label>
                                    <label class="evaluation-checkbox">
                                        <input type="radio" name="criterion_<?php echo sanitize_key($criterion); ?>" value="0.5">
                                        <span class="checkmark"></span>
                                        <span class="label-text">Intermedio (0.5)</span>
                                    </label>
                                    <label class="evaluation-checkbox">
                                        <input type="radio" name="criterion_<?php echo sanitize_key($criterion); ?>" value="1">
                                        <span class="checkmark"></span>
                                        <span class="label-text">Avanzado (1.0)</span>
                                    </label>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="tutor-d-flex tutor-justify-end tutor-mt-32">
                        <button type="submit" class="tutor-btn tutor-btn-primary">
                            Enviar Evaluación
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Función para cargar los criterios según la categoría
function loadCriteria(category) {
    const criteriaContainer = document.getElementById('criteria-container');
    const criteriaMatrixPHP = <?php echo json_encode($criteria_matrix); ?>;
    
    // Mapeo de categorías a índices
    const categoryIndex = {
        'RC': 0,
        'RAD': 1,
        'RDC': 2,
        'RL': 3,
        'RTE': 4
    };
    
    // Limpiar contenedor
    criteriaContainer.innerHTML = '';
    
    let index = 1;
    for (const [criterion, values] of Object.entries(criteriaMatrixPHP)) {
        const type = values[categoryIndex[category]];
        
        // Solo mostrar si es I o V
        if (type !== 'NA') {
            const criterionHtml = `
                <div class="evaluation-item">
                    <p><strong>${index}. ${criterion}</strong></p>
                    <p class="criterion-type">(${type === 'I' ? 'Indispensable' : 'Valorado'})</p>
                    <select name="criterion_${index - 1}" required>
                        <option value="">Seleccione un puntaje</option>
                        <option value="0.25">0.25</option>
                        <option value="0.50">0.50</option>
                        <option value="1.00">1.00</option>
                    </select>
                </div>
            `;
            criteriaContainer.innerHTML += criterionHtml;
            index++;
        }
    }
    
    if (criteriaContainer.innerHTML === '') {
        criteriaContainer.innerHTML = '<p>No hay criterios definidos para esta categoría.</p>';
    }
}

// Función para abrir el modal con la categoría correcta
function openEvaluationModal(resourceId, category) {
    document.getElementById('resource-id-eval').value = resourceId;
    document.getElementById('resource-category').value = category;
    document.getElementById('evaluation-modal').style.display = 'block';
    loadCriteria(category);
}
</script>

<script>
jQuery(document).ready(function($) {

    //DSpace upload buttom
    $('.dspace-upload-btn').on('click', function() {
            const resourceId = $(this).data('resource-id');
            const title = $(this).data('title');
            const description = $(this).data('description');
            const fileUrl = $(this).data('file-url');
            const publicationDate = $(this).data('publication-date');
            
            // Mostrar estado de carga
            $(this).prop('disabled', true).html('<span class="loading-spinner"></span>Subiendo...');
            
            $.ajax({
                url: ajax_object.ajax_url,
                type: 'POST',
                data: {
                    action: 'upload_to_dspace',
                    nonce: ajax_object.nonce,
                    resource_id: resourceId,
                    title: title,
                    description: description,
                    file_url: fileUrl,
                    publication_date: publicationDate
                },
                success: function(response) {
                    if(response.success) {
                        alert('Recurso subido exitosamente a DSpace');
                    } else {
                        alert('Error al subir a DSpace: ' + response.data);
                    }
                },
                error: function() {
                    alert('Error en la conexión');
                },
                complete: function() {
                    // Restaurar estado del botón
                    const $btn = $('.dspace-upload-btn[data-resource-id="' + resourceId + '"]');
                    $btn.prop('disabled', false).html('<span class="dashicons dashicons-upload"></span>Subir a DSpace');
                }
            });
        });

// Modal handling
$('.evaluate-btn').on('click', function() {
        const resourceId = $(this).data('resource-id');
        const category = $(this).data('category');
        $('#resource-id-eval').val(resourceId);
        $('#resource-category').val(category);
        $('#evaluation-modal').show();
    });


    $('.tutor-modal-close, .tutor-modal-overlay').on('click', function() {
        $('#evaluation-modal').hide();
    });

// Close modal when clicking outside
$(window).on('click', function(event) {
    if ($(event.target).hasClass('tutor-modal-overlay')) {
        $('#evaluation-modal').hide();
    }
});

// Handle evaluation form submission
$('#evaluation-form').on('submit', function(e) {
    e.preventDefault();
    
    // Recolectar datos de evaluación de los radio buttons
    var evaluationData = {};
    $(this).find('input[type="radio"]:checked').each(function() {
        evaluationData[$(this).attr('name')] = $(this).val();
    });
    
    $.ajax({
        url: ajax_object.ajax_url,
        type: 'POST',
        data: {
            action: 'save_evaluation',
            nonce: ajax_object.nonce,
            resource_id: $('#resource-id-eval').val(),
            evaluation_data: evaluationData
        },
        success: function(response) {
            if (response.success) {
                alert(response.data.message);
                $('#evaluation-modal').hide();
                location.reload();
            } else {
                alert(response.data || 'Error al guardar la evaluación');
            }
        },
        error: function(xhr, status, error) {
            alert('Error de conexión: ' + error);
        }
    });
});
    // Manejar el botón de editar
    $('.edit-btn').on('click', function() {
        var row = $(this).closest('tr');
        
        row.find('.display-value').hide();
        row.find('.editable-field').show();
        
        row.find('.edit-btn').hide();
        row.find('.edit-actions').show();
        
        row.addClass('editing');
    });

    // Manejar el botón de cancelar
    $('.cancel-btn').on('click', function() {
        var row = $(this).closest('tr');
        
        // Mostrar valores de visualización y ocultar campos de edición
        row.find('.display-value').show();
        row.find('.editable-field').hide();
        
        // Restaurar valores originales
        row.find('.editable-field').each(function() {
            $(this).val($(this).attr('value'));
        });
        
        // Cambiar botones
        row.find('.edit-btn').show();
        row.find('.edit-actions').hide();
        
        // Remover clase de edición
        row.removeClass('editing');
    });

    $('.approve-btn').on('click', function() {
    var resourceId = $(this).data('resource-id');
    var authorEmail = $(this).data('author-email');
    
    $.ajax({
        url: ajax_object.ajax_url,
        type: 'POST',
        data: {
            action: 'approve_resource',
            nonce: ajax_object.nonce,
            resource_id: resourceId,
            author_email: authorEmail
        },
        success: function(response) {
            if (response.success) {
                // Actualizar la interfaz
                var approvalButtons = $('[data-resource-id="' + resourceId + '"]').find('.approval-buttons');
                approvalButtons.replaceWith(
                    '<span class="tutor-badge-outline-success">Aprobado</span>'
                );
                alert('Recurso aprobado exitosamente');
            } else {
                alert('Error al aprobar el recurso');
            }
        },
        error: function(xhr, status, error) {
            alert('Error de conexión: ' + error);
        }
    });
});

$('.reject-btn').on('click', function() {
    var resourceId = $(this).data('resource-id');
    var authorEmail = $(this).data('author-email');
    
    var reason = prompt('Por favor, ingrese el motivo del rechazo:');
    if (reason === null) return; // Usuario canceló
    
    $.ajax({
        url: ajax_object.ajax_url,
        type: 'POST', 
        data: {
            action: 'reject_resource',
            nonce: ajax_object.nonce, 
            resource_id: resourceId,
            author_email: authorEmail,
            rejection_reason: reason
        },
        success: function(response) {
            if (response.success) {
                // Actualizar la interfaz
                var approvalButtons = $('[data-resource-id="' + resourceId + '"]').find('.approval-buttons');
                approvalButtons.replaceWith(
                    '<span class="tutor-badge-outline-danger">Rechazado</span>' +
                    '<i class="tutor-icon-info" title="' + reason + '"></i>'
                );
                alert('Recurso rechazado exitosamente');
            } else {
                alert('Error al rechazar el recurso');
            }
        },
        error: function(xhr, status, error) {
            alert('Error de conexión: ' + error);
        }
    });
});

    // Manejar el botón de guardar
    // Reemplaza el manejador del botón guardar con este código
$('.save-btn').on('click', function() {
    var row = $(this).closest('tr');
    var resourceId = row.data('resource-id');
    var data = {
        action: 'update_resource',
        nonce: ajax_object.nonce,
        resource_id: resourceId,
        resource_data: {}
    };

    // Recolectar datos
    row.find('.editable-field').each(function() {
        var field = $(this);
        var fieldName = field.attr('name');
        data.resource_data[fieldName] = field.val();
    });

    // Mostrar indicador de carga
    var saveBtn = $(this);
    saveBtn.prop('disabled', true).html('<span class="loading-spinner"></span>Guardando...');

    // Enviar AJAX
    $.ajax({
        url: ajax_object.ajax_url,
        type: 'POST',
        data: data,
        success: function(response) {
            if (response.success) {
                // Actualizar valores mostrados
                row.find('.editable-field').each(function() {
                    var field = $(this);
                    var fieldName = field.attr('name');
                    var displayField = row.find('.display-value').filter(function() {
                        return $(this).closest('td').find('.editable-field').attr('name') === fieldName;
                    });
                    
                    if (displayField.length) {
                        displayField.text(field.val());
                    }
                });

                // Restaurar vista
                row.find('.display-value').show();
                row.find('.editable-field').hide();
                row.find('.edit-btn').show();
                row.find('.edit-actions').hide();
                row.removeClass('editing');

                alert('Recurso actualizado exitosamente');
            } else {
                alert('Error al actualizar el recurso');
            }
        },
        error: function(xhr, status, error) {
            alert('Error de conexión: ' + error);
        },
        complete: function() {
            saveBtn.prop('disabled', false).html('<span>Guardar</span>');
        }
    });
});
});
</script>

